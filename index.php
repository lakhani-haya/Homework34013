<?php
include 'dbconfig.php';

$query = "SELECT * FROM Events";
$stmt = $conn->query($query);
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Events</title>
    <style>
        nav {
            background-color: #333;
            overflow: hidden;
        }
        nav a {
            float: left;
            display: block;
            color: white;
            text-align: center;
            padding: 14px 20px;
            text-decoration: none;
        }
        nav a:hover {
            background-color: #ddd;
            color: black;
        }

        /* Styling for the table */
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav>
        <a href="events.php">Events</a>
        <a href="participants.php">Participants</a>
        <a href="sessions.php">Sessions</a>
        <a href="speakers.php">Speakers</a>
    </nav>

    <h1>Events</h1>
    <table>
        <thead>
            <tr>
                <th>Event Name</th>
                <th>Event Date</th>
                <th>Location</th>
                <th>View Participants</th>
                <th>View Sessions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($events as $event): ?>
            <tr>
                <td><?php echo htmlspecialchars($event['EventName']); ?></td>
                <td><?php echo $event['EventDate']; ?></td>
                <td><?php echo $event['Location']; ?></td>
                <td><a href="participants.php?event_id=<?php echo $event['EventID']; ?>">View Participants</a></td>
                <td><a href="sessions.php?event_id=<?php echo $event['EventID']; ?>">View Sessions</a></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
