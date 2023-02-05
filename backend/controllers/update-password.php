<?php

require_once '../services/database/database.php';

function buildSQLQuery($attributes, $connection): string
{
    return "UPDATE user SET password = ? WHERE id=?";
}

function checkCorrectPassword($connection, $password, $user_id): bool
{
    $query = "SELECT password FROM user WHERE id=?";
    $statement = $connection->prepare($query);
    $statement->execute(array($user_id));
    $result = $statement->fetch(PDO::FETCH_ASSOC);

    return password_verify($password, $result["password"]);
}

$updated_attributes = json_decode(file_get_contents('php://input'), true);

if (!isset($updated_attributes)) {
    http_response_code(400);
    exit (json_encode(array("status" => "failure", "data" => "Неуспешна промяна на паролта"), JSON_UNESCAPED_UNICODE));
}

try {
    $db = Database::getInstance();
    $connection = $db->getConnection();
    $old_password = $updated_attributes['old-password'];
    $new_password = $updated_attributes['new-password'];

    session_start();
    $user_id = $_SESSION["user_id"];

    if (!checkCorrectPassword($connection, $old_password, $user_id)) {
        http_response_code(400);
        exit (json_encode(array("status" => "failure", "data" => "Неправилна парола"), JSON_UNESCAPED_UNICODE));
    } else {
        $sql_insert_query = buildSQLQuery($updated_attributes, $connection);
        $statement = $connection->prepare($sql_insert_query);
        $statement->execute(array(password_hash($new_password,PASSWORD_DEFAULT), $user_id));

        echo json_encode(array("status" => "success", "data" => "Успешна смяна на паролата"), JSON_UNESCAPED_UNICODE);
    }

} catch (PDOException $ex) {
    http_response_code(500);
    exit (json_encode(array("status" => "failure", "data" => $ex->getMessage()), JSON_UNESCAPED_UNICODE));
}
