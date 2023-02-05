<?php

require_once '../services/database/database.php';

function buildQuery($attributes): string
{
    $sql = "SELECT path FROM photo ph";

    if (isset($attributes["programme"])) {
        $sql .= " JOIN programme pr ON ph.programme_id = pr.id WHERE ph.class = ? AND pr.code = ?";
    } else {
        $sql .= " WHERE ph.class = ?";
    }

    if (isset($attributes["subclass"])) {
        $sql .= " AND ph.subclass = ?";
    }

    if (isset($attributes["studentGroup"])) {
        $sql .= " AND ph.student_group = ?";
    }

    return $sql;
}

$attributes = json_decode(file_get_contents('php://input'), true);

if (!isset($attributes)) {
    http_response_code(400);
    exit (json_encode(["status" => "failure", "data" => "Невалидна заявка"], JSON_UNESCAPED_UNICODE));
}

try {
    $db = Database::getInstance();
    $connection = $db->getConnection();

    $attributes = array_filter($attributes);
    $sql = buildQuery($attributes);
    $statement = $connection->prepare($sql);
    $statement->execute(array_values($attributes));
    $results = $statement->fetchAll(PDO::FETCH_ASSOC);

    http_response_code(200);
    echo json_encode(["status" => "success", "data" => $results], JSON_UNESCAPED_UNICODE);

} catch (PDOException $ex) {
    http_response_code(500);
    exit (json_encode(["status" => "failure", "data" => "Неуспешна заявка"], JSON_UNESCAPED_UNICODE));
}
