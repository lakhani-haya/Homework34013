<?php
include 'dbconfig.php';

$eventID = $_GET['event_id'];
$query = "SELECT * FROM Participants WHERE EventID = ?";
$stmt = $conn->prepare($query);
$stmt->execute([$eventID]);
$participants = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Participants</title>
</head>
<body>
    <h1>Participants</h1>
    <ul>
        <?php foreach ($participants as $participant): ?>
        <li>
            <?php echo htmlspecialchars($participant['ParticipantName']); ?> (<?php echo $participant['Email']; ?>)
            <a href="tickets.php" onclick="document.getElementById('participantForm').submit(); return false;">View Tickets</a>
            <form id="participantForm" method="POST" action="tickets.php">
                <input type="hidden" name="participant_id" value="<?php echo $participant['ParticipantID']; ?>">
            </form>
        </li>
        <?php endforeach; ?>
    </ul>
    <a href="index.php">Back to Events</a>
</body>
</html>
