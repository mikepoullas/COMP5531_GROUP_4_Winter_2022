<script>
    function validateProfessorCourse() {

        var student_id, course_id, can_enroll;

        professor_id = document.getElementById("user_id").value;
        course_id = document.getElementById("course_id").value;

        if (professor_id == '') {
            alert("Please select a professor from the list.");
            document.getElementById("user_id").focus();
            return false;
        } else if (course_id == '') {
            alert("Please select a course from the list.");
            document.getElementById("course_id").focus();
            return false;
        } else
            return true;
    }
</script>

<?php

/*******************************************************
 * ADD SQL
 ********************************************************/

if (isset($_POST['add'])) {

    $user_id = mysqli_real_escape_string($conn, $_POST['user_id']);
    $_GET['user_id'] = $user_id;

    $course_id = mysqli_real_escape_string($conn, $_POST['course_id']);
    $_GET['course_id'] = $course_id;

    $query = "SELECT * FROM user_course_section WHERE user_id = '$user_id'";
    $check = mysqli_query($conn, $query);

    foreach ($check as $row) {
        $check_course_id = $row['course_id'];

        if ($check_course_id == $course_id) {
            array_push($errors, "Professor is already assigned to this course.");
        }
    }

    if (count($errors) == 0) {

        $add_ucs = "INSERT INTO user_course_section (user_id, course_id) VALUES('$user_id', '$course_id')";

        $professor_id = mysqli_fetch_assoc(get_records_where('professor', 'user_id', $user_id))['professor_id'];
        $add_prof = "INSERT INTO prof_of_course (professor_id, course_id) VALUES('$professor_id', '$course_id')";

        if (mysqli_query($conn, $add_ucs) && mysqli_query($conn, $add_prof)) {
            array_push($success, "Professor successfully assigned to this course");
        } else {
            array_push($errors, "Could not INSERT error: " . mysqli_error($conn));
        }
    }
}

/*******************************************************
 * DELETE SQL
 ********************************************************/

if (isset($_GET['delete_view'])) {

    $user_id = mysqli_real_escape_string($conn, $_GET['user_id']);
    $course_id = mysqli_real_escape_string($conn, $_GET['course_id']);

    $delete_ucs = "DELETE FROM user_course_section WHERE user_id='$user_id' AND course_id='$course_id'";

    $professor_id = mysqli_fetch_assoc(get_records_where('professor', 'user_id', $user_id))['professor_id'];
    $delete_prof = "DELETE FROM prof_of_course WHERE professor_id='$professor_id' AND course_id='$course_id'";

    if (mysqli_query($conn, $delete_ucs) || mysqli_query($conn, $delete_prof)) {
        array_push($success, "Delete successful");
    } else {
        array_push($errors, "Could not DELETE error: " . mysqli_error($conn));
    }
}

//if (isset($_POST["course_id"])) {
//
//    $user_id_selected = $_POST["user_id"];
//    $course_id_selected = $_POST["course_id"];
//}

?>

<!-- Table Section
Always visible and shows delete error if delete_view is set true -->

<div class="content-body">

    <?php

    display_success();
    display_error();

    $query = "SELECT * FROM users as u
    JOIN professor as p ON p.user_id = u.user_id
    JOIN user_course_section as ucs ON ucs.user_id = u.user_id
    JOIN course as c ON c.course_id = ucs.course_id
    ORDER BY u.user_id ASC";

    $results = mysqli_query($conn, $query);

    ?>

    <h2>Professors - Course - Sections</h2>
    <hr>
    <table>
        <thead>
            <tr>
                <th>Professor ID</th>
                <th>Professor Name</th>
                <th>Course Name</th>
                <th colspan="2">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($results as $row) {
                $user_id = $row['user_id'];
                $professor_id = $row['professor_id'];
                $first_name = $row['first_name'];
                $last_name = $row['last_name'];
                $course_id = $row['course_id'];
                $course_name = $row['course_name'];
            ?>
                <tr>
                    <td><?= $professor_id ?></td>
                    <td><?= $first_name . " " . $last_name ?></td>
                    <td><?= $course_name ?></td>
                    <td><a href="?page=assign-professors&delete_view=true&user_id=<?= $user_id ?>&course_id=<?= $course_id ?>" onclick="return confirm('Are you sure you want to delete?')">Delete Course</a></td>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>

    <?php if (isAdmin()) { ?>
        <a href="?page=assign-professors&add_view=true">
            <button>Add New</button>
        </a>
    <?php } ?>

    <!-- Add Section
    Visible if add_view is set to true -->

    <?php if (isset($_GET['add_view'])) { ?>

        <div class="form-container">
            <form class="form-body" action="" method="POST" onSubmit="return validateProfessorCourse()">

                <div class="form-input">
                    <p>Courses</p>
                    <div class="scroll-list">
                        <select name="course_id" id="course_id">
                            <option value="" selected hidden>Choose a Course</option>
                            <?php
                            $query = "SELECT * FROM course";
                            $courses = mysqli_query($conn, $query);
                            foreach ($courses as $row) {
                                $course_id = $row['course_id'];
                                $course_name = $row['course_name'];
                                echo "<option value='$course_id'>$course_name</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="form-input">
                    <p>Professor</p>
                    <div class="scroll-list">
                        <select name="user_id" id="user_id">
                            <option value="" selected hidden>Choose a Professor</option>
                            <?php
                            $query = "SELECT * FROM users as u
                            JOIN professor as p ON p.user_id = u.user_id";
                            $users = mysqli_query($conn, $query);
                            foreach ($users as $user) {
                                $user_id = $user['user_id'];
                                $first_name = $user['first_name'];
                                $last_name = $user['last_name'];
                                echo "<option value='$user_id'>$first_name $last_name</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="form-submit">
                    <input type="submit" name="add" id="Submit" value="Add">
                </div>
            </form>
        </div>

    <?php } ?>

</div>