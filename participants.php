
<?php
include 'dbconfig.php';
include 'view-header.php';

$eventID = isset($_GET['event_id']) ? $_GET['event_id'] : null;

if ($eventID) {
    $query = "SELECT * FROM Participants WHERE EventID = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$eventID]);
} else {
    $query = "SELECT * FROM Participants";
    $stmt = $conn->query($query);
}

$participants = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h1>Participants</h1>
<table class="table table-striped">
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
