<script>
    function validateUserInput() {

        var first_name, last_name, dob, email, password_new, password_confirm, role;

        first_name = document.getElementById("first_name").value;
        last_name = document.getElementById("last_name").value;
        dob = document.getElementById("dob").value;
        email = document.getElementById("email").value;
        password_new = document.getElementById("password_new").value;
        password_confirm = document.getElementById("password_confirm").value;
        role = document.getElementById("roles").value;

        if (first_name == '') {
            alert("Please enter users first name.");
            document.getElementById("first_name").focus();
            return false;
        } else if (last_name == '') {
            alert("Please enter users last name.");
            document.getElementById("last_name").focus();
            return false;
        } else if (dob == '') {
            alert("Please enter users date of birth.");
            document.getElementById("dob").focus();
            return false;
        } else if (email == '') {
            alert("Please enter users email address.");
            document.getElementById("email").focus();
            return false;
        } else if (password_new == '') {
            alert("Please enter users new password.");
            document.getElementById("password_new").focus();
            return false;
        } else if (password_confirm == '') {
            alert("Please enter users confirmed password.");
            document.getElementById("password_confirm").focus();
            return false;
        } else if (password_new != password_confirm) {
            alert("Passwords entered do not match.");
            document.getElementById("password_new").focus();
            return false;
        } else if (role == '') {
            alert("Please enter users role type.");
            document.getElementById("role").focus();
            return false;
        } else
            return true;
    }

    function validateUpdateUserInput() {

        var first_name, last_name, dob, email;

        first_name = document.getElementById("first_name").value;
        last_name = document.getElementById("last_name").value;
        dob = document.getElementById("dob").value;
        email = document.getElementById("email").value;

        if (first_name == '') {
            alert("Please users first name.");
            document.getElementById("first_name").focus();
            return false;
        } else if (last_name == '') {
            alert("Please enter users last name.");
            document.getElementById("last_name").focus();
            return false;
        } else if (dob == '') {
            alert("Please enter users date of birth.");
            document.getElementById("dob").focus();
            return false;
        } else if (email == '') {
            alert("Please enter users email address.");
            document.getElementById("email").focus();
            return false;
        } else
            return true;
    }
</script>

<?php

// ADD
if (isset($_POST['add_user'])) {

    // receive all input values from the form
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $dob = mysqli_real_escape_string($conn, $_POST['dob']);
    $email = strtolower(mysqli_real_escape_string($conn, $_POST['email']));
    $password_new = mysqli_real_escape_string($conn, $_POST['password_new']);
    $password_confirm = mysqli_real_escape_string($conn, $_POST['password_confirm']);
    $role_id = mysqli_real_escape_string($conn, $_POST['role_id']);

    //make a unique username
    $username = strtolower($first_name[0] . "_" . $last_name . "_" . "$role_id");

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

        $add = "INSERT INTO users (first_name, last_name, dob, email, username, password, created_on, first_login, role_id) 
                    VALUES('$first_name', '$last_name', '$dob', '$email', '$username', '$password', NOW(), 1, '$role_id');";

        if (!mysqli_query($conn, $add)) {
            array_push($errors, "Error registering user: ", mysqli_error($conn));
        }

        //Insert new User to Student, TA or Professor table accordingly

        // Get User_Id auto generated by MySQL
        $user_id = mysqli_insert_id($conn);

        switch ($role_id) {
            case 2:
                $table_name = "professor";
                break;
            case 3:
                $table_name = "ta";
                break;
            case 4:
                $table_name = "student";
                break;
        }

        $add = "INSERT INTO $table_name (user_id) VALUE('$user_id')";

        if (mysqli_query($conn, $add)) {
            array_push($success, "Registration Successful");
        } else {
            array_push($errors, "Error registering user: ", mysqli_error($conn));
        }
    }
}

// UPDATE
if (isset($_POST['update_user'])) {
    // receive all input values from the form
    $user_id = mysqli_real_escape_string($conn, $_POST['user_id']);
    $first_name = mysqli_real_escape_string($conn, $_POST['firstname']);
    $last_name = mysqli_real_escape_string($conn, $_POST['lastname']);
    $dob = mysqli_real_escape_string($conn, $_POST['dob']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    //    $username = mysqli_real_escape_string($conn, $_POST['username']);
    //    $role_id = mysqli_real_escape_string($conn, $_POST['role_id']);

    if (count($errors) == 0) {
        $update = "UPDATE users SET first_name = '$first_name', last_name = '$last_name', 
                    dob = '$dob', email = '$email' WHERE user_id ='$user_id'";

        if (mysqli_query($conn, $update)) {
            array_push($success, "User profile updated successfully");
        } else {
            array_push($errors, "Error updating user: ", mysqli_error($conn));
        }
    }
}

// DELETE
if (isset($_GET['delete_id'])) {
    $user_id = mysqli_real_escape_string($conn, $_GET['delete_id']);
    $role_id = mysqli_real_escape_string($conn, $_GET['role_id']);

    //Delete User from Student, TA or Professor table accordingly first to avoid constraint issue

    switch ($role_id) {
        case 2:
            $table_name = "professor";
            break;
        case 3:
            $table_name = "ta";
            break;
        case 4:
            $table_name = "student";
            break;
    }

    $delete = "DELETE FROM $table_name WHERE user_id = '$user_id'";

    if (!mysqli_query($conn, $delete)) {
        array_push($errors, "Error deleting user from " . $table_name . " table: " . mysqli_error($conn));
    }

    // Now delete user from User table
    $delete = "DELETE FROM users WHERE user_id='$user_id'";

    if (mysqli_query($conn, $delete)) {
        array_push($success, "Delete successful");
    } else {
        array_push($errors, "Delete user error: " . mysqli_error($conn));
    }
}

?>

<div class="content-body">

    <?php

    display_success();
    display_error();

    $query = "SELECT * FROM users as u JOIN roles as r ON u.role_id = r.role_id ORDER BY user_id ASC";
    $results = mysqli_query($conn, $query);

    ?>
    <h2>Users</h2>
    <hr>
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
            foreach ($results as $users) {
                $user_id = $users['user_id'];
                $first_name = $users['first_name'];
                $last_name = $users['last_name'];
                $dob = $users['dob'];
                $email = $users['email'];
                $username = $users['username'];
                $created_on = date_convert($users['created_on']);
                $role_name = $users['role_name'];
                $role_id = $users['role_id'];
            ?>
                <tr>
                    <?php if (isAdmin()) {
                        echo '<td>' . $user_id . '</td>';
                    } ?>
                    <td><?= $first_name ?></td>
                    <td><?= $last_name ?></td>
                    <td><?= $dob ?></td>
                    <td><?= $email ?></td>
                    <td><?= $username ?></td>
                    <td><?= $created_on ?></td>
                    <td><?= $role_name ?></td>
                    <?php if (isAdmin()) {
                        echo '<td><a href="?page=users&update_view=true&update_id=' . $user_id . '">Update</a></td>';
                        echo "<td><a href='?page=users&delete_id=" . $user_id . "&role_id=" . $role_id . "' onclick='return confirm(&quot;Are you sure you want to delete? &quot;)'>Delete User</a></td>";
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

        <?php if (isset($_GET['add_view'])) { ?>

            <hr>
            <div class="form-container">
                <form class="form-body" action="" method="POST" onSubmit="return validateUserInput()">

                    <h3>Add a new user</h3>

                    <div class="form-input">
                        <label>First Name</label>
                        <span><input type="text" name="first_name" id="first_name"></span>
                    </div>
                    <div class="form-input">
                        <label>Last Name</label>
                        <span> <input type="text" name="last_name" id="last_name"> </span>
                    </div>
                    <div class="form-input">
                        <label>Date of Birth</label>
                        <span><input type="date" name="dob" id="dob"> </span>
                    </div>
                    <div class="form-input">
                        <label>Email</label>
                        <span><input type="email" name="email" id="email"> </span>
                    </div>
                    <!-- <div class="form-input">
                        <label>Username</label>
                        <span><input type="text" name="username"></span>
                    </div> -->
                    <div class="form-input">
                        <label>Password</label>
                        <span><input type="password" name="password_new" id="password_new"> </span>
                    </div>
                    <div class="form-input">
                        <label>Confirm password</label>
                        <span><input type="password" name="password_confirm" id="password_confirm"></span>
                    </div>
                    <div class="form-input">
                        <label for="roles">Choose a Role</label>
                        <span>
                            <select id="roles" name="role_id">
                                <option value="" selected hidden>Choose a Role</option>
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
            $user_id = mysqli_real_escape_string($conn, $_GET['update_id']);

            $query = "SELECT * FROM users WHERE user_id='$user_id'";
            $results = mysqli_query($conn, $query);

            foreach ($results as $row) {
                //$user_id = $row['user_id'];
                $first_name = $row['first_name'];
                $last_name = $row['last_name'];
                $dob = $row['dob'];
                $email = $row['email'];
                $username = $row['username'];
                $update_role_id = $row['role_id'];
            }
            ?>
            <hr>
            <div class="form-container">
                <form class="form-body" action="" method="POST" onSubmit="return validateUpdateUserInput()">

                    <h3>Update a user profile</h3>

                    <input type="text" name="user_id" id="user_id" value='<?= $user_id ?>' hidden>

                    <div class="form-input">
                        <label>First Name</label>
                        <span><input type="text" name="firstname" id="first_name" value='<?= $first_name ?>'></span>
                    </div>
                    <div class="form-input">
                        <label>Last Name</label>
                        <span> <input type="text" name="lastname" id="last_name" value='<?= $last_name ?>'> </span>
                    </div>
                    <div class="form-input">
                        <label>Date of Birth</label>
                        <span><input type="date" name="dob" id="dob" value='<?= $dob ?>'> </span>
                    </div>
                    <div class="form-input">
                        <label>Email</label>
                        <span><input type="email" name="email" id="email" value='<?= $email ?>'> </span>
                    </div>

                    <!-- <div class="form-input">
                        <label>Username</label>
                        <span><input type="text" name="username" value='<?= $username ?>'></span>
                    </div> -->

                    <!-- <div class="form-input">
                        <label for="roles">Choose a Role</label>
                        <span>
                            <select id="roles" name="role_id">
                                <?php
                                // $roles = get_table_array('roles');
                                // foreach ($roles as $role) {
                                //     $role_id = $role['role_id'];
                                //     $role_name = $role['role_name'];
                                //     if ($update_role_id == $role_id) {
                                //         echo "<option name=role_name value='$role_id' selected>$role_name</option>";
                                //     } else {
                                //         echo "<option name=role_name value='$role_id'>$role_name</option>";
                                //     }
                                // }
                                ?>
                            </select>
                        </span>
                    </div> -->
                    <div class="form-submit">
                        <input type="submit" name="update_user" value="Update">
                    </div>
                </form>
            </div>
        <?php } ?>

    <?php } ?>

</div>