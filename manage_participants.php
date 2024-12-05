<?php
include 'dbconfig.php';

$action = $_GET['action'] ?? '';
$eventID = $_GET['event_id'] ?? 0;

if ($action == 'add') {
    $name = $_POST['name'];
    $email = $_POST['email'];

    $query = "INSERT INTO Participants (ParticipantName, Email, EventID) VALUES (:name, :email, :event_id)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':event_id', $eventID);
    $stmt->execute();

    session_start();
    $_SESSION['message'] = "Participant added successfully.";
    header("Location: participants.php?event_id=$eventID");
    exit;
}

if ($action == 'edit') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];

    $query = "UPDATE Participants SET ParticipantName = :name, Email = :email WHERE ParticipantID = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    session_start();
    $_SESSION['message'] = "Participant updated successfully.";
    header("Location: participants.php?event_id=$eventID");
    exit;
}

if ($action == 'delete') {
    $id = $_GET['id'];

    $query = "DELETE FROM Participants WHERE ParticipantID = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    session_start();
    $_SESSION['message'] = "Participant deleted successfully.";
    header("Location: participants.php?event_id=$eventID");
    exit;
}
?>

