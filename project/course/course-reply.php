<script>
    function validateReply() {

        var reply;

        reply = document.getElementById("reply").value;

        if (reply == '') {
            alert("Please enter a reply.");
            document.getElementById("reply").focus();
            return false;
        } else
            return true;
    }
</script>

<?php

$session_user_id = $_SESSION['user_id'];
$session_forum_id = $_GET['forum_id'];

// ADD
if (isset($_POST['add_reply'])) {

    $content = mysqli_real_escape_string($conn, $_POST['reply_content']);

    if (empty($content)) {
        array_push($errors, "Content is required");
    }

    if (count($errors) == 0) {
        if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
            $file_id = upload_file('reply');
            $add = "INSERT INTO reply (reply_content, posted_by_uid, posted_on, forum_id, file_id)
            VALUES ('$content', '$session_user_id', NOW(), '$session_forum_id', '$file_id')";
        } else {
            $add = "INSERT INTO reply (reply_content, posted_by_uid, posted_on, forum_id)
            VALUES ('$content', '$session_user_id', NOW(), '$session_forum_id')";
        }
        if (mysqli_query($conn, $add)) {
            header("location: ?page=course-reply&forum_id=$session_forum_id");
        } else {
            array_push($errors, "Error adding: ", mysqli_error($conn));
        }
    }
}


// UPDATE
if (isset($_POST['update_reply'])) {

    $id = mysqli_real_escape_string($conn, $_GET['update_id']);
    $content = mysqli_real_escape_string($conn, $_POST['reply_content']);

    if (empty($content)) {
        array_push($errors, "Content is required");
    }

    if (count($errors) == 0) {

        $update = "UPDATE reply SET reply_content = '$content'
        WHERE reply_id ='$id'";

        if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
            $file_id = $_GET['update_file'];
            if ($file_id != '') {
                update_file('reply', $file_id);
            } else {
                $new_file_id = upload_file('reply');
                $update = "UPDATE reply SET reply_content = '$content', file_id = '$new_file_id'
                WHERE reply_id ='$id'";
            }
        }

        if (mysqli_query($conn, $update)) {
            header("location: ?page=course-reply&forum_id=$session_forum_id");
        } else {
            array_push($errors, "Error updating " . mysqli_error($conn));
        }
    }
}

//DOWNLOAD
if (isset($_GET['download_file'])) {
    download_file($_GET['download_file']);
}

// DELETE
if (isset($_GET['delete_id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['delete_id']);
    $delete = "DELETE FROM reply WHERE reply_id='$id'";
    $file_id = $_GET['delete_file'];
    if (mysqli_query($conn, $delete)) {
        header("location: ?page=course-reply&forum_id=$session_forum_id");
        delete_file($file_id);
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
    LEFT JOIN files as fl ON fl.file_id = f.file_id
    WHERE f.forum_id = '$session_forum_id'
    ORDER BY f.forum_id ASC";
    $forums = mysqli_query($conn, $query);

    $query = "SELECT r.*, u.*, fl.* FROM reply as r
    JOIN forum as f ON f.forum_id = r.forum_id
    LEFT JOIN files as fl ON fl.file_id = r.file_id
    JOIN users as u ON u.user_id = r.posted_by_uid
    WHERE r.forum_id = '$session_forum_id'
    ORDER BY r.reply_id ASC";
    $replys = mysqli_query($conn, $query);
    ?>

    <?php foreach ($forums as $row) {
        $forum_title = $row['forum_title'];
        $forum_content = $row['forum_content'];
        $forum_posted_by = $row['first_name'] . ' ' . $row['last_name'];
        $forum_posted_on = date_convert($row['posted_on']);
        $forum_file_id = $row['file_id'];
        $forum_file_name = $row['file_name'];
    } ?>

    <h2><?= $forum_title ?></h2>
    <p><?= $forum_content ?></p>
    <?php if ($forum_file_id != '') { ?>
        <a href="?page=course-reply&download_file=<?= $forum_file_id ?>">[ <b><?= $forum_file_name ?></b> ]</a>
        <!-- &emsp;<button type="submit" onclick="window.location.href='?page=course-forum&download_file=<?= $forum_file_id ?>'"><?= $forum_file_name ?></button> -->
    <?php } ?>
    <p>&emsp;by <b><?= $forum_posted_by ?></b> | <?= $forum_posted_on ?></p>

    <hr>
    <div class="reply-content">

        <?php
        if (mysqli_num_rows($replys) > 0) {
            foreach ($replys as $row) {
                $reply_id = $row['reply_id'];
                $reply_content = $row['reply_content'];
                $reply_posted_by = $row['first_name'] . " " . $row['last_name'];
                $reply_posted_on = date_convert($row['posted_on']);
                $reply_file_id = $row['file_id'];
                $reply_file_name = $row['file_name'];
        ?>
                <br>
                <ul>
                    <li><?= $reply_content ?></li>
                    <?php if ($reply_file_id != '') { ?>
                        <a href="?page=course-reply&download_file=<?= $reply_file_id ?>">[ <b><?= $reply_file_name ?></b> ]</a>
                    <?php } ?>
                    <li>&emsp;by <b><?= $reply_posted_by ?></b> | <?= $reply_posted_on ?></li>
                    <?php if ($session_user_id == $row['posted_by_uid']) { ?>
                        <li>
                            &emsp;<a href="?page=course-reply&update_view=true&forum_id=<?= $session_forum_id ?>&update_id=<?= $reply_id ?>&update_file=<?= $reply_file_id ?>">Update</a>
                            |
                            <a href="?page=course-reply&forum_id=<?= $session_forum_id ?>&delete_id=<?= $reply_id ?>&delete_file=<?= $reply_file_id ?>" onclick="return confirm('Are you sure you want to delete?')">Delete</a>
                        </li>
                    <?php } ?>
                </ul>
                <br>
            <?php } ?>
        <?php } else { ?>
            <p>No Replys</p>
        <?php } ?>

        <hr>

        <?php if (isset($_GET['update_view'])) {

            $id = mysqli_real_escape_string($conn, $_GET['update_id']);

            $query = "SELECT * FROM reply as c
            WHERE c.reply_id = '$id'
            ORDER BY c.reply_id ASC";
            $replys = mysqli_query($conn, $query);

            foreach ($replys as $row) {
                $content = $row['reply_content'];
            }

            var_dump($_GET['update_file']);
        ?>

            <div class="form-container">
                <form class="form-body" action="" enctype="multipart/form-data" method="POST" onSubmit="return validateReply()">
                    <div class="form-input">
                        <p>Reply</p>
                        <br>
                        <textarea name="reply_content" id="reply"><?= $content ?></textarea>
                    </div>
                    <div class="form-input">
                        <label>Add file <i>(Optional)</i></label>
                        <span><input type="file" name="file" id="file"></span>
                    </div>
                    <div class="form-submit">
                        <input type="submit" name="update_reply" value="Update">
                    </div>
                </form>
            </div>


        <?php } else { ?>

            <div class="form-container">
                <form class="form-body" action="" enctype="multipart/form-data" method="POST" onSubmit="return validateReply()">
                    <div class="form-input">
                        <p>Reply</p>
                        <br>
                        <textarea name="reply_content" id="reply"></textarea>
                    </div>
                    <div class="form-input">
                        <label>Add file <i>(Optional)</i></label>
                        <span><input type="file" name="file" id="file"></span>
                    </div>
                    <div class="form-submit">
                        <input type="submit" name="add_reply" value="Reply">
                    </div>
                </form>
            </div>

        <?php } ?>

    </div>