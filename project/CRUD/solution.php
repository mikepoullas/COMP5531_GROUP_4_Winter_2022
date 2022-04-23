<?php

$session_user_id = $_SESSION['user_id'];

//DOWNLOAD
if (isset($_GET['download_file'])) {
    download_file($_GET['download_file']);
}

// DELETE
if (isset($_GET['delete_id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['delete_id']);
    $delete = "DELETE FROM solution WHERE solution_id='$id'";
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

    $query = "SELECT * FROM solution as s
    JOIN task as t ON t.task_id = s.task_id
    JOIN course as c ON c.course_id = t.course_id
    JOIN files as f ON f.file_id = s.file_id
    JOIN users as u ON u.user_id = f.uploaded_by_uid
    ORDER BY s.solution_id ASC";
    $solution = mysqli_query($conn, $query);

    ?>
    <h2>Solution</h2>
    <hr>
    <table>
        <thead>
            <tr>
                <th>Type</th>
                <th>Content</th>
                <th>Uploaded by</th>
                <th>Uploaded on</th>
                <th>Course Name</th>
                <th>Files</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($solution as $row) {
                $id = $row['solution_id'];
                $type = $row['solution_type'];
                $content = $row['solution_content'];
                $uploaded_by = $row['username'];
                $uploaded_on = date_convert($row['uploaded_on']);
                $course_name = $row['course_name'];
                $file_id = $row['file_id'];
                $file_name = $row['file_name'];
            ?>
                <tr>
                    <td><?= $type ?></td>
                    <td><?= $content ?></td>
                    <td><?= $uploaded_by ?></td>
                    <td><?= $uploaded_on ?></td>
                    <td><?= $course_name ?></td>
                    <td><a href='?page=solution&download_file=<?= $file_id ?>'><?= $file_name ?></a></td>
                    <td><a href="?page=solution&delete_id=<?= $id ?>&file_id=<?= $file_id ?>" onclick="return confirm('Are you sure you want to delete?')">Delete Solution</a></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

</div>