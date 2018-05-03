<?php



interface IUser {

    public function transfer($tranceSum, $receiverId); // отправить
    public function show(); // узнать состояние счета

}

class User implements IUser {

    private $id;
    private $valuteNumCode;

    public function __construct($id)    { // конструктор
        $this->id = $id;
        $this->valuteNumCode = 1; // валюта по умолчанию рубль
    }

    public function setValute($valuteNumCode)  { // задать валюту
        $this->valuteNumCode = $valuteNumCode;
    }

    public function transfer($tranceSum, $receiverId)   { // перевод денег
        new Transfer(['tranceSum' => $tranceSum,
            'senderId' => $this->id,
            'receiverId' => $receiverId,
            'valuteNumCode' => $this->valuteNumCode]);
    }
    public function show()   { // вывести данные пользователя
        new Show(['userId' => $this->id,
            'valuteNumCode' => $this->valuteNumCode]);
    }
}


