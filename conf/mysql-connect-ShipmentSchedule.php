<?php
/*
Connection To ShipmentSchedule Databse
*/

if (file_exists("../.env")) {
    $env = parse_ini_file('../.env');
} else if (file_exists("../../../.env")) {
    $env = parse_ini_file('../../../.env');
}

$servername = $env["shipment-db-server"];
$dbName = $env["shipment-db-name"];
$username = $env["shipment-db-username"];
$password = $env["shipment-db-password"];

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbName", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection to $dbName failed: " . $e->getMessage();
    die();
}
