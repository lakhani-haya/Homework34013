<?php
$serverName = "event-mgmt-server.database.windows.net";
$database = "EventManagementDB";
$username = "haya";
$password = "Database100";

try {
    $conn = new PDO("sqlsrv:server=$serverName;Database=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die($e->getMessage());
}
?>
