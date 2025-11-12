<?php

session_start();
require "../functions.php";
require "../connection.php";
redirectToLogin('Employee');

$employee_id = $_SESSION['employeeID'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Employee Dashboard | Company Name</title>
  <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../css/Global.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>
<body>
  <div class="container-fluid">
    <div class="row flex-nowrap">
      
      <?php include 'nav.php'; ?>

      <!-- Main Content -->
      <div class="col py-4 px-5">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
          <h2 class="fw-bold">Employee Dashboard</h2>
          <div class="d-flex align-items-center">
            <div class="bg-primary text-white rounded-circle d-flex justify-content-center align-items-center me-2" style="width:45px; height:45px;">EN</div>
            <div>
              <h6 class="mb-0"><?php echo $_SESSION['employeeName']; ?></h6>
              <small><?php echo $_SESSION['employeePosition']; ?></small>
            </div>
          </div>
        </div>

        <!-- Dashboard Summary Cards -->
        <div class="row g-3 mb-4">
          <div class="col-md-3 col-sm-6">
            <div class="card shadow-sm text-center p-3">
              <i class="fas fa-calendar-check fa-2x text-primary mb-2"></i>
              <h4>10</h4>
              <p class="mb-0">Total Leaves</p>
            </div>
          </div>
          <div class="col-md-3 col-sm-6">
            <div class="card shadow-sm text-center p-3">
              <i class="fas fa-star fa-2x text-success mb-2"></i>
              <h4>4.8</h4>
              <p class="mb-0">Performance Rating</p>
            </div>
          </div>
          <div class="col-md-3 col-sm-6">
            <div class="card shadow-sm text-center p-3">
              <i class="fas fa-clock fa-2x text-warning mb-2"></i>
              <h4>3</h4>
              <p class="mb-0">Pending Requests</p>
            </div>
          </div>
          <div class="col-md-3 col-sm-6">
            <div class="card shadow-sm text-center p-3">
              <i class="fas fa-tasks fa-2x text-danger mb-2"></i>
              <h4>5</h4>
              <p class="mb-0">Active Projects</p>
            </div>
          </div>
        </div>

        <!-- Recent Activities -->
        <div class="card shadow-sm mb-4">
          <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-history me-2"></i>Recent Activities</h5>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-bordered table-hover align-middle">
                <thead> 
                  <tr>
                    <th>Date</th>
                    <th>Activity</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>Oct. 20, 2025</td>
                    <td>Submitted leave request for vacation</td>
                    <td><span class="badge bg-warning text-dark">Pending</span></td>
                  </tr>
                  <tr>
                    <td>Oct. 10, 2025</td>
                    <td>Performance review completed</td>
                    <td><span class="badge bg-success">Completed</span></td>
                  </tr>
                  <tr>
                    <td>Sept. 28, 2025</td>
                    <td>Submitted project progress report</td>
                    <td><span class="badge bg-success">Approved</span></td>
                  </tr>
                  <tr>
                    <td>Sept. 15, 2025</td>
                    <td>Filed sick leave</td>
                    <td><span class="badge bg-success">Approved</span></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- Quick Links -->
        <div class="card shadow-sm">
          <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-link me-2"></i>Quick Access</h5>
          </div>
          <div class="card-body">
            <div class="row g-3">
              <div class="col-md-4">
                <a href="leavePage.php" class="btn btn-outline-primary w-100"><i class="fas fa-calendar-alt me-2"></i>Request Leave</a>
              </div>
              <div class="col-md-4">
                <a href="performPage.php" class="btn btn-outline-success w-100"><i class="fas fa-chart-line me-2"></i>View Performance</a>
              </div>
              <div class="col-md-4">
                <a href="reportPage.php" class="btn btn-outline-danger w-100"><i class="fas fa-file-alt me-2"></i>File a Report</a>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>

  <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
