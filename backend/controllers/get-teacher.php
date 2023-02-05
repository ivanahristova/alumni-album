<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "failure", "data" => "unauthorized"]);
}

require_once '../services/database/database.php';

$user_id = $_SESSION['user_id'];
$teacher = getTeacherByUserId($user_id);
echo json_encode(["status" => "success", "data" => $teacher], JSON_UNESCAPED_UNICODE);
