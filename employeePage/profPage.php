    <?php
    session_start();
    require "../functions.php";
    require "../connection.php";
    redirectToLogin('Employee');

    $currentEmployeeID = $_SESSION['employeeID'];

    // Fetch employee basic information including email from candidate's user account
    $employeeQuery = "SELECT e.employee_id, e.date_hired, e.employment_status, e.status,
                            c.first_name, c.last_name, c.date_of_birth, c.phone_number, c.address,
                            p.position_name, d.department_name,
                            u.login_identifier as email
                    FROM employees e
                    JOIN candidates c ON e.candidate_id = c.candidate_id
                    JOIN applications a ON e.application_id = a.application_id
                    JOIN positions p ON a.position_id = p.position_id
                    JOIN departments d ON p.department_id = d.department_id
                    JOIN user_accounts u ON c.candidate_id = u.account_id
                    WHERE e.employee_id = '$currentEmployeeID'";
    $employeeResult = mysqli_query($con, $employeeQuery);
    $employee = mysqli_fetch_assoc($employeeResult);

    // Rest of your queries remain the same...
    $educationQuery = "SELECT * FROM educational_background 
                    WHERE candidate_id = (SELECT candidate_id FROM employees WHERE employee_id = '$currentEmployeeID')
                    ORDER BY FIELD(education_level, 'Doctorate', 'Masters', 'College', 'Senior High', 'High School')";
    $educationResult = mysqli_query($con, $educationQuery);
    $educations = [];
    while ($row = mysqli_fetch_assoc($educationResult)) {
        $educations[$row['education_level']] = $row;
    }

    $workQuery = "SELECT * FROM work_experience 
                WHERE candidate_id = (SELECT candidate_id FROM employees WHERE employee_id = '$currentEmployeeID')
                ORDER BY start_date DESC";
    $workResult = mysqli_query($con, $workQuery);
    $workExperiences = mysqli_fetch_all($workResult, MYSQLI_ASSOC);

    $skillsQuery = "SELECT * FROM skills 
                    WHERE candidate_id = (SELECT candidate_id FROM employees WHERE employee_id = '$currentEmployeeID')
                    ORDER BY proficiency_level DESC";
    $skillsResult = mysqli_query($con, $skillsQuery);
    $skills = mysqli_fetch_all($skillsResult, MYSQLI_ASSOC);

    $certsQuery = "SELECT * FROM certifications 
                WHERE candidate_id = (SELECT candidate_id FROM employees WHERE employee_id = '$currentEmployeeID')
                ORDER BY certification_id DESC";
    $certsResult = mysqli_query($con, $certsQuery);
    $certifications = mysqli_fetch_all($certsResult, MYSQLI_ASSOC);

    // Format date for display
    $hireDate = date('F d, Y', strtotime($employee['date_hired']));
    $birthDate = date('F d, Y', strtotime($employee['date_of_birth']));
    $fullName = $employee['first_name'] . ' ' . $employee['last_name'];
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile | Employee Portal</title>
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/Global.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    </head>
    <body class="bg-light">
        <div class="container-fluid">
            <div class="row flex-nowrap">

            <?php include 'nav.php'; ?>

            <!-- Main Content -->
            <div class="col py-4 px-5">
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
                <h2 class="fw-bold">Profile</h2>
                <div class="d-flex align-items-center">
                    <div class="bg-primary text-white rounded-circle d-flex justify-content-center align-items-center me-2" style="width:45px; height:45px;">EN</div>
                    <div>
                        <h6 class="mb-0"><?php echo $fullName; ?></h6>
                        <small><?php echo $employee['position_name']; ?></small>
                    </div>
                </div>
                </div>

                <!-- Profile Overview Header -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="fw-semibold">Profile Overview</h4>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editInfoModal">
                    <i class="fas fa-edit me-1"></i> Edit Information
                </button>
                </div>

                <!-- Profile Information Cards -->
                <div class="row g-4">

                <!-- Basic Information -->
                <div class="col-lg-6">
                    <div class="card shadow-sm h-100">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-id-badge me-2"></i> Basic Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3"><strong>Full Name:</strong> <?php echo $fullName; ?></div>
                        <div class="mb-3"><strong>Employee ID:</strong> <?php echo $employee['employee_id']; ?></div>
                        <div class="mb-3"><strong>Position:</strong> <?php echo $employee['position_name']; ?></div>
                        <div class="mb-3"><strong>Department:</strong> <?php echo $employee['department_name']; ?></div>
                        <div class="mb-3"><strong>Employment Status:</strong> <?php echo $employee['employment_status']; ?></div>
                        <div class="mb-3"><strong>Hire Date:</strong> <?php echo $hireDate; ?></div>
                        <div class="mb-3"><strong>Date of Birth:</strong> <?php echo $birthDate; ?></div>
                    </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="col-lg-6">
                    <div class="card shadow-sm h-100">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-address-book me-2"></i> Contact Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3"><strong>Contact Number:</strong> <?php echo $employee['phone_number'] ?: 'Not provided'; ?></div>
                        <div class="mb-3"><strong>Email:</strong> <?php echo $employee['email']; ?></div>
                        <div class="mb-3"><strong>Address:</strong> <?php echo $employee['address'] ?: 'Not provided'; ?></div>
                    </div>
                    </div>
                </div>

                <!-- Educational Background -->
                <div class="col-12">
                    <div class="card shadow-sm">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="fas fa-graduation-cap me-2"></i> Educational Background</h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($educations)): ?>
                            <?php foreach ($educations as $level => $education): ?>
                                <div class="mb-3">
                                    <strong><?php echo $education['education_level']; ?>:</strong>
                                    <?php echo $education['school_name']; ?>
                                    <?php if ($education['degree']): ?>(<?php echo $education['degree']; ?>)<?php endif; ?>
                                    <?php if ($education['year_graduated']): ?> - Graduated <?php echo $education['year_graduated']; ?><?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-muted">No educational background information available.</p>
                        <?php endif; ?>
                    </div>
                    </div>
                </div>

                <!-- Work Experience -->
                <div class="col-lg-6">
                    <div class="card shadow-sm h-100">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0"><i class="fas fa-briefcase me-2"></i> Work Experience</h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($workExperiences)): ?>
                            <?php foreach ($workExperiences as $work): ?>
                                <div class="mb-3">
                                    <strong><?php echo $work['position_title']; ?></strong><br>
                                    <em><?php echo $work['company_name']; ?></em><br>
                                    <?php echo date('M Y', strtotime($work['start_date'])); ?> - 
                                    <?php echo $work['end_date'] ? date('M Y', strtotime($work['end_date'])) : 'Present'; ?>
                                    <?php if ($work['description']): ?>
                                        <br><small><?php echo $work['description']; ?></small>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-muted">No work experience information available.</p>
                        <?php endif; ?>
                    </div>
                    </div>
                </div>

                <!-- Skills & Certifications -->
                <div class="col-lg-6">
                    <div class="card shadow-sm h-100">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0"><i class="fas fa-code me-2"></i> Skills & Certifications</h5>
                    </div>
                    <div class="card-body">
                        <h6>Skills:</h6>
                        <?php if (!empty($skills)): ?>
                            <div class="mb-3">
                                <?php foreach ($skills as $skill): ?>
                                    <span class="badge bg-primary me-1 mb-1">
                                        <?php echo $skill['skill_name']; ?> (<?php echo $skill['proficiency_level']; ?>)
                                    </span>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="text-muted">No skills information available.</p>
                        <?php endif; ?>

                        <h6>Certifications:</h6>
                        <?php if (!empty($certifications)): ?>
                            <div>
                                <?php foreach ($certifications as $cert): ?>
                                    <div class="mb-2">
                                        <strong><?php echo $cert['certificate_name']; ?></strong><br>
                                        <a href="<?php echo $cert['file_link']; ?>" target="_blank" class="text-decoration-none">
                                            <small>View Certificate</small>
                                        </a>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="text-muted">No certifications available.</p>
                        <?php endif; ?>
                    </div>
                    </div>
                </div>
                </div>
                
                <!-- Profile Modal -->
                <div class="modal fade" id="editInfoModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-xl">
                        <div class="modal-content">
                        <form id="editProfileForm">
                            
                            <div class="modal-header">
                                <h5 class="modal-title"><i class="fas fa-user-edit me-2"></i>Edit Employee Resume</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">

                            <!-- Personal Information -->
                            <h6 class="fw-bold text-primary mb-3" style="font-size: 20px;"><i class="fa-solid fa-id-card me-2"></i>Personal Information</h6>
                            <div class="border border-secondary rounded p-3 mb-3">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Full Name</label>
                                        <input type="text" class="form-control" value="<?php echo $fullName; ?>" readonly>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Birth Date</label>
                                        <input type="date" class="form-control" value="<?php echo $employee['date_of_birth']; ?>" readonly>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Employee ID</label>
                                        <input type="text" class="form-control" value="<?php echo $employee['employee_id']; ?>" disabled>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Department</label>
                                        <input type="text" class="form-control" value="<?php echo $employee['department_name']; ?>" readonly>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Position</label>
                                        <input type="text" class="form-control" value="<?php echo $employee['position_name']; ?>" readonly>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Employment Status</label>
                                        <input type="text" class="form-control" value="<?php echo $employee['employment_status']; ?>" readonly>
                                    </div>
                                </div>
                            </div>

                            <!-- Contact Information -->
                            <h6 class="fw-bold text-primary mt-4 mb-3" style="font-size: 20px;"><i class="fa-solid fa-address-book me-2"></i>Contact Information</h6>
                            <div class="border border-secondary rounded p-3 mb-3">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" class="form-control" value="<?php echo $employee['email']; ?>" readonly>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Contact Number</label>
                                        <input type="text" class="form-control" value="<?php echo $employee['phone_number']; ?>">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Address</label>
                                    <input type="text" class="form-control" value="<?php echo $employee['address']; ?>">
                                </div>
                            </div>

                            <!-- Educational Background -->
                            <h6 class="fw-bold text-primary mt-4 mb-3" style="font-size: 20px;"><i class="fa-solid fa-graduation-cap me-2"></i>Educational Background</h6>
                            <?php foreach ($educations as $level => $education): ?>
                            <div class="border border-secondary rounded p-3 mb-3">
                                <h6 class="fw-semibold text-white mb-4 mt-3" style="font-size: 17px;"><?php echo $education['education_level']; ?></h6>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">School Name</label>
                                        <input type="text" class="form-control" value="<?php echo $education['school_name']; ?>">
                                    </div>
                                    <?php if ($education['degree']): ?>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Degree/Course</label>
                                        <input type="text" class="form-control" value="<?php echo $education['degree']; ?>">
                                    </div>
                                    <?php endif; ?>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Year Graduated</label>
                                        <input type="text" class="form-control" value="<?php echo $education['year_graduated']; ?>">
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>

                            <!-- Work Experience -->
                            <h6 class="fw-bold text-primary mt-4 mb-3" style="font-size: 20px;"><i class="fa-solid fa-briefcase me-2"></i>Work Experience</h6>
                            <?php foreach ($workExperiences as $work): ?>
                            <div class="border border-secondary rounded p-3 mb-3">
                                <div>
                                    <div class="mb-3">
                                        <label class="form-label">Company Name</label>
                                        <input type="text" class="form-control" value="<?php echo $work['company_name']; ?>">
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Position Held</label>
                                            <input type="text" class="form-control" value="<?php echo $work['position_title']; ?>">
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">From</label>
                                            <input type="month" class="form-control" value="<?php echo date('Y-m', strtotime($work['start_date'])); ?>">
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">To</label>
                                            <input type="month" class="form-control" value="<?php echo $work['end_date'] ? date('Y-m', strtotime($work['end_date'])) : ''; ?>">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Description</label>
                                        <textarea class="form-control" rows="3"><?php echo $work['description']; ?></textarea>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>

                            <!-- Skills -->
                            <h6 class="fw-bold text-primary mt-4 mb-3" style="font-size: 20px;"><i class="fa-solid fa-code me-2"></i>Skills</h6>
                            <div class="border border-secondary rounded p-3 mb-3">
                                <div class="mb-3">
                                    <label class="form-label">Technical Skills</label>
                                    <input type="text" class="form-control" value="<?php 
                                        $skillNames = array_column($skills, 'skill_name');
                                        echo implode(', ', $skillNames);
                                    ?>">
                                </div>
                            </div>

                            <!-- Certifications -->
                            <h6 class="fw-bold text-primary mt-4 mb-3" style="font-size: 20px;"><i class="fa-solid fa-certificate me-2"></i>Certifications</h6>
                            <?php foreach ($certifications as $cert): ?>
                            <div class="border border-secondary rounded p-3 mb-3">
                                <div class="mb-3">
                                    <label class="form-label">Certification Title</label>
                                    <input type="text" class="form-control" value="<?php echo $cert['certificate_name']; ?>">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Certificate Link</label>
                                    <input type="text" class="form-control" value="<?php echo $cert['file_link']; ?>">
                                </div>
                            </div>
                            <?php endforeach; ?>

                            <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Save Changes
                            </button>
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                                <i class="fa-solid fa-ban me-1"></i> Cancel
                            </button>
                            </div>

                        </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
        <script>
            document.getElementById("editProfileForm").addEventListener("submit", function(event) {
                event.preventDefault(); // Prevents the page from reloading
                alert("Your profile changes have been saved successfully!");
                
                // Close modal after alert
                const modal = bootstrap.Modal.getInstance(document.getElementById("editInfoModal"));
                modal.hide();
            });
        </script>
    </body>
    </html>