<?php
include 'dbconfig.php';
include 'view-header.php';

// Get the EventID from query string (if available)
$eventID = isset($_GET['event_id']) ? $_GET['event_id'] : null;

// Query to fetch participants, filtering by EventID if provided
$query = "SELECT * FROM Participants" . ($eventID ? " WHERE EventID = ?" : "");
$stmt = $conn->prepare($query);
$stmt->execute($eventID ? [$eventID] : []);
$participants = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Session message for success feedback
session_start();
$message = $_SESSION['message'] ?? '';
unset($_SESSION['message']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Participants</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <div class="row">
        <div class="col">
            <h1>Participants</h1>
        </div>
        <div class="col-auto">
            <!-- Button to trigger modal -->
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newParticipantModal">
                Add Participant
            </button>
        </div>
    </div>

    <?php if ($message): ?>
        <div class="alert alert-success mt-3" role="alert">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <!-- Modal -->
    <div class="modal fade" id="newParticipantModal" tabindex="-1" aria-labelledby="newParticipantModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="newParticipantModalLabel">New Participant</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" action="manage_participants.php?action=add&event_id=<?php echo htmlspecialchars($eventID); ?>">
                        <div class="mb-3">
                            <label for="ParticipantName" class="form-label">Participant Name</label>
                            <input type="text" class="form-control" id="ParticipantName" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="Email" class="form-label">E-mail</label>
                            <input type="email" class="form-control" id="Email" name="email" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Participants Table -->
    <table class="table table-striped mt-4">
        <thead>
            <tr>
                <th>Participant Name</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($participants as $participant): ?>
                <tr>
                    <td><?php echo htmlspecialchars($participant['ParticipantName']); ?></td>
                    <td><?php echo htmlspecialchars($participant['Email']); ?></td>
                    <td>
                        <a href="manage_participants.php?action=delete&id=<?php echo $participant['ParticipantID']; ?>&event_id=<?php echo $eventID; ?>" class="btn btn-danger btn-sm">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <a href="index.php" class="btn btn-secondary">Back to Events</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

