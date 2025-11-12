<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<div class="sidebar bg-dark min-vh-100">
  <div class="d-flex flex-column align-items-center align-items-sm-start px-3 pt-3 text-white">
    <h3 class="fs-4">Company Name</h3>
    <p class="small mb-4">Employee Portal</p>

    <ul class="nav nav-pills flex-column mb-sm-auto mb-0 align-items-center align-items-sm-start w-100" id="menu">
      <li class="nav-item w-100">
        <a href="index.php" class="nav-link px-3 <?php echo ($currentPage == 'index.php') ? 'active bg-primary' : 'text-white'; ?>">
          <i class="fas fa-tachometer-alt me-2"></i> Dashboard
          <?php if($currentPage == 'index.php') echo '<span class="status-dot"></span>'; ?>
        </a>
      </li>
      <li class="w-100">
        <a href="performPage.php" class="nav-link px-3 <?php echo ($currentPage == 'performPage.php') ? 'active bg-primary' : 'text-white'; ?>">
          <i class="fas fa-chart-line me-2"></i> Performance
          <?php if($currentPage == 'performPage.php') echo '<span class="status-dot"></span>'; ?>
        </a>
      </li>
      <li class="w-100">
        <a href="leavePage.php" class="nav-link text-white px-3 <?php echo ($currentPage == 'leavePage.php') ? 'active bg-primary' : 'text-white'; ?>">
          <i class="fas fa-calendar-alt me-2"></i> Leave Request
          <?php if($currentPage == 'leavePage.php') echo '<span class="status-dot"></span>'; ?>
        </a>
      </li>
      <li class="w-100">
        <a href="reportPage.php" class="nav-link text-white px-3 <?php echo ($currentPage == 'reportPage.php') ? 'active bg-primary' : 'text-white'; ?>">
          <i class="fas fa-file-alt me-2"></i> File a Report
          <?php if($currentPage == 'reportPage.php') echo '<span class="status-dot"></span>'; ?>
        </a>
      </li>
      <li class="w-100">
        <a href="resignPage.php" class="nav-link text-white px-3 <?php echo ($currentPage == 'resignPage.php') ? 'active bg-primary' : 'text-white'; ?>">
          <i class="fas fa-sign-out-alt me-2"></i> Resignation
          <?php if($currentPage == 'resignPage.php') echo '<span class="status-dot"></span>'; ?>
        </a>
      </li>
      <li class="w-100">
        <a href="profPage.php" class="nav-link text-white px-3 <?php echo ($currentPage == 'profPage.php') ? 'active bg-primary' : 'text-white'; ?>">
          <i class="fas fa-user me-2"></i> Profile
          <?php if($currentPage == 'profPage.php') echo '<span class="status-dot"></span>'; ?>
        </a>
      </li>
      <li class="w-100">
        <a href="../logout.php" class="nav-link text-white px-3">
          <i class="fas fa-user me-2"></i> Logout
        </a>
      </li>
    </ul>
  </div>
</div>