<?php
include 'dbconfig.php';
include 'view-header.php';

$sessionID = isset($_GET['session_id']) ? $_GET['session_id'] : null;

if ($sessionID) {
    $query = "SELECT * FROM Speakers WHERE SessionID = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$sessionID]);
} else {
    $query = "SELECT * FROM Speakers";
    $stmt = $conn->query($query);
}

$speakers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h1>Speakers</h1>
<table class="table table-striped">
    <thead>
        <tr>
            <th>Speaker Name</th>
            <th>Topic</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($speakers as $speaker): ?>
        <tr>
            <td><?php echo htmlspecialchars($speaker['SpeakerName']); ?></td>
            <td><?php echo htmlspecialchars($speaker['Topic']); ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
