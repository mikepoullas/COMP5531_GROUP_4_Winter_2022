<!--
CODE CONTRIBUTOR:

# COMP 5531 - GROUP 4 (Winter 2022)
Student_ID  First_Name  Last_Name   Email
40159305    shafiq      IMTIAZ      s_mtiaz@encs.concordia.ca
21917730    michael     POULLAS     m_poull@encs.concordia.ca
-->

<?php
// Initialize the session
session_start();

require_once('../configs/config.php');
require_once('../helper/functions.php');
require_once('../includes/server.php');

// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ../auth/login.php");
    exit;
}

$username = $_SESSION['username'];
$name = $_SESSION['name'];
$role_name = $_SESSION['role_name'];
$session_user_id = $_SESSION['user_id'];
$role_id = $_SESSION['role_id'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/style.css">
    <title>CGA</title>
</head>

<body>

    <header>
        <div class="title-bar">
            <h1><?= $role_name ?></h1>
            <nav>
                <p>Welcome <b><?= $name ?></b></p>
                <ul>
                    <li><a href="javascript:history.go(-1)">&larr;</a></li>
                    <li><a href="?page=home">Home</a></li>
                    <li><a href="../auth/reset-email.php">Reset Email</a></li>
                    <li><a href="../auth/reset-password.php">Reset Password</a></li>
                    <li><a href="?page=help">Help</a></li>
                    <li><a href="../auth/logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>

    </header>