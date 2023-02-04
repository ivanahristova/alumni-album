<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(array("status" => "failure", "data" => "unauthorized"));
}

require_once '../services/database/database.php';

$user_id = $_SESSION['user_id'];
$teacher = getTeacherById($user_id);
echo json_encode(array("status" => "success", "data" => $teacher), JSON_UNESCAPED_UNICODE);
