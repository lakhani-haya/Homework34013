<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Participants</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

  <div class="container mt-4">
    <div class="row">
      <div class="col">
        <h1>Participants</h1>
      </div>
      <div class="col-auto">
        <!-- Button to trigger modal -->
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newParticipantModal">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
          </svg>
        </button>
      </div>
    </div>

    <!-- Modal for adding participant -->
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
                  <?php
                  // Fetch events for the dropdown
                  include 'dbconfig.php';
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

    <!-- Show Participants Table -->
    <div class="table-responsive mt-4">
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
          <?php
          // Fetch participants and display them in the table
          include 'dbconfig.php';
          $query = "SELECT p.ParticipantID, p.ParticipantName, p.Email, e.EventName FROM Participants p JOIN Events e ON p.EventID = e.EventID";
          $stmt = $conn->prepare($query);
          $stmt->execute();
          $participants = $stmt->fetchAll(PDO::FETCH_ASSOC);

          foreach ($participants as $participant) {
            echo "<tr>
                    <td>" . htmlspecialchars($participant['ParticipantName']) . "</td>
                    <td>" . htmlspecialchars($participant['Email']) . "</td>
                    <td>" . htmlspecialchars($participant['EventName']) . "</td>
                    <td>
                      <a href='#' class='btn btn-warning btn-sm' data-bs-toggle='modal' data-bs-target='#editParticipantModal' data-id='" . $participant['ParticipantID'] . "'>Edit</a>
                      <a href='manage_participants.php?action=delete&id=" . $participant['ParticipantID'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                    </td>
                  </tr>";
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Bootstrap JS and necessary scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

