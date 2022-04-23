<script>
    function validateMessage() {

        var message;

        message = document.getElementById("message").value;

        if (message == '') {
            alert("Please enter a message.");
            document.getElementById("message").focus();
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
            header("location: ?page=inbox-messages&receiver_id=$session_receiver_id");
        } else {
            array_push($errors, "Error adding: ", mysqli_error($conn));
        }
    }
}


// UPDATE
if (isset($_POST['update_message'])) {

    $id = mysqli_real_escape_string($conn, $_GET['update_id']);
    $content = mysqli_real_escape_string($conn, $_POST['message_content']);

    if (empty($content)) {
        array_push($errors, "Content is required");
    }

    if (count($errors) == 0) {
        $update = "UPDATE messages SET message_content = '$content'
        WHERE message_id ='$id'";

        if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
            $file_id = $_GET['update_file'];
            if ($file_id != '') {
                update_file('message', $file_id);
            } else {
                $new_file_id = upload_file('message');
                $update = "UPDATE messages SET message_content = '$content', file_id = '$new_file_id'
                WHERE message_id ='$id'";
            }
        }

        if (mysqli_query($conn, $update)) {
            header("location: ?page=inbox-messages&receiver_id=$session_receiver_id");
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

    $query = "SELECT * FROM messages as m
    JOIN users as ur ON m.receiver_user_id = ur.user_id
    JOIN users as us ON m.sender_user_id = us.user_id
    LEFT JOIN files as f ON m.file_id = f.file_id
    WHERE (us.user_id = $session_user_id AND ur.user_id = $session_receiver_id)
    OR (us.user_id = $session_receiver_id AND ur.user_id = $session_user_id)
    ORDER BY m.message_id ASC";

    $messages = mysqli_query($conn, $query);
    ?>

    <h2><?= $receiver_name ?></h2>
    <hr>
    <div class="list-content">
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
                    <li>&emsp;<b><?= $message_by ?></b> | <?= $message_on ?></li>
                    <?php if ($session_user_id == $row['sender_user_id']) { ?>
                        <li>
                            &emsp;<a href="?page=inbox-messages&update_view=true&receiver_id=<?= $session_receiver_id ?>&update_id=<?= $message_id ?>&update_file=<?= $message_file_id ?>">Update</a>
                            |
                            <a href="?page=inbox-messages&receiver_id=<?= $session_receiver_id ?>&delete_id=<?= $message_id ?>&delete_file=<?= $message_file_id ?>" onclick="return confirm('Are you sure you want to delete?')">Delete</a>
                        </li>
                    <?php } ?>
                </ul>
                <br>
            <?php } ?>
        <?php } else { ?>
            <p>No Messages</p>
        <?php } ?>
    </div>

    <hr>

    <?php if (isset($_GET['update_view'])) {
        $id = mysqli_real_escape_string($conn, $_GET['update_id']);
        $query = "SELECT * FROM messages as m
            WHERE m.message_id = '$id'
            ORDER BY m.message_id ASC";
        $messages = mysqli_query($conn, $query);

        foreach ($messages as $row) {
            $content = $row['message_content'];
        }
    ?>
        <div class="form-container">
            <form class="form-body" action="" enctype="multipart/form-data" method="POST" onSubmit="return validateMessage()">
                <div class="form-input">
                    <p>Update Message</p>
                    <br>
                    <textarea name="message_content" id="message"><?= $content ?></textarea>
                </div>
                <div class="form-input">
                    <label>Add file <i>(Optional)</i></label>
                    <span><input type="file" name="file" id="file"></span>
                </div>
                <div class="form-submit">
                    <input type="submit" name="update_message" value="Update">
                </div>
            </form>
        </div>


    <?php } else { ?>

        <div class="form-container">
            <form class="form-body" action="" enctype="multipart/form-data" method="POST" onSubmit="return validateMessage()">
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