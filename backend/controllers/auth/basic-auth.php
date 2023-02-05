<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["status" => "failure", "data" => "unauthorized"]);
} else {
    http_response_code(200);
    echo json_encode(["status" => "success", "data" => "authorized"]);
}
