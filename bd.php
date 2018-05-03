<?php

class BD  { // класс для работы с PDO

    static private $pdo = null;
    static private $instance = null;
    static private $dsn = 'mysql:host=localhost;dbname=mtstest';
    static private $name = 'root';
    static private $pass = '';

    private function __construct()  {}
    private function __clone() {}

    public static function setInstance()  {
        if (self::$instance == null) {
            self::$instance = new BD();
            self::$pdo = new PDO(self::$dsn, self::$name, self::$pass);
        }
        return self::$instance;
    }

    public static function select($query, $args = null)  {
        $stmt = self::$pdo->prepare($query);
        $stmt->execute($args);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function selectAll($query, $args = null)  {
        $stmt = self::$pdo->prepare($query);
        $stmt->execute($args);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function update($query, $args = null)  {
        $stmt = self::$pdo->prepare($query);
        $stmt->execute($args);
        return $stmt->rowCount();
    }

    public static function beginTransaction() {
        self::$pdo->beginTransaction();
    }

    public static function commit() {
        self::$pdo->commit();
    }

    public static function rollBack() {
        self::$pdo->rollBack();
    }
}
