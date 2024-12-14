<?php
include 'dbconfig.php';
include 'view-header.php';

$eventID = isset($_GET['event_id']) ? $_GET['event_id'] : null;

if ($eventID) {
    $query = "SELECT p.ParticipantID, p.ParticipantName, p.Email, e.EventName FROM Participants p JOIN Events e ON p.EventID = e.EventID WHERE p.EventID = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$eventID]);
} else {
    $query = "SELECT p.ParticipantID, p.ParticipantName, p.Email, e.EventName FROM Participants p JOIN Events e ON p.EventID = e.EventID";
    $stmt = $conn->query($query);
}

$participants = $stmt->fetchAll(PDO::FETCH_ASSOC);

$query = "SELECT EventID, EventName FROM Events";
$eventStmt = $conn->prepare($query);
$eventStmt->execute();
$events = $eventStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h1>Participants</h1>

<form method="GET">
    <label for="event_id">Filter by Event:</label>
    <select name="event_id" id="event_id" class="form-control" onchange="this.form.submit()">
        <option value="">Select Event</option>
        <?php foreach ($events as $event): ?>
            <option value="<?php echo $event['EventID']; ?>" <?php echo ($eventID == $event['EventID']) ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($event['EventName']); ?>
            </option>
        <?php endforeach; ?>
    </select>
</form>

<button type="button" class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#newParticipantModal">
    Add Participant
</button>

<table class="table table-striped mt-4">
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
            <td><?php echo htmlspecialchars($participant['EventName']); ?></td>
            <td>
                <a href="#" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editParticipantModal"
                   data-id="<?php echo $participant['ParticipantID']; ?>"
                   data-name="<?php echo htmlspecialchars($participant['ParticipantName']); ?>"
                   data-email="<?php echo htmlspecialchars($participant['Email']); ?>"
                   data-eventid="<?php echo $participant['EventID']; ?>">Edit</a>
                <a href="manage_participants.php?action=delete&id=<?php echo $participant['ParticipantID']; ?>&event_id=<?php echo $eventID; ?>"
                   class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this participant?')">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<div class="modal fade" id="newParticipantModal" tabindex="-1" aria-labelledby="newParticipantModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="newParticipantModalLabel">New Participant</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="POST" action="manage_participants.php?action=add">
          <div class="mb-3">
            <label for="ParticipantName" class="form-label">Participant Name</label>
            <input type="text" class="form-control" id="ParticipantName" name="name" required>
          </div>
          <div class="mb-3">
            <label for="Email" class="form-label">E-mail</label>
            <input type="email" class="form-control" id="Email" name="email" required>
          </div>
          <div class="mb-3">
            <label for="EventID" class="form-label">Event</label>
            <select class="form-control" name="event_id" required>
              <?php foreach ($events as $event): ?>
                <option value="<?php echo $event['EventID']; ?>"><?php echo htmlspecialchars($event['EventName']); ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <button type="submit" class="btn btn-primary">Save</button>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="editParticipantModal" tabindex="-1" aria-labelledby="editParticipantModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="editParticipantModalLabel">Edit Participant</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="POST" action="manage_participants.php?action=edit">
          <input type="hidden" id="ParticipantID" name="id">
          <div class="mb-3">
            <label for="ParticipantNameEdit" class="form-label">Participant Name</label>
            <input type="text" class="form-control" id="ParticipantNameEdit" name="name" required>
          </div>
          <div class="mb-3">
            <label for="EmailEdit" class="form-label">E-mail</label>
            <input type="email" class="form-control" id="EmailEdit" name="email" required>
          </div>
          <div class="mb-3">
            <label for="EventIDEdit" class="form-label">Event</label>
            <select class="form-control" id="EventIDEdit" name="event_id" required>
              <?php foreach ($events as $event): ?>
                <option value="<?php echo $event['EventID']; ?>"><?php echo htmlspecialchars($event['EventName']); ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <button type="submit" class="btn btn-primary">Save Changes</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<script>
  var editParticipantModal = document.getElementById('editParticipantModal');
  editParticipantModal.addEventListener('show.bs.modal', function (event) {
    var button = event.relatedTarget;
    var participantID = button.getAttribute('data-id');
    var participantName = button.getAttribute('data-name');
    var email = button.getAttribute('data-email');
    var eventID = button.getAttribute('data-eventid');

    var modal = editParticipantModal.querySelector('form');
    modal.querySelector('#ParticipantID').value = participantID;
    modal.querySelector('#ParticipantNameEdit').value = participantName;
    modal.querySelector('#EmailEdit').value = email;
    modal.querySelector('#EventIDEdit').value = eventID;
  });
</script>

</body>
</html>
