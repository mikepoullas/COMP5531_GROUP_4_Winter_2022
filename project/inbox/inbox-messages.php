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
$session_receiver_id = $_GET['receiver_id'];

$receiver_info = mysqli_fetch_assoc(get_records_where('users', 'user_id', $session_receiver_id));
$receiver_name = $receiver_info['first_name'] . ' ' . $receiver_info['last_name'];

// ADD
if (isset($_POST['send_message'])) {

    $content = mysqli_real_escape_string($conn, $_POST['message_content']);

    if (empty($content)) {
        array_push($errors, "Message is required");
    }

    if (count($errors) == 0) {
        if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
            $file_id = upload_file('message');
            $add = "INSERT INTO messages (message_content, message_date, sender_user_id, receiver_user_id, file_id)
            VALUES ('$content', NOW(), '$session_user_id', '$session_receiver_id', '$file_id')";
        } else {
            $add = "INSERT INTO messages (message_content, message_date, sender_user_id, receiver_user_id)
            VALUES ('$content', NOW(), '$session_user_id', '$session_receiver_id')";
        }
        if (mysqli_query($conn, $add)) {
            header("location: ?page=inbox-messages&sender_id=$session_user_id&receiver_id=$session_receiver_id");
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
    $delete = "DELETE FROM messages WHERE message_id='$id'";
    $file_id = $_GET['delete_file'];
    if (mysqli_query($conn, $delete)) {
        header("location: ?page=inbox-messages&sender_id=$session_user_id&receiver_id=$session_receiver_id");
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

    $query = "(SELECT * FROM messages as m1
    JOIN users as u ON m1.sender_user_id = u.user_id
    LEFT JOIN files as f ON m1.file_id = f.file_id
    WHERE u.user_id != $session_user_id
    AND (m1.sender_user_id = $session_user_id OR m1.receiver_user_id = $session_user_id)
    ORDER BY m1.message_id ASC)

    UNION

    (SELECT * FROM messages as m2
    JOIN users as u ON m2.receiver_user_id = u.user_id
    LEFT JOIN files as f ON m2.file_id = f.file_id
    WHERE u.user_id != $session_user_id
    AND (m2.sender_user_id = $session_user_id OR m2.receiver_user_id = $session_user_id)
    ORDER BY m2.message_id ASC)";

    $messages = mysqli_query($conn, $query);
    ?>

    <h2><?= $receiver_name ?></h2>
    <hr>
    <div class="message-content">
        <?php
        if (mysqli_num_rows($messages) > 0) {
            foreach ($messages as $row) {
                $message_id = $row['message_id'];
                $message_content = $row['message_content'];
                $message_by = $row['first_name'] . " " . $row['last_name'];
                $message_on = date_convert($row['message_date']);
                $message_file_id = $row['file_id'];
                $message_file_name = $row['file_name'];
        ?>
                <br>
                <ul>
                    <li><?= $message_content ?></li>
                    <?php if ($message_file_id != '') { ?>
                        <a href="?page=inbox-messages&download_file=<?= $message_file_id ?>">[ <b><?= $message_file_name ?></b> ]</a>
                    <?php } ?>
                    <li>&emsp;by <b><?= $message_by ?></b> | <?= $message_on ?></li>
                    <?php if ($session_user_id == $row['sender_user_id']) { ?>
                        <li>
                            &emsp;<a href="?page=inbox-messages&update_view=true$sender_id=<?= $session_user_id ?>&receiver_id=<?= $session_receiver_id ?>&update_id=<?= $message_id ?>&update_file=<?= $message_file_id ?>">Update</a>
                            |
                            <a href="?page=inbox-messages&sender_id=<?= $session_user_id ?>&receiver_id=<?= $session_receiver_id ?>&delete_id=<?= $message_id ?>&delete_file=<?= $message_file_id ?>" onclick="return confirm('Are you sure you want to delete?')">Delete</a>
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
                        <textarea name="message_content" id="message"></textarea>
                    </div>
                    <div class="form-input">
                        <label>Attach file <i>(Optional)</i></label>
                        <span><input type="file" name="file" id="file"></span>
                    </div>
                    <div class="form-submit">
                        <input type="submit" name="send_message" value="Send">
                    </div>
                </form>
            </div>

        <?php } ?>

    </div>