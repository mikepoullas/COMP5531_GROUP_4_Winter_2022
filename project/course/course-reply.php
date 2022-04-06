<?php

$user_id = $_SESSION['user_id'];
$forum_id = $_GET['forum_id'];

// ADD
if (isset($_POST['add_reply'])) {

    // receive all input values from the form
    $content = mysqli_real_escape_string($conn, $_POST['reply_content']);

    // form validation: ensure that the form is correctly filled ...
    // by adding (array_push()) corresponding error unto $errors array
    if (empty($content)) {
        array_push($errors, "Content is required");
    }

    if (count($errors) == 0) {
        $add = "INSERT INTO reply (reply_content, posted_by_uid, posted_on, forum_id)
                VALUES ('$content', '$user_id', NOW(), '$forum_id')";

        if (mysqli_query($conn, $add)) {
            header('location: ?page=course-reply&forum_id=' . $forum_id);
        } else {
            array_push($errors, "Error adding: ", mysqli_error($conn));
        }
    }
}


// UPDATE
if (isset($_POST['update_reply'])) {

    $id = mysqli_real_escape_string($conn, $_GET['update_id']);

    // receive all input values from the form
    $content = mysqli_real_escape_string($conn, $_POST['reply_content']);

    // form validation: ensure that the form is correctly filled ...
    // by adding (array_push()) corresponding error unto $errors array
    if (empty($content)) {
        array_push($errors, "Content is required");
    }

    if (count($errors) == 0) {
        $update = "UPDATE reply set reply_content = '$content'
        WHERE reply_id ='$id'";

        if (mysqli_query($conn, $update)) {
            header('location: ?page=course-reply&forum_id=' . $forum_id);
        } else {
            array_push($errors, "Error updating " . mysqli_error($conn));
        }
    }
}

// DELETE
if (isset($_GET['delete_id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['delete_id']);
    $delete = "DELETE FROM reply WHERE reply_id='$id'";
    if (mysqli_query($conn, $delete)) {
        header('location: ?page=course-reply&forum_id=' . $forum_id);
    } else {
        array_push($errors, "Error deleting " . mysqli_error($conn));
    }
}

?>


<div class="content-body">

    <?php

    display_success();
    display_error();

    $query = "SELECT * FROM forum as f
    JOIN users as u ON u.user_id = f.posted_by_uid
    WHERE f.forum_id = '$forum_id'
    ORDER BY f.forum_id ASC";
    $forums = mysqli_query($conn, $query);

    $query = "SELECT r.*,u.* FROM reply as r
    JOIN forum as f ON f.forum_id = r.forum_id
    JOIN users as u ON u.user_id = r.posted_by_uid
    WHERE r.forum_id = '$forum_id'
    ORDER BY r.reply_id ASC";
    $replys = mysqli_query($conn, $query);
    ?>

    <?php foreach ($forums as $row) {
        $forum_title = $row['forum_title'];
        $forum_content = $row['forum_content'];
        $forum_posted_by = $row['first_name'] . ' ' . $row['last_name'];
        $forum_posted_on = date_convert($row['posted_on']);
    } ?>

    <h2><?= $forum_title ?></h2>
    <p><?= $forum_content ?></p>
    <p>&emsp;by <b><?= $forum_posted_by ?></b> | <?= $forum_posted_on ?></p>
    <hr>
    <div class="reply-content">

        <?php
        foreach ($replys as $row) {
            $reply_id = $row['reply_id'];
            $reply_content = $row['reply_content'];
            $reply_posted_by = $row['first_name'] . " " . $row['last_name'];
            $reply_posted_on = date_convert($row['posted_on']);
            $forum_id = $row['forum_id'];

        ?>
            <ul>
                <li><?= $reply_content ?></li>
                <li>&emsp;by <b><?= $reply_posted_by ?></b> | <?= $reply_posted_on ?></li>
                <?php if ($user_id == $row['posted_by_uid']) { ?>
                    <li>
                        &emsp;<a href="?page=course-reply&update_view=true&forum_id=<?= $forum_id ?>&update_id=<?= $reply_id ?>">Update</a>
                        |
                        <a href="?page=course-reply&delete_view=true&forum_id=<?= $forum_id ?>&delete_id=<?= $reply_id ?>" onclick="return confirm('Are you sure you want to delete?')">Delete</a>
                    </li>
                <?php } ?>
            </ul><br>
        <?php } ?>

        <hr>

        <?php if (isset($_GET['update_view'])) {

            $id = mysqli_real_escape_string($conn, $_GET['update_id']);

            $query = "SELECT * FROM reply as c
    WHERE c.reply_id = '$id'
    ORDER BY c.reply_id ASC";
            $replys = mysqli_query($conn, $query);

            foreach ($replys as $row) {
                $content = $row['content'];
            }
        ?>

            <div class="form-container">
                <form class="form-body" action="" method="POST">
                    <div class="form-input">
                        <label>Reply</label>
                        <br>
                        <textarea name="reply_content"><?= $content ?></textarea>
                    </div>
                    <div class="form-submit">
                        <input type="submit" name="update_reply" value="Update">
                    </div>
                </form>
            </div>


        <?php } else { ?>

            <div class="form-container">
                <form class="form-body" action="" method="POST">
                    <div class="form-input">
                        <label>Reply</label>
                        <br>
                        <textarea name="reply_content"></textarea>
                    </div>
                    <div class="form-submit">
                        <input type="submit" name="add_reply" value="reply">
                    </div>
                </form>
            </div>

        <?php } ?>

    </div>