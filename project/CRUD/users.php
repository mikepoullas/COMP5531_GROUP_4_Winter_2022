<?php

// initializing variables
$id = $first_name = $last_name = $dob = $email = $username = $password_1 = $password_2 = $role = "";

// ADD
if (isset($_POST['add_user'])) {
    // receive all input values from the form
    $first_name = mysqli_real_escape_string($conn, $_POST['firstname']);
    $last_name = mysqli_real_escape_string($conn, $_POST['lastname']);
    $dob = mysqli_real_escape_string($conn, $_POST['dob']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password_new = mysqli_real_escape_string($conn, $_POST['password_new']);
    $password_confirm = mysqli_real_escape_string($conn, $_POST['password_confirm']);
    $role_id = mysqli_real_escape_string($conn, $_POST['role_id']);

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
    if (empty($password_new)) {
        array_push($errors, "Password is required");
    }
    if ($password_new !== $password_confirm) {
        array_push($errors, "The two passwords do not match");
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

    // Finally, register user if there are no errors in the form
    if (count($errors) == 0) {
        $password = $password_new;
        //$password = md5($password_1); //encrypt the password before saving in the database

        $user_add = "INSERT INTO users (first_name, last_name, dob, email, username, password, created_on, first_login, role_id) 
                    VALUES('$first_name', '$last_name', '$dob', '$email', '$username', '$password', CURRENT_TIMESTAMP, 1, '$role_id');";

        if (mysqli_query($conn, $user_add)) {
            array_push($success, "Registration Successful");
            // clear variables
            $id = $first_name = $last_name = $dob = $email = $username = $password_1 = $password_2 = $role_id = "";
        } else {
            array_push($errors, "Error registering user: ", mysqli_error($conn));
        }
    }
}

// UPDATE
if (isset($_POST['update_user'])) {
    // receive all input values from the form
    $first_name = mysqli_real_escape_string($conn, $_POST['firstname']);
    $last_name = mysqli_real_escape_string($conn, $_POST['lastname']);
    $dob = mysqli_real_escape_string($conn, $_POST['dob']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $role_id = mysqli_real_escape_string($conn, $_POST['role_id']);

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

    if (count($errors) == 0) {
        $update = "UPDATE users set first_name = '$first_name', last_name = '$last_name', dob = '$dob', email = '$email', username = '$username', role_id = '$role_id'
                WHERE user_id ='$id'";

        if (mysqli_query($conn, $update)) {
            array_push($success, "User Updated Successfully");
            // clear variables
            $first_name = $last_name = $dob = $email = $username = $role_id = "";
        } else {
            array_push($errors, "Error updating user: ", mysqli_error($conn));
        }
    }
}

// DELETE
if (isset($_GET['delete_id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['delete_id']);
    $delete = "DELETE FROM users WHERE user_id='$id'";
    mysqli_query($conn, $query);
    exit();
}

$query = "SELECT * FROM users as u JOIN roles as r ON u.role_id = r.role_id ORDER BY user_id ASC";
$results = mysqli_query($conn, $query);

?>

<div class="content-body">
    <p><b>Users</b></p>
    <table>
        <thead>
            <tr>
                <th>User ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Date of Birth</th>
                <th>Email</th>
                <th>Username</th>
                <th>Created On</th>
                <th>Role Name</th>
                <?php isAdmin() ? print '<th colspan="2">Action</th>' : '' ?>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($users = mysqli_fetch_assoc($results)) {
                $user_id = $users['user_id'];
                $first_name = $users['first_name'];
                $last_name = $users['last_name'];
                $dob = $users['dob'];
                $email = $users['email'];
                $username = $users['username'];
                $created_on = $users['created_on'];
                $role_name = $users['role_name'];
            ?>
                <tr>
                    <?php if (isAdmin()) {
                        echo '<td>' . $user_id . '</td>';
                    } ?>
                    <td><?php echo $user_id ?></td>
                    <td><?php echo $first_name ?></td>
                    <td><?php echo $last_name ?></td>
                    <td><?php echo $dob ?></td>
                    <td><?php echo $email ?></td>
                    <td><?php echo $username ?></td>
                    <td><?php echo $created_on ?></td>
                    <td><?php echo $role_name ?></td>
                    <?php if (isAdmin()) {
                        echo '<td><a href="?page=users&update_view=true&update_id=' . $user_id . '">Update</a></td>';
                        echo '<td><a href="?page=users&delete_view=true&delete_id=' . $user_id . '">Delete</a></td>';
                    } ?>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>

    <?php if (isAdmin()) { ?>
        <a href="?page=users&add_view=true">
            <button>Add User</button>
        </a>
    <?php } ?>

    <?php if (isset($_GET['add_view'])) { ?>
        <hr>
        <div class="form-container">
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
                        <select id="roles" name="role_id">
                            <?php
                            $roles = get_table_array('roles');
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
                    <input type="submit" name="add_user" value="Add">
                </div>
            </form>
        </div>
    <?php } ?>

    <?php if (isset($_GET['update_view'])) { ?>

        <?php
        $id = mysqli_real_escape_string($conn, $_GET['update_id']);
        $query = "SELECT * FROM users WHERE user_id='$id'";
        $results = mysqli_query($conn, $query);

        while ($row = mysqli_fetch_assoc($results)) {
            $first_name = $row['first_name'];
            $last_name = $row['last_name'];
            $dob = $row['dob'];
            $email = $row['email'];
            $username = $row['username'];
            $user_role_id = $row['role_id'];
        }
        ?>
        <hr>
        <div class="form-container">
            <form class="form-body" action="" method="post">
                <?php echo display_success(); ?>
                <?php echo display_error(); ?>
                <div class="form-input">
                    <p><b>Update</b></p>
                    <label>User ID</label>
                    <span><b><?= $id ?></b></span>
                </div>
                <div class="form-input">
                    <label>First Name</label>
                    <span><input type="text" name="firstname" value='<?= $first_name ?>'></span>
                </div>
                <div class="form-input">
                    <label>Last Name</label>
                    <span> <input type="text" name="lastname" value='<?= $last_name ?>'> </span>
                </div>
                <div class="form-input">
                    <label>Date of Birth</label>
                    <span><input type="date" name="dob" value='<?= $dob ?>'> </span>
                </div>
                <div class="form-input">
                    <label>Email</label>
                    <span><input type="email" name="email" value='<?= $email ?>'> </span>
                </div>
                <div class="form-input">
                    <label>Username</label>
                    <span><input type="text" name="username" value='<?= $username ?>'></span>
                </div>
                <div class="form-input">
                    <label for="roles">Choose a Role</label>
                    <span>
                        <select id="roles" name="role_id">
                            <?php
                            $roles = get_table_array('roles');
                            foreach ($roles as $role) {
                                $role_id = $role['role_id'];
                                $role_name = $role['role_name'];
                                if ($user_role_id == $role_id) {
                                    echo "<option name=role_name value='$role_id' selected>$role_name</option>";
                                } else {
                                    echo "<option name=role_name value='$role_id'>$role_name</option>";
                                }
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
    <?php } ?>

</div>