<?php

session_start();

$user_id = $_SESSION['user_id'];
$role_id = $_SESSION['role_id'];

$teacher_id = 2; // get id where code = 'teacher'

if (!isset($user_id) || !isset($role_id) || $role_id != $teacher_id) {
    echo json_encode(array("status" => "failure", "data" => "unauthorized"));
} else {
    echo json_encode(array("status" => "success", "data" => $user_id));
}
