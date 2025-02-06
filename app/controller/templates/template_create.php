<?php

//Later Create Module For Checking Credentials
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

// Checking Params
if (!isset($_POST['name'])) {
    //Set Response to 400 : Bad Request
    http_response_code(400);

    echo "<script> 
                alert('Bad Request'); 
                document.location='$redirect_link'; 
            </script>";
    die();
}

include('../../../conf/mssql-connect-ShipmentSchedule.php');

$name = $_POST['name'];

$query_insert_templates = "INSERT INTO schedule_template (name) VALUES (:name)";

try {
    $sth  = $conn->prepare($query_insert_templates);
    $sth->bindParam(":name", $name, PDO::PARAM_STR);
    $sth->execute();
} catch (Exception $e) {
    http_response_code(500);

    echo "<script> 
                alert('Failed to Creat Template, Try Again : $e'); 
                document.location='$redirect_link'; 
            </script>";
    die();
}

echo "<br>$name";
$query_get_id_template = "SELECT TOP(1) * FROM schedule_template WHERE name=:name";

$sth2  = $conn->prepare($query_get_id_template);
$sth2->bindParam(":name", $name, PDO::PARAM_STR);
$sth2->execute();

$data_res = $sth2->fetch();

$success_redirect_link = "../../index.php?page=schedule-templates-item-create&id=" . $data_res['id'];
echo "<script> 
                document.location='$success_redirect_link'; 
            </script>";

die();
