<?php

session_start();
session_destroy();

echo json_encode(["status" => "success", "data" => "Успешен изход"], JSON_UNESCAPED_UNICODE);
