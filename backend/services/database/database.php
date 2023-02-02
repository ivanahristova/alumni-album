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

    public function query(string $sql, string ...$params): PDOStatement {
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($params);

        return $stmt;
    }
}

function userExists(string $email): bool
{
    $database = Database::getInstance();
    $sql = "SELECT * FROM user WHERE email = ?";
    $stmt = $database->query($sql, $email);

    return $stmt->fetch();
}

function getRoleID(string $role): int|false
{
    $database = Database::getInstance();
    $sql = "SELECT id FROM role WHERE code = ?";
    $stmt = $database->query($sql, $role);

    return $stmt->fetch(PDO::FETCH_ASSOC)["id"];
}

function getProgrammeID(string $programme): int|false
{
    $database = Database::getInstance();
    $sql = "SELECT id FROM programme WHERE code = ?";
    $stmt = $database->query($sql, $programme);

    return $stmt->fetch(PDO::FETCH_ASSOC)["id"];
}

function getUserID(string $email): int|false
{
    $database = Database::getInstance();
    $sql = "SELECT id FROM user WHERE email = ?";
    $stmt = $database->query($sql, $email);

    return $stmt->fetch(PDO::FETCH_ASSOC)["id"];
}
