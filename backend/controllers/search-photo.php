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

    $productString = implode(" ", $search_attributes);
    if (trim($productString) == "") {
        http_response_code(400);
        exit (json_encode(array("status" => "failure", "data" => []), JSON_UNESCAPED_UNICODE));
    }

    $attributes = array();
    foreach ($search_attributes as $attribute => $value) {
        if (!isset($value) && $value != null && is_numeric($value)) {
            $attributes[] = $value;
        }
    }

    $sql_search_query = buildSQLQuery($attributes);
    $statement = $connection->prepare($sql_search_query);
    $statement->execute($attributes);
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(array("status" => "success", "data" => $result), JSON_UNESCAPED_UNICODE);

} catch (PDOException $ex) {
    echo $ex->getMessage();
}
function buildSQLQuery($search_attributes): string
{
    $sql_search_query = "SELECT path FROM photo ph ";
    $sql_search_query = $sql_search_query . addJoins($search_attributes);

    if (isset($search_attributes["class"]) && is_numeric($search_attributes["class"])) {
        $sql_search_query = $sql_search_query . " WHERE ph.class = ? ";
    }

    if (isset($search_attributes["programme"]) && is_numeric($search_attributes["programme"])) {
        $sql_search_query = $sql_search_query . " WHERE pr.id = ? ";
    }

    if (isset($search_attributes["subclass"]) && is_numeric($search_attributes["subclass"])) {
        $sql_search_query = $sql_search_query . " AND ph.subclass = ";
    }

    if (isset($search_attributes["studentGroup"]) && is_numeric($search_attributes["studentGroup"])) {
        $sql_search_query = $sql_search_query . " AND ph.student_group = ";
    }

    return $sql_search_query;
}

function addJoins($search_attributes): string
{
    if (isset($search_attributes["programme"])) {
        return " JOIN programme pr ON pr.id=ph.programme_id ";
    }

    return "";
}
