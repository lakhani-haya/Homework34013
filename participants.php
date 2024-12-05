<?php
include 'dbconfig.php';

// Fetch participants
$queryParticipants = "SELECT Participants.ParticipantID, Participants.ParticipantName, Participants.Email, Events.EventName 
                      FROM Participants 
                      JOIN Events ON Participants.EventID = Events.EventID";
$stmtParticipants = $conn->prepare($queryParticipants);
$stmtParticipants->execute();
$participants = $stmtParticipants->fetchAll(PDO::FETCH_ASSOC);

// Fetch events for dropdown
$queryEvents = "SELECT EventID, EventName FROM Events";
$stmtEvents = $conn->prepare($queryEvents);
$stmtEvents->execute();
$events = $stmtEvents->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Participants</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <div class="row mb-3">
        <div class="col">
            <h1>All Participants</h1>
        </div>
        <div class="col-auto">
            <!-- Button to open modal -->
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newParticipantModal">
                Add Participant
            </button>
        </div>
    </div>

    <!-- Display participants table -->
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Event</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($participants as $participant): ?>
                <tr>
                    <td><?php echo htmlspecialchars($participant['ParticipantName']); ?></td>
                    <td><?php echo htmlspecialchars($participant['Email']); ?></td>
                    <td><?php echo htmlspecialchars($participant['EventName']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Modal -->
<div class="modal fade" id="newParticipantModal" tabindex="-1" aria-labelledby="newParticipantModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="newParticipantModalLabel">Add New Participant</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form for adding participants -->
                <form method="post" action="manage_participants.php?action=add">
                    <div class="mb-3">
                        <label for="ParticipantName" class="form-label">Participant Name</label>
                        <input type="text" class="form-control" id="ParticipantName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="Email" class="form-label">E-mail</label>
                        <input type="email" class="form-control" id="Email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="EventID" class="form-label">Select Event</label>
                        <select class="form-control" id="EventID" name="event_id" required>
                            <option value="" disabled selected>Select an Event</option>
                            <?php foreach ($events as $event): ?>
                                <option value="<?php echo htmlspecialchars($event['EventID']); ?>">
                                    <?php echo htmlspecialchars($event['EventName']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
