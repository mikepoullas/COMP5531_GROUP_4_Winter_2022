<?php

$session_user_id = $_SESSION['user_id'];

// UPDATE
if (isset($_POST['update_course'])) {

    $id = mysqli_real_escape_string($conn, $_GET['update_id']);

    // receive all input values from the form
    $course_name = mysqli_real_escape_string($conn, $_POST['course_name']);
    $course_number = mysqli_real_escape_string($conn, $_POST['course_number']);

    // form validation: ensure that the form is correctly filled ...
    // by adding (array_push()) corresponding error unto $errors array
    if (empty($course_name)) {
        array_push($errors, "Course Name is required");
    }
    if (empty($course_number)) {
        array_push($errors, "Course Number is required");
    }

    if (count($errors) == 0) {
        $update = "UPDATE course SET course_name = '$course_name', course_number = '$course_number' WHERE course_id ='$id'";

        if (mysqli_query($conn, $update)) {
            array_push($success, "Update Successful");
        } else {
            array_push($errors, "Error updating course: ", mysqli_error($conn));
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
        ORDER BY g.grade_id ASC";
    } else {
        $query = "SELECT g.*, u.first_name, u.last_name, t.*, s.*, c.course_name FROM grades as g
        JOIN student as st ON st.student_id = g.student_id
        JOIN users as u ON u.user_id = st.user_id
        JOIN solution as s ON s.solution_id = g.solution_id
        JOIN task as t ON t.task_id = s.task_id
        JOIN course as c ON c.course_id = t.course_id
        JOIN user_course_section as ucs ON ucs.course_id = c.course_id
        JOIN users as us ON us.user_id = ucs.user_id
        WHERE us.user_id = '$session_user_id'
        ORDER BY g.grade_id ASC";
    }

    $grades = mysqli_query($conn, $query);

    ?>
    <h2>Grades</h2>
    <hr>
    <table>
        <thead>
            <tr>
                <th>Grade ID</th>
                <th>Grade</th>
                <th>Student Name</th>
                <th>Solution Type</th>
                <th>Task Content</th>
                <th>Solution Content</th>
                <th>Course Name</th>
                <th>Action</th>
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
                    <td><?= $id ?></td>
                    <td><?= $grade ?></td>
                    <td><?= $student_name ?></td>
                    <td><?= $solution_type ?></td>
                    <td><?= $task_content ?></td>
                    <td><?= $solution_content ?></td>
                    <td><?= $course_name ?></td>
                    <td><a href="?page=grades&delete_view=true&delete_id=<?= $id ?>" onclick="return confirm('Are you sure you want to delete?')">Delete</a></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

</div>