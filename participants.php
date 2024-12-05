<?php
include 'dbconfig.php';
include 'view-header.php';

// Get Event ID from query parameter
$eventID = isset($_GET['event_id']) ? intval($_GET['event_id']) : 0;

// Fetch Participants for the specific event
$query = "SELECT * FROM Participants WHERE EventID = :event_id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':event_id', $eventID, PDO::PARAM_INT);
$stmt->execute();
$participants = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Display Notifications
session_start();
$message = isset($_SESSION['message']) ? $_SESSION['message'] : '';
unset($_SESSION['message']);
?>

<h1>Participants for Event ID: <?php echo htmlspecialchars($eventID); ?></h1>

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
                        data-email="<?php echo htmlspecialchars($participant['Email']); ?>">
                    Edit
                </button>
                <a href="manage_participants.php?action=delete&id=<?php echo $participant['ParticipantID']; ?>&event_id=<?php echo $eventID; ?>" 
                   class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?');">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- Add Participant Button -->
<button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addParticipantModal">Add Participant</button>

<!-- Add Participant Modal -->
<div class="modal fade" id="addParticipantModal" tabindex="-1" aria-labelledby="addParticipantLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="addParticipantLabel">Add Participant</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="manage_participants.php?action=add&event_id=<?php echo $eventID; ?>">
                    <div class="mb-3">
                        <label for="add-name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="add-name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="add-email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="add-email" name="email" required>
                        <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Participant Modal -->
<div class="modal fade" id="editParticipantModal" tabindex="-1" aria-labelledby="editParticipantLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="editParticipantLabel">Edit Participant</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="manage_participants.php?action=edit&event_id=<?php echo $eventID; ?>">
                    <input type="hidden" name="id" id="edit-id">
                    <div class="mb-3">
                        <label for="edit-name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="edit-name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit-email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="edit-email" name="email" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
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

        document.getElementById('edit-id').value = id;
        document.getElementById('edit-name').value = name;
        document.getElementById('edit-email').value = email;
    });
});
</script>

<a href="index.php">Back to Events</a>
