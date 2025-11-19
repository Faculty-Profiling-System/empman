<?php
session_start();
require "../../connection.php";
if (!isset($_SESSION['candidate_id'])) {
    header("Location: ../login.php");
    exit();
}

?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Acme Corp Job Portal</title>
        <link rel="stylesheet" href="../../bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap">
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <div class="d-flex" id="wrapper">

            <!-- Sidebar -->
            <aside id="sidebar-wrapper" class="border-end bg-primary text-white">
                <div class="sidebar-header p-4 text-center">
                    <div class="avatar mb-2">AC</div>
                    <div class="fw-bold">Acme Corp</div>
                    <small class="d-block text-white-50">Candidate Portal</small>
                </div>
                <div class="list-group list-group-flush px-2 pb-3">
                                <button class="list-group-item list-group-item-action bg-transparent text-white active"
                                                data-bs-toggle="tab" data-bs-target="#jobs" aria-controls="jobs">
                                    <svg class="bi icon" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true" focusable="false">
                                        <rect x="2" y="5" width="12" height="8" rx="1"></rect>
                                        <path d="M5 5V4a3 3 0 0 1 6 0v1"></path>
                                    </svg>
                                    <span class="visually-hidden">Jobs</span>
                                    <span class="d-inline-block ms-1">Jobs</span>
                                </button>

                                <button class="list-group-item list-group-item-action bg-transparent text-white"
                                                data-bs-toggle="tab" data-bs-target="#applications" aria-controls="applications">
                                    <svg class="bi icon" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true" focusable="false">
                                        <path d="M4 1h6l3 3v10a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1z"></path>
                                        <path d="M9 1v3a1 1 0 0 0 1 1h3"></path>
                                    </svg>
                                    <span class="visually-hidden">Applications</span>
                                    <span class="d-inline-block ms-1">Applications</span>
                                </button>

                                <button class="list-group-item list-group-item-action bg-transparent text-white"
                                                data-bs-toggle="tab" data-bs-target="#profile" aria-controls="profile">
                                    <svg class="bi icon" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true" focusable="false">
                                        <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                    </svg>
                                    <span class="visually-hidden">Profile</span>
                                    <span class="d-inline-block ms-1">Profile</span>
                                </button>
                </div>
            </aside>

            <!-- Page Content -->
            <div id="page-content-wrapper" class="w-100">
                <!-- Navbar -->
                <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm">
                    <div class="container-fluid">
                        <div class="d-flex align-items-center">
                            <button class="btn btn-icon me-3" id="menu-toggle">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z"/>
                                </svg>
                            </button>
                            <a class="navbar-brand fw-bold text-primary mb-0 d-flex align-items-center" href="#" onclick="return false;">
                                <span class="bg-primary text-white rounded-3 p-2 me-2">AC</span>
                                Job Portal
                            </a>
                        </div>
                        <div class="collapse navbar-collapse">
                            <ul class="navbar-nav ms-auto align-items-center">
                                <li class="nav-item">
                                    <a id="notificationsBtn" class="nav-link px-3" href="#" title="Notifications">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2zm.995-14.901a1 1 0 1 0-1.99 0A5.002 5.002 0 0 0 3 6c0 1.098-.5 6-2 7h14c-1.5-1-2-5.902-2-7 0-2.42-1.72-4.44-4.005-4.901z"/>
                                        </svg>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <button class="nav-link px-3 border-0 bg-transparent" id="themeModeToggle" title="Toggle Theme">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="theme-icon-light" viewBox="0 0 16 16">
                                            <path d="M8 11a3 3 0 1 1 0-6 3 3 0 0 1 0 6zm0 1a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM8 0a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 0zm0 13a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 13zm8-5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2a.5.5 0 0 1 .5.5zM3 8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2A.5.5 0 0 1 3 8zm10.657-5.657a.5.5 0 0 1 0 .707l-1.414 1.415a.5.5 0 1 1-.707-.708l1.414-1.414a.5.5 0 0 1 .707 0zm-9.193 9.193a.5.5 0 0 1 0 .707L3.05 13.657a.5.5 0 0 1-.707-.707l1.414-1.414a.5.5 0 0 1 .707 0zm9.193 2.121a.5.5 0 0 1-.707 0l-1.414-1.414a.5.5 0 0 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .707zM4.464 4.465a.5.5 0 0 1-.707 0L2.343 3.05a.5.5 0 1 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .708z"/>
                                        </svg>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="theme-icon-dark d-none" viewBox="0 0 16 16">
                                            <path d="M6 .278a.768.768 0 0 1 .08.858 7.208 7.208 0 0 0-.878 3.46c0 4.021 3.278 7.277 7.318 7.277.527 0 1.04-.055 1.533-.16a.787.787 0 0 1 .81.316.733.733 0 0 1-.031.893A8.349 8.349 0 0 1 8.344 16C3.734 16 0 12.286 0 7.71 0 4.266 2.114 1.312 5.124.06A.752.752 0 0 1 6 .278z"/>
                                        </svg>
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button class="btn btn-outline-danger rounded-pill ms-3" onclick="window.location.href='logout.php'">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="me-2" viewBox="0 0 16 16">
                                            <path d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0v2z"/>
                                            <path d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z"/>
                                        </svg>
                                        Sign Out
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>

                <!-- Hero / quick info -->
                <div class="container-fluid p-4">
                    <div class="hero p-4 mb-4">
                        <div class="row align-items-center">
                            <div class="col-lg-8">
                                <h3 class="display-6 fw-bold mb-3">Explore opportunities at Acme Corp</h3>
                                <p class="lead mb-4">Browse open roles, save your profile, and submit applications — tailored for you.</p>
                                <div class="d-flex gap-3">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-building text-primary" viewBox="0 0 16 16">
                                                <path d="M4 2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1Zm3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1Zm3.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1ZM4 5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1ZM7.5 5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1Zm2.5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1ZM4.5 8a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1Zm2.5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1Zm3.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1Z"/>
                                                <path d="M2 1a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V1Zm11 0H3v14h3v-2.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 .5.5V15h3V1Z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block">Global Offices</small>
                                            <span class="fw-semibold">15+ Locations</span>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-people text-primary" viewBox="0 0 16 16">
                                                <path d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1h8Zm-7.978-1A.261.261 0 0 1 7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002a.274.274 0 0 1-.014.002H7.022ZM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4Zm3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM6.936 9.28a5.88 5.88 0 0 0-1.23-.247A7.35 7.35 0 0 0 5 9c-4 0-5 3-5 4 0 .667.333 1 1 1h4.216A2.238 2.238 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816ZM4.92 10A5.493 5.493 0 0 0 4 13H1c0-.26.164-1.03.76-1.724.545-.636 1.492-1.256 3.16-1.275ZM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0Zm3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4Z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block">Team Size</small>
                                            <span class="fw-semibold">5000+ Employees</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 d-none d-lg-block text-end">
                                <img src="https://images.unsplash.com/photo-1552664730-d307ca884978?auto=format&fit=crop&w=300&q=80" 
                                     alt="Office" class="img-fluid rounded-4 shadow-sm" style="max-width: 300px;">
                            </div>
                        </div>
                    </div>

                    <main class="container-fluid p-0">
                        <div class="tab-content">

                            <!-- PROFILE -->
                            <section class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                <div class="row g-4">
                                    <div class="col-lg-4">
                                        <div class="card shadow-sm" style="background: linear-gradient(135deg, #0d6efd 0%, #084298 100%); border: none;">
                                            <div class="card-body text-center position-relative">
                                                <div class="avatar-lg mx-auto mb-3" style="border: 4px solid white;">AC</div>
                                                <h5 id="profileName" class="card-title fw-bold mb-1">Your name</h5>
                                                <p id="profileEmail" class="text-muted small mb-3">your.email@example.com</p>
                                                <p class="small text-muted px-3">A short bio will appear here after you save your profile.</p>
                                                <hr class="my-4">
                                                <div class="d-flex justify-content-around text-center">
                                                    <div>
                                                        <h6 class="fw-bold mb-1" id="profileApplicationsCount">0</h6>
                                                        <small class="text-muted">Applications</small>
                                                    </div>
                                                    <div>
                                                        <h6 class="fw-bold mb-1" id="profileSavedJobsCount">0</h6>
                                                        <small class="text-muted">Saved Jobs</small>
                                                    </div>
                                                </div>
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
                                                        <input type="text" id="fullName" class="form-control" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">Email</label>
                                                        <input type="email" id="email" class="form-control" required>
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
                                                    <button class="btn btn-primary px-4">Save Profile</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </section>

                            <!-- JOBS -->
                            <section class="tab-pane fade show active" id="jobs">
                                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
                                    <div>
                                        <h4 class="fw-bold mb-1">Available Jobs</h4>
                                        <p class="text-muted mb-0">Find your next opportunity at Acme Corp</p>
                                    </div>
                                    <div class="d-flex flex-column flex-md-row gap-3">
                                        <div class="dropdown">
                                            <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi me-2" viewBox="0 0 16 16">
                                                    <path d="M6 10.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 0 1h-3a.5.5 0 0 1-.5-.5zm-2-3a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zm-2-3a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11a.5.5 0 0 1-.5-.5z"/>
                                                </svg>
                                                Salary Range
                                            </button>
                                            <div class="dropdown-menu p-3" style="min-width: 280px">
                                                <div class="mb-3">
                                                    <label class="form-label">Minimum Salary</label>
                                                    <input type="number" class="form-control" id="minSalary" placeholder="PHP" min="0">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Maximum Salary</label>
                                                    <input type="number" class="form-control" id="maxSalary" placeholder="PHP" min="0">
                                                </div>
                                                <div class="d-grid gap-2">
                                                    <button class="btn btn-primary" id="applySalaryFilter">Filter</button>
                                                    <button class="btn btn-outline-secondary" id="resetSalaryFilter">Reset Filter</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="input-group">
                                            <input type="search" class="form-control" id="jobSearch" placeholder="Search jobs..." aria-label="Search jobs">
                                            <button class="btn btn-outline-primary" type="button" id="searchBtn">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                                    <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="row gy-4" id="jobsList"></div>
                                <div id="jobsEmpty" class="d-none">
                                    <div class="card shadow-sm">
                                        <div class="card-body empty-illustration">
                                            <svg viewBox="0 0 64 64" width="120" height="120" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <rect x="8" y="18" width="48" height="28" rx="3" stroke="#cfe2ff" stroke-width="2" fill="#e9f2ff"/>
                                                <circle cx="20" cy="30" r="3" fill="#9fc5ff"/>
                                                <rect x="28" y="28" width="18" height="2" rx="1" fill="#9fc5ff"/>
                                                <rect x="28" y="32" width="12" height="2" rx="1" fill="#9fc5ff"/>
                                            </svg>
                                            <div>
                                                <h6 class="mb-1">No jobs found</h6>
                                                <p class="small text-muted mb-0">Try adjusting your filters or check back later.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>

                            <!-- APPLICATIONS -->
                            <section class="tab-pane fade" id="applications">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <div>
                                        <h4 class="fw-bold mb-1">My Applications</h4>
                                        <p class="text-muted mb-0">Track your job application status</p>
                                    </div>
                                    <div class="dropdown">
                                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            <span>All Applications</span>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item active" href="#">All Applications</a></li>
                                            <li><a class="dropdown-item" href="#">Pending Review</a></li>
                                            <li><a class="dropdown-item" href="#">Under Consideration</a></li>
                                            <li><a class="dropdown-item" href="#">Completed</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card shadow-sm">
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle mb-0">
                                            <thead>
                                                <tr>
                                                    <th class="ps-4">Job Title</th>
                                                    <th>Company</th>
                                                    <th>Status</th>
                                                    <th class="pe-4">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody id="applicationsTable">
                                                <tr>
                                                    <td colspan="4" class="text-center py-5">
                                                        <div class="empty-illustration">
                                                            <svg viewBox="0 0 64 64" width="64" height="64" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <rect x="8" y="18" width="48" height="28" rx="3" stroke="#cfe2ff" stroke-width="2" fill="#e9f2ff"/>
                                                                <circle cx="20" cy="30" r="3" fill="#9fc5ff"/>
                                                                <rect x="28" y="28" width="18" height="2" rx="1" fill="#9fc5ff"/>
                                                                <rect x="28" y="32" width="12" height="2" rx="1" fill="#9fc5ff"/>
                                                            </svg>
                                                            <h6 class="mt-3 mb-1">No applications yet</h6>
                                                            <p class="text-muted mb-0">Start applying to jobs to see them here</p>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </section>

                        </div>
                    </main>
                </div>
            </div>
        </div>

        <!-- Apply Modal -->
        <div class="modal fade" id="applyModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form id="applyForm">
                        <div class="modal-header">
                            <h5 class="modal-title">Apply for Job</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <h6 id="jobTitleText" class="fw-bold"></h6>
                            <p id="jobDeptText" class="text-muted small"></p>
                            <div class="mb-3">
                                <label class="form-label">Cover Letter</label>
                                <textarea id="coverLetter" class="form-control" rows="4" required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-primary" type="submit">Submit Application</button>
                            <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script src="../../bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="script.js"></script>
    </body>
</html>