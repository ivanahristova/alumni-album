<?php

require_once '../services/database/database.php';

$programme_code = json_decode(file_get_contents('php://input'), true)["programme_code"];

if (!isset($programme_code)) {
    http_response_code(400);
    exit (json_encode(array("status" => "failure", "data" => []), JSON_UNESCAPED_UNICODE));
} else {
    $programme_name = getProgrammeNameByCode($programme_code);
    echo json_encode(array("status" => "success", "data" => $programme_name), JSON_UNESCAPED_UNICODE);
}
