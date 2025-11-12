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
  <title>Company Name - HR Promotions</title>
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
          <h2 class="fw-bold">Employee Management</h2>
          <div class="d-flex align-items-center">
            <div class="bg-primary text-white rounded-circle d-flex justify-content-center align-items-center me-2" style="width:45px; height:45px;">HR</div>
            <div>
              <h6 class="mb-0"><?php echo $_SESSION['employeeName']; ?></h6>
              <small><?php echo $_SESSION['employeePosition']; ?></small>
            </div>
          </div>
        </div>

        <div class="card shadow-sm">
          <div class="card-header bg-primary text-white d-flex align-items-center">
            <i class="fas fa-chart-line me-2"></i>
            <h5 class="mb-0">Promotions</h5>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                  <tr>
                    <th>Employee Name</th>
                    <th>ID</th>
                    <th>Department</th>
                    <th>Position</th>
                    <th>Status</th>
                    <th>Recommended By</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>Juan Dela Cruz</td>
                    <td>123451</td>
                    <td>Marketing</td>
                    <td>Social Media Management</td>
                    <td><span class="badge bg-success">Full Time</span></td>
                    <td>Roberto Dizon</td>
                    <td>
                      <button class="btn btn-success btn-sm" onclick="approvePromotion('Juan Dela Cruz')">
                        <i class="fas fa-check me-1"></i> Accept
                      </button>
                    </td>
                  </tr>
                  <tr>
                    <td>Boyet Lopez</td>
                    <td>124513</td>
                    <td>IT</td>
                    <td>Network Engineer</td>
                    <td><span class="badge bg-success">Full Time</span></td>
                    <td>Maria Santos</td>
                    <td>
                      <button class="btn btn-success btn-sm" onclick="approvePromotion('Boyet Lopez')">
                        <i class="fas fa-check me-1"></i> Accept
                      </button>
                    </td>
                  </tr>
                  <tr>
                    <td>Maria Santos</td>
                    <td>124514</td>
                    <td>HR</td>
                    <td>HR Specialist</td>
                    <td><span class="badge bg-success">Full Time</span></td>
                    <td>John Smith</td>
                    <td>
                      <button class="btn btn-success btn-sm" onclick="approvePromotion('Maria Santos')">
                        <i class="fas fa-check me-1"></i> Accept
                      </button>
                    </td>
                  </tr>
                  <tr>
                    <td>Robert Lim</td>
                    <td>124515</td>
                    <td>Finance</td>
                    <td>Senior Accountant</td>
                    <td><span class="badge bg-success">Full Time</span></td>
                    <td>Lisa Johnson</td>
                    <td>
                      <button class="btn btn-warning btn-sm" disabled>
                        <i class="fas fa-clock me-1"></i> Pending
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

  <script src="./bootstrap/js/bootstrap.bundle.js"></script>

  <script>
    function approvePromotion(employeeName) {
      alert("Promotion approved for " + employeeName + "!");
    }
  </script>
</body>
</html>