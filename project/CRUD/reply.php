<?php

$session_user_id = $_SESSION['user_id'];

//DOWNLOAD
if (isset($_GET['download_file'])) {
    download_file($_GET['download_file']);
}

// DELETE
if (isset($_GET['delete_id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['delete_id']);
    $delete = "DELETE FROM reply WHERE reply_id='$id'";
    $file_id = $_GET['file_id'];
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

    $query = "SELECT * FROM reply as r
    JOIN forum as f ON f.forum_id = r.forum_id
    JOIN course as c ON c.course_id = f.course_id
    LEFT JOIN files as fl ON fl.file_id = r.file_id
    JOIN users as u ON u.user_id = r.posted_by_uid
    ORDER BY r.reply_id ASC";
    $replys = mysqli_query($conn, $query);

    ?>
    <h2>Replys</h2>
    <hr>
    <table>
        <thead>
            <tr>
                <th>Content</th>
                <th>Posted by</th>
                <th>Posted on</th>
                <th>Forum Title</th>
                <th>Course Name</th>
                <th>Files</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($replys as $row) {
                $id = $row['reply_id'];
                $content = $row['reply_content'];
                $posted_by = $row['username'];
                $posted_on = date_convert($row['posted_on']);
                $forum_title = $row['forum_title'];
                $course_name = $row['course_name'];
                $file_id = $row['file_id'];
                $file_name = $row['file_name'];
            ?>
                <tr>
                    <td><?= $content ?></td>
                    <td><?= $posted_by ?></td>
                    <td><?= $posted_on ?></td>
                    <td><?= $forum_title ?></td>
                    <td><?= $course_name ?></td>
                    <td><a href='?page=reply&download_file=<?= $file_id ?>'><?= $file_name ?></a></td>
                    <td><a href="?page=reply&delete_id=<?= $id ?>&file_id=<?= $file_id ?>" onclick="return confirm('Are you sure you want to delete?')">Delete Reply</a></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

</div>