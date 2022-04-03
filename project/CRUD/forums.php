<?php

$user_id = $_SESSION['user_id'];

// ADD
if (isset($_POST['add_forum'])) {

    // receive all input values from the form
    $title = mysqli_real_escape_string($conn, $_POST['forum_title']);
    $content = mysqli_real_escape_string($conn, $_POST['forum_content']);
    $course_id = mysqli_real_escape_string($conn, $_POST['course_id']);

    // form validation: ensure that the form is correctly filled ...
    // by adding (array_push()) corresponding error unto $errors array
    if (empty($title)) {
        array_push($errors, "Title is required");
    }
    if (empty($content)) {
        array_push($errors, "Content is required");
    }
    if (empty($course_id)) {
        array_push($errors, "course is required");
    }

    if (count($errors) == 0) {
        $add = "INSERT INTO forum (forum_title, forum_content, posted_by_uid, posted_on, course_id)
            VALUES('$title', '$content', '$user_id', NOW(),'$course_id')";

        if (mysqli_query($conn, $add)) {
            array_push($success, "Added successfully");
        } else {
            array_push($errors, "Error adding: ", mysqli_error($conn));
        }
    }
}

// UPDATE
if (isset($_POST['update_forum'])) {

    $id = mysqli_real_escape_string($conn, $_GET['update_id']);

    // receive all input values from the form
    $title = mysqli_real_escape_string($conn, $_POST['forum_title']);
    $content = mysqli_real_escape_string($conn, $_POST['forum_content']);
    // $course_id = mysqli_real_escape_string($conn, $_POST['course_id']);

    // form validation: ensure that the form is correctly filled ...
    // by adding (array_push()) corresponding error unto $errors array
    if (empty($title)) {
        array_push($errors, "Title is required");
    }
    if (empty($content)) {
        array_push($errors, "Content is required");
    }
    // if (empty($course_id)) {
    //     array_push($errors, "course is required");
    // }

    if (count($errors) == 0) {

        $update = "UPDATE forum set forum_title = '$title', forum_content = '$content'
                    WHERE forum_id ='$id'";

        if (mysqli_query($conn, $update)) {
            array_push($success, "Update Successful");
        } else {
            array_push($errors, "Error updating " . mysqli_error($conn));
        }
    }
}

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
    if (isset($_GET['delete_view'])) {
        display_success();
        display_error();
    }

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
                    <td><a href="?page=forums&delete_view=true&delete_id=?<?= $id ?>" onclick="return confirm('Are you sure you want to delete?')">Delete</a></td>
                </tr>

            <?php } ?>
        </tbody>
    </table>

</div>