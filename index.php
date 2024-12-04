<?php
include 'dbconfig.php';
$query = "SELECT * FROM Events";
$stmt = $conn->query($query);
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Events</title>
</head>
<body>
    <nav>
        <a href="index.php">Events</a>
        <a href="participants.php">Participants</a>
        <a href="sessions.php">Sessions</a>
        <a href="speakers.php">Speakers</a>
    </nav>

    <h1>Events</h1>
    <table>
        <thead>
            <tr>
                <th>Event Name</th>
                <th>Event Date</th>
                <th>Location</th>
                <th>View Sessions</th>
                <th>View Participants</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($events as $event): ?>
            <tr>
                <td><?php echo htmlspecialchars($event['EventName']); ?></td>
                <td><?php echo htmlspecialchars($event['EventDate']); ?></td>
                <td><?php echo htmlspecialchars($event['Location']); ?></td>
                <td><a href="sessions.php?event_id=<?php echo $event['EventID']; ?>">View Sessions</a></td>
                <td><a href="participants.php?event_id=<?php echo $event['EventID']; ?>">View Participants</a></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
