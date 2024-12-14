<?php
$pageTitle = 'Events';
include 'view-header.php';
include 'dbconfig.php';

// Fetch events from the database
$query = "SELECT * FROM Events";
$stmt = $conn->query($query);
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h1>Events</h1>

<!-- Button to trigger add event modal -->
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newEventModal">
  Add Event
</button>

<!-- Event Table -->
<table class="table table-striped mt-4">
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
          <a href="#" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editEventModal" data-id="<?php echo $event['EventID']; ?>" data-name="<?php echo htmlspecialchars($event['EventName']); ?>" data-date="<?php echo htmlspecialchars($event['EventDate']); ?>" data-location="<?php echo htmlspecialchars($event['Location']); ?>">Edit</a> |
          <a href="manage_events.php?action=delete&id=<?php echo $event['EventID']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this event?')">Delete</a>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<!-- Modal for Adding New Event -->
<div class="modal fade" id="newEventModal" tabindex="-1" aria-labelledby="newEventModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="newEventModalLabel">New Event</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="POST" action="manage_events.php?action=add">
          <div class="mb-3">
            <label for="EventName" class="form-label">Event Name</label>
            <input type="text" class="form-control" id="EventName" name="EventName" required>
          </div>
          <div class="mb-3">
            <label for="EventDate" class="form-label">Event Date</label>
            <input type="date" class="form-control" id="EventDate" name="EventDate" required>
          </div>
          <div class="mb-3">
            <label for="Location" class="form-label">Location</label>
            <input type="text" class="form-control" id="Location" name="Location" required>
          </div>
          <button type="submit" class="btn btn-primary">Save</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Modal for Editing Event -->
<div class="modal fade" id="editEventModal" tabindex="-1" aria-labelledby="editEventModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="editEventModalLabel">Edit Event</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="POST" action="manage_events.php?action=edit">
          <input type="hidden" id="EventID" name="EventID">
          <div class="mb-3">
            <label for="EventName" class="form-label">Event Name</label>
            <input type="text" class="form-control" id="EventNameEdit" name="EventName" required>
          </div>
          <div class="mb-3">
            <label for="EventDate" class="form-label">Event Date</label>
            <input type="date" class="form-control" id="EventDateEdit" name="EventDate" required>
          </div>
          <div class="mb-3">
            <label for="Location" class="form-label">Location</label>
            <input type="text" class="form-control" id="LocationEdit" name="Location" required>
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
  var editEventModal = document.getElementById('editEventModal');
  editEventModal.addEventListener('show.bs.modal', function (event) {
    var button = event.relatedTarget;
    var eventID = button.getAttribute('data-id');
    var eventName = button.getAttribute('data-name');
    var eventDate = button.getAttribute('data-date');
    var location = button.getAttribute('data-location');

    var modal = editEventModal.querySelector('form');
    modal.querySelector('#EventID').value = eventID;
    modal.querySelector('#EventNameEdit').value = eventName;
    modal.querySelector('#EventDateEdit').value = eventDate;
    modal.querySelector('#LocationEdit').value = location;
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

