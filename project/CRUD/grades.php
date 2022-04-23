<script>
    function validateGrade() {

        var grade;

        grade = document.getElementById("grade").value;

        if (grade == '') {
            alert("Please enter a grade.");
            document.getElementById("grade").focus();
            return false;
        } else
            return true;

    }
</script>
<?php

$session_user_id = $_SESSION['user_id'];

// UPDATE
if (isset($_POST['update_grade'])) {

    $id = mysqli_real_escape_string($conn, $_GET['update_id']);
    $grade = mysqli_real_escape_string($conn, $_POST['grade']);



    if (empty($grade)) {
        array_push($errors, "Grade is required");
    }

    if (count($errors) == 0) {
        $update = "UPDATE grades SET grade = '$grade' WHERE grade_id ='$id'";

        if (mysqli_query($conn, $update)) {
            array_push($success, "Update Successful");
        } else {
            array_push($errors, "Error updating grade: ", mysqli_error($conn));
        }
    }
}

// DELETE
if (isset($_GET['delete_id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['delete_id']);
    $delete = "DELETE FROM grades WHERE grade_id='$id'";
    if (mysqli_query($conn, $delete)) {
        array_push($success, "Delete successful");
    } else {
        array_push($errors, "Error deleting " . mysqli_error($conn));
    }
}

?>

<div class="content-body">
    <?php

    display_success();
    display_error();

    if (isAdmin()) {
        $query = "SELECT g.*, u.first_name, u.last_name, t.*, s.*, c.course_name FROM grades as g
        JOIN student as st ON st.student_id = g.student_id
        JOIN users as u ON u.user_id = st.user_id
        JOIN solution as s ON s.solution_id = g.solution_id
        JOIN task as t ON t.task_id = s.task_id
        JOIN course as c ON c.course_id = t.course_id
        ORDER BY t.task_id ASC";
    } else {
        $query = "SELECT g.*, u.first_name, u.last_name, t.*, s.*, c.course_name FROM grades as g
        LEFT JOIN student as st ON st.student_id = g.student_id
        JOIN users as u ON u.user_id = st.user_id
        JOIN solution as s ON s.solution_id = g.solution_id
        JOIN task as t ON t.task_id = s.task_id
        JOIN course as c ON c.course_id = t.course_id
        JOIN user_course_section as ucs ON ucs.course_id = c.course_id
        JOIN users as us ON us.user_id = ucs.user_id
        WHERE us.user_id = '$session_user_id'
        ORDER BY t.task_id ASC";
    }

    $grades = mysqli_query($conn, $query);

    ?>
    <h2>Grades</h2>
    <hr>
    <table>
        <thead>
            <tr>
                <th>Grade</th>
                <th>Student Name</th>
                <th>Solution Type</th>
                <th>Task Content</th>
                <th>Solution Content</th>
                <th>Course Name</th>
                <th colspan="2">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($grades as $row) {
                $id = $row['grade_id'];
                $grade = $row['grade'];
                $student_name = $row['first_name'] . " " . $row['last_name'];
                $solution_type = $row['solution_type'];
                $task_content = $row['task_content'];
                $solution_content = $row['solution_content'];
                $course_name = $row['course_name'];
            ?>
                <tr>
                    <td><?= $grade ?></td>
                    <td><?= $student_name ?></td>
                    <td><?= $solution_type ?></td>
                    <td><?= $task_content ?></td>
                    <td><?= $solution_content ?></td>
                    <td><?= $course_name ?></td>
                    <td><a href="?page=grades&update_view=true&update_id=<?= $id ?>">Update</a></td>
                    <td><a href="?page=grades&delete_id=<?= $id ?>" onclick="return confirm('Are you sure you want to delete?')">Delete</a></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>


    <?php if (isset($_GET['update_view'])) { ?>

        <?php
        $id = mysqli_real_escape_string($conn, $_GET['update_id']);
        $query = "SELECT * FROM grades as g
        JOIN solution as s ON s.solution_id = g.solution_id
        JOIN task as t ON t.task_id = s.task_id
        JOIN student as st ON st.student_id = g.student_id
        JOIN users as u ON u.user_id = st.user_id
        WHERE grade_id='$id'";
        $results = mysqli_query($conn, $query);

        foreach ($results as $row) {
            $grade = $row['grade'];
            $student_id = $row['student_id'];
            $student_name = $row['first_name'] . " " . $row['last_name'];
            $task_content = $row['task_content'];
            $solution_content = $row['solution_content'];
        }
        ?>

        <hr>
        <div class="form-container">
            <form class="form-body" action="" method="POST" onsubmit="return validateGrade()">

                <h3>Update Grade</h3>

                <div class="form-input">
                    <label>Student ID</label>
                    <span><b><?= $student_id ?></b></span>
                </div>

                <div class="form-input">
                    <label>Student Name</label>
                    <span><b><?= $student_name ?></b></span>
                </div>

                <div class="form-input">
                    <label>Task</label>
                    <span><b><?= $task_content ?></b></span>
                </div>

                <div class="form-input">
                    <label>Solution</label>
                    <span><b><?= $solution_content ?></b></span>
                </div>

                <div class="form-input">
                    <label>Grade</label>
                    <span><input type="number" name="grade" id="grade" value='<?= $grade ?>'></span>
                </div>

                <div class="form-submit">
                    <input type="submit" name="update_grade" value="Update">
                </div>
            </form>
        </div>

    <?php } ?>

</div>