<?php

$id = $_GET['id'];
$query = "SELECT * FROM users WHERE user_id='$id'";
$results = mysqli_query($conn, $query);

while ($row = mysqli_fetch_assoc($results)) {
    $first_name = $row['first_name'];
    $last_name = $row['last_name'];
    $dob = $row['dob'];
    $email = $row['email'];
    $username = $row['username'];
    $role_id = $row['role_id'];
}

if (isset($_POST['update_user'])) {
    // REGISTER USER

    // receive all input values from the form
    $first_name = mysqli_real_escape_string($conn, $_POST['firstname']);
    $last_name = mysqli_real_escape_string($conn, $_POST['lastname']);
    $dob = mysqli_real_escape_string($conn, $_POST['dob']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $role_name = mysqli_real_escape_string($conn, $_POST['role_name']);

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
    if (empty($role_name)) {
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

    $roles = get_role_array();
    foreach ($roles as $role) {
        if ($role['role_name'] == $role_name) {
            $role_id = $role['role_id'];
        }
    }


    $query = "UPDATE users set first_name = '$first_name', last_name = '$last_name', dob = '$dob', email = '$email', username = '$username', role = '$role_id'
                WHERE user_id ='$id'";
    $result = mysqli_query($conn, $query);

    array_push($success, "Update Suuccessful");
}

?>

<div class="form-container">

    <!-- <?php //echo htmlspecialchars($_SERVER["PHP_SELF"]); 
            ?> -->
    <form class="form-body" action="" method="post">

        <?php echo display_success(); ?>
        <?php echo display_error(); ?>

        <div class="form-input">
            <p><b>Update</b></p>
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
            <label for="roles">Choose a Role</label>
            <span>
                <select id="roles" name="role">
                    <?php
                    $roles = get_role_array();
                    foreach ($roles as $role) {
                        $role_id = $role['role_id'];
                        $role_name = $role['role_name'];
                        echo "<option name=role_name value='$role_id'>$role_name</option>";
                    }
                    ?>
                </select>
            </span>

        </div>

        <div class="form-submit">
            <input type="submit" name="update_user" value="Update">
        </div>
    </form>
</div>