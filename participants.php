<?php
include 'dbconfig.php';
include 'view-header.php';

// Fetch Participants
$query = "SELECT * FROM Participants";
$stmt = $conn->prepare($query);
$stmt->execute();
$participants = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Display Notifications
session_start();
$message = isset($_SESSION['message']) ? $_SESSION['message'] : '';
unset($_SESSION['message']);
?>

<h1>Participants</h1>

<!-- Notification -->
<?php if ($message): ?>
<div class="alert alert-success">
    <?php echo htmlspecialchars($message); ?>
</div>
<?php endif; ?>

<!-- Participants Table -->
<table class="table table-striped">
    <thead>
        <tr>
            <th>Participant Name</th>
            <th>Email</th>
            <th>Event</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($participants as $participant): ?>
        <tr>
            <td><?php echo htmlspecialchars($participant['ParticipantName']); ?></td>
            <td><?php echo htmlspecialchars($participant['Email']); ?></td>
            <td><?php echo htmlspecialchars($participant['EventID']); ?></td>
            <td>
                <button class="btn btn-link" data-bs-toggle="modal" data-bs-target="#editParticipantModal" 
                        data-id="<?php echo $participant['ParticipantID']; ?>" 
                        data-name="<?php echo htmlspecialchars($participant['ParticipantName']); ?>"
                        data-email="<?php echo htmlspecialchars($participant['Email']); ?>" 
                        data-event="<?php echo $participant['EventID']; ?>">
                    Edit
                </button>
                <a href="manage_participants.php?action=delete&id=<?php echo $participant['ParticipantID']; ?>" 
                   class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?');">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- Add Participant Button -->
<button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addParticipantModal">Add Participant</button>

<!-- Modals -->
<div class="modal fade" id="addParticipantModal" tabindex="-1" aria-labelledby="addParticipantLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="manage_participants.php?action=add">
                <div class="modal-header">
                    <h5 class="modal-title" id="addParticipantLabel">Add Participant</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label for="name">Name:</label>
                    <input type="text" name="name" id="name" class="form-control" required>
                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email" class="form-control" required>
                    <label for="event_id">Event ID:</label>
                    <input type="number" name="event_id" id="event_id" class="form-control" required>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editParticipantModal" tabindex="-1" aria-labelledby="editParticipantLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="manage_participants.php?action=edit">
                <div class="modal-header">
                    <h5 class="modal-title" id="editParticipantLabel">Edit Participant</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit-id">
                    <label for="edit-name">Name:</label>
                    <input type="text" name="name" id="edit-name" class="form-control" required>
                    <label for="edit-email">Email:</label>
                    <input type="email" name="email" id="edit-email" class="form-control" required>
                    <label for="edit-event-id">Event ID:</label>
                    <input type="number" name="event_id" id="edit-event-id" class="form-control" required>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var editModal = document.getElementById('editParticipantModal');
    editModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var id = button.getAttribute('data-id');
        var name = button.getAttribute('data-name');
        var email = button.getAttribute('data-email');
        var eventID = button.getAttribute('data-event');
        
        document.getElementById('edit-id').value = id;
        document.getElementById('edit-name').value = name;
        document.getElementById('edit-email').value = email;
        document.getElementById('edit-event-id').value = eventID;
    });
});
</script>
<a href="index.php">Back to Events</a>
