<?php

$user_id = $_SESSION['user_id'];
$discussion_id = $_GET['discussion_id'];

// ADD
if (isset($_POST['add_comment'])) {

    // receive all input values from the form
    $content = mysqli_real_escape_string($conn, $_POST['comment_content']);

    // form validation: ensure that the form is correctly filled ...
    // by adding (array_push()) corresponding error unto $errors array
    if (empty($content)) {
        array_push($errors, "Content is required");
    }

    if (count($errors) == 0) {
        $add = "INSERT INTO comment (comment_content, posted_by_uid, posted_on, discussion_id)
                VALUES ('$content', '$user_id', NOW(), '$discussion_id')";

        if (mysqli_query($conn, $add)) {
            header('location: ?page=group-comment&discussion_id=' . $discussion_id);
        } else {
            array_push($errors, "Error adding: ", mysqli_error($conn));
        }
    }
}


// UPDATE
if (isset($_POST['update_comment'])) {

    $id = mysqli_real_escape_string($conn, $_GET['update_id']);

    // receive all input values from the form
    $content = mysqli_real_escape_string($conn, $_POST['comment_content']);

    // form validation: ensure that the form is correctly filled ...
    // by adding (array_push()) corresponding error unto $errors array
    if (empty($content)) {
        array_push($errors, "Content is required");
    }

    if (count($errors) == 0) {
        $update = "UPDATE comment set comment_content = '$content'
                    WHERE comment_id ='$id'";

        if (mysqli_query($conn, $update)) {
            header('location: ?page=group-comment&discussion_id=' . $discussion_id);
        } else {
            array_push($errors, "Error updating " . mysqli_error($conn));
        }
    }
}

// DELETE
if (isset($_GET['delete_id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['delete_id']);
    $delete = "DELETE FROM comment WHERE comment_id='$id'";
    if (mysqli_query($conn, $delete)) {
        header('location: ?page=group-comment&discussion_id=' . $discussion_id);
    } else {
        array_push($errors, "Error deleting " . mysqli_error($conn));
    }
}

?>


<div class="content-body">

    <?php

    display_success();
    display_error();

    $query = "SELECT * FROM discussion as d
                JOIN users as u ON u.user_id = d.posted_by_uid
                WHERE d.discussion_id = '$discussion_id'
                ORDER BY discussion_id ASC";
    $discussions = mysqli_query($conn, $query);

    $query = "SELECT c.*, u.*, d.discussion_id FROM comment as c
                JOIN discussion as d ON d.discussion_id = c.discussion_id
                JOIN users as u ON u.user_id = c.posted_by_uid
                WHERE c.discussion_id = '$discussion_id'
                ORDER BY c.comment_id ASC";
    $comments = mysqli_query($conn, $query);
    ?>

    <?php foreach ($discussions as $row) {
        $discussion_title = $row['discussion_title'];
        $discussion_content = $row['discussion_content'];
        $discussion_posted_by = $row['first_name'] . " " . $row['last_name'];
        $discussion_posted_on = date_convert($row['posted_on']);
    } ?>

    <h2><?= $discussion_title ?></h2>
    <p><?= $discussion_content ?></p>
    <p>&emsp;by <b><?= $discussion_posted_by ?></b> | <?= $discussion_posted_on ?></p>
    <hr>
    <div class="comment-content">

        <?php
        foreach ($comments as $row) {
            $comment_id = $row['comment_id'];
            $comment_content = $row['comment_content'];
            $comment_posted_by = $row['first_name'] . " " . $row['last_name'];
            $comment_posted_on = date_convert($row['posted_on']);
            $discussion_id = $row['discussion_id'];
        ?>
            <ul>
                <li><?= $comment_content ?></li>
                <li>&emsp;by <b><?= $comment_posted_by ?></b> | <?= $comment_posted_on ?></li>
                <?php if ($user_id == $row['posted_by_uid']) { ?>
                    <li>
                        &emsp;<a href="?page=group-comment&update_view=true&discussion_id=<?= $discussion_id ?>&update_id=<?= $comment_id ?>">Update</a>
                        |
                        <a href="?page=group-comment&delete_view=true&discussion_id=<?= $discussion_id ?>&delete_id=<?= $comment_id ?>" onclick="return confirm('Are you sure you want to delete?')">Delete</a>
                    </li>
                <?php } ?>
            </ul><br>
        <?php } ?>

        <hr>

        <?php if (isset($_GET['update_view'])) {

            $id = mysqli_real_escape_string($conn, $_GET['update_id']);

            $query = "SELECT * FROM comment as c
                WHERE c.comment_id = '$id'
                ORDER BY c.comment_id ASC";
            $comments = mysqli_query($conn, $query);

            foreach ($comments as $row) {
                $content = $row['content'];
            }
        ?>

            <div class="form-container">
                <form class="form-body" action="" method="POST">
                    <div class="form-input">
                        <label>Comment</label>
                        <br>
                        <textarea name="comment_content"><?= $content ?></textarea>
                    </div>
                    <div class="form-submit">
                        <input type="submit" name="update_comment" value="Update">
                    </div>
                </form>
            </div>


        <?php } else { ?>

            <div class="form-container">
                <form class="form-body" action="" method="POST">
                    <div class="form-input">
                        <label>Comment</label>
                        <br>
                        <textarea name="comment_content"></textarea>
                    </div>
                    <div class="form-submit">
                        <input type="submit" name="add_comment" value="Comment">
                    </div>
                </form>
            </div>

        <?php } ?>

    </div>