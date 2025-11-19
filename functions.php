<?php


function login($con, $login_identifier, $password) {
    $query = "SELECT * FROM user_accounts WHERE login_identifier = '$login_identifier' and user_type != 'Candidate'";
    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($result);

    if (mysqli_num_rows($result) > 0) {
        if(password_verify($password, $row["password"])) {
            // Check if employee is suspended or terminated
            $accountId = $row['account_id'];
            $status_query = "SELECT e.status 
                           FROM employees e 
                           WHERE e.account_id = '$accountId'";
            $status_result = mysqli_query($con, $status_query);
            
            if ($status_result && mysqli_num_rows($status_result) > 0) {
                $status_row = mysqli_fetch_assoc($status_result);
                $employee_status = $status_row['status'];
                
                // Check if employee is suspended or terminated
                if ($employee_status === 'Suspended') {
                    $_SESSION['account_status'] = 'suspended';
                    $_SESSION['status_message'] = 'You have been suspended. To Resolve this issue, please go the HR immediately.';
                    header('Location: account_status.php');
                    exit();
                } elseif ($employee_status === 'Terminated') {
                    $_SESSION['account_status'] = 'terminated';
                    $_SESSION['status_message'] = 'You have been terminated. To Resolve this issue, please go the HR immediately.';
                    header('Location: account_status.php');
                    exit();
                }
            }
            
            if($row["is_first_login"] == 1) {
                $_SESSION['message'] = 'Please change your password as this is your first login and your password is the default password set by the HR.';
                $_SESSION['employeeID'] = $login_identifier;
                header('Location: changePass.php');
                exit();
            } else {
                $accountId = $row['account_id'];
                $query2 = "SELECT e.employee_id, c.first_name, c.last_name, p.position_name, e.employment_status 
                        FROM employees e 
                        JOIN candidates c ON e.candidate_id = c.candidate_id
                        JOIN positions p ON e.position_id = p.position_id
                        WHERE e.account_id = '$accountId'";
                $result2 = mysqli_query($con, $query2);
                
                if ($result2 && mysqli_num_rows($result2) > 0) {
                    $row2 = mysqli_fetch_assoc($result2);

                    $_SESSION["firstName"] = $row2["first_name"];
                    $_SESSION["lastName"] = $row2["last_name"];
                    $_SESSION['employeeName'] = $row2['first_name'] . ' ' . $row2['last_name'];
                    $_SESSION['employeePosition'] = $row2['position_name'];
                    $_SESSION['user_type'] = $row["user_type"];
                    $_SESSION['employeeID'] = $row2["employee_id"];
                    $_SESSION['employment_status'] = $row2['employment_status'];

                    if ($row["user_type"] === "Employee") {
                        header('Location: employeePage/index.php');
                        exit();
                    } elseif ($row["user_type"] === "Manager") {
                        header('Location: managerPage/index.php');
                        exit();
                    } elseif ($row["user_type"] === "HR") {
                        header('Location: hrPage/index.php');
                        exit();
                    } else {
                        return "Unknown user type.";
                    }
                } else {
                    return "Employee details not found.";
                }
            }
        } else {
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
