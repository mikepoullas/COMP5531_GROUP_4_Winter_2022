<?php

// initializing variables
$id = $title = $posted_by = $posted_on = $content = $group_id = $group_name = $course_id = "";
$user_id = $_SESSION['user_id'];

// ADD
if (isset($_POST['discussion_add'])) {

    // receive all input values from the form
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $group_id = mysqli_real_escape_string($conn, $_POST['group_id']);

    // form validation: ensure that the form is correctly filled ...
    // by adding (array_push()) corresponding error unto $errors array
    if (empty($title)) {
        array_push($errors, "Title is required");
    }
    if (empty($content)) {
        array_push($errors, "Content is required");
    }
    if (empty($group_id)) {
        array_push($errors, "Group is required");
    }

    if (count($errors) == 0) {
        $add = "INSERT INTO discussion (title, content, posted_by_uid, posted_on, group_id)
            VALUES('$title', '$content', '$user_id', CURRENT_TIMESTAMP,'$group_id')";

        if (mysqli_query($conn, $add)) {
            array_push($success, "Added successfully");
            // clear variables
            $title = $content = $group_id = "";
        } else {
            array_push($errors, "Error adding: ", mysqli_error($conn));
        }
    }
}

// UPDATE
if (isset($_POST['update_discussion'])) {

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
            // clear variables
            $course_name = $course_number = "";
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
        array_push($success, "Delete successful");
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

    $query = "SELECT d.*, u.username, g.group_name, c.course_name FROM discussion as d
    JOIN users as u ON d.posted_by_uid = u.user_id
    JOIN student_group as g ON g.group_id = d.group_id
    JOIN group_of_course as gc ON gc.group_id = g.group_id
    JOIN course as c ON c.course_id = gc.course_id
    ORDER BY discussion_id ASC";
    $discussions = mysqli_query($conn, $query);

    ?>
    <h3>Discussions</h3>
    <hr>
    <table>
        <thead>
            <tr>
                <?php isAdmin() ? print '<th>Discussion ID</th>' : ''; ?>
                <th>Title</th>
                <th>Content</th>
                <th>Posted by</th>
                <th>Posted on</th>
                <th>Group Name</th>
                <th>Course Name</th>
                <?php !isStudent() ? print '<th colspan="2">Action</th>' : ''; ?>

            </tr>
        </thead>
        <tbody>
            <?php

            foreach ($discussions as $row) {
                $id = $row['discussion_id'];
                $title = $row['title'];
                $content = $row['content'];
                $posted_by = $row['username'];
                $posted_on = $row['posted_on'];
                $group_id = $row['group_id'];
                $group_name = $row['group_name'];
                $course_name = $row['course_name'];
            ?>
                <tr>
                    <?php if (isAdmin()) {
                        echo '<td>' . $id . '</td>';
                    } ?>
                    <td><?php echo $title ?></td>
                    <td><?php echo $content ?></td>
                    <td><?php echo $posted_by ?></td>
                    <td><?php echo $posted_on ?></td>
                    <td><?php echo $group_name ?></td>
                    <td><?php echo $course_name ?></td>
                    <?php if (!isStudent()) {
                        echo '<td><a href="?page=discussions&update_view=true&update_id=' . $id . '">Update</a></td>';
                        echo '<td><a href="?page=discussions&delete_view=true&delete_id=' . $id . '">Delete</a></td>';
                    } ?>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>

    <?php if (!isStudent()) { ?>
        <a href="?page=discussions&add_view=true">
            <button>Add Discussion</button>
        </a>

        <?php if (isset($_GET['add_view'])) { ?>
            <hr>
            <div class="form-container">
                <form class="form-body" action="" method="POST">

                    <?php
                    echo display_success();
                    echo display_error();
                    ?>

                    <h4><u>Add Discussion</u></h4>

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

                    <div class=" form-input">
                        <label for="group_id">For Group</label>
                        <span>
                            <select name="group_id">
                                <option value="" selected hidden>Choose a Group</option>
                                <?php

                                $query = "SELECT * FROM student_group as g
                                            JOIN group_of_course as gc ON gc.group_id = g.group_id
                                            JOIN course as c ON c.course_id = gc.course_id";
                                $groups = mysqli_query($conn, $query);

                                foreach ($groups as $row) {
                                    $group_id = $row['group_id'];
                                    $group_name = $row['group_name'];
                                    $course_name = $row['course_name'];
                                    echo "<option name=group_id id=group_id value='$group_id'>$group_name - $course_name</option>";
                                }
                                ?>
                            </select>
                        </span>
                    </div>

                    <div class="form-submit">
                        <input type="submit" name="discussion_add" value="Add">
                    </div>

                </form>
            </div>

        <?php } ?>

        <?php if (isset($_GET['update_view'])) { ?>

            <?php
            $id = mysqli_real_escape_string($conn, $_GET['update_id']);
            $query = "SELECT d.*, u.username, g.group_name, c.course_name FROM discussion as d
                        JOIN users as u ON d.posted_by_uid = u.user_id
                        JOIN student_group as g ON g.group_id = d.group_id
                        JOIN group_of_course as gc ON gc.group_id = g.group_id
                        JOIN course as c ON c.course_id = gc.course_id
                        WHERE d.discussion_id='$id'
                        ORDER BY discussion_id ASC";
            $results = mysqli_query($conn, $query);

            while ($row = mysqli_fetch_assoc($results)) {
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

                    <?php
                    echo display_success();
                    echo display_error();
                    ?>

                    <h4><u>Update Discussion</u></h4>

                    <div class="form-input">
                        <label>Group Name</label>
                        <span><?= $group_name ?></span>
                        <label>Course Name</label>
                        <span><?= $course_name ?></span>
                    </div>

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
                        <input type="submit" name="update_discussion" value="Update">
                    </div>
                </form>
            </div>

        <?php } ?>

    <?php } ?>

</div>