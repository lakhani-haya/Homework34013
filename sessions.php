<?php
include 'dbconfig.php';
include 'view-header.php';

$eventID = isset($_GET['event_id']) ? $_GET['event_id'] : null;

if ($eventID) {
    $query = "SELECT * FROM Sessions WHERE EventID = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$eventID]);
} else {
    $query = "SELECT * FROM Sessions";
    $stmt = $conn->query($query);
}

$sessions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h1>Sessions</h1>


<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newSessionModal">
  Add Session
</button>


<table class="table table-striped mt-4">
    <thead>
        <tr>
            <th>Session Name</th>
            <th>Duration</th>
            <th>View Speakers</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($sessions as $session): ?>
        <tr>
            <td><?php echo htmlspecialchars($session['SessionName']); ?></td>
            <td><?php echo htmlspecialchars($session['Duration']); ?></td>
            <td><a href="speakers.php?session_id=<?php echo $session['SessionID']; ?>">View Speakers</a></td>
            <td>
                <a href="#" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editSessionModal" data-id="<?php echo $session['SessionID']; ?>" data-name="<?php echo htmlspecialchars($session['SessionName']); ?>" data-duration="<?php echo htmlspecialchars($session['Duration']); ?>" data-eventid="<?php echo $session['EventID']; ?>">Edit</a> |
                <a href="manage_sessions.php?action=delete&id=<?php echo $session['SessionID']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this session?')">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>


<div class="modal fade" id="newSessionModal" tabindex="-1" aria-labelledby="newSessionModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="newSessionModalLabel">New Session</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="POST" action="manage_sessions.php?action=add">
          <div class="mb-3">
            <label for="SessionName" class="form-label">Session Name</label>
            <input type="text" class="form-control" id="SessionName" name="SessionName" required>
          </div>
          <div class="mb-3">
            <label for="Duration" class="form-label">Duration</label>
            <input type="text" class="form-control" id="Duration" name="Duration" required>
          </div>
          <div class="mb-3">
            <label for="EventID" class="form-label">Select Event</label>
            <select class="form-control" id="EventID" name="EventID" required>
              <?php
              // Fetch events for the dropdown
              $eventQuery = "SELECT EventID, EventName FROM Events";
              $eventStmt = $conn->prepare($eventQuery);
              $eventStmt->execute();
              $events = $eventStmt->fetchAll(PDO::FETCH_ASSOC);
              foreach ($events as $event) {
                echo "<option value='" . $event['EventID'] . "'>" . htmlspecialchars($event['EventName']) . "</option>";
              }
              ?>
            </select>
          </div>
          <button type="submit" class="btn btn-primary">Save</button>
        </form>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="editSessionModal" tabindex="-1" aria-labelledby="editSessionModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="editSessionModalLabel">Edit Session</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="POST" action="manage_sessions.php?action=edit">
          <input type="hidden" id="SessionID" name="SessionID">
          <div class="mb-3">
            <label for="SessionName" class="form-label">Session Name</label>
            <input type="text" class="form-control" id="SessionNameEdit" name="SessionName" required>
          </div>
          <div class="mb-3">
            <label for="Duration" class="form-label">Duration</label>
            <input type="text" class="form-control" id="DurationEdit" name="Duration" required>
          </div>
          <div class="mb-3">
            <label for="EventID" class="form-label">Select Event</label>
            <select class="form-control" id="EventIDEdit" name="EventID" required>
              <?php
              // Fetch events for the dropdown
              foreach ($events as $event) {
                echo "<option value='" . $event['EventID'] . "'>" . htmlspecialchars($event['EventName']) . "</option>";
              }
              ?>
            </select>
          </div>
          <button type="submit" class="btn btn-primary">Save Changes</button>
        </form>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="notificationModal" tabindex="-1" aria-labelledby="notificationModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="notificationModalLabel">Notification</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p id="notificationMessage">Action completed successfully!</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<script>
  
  var editSessionModal = document.getElementById('editSessionModal');
  editSessionModal.addEventListener('show.bs.modal', function (event) {
    var button = event.relatedTarget;
    var sessionID = button.getAttribute('data-id');
    var sessionName = button.getAttribute('data-name');
    var duration = button.getAttribute('data-duration');
    var eventID = button.getAttribute('data-eventid');

    var modal = editSessionModal.querySelector('form');
    modal.querySelector('#SessionID').value = sessionID;
    modal.querySelector('#SessionNameEdit').value = sessionName;
    modal.querySelector('#DurationEdit').value = duration;
    modal.querySelector('#EventIDEdit').value = eventID;
  });


  function showNotification(message) {
    document.getElementById('notificationMessage').innerText = message;
    var notificationModal = new bootstrap.Modal(document.getElementById('notificationModal'));
    notificationModal.show();
  }

  
  <?php if (isset($_SESSION['message'])): ?>
    showNotification('<?php echo $_SESSION['message']; ?>');
    <?php unset($_SESSION['message']); ?>
  <?php endif; ?>
</script>

</body>
</html>

