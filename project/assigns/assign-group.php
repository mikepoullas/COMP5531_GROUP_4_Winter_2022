<script>
    function validateStudentCourseSection() {

        var student_id, course_id, section_id;

        student_id = document.getElementById("user_id").value;
        course_id = document.getElementById("course_id").value;
        section_id = document.getElementById("section_id").value;

        if (student_id == '') {
            alert("Please select a student from the list.");
            document.getElementById("user_id").focus();
            return false;
        } else if (course_id == '') {
            alert("Please select a course from the list.");
            document.getElementById("course_id").focus();
            return false;
        } else if (section_id == '') {
            alert("Please select a section from the list. ");
            document.getElementById("section_id").focus();
            return false;
        } else
            return true;


    }
</script>

<?php

/*******************************************************
 * ADD SQL
 ********************************************************/

if (isset($_POST['assign'])) {

    //    if (empty($_POST['user_id'])) {
    //        array_push($errors, "Please select a user");
    //    } else {
    $user_id = mysqli_real_escape_string($conn, $_POST['user_id']);
    //    }

    //    if (empty($_POST['course_id'])) {
    //        array_push($errors, "Please select a course");
    //    } else {
    $course_id = mysqli_real_escape_string($conn, $_POST['course_id']);
    //    }

    //    if (empty($_POST['section_id'])) {
    //        array_push($errors, "Please select a section");
    //    } else {
    $section_id = mysqli_real_escape_string($conn, $_POST['section_id']);
    //    }

    $query = "SELECT * FROM user_course_section WHERE user_id = '$user_id'";
    $check = mysqli_query($conn, $query);

    foreach ($check as $row) {
        $check_course_id = $row['course_id'];
        $check_section_id = $row['section_id'];

        if ($check_course_id == $course_id) {
            array_push($errors, "This student is already assigned to this course.");
            if ($check_section_id == $section_id) {
                array_push($errors, "This student is already assigned to this section.");
            }
        }
    }

    if (count($errors) == 0) {

        $add = "INSERT INTO user_course_section (user_id, course_id, section_id) VALUES('$user_id', '$course_id', '$section_id')";

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

    //    if (empty($_POST['course_id'])) {
    //        array_push($errors, "Please select a course");
    //    } else {
    $course_id = mysqli_real_escape_string($conn, $_GET['course_id']);
    //    }

    //    if (empty($_POST['section_id'])) {
    //        array_push($errors, "Please select a section");
    //    } else {
    $section_id = mysqli_real_escape_string($conn, $_POST['section_id']);
    $_GET['section_id'] = $section_id;
    // }

    $query = "SELECT * FROM user_course_section WHERE user_id = '$user_id'";
    $check = mysqli_query($conn, $query);

    foreach ($check as $row) {
        $check_course_id = $row['course_id'];
        $check_section_id = $row['section_id'];

        // Check to see if the Student is already assigned the same course and/or section
        if ($check_course_id == $course_id) {
            if ($check_section_id == $section_id) {
                array_push($errors, "Student is already assigned to this course and section.");
            }
        }
    }

    if (count($errors) == 0) {

        $update = "UPDATE user_course_section set section_id = '$section_id' WHERE user_id ='$user_id' AND course_id = '$course_id'";

        if (mysqli_query($conn, $update)) {
            array_push($success, "Updated Successfully.");
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
    $course_id = mysqli_real_escape_string($conn, $_GET['course_id']);
    $section_id = mysqli_real_escape_string($conn, $_GET['section_id']);

    $delete = "DELETE FROM user_course_section WHERE user_id='$user_id' AND course_id='$course_id' AND section_id='$section_id'";

    if (mysqli_query($conn, $delete)) {
        array_push($success, "Delete successful.");
    } else {
        array_push($errors, "Could not DELETE error: " . mysqli_error($conn));
    }
}

if (isset($_POST["course_id"])) {

    $user_id_selected = $_POST["user_id"];
    $course_id_selected = $_POST["course_id"];
}

?>

<!-- Table Section
Always visible and shows delete error if delete_view is set true -->

<div class="content-body">

    <?php

    display_success();
    display_error();

    $query = "SELECT * FROM student_group as g
                JOIN member_of_group as mg ON mg.group_id = g.group_id
                JOIN student as st ON st.student_id = mg.student_id
                JOIN users as u ON u.user_id = st.user_id
                JOIN group_of_course as gc ON gc.group_id = g.group_id
                JOIN course as c ON c.course_id = gc.course_id
                ORDER BY g.group_id ASC";

    $results = mysqli_query($conn, $query);

    ?>

    <h2>Group - Members</h2>
    <hr>
    <table>
        <thead>
            <tr>
                <th>Group ID</th>
                <th>Group Name</th>
                <th>Student ID</th>
                <th>Student Name</th>
                <th>Course Name</th>
                <th colspan="2">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($results as $row) {
                $group_id = $row['group_id'];
                $group_name = $row['group_name'];
                $student_id = $row['student_id'];
                $student_name = $row['first_name'] . " " . $row['last_name'];
                $course_id = $row['course_id'];
                $course_name = $row['course_name'];
            ?>
                <tr>
                    <td><?= $group_id ?></td>
                    <td><?= $group_name ?></td>
                    <td><?= $student_id ?></td>
                    <td><?= $student_name ?></td>
                    <td><?= $course_name ?></td>
                    <td><a href="?page=assign-group&update_view=true&user_id=<?= $user_id ?>&group_id=<?= $group_id ?>&course_id=<?= $course_id ?>">Change Group</a></td>
                    <td><a href="?page=assign-group&delete_view=true&user_id=<?= $user_id ?>&group_id=<?= $group_id ?>&course_id=<?= $course_id ?>" onclick="return confirm('Are you sure you want to delete?')">Remove</a></td>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>

    <?php if (isAdmin()) { ?>
        <a href="?page=assign-group&add_view=true">
            <button>Add New</button>
        </a>

        <!-- Add Section
    Visible if add_view is set to true -->

        <?php if (isset($_GET['add_view'])) { ?>

            <div class="form-container">
                <form class="form-body" action="" method="POST" onSubmit="return validateStudentCourseSection()">

                    <div class="form-input">
                        <p>Student</p>
                        <div class="scroll-list">
                            <select name="user_id" id="user_id">
                                <option value="" selected hidden>Choose a Student</option>
                                <?php
                                $query = "SELECT * FROM users as u
                                        JOIN student as st ON st.user_id = u.user_id
                                        WHERE role_id != 1";
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

                    <div class="form-input">
                        <p>Courses</p>
                        <div class="scroll-list">
                            <select name="course_id" id="course_id" onchange="this.form.submit()">
                                <option value="" selected hidden>Choose a Course</option>
                                <?php
                                $query = "SELECT * FROM course";
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
                        <p>Sections</p>
                        <div class="scroll-list">

                            <select name="section_id" id="section_id">
                                <option value="" selected hidden>Choose a Section</option>
                                <?php

                                $query = "SELECT * FROM section as s
                                        JOIN course as c ON c.course_id = s.course_id
										WHERE c.course_id = '$course_id_selected'";

                                $sections = mysqli_query($conn, $query);
                                foreach ($sections as $row) {
                                    $section_id = $row['section_id'];
                                    $section_name = $row['section_name'];
                                    $course_name = $row['course_name'];
                                    echo "<option value='$section_id'>$section_name ($course_name)</option>";
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


        <!-- Update Section
    Visible if update_view is set to true -->

        <?php if (isset($_GET['update_view'])) { ?>

            <?php
            $user_id = mysqli_real_escape_string($conn, $_GET['user_id']);
            $course_id = mysqli_real_escape_string($conn, $_GET['course_id']);
            $section_id = mysqli_real_escape_string($conn, $_GET['section_id']);

            $query = "SELECT * FROM users as u
        JOIN student as st ON st.user_id = u.user_id
        JOIN user_course_section as ucs ON ucs.user_id = u.user_id
        JOIN course as c ON c.course_id = ucs.course_id
        JOIN section as s ON s.section_id = ucs.section_id
        WHERE u.user_id='$user_id' AND c.course_id = '$course_id' AND s.section_id = '$section_id'";

            $results = mysqli_query($conn, $query);

            foreach ($results as $row) {
                $student_name = $row['first_name'] . " " . $row['last_name'];
                $user_id = $row['user_id'];
                $course_id = $row['course_id'];
                $course_name = $row['course_name'];
                $update_section_name = $row['section_name'];
            }
            ?>

            <div class="form-container">
                <form class="form-body" action="" method="POST">

                    <div class="form-input">
                        <label>Student: </label>
                        <span><b><?= $student_name ?></b></span>
                    </div>

                    <div class="form-input">
                        <p>Course:</p>
                        <div class="scroll-list">
                            <select name="course_id" id="course_id" disabled>
                                <?php
                                echo "<option value='$course_id' selected>$course_name</option>";
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-input">
                        <p>Course Sections:</p>
                        <div class="scroll-list">
                            <select name="section_id" id="section_id">
                                <option value="" selected hidden>Choose a section:</option>
                                <?php

                                // Get limited section names based on course_id
                                $query = "SELECT * FROM section as s
                                        JOIN course as c ON c.course_id = s.course_id
										WHERE c.course_id = '$course_id'";

                                $sections = mysqli_query($conn, $query);
                                foreach ($sections as $row) {
                                    $section_id = $row['section_id'];
                                    $section_name = $row['section_name'];
                                    $course_name = $row['course_name'];
                                    if ($update_section_name == $section_name) {
                                        echo "<option value='$section_id' selected>$section_name ($course_name)</option>";
                                    } else {
                                        echo "<option value='$section_id'>$section_name ($course_name)</option>";
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

    <?php } ?>

</div>