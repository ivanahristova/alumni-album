<?php

session_start();

$user_id = $_SESSION['user_id'];
$role_id = $_SESSION['role_id'];
$role = getRoleCodeByRoleId($role_id);

if (!isset($user_id) || !isset($role_id) || $role != "student") {
    echo json_encode(array("status" => "failure", "data" => "unauthorized"));
} else {
    echo json_encode(array("status" => "success", "data" => $user_id));
}
