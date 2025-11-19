<?php
session_start();
require "../functions.php";
require "../connection.php";
redirectToLogin('Employee');

$currentEmployeeID = $_SESSION['employeeID'];
$employeeFirstName = $_SESSION['firstName'];
$employeeLastName = $_SESSION['lastName'];

// Fetch employee basic information including email from candidate's user account
$employeeQuery = "SELECT e.employee_id, e.date_hired, e.employment_status, e.status,
                        c.candidate_id, c.first_name, c.last_name, c.date_of_birth, c.phone_number, c.address,
                        p.position_name, d.department_name,
                        u.login_identifier as email
                FROM employees e
                JOIN candidates c ON e.candidate_id = c.candidate_id
                JOIN positions p ON e.position_id = p.position_id
                JOIN departments d ON p.department_id = d.department_id
                JOIN user_accounts u ON c.candidate_id = u.account_id
                WHERE e.employee_id = '$currentEmployeeID'";
$employeeResult = mysqli_query($con, $employeeQuery);
$employee = mysqli_fetch_assoc($employeeResult);

$candidate_id = $employee['candidate_id'];

// Handle form submission for updating profile
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Update personal and contact information
    if (isset($_POST['update_profile'])) {
        $firstName = mysqli_real_escape_string($con, $_POST['first_name']);
        $lastName = mysqli_real_escape_string($con, $_POST['last_name']);
        $phoneNumber = mysqli_real_escape_string($con, $_POST['phone_number']);
        $address = mysqli_real_escape_string($con, $_POST['address']);
        
        // Update candidate information
        $updateCandidateQuery = "UPDATE candidates SET 
                                first_name = '$firstName',
                                last_name = '$lastName',
                                phone_number = '$phoneNumber',
                                address = '$address'
                                WHERE candidate_id = '$candidate_id'";
        
        if (mysqli_query($con, $updateCandidateQuery)) {
            $_SESSION['message'] = "Profile updated successfully!";
            $_SESSION['message_type'] = "success";
            
            // Update session variables
            $_SESSION['employeeName'] = $firstName . ' ' . $lastName;
            $_SESSION['firstName'] = $firstName;
            $_SESSION['lastName'] = $lastName;
        } else {
            $_SESSION['message'] = "Error updating profile: " . mysqli_error($con);
            $_SESSION['message_type'] = "error";
        }
    }
    
    // Update education information
    if (isset($_POST['update_education'])) {
        $educationUpdated = false;
        foreach ($_POST['education'] as $educationId => $educationData) {
            $schoolName = mysqli_real_escape_string($con, $educationData['school_name']);
            $degree = mysqli_real_escape_string($con, $educationData['degree']);
            $yearGraduated = mysqli_real_escape_string($con, $educationData['year_graduated']);
            
            $updateEducationQuery = "UPDATE educational_background SET 
                                   school_name = '$schoolName',
                                   degree = '$degree',
                                   year_graduated = '$yearGraduated'
                                   WHERE education_id = '$educationId' AND candidate_id = '$candidate_id'";
            if (mysqli_query($con, $updateEducationQuery)) {
                $educationUpdated = true;
            }
        }
        
        if ($educationUpdated) {
            $_SESSION['message'] = "Education information updated successfully!";
            $_SESSION['message_type'] = "success";
        }
    }
    
    // Update work experience
    if (isset($_POST['update_work_experience'])) {
        $workUpdated = false;
        foreach ($_POST['work_experience'] as $experienceId => $workData) {
            $companyName = mysqli_real_escape_string($con, $workData['company_name']);
            $positionTitle = mysqli_real_escape_string($con, $workData['position_title']);
            $startDate = mysqli_real_escape_string($con, $workData['start_date']);
            $endDate = mysqli_real_escape_string($con, $workData['end_date']);
            $description = mysqli_real_escape_string($con, $workData['description']);
            
            // Convert month input to proper date format
            $startDate = $startDate ? $startDate . '-01' : null;
            $endDate = $endDate ? $endDate . '-01' : null;
            
            $updateWorkQuery = "UPDATE work_experience SET 
                              company_name = '$companyName',
                              position_title = '$positionTitle',
                              start_date = " . ($startDate ? "'$startDate'" : "NULL") . ",
                              end_date = " . ($endDate ? "'$endDate'" : "NULL") . ",
                              description = '$description'
                              WHERE experience_id = '$experienceId' AND candidate_id = '$candidate_id'";
            if (mysqli_query($con, $updateWorkQuery)) {
                $workUpdated = true;
            }
        }
        
        if ($workUpdated) {
            $_SESSION['message'] = "Work experience updated successfully!";
            $_SESSION['message_type'] = "success";
        }
    }
    
    // Handle adding new skill
    if (isset($_POST['add_skill'])) {
        $skillName = mysqli_real_escape_string($con, $_POST['skill_name']);
        $proficiencyLevel = mysqli_real_escape_string($con, $_POST['proficiency_level']);
        
        $addSkillQuery = "INSERT INTO skills (candidate_id, skill_name, proficiency_level) 
                         VALUES ('$candidate_id', '$skillName', '$proficiencyLevel')";
        
        if (mysqli_query($con, $addSkillQuery)) {
            $_SESSION['message'] = "Skill added successfully!";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Error adding skill: " . mysqli_error($con);
            $_SESSION['message_type'] = "error";
        }
    }
    
    // Handle adding new certification
    if (isset($_POST['add_certification'])) {
        $certificateName = mysqli_real_escape_string($con, $_POST['certificate_name']);
        $fileLink = mysqli_real_escape_string($con, $_POST['file_link']);
        $googleDriveId = mysqli_real_escape_string($con, $_POST['google_drive_id']);
        
        $addCertQuery = "INSERT INTO certifications (candidate_id, certificate_name, file_link, google_drive_id) 
                        VALUES ('$candidate_id', '$certificateName', '$fileLink', '$googleDriveId')";
        
        if (mysqli_query($con, $addCertQuery)) {
            $_SESSION['message'] = "Certification added successfully!";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Error adding certification: " . mysqli_error($con);
            $_SESSION['message_type'] = "error";
        }
    }
    
    // Refresh the page to show updated data
    header("Location: profPage.php");
    exit();
}

// Fetch education data
$educationQuery = "SELECT * FROM educational_background 
                WHERE candidate_id = '$candidate_id'
                ORDER BY FIELD(education_level, 'Doctorate', 'Masters', 'College', 'Senior High', 'High School')";
$educationResult = mysqli_query($con, $educationQuery);
$educations = [];
while ($row = mysqli_fetch_assoc($educationResult)) {
    $educations[$row['education_id']] = $row;
}

// Fetch work experience
$workQuery = "SELECT * FROM work_experience 
            WHERE candidate_id = '$candidate_id'
            ORDER BY start_date DESC";
$workResult = mysqli_query($con, $workQuery);
$workExperiences = mysqli_fetch_all($workResult, MYSQLI_ASSOC);

// Fetch skills
$skillsQuery = "SELECT * FROM skills 
                WHERE candidate_id = '$candidate_id'
                ORDER BY proficiency_level DESC";
$skillsResult = mysqli_query($con, $skillsQuery);
$skills = mysqli_fetch_all($skillsResult, MYSQLI_ASSOC);

// Fetch certifications
$certsQuery = "SELECT * FROM certifications 
            WHERE candidate_id = '$candidate_id'
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
                    <h6 class="mb-0"><?php echo $_SESSION['employeeName']; ?></h6>
                    <small><?php echo $employee['position_name']; ?></small>
                </div>
            </div>
            </div>

            <!-- Display Messages -->
            <?php if (isset($_SESSION['message'])): ?>
                <div class="alert alert-<?php echo $_SESSION['message_type'] === 'success' ? 'success' : 'danger'; ?> alert-dismissible fade show" role="alert">
                    <?php echo $_SESSION['message']; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['message']); unset($_SESSION['message_type']); ?>
            <?php endif; ?>

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
                        <?php foreach ($educations as $education): ?>
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
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0">Skills:</h6>
                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addSkillModal">
                            <i class="fas fa-plus me-1"></i> Add Skill
                        </button>
                    </div>
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

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0">Certifications:</h6>
                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addCertModal">
                            <i class="fas fa-plus me-1"></i> Add Certification
                        </button>
                    </div>
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
            
            <!-- Edit Profile Modal -->
            <div class="modal fade" id="editInfoModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-xl">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fas fa-user-edit me-2"></i>Edit Employee Profile</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">

                        <!-- Personal Information Form -->
                        <form method="post" action="profPage.php" class="mb-4">
                            <input type="hidden" name="update_profile" value="1">
                            
                            <h6 class="fw-bold text-primary mb-3" style="font-size: 20px;"><i class="fa-solid fa-id-card me-2"></i>Personal Information</h6>
                            <div class="border border-secondary rounded p-3 mb-3">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">First Name</label>
                                        <input type="text" class="form-control" name="first_name" value="<?php echo $employee['first_name']; ?>" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Last Name</label>
                                        <input type="text" class="form-control" name="last_name" value="<?php echo $employee['last_name']; ?>" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Birth Date</label>
                                        <input type="date" class="form-control" value="<?php echo $employee['date_of_birth']; ?>" readonly>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Employee ID</label>
                                        <input type="text" class="form-control" value="<?php echo $employee['employee_id']; ?>" disabled>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Employment Status</label>
                                        <input type="text" class="form-control" value="<?php echo $employee['employment_status']; ?>" readonly>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Department</label>
                                        <input type="text" class="form-control" value="<?php echo $employee['department_name']; ?>" readonly>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Position</label>
                                        <input type="text" class="form-control" value="<?php echo $employee['position_name']; ?>" readonly>
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
                                        <input type="text" class="form-control" name="phone_number" value="<?php echo $employee['phone_number']; ?>">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Address</label>
                                    <input type="text" class="form-control" name="address" value="<?php echo $employee['address']; ?>">
                                </div>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i> Save Personal Information
                                </button>
                            </div>
                        </form>

                        <!-- Educational Background Form -->
                        <?php if (!empty($educations)): ?>
                        <form method="post" action="profPage.php" class="mb-4">
                            <input type="hidden" name="update_education" value="1">
                            
                            <h6 class="fw-bold text-primary mt-4 mb-3" style="font-size: 20px;"><i class="fa-solid fa-graduation-cap me-2"></i>Educational Background</h6>
                            <?php foreach ($educations as $educationId => $education): ?>
                            <div class="border border-secondary rounded p-3 mb-3">
                                <h6 class="fw-semibold text-dark mb-4 mt-3" style="font-size: 17px;"><?php echo $education['education_level']; ?></h6>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">School Name</label>
                                        <input type="text" class="form-control" name="education[<?php echo $educationId; ?>][school_name]" value="<?php echo $education['school_name']; ?>">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Degree/Course</label>
                                        <input type="text" class="form-control" name="education[<?php echo $educationId; ?>][degree]" value="<?php echo $education['degree']; ?>">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Year Graduated</label>
                                        <input type="text" class="form-control" name="education[<?php echo $educationId; ?>][year_graduated]" value="<?php echo $education['year_graduated']; ?>">
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>

                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i> Save Education Information
                                </button>
                            </div>
                        </form>
                        <?php endif; ?>

                        <!-- Work Experience Form -->
                        <?php if (!empty($workExperiences)): ?>
                        <form method="post" action="profPage.php">
                            <input type="hidden" name="update_work_experience" value="1">
                            
                            <h6 class="fw-bold text-primary mt-4 mb-3" style="font-size: 20px;"><i class="fa-solid fa-briefcase me-2"></i>Work Experience</h6>
                            <?php foreach ($workExperiences as $work): ?>
                            <div class="border border-secondary rounded p-3 mb-3">
                                <div>
                                    <div class="mb-3">
                                        <label class="form-label">Company Name</label>
                                        <input type="text" class="form-control" name="work_experience[<?php echo $work['experience_id']; ?>][company_name]" value="<?php echo $work['company_name']; ?>">
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Position Held</label>
                                            <input type="text" class="form-control" name="work_experience[<?php echo $work['experience_id']; ?>][position_title]" value="<?php echo $work['position_title']; ?>">
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">From</label>
                                            <input type="month" class="form-control" name="work_experience[<?php echo $work['experience_id']; ?>][start_date]" value="<?php echo date('Y-m', strtotime($work['start_date'])); ?>">
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">To</label>
                                            <input type="month" class="form-control" name="work_experience[<?php echo $work['experience_id']; ?>][end_date]" value="<?php echo $work['end_date'] ? date('Y-m', strtotime($work['end_date'])) : ''; ?>">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Description</label>
                                        <textarea class="form-control" name="work_experience[<?php echo $work['experience_id']; ?>][description]" rows="3"><?php echo $work['description']; ?></textarea>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>

                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i> Save Work Experience
                                </button>
                            </div>
                        </form>
                        <?php endif; ?>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                            <i class="fa-solid fa-ban me-1"></i> Close
                        </button>
                    </div>
                    </div>
                </div>
            </div>

            <!-- Add Skills Modal -->
            <div class="modal fade" id="addSkillModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <form method="post" action="profPage.php">
                            <input type="hidden" name="add_skill" value="1">
                            <div class="modal-header">
                                <h5 class="modal-title"><i class="fas fa-plus me-2"></i>Add New Skill</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Skill Name</label>
                                    <input type="text" class="form-control" name="skill_name" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Proficiency Level</label>
                                    <select class="form-select" name="proficiency_level" required>
                                        <option value="">Select Level</option>
                                        <option value="Beginner">Beginner</option>
                                        <option value="Intermediate">Intermediate</option>
                                        <option value="Advanced">Advanced</option>
                                        <option value="Expert">Expert</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Add Skill</button>
                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Add Certification Modal -->
            <div class="modal fade" id="addCertModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <form method="post" action="profPage.php">
                            <input type="hidden" name="add_certification" value="1">
                            <div class="modal-header">
                                <h5 class="modal-title"><i class="fas fa-plus me-2"></i>Add New Certification</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Certificate Name</label>
                                    <input type="text" class="form-control" name="certificate_name" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">File Link</label>
                                    <input type="url" class="form-control" name="file_link" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Google Drive ID</label>
                                    <input type="text" class="form-control" name="google_drive_id" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Add Certification</button>
                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>