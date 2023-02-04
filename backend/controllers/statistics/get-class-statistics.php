<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(array("status" => "failure", "data" => "unauthorized"));
}

require_once '../../services/database/database.php';

$stats = getClassStatistics();
echo json_encode(array("status" => "success", "data" => $stats), JSON_UNESCAPED_UNICODE);
