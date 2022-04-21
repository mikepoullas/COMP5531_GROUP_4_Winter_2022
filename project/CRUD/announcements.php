<script>
function validateAnnouncement() {

	var title, content, course_id;

	title = document.getElementById("title").value;
	content = document.getElementById("content").value;
	course_id = document.getElementById("course_id").value;

	if (title == '') {
		alert("Please enter a title.");
		document.getElementById("title").focus();
		return false;
	} else if (content == '') {
		alert("Please enter some content. ");
		document.getElementById("content").focus();
		return false;
	} else if (course_id == '') {
		alert("Please select a course.");
		document.getElementById("course_id").focus();
		return false;
	} else
		return true;

}
</script>

<?php

$session_user_id = $_SESSION['user_id'];

// ADD
if (isset($_POST['add_announcement'])) {

    // receive all input values from the form
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $course_id = mysqli_real_escape_string($conn, $_POST['course_id']);

    // form validation: ensure that the form is correctly filled ...
    // by adding (array_push()) corresponding error unto $errors array
    if (empty($title)) {
        array_push($errors, "Title is required");
    }
    if (empty($content)) {
        array_push($errors, "Content is required");
    }
    if (empty($course_id)) {
        array_push($errors, "Course is required");
    }

    if (count($errors) == 0) {
        $add = "INSERT INTO announcement (announcement_title, announcement_content, posted_by_uid, posted_on, course_id)
            VALUES('$title', '$content', '$session_user_id', NOW(),'$course_id')";

        if (mysqli_query($conn, $add)) {
            array_push($success, "Added successfully");
        } else {
            array_push($errors, "Error adding: ", mysqli_error($conn));
        }
    }
}

// UPDATE
if (isset($_POST['update_announcement'])) {

    $id = mysqli_real_escape_string($conn, $_GET['update_id']);

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

        $update = "UPDATE announcement SET announcement_title = '$title', announcement_content = '$content' WHERE announcement_id ='$id'";

        if (mysqli_query($conn, $update)) {
            array_push($success, "Update Successful");
        } else {
            array_push($errors, "Error updating: " . mysqli_error($conn));
        }
    }
}

// DELETE
if (isset($_GET['delete_id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['delete_id']);
    $delete = "DELETE FROM announcement WHERE announcement_id='$id'";
    if (mysqli_query($conn, $delete)) {
        array_push($success, "Delete successful");
    } else {
        array_push($errors, "Error Deleting " . mysqli_error($conn));
    }
}

?>

<div class="content-body">
    <?php

    display_success();
    display_error();

    if (isAdmin()) {
        $query = "SELECT a.*, u.username, c.course_name FROM announcement as a
        JOIN users as u ON a.posted_by_uid = u.user_id
        JOIN course as c ON c.course_id = a.course_id
        ORDER BY announcement_id ASC";
    } else {
        $query = "SELECT a.*, u.username, c.course_name FROM announcement as a
        JOIN users as u ON a.posted_by_uid = u.user_id
        JOIN course as c ON c.course_id = a.course_id
        JOIN user_course_section as ucs ON ucs.course_id = c.course_id
        JOIN users as us ON us.user_id = ucs.user_id
        WHERE us.user_id = $session_user_id
        ORDER BY announcement_id ASC";
    }

    $announcements = mysqli_query($conn, $query);

    ?>
    <h2>Announcements</h2>
    <hr>
    <table>
        <thead>
            <tr>
                <?php isAdmin() ? print '<th>Announcement ID</th>' : ''; ?>
                <th>Title</th>
                <th>Content</th>
                <th>Posted by</th>
                <th>Posted on</th>
                <th>Course Name</th>
                <?php !isStudent() ? print '<th colspan="2">Action</th>' : ''; ?>

            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($announcements as $row) {
                $id = $row['announcement_id'];
                $title = $row['announcement_title'];
                $content = $row['announcement_content'];
                $posted_by = $row['username'];
                $posted_on = date_convert($row['posted_on']);
                $course_id = $row['course_id'];
                $course_name = $row['course_name'];
            ?>
                <tr>
                    <?php if (isAdmin()) {
                        echo '<td>' . $id . '</td>';
                    } ?>
                    <td><?= $title ?></td>
                    <td><?= $content ?></td>
                    <td><?= $posted_by ?></td>
                    <td><?= $posted_on ?></td>
                    <td><?= $course_name ?></td>
                    <?php if (!isStudent()) {
                        echo '<td><a href="?page=announcements&update_view=true&update_id=' . $id . '">Update</a></td>';
                        echo "<td><a href='?page=announcements&delete_id=" . $id . "' onclick='return confirm(&quot;Are you sure you want to delete? &quot;)'>Delete Announcement</a></td>";
                    } ?>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>

    <?php if (!isStudent()) { ?>
        <a href="?page=announcements&add_view=true">
            <button>Add Announcement</button>
        </a>

        <?php if (isset($_GET['add_view'])) { ?>
            <hr>
            <div class="form-container">
                <form class="form-body" action="" method="POST" onsubmit="return validateAnnouncement()">

                    <h3>Add Announcement</h3>

                    <div class="form-input">
                        <label>Title</label>
                        <span><input type="text" name="title" id="title"></span>
                    </div>
                    <div class="form-input">
                        <label>Content</label>
                        <br>
                        <textarea name="content" id="content"></textarea>
                    </div>

                    <div class="form-input">
                        <p>Course</p>
                        <div class="scroll-list">
                            <select name="course_id" id="course_id">
                                <option value="" selected hidden>Choose Course</option>
                                <?php
                                if (isProfessor()) {
                                    $query = "SELECT c.* FROM course as c
                                    JOIN prof_of_course as pc ON pc.course_id = c.course_id
                                    JOIN professor as p ON p.professor_id = pc.professor_id
                                    WHERE p.user_id = '$session_user_id'";
                                    $courses = mysqli_query($conn, $query);
                                }
                                if (isAdmin()) {
                                    $courses = get_table_array('course');
                                }

                                foreach ($courses as $row) {
                                    $course_id = $row['course_id'];
                                    $course_name = $row['course_name'];
                                    echo "<option name=course_id value='$course_id'>$course_name</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-submit">
                        <input type="submit" name="add_announcement" value="Add">
                    </div>

                </form>
            </div>

        <?php } ?>

        <?php if (isset($_GET['update_view'])) { ?>

            <?php
            $id = mysqli_real_escape_string($conn, $_GET['update_id']);
            $query = "SELECT a.*, u.username, c.course_name FROM announcement as a
            JOIN users as u ON a.posted_by_uid = u.user_id
            JOIN course as c ON a.course_id = c.course_id
            WHERE a.announcement_id='$id'";
            $results = mysqli_query($conn, $query);

            foreach ($results as $row) {
                $id = $row['announcement_id'];
                $title = $row['announcement_title'];
                $content = $row['announcement_content'];
                $course_name = $row['course_name'];
            }
            ?>

            <hr>
            <div class="form-container">
                <form class="form-body" action="" method="POST" onsubmit="return validateAnnouncement()">

                    <h3>Update Announcement</h3>

                    <div class="form-input">
                        <label id=>Course Name</label>
                        <span><b><?= $course_name ?></b></span>
						<input type="hidden" id="course_id" value="<?= $course_name ?>">
                    </div>

                    <div class="form-input">
                        <label>Title</label>
                        <span><input type="text" name="title" id="title" value='<?= $title ?>'></span>
                    </div>

                    <div class="form-input">
                        <label>Content</label>
                        <br>
                        <textarea name="content" id="content"><?= $content ?></textarea>
                    </div>

                    <div class="form-submit">
                        <input type="submit" name="update_announcement" value="Update">
                    </div>
                </form>
            </div>

        <?php } ?>

    <?php } ?>

</div>