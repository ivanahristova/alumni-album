<?php

require_once '../services/database/database.php';

$programme_id = json_decode(file_get_contents('php://input'), true)["programme_id"];

if (!isset($programme_id)) {
    http_response_code(400);
    exit (json_encode(array("status" => "failure", "data" => []), JSON_UNESCAPED_UNICODE));
} else {
    $programme_name = getProgrammeNameById($programme_id);
    echo json_encode(array("status" => "success", "data" => $programme_name), JSON_UNESCAPED_UNICODE);
}
