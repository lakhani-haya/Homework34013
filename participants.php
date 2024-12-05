<?php
include 'dbconfig.php';
include 'view-header.php';

$eventID = isset($_GET['event_id']) ? $_GET['event_id'] : null;
$query = "SELECT * FROM Participants" . ($eventID ? " WHERE EventID = ?" : "");
$stmt = $conn->prepare($query);
$stmt->execute($eventID ? [$eventID] : []);
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
            <td>
                <form method="POST" action="tickets.php">
                    <input type="hidden" name="participant_id" value="<?php echo $participant['ParticipantID']; ?>">
                    <button type="submit" class="btn btn-link">View Tickets</button>
                </form>
            </td>
             <td>
                    <a href="edit_participant.php?id=<?php echo $participant['ParticipantID']; ?>">Edit</a>
                    <a href="delete_participant.php?id=<?php echo $participant['ParticipantID']; ?>">Delete</a>
           </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<a href="index.php">Back to Events</a>

