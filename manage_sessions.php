<?php
include 'dbconfig.php';

$action = $_GET['action'] ?? '';
$sessionID = $_GET['id'] ?? null;

if ($action === 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $sessionName = $_POST['SessionName'];
    $duration = $_POST['Duration'];
    $eventID = $_POST['EventID'];

    $query = "INSERT INTO Sessions (SessionName, Duration, EventID) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->execute([$sessionName, $duration, $eventID]);

    $_SESSION['message'] = 'Session added successfully.';
    header('Location: sessions.php');
    exit;
} elseif ($action === 'edit' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $sessionID = $_POST['SessionID'];
    $sessionName = $_POST['SessionName'];
    $duration = $_POST['Duration'];
    $eventID = $_POST['EventID'];

    $query = "UPDATE Sessions SET SessionName = ?, Duration = ?, EventID = ? WHERE SessionID = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$sessionName, $duration, $eventID, $sessionID]);

    $_SESSION['message'] = 'Session updated successfully.';
    header('Location: sessions.php');
    exit;
} elseif ($action === 'delete' && $sessionID) {
    $query = "DELETE FROM Sessions WHERE SessionID = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$sessionID]);

    $_SESSION['message'] = 'Session deleted successfully.';
    header('Location: sessions.php');
    exit;
}

?>
