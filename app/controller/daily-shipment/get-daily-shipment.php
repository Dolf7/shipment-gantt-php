<?php

if ($_SERVER['REQUEST_METHOD'] != 'GET') {

    //Set Response to 405 : Method Not Allowed
    http_response_code(405);
    echo json_encode(['message' => 'METHOD NOT Allowed']);
    die();
}

if (!isset($_GET['shipmentDate'])) {
    http_response_code(400);
    echo json_encode(['message' => "BAD Request, Require Param GET shipmentDate"]);
    die();
}
$shipmentDate = $_GET['shipmentDate'];

// Check if the date follows the format yyyy-MM-dd
$dateRegex = '/^\d{4}-\d{2}-\d{2}$/';
if (!preg_match($dateRegex, $shipmentDate)) {
    http_response_code(400);
    echo json_encode(['message' => 'BAD Request, Invalid Date Format']);
    die();
}

include('../../../conf/mssql-connect-ShipmentSchedule.php');

$query_get_templates_item =
    "SELECT a.scheduleDate, a.name, c.item , b.durationMinute, b.startTime, b.endTime 
        FROM schedule_schedules as a 
        JOIN schedule_schedules_item as b on b.schedulesid = a.id
        JOIN schedule_template_item as c on b.templateitemid = c.id
        WHERE scheduleDate = :shipmentDate
        ";

$stmt = $conn->prepare($query_get_templates_item);
$stmt->bindParam(':shipmentDate', $shipmentDate, PDO::PARAM_STR);

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
