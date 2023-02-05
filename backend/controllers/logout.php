<?php

if (!session_start() || !session_destroy()) {
    http_response_code(500);
    echo json_encode(["status" => "success", "data" => "Неуспешен изход"], JSON_UNESCAPED_UNICODE);
} else {
    http_response_code(200);
    echo json_encode(["status" => "success", "data" => "Успешен изход"], JSON_UNESCAPED_UNICODE);
}
