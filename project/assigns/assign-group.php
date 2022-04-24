<!--
CODE CONTRIBUTOR:

# COMP 5531 - GROUP 4 (Winter 2022)
Student_ID  First_Name  Last_Name   Email
40159305    shafiq      IMTIAZ      s_mtiaz@encs.concordia.ca
21917730    michael     POULLAS     m_poull@encs.concordia.ca
-->

<script>
    function validateStudentCoursegroup() {

        var student_id, course_id, group_id;

        student_id = document.getElementById("user_id").value;
        course_id = document.getElementById("course_id").value;
        group_id = document.getElementById("group_id").value;

        if (course_id == '') {
            alert("Please select a course from the list.");
            document.getElementById("course_id").focus();
            return false;
        } else if (group_id == '') {
            alert("Please select a group from the list. ");
            document.getElementById("group_id").focus();
            return false;
        } else if (student_id == '') {
            alert("Please select a student from the list.");
            document.getElementById("user_id").focus();
            return false;
        } else
            return true;

    }
</script>

<?php

$session_user_id = $_SESSION['user_id'];

if (isset($_GET['group_id'])) {
    $session_group_id = $_GET['group_id'];
}

if (isset($_GET['course_id'])) {
    $session_course_id = $_GET['course_id'];
}

/*******************************************************
 * ADD SQL
 ********************************************************/

if (isset($_POST['assign'])) {

    $user_id = mysqli_real_escape_string($conn, $_POST['user_id']);
    $course_id = mysqli_real_escape_string($conn, $_POST['course_id']);

    $group_id = mysqli_real_escape_string($conn, $_POST['group_id']);
    $_GET['group_id'] = $group_id;

    $student_id = mysqli_fetch_assoc(get_records_where('student', 'user_id', $user_id))['student_id'];

    $query = "SELECT * FROM member_of_group as mg
    JOIN group_of_course as gc ON mg.group_id = gc.group_id
    WHERE mg.student_id = '$student_id' AND gc.course_id = '$course_id'";
    $check = mysqli_query($conn, $query);

    foreach ($check as $row) {
        $check_group_id = $row['group_id'];
        if ($check_group_id == $group_id) {
            array_push($errors, "This student is already assigned to this group.");
        } elseif (mysqli_num_rows($check) > 0) {
            array_push($errors, "This student is already assigned to another course group.");
        }
    }

    if (count($errors) == 0) {

        $add = "INSERT INTO member_of_group (student_id, group_id) VALUES('$student_id', '$group_id')";

        if (mysqli_query($conn, $add)) {
            array_push($success, "Student has been assigned to course successfully");
        } else {
            array_push($errors, "Could not INSERT error: " . mysqli_error($conn));
        }
    }
}

/*******************************************************
 * UPDATE SQL
 ********************************************************/

if (isset($_POST['update'])) {

    $user_id = mysqli_real_escape_string($conn, $_GET['user_id']);
    $course_id = mysqli_real_escape_string($conn, $_GET['course_id']);

    $group_id = mysqli_real_escape_string($conn, $_POST['group_id']);
    $_GET['group_id'] = $group_id;

    $student_id = mysqli_fetch_assoc(get_records_where('student', 'user_id', $user_id))['student_id'];

    $query = "SELECT * FROM member_of_group as mg
    JOIN group_of_course as gc ON mg.group_id = gc.group_id
    WHERE mg.student_id = '$student_id' AND gc.course_id = '$course_id'";
    $check = mysqli_query($conn, $query);

    foreach ($check as $row) {
        $check_group_id = $row['group_id'];
        if ($check_group_id == $group_id) {
            array_push($errors, "This student is already assigned to this group.");
        }
    }

    if (count($errors) == 0) {

        $update = "UPDATE member_of_group SET group_id = '$group_id' WHERE student_id ='$student_id'";
        if (mysqli_query($conn, $update)) {
            array_push($success, "Updated Successfully.");

            // if update is done on group leader, make last member group leader
            if (isGroupLeader($student_id, $check_group_id)) {
                $check = "SELECT * FROM member_of_group as mg WHERE mg.group_id = '$check_group_id'";

                if (mysqli_num_rows(mysqli_query($conn, $check)) == 1) {
                    $last_member_sid = mysqli_fetch_assoc(get_records_where('member_of_group', 'group_id', $check_group_id))['student_id'];
                    $make_last_member_group_leader = "UPDATE student_groups SET group_leader_sid = '$last_member_sid' WHERE group_id = '$check_group_id'";
                    if (mysqli_query($conn,  $make_last_member_group_leader)) {
                        array_push($success, "Last member is now Group Leader.");
                    } else {
                        array_push($errors, "Could not UPDATE Group Leader: " . mysqli_error($conn));
                    }
                } else {
                    $query = "SELECT * FROM member_of_group as mg WHERE mg.group_id = '$check_group_id' LIMIT 1";
                    $first_member_sid = mysqli_fetch_assoc(mysqli_query($conn, $query))['student_id'];
                    $make_first_member_group_leader = "UPDATE student_groups SET group_leader_sid = '$first_member_sid' WHERE group_id = '$check_group_id'";
                    if (mysqli_query($conn,  $make_first_member_group_leader)) {
                        array_push($success, "First member is now Group Leader.");
                    } else {
                        array_push($errors, "Could not UPDATE Group Leader: " . mysqli_error($conn));
                    }
                }
            }
        } else {
            array_push($errors, "Could not UPDATE error: " . mysqli_error($conn));
        }
    }
}

/*******************************************************
 * DELETE SQL
 ********************************************************/

if (isset($_GET['delete_view'])) {

    $user_id = mysqli_real_escape_string($conn, $_GET['user_id']);
    $group_id = mysqli_real_escape_string($conn, $_GET['group_id']);

    $student_id = mysqli_fetch_assoc(get_records_where('student', 'user_id', $user_id))['student_id'];

    if (isGroupLeader($student_id, $group_id)) {
        array_push($errors, "You cannot delete this student as they are the group leader.");
    }

    if (count($errors) == 0) {
        $delete = "DELETE FROM member_of_group WHERE student_id='$student_id' AND group_id='$group_id'";
        if (mysqli_query($conn, $delete)) {
            array_push($success, "Delete successful");
        } else {
            array_push($errors, "Could not DELETE: " . mysqli_error($conn));
        }
    }
}

if (isset($_POST["course_id"])) {
    $user_id_selected = $_POST["user_id"];
    $course_id_selected = $_POST["course_id"];
}

?>

<!-- Table group
Always visible and shows delete error if delete_view is set true -->

<div class="content-body">

    <?php

    display_success();
    display_error();

    if (isset($_GET['group_id'])) {
        if (isAdmin()) {
            $query = "SELECT g.*, c.*, st.*, u.* FROM student_groups as g
            JOIN member_of_group as mg ON mg.group_id = g.group_id
            JOIN student as st ON st.student_id = mg.student_id
            JOIN users as u ON u.user_id = st.user_id
            JOIN group_of_course as gc ON gc.group_id = g.group_id
            JOIN course as c ON c.course_id = gc.course_id
            WHERE g.group_id = '$session_group_id'
            ORDER BY g.group_id ASC";
        } elseif (isProfessor()) {
            $query = "SELECT g.*, c.*, st.*, u.* FROM student_groups as g
            JOIN member_of_group as mg ON mg.group_id = g.group_id
            JOIN student as st ON st.student_id = mg.student_id
            JOIN users as u ON u.user_id = st.user_id
            JOIN group_of_course as gc ON gc.group_id = g.group_id
            JOIN course as c ON c.course_id = gc.course_id
            JOIN prof_of_course as pc ON pc.course_id = c.course_id
            JOIN professor as p ON p.professor_id = pc.professor_id
            WHERE p.user_id = '$session_user_id' AND g.group_id = '$session_group_id'
            ORDER BY g.group_id ASC";
        } else {
            $query = "SELECT g.*, c.*, st.*, u.* FROM student_groups as g
            JOIN member_of_group as mg ON mg.group_id = g.group_id
            JOIN student as st ON st.student_id = mg.student_id
            JOIN users as u ON u.user_id = st.user_id
            JOIN group_of_course as gc ON gc.group_id = g.group_id
            JOIN course as c ON c.course_id = gc.course_id
            WHERE g.group_id = '$session_group_id'
            ORDER BY g.group_id ASC";
        }
    } else {
        if (isAdmin()) {
            $query = "SELECT g.*, c.*, st.*, u.* FROM student_groups as g
            JOIN member_of_group as mg ON mg.group_id = g.group_id
            JOIN student as st ON st.student_id = mg.student_id
            JOIN users as u ON u.user_id = st.user_id
            JOIN group_of_course as gc ON gc.group_id = g.group_id
            JOIN course as c ON c.course_id = gc.course_id
            ORDER BY g.group_id ASC";
        }
    }

    $results = mysqli_query($conn, $query);

    ?>

    <h2>Group - Members</h2>
    <hr>
    <table>
        <thead>
            <tr>
                <th>Group Name</th>
                <th>Student Name</th>
                <th>Course Name</th>
                <th colspan="2">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($results as $row) {
                $user_id = $row['user_id'];
                $group_id = $row['group_id'];
                $group_name = $row['group_name'];
                $student_name = $row['first_name'] . " " . $row['last_name'];
                $course_id = $row['course_id'];
                $course_name = $row['course_name'];
                $student_id = $row['student_id'];
            ?>
                <tr>
                    <td><?= $group_name ?></td>
                    <?php if (isGroupLeader($student_id, $group_id)) { ?>
                        <td><u><?= $student_name ?></u></td>
                    <?php } else { ?>
                        <td><?= $student_name ?></td>
                    <?php } ?>
                    <td><?= $course_name ?></td>
                    <?php if (!isStudent()) { ?>
                        <td><a href="?page=assign-group&update_view=true&user_id=<?= $user_id ?>&course_id=<?= $course_id ?>&group_id=<?= $group_id ?>">Change Group</a></td>
                        <td><a href="?page=assign-group&delete_view=true&user_id=<?= $user_id ?>&group_id=<?= $group_id ?>" onclick="return confirm('Are you sure you want to delete?')">Remove Member</a></td>
                    <?php } ?>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <?php if (isAdmin()) { ?>
        <a href="?page=assign-group&add_view=true">
            <button>Add New</button>
        </a>
    <?php } ?>

    <?php if (isProfessor()) { ?>
        <a href="?page=assign-group&add_view=true&group_id=<?= $group_id ?>">
            <button>Add New</button>
        </a>
    <?php } ?>

    <!-- Add group
    Visible if add_view is set to true -->

    <?php if (isset($_GET['add_view'])) { ?>

        <div class="form-container">
            <form class="form-body" action="" method="POST" onSubmit="return validateStudentCoursegroup()">

                <div class="form-input">
                    <p>Courses</p>
                    <div class="scroll-list">
                        <select name="course_id" id="course_id" onchange="this.form.submit()">
                            <option value="" selected hidden>Choose a Course</option>
                            <?php
                            if (isAdmin()) {
                                $query = "SELECT * FROM course";
                            }
                            if (isProfessor()) {
                                $query = "SELECT * FROM course as c
                                JOIN prof_of_course as pc ON pc.course_id = c.course_id
                                JOIN professor as p ON p.professor_id = pc.professor_id
                                WHERE p.user_id = '$session_user_id'";
                            }
                            $courses = mysqli_query($conn, $query);
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
                    <p>Groups</p>
                    <div class="scroll-list">

                        <select name="add_group_id" id="group_id">
                            <option value="" selected hidden>Choose a group</option>
                            <?php

                            if (isset($_GET['group_id'])) {
                                $group_id = $_GET['group_id'];
                                $group_name = mysqli_fetch_assoc(get_records_where("student_groups", "group_id", $group_id))['group_name'];
                                echo "<option value='$group_id'>$group_name ($course_name)</option>";
                            } else {
                                $query = "SELECT * FROM student_groups as g
                                            JOIN group_of_course as gc ON gc.group_id = g.group_id
                                            JOIN course as c ON c.course_id = gc.course_id
                                            WHERE c.course_id = '$course_id_selected'";

                                $groups = mysqli_query($conn, $query);
                                foreach ($groups as $row) {
                                    $group_id = $row['group_id'];
                                    $group_name = $row['group_name'];
                                    $course_name = $row['course_name'];
                                    echo "<option value='$group_id'>$group_name ($course_name)</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>


                <div class="form-input">
                    <p>Student</p>
                    <div class="scroll-list">
                        <select name="user_id" id="user_id">
                            <option value="" selected hidden>Choose a Student</option>
                            <?php
                            $query = "SELECT * FROM users as u
                            JOIN student as st ON st.user_id = u.user_id
                            JOIN user_course_section as ucs ON ucs.user_id = u.user_id
                            WHERE ucs.course_id = '$course_id_selected'";

                            $users = mysqli_query($conn, $query);
                            foreach ($users as $user) {
                                $user_id = $user['user_id'];
                                $first_name = $user['first_name'];
                                $last_name = $user['last_name'];
                                if ($user_id_selected == $user_id) {
                                    echo "<option value='$user_id' selected>$first_name $last_name</option>";
                                } else {
                                    echo "<option value='$user_id'>$first_name $last_name</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="form-submit">
                    <input type="submit" name="assign" value="Assign">
                </div>
            </form>
        </div>

    <?php } ?>


    <!-- Update group
    Visible if update_view is set to true -->

    <?php if (isset($_GET['update_view'])) { ?>

        <?php
        $user_id = mysqli_real_escape_string($conn, $_GET['user_id']);
        $group_id = mysqli_real_escape_string($conn, $_GET['group_id']);

        $query = "SELECT * FROM users as u
        JOIN student as st ON st.user_id = u.user_id
        JOIN user_course_section  as ucs ON ucs.user_id = u.user_id
        JOIN course as c ON c.course_id = ucs.course_id
        JOIN group_of_course as gc ON gc.course_id = c.course_id
        JOIN student_groups as g ON g.group_id = gc.group_id
        WHERE u.user_id='$user_id' AND g.group_id = '$group_id'";

        $results = mysqli_query($conn, $query);

        foreach ($results as $row) {
            $student_name = $row['first_name'] . " " . $row['last_name'];
            $user_id = $row['user_id'];
            $course_id = $row['course_id'];
            $course_name = $row['course_name'];
            $update_group_id = $row['group_id'];
        }
        ?>

        <div class="form-container">
            <form class="form-body" action="" method="POST">

                <div class="form-input">
                    <label>Student</label>
                    <span><b><?= $student_name ?></b></span>
                </div>

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
                    <p>Course groups</p>
                    <div class="scroll-list">
                        <select name="group_id" id="group_id">
                            <option value="" selected hidden>Choose a group</option>
                            <?php

                            // Get limited group names based on course_id
                            $query = "SELECT * FROM student_groups as g
                                        JOIN group_of_course as gc ON gc.group_id = g.group_id
                                        JOIN course as c ON c.course_id = gc.course_id
										WHERE c.course_id = '$course_id'";
                            $groups = mysqli_query($conn, $query);

                            foreach ($groups as $row) {
                                $group_id = $row['group_id'];
                                $group_name = $row['group_name'];
                                $course_name = $row['course_name'];
                                if ($update_group_id == $group_id) {
                                    echo "<option value='$group_id' selected>$group_name ($course_name)</option>";
                                } else {
                                    echo "<option value='$group_id'>$group_name ($course_name)</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="form-submit">
                    <input type="submit" name="update" value="Update">
                </div>
            </form>
        </div>

    <?php } ?>

</div>