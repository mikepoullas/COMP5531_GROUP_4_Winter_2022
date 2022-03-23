<?php

// initializing variables
$id = $title = $posted_by = $posted_on = $content = $section_name = "";

// ADD
if (isset($_POST['announcement_add'])) {

    // receive all input values from the form
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);

    // form validation: ensure that the form is correctly filled ...
    // by adding (array_push()) corresponding error unto $errors array
    if (empty($title)) {
        array_push($errors, "Title is required");
    }
    if (empty($content)) {
        array_push($errors, "Content is required");
    }

    $course_add = "INSERT INTO announcement (title, posted_by_uid, posted_on, content, section_id) VALUES('$title', '$content');";

    if (mysqli_query($conn, $course_add)) {
        array_push($success, "Added successfully");
        // clear variables
        $title = $content = "";
    } else {
        array_push($errors, "Error adding: ", mysqli_error($conn));
    }
}

// UPDATE
if (isset($_POST['update_announcement'])) {

    $id = $_GET['update_id'];

    // receive all input values from the form
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);

    // form validation: ensure that the form is correctly filled ...
    // by adding (array_push()) corresponding error unto $errors array
    if (empty($title)) {
        array_push($errors, "Title is required");
    }
    if (empty($content)) {
        array_push($errors, "Content is required");
    }

    $update = "UPDATE course set title = '$title', content = '$content' WHERE id ='$id'";

    if (mysqli_query($conn, $update)) {
        array_push($success, "Update Successful");
        // clear variables
        $course_name = $course_number = "";
    } else {
        array_push($errors, "Error updating: ", mysqli_error($conn));
    }
}

// DELETE
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $query = "DELETE FROM announcement WHERE id='$id'";
    if (mysqli_query($conn, $query)) {
        array_push($success, "Delete successful");
    } else {
        array_push($errors, "Error Deleting " . mysqli_error($conn));
    }
}

$query = "SELECT a.*, u.username, cs.section_name FROM announcement as a
LEFT JOIN users as u
ON a.posted_by_uid = u.user_id
LEFT JOIN course_section as cs
ON a.section_id = cs.section_id
ORDER BY announcement_id DESC";
$results = mysqli_query($conn, $query);

?>

<div class="content-body">
    <?php if (isset($_GET['delete_view']))
        display_success();
    display_error();
    ?>
    <p><b>Announcement</b></p>
    <table>
        <thead>
            <tr>
                <?php isAdmin() ? print '<th>Announcement ID</th>' : ''; ?>
                <th>Title</th>
                <th>Posted by</th>
                <th>Posted on</th>
                <th>Content</th>
                <th>Section Name</th>
                <?php isAdmin() ? print '<th colspan="2">Action</th>' : ''; ?>

            </tr>
        </thead>
        <tbody>
            <?php
            while ($announcements = mysqli_fetch_assoc($results)) {
                $id = $announcements['announcement_id'];
                $title = $announcements['title'];
                $posted_by = $announcements['username'];
                $posted_on = $announcements['posted_on'];
                $content = $announcements['content'];
                $section_name = $announcements['section_name'];
            ?>
                <tr>
                    <?php if (isAdmin()) {
                        echo '<td>' . $id . '</td>';
                    } ?>
                    <td><?php echo $title ?></td>
                    <td><?php echo $posted_by ?></td>
                    <td><?php echo $posted_on ?></td>
                    <td><?php echo $content ?></td>
                    <td><?php echo $section_name ?></td>
                    <?php if (isAdmin()) {
                        echo '<td><a href="?page=announcements&update_view=true&update_id=' . $id . '">Update</a></td>';
                        echo '<td><a href="?page=announcements&delete_view=true&delete_id=' . $id . '">Delete</a></td>';
                    } ?>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>

    <?php if (isAdmin()) { ?>
        <a href="?page=announcements&add_view=true">
            <button>Add Announcement</button>
        </a>
    <?php } ?>

    <?php if (isset($_GET['add_view'])) { ?>
        <hr>
        <div class="form-container">
            <form class="form-body" action="" method="post">
                <?php echo display_success(); ?>
                <?php echo display_error(); ?>
                <div class="form-input">
                    <p><b>Add Announcement</b></p>
                    <label>Title</label>
                    <span><input type="text" name="title"></span>
                </div>
                <div class="form-input">
                    <label>Content </label>
                    <br>
                    <textarea name="content"></textarea>
                </div>
                <div class="form-submit">
                    <input type="submit" name="announcement_add" value="Add">
                </div>
            </form>
        </div>

    <?php } ?>

    <?php if (isset($_GET['update_view'])) { ?>

        <?php
        $id = $_GET['update_id'];
        $query = "SELECT a.*, u.username, cs.section_name FROM announcement as a
        LEFT JOIN users as u
        ON a.posted_by_uid = u.user_id
        LEFT JOIN course_section as cs
        ON a.section_id = cs.section_id
        WHERE id='$id'";
        $results = mysqli_query($conn, $query);

        while ($row = mysqli_fetch_assoc($results)) {
            $id = $row['id'];
            $title = $row['title'];
            $content = $row['content'];
        }
        ?>

        <hr>
        <div class="form-container">
            <form class="form-body" action="" method="post">
                <?php echo display_success(); ?>
                <?php echo display_error(); ?>
                <div class="form-input">
                    <p><b>Update Announcement</b></p>
                    <label>Announcement ID</label>
                    <span><b><?= $id ?></b></span>
                </div>
                <div class="form-input">
                    <label>Title</label>
                    <span><input type="text" name="title" value='<?= $title ?>'></span>
                </div>

                <div class="form-input">
                    <label>Content </label>
                    <br>
                    <textarea name="content"> <?= $content ?></textarea>
                </div>

                <div class="form-submit">
                    <input type="submit" name="update_announcement" value="Update">
                </div>
            </form>
        </div>

    <?php } ?>

</div>