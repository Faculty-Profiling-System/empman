<?php


function login($con, $login_identifier, $password) {
    $query = "SELECT * FROM user_accounts WHERE login_identifier = '$login_identifier' and user_type != 'Candidate'";
    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($result);

    if (mysqli_num_rows($result) > 0) {
        if(password_verify($password, $row["password"])) {
            if($row["is_first_login"] == 1) {
                $_SESSION['message'] = 'Please change your password as this is your first login and your password is the default password set by the HR.';
                $_SESSION['employeeID'] = $login_identifier;
                header('Location: changePass.php');
                exit();
            }else{
                $accountId = $row['account_id'];
                $query2 = "SELECT e.employee_id, c.first_name, c.last_name, p.position_name 
                        FROM employees e 
                        JOIN candidates c ON e.candidate_id = c.candidate_id
                        JOIN applications a ON e.application_id = a.application_id
                        JOIN positions p ON a.position_id = p.position_id
                        WHERE e.account_id = '$accountId'";
                $result2 = mysqli_query($con, $query2);
                $row2 = mysqli_fetch_assoc($result2);

                $_SESSION['employeeName'] = $row2['first_name'] . ' ' . $row2['last_name'];
                $_SESSION['employeePosition'] = $row2['position_name'];
                $_SESSION['user_type'] = $row["user_type"];
                $_SESSION['employeeID'] = $row2["employee_id"];

                if ($row["user_type"] === "Employee") {
                    header('Location: employeePage/index.php');
                    exit();
                } elseif ($row["user_type"] === "Manager") {
                    header('Location: managerPage/index.php');
                    exit();
                } elseif ($row["user_type"] === "HR") {
                    header('Location: hrPage/index.php');
                    exit();
                }else{
                    return "Unknown user type.";
                }
            }
        }else{
            return "Password do not match.";
        }

    } else {
        return "Invalid Employee ID";
    }

}

function redirectToLogin($userType) {
    if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== $userType) {
        header("Location: ../index.php");
        exit();
    }
}

// function employeeEducation($con, $candidateID) {
//     $query = "Select * from educational_background where candidate_id = $candidateID";
//     $result = mysqli_query($con, $query);

//     if($result && mysqli_num_rows($result) > 0) {
//         return $result;
//     }else{
//         return false;
//     }
// }

// function employeeCertifications($con, $candidateID) {
//     $query = "Select * from certifications where candidate_id = $candidateID";
//     $result = mysqli_query($con, $query);

//     if($result && mysqli_num_rows($result) > 0) {
//         return $result;
//     }else{
//         return false;
//     }
// }
// function employeeSkills($con, $candidateID) {
//     $query = "Select * from skills where candidate_id = $candidateID";
//     $result = mysqli_query($con, $query);

//     if($result && mysqli_num_rows($result) > 0) {
//         return $result;
//     }else{
//         return false;
//     }
// }
// function employeeExp($con, $candidateID) {
//     $query = "Select * from work_experience where candidate_id = $candidateID";
//     $result = mysqli_query($con, $query);

//     if($result && mysqli_num_rows($result) > 0) {
//         return $result;
//     }else{
//         return false;
//     }
// }
