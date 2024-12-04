<?php
include 'dbconfig.php';
include 'view-header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['participant_id'])) {
    $participantID = $_POST['participant_id'];
    $query = "SELECT * FROM Tickets WHERE ParticipantID = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$participantID]);
    $tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    echo "No participant selected.";
    exit;
}
?>

<h1>Tickets</h1>
<table class="table table-striped">
    <thead>
        <tr>
            <th>Ticket Type</th>
            <th>Price</th>
        </tr>
    </thead>
    <tbody>
        <?php if (isset($tickets) && count($tickets) > 0): ?>
            <?php foreach ($tickets as $ticket): ?>
            <tr>
                <td><?php echo htmlspecialchars($ticket['TicketType']); ?></td>
                <td>$<?php echo htmlspecialchars($ticket['Price']); ?></td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="2">No tickets found for this participant.</td></tr>
        <?php endif; ?>
    </tbody>
</table>
<a href="participants.php?event_id=<?php echo $participantID; ?>">Back to Participants</a>
