<?php

if ($_SERVER['REQUEST_METHOD'] != 'GET') {

    //Set Response to 405 : Method Not Allowed
    http_response_code(405);
    echo json_encode(['message' => 'METHOD NOT Allowed']);
    die();
}

if (!isset($_GET['id'])) {
    http_response_code(400);
    echo json_encode(['message' => "BAD Request, Require Param GET id"]);
    die();
}

$id = $_GET['id'];

if (!is_numeric($id)) {
    http_response_code(400);
    echo json_encode(['message' => "Id Should be numeric"]);
    die();
}

include('../../../conf/mssql-connect-ShipmentSchedule.php');


$query_get_templates_item = "SELECT * FROM schedule_template_item WHERE templateid=:id";

$stmt = $conn->prepare($query_get_templates_item);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);

try {
    $stmt->execute();
    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);

    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($res);
} catch (Exception $ex) {
    http_response_code(500);
    echo json_encode(['message' => 'Internal Server Error' . $ex->getMessage()]);
}

$conn = null;
