<?php

$user_id = $_SESSION['user_id'];

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

    $query = "SELECT g.*, u.first_name, u.last_name, s.solution_content, t.task_content, c.course_name FROM grades as g
    JOIN student as st ON st.student_id = g.student_id
    JOIN users as u ON u.user_id = st.user_id
    JOIN solution as s ON s.solution_id = g.solution_id
    JOIN task as t ON t.task_id = s.task_id
    JOIN course as c ON c.course_id = t.course_id
    ORDER BY g.grade_id ASC";
    $grades = mysqli_query($conn, $query);

    ?>
    <h2>grades</h2>
    <hr>
    <table>
        <thead>
            <tr>
                <th>Grade ID</th>
                <th>Grade</th>
                <th>Student Name</th>
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
                $task_content = $row['task_content'];
                $solution_content = $row['solution_content'];
                $course_name = $row['course_name'];
            ?>
                <tr>
                    <td><?= $id ?></td>
                    <td><?= $grade ?></td>
                    <td><?= $student_name ?></td>
                    <td><?= $task_content ?></td>
                    <td><?= $solution_content ?></td>
                    <td><?= $course_name ?></td>
                    <td><a href="?page=grades&delete_view=true&delete_id=<?= $id ?>" onclick="return confirm('Are you sure you want to delete?')">Delete Grade</a></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

</div>