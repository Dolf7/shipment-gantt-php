<?php
$redirect_link = "../../index.php?page=schedule-templates";

if ($_SERVER['REQUEST_METHOD'] != 'POST') {

    //Set Response to 405 : Method Not Allowed
    http_response_code(405);

    echo "<script> 
                alert('Method Not Allowed'); 
                document.location='$redirect_link'; 
            </script>";
    die();
}

//GET PUT DATA
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

$update_item_query = "UPDATE schedule_template_item 
        SET
        templateid=?,
        item=?,
        FixDurationMinute=?,
        FixStartTime=?,
        FixEndTime=?
        WHERE
        id=?";
$sth2 = $conn->prepare($update_item_query);

foreach ($schedules as $schedule) {
    //For Create new Item for value id=0
    if ($schedule['id'] == '0' || $schedule['id'] == 0) {
        $sth->bindParam(1, $templateId, PDO::PARAM_INT);
        $sth->bindParam(2, $schedule['task'], PDO::PARAM_STR);
        $sth->bindParam(3, $schedule['totalTime'], PDO::PARAM_INT);
        $sth->bindParam(4, $schedule['startTime'], PDO::PARAM_STR);
        $sth->bindParam(5, $schedule['endTime'], PDO::PARAM_STR);

        try {
            $sth->execute();
        } catch (Exception $ex) {
            http_response_code(500); // Internal Server Error
            echo json_encode(['error' => 'Database error: ' . $ex->getMessage()]);
            exit;
        }
    } else {
        //For Update Existing Item
        $sth2->bindParam(1, $templateId, PDO::PARAM_INT);
        $sth2->bindParam(2, $schedule['task'], PDO::PARAM_STR);
        $sth2->bindParam(3, $schedule['totalTime'], PDO::PARAM_INT);
        $sth2->bindParam(4, $schedule['startTime'], PDO::PARAM_STR);
        $sth2->bindParam(5, $schedule['endTime'], PDO::PARAM_STR);
        $sth2->bindParam(6, $schedule['id'], PDO::PARAM_INT);

        try {
            $sth2->execute();
        } catch (Exception $ex) {
            http_response_code(500); // Internal Server Error
            echo json_encode(['error' => 'Database error: ' . $ex->getMessage()]);
            exit;
        }
    }
}

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

//success response
http_response_code(200);
header('Content-Type: application/json');
echo json_encode(['data' => "Success Update Data"]);

$conn = null;
