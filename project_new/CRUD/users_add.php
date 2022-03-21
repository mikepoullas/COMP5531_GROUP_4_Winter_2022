<?php

// initializing variables
$first_name = $last_name = $dob = $email = $username = $password_1 = $password_2 = $role = "";

if (isset($_POST['register_user'])) {

    // REGISTER USER

    // receive all input values from the form
    $first_name = mysqli_real_escape_string($conn, $_POST['firstname']);
    $last_name = mysqli_real_escape_string($conn, $_POST['lastname']);
    $dob = mysqli_real_escape_string($conn, $_POST['dob']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password_new = mysqli_real_escape_string($conn, $_POST['password_new']);
    $password_confirm = mysqli_real_escape_string($conn, $_POST['password_confirm']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);

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
    if ($password_1 !== $password_2) {
        array_push($errors, "The two passwords do not match");
    }
    if (empty($role)) {
        array_push($errors, "Role is required");
    }

    // first check the database to make sure 
    // a user does not already exist with the same username and/or email
    $query = "SELECT * FROM users WHERE username='$username' OR email='$email' LIMIT 1";
    $results = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($results);

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
        $password = $password_new;
        //$password = md5($password_1); //encrypt the password before saving in the database

        $query = "INSERT INTO users (first_name, last_name, dob, email, username, password, created_on, first_login, role_id) 
                    VALUES('$first_name', '$last_name', '$dob', '$email', '$username', '$password', CURRENT_TIMESTAMP, 1, '$role');";
        mysqli_query($conn, $query);
        array_push($success, "Registration Suuccessful");

        // clear variables
        $first_name = $last_name = $dob = $email = $username = $password_1 = $password_2 = $role = "";
    }
}

?>

<div class="form-container">

    <!-- <?php //echo htmlspecialchars($_SERVER["PHP_SELF"]); 
            ?> -->
    <form class="form-body" action="" method="post">

        <?php echo display_success(); ?>
        <?php echo display_error(); ?>

        <div class="form-input">
            <p><b>Registration</b></p>
            <label>First Name</label>
            <span><input type="text" name="firstname" value=<?= $first_name ?>></span>
        </div>

        <div class="form-input">
            <label>Last Name</label>
            <span> <input type="text" name="lastname" value=<?= $last_name ?>> </span>
        </div>

        <div class="form-input">
            <label>Date of Birth</label>
            <span><input type="date" name="dob" value=<?= $dob ?>> </span>
        </div>

        <div class="form-input">
            <label>Email</label>
            <span><input type="email" name="email" value=<?= $email ?>> </span>
        </div>

        <div class="form-input">
            <label>Username</label>
            <span><input type="text" name="username" value=<?= $username ?>></span>
        </div>

        <div class="form-input">
            <label>Password</label>
            <span><input type="password" name="password_new"> </span>
        </div>

        <div class="form-input">
            <label>Confirm password</label>
            <span><input type="password" name="password_confirm"></span>
        </div>

        <div class="form-input">
            <label for="roles">Choose a Role</label>
            <span>
                <select id="roles" name="role">
                    <?php
                    $roles = get_role_array();
                    foreach ($roles as $role) {
                        $role_id = $role['role_id'];
                        $role_name = $role['role_name'];
                        echo "<option value='$role_id'>$role_name</option>";
                    }
                    ?>
                </select>
            </span>

        </div>

        <div class="form-submit">
            <input type="submit" name="register_user" value="Register">
        </div>
    </form>
</div>