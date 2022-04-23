<?php

// DELETE
if (isset($_GET['delete_id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['delete_id']);
    $delete = "DELETE FROM task WHERE task_id='$id'";
    if (mysqli_query($conn, $delete)) {
        delete_file($_GET['file_id']);
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

    $query = "SELECT * FROM task as t
    JOIN files as f ON f.file_id = t.file_id
    JOIN course as c ON c.course_id = t.course_id
    JOIN users as u ON u.user_id = f.uploaded_by_uid
    ORDER BY t.task_id ASC";
    $task = mysqli_query($conn, $query);

    ?>
    <h2>Task</h2>
    <hr>
    <table>
        <thead>
            <tr>
                <th>Task ID</th>
                <th>Type</th>
                <th>Content</th>
                <th>Uploaded by</th>
                <th>Uploaded on</th>
                <th>Course Name</th>
                <th>File Name</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($task as $row) {
                $id = $row['task_id'];
                $type = $row['task_type'];
                $content = $row['task_content'];
                $uploaded_by = $row['username'];
                $uploaded_on = date_convert($row['uploaded_on']);
                $course_name = $row['course_name'];
                $file_id = $row['file_id'];
                $file_name = $row['file_name'];
            ?>
                <tr>
                    <td><?= $id ?></td>
                    <td><?= $type ?></td>
                    <td><?= $content ?></td>
                    <td><?= $uploaded_by ?></td>
                    <td><?= $uploaded_on ?></td>
                    <td><?= $course_name ?></td>
                    <td><?= $file_name ?></td>
                    <td><a href="?page=task&delete_id=<?= $id ?>&file_id=<?= $file_id ?>" onclick="return confirm('Are you sure you want to delete?')">Delete Task</a></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

</div>