<?php

session_start();

require_once('../configs/config.php');
require_once('../helper/functions.php');

$session_user_id = $_SESSION['user_id'];

$email_new = "";

if (isset($_POST['reset_email'])) {

    // Check if email_new is empty
    if (empty(trim($_POST["email_new"]))) {
        array_push($errors, "New Email is required");
    } else {
        $email_new = mysqli_real_escape_string($conn, $_POST['email_new']);
    }

    $users_array = get_table_array('users');

    foreach ($users_array as $users) {
        // Check if email input match with email in db
        if ($users['email'] === $email_new) {
            array_push($errors, "Email already exists");
        }
    }

    if (count($errors) == 0) {
        $query = "UPDATE users SET email='$email_new' WHERE user_id='$session_user_id'";
        if (mysqli_query($conn, $query)) {
            array_push($success, "Email reset successful");
        } else {
            array_push($errors, "Email reset failed" . mysqli_error($conn));
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
    <title>Reset Email</title>
</head>

<body>

    <header>
        <h1>Reset Email</h1>
    </header>

    <br>

    <main>

        <div class="form-container">

            <form class="form-body" action="" method="POST">

                <?php
                display_success();
                display_error();
                ?>

                <?php if (!isset($_POST['reset_email']) || count($errors) > 0) { ?>
                    <div class="form-input">
                        <label>Current Email</label>
                        <span> <b><?= mysqli_fetch_assoc(get_records_where('users', 'user_id', $session_user_id))['email'] ?></b></span>
                    </div>
                    <div class="form-input">
                        <label>New Email</label>
                        <span>
                            <input type="email" name="email_new">
                        </span>
                    </div>
                    <div class="form-submit">
                        <input type="submit" name="reset_email" value="Reset">
                    </div>
                <?php } ?>

                <?php if (isset($_POST['reset_email']) && count($errors) == 0) { ?>
                    <span>
                        <a href="../index.php">Welcome</a>
                    </span>
                <?php } ?>

            </form>

        </div>

    </main>

    <?php require("../includes/footer.php") ?>