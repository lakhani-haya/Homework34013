<?php
include 'dbconfig.php';

$action = $_GET['action'] ?? '';
$participantID = $_GET['id'] ?? null;

// Fetch all events for dropdown
$query = "SELECT * FROM Events";
$stmt = $conn->prepare($query);
$stmt->execute();
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle Actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $eventID = $_POST['event_id'];

    if ($action === 'edit') {
        // Edit Participant
        $query = "UPDATE Participants SET ParticipantName = ?, Email = ?, EventID = ? WHERE ParticipantID = ?";
        $stmt = $conn->prepare($query);
        $stmt->execute([$name, $email, $eventID, $participantID]);
    } elseif ($action === 'add') {
        // Add Participant
        $query = "INSERT INTO Participants (ParticipantName, Email, EventID) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->execute([$name, $email, $eventID]);
    }
    header('Location: participants.php');
    exit;
}

if ($action === 'delete' && $participantID) {
    // Delete Participant
    $query = "DELETE FROM Participants WHERE ParticipantID = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$participantID]);
    header('Location: participants.php');
    exit;
}

// Fetch Participant Data for Editing
$participant = null;
if ($action === 'edit' && $participantID) {
    $query = "SELECT * FROM Participants WHERE ParticipantID = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$participantID]);
    $participant = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Participant</title>
</head>
<body>
    <h1><?php echo ucfirst($action); ?> Participant</h1>

    <form method="POST">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?php echo $participant['ParticipantName'] ?? ''; ?>" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo $participant['Email'] ?? ''; ?>" required>

        <label for="event_id">Event:</label>
        <select id="event_id" name="event_id">
            <?php foreach ($events as $event): ?>
                <option value="<?php echo $event['EventID']; ?>" 
                        <?php echo isset($participant) && $participant['EventID'] == $event['EventID'] ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($event['EventName']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit"><?php echo ucfirst($action); ?></button>
    </form>

    <a href="participants.php">Back to Participants</a>
</body>
</html>
