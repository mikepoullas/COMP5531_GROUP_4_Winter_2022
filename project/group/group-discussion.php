<script>
    function validateDiscussion() {

        var discussion_title, discussion_content;

        discussion_title = document.getElementById("discussion_title").value;
        discussion_content = document.getElementById("discussion_content").value;

        if (discussion_title == '') {
            alert("Please enter a discussion title.");
            document.getElementById("discussion_title").focus();
            return false;
        } else if (discussion_content == '') {
            alert("Please enter discussion content.");
            document.getElementById("discussion_content").focus();
            return false;
        } else
            return true;
    }
</script>

<?php

$session_user_id = $_SESSION['user_id'];

if (isset($_GET['group_id'])) {
    $session_group_id = $_GET['group_id'];
} else {
    $session_group_id = null;
}

if (isset($_GET['task_id'])) {
    $session_task_id = $_GET['task_id'];
} else {
    $session_task_id = null;
}

// ADD
if (isset($_POST['add_discussion'])) {


    $title = mysqli_real_escape_string($conn, $_POST['discussion_title']);
    $content = mysqli_real_escape_string($conn, $_POST['discussion_content']);



    if (empty($title)) {
        array_push($errors, "Title is required");
    }
    if (empty($content)) {
        array_push($errors, "Content is required");
    }

    if (count($errors) == 0) {
        if ($session_group_id != null) {
            $add = "INSERT INTO discussion (discussion_title, discussion_content, posted_by_uid, posted_on, group_id)
            VALUES('$title', '$content', '$session_user_id', NOW(),'$session_group_id')";

            if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
                $file_id = upload_file('discussion_group');
                $add = "INSERT INTO discussion (discussion_title, discussion_content, posted_by_uid, posted_on, group_id, file_id)
                VALUES('$title', '$content', '$session_user_id', NOW(),'$session_group_id', '$file_id')";
            }
        }
        if ($session_task_id != null) {
            $add = "INSERT INTO discussion (discussion_title, discussion_content, posted_by_uid, posted_on, task_id)
            VALUES('$title', '$content', '$session_user_id', NOW(),'$session_task_id')";

            if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
                $file_id = upload_file('discussion_task');
                $add = "INSERT INTO discussion (discussion_title, discussion_content, posted_by_uid, posted_on, task_id, file_id)
                VALUES('$title', '$content', '$session_user_id', NOW(),'$session_task_id', '$file_id')";
            }
        }

        if (mysqli_query($conn, $add)) {
            array_push($success, "Added successfully");
            if ($session_group_id != '') {
                header("location: ?page=group-discussion&group_id=$session_group_id");
            } elseif ($session_task_id != '') {
                header("location: ?page=group-discussion&task_id=$session_task_id");
            } else {
                array_push($errors, "Seesion variable error!");
            }
        } else {
            array_push($errors, "Error adding: ", mysqli_error($conn));
        }
    }
}

// UPDATE
if (isset($_POST['update_discussion'])) {

    $id = mysqli_real_escape_string($conn, $_GET['update_id']);


    $title = mysqli_real_escape_string($conn, $_POST['discussion_title']);
    $content = mysqli_real_escape_string($conn, $_POST['discussion_content']);



    if (empty($title)) {
        array_push($errors, "Title is required");
    }
    if (empty($content)) {
        array_push($errors, "Content is required");
    }

    if (count($errors) == 0) {

        $update = "UPDATE discussion SET discussion_title = '$title', discussion_content = '$content'
        WHERE discussion_id ='$id'";

        if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
            $file_id = $_GET['update_file'];
            if ($session_group_id != null) {
                if ($file_id != '') {
                    update_file('discussion_group', $file_id);
                } else {
                    $new_file_id = upload_file('discussion_group');
                    $update = "UPDATE discussion SET discussion_title = '$title', discussion_content = '$content', file_id = '$new_file_id'
                    WHERE discussion_id ='$id'";
                }
            }
            if ($session_task_id != null) {
                if ($file_id != '') {
                    update_file('discussion_task', $file_id);
                } else {
                    $new_file_id = upload_file('discussion_task');
                    $update = "UPDATE discussion SET discussion_title = '$title', discussion_content = '$content', file_id = '$new_file_id'
                    WHERE discussion_id ='$id'";
                    echo "check";
                }
            }
        }


        if (mysqli_query($conn, $update)) {
            array_push($success, "Update Successful");
            header("location: ?page=group-discussion&group_id=$session_group_id&task_id=$session_task_id");
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
    $delete = "DELETE FROM discussion WHERE discussion_id='$id'";
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

    $query = "SELECT * FROM discussion as d
    JOIN users as u ON u.user_id = d.posted_by_uid
    LEFT JOIN student_groups as g ON g.group_id = d.group_id
    LEFT JOIN task as t ON t.task_id = d.task_id
    LEFT JOIN group_of_course as gc ON gc.group_id = g.group_id
    LEFT JOIN files as fl ON fl.file_id = d.file_id
    JOIN course as c ON c.course_id = gc.course_id OR c.course_id = t.course_id
    WHERE g.group_id = '$session_group_id' OR t.task_id = '$session_task_id'
    ORDER BY discussion_id ASC";
    $discussions = mysqli_query($conn, $query);

    if (mysqli_num_rows($discussions) > 0) {
        if ($session_group_id != null) {
            $discussion_heading = mysqli_fetch_assoc($discussions)['group_name'];
        }
        if ($session_task_id != null) {
            $discussion_heading = mysqli_fetch_assoc($discussions)['task_content'];
        }
    } else {
        $discussion_heading = "No";
    }

    ?>

    <h2><?= $discussion_heading ?> Discussions</h2>
    <hr>
    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Content</th>
                <th>Posted by</th>
                <th>Posted on</th>
                <th>Course Name</th>
                <th>File Name</th>
                <th colspan="3">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php

            foreach ($discussions as $row) {
                $discussion_id = $row['discussion_id'];
                $title = $row['discussion_title'];
                $content = $row['discussion_content'];
                $posted_by = $row['username'];
                $posted_by_uid = $row['posted_by_uid'];
                $posted_on = date_convert($row['posted_on']);
                $course_name = $row['course_name'];
                $file_id = $row['file_id'];
                $file_name = $row['file_name'];
            ?>
                <tr>
                    <td><b><a href='?page=group-comment&discussion_id=<?= $discussion_id ?>'><?= $title ?></a></b></td>
                    <td><?= $content ?></td>
                    <td><?= $posted_by ?></td>
                    <td><?= $posted_on ?></td>
                    <td><?= $course_name ?></td>
                    <td><a href='?page=group-discussion&group_id=<?= $session_group_id ?>&download_file=<?= $file_id ?>'><?= $file_name ?></a></td>
                    <td><a href='?page=group-comment&discussion_id=<?= $discussion_id ?>'>Comment</a></td>
                    <?php if ($posted_by_uid == $session_user_id) { ?>
                        <td><a href="?page=group-discussion&update_view=true&group_id=<?= $session_group_id ?>&task_id=<?= $session_task_id ?>&update_id=<?= $discussion_id ?>&update_file=<?= $file_id ?>">Update</a></td>
                        <td><a href="?page=group-discussion&group_id=<?= $session_group_id ?>&task_id=<?= $session_task_id ?>&delete_id=<?= $discussion_id ?>&delete_file=<?= $file_id ?>" onclick="return confirm('Are you sure you want to delete?')">Delete</a></td>
                    <?php } ?>

                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>

    <a href="?page=group-discussion&add_view=true&group_id=<?= $session_group_id ?>&task_id=<?= $session_task_id ?>">
        <button>Post Discussion</button>
    </a>

    <?php if (isset($_GET['add_view'])) { ?>
        <hr>
        <div class="form-container">
            <form class="form-body" action="" enctype="multipart/form-data" method="POST" onsubmit="return validateDiscussion()">

                <h3>Post Discussion</h3>

                <div class="form-input">
                    <label>Title</label>
                    <span><input type="text" name="discussion_title" id="discussion_title"></span>
                </div>

                <div class="form-input">
                    <label>Content </label>
                    <br>
                    <textarea name="discussion_content" id="discussion_content"></textarea>
                </div>

                <div class="form-input">
                    <label>Add file <i>(Optional)</i></label>
                    <span><input type="file" name="file" id="file"></span>
                </div>

                <div class="form-submit">
                    <input type="submit" name="add_discussion" value="Post">
                </div>

            </form>
        </div>

    <?php } ?>

    <?php if (isset($_GET['update_view'])) {

        $id = mysqli_real_escape_string($conn, $_GET['update_id']);
        $query = "SELECT d.*, u.username, g.group_name, c.course_name FROM discussion as d
            JOIN users as u ON u.user_id = d.posted_by_uid
            JOIN student_groups as g ON g.group_id = d.group_id
            JOIN group_of_course as gc ON gc.group_id = g.group_id
            JOIN course as c ON c.course_id = gc.course_id
            WHERE d.discussion_id='$id'
            ORDER BY discussion_id ASC";
        $results = mysqli_query($conn, $query);

        foreach ($results as $row) {
            $id = $row['discussion_id'];
            $title = $row['discussion_title'];
            $content = $row['discussion_content'];
            $group_name = $row['group_name'];
            $course_name = $row['course_name'];
        }

    ?>

        <hr>
        <div class="form-container">
            <form class="form-body" action="" enctype="multipart/form-data" method="POST" onsubmit="return validateDiscussion()">

                <h3>Update Discussion</h3>

                <div class="form-input">
                    <label>Title</label>
                    <span><input type="text" name="discussion_title" id="discussion_title" value='<?= $title ?>'></span>
                </div>

                <div class="form-input">
                    <label>Content</label>
                    <br>
                    <textarea name="discussion_content" id="discussion_content"><?= $content ?></textarea>
                </div>

                <div class="form-input">
                    <label>Add file <i>(Optional)</i></label>
                    <span><input type="file" name="file" id="file"></span>
                </div>

                <div class="form-submit">
                    <input type="submit" name="update_discussion" value="Update">
                </div>
            </form>
        </div>

    <?php } ?>

</div>