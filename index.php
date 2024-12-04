<?php
$pageTitle = 'Events';
include 'view-header.php';
include 'dbconfig.php';

$query = "SELECT * FROM Events";
$stmt = $conn->query($query);
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h1>Events</h1>
<table class="table table-striped">
  <thead>
    <tr>
      <th>Event Name</th>
      <th>Event Date</th>
      <th>Location</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($events as $event): ?>
      <tr>
        <td><?php echo htmlspecialchars($event['EventName']); ?></td>
        <td><?php echo htmlspecialchars($event['EventDate']); ?></td>
        <td><?php echo htmlspecialchars($event['Location']); ?></td>
        <td>
          <a href="sessions.php?event_id=<?php echo $event['EventID']; ?>">View Sessions</a> |
          <a href="participants.php?event_id=<?php echo $event['EventID']; ?>">View Participants</a>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

