<?php
include 'dbconfig.php';
session_start();

$action = $_GET['action'] ?? '';
$participantID = $_POST['id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $eventID = $_POST['event_id'];

    if ($action === 'add') {
        $query = "INSERT INTO Participants (ParticipantName, Email, EventID) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->execute([$name, $email, $eventID]);
        $_SESSION['message'] = "Participant added successfully!";
    } elseif ($action === 'edit') {
        $query = "UPDATE Participants SET ParticipantName = ?, Email = ?, EventID = ? WHERE ParticipantID = ?";
        $stmt = $conn->prepare($query);
        $stmt->execute([$name, $email, $eventID, $participantID]);
        $_SESSION['message'] = "Participant updated successfully!";
    }
    header('Location: participants.php');
    exit;
}

if ($action === 'delete' && $participantID) {
    $query = "DELETE FROM Participants WHERE ParticipantID = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$participantID]);
    $_SESSION['message'] = "Participant deleted successfully!";
    header('Location: participants.php');
    exit;
}

