<?php
include 'dbconfig.php';
include 'view-header.php';

// Fetch All Events for Dropdown
$query = "SELECT * FROM Events";
$stmt = $conn->prepare($query);
$stmt->execute();
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch Participants
$eventID = isset($_GET['event_id']) ? $_GET['event_id'] : null;
$query = "SELECT * FROM Participants" . ($eventID ? " WHERE EventID = ?" : "");
$stmt = $conn->prepare($query);
$stmt->execute($eventID ? [$eventID] : []);
$participants = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h1>Participants</h1>

<!-- Event Filter -->
<form method="GET" action="participants.php">
    <label for="event_id">Filter by Event:</label>
    <select name="event_id" id="event_id">
        <option value="">All Events</option>
        <?php foreach ($events as $event): ?>
            <option value="<?php echo $event['EventID']; ?>" <?php echo $eventID == $event['EventID'] ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($event['EventName']); ?>
            </option>
        <?php endforeach; ?>
    </select>
    <button type="submit">Filter</button>
</form>

<!-- Participants Table -->
<table class="table table-striped">
    <thead>
        <tr>
            <th>Participant Name</th>
            <th>Email</th>
            <th>Event</th>
            <th>View Tickets</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($participants as $participant): ?>
        <tr>
            <td><?php echo htmlspecialchars($participant['ParticipantName']); ?></td>
            <td><?php echo htmlspecialchars($participant['Email']); ?></td>
            <td>
                <?php
                $eventName = array_column($events, 'EventName', 'EventID')[$participant['EventID']] ?? 'N/A';
                echo htmlspecialchars($eventName);
                ?>
            </td>
            <td>
                <form method="POST" action="tickets.php">
                    <input type="hidden" name="participant_id" value="<?php echo $participant['ParticipantID']; ?>">
                    <button type="submit" class="btn btn-link">View Tickets</button>
                </form>
            </td>
            <td>
                <a href="manage_participants.php?action=edit&id=<?php echo $participant['ParticipantID']; ?>">Edit</a> |
                <a href="manage_participants.php?action=delete&id=<?php echo $participant['ParticipantID']; ?>" onclick="return confirm('Are you sure?');">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<a href="manage_participants.php?action=add" class="btn btn-primary">Add Participant</a>
<a href="index.php">Back to Events</a>
