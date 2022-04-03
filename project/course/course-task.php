<?php

$user_id = $_SESSION['user_id'];

// ADD
if (isset($_POST['upload_file'])) {

    //task_type, task_content, task_deadline, course_id, file_id

    // receive all input values from the form
    $task_type = mysqli_real_escape_string($conn, $_POST['task_type']);
    $task_content = mysqli_real_escape_string($conn, $_POST['task_content']);
    $task_deadline = mysqli_real_escape_string($conn, $_POST['task_deadline']);


    // form validation: ensure that the form is correctly filled ...
    // by adding (array_push()) corresponding error unto $errors array
    if (empty($course_name)) {
        array_push($errors, "Course Name is required");
    }
    if (empty($course_number)) {
        array_push($errors, "Course Number is required");
    }

    if (count($errors) == 0) {
        $add = "INSERT INTO course (course_name, course_number) VALUES('$course_name', '$course_number');";

        if (mysqli_query($conn, $add)) {
            array_push($success, "Course added Successful");
        } else {
            array_push($errors, "Error adding course: " . mysqli_error($conn));
        }
    }
}

// DELETE
if (isset($_GET['delete_id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['delete_id']);
    $delete = "DELETE FROM task WHERE task_id='$id'";
    if (mysqli_query($conn, $delete)) {
        array_push($success, "Delete successful");
    } else {
        array_push($errors, "Delete error: " . mysqli_error($conn));
    }
}

?>

<div class="content-body">
    <?php

    if (isset($_GET['delete_view'])) {
        display_success();
        display_error();
    }

    $query = "SELECT * FROM task as s
                JOIN course as c ON c.course_id = s.course_id
                JOIN files as f ON f.file_id = s.file_id
                JOIN users as u ON u.user_id = f.uploaded_by_uid
                JOIN user_course_section as ucs ON ucs.course_id = c.course_id
                JOIN users as us ON us.user_id = ucs.user_id
                WHERE us.user_id = '$user_id'
                ORDER BY s.task_id ASC";
    $results = mysqli_query($conn, $query);

    ?>
    <h2>tasks</h2>
    <hr>
    <table>
        <thead>
            <tr>
                <th>Type</th>
                <th>Content</th>
                <th>Deadline</th>
                <th>Uploaded by</th>
                <th>Uploaded on</th>
                <th>File Name</th>
                <?php
                if (!isStudent()) {
                    echo '<th colspan="3">Action</th>';
                } else {
                    echo '<th>Action</th>';
                }
                ?>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($results as $row) {
                $id = $row['task_id'];
                $type = $row['task_type'];
                $content = $row['task_content'];
                $deadline = date_convert($row['task_deadline']);
                $uploaded_by_uid = $row['username'];
                $uploaded_on = date_convert($row['uploaded_on']);
                $file_name = $row['file_name'];
            ?>
                <tr>
                    <td><?= $type ?></td>
                    <td><?= $content ?></td>
                    <td><?= $deadline ?></td>
                    <td><?= $uploaded_by_uid ?></td>
                    <td><?= $uploaded_on ?></td>
                    <td><?= $file_name ?></td>
                    <?php
                    if (isProfessor()) {
                        echo '<td><a href="?page=course-task&download_file=' . $id . '">Download</a></td>';
                        echo '<td><a href="?page=course-task&update_view=true&update_file=' . $id . '">Update</a></td>';
                        echo "<td><a href='?page=course-task&delete_file=" . $id . "' onclick='return confirm(&quot;Are you sure you want to delete?&quot;)'>Delete</a></td>";
                    } else {
                        echo '<td><a href="?page=course-task&download_file=' . $id . '">Download</a></td>';
                    }
                    ?>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>

    <?php if (!isTA()) { ?>
        <a href="?page=course-task&upload_view=true">
            <button>Upload File</button>
        </a>
    <?php } ?>

    <?php if (isset($_GET['upload_view'])) { ?>
        <hr>
        <div class="form-container">
            <form class="form-body" action="" enctype="multipart/form-data" method="POST">

                <h3>Upload task</h3>

                <div class="form-input">
                    <label>Description</label>
                    <span><input type="text" name="task_content"></span>
                </div>

                <div class="form-input">
                    <label for="task_type">Upload type</label>
                    <span>
                        <select name="task_type">
                            <option value="" selected hidden>Choose a type</option>
                            <?php if (isProfessor()) { ?>
                                <option value="Assignment">Assignment</option>
                                <option value="Project">Project</option>
                            <?php } elseif (isStudent()) { ?>
                                <option value="Assignment_Solution">Solution - Assignment</option>
                                <option value="Project_Solution">Solution - Project</option>
                            <?php } ?>
                        </select>
                    </span>
                </div>

                <div class="form-input">
                    <label>Select file</label>
                    <span><input type="file" name="file"> </span>
                </div>

                <div class="form-submit">
                    <input type="submit" name="upload_file" value="Upload">
                </div>
            </form>
        </div>

    <?php } ?>

    <?php if (!isStudent()) { ?>

        <?php if (isset($_GET['update_view'])) { ?>

            <?php
            $id = mysqli_real_escape_string($conn, $_GET['update_file']);
            $query = "SELECT * FROM files WHERE file_id='$id'";

            $results = mysqli_query($conn, $query);

            foreach ($results as $row) {
                $id = $row['file_id'];
                $file_name = $row['file_name'];
                $content = $row['content'];
            }

            ?>

            <hr>
            <div class="form-container">
                <form class="form-body" action="?update_file=<?= $id ?>" enctype="multipart/form-data" method="POST">
                    <h3>Update File</h3>
                    <!-- <div class="form-input">
                        <label>File Name</label>
                        <span><?= $file_name ?></span>
                    </div> -->
                    <div class="form-input">
                        <label>Select file</label>
                        <span><input type="file" name="file"> </span>
                    </div>
                    <div class="form-submit">
                        <input type="submit" name="update_file" value="Update">
                    </div>
                </form>
            </div>

        <?php } ?>

    <?php } ?>

</div>