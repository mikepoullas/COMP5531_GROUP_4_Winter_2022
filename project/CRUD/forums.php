<?php

// DELETE
if (isset($_GET['delete_id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['delete_id']);
    $delete = "DELETE FROM forum WHERE forum_id='$id'";
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


    $query = "SELECT f.*, u.username, c.course_name FROM forum as f
    JOIN users as u ON  u.user_id = f.posted_by_uid
    JOIN course as c ON c.course_id = f.course_id
    ORDER BY f.forum_id ASC";
    $forums = mysqli_query($conn, $query);

    ?>
    <h2>Forums</h2>
    <hr>
    <table>
        <thead>
            <tr>
                <th>Forum ID</th>
                <th>Forum Title</th>
                <th>Content</th>
                <th>Posted by</th>
                <th>Posted on</th>
                <th>Course Name</th>
                <th>Action</th>

            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($forums as $row) {
                $id = $row['forum_id'];
                $title = $row['forum_title'];
                $content = $row['forum_content'];
                $posted_by = $row['username'];
                $posted_on = date_convert($row['posted_on']);
                $course_id = $row['course_id'];
                $course_name = $row['course_name'];
            ?>
                <tr>

                    <td><?= $id ?></td>
                    <td><?= $title ?></td>
                    <td><?= $content ?></td>
                    <td><?= $posted_by ?></td>
                    <td><?= $posted_on ?></td>
                    <td><?= $course_name ?></td>
                    <td><a href="?page=forums&delete_view=true&delete_id=?<?= $id ?>" onclick="return confirm('Are you sure you want to delete?')">Delete Forum</a></td>
                </tr>

            <?php } ?>
        </tbody>
    </table>

</div>