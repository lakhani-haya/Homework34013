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
    <nav>
        <a href="index.php">Events</a>
        <a href="participants.php">Participants</a>
        <a href="sessions.php">Sessions</a>
        <a href="speakers.php">Speakers</a>
    </nav>

    <h1>Participants</h1>
    <table>
        <thead>
            <tr>
                <th>Participant Name</th>
                <th>Email</th>
                <th>View Tickets</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($participants as $participant): ?>
            <tr>
                <td><?php echo htmlspecialchars($participant['ParticipantName']); ?></td>
                <td><?php echo htmlspecialchars($participant['Email']); ?></td>
                <td><a href="tickets.php?participant_id=<?php echo $participant['ParticipantID']; ?>">View Tickets</a></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
