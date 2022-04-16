<?php

session_start();

require_once('../configs/config.php');
require_once('../helper/functions.php');

$session_user_id = $_SESSION['user_id'];

$password_old = $password_new = $password_confirm = "";

if (isset($_POST['reset_password'])) {

    // Check if password_old is empty
    if (empty(trim($_POST["password_old"]))) {
        array_push($errors, "Old Password is required");
    } else {
        $password_old = mysqli_real_escape_string($conn, $_POST['password_old']);
    }

    // Check if password_new is empty
    if (empty(trim($_POST["password_new"]))) {
        array_push($errors, "New Password is required!");
    } else {
        $password_new = mysqli_real_escape_string($conn, $_POST['password_new']);
    }

    // Check if password_confirm is empty
    if (empty(trim($_POST["password_confirm"]))) {
        array_push($errors, "Confirmation Password is required!");
    } else {
        $password_confirm = mysqli_real_escape_string($conn, $_POST['password_confirm']);
    }

    //using custom function :)
    $password_db = mysqli_fetch_assoc(get_records_where('users', 'user_id', $session_user_id))['password'];

    // Check if new and confirm password match
    if ($password_new !== $password_confirm) {
        array_push($errors, "New and Confirmation Password must match!");
    }

    // Check if old password input match with password in db
    if ($password_db !== $password_old) {
        array_push($errors, "Old Password is incorrect!");
    }

    if (count($errors) == 0) {
        $query = "UPDATE users SET password='$password_new', first_login = 0 WHERE user_id='$session_user_id'";
        if (mysqli_query($conn, $query)) {
            array_push($success, "Password reset successful");
        } else {
            array_push($errors, "Password reset failed" . mysqli_error($conn));
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/style.css">
    <title>Reset Password</title>
</head>

<body>

    <header>
        <h1>Reset Password</h1>
    </header>

    <br>

    <main>

        <div class="form-container">

            <form class="form-body" action="" method="POST">

                <?php
                display_success();
                display_error();
                ?>

                <?php if (!isset($_POST['reset_password']) || count($errors) > 0) { ?>
                    <div class="form-input">
                        <label>Old Password</label>
                        <span>
                            <input type="password" name="password_old">
                        </span>
                    </div>
                    <div class="form-input">
                        <label>New Password</label>
                        <span>
                            <input type="password" name="password_new">
                        </span>
                    </div>
                    <div class="form-input">
                        <label>Confirm Password</label>
                        <span>
                            <input type="password" name="password_confirm">
                        </span>
                    </div>
                    <div class="form-submit">
                        <input type="submit" name="reset_password" value="Reset">
                    </div>
                <?php } ?>

                <?php if (isset($_POST['reset_password']) && count($errors) == 0) { ?>
                    <span>
                        <a href="../index.php">Welcome</a>
                    </span>
                <?php } ?>

            </form>
        </div>

    </main>

    <?php require("../includes/footer.php") ?>