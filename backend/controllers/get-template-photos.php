<?php

$images = [
    ['src' => '..\..\..\backend\images\template\web-01.jpg', 'alt' => 'Image 1'],
    ['src' => '..\..\..\backend\images\template\web-10.jpg', 'alt' => 'Image 10'],
    ['src' => '..\..\..\backend\images\template\web-02.jpg', 'alt' => 'Image 2'],
    ['src' => '..\..\..\backend\images\template\web-03.jpg', 'alt' => 'Image 3'],
    ['src' => '..\..\..\backend\images\template\web-04.jpg', 'alt' => 'Image 4'],
    ['src' => '..\..\..\backend\images\template\web-05.jpg', 'alt' => 'Image 5'],
    ['src' => '..\..\..\backend\images\template\web-06.jpg', 'alt' => 'Image 6'],
    ['src' => '..\..\..\backend\images\template\web-07.jpg', 'alt' => 'Image 7'],
    ['src' => '..\..\..\backend\images\template\web-08.jpg', 'alt' => 'Image 8'],
    ['src' => '..\..\..\backend\images\template\web-09.jpg', 'alt' => 'Image 9'],
    ['src' => '..\..\..\backend\images\template\web-11.jpg', 'alt' => 'Image 11'],
    ['src' => '..\..\..\backend\images\template\web-14.jpg', 'alt' => 'Image 14'],
    ['src' => '..\..\..\backend\images\template\web-12.jpg', 'alt' => 'Image 12'],
    ['src' => '..\..\..\backend\images\template\web-13.jpg', 'alt' => 'Image 13'],
    ['src' => '..\..\..\backend\images\template\web-15.jpg', 'alt' => 'Image 15'],
    ['src' => '..\..\..\backend\images\template\web-16.jpg', 'alt' => 'Image 16']
];

echo json_encode(array("status" => "success", "data" => $images), JSON_UNESCAPED_UNICODE);
