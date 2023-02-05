<?php

require_once '../services/database/database.php';

session_start(["cookie_lifetime" => 3600]);

if (isset($_SESSION['user_id'], $_SESSION["role_id"])) {
    http_response_code(200);
    exit(json_encode(["status" => "success", "data" => "Успешен вход"], JSON_UNESCAPED_UNICODE));
}

$user_data = json_decode(file_get_contents("php://input"), true);

if (!$user_data || !isset($user_data["email"], $user_data["password"])) {
    http_response_code(400);
    exit(json_encode(["status" => "failure", "data" => "Невалидни данни"], JSON_UNESCAPED_UNICODE));
}

try {
    $user = login($user_data);

    if (!$user) {
        http_response_code(400);
        exit(json_encode(["status" => "failure", "data" => "Неуспешен вход"], JSON_UNESCAPED_UNICODE));
    }

    $_SESSION["user_id"] = $user["id"];
    $_SESSION["role_id"] = $user["role_id"];

    http_response_code(200);
    echo json_encode(["status" => "success", "data" => "Успешен вход"], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["status" => "failure", "data" => $e->getMessage()], JSON_UNESCAPED_UNICODE);
}
