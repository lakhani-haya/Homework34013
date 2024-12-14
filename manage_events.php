<?php
include 'dbconfig.php';

$action = $_GET['action'] ?? '';
$eventID = $_GET['id'] ?? 0;

if ($action == 'add') {
    $eventName = $_POST['EventName'];
    $eventDate = $_POST['EventDate'];
    $location = $_POST['Location'];

    $query = "INSERT INTO Events (EventName, EventDate, Location) VALUES (:EventName, :EventDate, :Location)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':EventName', $eventName);
    $stmt->bindParam(':EventDate', $eventDate);
    $stmt->bindParam(':Location', $location);
    $stmt->execute();

    session_start();
    $_SESSION['message'] = "Event added successfully.";
    header("Location: events.php");
    exit;
}

if ($action == 'edit') {
    $eventID = $_POST['EventID'];
    $eventName = $_POST['EventName'];
    $eventDate = $_POST['EventDate'];
    $location = $_POST['Location'];

    $query = "UPDATE Events SET EventName = :EventName, EventDate = :EventDate, Location = :Location WHERE EventID = :EventID";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':EventID', $eventID);
    $stmt->bindParam(':EventName', $eventName);
    $stmt->bindParam(':EventDate', $eventDate);
    $stmt->bindParam(':Location', $location);
    $stmt->execute();

    session_start();
    $_SESSION['message'] = "Event updated successfully.";
    header("Location: events.php");
    exit;
}

if ($action == 'delete') {
    $query = "DELETE FROM Events WHERE EventID = :EventID";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':EventID', $eventID);
    $stmt->execute();

    session_start();
    $_SESSION['message'] = "Event deleted successfully.";
    header("Location: events.php");
    exit;
}
