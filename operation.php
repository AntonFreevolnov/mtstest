<?php

abstract class Operation {

	protected $args; // массив аргументов

	public function __construct($array = null) {
		$this -> args = $array;     // определить свойства объекта
		Handler::setAction($this);  // добавить в Handler
	}	
	public function execute(){} //вызвать метод
}

class Journal extends Operation { // запись в журнал операций

    public function execute()  {
        $bd = BD::setInstance();
        $data=$bd::update("INSERT INTO operations 
            (id, senderId, receiverId, amount, date) 
            VALUES 
            (NULL, :senderId, :receiverId, :tranceSum, CURRENT_TIMESTAMP )",
            ['senderId' => $this->args['senderId'],
                'receiverId' => $this->args['receiverId'],
                'tranceSum' => $this->args['tranceSum']]);
        if($data != 1) {
            bd::rollBack(); // если не записалась операция прервать транзакцию
        }
    }
}
class Transfer extends Operation { // транзакция

    public function execute()  {

        $bd = BD::setInstance();
        $bd::beginTransaction(); //
        $data = $bd::select("SELECT 
            users.amount - :tranceSum * valute.Value / valute.Nominal as balance,  /* остаток после перевода */
            :tranceSum * valute.Value / valute.Nominal as tranceInRoubles         /* сумма перевода в рублях */
            FROM users, valute WHERE users.id = :senderId AND valute.NumCode = :valuteNumCode",
            ['tranceSum' => $this->args['tranceSum'],
                'senderId' => $this->args['senderId'],
                'valuteNumCode' => $this->args['valuteNumCode']]);
        if ($data['balance'] < 0) { /* если не хватает средств */
            return;
        }
        $tranceInRoubles = $data['tranceInRoubles'];
        $data = $bd::update("UPDATE users SET users.amount = CASE
            WHEN users.id = :senderId THEN users.amount -  :tranceSum
            WHEN users.id = :receiverId THEN users.amount + :tranceSum
            ELSE users.amount END",
            ['senderId' => $this->args['senderId'],
                'receiverId' => $this->args['receiverId'],
                'tranceSum' => $tranceInRoubles]);
        if ($data != 2) {
            return; /* если не обновились 2 ячейки */
        }
        new Journal(['tranceSum' => $tranceInRoubles,  /* запись в журнал */
            'senderId' => $this->args['senderId'],
            'receiverId' => $this->args['receiverId']]);
        $bd::commit(); // успешно
    }
}

class Show extends Operation  { // вывод данных пользователя на экран

    public function execute()   {

        $bd = BD::setInstance();
        $userData = $bd::select("SELECT 
            users.amount*valute.Nominal/valute.Value AS balance,
            users.name, valute.CharCode
            FROM users JOIN valute 
            WHERE users.id = :userId AND 
            valute.NumCode = :valuteNumCode",
            ['userId' => $this->args['userId'], 'valuteNumCode' => $this->args['valuteNumCode']]);




       echo json_encode($userData);

        $dataTable = $bd::selectAll("SELECT *
             FROM operations 
             WHERE operations.senderId = :userId OR operations.receiverId = :userId",
            ['userId' => $this->args['userId']]);
        print_r($this->args);
        echo json_encode($dataTable);
    }
}