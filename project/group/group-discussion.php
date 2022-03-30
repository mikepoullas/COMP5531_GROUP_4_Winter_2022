<?php

// initializing variables
$id = $title = $content = $posted_by = $posted_on = $group_id = $group_name = $course_id = "";

$user_id = $_SESSION['user_id'];
$group_id = $_GET['group_id'];

// ADD
if (isset($_POST['add_discussion'])) {

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
        $add = "INSERT INTO discussion (title, content, posted_by_uid, posted_on, group_id)
            VALUES('$title', '$content', '$user_id', NOW(),'$group_id')";

        if (mysqli_query($conn, $add)) {
            array_push($success, "Added successfully");
            header('location: ?page=group-discussion&group_id=' . $group_id);
        } else {
            array_push($errors, "Error adding: ", mysqli_error($conn));
        }
    }
}

// UPDATE
if (isset($_POST['discussion_update'])) {

    $id = mysqli_real_escape_string($conn, $_GET['update_id']);

    // receive all input values from the form
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    // $group_id = mysqli_real_escape_string($conn, $_POST['group_id']);

    // form validation: ensure that the form is correctly filled ...
    // by adding (array_push()) corresponding error unto $errors array
    if (empty($title)) {
        array_push($errors, "Title is required");
    }
    if (empty($content)) {
        array_push($errors, "Content is required");
    }
    // if (empty($group_id)) {
    //     array_push($errors, "Group is required");
    // }

    if (count($errors) == 0) {

        $update = "UPDATE discussion set title = '$title', content = '$content'
                    WHERE discussion_id ='$id'";

        if (mysqli_query($conn, $update)) {
            array_push($success, "Update Successful");
            header('location: ?page=group-discussion&group_id=' . $group_id);
        } else {
            array_push($errors, "Error updating " . mysqli_error($conn));
        }
    }
}

// DELETE
if (isset($_GET['delete_id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['delete_id']);
    $delete = "DELETE FROM discussion WHERE discussion_id='$id'";
    if (mysqli_query($conn, $delete)) {
        header('location: ?page=group-discussion&group_id=' . $group_id);
    } else {
        array_push($errors, "Error deleting " . mysqli_error($conn));
    }
}

?>

<div class="content-body">
    <?php

    display_success();
    display_error();

    $query = "SELECT d.*, u.username, g.group_name, c.course_name FROM discussion as d
    JOIN users as u ON d.posted_by_uid = u.user_id
    JOIN student_group as g ON g.group_id = d.group_id
    JOIN group_of_course as gc ON gc.group_id = g.group_id
    JOIN course as c ON c.course_id = gc.course_id
    WHERE g.group_id = '$group_id'
    ORDER BY discussion_id DESC";
    $discussions = mysqli_query($conn, $query);

    ?>

    <h2><?= mysqli_fetch_assoc($discussions)['group_name'] ?> Discussions</h2>
    <hr>
    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Content</th>
                <th>Posted by</th>
                <th>Posted on</th>
                <th>Course Name</th>
                <th colspan="2">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php

            foreach ($discussions as $row) {
                $discussion_id = $row['discussion_id'];
                $title = $row['title'];
                $content = $row['content'];
                $posted_by = $row['username'];
                $posted_on = date_convert($row['posted_on']);
                $group_id = $row['group_id'];
                $course_name = $row['course_name'];
            ?>
                <tr>
                    <td><b><a href='?page=group-comment&discussion_id=<?= $discussion_id ?>'><?= $title ?></a></b></td>
                    <td><?= $content ?></td>
                    <td><?= $posted_by ?></td>
                    <td><?= $posted_on ?></td>
                    <td><?= $course_name ?></td>
                    <td><a href="?page=group-discussion&update_view=true&group_id=<?= $group_id ?>&update_id=<?= $discussion_id ?>">Update</a></td>
                    <td><a href="?page=group-discussion&delete_view=true&group_id=<?= $group_id ?>&delete_id=<?= $discussion_id ?>">Delete</a></td>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>

    <a href="?page=group-discussion&add_view=true&group_id=<?= $group_id ?>">
        <button>Post Discussion</button>
    </a>

    <?php if (isset($_GET['add_view'])) { ?>
        <hr>
        <div class="form-container">
            <form class="form-body" action="" method="POST">

                <h3>Post Discussion</h3>

                <div class="form-input">
                    <label>Title</label>
                    <span><input type="text" name="title"></span>
                </div>

                <div class="form-input">
                    <label>Content </label>
                    <br>
                    <textarea name="content"></textarea>
                </div>

                <!-- <div class="form-input">
                        <label for="course_id">For Course</label>
                        <span>
                            <select name="course_id" onchange=showData(this)>

                                <option value="" selected hidden>Choose a Course</option>
                                <?php
                                // $courses = get_table_array('course');
                                // foreach ($courses as $row) {
                                //     $course_id = $row['course_id'];
                                //     $course_name = $row['course_name'];
                                //     echo "<option name=course_id id=course_id value='$course_id'>$course_name</option>";
                                // }
                                ?>
                            </select>
                        </span>
                    </div> -->

                <!-- <div class=" form-input">
                    <label for="group_id">For Group</label>
                    <span>
                        <select name="group_id">
                            <option value="" selected hidden>Choose a Group</option>
                            <?php

                            // $query = "SELECT * FROM student_group as g
                            //                 JOIN group_of_course as gc ON gc.group_id = g.group_id
                            //                 JOIN course as c ON c.course_id = gc.course_id";
                            // $groups = mysqli_query($conn, $query);

                            // foreach ($groups as $row) {
                            //     $group_id = $row['group_id'];
                            //     $group_name = $row['group_name'];
                            //     $course_name = $row['course_name'];
                            //     echo "<option name=group_id id=group_id value='$group_id'>$group_name - $course_name</option>";
                            // }
                            ?>
                        </select>
                    </span>
                </div> -->

                <div class="form-submit">
                    <input type="submit" name="add_discussion" value="Post">
                </div>

            </form>
        </div>

    <?php } ?>

    <?php if (isset($_GET['update_view'])) {

        $id = mysqli_real_escape_string($conn, $_GET['update_id']);
        $query = "SELECT d.*, u.username, g.group_name, c.course_name FROM discussion as d
                        JOIN users as u ON d.posted_by_uid = u.user_id
                        JOIN student_group as g ON g.group_id = d.group_id
                        JOIN group_of_course as gc ON gc.group_id = g.group_id
                        JOIN course as c ON c.course_id = gc.course_id
                        WHERE d.discussion_id='$id'
                        ORDER BY discussion_id ASC";
        $results = mysqli_query($conn, $query);

        foreach ($results as $row) {
            $id = $row['discussion_id'];
            $title = $row['title'];
            $content = $row['content'];
            $group_name = $row['group_name'];
            $course_name = $row['course_name'];
            // $update_group_id = $row['group_id'];
        }

    ?>

        <hr>
        <div class="form-container">
            <form class="form-body" action="" method="POST">

                <h3>Update Discussion</h3>

                <!-- <div class="form-input">
                    <label>Group Name</label>
                    <span><?= $group_name ?></span>
                    <label>Course Name</label>
                    <span><?= $course_name ?></span>
                </div> -->

                <div class="form-input">
                    <label>Title</label>
                    <span><input type="text" name="title" value='<?= $title ?>'></span>
                </div>

                <div class="form-input">
                    <label>Content</label>
                    <br>
                    <textarea name="content"><?= $content ?></textarea>
                </div>

                <!-- <div class="form-input">
                        <label for="course">For Group</label>
                        <span>
                            <select name="group_id">
                                <?php
                                // $groups = get_table_array('student_group');
                                // foreach ($groups as $row) {
                                //     $group_id = $row['group_id'];
                                //     $group_name = $row['group_name'];
                                //     if ($update_group_id == $group_id) {
                                //         echo "<option name=group_id value='$group_id' selected>$group_name</option>";
                                //     } else {
                                //         echo "<option name=group_id value='$group_id'>$group_name</option>";
                                //     }
                                // }
                                ?>
                            </select>
                        </span>
                    </div> -->

                <div class="form-submit">
                    <input type="submit" name="discussion_update" value="Update">
                </div>
            </form>
        </div>

    <?php } ?>

</div>