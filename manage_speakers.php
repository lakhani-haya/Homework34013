<?php
include 'dbconfig.php';

$action = $_GET['action'] ?? '';
$speakerID = $_GET['id'] ?? null;

if ($action === 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $speakerName = $_POST['SpeakerName'];
    $topic = $_POST['Topic'];
    $sessionID = $_POST['SessionID'];

    $query = "INSERT INTO Speakers (SpeakerName, Topic, SessionID) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->execute([$speakerName, $topic, $sessionID]);

    $_SESSION['message'] = 'Speaker added successfully.';
    header('Location: speakers.php');
    exit;
} elseif ($action === 'edit' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $speakerID = $_POST['SpeakerID'];
    $speakerName = $_POST['SpeakerName'];
    $topic = $_POST['Topic'];
    $sessionID = $_POST['SessionID'];

    $query = "UPDATE Speakers SET SpeakerName = ?, Topic = ?, SessionID = ? WHERE SpeakerID = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$speakerName, $topic, $sessionID, $speakerID]);

    $_SESSION['message'] = 'Speaker updated successfully.';
    header('Location: speakers.php');
    exit;
} elseif ($action === 'delete' && $speakerID) {
    $query = "DELETE FROM Speakers WHERE SpeakerID = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$speakerID]);

    $_SESSION['message'] = 'Speaker deleted successfully.';
    header('Location: speakers.php');
    exit;
}
?>
