<?php

$session_user_id = $_SESSION['user_id'];

// ADD
if (isset($_POST['add_group'])) {


    $group_name = mysqli_real_escape_string($conn, $_POST['group_name']);
    $group_leader_sid = mysqli_real_escape_string($conn, $_POST['group_leader_sid']);
    $course_id = mysqli_real_escape_string($conn, $_POST['course_id']);



    if (empty($group_name)) {
        array_push($errors, "Group Name is required");
    }
    if (empty($course_id)) {
        array_push($errors, "Course is required");
    }
    if (empty($group_leader_sid)) {
        array_push($errors, "Group Leader SID is required");
    }

    $query = "SELECT * FROM student as st
    WHERE st.student_id = '$group_leader_sid'";
    $check = mysqli_query($conn, $query);

    if (mysqli_num_rows($check) == 0) {
        array_push($errors, "Group Leader SID is not in the database");
    }

    $query = "SELECT * FROM member_of_group as mg
    JOIN group_of_course as gc ON mg.group_id = gc.group_id
    WHERE mg.student_id = '$group_leader_sid' AND gc.course_id = '$course_id'";
    $check = mysqli_query($conn, $query);

    if (mysqli_num_rows($check) == 1) {
        array_push($errors, "Group Leader is already in a course group");
    }


    if (count($errors) == 0) {
        $add_group = "INSERT INTO student_groups (group_name, group_leader_sid) VALUES('$group_name', '$group_leader_sid')";

        if (mysqli_query($conn, $add_group)) {

            $group_id = $conn->insert_id;

            $add_member_of_group = "INSERT INTO member_of_group (student_id, group_id) VALUES('$group_leader_sid', '$group_id')";
            $add_group_of_course = "INSERT INTO group_of_course (group_id, course_id) VALUES('$group_id', '$course_id')";

            if (mysqli_query($conn, $add_member_of_group) && mysqli_query($conn, $add_group_of_course)) {
                array_push($success, "Group added Successfully");
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

    $group_id = mysqli_real_escape_string($conn, $_GET['update_id']);


    $group_name = mysqli_real_escape_string($conn, $_POST['group_name']);
    $group_leader_sid = mysqli_real_escape_string($conn, $_POST['group_leader_sid']);
    $course_id = mysqli_real_escape_string($conn, $_GET['course_id']);



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
        $update_group = "UPDATE student_groups SET group_name = '$group_name', group_leader_sid = '$group_leader_sid' WHERE group_id ='$group_id'";
        $update_group_of_course = "UPDATE group_of_course SET course_id = '$course_id' WHERE group_id ='$group_id'";

        if (mysqli_query($conn, $update_group) && mysqli_query($conn, $update_group_of_course)) {
            array_push($success, "Update Successful");
        } else {
            array_push($errors, "Error updating groups: ", mysqli_error($conn));
        }
    }
}


// DELETE
if (isset($_GET['delete_id'])) {
    $group_id = mysqli_real_escape_string($conn, $_GET['delete_id']);

    $delete_group = "DELETE FROM student_groups WHERE group_id='$group_id'";
    $delete_group_of_course = "DELETE FROM group_of_course WHERE group_id='$group_id'";

    if (mysqli_query($conn, $delete_group) && mysqli_query($conn, $delete_group_of_course)) {
        array_push($success, "Delete successful");
    } else {
        array_push($errors, "Delete error: " . mysqli_error($conn));
    }
}

if (isset($_POST["course_id"])) {
    $course_id_selected = $_POST["course_id"];
}

?>


<div class="content-body">
    <?php

    display_success();
    display_error();

    if (isAdmin()) {
        $query = "SELECT g.*, s.*, u.*, c.* FROM student_groups as g
        JOIN student as s ON g.group_leader_sid = s.student_id
        JOIN users as u ON s.user_id = u.user_id
        LEFT JOIN group_of_course as gc ON gc.group_id = g.group_id
        LEFT JOIN course as c ON c.course_id = gc.course_id
        ORDER BY g.group_id ASC";
    } else {
        $query = "SELECT g.*, s.*, u.*, c.* FROM student_groups as g
        JOIN student as s ON g.group_leader_sid = s.student_id
        JOIN users as u ON s.user_id = u.user_id
        JOIN group_of_course as gc ON gc.group_id = g.group_id
        JOIN course as c ON c.course_id = gc.course_id
        JOIN user_course_section as ucs ON ucs.course_id = c.course_id
        JOIN users as us ON us.user_id = ucs.user_id
        WHERE us.user_id = '$session_user_id'
        ORDER BY g.group_id ASC";
    }

    $results = mysqli_query($conn, $query);

    ?>
    <h2>Groups</h2>
    <hr>
    <table>
        <thead>
            <tr>
                <th>Group Name</th>
                <th>Group Leader SID</th>
                <th>Group Leader</th>
                <th>Course Name</th>
                <?php !isStudent() ? print '<th colspan="3">Action</th>' : ''; ?>

            </tr>
        </thead>
        <tbody>
            <?php

            foreach ($results as $groups) {
                $group_id = $groups['group_id'];
                $group_name = $groups['group_name'];
                $group_leader_sid = $groups['group_leader_sid'];
                $group_leader_name = $groups['first_name'] . " " . $groups['last_name'];
                $course_id = $groups['course_id'];
                $course_name = $groups['course_name'];
            ?>
                <tr>
                    <td><?= $group_name ?></td>
                    <td><?= $group_leader_sid ?></td>
                    <td><u><?= $group_leader_name ?></u></td>
                    <td><?= $course_name ?></td>
                    <?php if (!isStudent()) {
                        echo '<td><a href="?page=groups&update_view=true&update_id=' . $group_id . '&course_id=' . $course_id . '">Update</a></td>';
                        echo '<td><a href="?page=assign-group&group_id=' . $group_id . '&course_id=' . $course_id . '">Manage</a></td>';
                        echo "<td><a href='?page=groups&delete_id=" . $group_id . "' onclick='return confirm(&quot;Are you sure you want to delete?&quot;)'>Delete</a></td>";
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
                        <p>Course</p>
                        <div class="scroll-list">
                            <select name="course_id" onchange="this.form.submit()">
                                <option value="" selected hidden>Choose Course</option>
                                <?php
                                if (isProfessor()) {
                                    $query = "SELECT c.* FROM course as c
                                    JOIN prof_of_course as pc ON pc.course_id = c.course_id
                                    JOIN professor as p ON p.professor_id = pc.professor_id
                                    WHERE p.user_id = '$session_user_id'";
                                    $courses = mysqli_query($conn, $query);
                                }
                                if (isAdmin()) {
                                    $courses = get_table_array('course');
                                }

                                foreach ($courses as $row) {
                                    $course_id = $row['course_id'];
                                    $course_name = $row['course_name'];

                                    if ($course_id_selected == $course_id) {
                                        echo "<option value='$course_id' selected>$course_name</option>";
                                    } else {
                                        echo "<option value='$course_id'>$course_name</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-input">
                        <p>Group Leader</p>
                        <div class="scroll-list">
                            <select name="group_leader_sid">
                                <option value="" selected hidden>Choose Student</option>
                                <?php
                                $query = "SELECT * FROM student as st
                                JOIN users as u ON st.user_id = u.user_id
                                JOIN user_course_section as ucs ON ucs.user_id = u.user_id
                                JOIN course as c ON c.course_id = ucs.course_id
                                WHERE c.course_id = '$course_id_selected'
                                ORDER BY st.student_id ASC";
                                $groups = mysqli_query($conn, $query);
                                foreach ($groups as $row) {
                                    $student_id = $row['student_id'];
                                    $student_name = $row['first_name'] . " " . $row['last_name'];
                                    echo "<option name=group_leader_sid value='$student_id'>$student_name</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-input">
                        <label>Group Name</label>
                        <span><input type="text" name="group_name"></span>
                    </div>

                    <div class="form-submit">
                        <input type="submit" name="add_group" value="Add">
                    </div>
                </form>
            </div>

        <?php } ?>

        <?php if (isset($_GET['update_view'])) { ?>

            <?php
            $group_id = mysqli_real_escape_string($conn, $_GET['update_id']);

            $query = "SELECT * FROM student_groups as g
            JOIN group_of_course as gc ON gc.group_id = g.group_id
            JOIN course as c ON c.course_id = gc.course_id
            WHERE g.group_id='$group_id'";
            $results = mysqli_query($conn, $query);

            foreach ($results as $row) {
                $group_id = $row['group_id'];
                $group_name = $row['group_name'];
                $group_leader_sid = $row['group_leader_sid'];
                $course_id = $row['course_id'];
                $course_name = $row['course_name'];
            }

            ?>

            <hr>
            <div class="form-container">
                <form class="form-body" action="" method="POST">

                    <h3>Update Group</h3>

                    <div class="form-input">
                        <p>Course</p>
                        <div class="scroll-list">
                            <select name="course_id" id="course_id" disabled>
                                <?php
                                echo "<option value='$course_id' selected>$course_name</option>";
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-input">
                        <p>Group Leader</p>
                        <div class="scroll-list">
                            <select name="group_leader_sid">
                                <option value="" selected hidden>Choose Student</option>
                                <?php
                                $query = "SELECT * FROM student as st
                                JOIN users as u ON st.user_id = u.user_id
                                JOIN user_course_section as ucs ON ucs.user_id = u.user_id
                                JOIN course as c ON c.course_id = ucs.course_id
                                WHERE c.course_id = '$course_id'
                                ORDER BY st.student_id ASC";
                                $groups = mysqli_query($conn, $query);
                                foreach ($groups as $row) {
                                    $student_id = $row['student_id'];
                                    $student_name = $row['first_name'] . " " . $row['last_name'];
                                    if ($group_leader_sid == $student_id) {
                                        echo "<option name=group_leader_sid value='$student_id' selected>$student_name</option>";
                                    } else {
                                        echo "<option name=group_leader_sid value='$student_id'>$student_name</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-input">
                        <label>Group Name</label>
                        <span><input type="text" name="group_name" value='<?= $group_name ?>'></span>
                    </div>

                    <div class="form-submit">
                        <input type="submit" name="update_group" value="Update">
                    </div>
                </form>
            </div>

        <?php } ?>

    <?php } ?>

</div>