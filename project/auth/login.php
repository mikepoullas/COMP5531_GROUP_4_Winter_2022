<?php
// Initialize the session
session_start();

require_once('../configs/config.php');
require_once('../helper/functions.php');

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header("location: ../index.php");
    exit;
}

$username = $password = "";

if (isset($_POST['login_user'])) {

    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    if (empty($username)) {
        array_push($errors, "Username is required!");
    }
    if (empty($password)) {
        array_push($errors, "Password is required!");
    }

    if (count($errors) == 0) {

        $query = "SELECT * FROM users as u WHERE username='$username' AND password='$password' LIMIT 1";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) == 1) {

            $_SESSION['loggedin'] = true;

            $check = mysqli_fetch_assoc($result);
            $_SESSION['first_login'] = $check['first_login'];

            $_SESSION['user_id'] = $check['user_id'];
            $_SESSION['username'] = $username;
            $_SESSION['name'] = $check['first_name'] . " " . $check['last_name'];

            $_SESSION['role_id'] = $check['role_id'];
            $roles = get_records_where('roles', 'role_id', $check['role_id']);
            foreach ($roles as $role) {
                if ($role['role_id'] == $check['role_id']) {
                    $_SESSION['role_name'] = $role['role_name'];
                }
            }

            if ($check['first_login'] == 1) {
                header("location: reset-password.php");
                exit;
            } else {
                header("location: ../index.php");
                exit;
            }
        } else {
            array_push($errors, "Invalid username or password");
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
    <title>Login</title>
</head>

<body>

    <header>
        <h1>Login</h1>
    </header>

    <main>


        <div class="form-container">

            <form class="form-body" action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">

                <?php
                display_success();
                display_error();
                ?>
                <br>

                <div class="form-input">
                    <label>Username</label>
                    <span>
                        <input type="text" name="username" value="<?= $username; ?>">
                    </span>

                </div>

                <div class="form-input">
                    <label>Password</label>
                    <span>
                        <input type="password" name="password">
                    </span>

                </div>

                <div class="form-submit">
                    <input type="submit" name="login_user" value="Login">
                    <br><br>
                    <a href="forgot-password.php">Forgot password?</a>
                </div>

            </form>
        </div>

    </main>

    <?php require("../includes/footer.php") ?>