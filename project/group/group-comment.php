<script>
    function validateComment() {

        var comment;

        comment = document.getElementById("comment").value;

        if (comment == '') {
            alert("Please enter a comment.");
            document.getElementById("comment").focus();
            return false;
        } else
            return true;
    }
</script>

<?php

$session_user_id = $_SESSION['user_id'];
$session_discussion_id = $_GET['discussion_id'];

// ADD
if (isset($_POST['add_comment'])) {

    $content = mysqli_real_escape_string($conn, $_POST['comment_content']);

    if (empty($content)) {
        array_push($errors, "Content is required");
    }

    if (count($errors) == 0) {
        if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
            $file_id = upload_file('comment');
            $add = "INSERT INTO comment (comment_content, posted_by_uid, posted_on, discussion_id, file_id)
            VALUES ('$content', '$session_user_id', NOW(), '$session_discussion_id', '$file_id')";
        } else {
            $add = "INSERT INTO comment (comment_content, posted_by_uid, posted_on, discussion_id)
            VALUES ('$content', '$session_user_id', NOW(), '$session_discussion_id')";
        }
        if (mysqli_query($conn, $add)) {
            header("location: ?page=group-comment&discussion_id=$session_discussion_id");
        } else {
            array_push($errors, "Error adding: ", mysqli_error($conn));
        }
    }
}


// UPDATE
if (isset($_POST['update_comment'])) {

    $id = mysqli_real_escape_string($conn, $_GET['update_id']);
    $content = mysqli_real_escape_string($conn, $_POST['comment_content']);

    if (empty($content)) {
        array_push($errors, "Content is required");
    }

    if (count($errors) == 0) {
        $update = "UPDATE comment SET comment_content = '$content'
        WHERE comment_id ='$id'";

        if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
            $file_id = $_GET['update_file'];
            if ($file_id != '') {
                update_file('comment', $file_id);
            } else {
                $new_file_id = upload_file('comment');
                $update = "UPDATE comment SET comment_content = '$content', file_id = '$new_file_id'
                WHERE comment_id ='$id'";
            }
        }

        if (mysqli_query($conn, $update)) {
            header("location: ?page=group-comment&discussion_id=$session_discussion_id");
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
    $delete = "DELETE FROM comment WHERE comment_id='$id'";
    $file_id = $_GET['delete_file'];
    if (mysqli_query($conn, $delete)) {
        header("location: ?page=group-comment&discussion_id=$session_discussion_id");
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

    $query = "SELECT * FROM discussion as d
    JOIN users as u ON u.user_id = d.posted_by_uid
    LEFT JOIN files as fl ON fl.file_id = d.file_id
    WHERE d.discussion_id = '$session_discussion_id'
    ORDER BY d.discussion_id ASC";
    $discussions = mysqli_query($conn, $query);

    $query = "SELECT c.*, u.*, d.discussion_id, fl.* FROM comment as c
    JOIN discussion as d ON d.discussion_id = c.discussion_id
    LEFT JOIN files as fl ON fl.file_id = c.file_id
    JOIN users as u ON u.user_id = c.posted_by_uid
    WHERE c.discussion_id = '$session_discussion_id'
    ORDER BY c.comment_id ASC";
    $comments = mysqli_query($conn, $query);
    ?>

    <?php foreach ($discussions as $row) {
        $discussion_title = $row['discussion_title'];
        $discussion_content = $row['discussion_content'];
        $discussion_posted_by = $row['first_name'] . " " . $row['last_name'];
        $discussion_posted_on = date_convert($row['posted_on']);
        $discussion_file_id = $row['file_id'];
        $discussion_file_name = $row['file_name'];
    } ?>

    <h2><?= $discussion_title ?></h2>
    <p><?= $discussion_content ?></p>
    <?php if ($discussion_file_id != '') { ?>
        <a href="?page=group-comment&download_file=<?= $discussion_file_id ?>">[ <b><?= $discussion_file_name ?></b> ]</a>
    <?php } ?>
    <p>&emsp;by <b><?= $discussion_posted_by ?></b> | <?= $discussion_posted_on ?></p>
    <hr>
    <div class="comment-content">

        <?php
        if (mysqli_num_rows($comments) > 0) {
            foreach ($comments as $row) {
                $comment_id = $row['comment_id'];
                $comment_content = $row['comment_content'];
                $comment_posted_by = $row['first_name'] . " " . $row['last_name'];
                $comment_posted_on = date_convert($row['posted_on']);
                $comment_file_id = $row['file_id'];
                $comment_file_name = $row['file_name'];
        ?>
                <br>
                <ul>
                    <li><?= $comment_content ?></li>
                    <?php if ($comment_file_id != '') { ?>
                        <a href="?page=group-comment&download_file=<?= $comment_file_id ?>">[ <b><?= $comment_file_name ?></b> ]</a>
                    <?php } ?>
                    <li>&emsp;by <b><?= $comment_posted_by ?></b> | <?= $comment_posted_on ?></li>
                    <?php if ($session_user_id == $row['posted_by_uid']) { ?>
                        <li>
                            &emsp;<a href="?page=group-comment&update_view=true&discussion_id=<?= $session_discussion_id ?>&update_id=<?= $comment_id ?>&update_file=<?= $comment_file_id ?>">Update</a>
                            |
                            <a href="?page=group-comment&discussion_id=<?= $session_discussion_id ?>&delete_id=<?= $comment_id ?>&delete_file=<?= $comment_file_id ?>" onclick="return confirm('Are you sure you want to delete?')">Delete</a>
                        </li>
                    <?php } ?>
                </ul>
                <br>
            <?php } ?>
        <?php } else { ?>
            <p>No Comments</p>
        <?php } ?>

        <hr>

        <?php if (isset($_GET['update_view'])) {

            $id = mysqli_real_escape_string($conn, $_GET['update_id']);

            $query = "SELECT * FROM comment as c
            WHERE c.comment_id = '$id'
            ORDER BY c.comment_id ASC";
            $comments = mysqli_query($conn, $query);

            foreach ($comments as $row) {
                $content = $row['comment_content'];
            }
        ?>

            <div class="form-container">
                <form class="form-body" action="" enctype="multipart/form-data" method="POST" onSubmit="return validateComment()">
                    <div class="form-input">
                        <p>Comment</p>
                        <br>
                        <textarea name="comment_content" id="comment"><?= $content ?></textarea>
                    </div>
                    <div class="form-input">
                        <label>Add file <i>(Optional)</i></label>
                        <span><input type="file" name="file" id="file"></span>
                    </div>
                    <div class="form-submit">
                        <input type="submit" name="update_comment" value="Update">
                    </div>
                </form>
            </div>


        <?php } else { ?>

            <div class="form-container">
                <form class="form-body" action="" enctype="multipart/form-data" method="POST" onSubmit="return validateComment()">
                    <div class="form-input">
                        <p>Comment</p>
                        <br>
                        <textarea name="comment_content" id="comment"></textarea>
                    </div>
                    <div class="form-input">
                        <label>Add file <i>(Optional)</i></label>
                        <span><input type="file" name="file" id="file"></span>
                    </div>
                    <div class="form-submit">
                        <input type="submit" name="add_comment" value="Comment">
                    </div>
                </form>
            </div>

        <?php } ?>

    </div>