<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ./auth/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles/style.css">
    <title>Welcome</title>
</head>

<body>

    <header>
        <h1>Welcome</h1>
        <nav>
            <p>Logged in as <b>User</b></p>
            <ul>
                <li><a href="#">Change Email</a></li>
                <li><a href="./auth/reset-password.php">Change Password</a></li>
                <li><a href="./auth/logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>

        <Section>
            <div class="role-body">
                <p>Select available role</p>

                <div class="role-list">
                    <a href="./pages/admin.php">Admin</a>
                    <a href="./pages/professor.php">Professor</a>
                    <a href="./pages/ta.php">Teaching Assistant</a>
                    <a href="./pages/student.php">Student</a>
                </div>

            </div>
        </Section>

    </main>

</body>

</html>