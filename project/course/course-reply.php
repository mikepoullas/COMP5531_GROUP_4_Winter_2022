<?php

$user_id = $_SESSION['user_id'];
$forum_id = $_GET['forum_id'];

// ADD
if (isset($_POST['add_reply'])) {

    // receive all input values from the form
    $content = mysqli_real_escape_string($conn, $_POST['content']);

    // form validation: ensure that the form is correctly filled ...
    // by adding (array_push()) corresponding error unto $errors array
    if (empty($content)) {
        array_push($errors, "Content is required");
    }

    if (count($errors) == 0) {
        $add = "INSERT INTO reply (content, posted_by_uid, posted_on, forum_id)
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
    $content = mysqli_real_escape_string($conn, $_POST['content']);

    // form validation: ensure that the form is correctly filled ...
    // by adding (array_push()) corresponding error unto $errors array
    if (empty($content)) {
        array_push($errors, "Content is required");
    }

    if (count($errors) == 0) {
        $update = "UPDATE reply set content = '$content'
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

    $query = "SELECT f.*, u.* FROM forum as f
                JOIN users as u ON u.user_id = f.posted_by_uid
                WHERE f.forum_id = '$forum_id'
                ORDER BY f.forum_id DESC";
    $forums = mysqli_query($conn, $query);

    $query = "SELECT r.*, f.title, f.forum_id, f.posted_by_uid, u.* FROM reply as r
                JOIN forum as f ON f.forum_id = r.forum_id
                JOIN users as u ON u.user_id = r.posted_by_uid
                WHERE r.forum_id = '$forum_id'
                ORDER BY r.reply_id ASC";
    $replys = mysqli_query($conn, $query);
    ?>

    <?php foreach ($forums as $row) {
        $forum_title = $row['title'];
        $forum_content = $row['content'];
        $forum_posted_by = $row['first_name'] . ' ' . $row['last_name'];
        $forum_posted_on = date_convert($row['posted_on']);
    } ?>

    <h2><?= $forum_title ?></h2>
    <p><?= $forum_content ?></p>
    <p>&emsp;by <b><?= $forum_posted_by ?></b> | <?= $forum_posted_on ?></p>
    <hr>
    <div class="reply-content">

        <?php foreach ($replys as $row) { ?>
            <ul>
                <li><?= $row['content'] ?></li>
                <li>&emsp;by <b><?= $row['first_name'] . ' ' . $row['last_name'] ?></b> | <?= date_convert($row['posted_on']) ?></li>
                <li>
                    &emsp;<a href="?page=course-reply&update_view=true&forum_id=<?= $row['forum_id'] ?>&update_id=<?= $row['reply_id'] ?>">Update</a>
                    |
                    <a href="?page=course-reply&delete_view=true&forum_id=<?= $row['forum_id'] ?>&delete_id=<?= $row['reply_id'] ?>" onclick="return confirm('Are you sure you want to delete?')">Delete</a>
                </li>
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
                        <textarea name="content"><?= $content ?></textarea>
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
                        <textarea name="content"></textarea>
                    </div>
                    <div class="form-submit">
                        <input type="submit" name="add_reply" value="reply">
                    </div>
                </form>
            </div>

        <?php } ?>

    </div>