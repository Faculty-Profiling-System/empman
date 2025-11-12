<?php
session_start();
require "../functions.php";
require "../connection.php";
redirectToLogin('HR');

?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Company Name - HR Violations</title>
  <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../css/Global.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>
<body>
  <div class="container-fluid">
    <div class="row flex-nowrap">

      <!-- Sidebar -->
            <?php include 'nav.php' ?>


      <!-- Main Content -->
      <div class="col py-4 px-5">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
          <h2 class="fw-bold">Violations</h2>
          <div class="d-flex align-items-center">
            <div class="bg-primary text-white rounded-circle d-flex justify-content-center align-items-center me-2" style="width:45px; height:45px;">HR</div>
            <div>
              <h6 class="mb-0"><?php echo $_SESSION['employeeName']; ?></h6>
              <small><?php echo $_SESSION['employeePosition']; ?></small>
            </div>
          </div>
        </div>

        <!-- Violations Card -->
        <div class="card shadow-sm">
          <div class="card-header bg-primary text-white d-flex align-items-center">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <h5 class="mb-0">Policy Violations</h5>
          </div>
          <div class="card-body">

            <!-- Sort Dropdown -->
            <div class="mb-3">
              <label class="form-label fw-semibold">Sort By:</label>
              <select class="form-select w-auto d-inline-block ms-2">
                <option>Employee Name</option>
                <option>ID</option>
                <option>Position</option>
                <option>Email</option>
                <option>Status</option>
              </select>
            </div>

            <!-- Employees Section -->
            <div class="mb-4">
              <h5 class="fw-bold border-bottom pb-2 mb-3"><i class="fas fa-users me-2 text-primary"></i>Employees</h5>
              <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle" id="employeesTable">
                  <thead class="table-light">
                    <tr>
                      <th>Employee Name</th>
                      <th>ID</th>
                      <th>Position</th>
                      <th>Email</th>
                      <th>Status</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>Jenie Smith</td>
                      <td>12352</td>
                      <td>Junior Back End Developer</td>
                      <td>jen@gmail.com</td>
                      <td><span class="badge bg-success">Reviewed</span></td>
                      <td>
                        <button type="button"
                          class="btn btn-outline-primary btn-sm me-1"
                          data-bs-toggle="modal" data-bs-target="#reviewModal"
                          data-emp="Jenie Smith"
                          data-id="12352"
                          data-violation="Attendance"
                          data-date="2025-10-10"
                          data-desc="Missed 3 consecutive days without notification."
                          data-status="Reviewed"
                          onclick="openReviewModalFromBtn(this)">
                          <i class="fas fa-eye me-1"></i>Review
                        </button>

                        <button type="button"
                          class="btn btn-info btn-sm text-white"
                          data-bs-toggle="modal" data-bs-target="#infoModal"
                          data-emp="Jenie Smith"
                          data-id="12352"
                          data-violation="Attendance"
                          data-date="2025-10-10"
                          data-desc="Missed 3 consecutive days without notification."
                          data-status="Reviewed"
                          onclick="openInfoModalFromBtn(this)">
                          <i class="fas fa-info-circle me-1"></i>More Info
                        </button>
                      </td>
                    </tr>

                    <tr>
                      <td>Krith Brown</td>
                      <td>56433</td>
                      <td>Senior Network Engineer</td>
                      <td>krith@gmail.com</td>
                      <td><span class="badge bg-warning text-dark">In Progress</span></td>
                      <td>
                        <button type="button"
                          class="btn btn-outline-primary btn-sm me-1"
                          data-bs-toggle="modal" data-bs-target="#reviewModal"
                          data-emp="Krith Brown"
                          data-id="56433"
                          data-violation="Performance"
                          data-date="2025-09-30"
                          data-desc="Repeated missed deadlines and low quality deliverables."
                          data-status="In Progress"
                          onclick="openReviewModalFromBtn(this)">
                          <i class="fas fa-eye me-1"></i>Review
                        </button>

                        <button type="button"
                          class="btn btn-info btn-sm text-white"
                          data-bs-toggle="modal" data-bs-target="#infoModal"
                          data-emp="Krith Brown"
                          data-id="56433"
                          data-violation="Performance"
                          data-date="2025-09-30"
                          data-desc="Repeated missed deadlines and low quality deliverables."
                          data-status="In Progress"
                          onclick="openInfoModalFromBtn(this)">
                          <i class="fas fa-info-circle me-1"></i>More Info
                        </button>
                      </td>
                    </tr>

                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>

  <!-- REVIEW MODAL -->
  <div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <form id="reviewForm">
          <div class="modal-header bg-primary text-white">
            <h5 class="modal-title" id="reviewModalLabel"><i class="fas fa-gavel me-2"></i>Review Violation</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>

          <div class="modal-body">
            <input type="hidden" id="reviewEmpId">

            <div class="mb-3">
              <label class="form-label fw-semibold">Employee Name</label>
              <input type="text" id="reviewEmployeeName" class="form-control" readonly>
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold">Violation Type</label>
              <select id="reviewViolationType" class="form-select" required>
                <option value="Attendance">Attendance</option>
                <option value="Performance">Performance</option>
                <option value="Behavior">Behavior</option>
                <option value="Policy Breach">Policy Breach</option>
              </select>
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold">Date of Violation</label>
              <input type="date" id="reviewDate" class="form-control" required>
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold">Remarks</label>
              <textarea id="reviewRemarks" class="form-control" rows="3" placeholder="Enter remarks..."></textarea>
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold">Status</label>
              <select id="reviewStatusSelect" class="form-select" required>
                <option>In Review</option>
                <option>Reviewed</option>
                <option>Resolved</option>
              </select>
            </div>
          </div>

          <div class="modal-footer">
            <button type="submit" class="btn btn-success">
              <i class="fas fa-save me-1"></i>Save Review
            </button>
            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
              <i class="fas fa-times me-1"></i>Close
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- INFO MODAL -->
  <div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-info text-white">
          <h5 class="modal-title" id="infoModalLabel"><i class="fas fa-info-circle me-2"></i>Violation Details</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p><strong>Employee:</strong> <span id="infoEmployeeName">N/A</span> (ID: <span id="infoEmployeeId">N/A</span>)</p>
          <p><strong>Violation:</strong> <span id="infoViolation">N/A</span></p>
          <p><strong>Date:</strong> <span id="infoDate">N/A</span></p>
          <p><strong>Description:</strong> <span id="infoDesc">N/A</span></p>
          <p><strong>Status:</strong> <span id="infoStatus" class="badge bg-secondary">N/A</span></p>
        </div>
        <div class="modal-footer">
          <button class="btn btn-danger" data-bs-dismiss="modal">
            <i class="fas fa-times me-1"></i>Close
          </button>
        </div>
      </div>
    </div>
  </div>

  <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>

  <script>

    // Called by each Review button
    function openReviewModalFromBtn(thisBtn) {
      try {
        const emp = thisBtn.dataset.emp || '';
        const id = thisBtn.dataset.id || '';
        const violation = thisBtn.dataset.violation || '';
        const date = thisBtn.dataset.date || '';
        const desc = thisBtn.dataset.desc || '';
        const status = thisBtn.dataset.status || '';

        // Populate inputs
        document.getElementById('reviewEmpId').value = id;
        document.getElementById('reviewEmployeeName').value = emp;
        document.getElementById('reviewViolationType').value = violation;
        document.getElementById('reviewDate').value = date;
        document.getElementById('reviewRemarks').value = desc;
        document.getElementById('reviewStatusSelect').value = status === '' ? 'In Review' : status;

        console.log('openReviewModalFromBtn:', {emp, id, violation, date, status});
      } catch (err) {
        console.error('openReviewModalFromBtn error:', err);
      }
    }

    // Called by each More Info button
    function openInfoModalFromBtn(thisBtn) {
      try {
        const emp = thisBtn.dataset.emp || '';
        const id = thisBtn.dataset.id || '';
        const violation = thisBtn.dataset.violation || '';
        const date = thisBtn.dataset.date || '';
        const desc = thisBtn.dataset.desc || '';
        const status = thisBtn.dataset.status || '';

        document.getElementById('infoEmployeeName').textContent = emp;
        document.getElementById('infoEmployeeId').textContent = id;
        document.getElementById('infoViolation').textContent = violation;
        document.getElementById('infoDate').textContent = date;
        document.getElementById('infoDesc').textContent = desc;
        const infoStatusEl = document.getElementById('infoStatus');
        infoStatusEl.textContent = status || 'N/A';

        // set badge style
        infoStatusEl.className = 'badge ' + (status === 'Reviewed' ? 'bg-success' : (status === 'In Progress' ? 'bg-warning text-dark' : 'bg-secondary'));

        console.log('openInfoModalFromBtn:', {emp, id, violation, date, status});
      } catch (err) {
        console.error('openInfoModalFromBtn error:', err);
      }
    }

    // Handle review form submit
    document.getElementById('reviewForm').addEventListener('submit', function (e) {
      e.preventDefault();
      try {
        const id = document.getElementById('reviewEmpId').value;
        const name = document.getElementById('reviewEmployeeName').value;
        const type = document.getElementById('reviewViolationType').value;
        const date = document.getElementById('reviewDate').value;
        const remarks = document.getElementById('reviewRemarks').value;
        const status = document.getElementById('reviewStatusSelect').value;

        // For demo we just log and show an alert; replace with AJAX to server when ready
        console.log('Saving review:', {id, name, type, date, remarks, status});
        alert('Review saved for ' + name + ' (ID: ' + id + ').');

        // Close modal programmatically
        const reviewModalEl = document.getElementById('reviewModal');
        const bsModal = bootstrap.Modal.getInstance(reviewModalEl) || new bootstrap.Modal(reviewModalEl);
        bsModal.hide();

        // Find the table row by employee id (search in table cells)
        const rows = document.querySelectorAll('#employeesTable tbody tr');
        rows.forEach(r => {
          if (r.cells[1] && r.cells[1].textContent.trim() === id) {
            const statusCell = r.cells[4];
            // map status to badge class
            const badgeMap = {
              'In Review': 'bg-warning text-dark',
              'Reviewed': 'bg-success',
              'Resolved': 'bg-primary'
            };
            statusCell.innerHTML = `<span class="badge ${badgeMap[status] || 'bg-secondary'}">${status}</span>`;
          }
        });
      } catch (err) {
        console.error('reviewForm submit error:', err);
        alert('An error occurred while saving. Check console for details.');
      }
    });
  </script>
</body>
</html>