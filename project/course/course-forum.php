<?php

$user_id = $_SESSION['user_id'];
$course_id = $_GET['course_id'];

// ADD
if (isset($_POST['add_forum'])) {

    // receive all input values from the form
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);

    // form validation: ensure that the form is correctly filled ...
    // by adding (array_push()) corresponding error unto $errors array
    if (empty($title)) {
        array_push($errors, "Title is required");
    }
    if (empty($content)) {
        array_push($errors, "Content is required");
    }

    if (count($errors) == 0) {
        $add = "INSERT INTO forum (title, content, posted_by_uid, posted_on, course_id)
            VALUES('$title', '$content', '$user_id', NOW(),'$course_id')";

        if (mysqli_query($conn, $add)) {
            array_push($success, "Added successfully");
            header('location: ?page=course-forum&course_id=' . $course_id);
        } else {
            array_push($errors, "Error adding: ", mysqli_error($conn));
        }
    }
}

// UPDATE
if (isset($_POST['update_forum'])) {

    $id = mysqli_real_escape_string($conn, $_GET['update_id']);

    // receive all input values from the form
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    // $course_id = mysqli_real_escape_string($conn, $_POST['course_id']);

    // form validation: ensure that the form is correctly filled ...
    // by adding (array_push()) corresponding error unto $errors array
    if (empty($title)) {
        array_push($errors, "Title is required");
    }
    if (empty($content)) {
        array_push($errors, "Content is required");
    }
    // if (empty($course_id)) {
    //     array_push($errors, "course is required");
    // }

    if (count($errors) == 0) {

        $update = "UPDATE forum set title = '$title', content = '$content'
                    WHERE forum_id ='$id'";

        if (mysqli_query($conn, $update)) {
            array_push($success, "Update Successful");
            header('location: ?page=course-forum&course_id=' . $course_id);
        } else {
            array_push($errors, "Error updating " . mysqli_error($conn));
        }
    }
}

// DELETE
if (isset($_GET['delete_id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['delete_id']);
    $delete = "DELETE FROM forum WHERE forum_id='$id'";
    if (mysqli_query($conn, $delete)) {
        header('location: ?page=course-forum&course_id=' . $course_id);
    } else {
        array_push($errors, "Error deleting " . mysqli_error($conn));
    }
}

?>

<div class="content-body">
    <?php

    if (isset($_GET['delete_view'])) {
        display_success();
        display_error();
    }

    $query = "SELECT f.*, u.username, c.course_name FROM forum as f
                JOIN users as u ON  u.user_id = f.posted_by_uid
                JOIN course as c ON c.course_id = f.course_id
                WHERE c.course_id = $course_id
                ORDER BY f.forum_id DESC";
    $forum = mysqli_query($conn, $query);

    ?>

    <h2><?= mysqli_fetch_assoc($forum)['course_name'] ?> Forum</h2>
    <hr>
    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Content</th>
                <th>Posted by</th>
                <th>Posted on</th>
                <th colspan="2">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php

            foreach ($forum as $row) {
                $forum_id = $row['forum_id'];
                $title = $row['title'];
                $content = $row['content'];
                $posted_by = $row['username'];
                $posted_on = date_convert($row['posted_on']);
            ?>
                <tr>
                    <td><b><a href='?page=course-reply&forum_id=<?= $forum_id ?>'><?= $title ?></a></b></td>
                    <td><?= $content ?></td>
                    <td><?= $posted_by ?></td>
                    <td><?= $posted_on ?></td>
                    <td><a href="?page=course-forum&update_view=true&course_id=<?= $course_id ?>&update_id=<?= $forum_id ?>">Update</a></td>
                    <td><a href="?page=course-forum&delete_view=true&course_id=<?= $course_id ?>&delete_id=<?= $forum_id ?>" onclick="return confirm('Are you sure you want to delete?')">Delete</a></td>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>

    <a href="?page=course-forum&add_view=true&course_id=<?= $course_id ?>">
        <button>Post Forum</button>
    </a>

    <?php if (isset($_GET['add_view'])) { ?>
        <hr>
        <div class="form-container">
            <form class="form-body" action="" method="POST">

                <h3>Post Forum</h3>

                <div class="form-input">
                    <label>Title</label>
                    <span><input type="text" name="title"></span>
                </div>

                <div class="form-input">
                    <label>Content</label>
                    <br>
                    <textarea name="content"></textarea>
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
            $title = $row['title'];
            $content = $row['content'];
            $course_name = $row['course_name'];
        }

    ?>

        <hr>
        <div class="form-container">
            <form class="form-body" action="" method="POST">

                <h3>Update forum</h3>

                <div class="form-input">
                    <label>Title</label>
                    <span><input type="text" name="title" value='<?= $title ?>'></span>
                </div>

                <div class="form-input">
                    <label>Content</label>
                    <br>
                    <textarea name="content"><?= $content ?></textarea>
                </div>

                <div class="form-submit">
                    <input type="submit" name="update_forum" value="Update">
                </div>
            </form>
        </div>

    <?php } ?>

</div>