<?php

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
$templateName = $inputData['templateName'];
$templateId = $inputData['templateId'];
$schedules = $inputData['schedules'];
// Checking Params
if (!isset($inputData) || !isset($templateName) || !isset($schedules) || !isset($templateId)) {
    //Set Response to 400 : Bad Request
    http_response_code(400);
    echo json_encode(['message' => $inputData]);
    die();
}

include('../../../conf/mssql-connect-ShipmentSchedule.php');

$insert_item_query = "INSERT INTO schedule_template_item 
        (templateid, item, FixDurationMinute, FixStartTime, FixEndTime) 
        VALUES 
        (?, ?, ?, ?, ?)";
$sth = $conn->prepare($insert_item_query);

foreach ($schedules as $schedule) {
    $startTime = $schedule['startTime'] == '' ? null : $schedule['startTime'];
    $endTime = $schedule['endTime'] == '' ? null : $schedule['endTime'];

    $sth->bindParam(1, $templateId, PDO::PARAM_INT);
    $sth->bindParam(2, $schedule['task'], PDO::PARAM_STR);
    $sth->bindParam(3, $schedule['totalTime'], PDO::PARAM_INT);
    $sth->bindParam(4, $startTime, PDO::PARAM_STR);
    $sth->bindParam(5, $endTime, PDO::PARAM_STR);

    try {
        $sth->execute();
    } catch (Exception $ex) {
        http_response_code(500); // Internal Server Error
        echo json_encode(['error' => 'Database error: ' . $ex->getMessage()]);
        exit;
    }
}

// Update template name (optional, depending on your requirements)
$update_template_query = "UPDATE schedule_template SET name = ? WHERE id = ?";
$stmt = $conn->prepare($update_template_query);
$stmt->bindParam(1, $templateName, PDO::PARAM_STR);
$stmt->bindParam(2, $templateId, PDO::PARAM_INT);

try {
    $stmt->execute();
} catch (PDOException $e) {
    http_response_code(500); // Internal Server Error
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    exit;
}

// Success response
http_response_code(201); // Created
header('Content-Type: application/json');
echo json_encode(['message' => 'Schedule items created successfully.']);

$conn = null; // Close the database connection
