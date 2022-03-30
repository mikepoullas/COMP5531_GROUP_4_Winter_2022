<?php

// initializing variables
$id = $title = $posted_by = $posted_on = $content = $group_id = $group_name = $course_id = "";
$user_id = $_SESSION['user_id'];

// ADD
if (isset($_POST['add_discussion'])) {

    // receive all input values from the form
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $group_id = mysqli_real_escape_string($conn, $_POST['group_id']);

    // form validation: ensure that the form is correctly filled ...
    // by adding (array_push()) corresponding error unto $errors array
    if (empty($title)) {
        array_push($errors, "Title is required");
    }
    if (empty($content)) {
        array_push($errors, "Content is required");
    }
    if (empty($group_id)) {
        array_push($errors, "Group is required");
    }

    if (count($errors) == 0) {
        $add = "INSERT INTO discussion (title, content, posted_by_uid, posted_on, group_id)
            VALUES('$title', '$content', '$user_id', NOW(),'$group_id')";

        if (mysqli_query($conn, $add)) {
            array_push($success, "Added successfully");
            // clear variables
            $title = $content = $group_id = "";
        } else {
            array_push($errors, "Error adding: ", mysqli_error($conn));
        }
    }
}

// UPDATE
if (isset($_POST['update_discussion'])) {

    $id = mysqli_real_escape_string($conn, $_GET['update_id']);

    // receive all input values from the form
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    // $group_id = mysqli_real_escape_string($conn, $_POST['group_id']);

    // form validation: ensure that the form is correctly filled ...
    // by adding (array_push()) corresponding error unto $errors array
    if (empty($title)) {
        array_push($errors, "Title is required");
    }
    if (empty($content)) {
        array_push($errors, "Content is required");
    }
    // if (empty($group_id)) {
    //     array_push($errors, "Group is required");
    // }

    if (count($errors) == 0) {

        $update = "UPDATE discussion set title = '$title', content = '$content'
                    WHERE discussion_id ='$id'";

        if (mysqli_query($conn, $update)) {
            array_push($success, "Update Successful");
            // clear variables
            $course_name = $course_number = "";
        } else {
            array_push($errors, "Error updating " . mysqli_error($conn));
        }
    }
}

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
    if (isset($_GET['delete_view'])) {
        display_success();
        display_error();
    }

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
                <?php isAdmin() ? print '<th>Discussion ID</th>' : ''; ?>
                <th>Title</th>
                <th>Content</th>
                <th>Posted by</th>
                <th>Posted on</th>
                <th>Group Name</th>
                <th>Course Name</th>
                <?php isAdmin() ? print '<th>Action</th>' : ''; ?>

            </tr>
        </thead>
        <tbody>
            <?php

            foreach ($discussions as $row) {
                $id = $row['discussion_id'];
                $title = $row['title'];
                $content = $row['content'];
                $posted_by = $row['username'];
                $posted_on = date_convert($row['posted_on']);
                $group_id = $row['group_id'];
                $group_name = $row['group_name'];
                $course_name = $row['course_name'];
            ?>
                <tr>
                    <?php if (isAdmin()) {
                        echo '<td>' . $id . '</td>';
                    } ?>
                    <td><?= $title ?></td>
                    <td><?= $content ?></td>
                    <td><?= $posted_by ?></td>
                    <td><?= $posted_on ?></td>
                    <td><?= $group_name ?></td>
                    <td><?= $course_name ?></td>
                    <?php if (isAdmin()) {
                        echo '<td><a href="?page=discussions&delete_view=true&delete_id=' . $id . '">Delete</a></td>';
                    } ?>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>

</div>