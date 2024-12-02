<?php
include 'dbconfig.php';

$sessionID = $_GET['session_id'];
$query = "SELECT * FROM Speakers WHERE SessionID = ?";
$stmt = $conn->prepare($query);
$stmt->execute([$sessionID]);
$speakers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Speakers</title>
</head>
<body>
    <h1>Speakers</h1>
    <ul>
        <?php foreach ($speakers as $speaker): ?>
        <li><?php echo htmlspecialchars($speaker['SpeakerName']); ?> - Topic: <?php echo $speaker['Topic']; ?></li>
        <?php endforeach; ?>
    </ul>
    <a href="index.php">Back to Events</a>
</body>
</html>
