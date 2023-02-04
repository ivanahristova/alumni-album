<?php

require_once '../services/database/database.php';
$updated_attributes = json_decode(file_get_contents('php://input'), true);

if (!isset($updated_attributes)) {
    http_response_code(400);
    exit (json_encode(array("status" => "failure", "data" => []), JSON_UNESCAPED_UNICODE));
}

try {
    $db = Database::getInstance();
    $connection = $db->getConnection();
    $entered_password = $updated_attributes['password'];
    $entered_email = $updated_attributes['email'];

    session_start();
    $user_id = $_SESSION["user_id"];

    if (!checkCorrectPassword($connection, $entered_password, $user_id)) {
        http_response_code(400);
        exit (json_encode(array("status" => "failure", "data" => "Неправилна парола!"), JSON_UNESCAPED_UNICODE));
    } else {
        $sql_insert_query = buildSQLQuery($updated_attributes, $connection);
        $statement = $connection->prepare($sql_insert_query);
        $statement->execute(array($entered_email, $user_id));
        // $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(array("status" => "success", "data" => []), JSON_UNESCAPED_UNICODE);
    }

} catch (PDOException $ex) {
    // echo $ex->getMessage();
    http_response_code(400);
    exit (json_encode(array("status" => "failure", "data" => $ex->getMessage()), JSON_UNESCAPED_UNICODE));
}

function buildSQLQuery($attributes, $connection): string
{
    return "UPDATE user SET email = ? WHERE id=?";
}

function checkCorrectPassword($connection, $password, $user_id): bool
{
    $query = "SELECT password FROM user WHERE id=?";
    $statement = $connection->prepare($query);
    $statement->execute(array($user_id));
    $result = $statement->fetch(PDO::FETCH_ASSOC);

    return password_verify($password, $result["password"]);
}
