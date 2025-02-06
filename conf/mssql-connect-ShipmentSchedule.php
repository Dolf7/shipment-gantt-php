<?php
/*
Connection To ShipmentSchedule Databse
*/

if (file_exists("../.env")) {
    $env = parse_ini_file('../.env');
} else if (file_exists("../../../.env")) {
    $env = parse_ini_file('../../../.env');
}
$sql_server_db = $env["odbc-shipment-dsn-server"];
$sql_server_username = $env['odbc-shipment-db-username'];
$sql_server_password = $env['odbc-shipment-db-password'];

try {
    $conn = new PDO("odbc:$sql_server_db", $sql_server_username, $sql_server_password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection to $sql_server_db failed: " . $e->getMessage();
    die();
}
