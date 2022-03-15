<?php
session_start();

// initializing variables

$first_name = $last_name = $dob = $email = $username = $password = $password_1 = $password_2 = $role = "";
$errors = array();

// connect to the database
include('db.php');

// REGISTER USER
if (isset($_POST['reg_user'])) {
    // receive all input values from the form

    $first_name = mysqli_real_escape_string($db, $_POST['firstname']);
    $last_name = mysqli_real_escape_string($db, $_POST['lastname']);
    $dob = mysqli_real_escape_string($db, $_POST['dob']);
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $username = mysqli_real_escape_string($db, $_POST['username']);
    $password_1 = mysqli_real_escape_string($db, $_POST['password_1']);
    $password_2 = mysqli_real_escape_string($db, $_POST['password_2']);
    $role = mysqli_real_escape_string($db, $_POST['role']);

    // form validation: ensure that the form is correctly filled ...
    // by adding (array_push()) corresponding error unto $errors array

    if (empty($first_name)) {
        array_push($errors, "First Name is required");
    }
    if (empty($last_name)) {
        array_push($errors, "First Name is required");
    }
    if (empty($dob)) {
        array_push($errors, "Date of Birth is required");
    }
    if (empty($email)) {
        array_push($errors, "Email is required");
    }
    if (empty($username)) {
        array_push($errors, "Username is required");
    }
    if (empty($password_1)) {
        array_push($errors, "Password is required");
    }
    if ($password_1 != $password_2) {
        array_push($errors, "The two passwords do not match");
    }
    if (empty($role)) {
        array_push($errors, "Role is required");
    }

    // first check the database to make sure 
    // a user does not already exist with the same username and/or email
    $user_check_query = "SELECT * FROM users WHERE username='$username' OR email='$email' LIMIT 1";
    $result = mysqli_query($db, $user_check_query);
    $user = mysqli_fetch_assoc($result);

    if ($user) { // if user exists
        if ($user['username'] === $username) {
            array_push($errors, "Username already exists");
        }

        if ($user['email'] === $email) {
            array_push($errors, "Email already exists");
        }
    }

    // Finally, register user if there are no errors in the form
    if (count($errors) == 0) {
        $password = $password_1;
        //$password = md5($password_1); //encrypt the password before saving in the database

        $query = "INSERT INTO users (first_name, last_name, dob, email, username, password, created_on, is_first_login, role_id) 
        VALUES('$first_name', '$last_name', '$dob', '$email', '$username', '$password', CURRENT_TIMESTAMP, 1, '$role');";
        mysqli_query($db, $query);
        $_SESSION['username'] = $username;
        $_SESSION['success'] = "You are now logged in";
        header('location: index.php');
    }
}

// LOGIN USER
if (isset($_POST['login_user'])) {
    $username = mysqli_real_escape_string($db, $_POST['username']);
    $password = mysqli_real_escape_string($db, $_POST['password']);

    if (empty($username)) {
        array_push($errors, "Username is required");
    }
    if (empty($password)) {
        array_push($errors, "Password is required");
    }

    if (count($errors) == 0) {
        //$password = md5($password);
        $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
        $results = mysqli_query($db, $query);
        if (mysqli_num_rows($results) == 1) {
            $_SESSION['username'] = $username;
            $_SESSION['success'] = "You are now logged in";
            header('location: index.php');
        } else {
            array_push($errors, "Wrong username/password combination");
        }
    }
}

function display_error()
{
    global $errors;

    if (count($errors) > 0) {
        echo '<div class="error">';
        foreach ($errors as $error) {
            echo $error . '<br>';
        }
        echo '</div>';
    }
}
