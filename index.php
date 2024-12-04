<?php
include 'dbconfig.php';
$query = "SELECT * FROM Events";
$stmt = $conn->query($query);
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);

       <li class="nav-item">
        <a class="nav-link" href="events.php">Events</a>
       </li>
     
    <h1>Events</h1>
    <table>
        <tbody>
            <?php foreach ($events as $event): ?>
            <tr>
                <td><?php echo htmlspecialchars($event['EventName']); ?></td>
                <td><?php echo htmlspecialchars($event['EventDate']); ?></td>
                <td><?php echo htmlspecialchars($event['Location']); ?></td>
                <td><a href="sessions.php?event_id=<?php echo $event['EventID']; ?>">View Sessions</a></td>
                <td><a href="participants.php?event_id=<?php echo $event['EventID']; ?>">View Participants</a></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
