<?php
session_start();
require "../functions.php";
require "../connection.php";
redirectToLogin('HR');

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>HR Dashboard | Company Name</title>
  <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../css/Global.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>
<body>
  <div class="container-fluid">
    <div class="row flex-nowrap">
      
      <?php include 'nav.php' ?>

      <!-- Main Content -->
      <div class="col py-4 px-5">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
          <h2 class="fw-bold">HR Dashboard</h2>
          <div class="d-flex align-items-center">
            <div class="bg-primary text-white rounded-circle d-flex justify-content-center align-items-center me-2" style="width:45px; height:45px;">HR</div>
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
              <i class="fas fa-users fa-2x text-primary mb-2"></i>
              <h4>156</h4>
              <p class="mb-0">Total Employees</p>
            </div>
          </div>
          <div class="col-md-3 col-sm-6">
            <div class="card shadow-sm text-center p-3">
              <i class="fas fa-user-plus fa-2x text-success mb-2"></i>
              <h4>12</h4>
              <p class="mb-0">Active Recruitments</p>
            </div>
          </div>
          <div class="col-md-3 col-sm-6">
            <div class="card shadow-sm text-center p-3">
              <i class="fas fa-calendar-check fa-2x text-warning mb-2"></i>
              <h4>8</h4>
              <p class="mb-0">Pending Leaves</p>
            </div>
          </div>
          <div class="col-md-3 col-sm-6">
            <div class="card shadow-sm text-center p-3">
              <i class="fas fa-exclamation-triangle fa-2x text-danger mb-2"></i>
              <h4>3</h4>
              <p class="mb-0">Open Violations</p>
            </div>
          </div>
        </div>

        <!-- Recent Activity Table -->
        <div class="card shadow-sm mb-4">
          <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-history me-2"></i>Recent Activities</h5>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-bordered table-hover align-middle ">
                <thead> 
                  <tr>
                    <th>Employee Name</th>
                    <th>Department</th>
                    <th>Position</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>Juan Dela Cruz</td>
                    <td>Marketing</td>
                    <td>Social Media Manager</td>
                    <td><span class="badge bg-success">Active</span></td>
                  </tr>
                  <tr>
                    <td>Boyet Lopez</td>
                    <td>IT</td>
                    <td>Network Engineer</td>
                    <td><span class="badge bg-success">Active</span></td>
                  </tr>
                  <tr>
                    <td>Maria Santos</td>
                    <td>HR</td>
                    <td>HR Specialist</td>
                    <td><span class="badge bg-warning text-dark">On Leave</span></td>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- Quick Access -->
        <div class="card shadow-sm">
          <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-link me-2"></i>Quick Access</h5>
          </div>
          <div class="card-body">
            <div class="row g-3">
              <div class="col-md-3">
                <a href="employeePage.php" class="btn btn-outline-primary w-100"><i class="fas fa-users me-2"></i>Manage Employees</a>
              </div>
              <div class="col-md-3">
                <a href="recruitPage.php" class="btn btn-outline-success w-100"><i class="fas fa-user-plus me-2"></i>Recruitment</a>
              </div>
              <div class="col-md-3">
                <a href="promotePage.php" class="btn btn-outline-warning w-100"><i class="fas fa-chart-line me-2"></i>Promotions</a>
              </div>
              <div class="col-md-3">
                <a href="violatePage.php" class="btn btn-outline-danger w-100"><i class="fas fa-exclamation-triangle me-2"></i>Violations</a>
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