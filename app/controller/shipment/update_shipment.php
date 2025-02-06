<?php

//Later Create Module For Checking Credentials
$redirect_link = "../../index.php?page=s";

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    //Set Response to 405 : Method Not Allowed
    http_response_code(405);

    echo "<script> 
                alert('Method Not Allowed'); 
                document.location='$redirect_link'; 
            </script>";
    die();
}

//GET POST DATA
$inputData = json_decode(file_get_contents('php://input'), true);
$shipmentId = $inputData['id'];
$scheduleDate = $inputData['date'];
$scheduleName = $inputData['name'];
$templateId = $inputData['templateId'];
$shipmentItems = $inputData['scheduleItem'];

// Checking Params
if (!isset($scheduleDate) || !isset($scheduleName) || !isset($templateId) || !isset($shipmentItems)) {
    //Set Response to 400 : Bad Request
    http_response_code(400);

    echo "<script> 
                alert('Bad Request'); 
                document.location='$redirect_link'; 
            </script>";
    die();
}

include('../../../conf/mssql-connect-ShipmentSchedule.php');

//Update Shipment
$insert_shipment_query = "UPDATE schedule_schedules SET
                            scheduleDate = ?,
                            templateId = ?,
                            name = ?
                            WHERE id = ?";

$stmt1 = $conn->prepare($insert_shipment_query);

$stmt1->bindParam(1, $scheduleDate, PDO::PARAM_STR);
$stmt1->bindParam(2, $templateId, PDO::PARAM_INT);
$stmt1->bindParam(3, $scheduleName, PDO::PARAM_STR);
$stmt1->bindParam(4, $shipmentId, PDO::PARAM_INT);

try {
    $stmt1->execute();
} catch (Exception $ex) {
    http_response_code(500); // Internal Server Error
    echo json_encode(['error' => 'Database error: ' . $ex->getMessage()]);
    exit;
}

$insert_item_shipment_query = "UPDATE schedule_schedules_item SET 
                                durationMinute = ?,
                                startTime = ?,
                                endTime = ?
                                WHERE id = ?";

$stmt2 = $conn->prepare($insert_item_shipment_query);

foreach ($shipmentItems as $item) {
    $startTime = $item['startTime'] == '' ? null : $item['startTime'];
    $endTime = $item['endTime'] == '' ? null : $item['endTime'];

    $stmt2->bindParam(1, $item['totalTime'], PDO::PARAM_INT);
    $stmt2->bindParam(2, $startTime, PDO::PARAM_STR);
    $stmt2->bindParam(3, $endTime, PDO::PARAM_STR);
    $stmt2->bindParam(4, $item['id'], PDO::PARAM_INT);

    try {
        $stmt2->execute();
    } catch (Exception $ex) {
        http_response_code(500); // Internal Server Error
        echo json_encode(['error' => 'Database error: ' . $ex->getMessage()]);
        exit;
    }
}

http_response_code(200); // Created
header('Content-Type: application/json');
echo json_encode(['message' => 'Shipment updated successfully']);

$conn = null; // Close the database connection
