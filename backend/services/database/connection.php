<?php

class Database
{
    private static $instance = null;

    private $connection;

    private function __construct()
    {
        $host = 'localhost';
        $name = 'alumni_album';
        $user = 'root';
        $password = '';

        $dsn = "mysdql:host=$host;dbname=$name;charset=UTF8";

        try {
            $this->connection = new PDO($dsn, $user, $password);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new Database();
        }

        return self::$instance;
    }

    public function getConnection() {
        return $this->connection;
    }
}
