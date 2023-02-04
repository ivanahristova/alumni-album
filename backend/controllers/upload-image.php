<?php
require_once '../services/database/database.php';

$message = "";
if (isset($_FILES["image"])) {//TODO choose happy path
    $file = $_FILES["image"];
    $file_name = $file["name"];
    $file_tmp = $file["tmp_name"];

    $class = $_POST["class"];
    $subclass = $_POST["subclass"];
    $programme = $_POST["programme"];
    $group = $_POST["student-group"];
    $programme_id = getProgrammeId($programme);

    $file_size = $_FILES['image']['size'];
    $extension = explode('.', $_FILES['image']['name']);
    $file_ext = strtolower(end($extension));
    $extensions = array("jpeg", "jpg", "png");


    if (in_array($file_ext, $extensions) === false) {
        $message = $message . "Разширението не е позволено, моля използвайте JPEG или PNG файл.";
    }

//      if($file_size > 2097152){
//          $message = $message . 'Файлът трябва да е по-малък от 2 MB. ';
//      }

    if ($message == "") {

        $new_file_name = uniqid() . "database" . $file_ext;
        $path = "..\..\..\backend\images\uploads\\" . $new_file_name . "." . $file_ext;
        $file_destination = "..\images\uploads\\" . $new_file_name . "." . $file_ext;
        move_uploaded_file($file_tmp, $file_destination);
        try {
            $db = Database::getInstance();
            $connection = $db->getConnection();

            $image_path = $_FILES['image']['name'];

            $sqlInsert = "INSERT INTO photo(path, class,subclass,programme_id,student_group) VALUES (?,?,?,?,?)";
            $statement = $connection->prepare($sqlInsert);
            $statement->execute(array($path, $class, $subclass, $programme_id, $group));
            $message = "Успешно качване!";
            echo json_encode(array("status" => "success", "data" => $message), JSON_UNESCAPED_UNICODE);

        } catch (PDOException $ex) {
            echo $ex->getMessage();
        }
    } else {
        echo json_encode(array("status" => "failure", "data" => "Неуспешно качване. " . $message), JSON_UNESCAPED_UNICODE);
    }
}
