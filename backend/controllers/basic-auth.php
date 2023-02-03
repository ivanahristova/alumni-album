<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(array("status" => "failure", "data" => "unauthorized"));
} else {
    echo json_encode(array("status" => "failure", "data" => "authorized"));
}
