<?php

$user_id = $_SESSION['user_id'];

// DELETE
if (isset($_GET['delete_id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['delete_id']);
    $delete = "DELETE FROM discussion WHERE discussion_id='$id'";
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

    $query = "SELECT d.*, u.username, g.group_name, c.course_name FROM discussion as d
    JOIN users as u ON d.posted_by_uid = u.user_id
    JOIN student_group as g ON g.group_id = d.group_id
    JOIN group_of_course as gc ON gc.group_id = g.group_id
    JOIN course as c ON c.course_id = gc.course_id
    ORDER BY discussion_id ASC";
    $discussions = mysqli_query($conn, $query);

    ?>
    <h2>Discussions</h2>
    <hr>
    <table>
        <thead>
            <tr>
                <th>Discussion ID</th>
                <th>Title</th>
                <th>Content</th>
                <th>Posted by</th>
                <th>Posted on</th>
                <th>Group Name</th>
                <th>Course Name</th>
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
                $group_id = $row['group_id'];
                $group_name = $row['group_name'];
                $course_name = $row['course_name'];
            ?>
                <tr>
                    <td><?= $id ?></td>
                    <td><?= $title ?></td>
                    <td><?= $content ?></td>
                    <td><?= $posted_by ?></td>
                    <td><?= $posted_on ?></td>
                    <td><?= $group_name ?></td>
                    <td><?= $course_name ?></td>
                    <td><a href="?page=discussions&delete_view=true&delete_id=<?= $id ?>" onclick="return confirm('Are you sure you want to delete?')">Delete</a></td>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>

</div>