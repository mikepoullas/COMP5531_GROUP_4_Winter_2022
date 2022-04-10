<?php

// ADD
if (isset($_POST['add_group'])) {

    pre_print($_POST);

    // receive all input values from the form
    $group_name = mysqli_real_escape_string($conn, $_POST['group_name']);
    $group_leader_sid = mysqli_real_escape_string($conn, $_POST['group_leader_sid']);
    $course_id = mysqli_real_escape_string($conn, $_POST['course_id']);

    // form validation: ensure that the form is correctly filled ...
    // by adding (array_push()) corresponding error unto $errors array
    if (empty($group_name)) {
        array_push($errors, "Group Name is required");
    }
    if (empty($course_id)) {
        array_push($errors, "Course is required");
    }
    if (empty($group_leader_sid)) {
        array_push($errors, "Group Leader SID is required");
    }

    $check = "SELECT * FROM student as st
    WHERE st.student_id = '$group_leader_sid'";
    $result = mysqli_query($conn, $check);

    if (mysqli_num_rows($result) == 0) {
        array_push($errors, "Group Leader SID is not in the database");
    }


    if (count($errors) == 0) {
        $add_group = "INSERT INTO student_group (group_name, group_leader_sid) VALUES('$group_name', '$group_leader_sid')";

        if (mysqli_query($conn, $add_group)) {
            array_push($success, "Group added Successful");
            $group_id = $conn->insert_id;
            $add_group_of_course = "INSERT INTO group_of_course (group_id, course_id) VALUES('$group_id', '$course_id')";
            if (mysqli_query($conn, $add_group_of_course)) {
                array_push($success, "Group added to Course Successful");
            } else {
                array_push($errors, "Group not added to course");
            }
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
    $course_id = mysqli_real_escape_string($conn, $_POST['course_id']);

    // form validation: ensure that the form is correctly filled ...
    // by adding (array_push()) corresponding error unto $errors array
    if (empty($group_name)) {
        array_push($errors, "Group Name is required");
    }
    if (empty($course_id)) {
        array_push($errors, "Course is required");
    }
    if (empty($group_leader_sid)) {
        array_push($errors, "Group Leader SID is required");
    }

    if (count($errors) == 0) {
        $update_group = "UPDATE student_group set group_name = '$group_name', group_leader_sid = '$group_leader_sid' WHERE group_id ='$id'";
        $update_group_of_course = "UPDATE group_of_course set course_id = '$course_id' WHERE group_id ='$id'";

        if (mysqli_query($conn, $update_group) && mysqli_query($conn, $update_group_of_course)) {
            array_push($success, "Update Successful");
        } else {
            array_push($errors, "Error updating groups: ", mysqli_error($conn));
        }
    }
}


// DELETE
if (isset($_GET['delete_id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['delete_id']);

    $delete_group_of_course = "DELETE FROM group_of_course WHERE group_id='$id'";
    $delete_group = "DELETE FROM student_group WHERE group_id='$id'";

    if (mysqli_query($conn, $delete_group_of_course) && mysqli_query($conn, $delete_group)) {
        array_push($success, "Delete successful");
    } else {
        array_push($errors, "Delete error: " . mysqli_error($conn));
    }
}

?>

<div class="content-body">
    <?php

    display_success();
    display_error();

    $query = "SELECT g.*, s.*, u.*, c.* FROM student_group as g
    JOIN student as s ON g.group_leader_sid = s.student_id
    JOIN users as u ON s.user_id = u.user_id
    LEFT JOIN group_of_course as gc ON gc.group_id = g.group_id
    LEFT JOIN course as c ON c.course_id = gc.course_id
    ORDER BY g.group_id ASC";
    $results = mysqli_query($conn, $query);

    ?>
    <h2>Groups</h2>
    <hr>
    <table>
        <thead>
            <tr>
                <?php isAdmin() ? print '<th>Group ID</th>' : ''; ?>
                <th>Group Name</th>
                <th>Group Leader SID</th>
                <th>Group Leader Name</th>
                <th>Course Name</th>
                <?php !isStudent() ? print '<th colspan="2">Action</th>' : ''; ?>

            </tr>
        </thead>
        <tbody>
            <?php

            foreach ($results as $groups) {
                $id = $groups['group_id'];
                $group_name = $groups['group_name'];
                $group_leader_sid = $groups['group_leader_sid'];
                $group_leader_name = $groups['first_name'] . " " . $groups['last_name'];
                $course_name = $groups['course_name'];
            ?>
                <tr>
                    <?php if (isAdmin()) {
                        echo '<td>' . $id . '</td>';
                    } ?>
                    <td><?= $group_name ?></td>
                    <td><?= $group_leader_sid ?></td>
                    <td><?= $group_leader_name ?></td>
                    <td><?= $course_name ?></td>
                    <?php if (!isStudent()) {
                        echo '<td><a href="?page=groups&update_view=true&update_id=' . $id . '">Update</a></td>';
                        echo "<td><a href='?page=groups&delete_view=true&delete_id=" . $id . "' onclick='return confirm(&quot;Are you sure you want to delete?&quot;)'>Delete</a></td>";
                    } ?>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <?php if (!isStudent()) { ?>
        <a href="?page=groups&add_view=true">
            <button>Add Group</button>
        </a>

        <?php if (isset($_GET['add_view'])) { ?>
            <hr>
            <div class="form-container">
                <form class="form-body" action="" method="POST">

                    <h3>Add Group</h3>

                    <div class="form-input">
                        <label>Group Name</label>
                        <span><input type="text" name="group_name"></span>
                    </div>

                    <div class="form-input">
                        <label for="course_id">Course</label>
                        <span>
                            <select name="course_id">
                                <option value="" selected hidden>Choose Course</option>
                                <?php
                                $courses = get_table_array('course');
                                foreach ($courses as $row) {
                                    $course_id = $row['course_id'];
                                    $course_name = $row['course_name'];
                                    echo "<option name=course_id value='$course_id'>$course_name</option>";
                                }
                                ?>
                            </select>
                        </span>
                    </div>

                    <div class="form-input">
                        <label for="group_leader_sid">Group Leader</label>
                        <span>
                            <select name="group_leader_sid">
                                <option value="" selected hidden>Choose Student</option>
                                <?php
                                $query = "SELECT * FROM student as st
                                            JOIN users as u ON st.user_id = u.user_id";
                                $groups = mysqli_query($conn, $query);
                                foreach ($groups as $row) {
                                    $student_id = $row['student_id'];
                                    $student_name = $row['first_name'] . " " . $row['last_name'];
                                    echo "<option name=group_leader_sid value='$student_id'>$student_name</option>";
                                }
                                ?>
                            </select>
                        </span>
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
            $query = "SELECT * FROM student_group WHERE group_id='$id'";
            $results = mysqli_query($conn, $query);

            foreach ($results as $row) {
                $id = $row['group_id'];
                $group_name = $row['group_name'];
                $group_leader_sid = $row['group_leader_sid'];
            }
            ?>

            <hr>
            <div class="form-container">
                <form class="form-body" action="" method="POST">

                    <h3>Update Group</h3>

                    <div class="form-input">
                        <label>Group Name</label>
                        <span><input type="text" name="group_name" value='<?= $group_name ?>'></span>
                    </div>

                    <div class="form-input">
                        <label for="course_id">Course</label>
                        <span>
                            <select name="course_id">
                                <option value="" selected hidden>Choose Course</option>
                                <?php
                                $courses = get_table_array('course');
                                foreach ($courses as $row) {
                                    $course_id = $row['course_id'];
                                    $course_name = $row['course_name'];
                                    echo "<option name=course_id value='$course_id'>$course_name</option>";
                                }
                                ?>
                            </select>
                        </span>
                    </div>

                    <div class="form-input">
                        <label for="group_leader_sid">Group Leader</label>
                        <span>
                            <select name="group_leader_sid">
                                <option value="" selected hidden>Choose Student</option>
                                <?php
                                $query = "SELECT * FROM student as st
                                            JOIN users as u ON st.user_id = u.user_id";
                                $groups = mysqli_query($conn, $query);
                                foreach ($groups as $row) {
                                    $student_id = $row['student_id'];
                                    $student_name = $row['first_name'] . " " . $row['last_name'];
                                    echo "<option name=group_leader_sid value='$student_id'>$student_name</option>";
                                }
                                ?>
                            </select>
                        </span>
                    </div>

                    <div class="form-submit">
                        <input type="submit" name="update_group" value="Update">
                    </div>
                </form>
            </div>

        <?php } ?>

    <?php } ?>

</div>