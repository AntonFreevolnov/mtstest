<?php

class ExchangeRate {

	public $valute;
	public function __construct() {
		header('Content-type: text/html; charset=utf-8');
		$curl = curl_init("http://www.cbr-xml-daily.ru/daily_json.js");
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$content = curl_exec($curl);
		curl_close($curl);
		$temp = json_decode($content, true);
		$this -> valute = $temp['Valute'];
	}
}