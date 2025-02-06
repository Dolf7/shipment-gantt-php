<?php

//Later Create Module For Checking Credentials
$redirect_link = "../../index.php?page=schedule-templates";

if ($_SERVER['REQUEST_METHOD'] != 'GET') {

    //Set Response to 405 : Method Not Allowed
    http_response_code(405);

    echo "<script> 
                alert('Method Not Allowed'); 
                document.location='$redirect_link'; 
            </script>";
    die();
}

// Checking Params
if (!isset($_GET['id'])) {
    //Set Response to 400 : Bad Request
    http_response_code(400);

    echo "<script> 
                alert('Bad Request'); 
                document.location='$redirect_link'; 
            </script>";
    die();
}

include('../../../conf/mssql-connect-ShipmentSchedule.php');

$id = $_GET['id'];

$query_delete_templates_item = "DELETE FROM schedule_template_item WHERE templateid=:id";

$stmt = $conn->prepare($query_delete_templates_item);
$stmt->bindParam(':id', $id);

try {
    $stmt->execute();
} catch (Exception $e) {
    http_response_code(500);

    echo "<script> 
                alert('Failed to Delete Template, Try Again : $e'); 
                document.location='$redirect_link'; 
            </script>";
    die();
}


$query_delete_templates = "DELETE FROM schedule_template WHERE id=:id";

$sth  = $conn->prepare($query_delete_templates);
$sth->bindParam(":id", $id, PDO::PARAM_INT);

try {
    $sth->execute();
} catch (Exception $e) {
    http_response_code(500);

    echo "<script> 
                alert('Failed to Delete Template, Try Again : $e'); 
                document.location='$redirect_link'; 
            </script>";
    die();
}

echo "<script> 
        alert('Template with id $id Deleted'); 
        document.location='$redirect_link'; 
        </script>";

die();
