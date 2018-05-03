<?php

require_once("bd.php");
require_once("operation.php");
require_once("exchangeRate.php");
require_once("handler.php");
require_once("user.php");

Handler::getInstance(); // инстанцировали обработчик операций

$user1 = new User(1); // создание объектов
$user2 = new User(2);
$user3 = new User(3);
$user4 = new User(4);

$user2->setValute(840); // переопределена валюта
$user2->transfer(5, 3); // перевод средств
$user4->transfer(1, 1);
$user4->transfer(1, 3);



$user2->show();
//$user2->show();
//$user3->show();
//$user4->show();

?>