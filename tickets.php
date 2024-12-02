<?php
include 'dbconfig.php';

$participantID = $_POST['participant_id'];
$query = "SELECT * FROM Tickets WHERE ParticipantID = ?";
$stmt = $conn->prepare($query);
$stmt->execute([$participantID]);
$tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tickets</title>
</head>
<body>
    <h1>Tickets</h1>
    <ul>
        <?php foreach ($tickets as $ticket): ?>
        <li><?php echo htmlspecialchars($ticket['TicketType']); ?> - $<?php echo $ticket['Price']; ?></li>
        <?php endforeach; ?>
    </ul>
    <a href="index.php">Back to Events</a>
</body>
</html>
