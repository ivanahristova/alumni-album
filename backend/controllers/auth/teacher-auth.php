<?php

require_once '../../services/database/database.php';

session_start();

if (!isset($_SESSION['user_id'], $_SESSION['role_id'])) {
    http_response_code(401);
    exit(json_encode(["status" => "failure", "data" => "unauthorized"]));
}

if (getRoleCodeByRoleId($_SESSION['role_id']) != "teacher") {
    http_response_code(401);
    echo json_encode(["status" => "failure", "data" => "unauthorized"]);
} else {
    http_response_code(200);
    echo json_encode(["status" => "success", "data" => $_SESSION['user_id']]);
}
