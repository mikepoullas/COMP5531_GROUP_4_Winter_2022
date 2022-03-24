<?php

// initializing variables
$id = $group_name = $group_leader_sid = "";

// ADD
if (isset($_POST['add_group'])) {

    // receive all input values from the form
    $group_name = mysqli_real_escape_string($conn, $_POST['group_name']);
    $group_leader_sid = mysqli_real_escape_string($conn, $_POST['group_leader_sid']);

    // form validation: ensure that the form is correctly filled ...
    // by adding (array_push()) corresponding error unto $errors array
    if (empty($group_name)) {
        array_push($errors, "Group Name is required");
    }
    if (empty($group_leader_sid)) {
        $group_leader_sid = 0;
    }

    if (count($errors) == 0) {
        $add = "INSERT INTO student_groups (group_name, group_leader_sid) VALUES('$group_name', '$group_leader_sid');";

        if (mysqli_query($conn, $add)) {
            array_push($success, "Group added Successful");
            // clear variables
            $group_name = $group_leader_sid = "";
        } else {
            array_push($errors, "Error adding groups: ", mysqli_error($conn));
        }
    }
}

// UPDATE
if (isset($_POST['update_group'])) {

    $id = mysqli_real_escape_string($conn, $_GET['update_id']);

    // receive all input values from the form
    $group_name = mysqli_real_escape_string($conn, $_POST['group_name']);
    $group_leader_sid = mysqli_real_escape_string($conn, $_POST['group_leader_sid']);

    // form validation: ensure that the form is correctly filled ...
    // by adding (array_push()) corresponding error unto $errors array
    if (empty($group_name)) {
        array_push($errors, "Group Name is required");
    }
    if (empty($group_leader_sid)) {
        $group_leader_sid = 0;
    }

    if (count($errors) == 0) {
        $update = "UPDATE student_groups set group_name = '$group_name', group_leader_sid = '$group_leader_sid' WHERE group_id ='$id'";

        if (mysqli_query($conn, $update)) {
            array_push($success, "Update Successful");
            // clear variables
            $group_name = $group_leader_sid = "";
        } else {
            array_push($errors, "Error updating groups: ", mysqli_error($conn));
        }
    }
}

// DELETE
if (isset($_GET['delete_id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['delete_id']);
    $delete = "DELETE FROM student_groups WHERE group_id='$id'";
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

    $query = "SELECT * FROM student_groups ORDER BY group_id ASC";
    $results = mysqli_query($conn, $query);

    ?>
    <p><b>Groups</b></p>
    <table>
        <thead>
            <tr>
                <?php isAdmin() ? print '<th>Group ID</th>' : ''; ?>
                <th>Group Name</th>
                <th>Group Leader SID</th>
                <?php isAdmin() ? print '<th colspan="2">Action</th>' : ''; ?>

            </tr>
        </thead>
        <tbody>
            <?php
            while ($groups = mysqli_fetch_assoc($results)) {
                $id = $groups['group_id'];
                $group_name = $groups['group_name'];
                $group_leader_sid = $groups['group_leader_sid'];
            ?>
                <tr>
                    <?php if (isAdmin()) {
                        echo '<td>' . $id . '</td>';
                    } ?>
                    <td><?php echo $group_name ?></td>
                    <td><?php echo $group_leader_sid ?></td>
                    <?php if (isAdmin()) {
                        echo '<td><a href="?page=groups&update_view=true&update_id=' . $id . '">Update</a></td>';
                        echo '<td><a href="?page=groups&delete_view=true&delete_id=' . $id . '">Delete</a></td>';
                    } ?>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>

    <?php if (isAdmin()) { ?>
        <a href="?page=groups&add_view=true">
            <button>Add Group</button>
        </a>
    <?php } ?>

    <?php if (isset($_GET['add_view'])) { ?>
        <hr>
        <div class="form-container">
            <form class="form-body" action="" method="post">
                <?php echo display_success(); ?>
                <?php echo display_error(); ?>
                <div class="form-input">
                    <p><b>Add a Group</b></p>
                    <label>Group Name</label>
                    <span><input type="text" name="group_name"></span>
                </div>
                <div class="form-input">
                    <label>Group Leader SID</label>
                    <span><input type="number" name="group_leader_sid"> </span>
                </div>
                <div class="form-submit">
                    <input type="submit" name="add_group" value="Add">
                </div>
            </form>
        </div>

    <?php } ?>

    <?php if (isset($_GET['update_view'])) { ?>

        <?php
        $id = mysqli_real_escape_string($conn, $_GET['update_id']);
        $query = "SELECT * FROM student_groups WHERE group_id='$id'";
        $results = mysqli_query($conn, $query);

        while ($row = mysqli_fetch_assoc($results)) {
            $id = $row['group_id'];
            $group_name = $row['group_name'];
            $group_leader_sid = $row['group_leader_sid'];
        }
        ?>

        <hr>
        <div class="form-container">
            <form class="form-body" action="" method="post">
                <?php echo display_success(); ?>
                <?php echo display_error(); ?>
                <div class="form-input">
                    <p><b>Update Group</b></p>
                    <label>Group ID</label>
                    <span><b><?= $id ?></b></span>
                </div>
                <div class="form-input">
                    <label>Group Name</label>
                    <span><input type="text" name="group_name" value='<?= $group_name ?>'></span>
                </div>
                <div class="form-input">
                    <label>Group Leader SID</label>
                    <span><input type="number" name="group_leader_sid" value='<?= $group_leader_sid ?>'> </span>
                </div>
                <div class="form-submit">
                    <input type="submit" name="update_group" value="Update">
                </div>
            </form>
        </div>

    <?php } ?>

</div>