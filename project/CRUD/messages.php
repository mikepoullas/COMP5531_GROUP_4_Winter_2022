<?php

$session_user_id = $_SESSION['user_id'];

//DOWNLOAD
if (isset($_GET['download_file'])) {
    download_file($_GET['download_file']);
}

// DELETE
if (isset($_GET['delete_id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['delete_id']);
    $delete = "DELETE FROM messages WHERE message_id='$id'";
    $file_id = $_GET['file_id'];
    if (mysqli_query($conn, $delete)) {
        delete_file($file_id);
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

    $query = "SELECT m.*, f.* FROM messages as m
    JOIN users as ur ON m.receiver_user_id = ur.user_id
    JOIN users as us ON m.sender_user_id = us.user_id
    LEFT JOIN files as f ON m.file_id = f.file_id
    ORDER BY m.message_id ASC";
    $messages = mysqli_query($conn, $query);

    ?>
    <h2>Messages</h2>
    <hr>
    <table>
        <thead>
            <tr>
                <th>Content</th>
                <th>Sender</th>
                <th>Receiver</th>
                <th>Message date</th>
                <th>Files</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($messages as $row) {
                $id = $row['message_id'];
                $content = $row['message_content'];

                $sender_user_id = $row['sender_user_id'];
                $sender_info = mysqli_fetch_assoc(get_records_where('users', 'user_id', $sender_user_id));
                $sender_name = $sender_info['first_name'] . " " . $sender_info['last_name'];
                $receiver_user_id = $row['receiver_user_id'];
                $receiver_info = mysqli_fetch_assoc(get_records_where('users', 'user_id', $receiver_user_id));
                $receiver_name = $receiver_info['first_name'] . " " . $receiver_info['last_name'];

                $posted_on = date_convert($row['message_date']);
                $file_id = $row['file_id'];
                $file_name = $row['file_name'];
            ?>
                <tr>
                    <td><?= $content ?></td>
                    <td><?= $sender_name ?></td>
                    <td><?= $receiver_name ?></td>
                    <td><?= $posted_on ?></td>
                    <td><a href='?page=messages&download_file=<?= $file_id ?>'><?= $file_name ?></a></td>
                    <td><a href="?page=messages&delete_id=<?= $id ?>&file_id=<?= $file_id ?>" onclick="return confirm('Are you sure you want to delete?')">Delete Message</a></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

</div>