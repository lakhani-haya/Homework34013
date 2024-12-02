<?php
include 'dbconfig.php';

$eventID = $_GET['event_id'];
$query = "SELECT * FROM Sessions WHERE EventID = ?";
$stmt = $conn->prepare($query);
$stmt->execute([$eventID]);
$sessions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sessions</title>
</head>
<body>
    <h1>Sessions</h1>
    <ul>
        <?php foreach ($sessions as $session): ?>
        <li>
            <?php echo htmlspecialchars($session['SessionName']); ?> (<?php echo $session['Duration']; ?>)
            <a href="speakers.php?session_id=<?php echo $session['SessionID']; ?>">View Speakers</a>
        </li>
        <?php endforeach; ?>
    </ul>
    <a href="index.php">Back to Events</a>
</body>
</html>
