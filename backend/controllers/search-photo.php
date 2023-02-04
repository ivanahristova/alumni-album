<?php
require_once '../services/database/database.php';
$search_attributes = json_decode(file_get_contents('php://input'), true);

if (!isset($search_attributes)) {
    http_response_code(400);
    exit (json_encode(array("status" => "failure", "data" => []), JSON_UNESCAPED_UNICODE));
}

try {
    $db = Database::getInstance();
    $connection = $db->getConnection();

    $attributes = array();
    foreach ($search_attributes as $attribute => $value) {
        if (isset($value)) {
            $attributes[] = $value;

        }
    }
    $sql_insert_query = buildSQLQuery($search_attributes);
    $statement = $connection->prepare($sql_insert_query);
    $statement->execute($attributes);
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(array("status" => "success", "data" => $result), JSON_UNESCAPED_UNICODE);

} catch (PDOException $ex) {
   // echo $ex->getMessage();
    http_response_code(400);
    exit (json_encode(array("status" => "failure", "data" => []), JSON_UNESCAPED_UNICODE));
}

function buildSQLQuery($search_attributes): string
{
    $sql_insert_query = "SELECT path FROM photo ph ";

    if (isset($search_attributes["programme"])) {
        $sql_insert_query = $sql_insert_query . "JOIN programme pr ON ph.programme_id=pr.id WHERE ph.class= ? AND pr.code = ? ";
        $is_programme_set = true;
    }

    if (isset($search_attributes["subclass"])) {
        $sql_insert_query = $sql_insert_query . " AND ph.subclass = ? ";
    }
    if (isset($search_attributes["studentGroup"])) {
        $sql_insert_query = $sql_insert_query . " AND ph.student_group = ? ";
    }

    return $sql_insert_query;
}
