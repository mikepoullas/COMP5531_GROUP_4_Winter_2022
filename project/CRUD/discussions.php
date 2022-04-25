<?php

//DOWNLOAD
if (isset($_GET['download_file'])) {
    download_file($_GET['download_file']);
}

// DELETE
if (isset($_GET['delete_id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['delete_id']);
    $delete = "DELETE FROM discussion WHERE discussion_id='$id'";
    $file_id = $_GET['delete_file'];
    if (mysqli_query($conn, $delete)) {
        delete_file($file_id);
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

    $query = "SELECT d.*, fl.*, u.username, g.group_name, c.course_name FROM discussion as d
    JOIN users as u ON d.posted_by_uid = u.user_id
    JOIN student_groups as g ON g.group_id = d.group_id
    JOIN group_of_course as gc ON gc.group_id = g.group_id
    JOIN course as c ON c.course_id = gc.course_id
    LEFT JOIN files as fl ON fl.file_id = d.file_id
    ORDER BY discussion_id ASC";
    $discussions = mysqli_query($conn, $query);

    ?>
    <h2>Discussions</h2>
    <hr>
    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Content</th>
                <th>Posted by</th>
                <th>Posted on</th>
                <th>Group Name</th>
                <th>Course Name</th>
                <th>Files</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php

            foreach ($discussions as $row) {
                $id = $row['discussion_id'];
                $title = $row['discussion_title'];
                $content = $row['discussion_content'];
                $posted_by = $row['username'];
                $posted_on = date_convert($row['posted_on']);
                $group_name = $row['group_name'];
                $course_name = $row['course_name'];
                $file_id = $row['file_id'];
                $file_name = $row['file_name'];
            ?>
                <tr>
                    <td><?= $title ?></td>
                    <td><?= $content ?></td>
                    <td><?= $posted_by ?></td>
                    <td><?= $posted_on ?></td>
                    <td><?= $group_name ?></td>
                    <td><?= $course_name ?></td>
                    <td><a href='?page=discussions&download_file=<?= $file_id ?>'><?= $file_name ?></a></td>
                    <td><a href="?page=discussions&delete_id=<?= $id ?>&delete_file=<?= $file_id ?>" onclick="return confirm('Are you sure you want to delete?')">Delete Discussion</a></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

</div>