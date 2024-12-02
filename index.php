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
    <h1>Events</h1>
    <ul>
        <?php foreach ($events as $event): ?>
        <li>
            <?php echo htmlspecialchars($event['EventName']); ?> - <?php echo $event['EventDate']; ?> (<?php echo $event['Location']; ?>)
            <a href="participants.php?event_id=<?php echo $event['EventID']; ?>">View Participants</a>
            <a href="sessions.php?event_id=<?php echo $event['EventID']; ?>">View Sessions</a>
        </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
