<?php


class Handler { // Singleton должен гарантировать единовременное выполнение только одной операции
	
	static private $exchangeRate; // полученный из источника массив курсов валют
	static private $operation; // метод к исполнению
	// исключение возможности создания нескольких объектов класса
	static private $instance = NULL; // область памяти "под себя"
	private function __construct() {} 
	private function __clone() {}
	static public function getInstance() {
		if(self::$instance == NULL) {
			self::$instance = new Handler();
		}
		// self::insertExchangeRate(); // получить и добавить в дату данные о курсах валют из внещнего источника
		return self::$instance;
	}
	static public function setAction($action) { // инициировать и выполнить действие
		self::$operation = $action;
		self::execute();
	}	
	
	static public function insertExchangeRate() { // подучить и добавленить в базу данные о курсах валют
		self::$exchangeRate = new ExchangeRate();

//		foreach(self::$exchangeRate->valute as $key) {
//			$query = "INSERT INTO valute VALUES ('". implode("', '", $key) ."')";
//			if (!$result = $mysqli->query($query)) { exit ($mysqli->error); }
//		}
	}

	public function execute() {
		self::$operation->execute(); // запустить процесс
	}
}	
	
	
