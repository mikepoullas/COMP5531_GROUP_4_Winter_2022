<?php

// initializing variables
$id = $role_name = $role_description = "";

// ADD
if (isset($_POST['add_role'])) {

    // receive all input values from the form
    $role_name = mysqli_real_escape_string($conn, $_POST['role_name']);
    $role_description = mysqli_real_escape_string($conn, $_POST['role_description']);

    // form validation: ensure that the form is correctly filled ...
    // by adding (array_push()) corresponding error unto $errors array
    if (empty($role_name)) {
        array_push($errors, "Role Name is required");
    }
    if (empty($role_description)) {
        array_push($errors, "Role description is required");
    }

    if (count($errors) == 0) {
        $add = "INSERT INTO roles (role_name, role_description) VALUES('$role_name', '$role_description');";

        if (mysqli_query($conn, $add)) {
            array_push($success, "Role added Successful");
            // clear variables
            $role_name = $role_description = "";
        } else {
            array_push($errors, "Error adding role: ", mysqli_error($conn));
        }
    }
}

// UPDATE
if (isset($_POST['update_role'])) {

    $id = mysqli_real_escape_string($conn, $_GET['update_id']);

    // receive all input values from the form
    $role_name = mysqli_real_escape_string($conn, $_POST['role_name']);
    $role_description = mysqli_real_escape_string($conn, $_POST['role_description']);

    // form validation: ensure that the form is correctly filled ...
    // by adding (array_push()) corresponding error unto $errors array
    if (empty($role_name)) {
        array_push($errors, "Role Name is required");
    }
    if (empty($role_description)) {
        array_push($errors, "Role description is required");
    }

    if (count($errors) == 0) {
        $update = "UPDATE roles set role_name = '$role_name', role_description = '$role_description' WHERE role_id ='$id'";

        if (mysqli_query($conn, $update)) {
            array_push($success, "Update Successful");
            // clear variables
            $role_name = "";
        } else {
            array_push($errors, "Error updating role: ", mysqli_error($conn));
        }
    }
}

// DELETE
if (isset($_GET['delete_id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['delete_id']);
    $delete = "DELETE FROM roles WHERE role_id='$id'";
    if (mysqli_query($conn, $delete)) {
        array_push($success, "Delete successful");
    } else {
        array_push($errors, "Delete error: " . mysqli_error($conn));
    }
}

?>

<div class="content-body">
    <?php if (isset($_GET['delete_view'])) {
        display_success();
        display_error();
    }

    $query = "SELECT * FROM roles ORDER BY role_id ASC";
    $results = mysqli_query($conn, $query);

    ?>
    <p><b>Roles</b></p>
    <table>
        <thead>
            <tr>
                <?php isAdmin() ? print '<th>Role ID</th>' : ''; ?>
                <th>Role Name</th>
                <th>Role Description</th>
                <?php isAdmin() ? print '<th colspan="2">Action</th>' : ''; ?>

            </tr>
        </thead>
        <tbody>
            <?php
            while ($row = mysqli_fetch_assoc($results)) {
                $id = $row['role_id'];
                $role_name = $row['role_name'];
                $role_description = $row['role_description'];
            ?>
                <tr>
                    <?php if (isAdmin()) {
                        echo '<td>' . $id . '</td>';
                    } ?>
                    <td><?php echo $role_name ?></td>
                    <td><?php echo $role_description ?></td>
                    <?php if (isAdmin()) {
                        echo '<td><a href="?page=roles&update_view=true&update_id=' . $id . '">Update</a></td>';
                        echo '<td><a href="?page=roles&delete_view=true&delete_id=' . $id . '">Delete</a></td>';
                    } ?>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>

    <?php if (isAdmin()) { ?>
        <a href="?page=roles&add_view=true">
            <button>Add Role</button>
        </a>
    <?php } ?>

    <?php if (isset($_GET['add_view'])) { ?>
        <hr>
        <div class="form-container">
            <form class="form-body" action="" method="post">
                <?php echo display_success(); ?>
                <?php echo display_error(); ?>
                <div class="form-input">
                    <p><b>Add a role</b></p>
                    <label>Role Name</label>
                    <span><input type="text" name="role_name"></span>
                </div>
                <div class="form-input">
                    <label>Role Description</label>
                    <span><input type="text" name="role_description"> </span>
                </div>
                <div class="form-submit">
                    <input type="submit" name="add_role" value="Add">
                </div>
            </form>
        </div>

    <?php } ?>

    <?php if (isset($_GET['update_view'])) { ?>

        <?php
        $id = mysqli_real_escape_string($conn, $_GET['update_id']);
        $query = "SELECT * FROM roles WHERE role_id='$id'";
        $results = mysqli_query($conn, $query);

        while ($row = mysqli_fetch_assoc($results)) {
            $id = $row['role_id'];
            $role_name = $row['role_name'];
            $role_description = $row['role description'];
        }
        ?>

        <hr>
        <div class="form-container">
            <form class="form-body" action="" method="post">
                <?php echo display_success(); ?>
                <?php echo display_error(); ?>
                <div class="form-input">
                    <p><b>Update Role</b></p>
                    <label>Role ID</label>
                    <span><b><?= $id ?></b></span>
                </div>
                <div class="form-input">
                    <label>Role Name</label>
                    <span><input type="text" name="role_name" value='<?= $role_name ?>'></span>
                </div>
                <div class="form-input">
                    <label>Role description</label>
                    <span><input type="text" name="role_description" value='<?= $role_description ?>'> </span>
                </div>
                <div class="form-submit">
                    <input type="submit" name="update_role" value="Update">
                </div>
            </form>
        </div>

    <?php } ?>

</div>