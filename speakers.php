<?php
include 'dbconfig.php';
include 'view-header.php';

$sessionID = isset($_GET['session_id']) ? $_GET['session_id'] : null;

if ($sessionID) {
    $query = "SELECT * FROM Speakers WHERE SessionID = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$sessionID]);
} else {
    $query = "SELECT * FROM Speakers";
    $stmt = $conn->query($query);
}

$speakers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h1>Speakers</h1>

<!-- Button to trigger Add Speaker Modal -->
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newSpeakerModal">
  Add Speaker
</button>

<!-- Filter by Session -->
<form method="GET" class="mt-3">
    <label for="session_id">Filter by Session:</label>
    <select name="session_id" id="session_id" class="form-control" onchange="this.form.submit()">
        <option value="">Select Session</option>
        <?php
        // Fetch sessions for the dropdown
        $sessionQuery = "SELECT SessionID, SessionName FROM Sessions";
        $sessionStmt = $conn->prepare($sessionQuery);
        $sessionStmt->execute();
        $sessions = $sessionStmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($sessions as $session) {
            $selected = ($sessionID == $session['SessionID']) ? 'selected' : '';
            echo "<option value='" . $session['SessionID'] . "' $selected>" . htmlspecialchars($session['SessionName']) . "</option>";
        }
        ?>
    </select>
</form>

<!-- Speakers Table -->
<table class="table table-striped mt-4">
    <thead>
        <tr>
            <th>Speaker Name</th>
            <th>Topic</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($speakers as $speaker): ?>
        <tr>
            <td><?php echo htmlspecialchars($speaker['SpeakerName']); ?></td>
            <td><?php echo htmlspecialchars($speaker['Topic']); ?></td>
            <td>
                <a href="#" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editSpeakerModal" data-id="<?php echo $speaker['SpeakerID']; ?>" data-name="<?php echo htmlspecialchars($speaker['SpeakerName']); ?>" data-topic="<?php echo htmlspecialchars($speaker['Topic']); ?>" data-sessionid="<?php echo $speaker['SessionID']; ?>">Edit</a> |
                <a href="manage_speakers.php?action=delete&id=<?php echo $speaker['SpeakerID']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this speaker?')">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- Modal for Adding New Speaker -->
<div class="modal fade" id="newSpeakerModal" tabindex="-1" aria-labelledby="newSpeakerModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="newSpeakerModalLabel">New Speaker</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="POST" action="manage_speakers.php?action=add">
          <div class="mb-3">
            <label for="SpeakerName" class="form-label">Speaker Name</label>
            <input type="text" class="form-control" id="SpeakerName" name="SpeakerName" required>
          </div>
          <div class="mb-3">
            <label for="Topic" class="form-label">Topic</label>
            <input type="text" class="form-control" id="Topic" name="Topic" required>
          </div>
          <div class="mb-3">
            <label for="SessionID" class="form-label">Select Session</label>
            <select class="form-control" id="SessionID" name="SessionID" required>
              <?php
              // Fetch sessions for the dropdown
              foreach ($sessions as $session) {
                echo "<option value='" . $session['SessionID'] . "'>" . htmlspecialchars($session['SessionName']) . "</option>";
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

<!-- Modal for Editing Speaker -->
<div class="modal fade" id="editSpeakerModal" tabindex="-1" aria-labelledby="editSpeakerModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="editSpeakerModalLabel">Edit Speaker</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="POST" action="manage_speakers.php?action=edit">
          <input type="hidden" id="SpeakerID" name="SpeakerID">
          <div class="mb-3">
            <label for="SpeakerNameEdit" class="form-label">Speaker Name</label>
            <input type="text" class="form-control" id="SpeakerNameEdit" name="SpeakerName" required>
          </div>
          <div class="mb-3">
            <label for="TopicEdit" class="form-label">Topic</label>
            <input type="text" class="form-control" id="TopicEdit" name="Topic" required>
          </div>
          <div class="mb-3">
            <label for="SessionIDEdit" class="form-label">Select Session</label>
            <select class="form-control" id="SessionIDEdit" name="SessionID" required>
              <?php
              // Fetch sessions for the dropdown
              foreach ($sessions as $session) {
                echo "<option value='" . $session['SessionID'] . "'>" . htmlspecialchars($session['SessionName']) . "</option>";
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

<!-- Custom Notification Modal -->
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

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<script>
  // Fill the edit modal with data when the Edit button is clicked
  var editSpeakerModal = document.getElementById('editSpeakerModal');
  editSpeakerModal.addEventListener('show.bs.modal', function (event) {
    var button = event.relatedTarget;
    var speakerID = button.getAttribute('data-id');
    var speakerName = button.getAttribute('data-name');
    var topic = button.getAttribute('data-topic');
    var sessionID = button.getAttribute('data-sessionid');

    var modal = editSpeakerModal.querySelector('form');
    modal.querySelector('#SpeakerID').value = speakerID;
    modal.querySelector('#SpeakerNameEdit').value = speakerName;
    modal.querySelector('#TopicEdit').value = topic;
    modal.querySelector('#SessionIDEdit').value = sessionID;
  });

  // Function to show notification modal
  function showNotification(message) {
    document.getElementById('notificationMessage').innerText = message;
    var notificationModal = new bootstrap.Modal(document.getElementById('notificationModal'));
    notificationModal.show();
  }

  // Check for session message on page load (success or error message)
  <?php if (isset($_SESSION['message'])): ?>
    showNotification('<?php echo $_SESSION['message']; ?>');
    <?php unset($_SESSION['message']); ?>
  <?php endif; ?>
</script>

</body>
</html>
