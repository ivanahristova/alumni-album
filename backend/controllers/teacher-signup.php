<?php

require_once '../services/database/database.php';

class TeacherFormInput
{
    public string $email;
    public string $password;
    public string $first_name;
    public string $last_name;
    public string $title;

    public function __construct(string $email, string $password, string $first_name, string $last_name, string $title)
    {
        $this->email = $email;
        $this->password = $password;
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->title = $title;
    }
}

function teacherSignup(TeacherFormInput $input): void
{
    $password = password_hash($input->password, PASSWORD_DEFAULT);
    $role_id = getRoleID('teacher');

    $database = Database::getInstance();
    $sql = "INSERT INTO user (email, password, role_id) VALUES (?, ?, ?)";
    $connection = $database->getConnection();
    $connection->prepare($sql)->execute([$input->email, $password, $role_id]);

    $id = getUserID($input->email);

    $sql = "INSERT INTO teacher (first_name, last_name, user_id, title) VALUES (?, ?, ?, ?)";
    $stmt = $database->query($sql, $input->first_name, $input->last_name, $id, $input->title);
    $stmt->fetch();
}

$required = ["email", "password", "firstName", "lastName"];
$user_data = json_decode(file_get_contents("php://input"), true);

foreach ($required as $param) {
    if (!isset($user_data[$param])) {
        http_response_code(400);
        exit(json_encode(["status" => "failure", "data" => "Невалидни данни"], JSON_UNESCAPED_UNICODE));
    }
}

$input = new TeacherFormInput(
    $user_data["email"],
    $user_data["password"],
    $user_data["firstName"],
    $user_data["lastName"],
    $user_data["title"]
);

if (!filter_var($input->email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    exit(json_encode(["status" => "failure", "data" => "Невалидни данни"], JSON_UNESCAPED_UNICODE));
}

if (mb_strlen($input->password) < 8 || mb_strlen($input->password) > 64) {
    http_response_code(400);
    exit(json_encode(["status" => "failure", "data" => "Невалидни данни"], JSON_UNESCAPED_UNICODE));
}

if (mb_strlen($input->first_name) < 1 || mb_strlen($input->first_name) > 255) {
    http_response_code(400);
    exit(json_encode(["status" => "failure", "data" => "Невалидни данни"], JSON_UNESCAPED_UNICODE));
}

if (mb_strlen($input->last_name) < 1 || mb_strlen($input->last_name) > 255) {
    http_response_code(400);
    exit(json_encode(["status" => "failure", "data" => "Невалидни данни"], JSON_UNESCAPED_UNICODE));
}

if (userExists($input->email)) {
    http_response_code(400);
    exit(json_encode(["status" => "failure", "data" => "Имейлът е използван"], JSON_UNESCAPED_UNICODE));
}

try {
    teacherSignup($input);
    http_response_code(200);
    echo json_encode(["status" => "success", "data" => "Успешна регистрация"], JSON_UNESCAPED_UNICODE);
} catch (PDOException $ex) {
    http_response_code(400);
    echo json_encode(["status" => "success", "data" => "Неуспешна регистрация"], JSON_UNESCAPED_UNICODE);
}
