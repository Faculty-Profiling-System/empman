<?php
session_start();
require 'connection.php';

$err = null;
$employeeId = null;
$currentPass = null;
$newPass = null;
$confirmPass = null;

if(isset($_SESSION['employeeID']))
{
    $employeeId = $_SESSION['employeeID'];
}

if (isset($_POST['resetPass'])) {
    $employeeId = $_POST['employeeId'];
    $currentPass = $_POST['currentPass'];
    $newPass = $_POST['newPass'];
    $confirmPass = $_POST['confirmPass'];

    $query = "SELECT * FROM user_accounts WHERE login_identifier = '$employeeId'";
    $result = mysqli_query($con, $query);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        if (password_verify($currentPass, $row["password"])) {
            if ($newPass === $confirmPass) {
                $hashedNewPass = password_hash($newPass, PASSWORD_BCRYPT);

                mysqli_begin_transaction($con);
                $updateQuery = "UPDATE user_accounts SET password = '$hashedNewPass', is_first_login = 0 WHERE login_identifier = '$employeeId'";
                if (mysqli_query($con, $updateQuery)) {
                    mysqli_commit($con);
                    session_unset();
                    session_destroy();
                    $_SESSION['message'] = 'Password successfully updated. Please login with your new password.';
                    header('Location: index.php');
                    exit();
                } else {
                    mysqli_rollback($con);
                    $err = "Error updating password. Please try again.";
                }
            } else {
                $err = "New password and confirm password do not match.";
            }
        } else {
            $err = "Current password is incorrect.";
        }
    } else {
        $err = "Invalid Employee ID.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php 
    if(isset($_SESSION['message'])){
        echo ''.$_SESSION['message'].'';
    }
    ?><br><br>
    <form action="changePass.php" method="post">
        <label for="employeeId">Employee ID:</label><br>
        <input type="text" name="employeeId" id="employeeId" value="<?php echo $employeeId ;?>"><br><br>
        <label for="currentPass">Current Password:</label><br>
        <input type="password" name="currentPass" id="currentPass"><br><br>
        <label for="newPass">New Password:</label><br>
        <input type="password" name="newPass" id="newPass"><br><br>
        <label for="confirmPass">Confirm Password:</label><br>
        <input type="password" name="confirmPass" id="confirmPass"><br><br>

        <?php echo $err; ?><br><br>
        <input type="submit" value="Reset Password" name="resetPass">
        <a href="index.php">Back to login</a>
    </form>
</body>
</html>