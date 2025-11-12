<?php

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
    <form action="forgotPass.php" method="post">
        <label for="employeeId">Employee ID:</label><br>
        <input type="text" name="employeeId" id="employeeId"><br><br>
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