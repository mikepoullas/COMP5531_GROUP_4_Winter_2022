<?php

$user_id = $_SESSION['user_id'];

// DELETE
if (isset($_GET['delete_id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['delete_id']);
    $delete = "DELETE FROM comment WHERE comment_id='$id'";
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

    $query = "SELECT c.*, d.*, u.*, cr.course_name, g.group_name FROM comment as c
                JOIN discussion as d ON d.discussion_id = c.discussion_id
                JOIN student_group as g ON g.group_id = d.group_id
                JOIN group_of_course as gc ON gc.group_id = g.group_id
                JOIN course as cr ON cr.course_id = gc.course_id
                JOIN users as u ON u.user_id = c.posted_by_uid
                ORDER BY c.comment_id ASC";
    $comments = mysqli_query($conn, $query);

    ?>
    <h2>Comments</h2>
    <hr>
    <table>
        <thead>
            <tr>
                <th>Comment ID</th>
                <th>Content</th>
                <th>Posted by</th>
                <th>Posted on</th>
                <th>Discussion Title</th>
                <th>Group Name</th>
                <th>Course Name</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($comments as $row) {
                $id = $row['comment_id'];
                $content = $row['comment_content'];
                $posted_by = $row['username'];
                $posted_on = date_convert($row['posted_on']);
                $discussion_title = $row['discussion_title'];
                $group_name = $row['group_name'];
                $course_name = $row['course_name'];
            ?>
                <tr>

                    <td><?= $id ?></td>

                    <td><?= $content ?></td>
                    <td><?= $posted_by ?></td>
                    <td><?= $posted_on ?></td>
                    <td><?= $discussion_title ?></td>
                    <td><?= $group_name ?></td>
                    <td><?= $course_name ?></td>
                    <td><a href="?page=comments&delete_view=true&delete_id=<?= $id ?>" onclick="return confirm('Are you sure you want to delete?')">Delete Comment</a></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

</div>