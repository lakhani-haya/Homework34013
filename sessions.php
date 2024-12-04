<?php
include 'dbconfig.php';
include 'view-header.php';

$eventID = isset($_GET['event_id']) ? $_GET['event_id'] : null;

if ($eventID) {
    $query = "SELECT * FROM Sessions WHERE EventID = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$eventID]);
} else {
    $query = "SELECT * FROM Sessions";
    $stmt = $conn->query($query);
}

$sessions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h1>Sessions</h1>
<table class="table table-striped">
    <thead>
        <tr>
            <th>Session Name</th>
            <th>Duration</th>
            <th>View Speakers</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($sessions as $session): ?>
        <tr>
            <td><?php echo htmlspecialchars($session['SessionName']); ?></td>
            <td><?php echo htmlspecialchars($session['Duration']); ?></td>
            <td><a href="speakers.php?session_id=<?php echo $session['SessionID']; ?>">View Speakers</a></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

