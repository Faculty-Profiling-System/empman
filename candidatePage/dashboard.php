<?php 
session_start();
?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Acme Corp Job Portal</title>
        <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <div class="d-flex" id="wrapper">

            <!-- Sidebar -->
            <aside id="sidebar-wrapper" class="border-end bg-primary text-white d-flex flex-column">

                <div class="sidebar-header p-4 text-center">
                    <div class="avatar mb-2">AC</div>
                    <div class="fw-bold">Acme Corp</div>
                    <small class="d-block text-white-50">Candidate Portal</small>
                </div>

                <div class="list-group list-group-flush px-2 pb-3 flex-grow-1">
                    <button class="list-group-item list-group-item-action bg-transparent text-white active"
                            data-bs-toggle="tab" data-bs-target="#profile">
                        <svg class="bi icon" viewBox="0 0 16 16" fill="currentColor">
                            <circle cx="8" cy="5" r="2.5"></circle>
                            <path d="M2 14s1-4 6-4 6 4 6 4H2z"></path>
                        </svg>
                        <span class="d-inline-block ms-1">Profile</span>
                    </button>

                    <button class="list-group-item list-group-item-action bg-transparent text-white"
                            data-bs-toggle="tab" data-bs-target="#jobs">
                        <svg class="bi icon" viewBox="0 0 16 16" fill="currentColor">
                            <rect x="2" y="5" width="12" height="8" rx="1"></rect>
                            <path d="M5 5V4a3 3 0 0 1 6 0v1"></path>
                        </svg>
                        <span class="d-inline-block ms-1">Jobs</span>
                    </button>

                    <button class="list-group-item list-group-item-action bg-transparent text-white"
                            data-bs-toggle="tab" data-bs-target="#applications">
                        <svg class="bi icon" viewBox="0 0 16 16" fill="currentColor">
                            <path d="M4 1h6l3 3v10a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1z"></path>
                            <path d="M9 1v3a1 1 0 0 0 1 1h3"></path>
                        </svg>
                        <span class="d-inline-block ms-1">Applications</span>
                    </button>
                </div>

                <!-- Logout button at bottom -->
                <div class="p-3 border-top">
                    <a href="login.php" class="btn btn-danger w-100">Logout</a>
                </div>

            </aside>

            <!-- Page Content -->
            <div id="page-content-wrapper" class="w-100">
                
                <!-- Navbar -->
                <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm">
                    <div class="container-fluid">
                        <div class="d-flex align-items-center">
                            <button class="btn btn-outline-primary me-2" id="menu-toggle">✖</button>
                            <a class="navbar-brand fw-bold text-primary mb-0" href="#">Job Portal</a>
                        </div>

                        <div class="ms-auto d-flex align-items-center">
                            <span class="me-3 fw-bold text-primary">
                                Welcome, 
                                <?php echo $_SESSION['candidate_firstName'] . ' ' . $_SESSION['candidate_lastName']; ?>
                            </span>
                        </div>
                    </div>
                </nav>

                <!-- Hero / quick info -->
                <div class="container-fluid p-4">
                    <div class="hero p-3 mb-4">
                        <h3 class="mb-1 fw-bold">Explore opportunities at Acme Corp</h3>
                        <p class="lead mb-0">Browse open roles, save your profile, and submit applications — tailored for you.</p>
                    </div>

                    <main class="container-fluid p-0">
                        <div class="tab-content">

                            <!-- PROFILE -->
                            <section class="tab-pane fade show active" id="profile">
                                <div class="row g-4">

                                    <div class="col-lg-4">
                                        <div class="card shadow-sm">
                                            <div class="card-body text-center">
                                                <div class="avatar-lg mx-auto mb-3">AC</div>
                                                <h5 id="profileName" class="card-title fw-bold">
                                                    <?php echo $_SESSION['candidate_firstName'] . ' ' . $_SESSION['candidate_lastName']; ?>
                                                </h5>
                                                <p id="profileEmail" class="text-muted small">
                                                    <?php echo $_SESSION['candidate_email']; ?>
                                                </p>
                                                <p class="small text-muted">A short bio will appear here after you save your profile.</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-8">
                                        <h4 class="fw-bold mb-3">My Profile</h4>
                                        <div class="card shadow-sm p-3">

                                            <form id="profileForm">
                                                <div class="row g-3">

                                                    <div class="col-md-6">
                                                        <label class="form-label">Full Name</label>
                                                        <input type="text" id="fullName" class="form-control"
                                                            value="<?php echo $_SESSION['candidate_firstName'] . ' ' . $_SESSION['candidate_lastName']; ?>" 
                                                            required>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label class="form-label">Email</label>
                                                        <input type="email" id="email" class="form-control"
                                                            value="<?php echo $_SESSION['candidate_email']; ?>" 
                                                            required>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label class="form-label">Phone</label>
                                                        <input type="text" id="phone" class="form-control">
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label class="form-label">LinkedIn / Portfolio</label>
                                                        <input type="url" id="portfolio" class="form-control" placeholder="https://">
                                                    </div>

                                                    <div class="col-12">
                                                        <label class="form-label">About / Bio</label>
                                                        <textarea id="bio" class="form-control" rows="4"></textarea>
                                                    </div>
                                                </div>

                                                <div class="mt-3 text-end">
                                                    <button class="btn btn-primary px-4" type="submit">Save Profile</button>
                                                </div>
                                            </form>

                                        </div>
                                    </div>

                                </div>
                            </section>

                            <!-- JOBS -->
                            <section class="tab-pane fade" id="jobs">
                                <h4 class="fw-bold mb-3">Available Jobs</h4>
                                <div class="row gy-4" id="jobsList"></div>
                                <div id="jobsEmpty" class="d-none">No jobs found.</div>
                            </section>

                            <!-- APPLICATIONS -->
                            <section class="tab-pane fade" id="applications">
                                <h4 class="fw-bold mb-3">My Applications</h4>
                                <div class="card shadow-sm p-3">
                                    <table class="table table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Job Title</th>
                                                <th>Company</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody id="applicationsTable">
                                            <tr><td colspan="4" class="text-muted">No applications yet.</td></tr>
                                        </tbody>
                                    </table>
                                </div>
                            </section>

                        </div>
                    </main>
                </div>
            </div>
        </div>

        <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="script.js"></script>
    </body>
</html>