<?php

require_once '../services/database/database.php';

class StudentFormInput
{
    public string $email;
    public string $password;
    public string $first_name;
    public string $last_name;
    public string $faculty_number;
    public int $class;
    public string $programme;
    public int $subclass;
    public int $student_group;

    public function __construct(string $email, string $password, string $first_name, string $last_name, string $faculty_number, int $class, string $programme, int $subclass, int $student_group)
    {
        $this->email = $email;
        $this->password = $password;
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->faculty_number = $faculty_number;
        $this->class = $class;
        $this->programme = $programme;
        $this->subclass = $subclass;
        $this->student_group = $student_group;
    }
}

function studentSignup(StudentFormInput $input): void
{
    $password = password_hash($input->password, PASSWORD_DEFAULT);
    $role_id = getRoleID('student');

    $database = Database::getInstance();
    $sql = "INSERT INTO user (email, password, role_id) VALUES (?, ?, ?)";
    $connection = $database->getConnection();
    $connection->prepare($sql)->execute([$input->email, $password, $role_id]);

    $user_id = getUserID($input->email);
    $programme_id = getProgrammeID($input->programme);

    $sql = "INSERT INTO student (first_name, last_name, faculty_number, class, programme_id, user_id, subclass, student_group) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $database->query($sql, $input->first_name, $input->last_name, $input->faculty_number, $input->class, $programme_id, $user_id, $input->subclass, $input->student_group);
    $stmt->fetch();
}

$required = ["email", "password", "firstName", "lastName", "facultyNumber", "class", "programme", "subclass", "studentGroup"];
$programmes = ["Data Analytics", "Mathematics", "Applied Mathematics", "Statistics", "Informatics", "Information Systems", "Computer Science", "Software Engineering", "Mathematics and Informatics"];
$user_data = json_decode(file_get_contents("php://input"), true);

foreach ($required as $param) {
    if (!isset($user_data[$param])) {
        http_response_code(400);
        exit(json_encode(["status" => "failure", "data" => "Невалидни данни"], JSON_UNESCAPED_UNICODE));
    }
}

$input = new StudentFormInput(
    $user_data["email"],
    $user_data["password"],
    $user_data["firstName"],
    $user_data["lastName"],
    $user_data["facultyNumber"],
    intval($user_data["class"]),
    $user_data["programme"],
    intval($user_data["subclass"]),
    intval($user_data["studentGroup"])
);

$bulgarianAlphabet = '/^[А-Яа-я]+$/u';

if (!preg_match($bulgarianAlphabet, $input->first_name) || !preg_match($bulgarianAlphabet, $input->last_name)) {
    http_response_code(400);
    exit(json_encode(["status" => "failure", "data" => "Име и фамилия трябва да са на кирилица"], JSON_UNESCAPED_UNICODE));
}

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

if (mb_strlen($input->faculty_number) < 1 || mb_strlen($input->faculty_number) > 255) {
    http_response_code(400);
    exit(json_encode(["status" => "failure", "data" => "Невалидни данни"], JSON_UNESCAPED_UNICODE));
}

if ($input->class < 1888 || $input->class > date("Y")) {
    http_response_code(400);
    exit(json_encode(["status" => "failure", "data" => "Невалидни данни"], JSON_UNESCAPED_UNICODE));
}

if (!in_array($input->programme, $programmes)) {
    http_response_code(400);
    exit(json_encode(["status" => "failure", "data" => "Невалидни данни"], JSON_UNESCAPED_UNICODE));
}

if ($input->subclass < 1 || $input->subclass > 2) {
    http_response_code(400);
    exit(json_encode(["status" => "failure", "data" => "Невалидни данни"], JSON_UNESCAPED_UNICODE));
}

if ($input->student_group < 1 || $input->student_group > 8) {
    http_response_code(400);
    exit(json_encode(["status" => "failure", "data" => "Невалидни данни"], JSON_UNESCAPED_UNICODE));
}

if (userExists($input->email)) {
    http_response_code(400);
    exit(json_encode(["status" => "failure", "data" => "Съществува потребител с въведените данни"], JSON_UNESCAPED_UNICODE));
}

if (facultyNumberExists($input->faculty_number)) {
    http_response_code(400);
    exit(json_encode(["status" => "failure", "data" => "Съществува потребител с въведените данни"], JSON_UNESCAPED_UNICODE));
}

try {
    studentSignup($input);
    http_response_code(200);
    echo json_encode(["status" => "success", "data" => "Успешна регистрация"], JSON_UNESCAPED_UNICODE);
} catch (PDOException $ex) {
    http_response_code(400);
    echo json_encode(["status" => "success", "data" => "Неуспешна регистрация"], JSON_UNESCAPED_UNICODE);
}
