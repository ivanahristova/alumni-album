<?php

class Database
{
    private static ?Database $instance = null;

    private PDO $connection;

    private function __construct()
    {
        $config = include_once __DIR__ . '/../../config/database.php';

        $driver = $config['DATABASE_DRIVER'];
        $host = $config['DATABASE_HOST'];
        $name = $config['DATABASE_NAME'];
        $username = $config['DATABASE_USERNAME'];
        $password = $config['DATABASE_PASSWORD'];
        $dsn = "$driver:host=$host;dbname=$name;charset=UTF8";

        $this->connection = new PDO($dsn, $username, $password);
    }

    public static function getInstance(): Database
    {
        if (self::$instance == null) {
            self::$instance = new Database();
        }

        return self::$instance;
    }
    public function getConnection(): PDO
    {
        return $this->connection;
    }
}
