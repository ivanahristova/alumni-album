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

function getStudentByUserId($user_id): array
{
    $database = Database::getInstance();
    $query = "SELECT * FROM student WHERE user_id=?";
    $statement = $database->query($query, $user_id);

    return $statement->fetchAll(PDO::FETCH_ASSOC);
}

function getTeacherByUserId($user_id): array
{
    $database = Database::getInstance();
    $query = "SELECT * FROM teacher WHERE user_id=?";
    $statement = $database->query($query, $user_id);

    return $statement->fetchAll(PDO::FETCH_ASSOC);
}

function getClassStatistics(): array
{
    $database = Database::getInstance();
    $query = "SELECT class, COUNT(u.id) as count FROM user u JOIN student st ON st.user_id=u.id GROUP BY st.class";
    $statement = $database->query($query);

    return $statement->fetchAll(PDO::FETCH_ASSOC);
}

function getProgrammeStatistics(): array
{
    $database = Database::getInstance();
    $query = "SELECT pr.name, COUNT(u.id) as count FROM user u "
        . "	JOIN student st ON st.user_id=u.id "
        . "	JOIN programme pr ON pr.id=st.programme_id "
        . " GROUP BY pr.id";
    $statement = $database->query($query);

    return $statement->fetchAll(PDO::FETCH_ASSOC);
}

function getClassPhotosStatistics(): array
{
    $database = Database::getInstance();
    $query = "SELECT class, COUNT(ph.id) as count FROM photo ph GROUP BY ph.class";
    $statement = $database->query($query);

    return $statement->fetchAll(PDO::FETCH_ASSOC);
}

function getRoleCodeByRoleId(int $id): string|false
{
    $database = Database::getInstance();
    $sql = "SELECT code FROM role WHERE id = ?";
    $stmt = $database->query($sql, $id);

    return $stmt->fetch(PDO::FETCH_ASSOC)["code"];
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

function getProgrammeNameByCode(string $code): string
{
    $database = Database::getInstance();
    $sql = "SELECT * FROM programme WHERE code = ?";
    $statement = $database->query($sql, $code);

    return $statement->fetch(PDO::FETCH_ASSOC)["name"];
}

function getUserID(string $email): int|false
{
    $database = Database::getInstance();
    $sql = "SELECT id FROM user WHERE email = ?";
    $stmt = $database->query($sql, $email);

    return $stmt->fetch(PDO::FETCH_ASSOC)["id"];
}

function getProfile($table_name, $id): array|false
{
    $db = Database::getInstance();
    $sql = "SELECT * FROM $table_name WHERE user_id = ?";
    $stmt = $db->query($sql, $id);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function login($user_data): array|false
{
    $db = Database::getInstance();
    $sql = "SELECT * FROM user WHERE email = ?";
    $user = $db->query($sql, $user_data["email"])->fetch(PDO::FETCH_ASSOC);

    return $user && password_verify($user_data["password"], $user["password"]) ? $user : false;
}
