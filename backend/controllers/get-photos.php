<?php

require_once '../services/database/database.php';

if (!isset($_GET["offset"], $_GET["length"])) {
    http_response_code(400);
    exit(json_encode(["status" => "failure", "data" => "Невалидна заявка"], JSON_UNESCAPED_UNICODE));
}

$offset = $_GET["offset"];
$length = $_GET["length"];

try {
    $database = Database::getInstance();
    $connection = $database->getConnection();

    $sql = "SELECT * FROM photo ORDER BY id DESC";
    $stmt = $connection->prepare($sql);
    $stmt->execute();

    $result = array_slice($stmt->fetchAll(PDO::FETCH_ASSOC), $offset, $length);

    echo json_encode(["status" => "success", "data" => $result], JSON_UNESCAPED_UNICODE);

} catch (PDOException $ex) {
    http_response_code(500);
    echo json_encode(["status" => "failure", "data" => "Неуспешна заявка"], JSON_UNESCAPED_UNICODE);
}
