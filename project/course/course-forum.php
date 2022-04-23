<script>
    function validatePost() {

        var student_id, course_id, section_id;

        forum_title = document.getElementById("forum_title").value;
        forum_content = document.getElementById("forum_content").value;

        if (forum_title == '') {
            alert("Please enter a title.");
            document.getElementById("forum_title").focus();
            return false;
        } else if (forum_content == '') {
            alert("Please enter some content.");
            document.getElementById("forum_content").focus();
            return false;
        } else
            return true;
    }
</script>

<?php

$session_user_id = $_SESSION['user_id'];
$session_course_id = $_GET['course_id'];

// ADD
if (isset($_POST['add_forum'])) {


    $title = mysqli_real_escape_string($conn, $_POST['forum_title']);
    $content = mysqli_real_escape_string($conn, $_POST['forum_content']);



    if (empty($title)) {
        array_push($errors, "Title is required");
    }
    if (empty($content)) {
        array_push($errors, "Content is required");
    }

    if (count($errors) == 0) {

        if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
            $file_id = upload_file('forum');
            $add = "INSERT INTO forum (forum_title, forum_content, posted_by_uid, posted_on, course_id, file_id)
            VALUES('$title', '$content', '$session_user_id', NOW(),'$session_course_id', '$file_id')";
        } else {
            $add = "INSERT INTO forum (forum_title, forum_content, posted_by_uid, posted_on, course_id)
            VALUES('$title', '$content', '$session_user_id', NOW(),'$session_course_id')";
        }

        if (mysqli_query($conn, $add)) {
            array_push($success, "Added successfully");
            header('location: ?page=course-forum&course_id=' . $session_course_id);
        } else {
            array_push($errors, "Error adding: ", mysqli_error($conn));
        }
    }
}

// UPDATE
if (isset($_POST['update_forum'])) {

    $id = mysqli_real_escape_string($conn, $_GET['update_id']);


    $title = mysqli_real_escape_string($conn, $_POST['forum_title']);
    $content = mysqli_real_escape_string($conn, $_POST['forum_content']);



    if (empty($title)) {
        array_push($errors, "Title is required");
    }
    if (empty($content)) {
        array_push($errors, "Content is required");
    }

    if (count($errors) == 0) {

        $update = "UPDATE forum SET forum_title = '$title', forum_content = '$content'
        WHERE forum_id ='$id'";

        if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
            $file_id = $_GET['update_file'];
            if ($file_id != '') {
                update_file('forum', $file_id);
            } else {
                $new_file_id = upload_file('forum');
                $update = "UPDATE forum SET forum_title = '$title', forum_content = '$content', file_id = '$new_file_id'
                WHERE forum_id ='$id'";
            }
        }


        if (mysqli_query($conn, $update)) {
            header("location: ?page=course-forum&course_id=$session_course_id");
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
    $delete = "DELETE FROM forum WHERE forum_id='$id'";
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

    $query = "SELECT f.*, fl.*, u.username, c.course_name FROM forum as f
    JOIN users as u ON  u.user_id = f.posted_by_uid
    JOIN course as c ON c.course_id = f.course_id
    LEFT JOIN files as fl ON fl.file_id = f.file_id
    WHERE c.course_id = '$session_course_id'
    ORDER BY f.forum_id ASC";
    $forum = mysqli_query($conn, $query);

    if (mysqli_num_rows($forum) > 0) {
        $course_name = mysqli_fetch_assoc($forum)['course_name'];
    } else {
        $course_name = "No";
    }

    ?>

    <h2><?= $course_name ?> Forum</h2>
    <hr>
    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Content</th>
                <th>Posted by</th>
                <th>Posted on</th>
                <th>File Name</th>
                <th colspan="3">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php

            foreach ($forum as $row) {
                $forum_id = $row['forum_id'];
                $title = $row['forum_title'];
                $content = $row['forum_content'];
                $posted_by = $row['username'];
                $posted_by_uid = $row['posted_by_uid'];
                $posted_on = date_convert($row['posted_on']);
                $file_id = $row['file_id'];
                $file_name = $row['file_name'];
            ?>
                <tr>
                    <td><b><a href='?page=course-reply&forum_id=<?= $forum_id ?>'><?= $title ?></a></b></td>
                    <td><?= $content ?></td>
                    <td><?= $posted_by ?></td>
                    <td><?= $posted_on ?></td>
                    <td><a href='?page=course-forum&course_id=<?= $session_course_id ?>&download_file=<?= $file_id ?>'><?= $file_name ?></a></td>
                    <td><a href='?page=course-reply&forum_id=<?= $forum_id ?>'>Reply</a></td>
                    <?php if ($posted_by_uid == $session_user_id) { ?>
                        <td><a href="?page=course-forum&update_view=true&course_id=<?= $session_course_id ?>&update_id=<?= $forum_id ?>&update_file=<?= $file_id ?>">Update</a></td>
                        <td><a href="?page=course-forum&course_id=<?= $session_course_id ?>&delete_id=<?= $forum_id ?>&delete_file=<?= $file_id ?>" onclick="return confirm('Are you sure you want to delete?')">Delete</a></td>
                    <?php } ?>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>

    <a href="?page=course-forum&add_view=true&course_id=<?= $session_course_id ?>">
        <button>Post Forum</button>
    </a>

    <?php if (isset($_GET['add_view'])) { ?>
        <hr>
        <div class="form-container">
            <form class="form-body" action="" enctype="multipart/form-data" method="POST" onSubmit="return validatePost()">

                <h3>Post Forum</h3>

                <div class="form-input">
                    <label>Title</label>
                    <span><input type="text" name="forum_title" id="forum_title"></span>
                </div>

                <div class="form-input">
                    <label>Content</label>
                    <br>
                    <textarea name="forum_content" id="forum_content"></textarea>
                </div>

                <div class="form-input">
                    <label>Add file <i>(Optional)</i></label>
                    <span><input type="file" name="file" id="file"></span>
                </div>

                <div class="form-submit">
                    <input type="submit" name="add_forum" value="Post">
                </div>

            </form>
        </div>

    <?php } ?>

    <?php if (isset($_GET['update_view'])) {

        $id = mysqli_real_escape_string($conn, $_GET['update_id']);
        $query = "SELECT f.*, u.username, c.course_name FROM forum as f
            JOIN users as u ON u.user_id = f.posted_by_uid
            JOIN course as c ON c.course_id = f.course_id
            WHERE f.forum_id='$id'
            ORDER BY forum_id ASC";
        $results = mysqli_query($conn, $query);

        foreach ($results as $row) {
            $id = $row['forum_id'];
            $title = $row['forum_title'];
            $content = $row['forum_content'];
            $course_name = $row['course_name'];
        }

    ?>

        <hr>
        <div class="form-container">
            <form class="form-body" action="" enctype="multipart/form-data" method="POST" onSubmit="return validatePost()">

                <h3>Update forum</h3>

                <div class="form-input">
                    <label>Title</label>
                    <span><input type="text" name="forum_title" id="forum_title" value='<?= $title ?>'></span>
                </div>

                <div class="form-input">
                    <label>Content</label>
                    <br>
                    <textarea name="forum_content" id="forum_content"><?= $content ?></textarea>
                </div>

                <div class=" form-input">
                    <label>Add file <i>(Optional)</i></label>
                    <span><input type="file" name="file" id="file"></span>
                </div>

                <div class="form-submit">
                    <input type="submit" name="update_forum" value="Update">
                </div>
            </form>
        </div>

    <?php } ?>

</div>