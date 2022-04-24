<script>
    function validateTask() {

        var task_type, task_content, task_deadline, file;

        task_type = document.getElementById("task_type").value;
        task_content = document.getElementById("task_content").value;
        task_deadline = document.getElementById("task_deadline").value;
        file = document.getElementById("file").value;

        if (task_type == '') {
            alert("Please select a task type.");
            document.getElementById("task_type").focus();
            return false;
        } else if (task_content == '') {
            alert("Please enter a description title.");
            document.getElementById("task_content").focus();
            return false;
        } else if (task_deadline == '') {
            alert("Please enter a deadline date.");
            document.getElementById("task_deadline").focus();
            return false;
        } else if (file == '') {
            alert("Please select a file name.");
            document.getElementById("file").focus();
            return false;
        } else
            return true;
    }
</script>
<?php

$session_user_id = $_SESSION['user_id'];

if (isset($_GET['course_id'])) {
    $session_course_id = $_GET['course_id'];
}

// ADD
if (isset($_POST['upload_task'])) {


    $task_type = mysqli_real_escape_string($conn, $_POST['task_type']);
    $task_content = mysqli_real_escape_string($conn, $_POST['task_content']);
    $task_deadline = mysqli_real_escape_string($conn, $_POST['task_deadline']);
    $today = date('Y-m-d', time());



    if (empty($task_type)) {
        array_push($errors, "Type is required");
    }
    if (empty($task_content)) {
        array_push($errors, "Content is required");
    }
    if (empty($task_deadline)) {
        array_push($errors, "Deadline is required");
    } elseif ($task_deadline <= $today) {
        array_push($errors, "Deadline invalid");
    }

    if (count($errors) == 0) {

        $file_id = upload_file('task');
        $add = "INSERT INTO task (task_type, task_content, task_deadline, course_id, file_id)
                VALUES('$task_type', '$task_content', '$task_deadline', '$session_course_id', '$file_id')";

        if (mysqli_query($conn, $add)) {
            array_push($success, "Task added Successful");
        } else {
            array_push($errors, "Error adding Task: " . mysqli_error($conn));
        }
    }
}

//DOWNLOAD
if (isset($_GET['download_file'])) {
    download_file($_GET['download_file']);
}

//UPDATE
if (isset($_POST['update_task'])) {

    $task_id = mysqli_real_escape_string($conn, $_GET['update_id']);


    $task_type = mysqli_real_escape_string($conn, $_POST['task_type']);
    $task_content = mysqli_real_escape_string($conn, $_POST['task_content']);
    $task_deadline = mysqli_real_escape_string($conn, $_POST['task_deadline']);
    $today = date('Y-m-d', time());



    if (empty($task_type)) {
        array_push($errors, "Type is required");
    }
    if (empty($task_content)) {
        array_push($errors, "Content is required");
    }
    if (empty($task_deadline)) {
        array_push($errors, "Deadline is required");
    } elseif ($task_deadline <= $today) {
        array_push($errors, "Deadline invalid");
    }

    if (count($errors) == 0) {

        $update = "UPDATE task SET task_type='$task_type', task_content='$task_content', task_deadline='$task_deadline'
                WHERE task_id='$task_id'";

        $file_id = $_GET['update_file'];

        if (mysqli_query($conn, $update)) {
            array_push($success, "Task update Successful");
            update_file('task', $file_id);
        } else {
            array_push($errors, "Error adding Task: " . mysqli_error($conn));
        }
    }
}


// DELETE
if (isset($_GET['delete_id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['delete_id']);
    $delete = "DELETE FROM task WHERE task_id='$id'";

    $file_id = $_GET['delete_file'];

    if (mysqli_query($conn, $delete)) {
        delete_file($file_id);
        header("location: ?page=course-task&course_id=$session_course_id");
        array_push($success, "Delete successful");
    } else {
        array_push($errors, "Delete error: " . mysqli_error($conn));
    }
}

?>

<div class="content-body">
    <?php

    display_success();
    display_error();

    if (isStudent()) {
        $query = "SELECT t.*, c.*, f.*, u.*, gc.group_id FROM task as t
        JOIN course as c ON c.course_id = t.course_id
        JOIN files as f ON f.file_id = t.file_id
        JOIN users as u ON u.user_id = f.uploaded_by_uid
        JOIN user_course_section as ucs ON ucs.course_id = c.course_id
		JOIN group_of_course as gc ON gc.course_id = c.course_id
        JOIN member_of_group as mg ON mg.group_id = gc.group_id
        JOIN student as st ON st.student_id = mg.student_id
        JOIN users as us ON us.user_id = ucs.user_id
        WHERE us.user_id = '$session_user_id' AND c.course_id = '$session_course_id' AND st.user_id = '$session_user_id'
        ORDER BY t.task_id ASC";
    } else {
        $query = "SELECT t.*, c.*, f.*, u.* FROM task as t
        JOIN course as c ON c.course_id = t.course_id
        JOIN files as f ON f.file_id = t.file_id
        JOIN users as u ON u.user_id = f.uploaded_by_uid
        JOIN user_course_section as ucs ON ucs.course_id = c.course_id
        JOIN users as us ON us.user_id = ucs.user_id
        WHERE us.user_id = '$session_user_id' AND c.course_id = '$session_course_id'
        ORDER BY t.task_id ASC";
    }
    $results = mysqli_query($conn, $query);

    if (mysqli_num_rows($results) > 0) {
        $course_name = mysqli_fetch_assoc($results)['course_name'];
    } else {
        $course_name = "No";
    }

    ?>
    <h2><?= $course_name ?> Tasks</h2>
    <hr>
    <table>
        <thead>
            <tr>
                <th>Content</th>
                <th>Type</th>
                <th>Deadline</th>
                <th>Uploaded by</th>
                <th>Uploaded on</th>
                <th>Files</th>
                <?php
                if (isStudent()) {
                    echo '<th>Solution</th>';
                }
                ?>
                <?php
                if (isProfessor()) {
                    echo '<th colspan="4">Action</th>';
                } else {
                    echo '<th colspan="2">Action</th>';
                }
                ?>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($results as $row) {
                $task_id = $row['task_id'];
                $task_type = $row['task_type'];
                $task_content = $row['task_content'];
                $task_deadline = date_convert($row['task_deadline']);
                $uploaded_by_uid = $row['username'];
                $uploaded_on = date_convert($row['uploaded_on']);
                $file_id = $row['file_id'];
                $file_name = $row['file_name'];
                if (isStudent()) {
                    $group_id = $row['group_id'];
                }
                $today = date('Y-m-d', time());
            ?>
                <tr>
                    <td><a href='?page=group-discussion&task_id=<?= $task_id ?>'><b><?= $task_content ?></b></a></td>
                    <td><?= $task_type ?></td>
                    <td><?= $task_deadline ?></td>
                    <td><?= $uploaded_by_uid ?></td>
                    <td><?= $uploaded_on ?></td>
                    <td><?= $file_name ?></td>
                    <?php
                    // MIGHT HAVE BUGS
                    if (isStudent()) {
                        $session_student_id = mysqli_fetch_assoc(get_records_where('student', 'user_id', $session_user_id))['student_id'];
                        if (isGroupLeader($session_student_id, $group_id)) {
                            if ($task_deadline >= $today) {
                                $query = "SELECT * FROM solution WHERE task_id='$task_id' AND group_id='$group_id'";
                                $check_solution = mysqli_query($conn, $query);

                                if (mysqli_num_rows($check_solution) > 0) {
                                    echo "<td><a href='?page=group-solution&course_id=$session_course_id&group_id=$group_id'>View</a></td>";
                                } else {
                                    echo "<td><a href='?page=group-solution&course_id=$session_course_id&group_id=$group_id&task_id=$task_id&upload_view=true'>Upload</a></td>";
                                }
                            } else {
                                echo "<td><b style='color:red;'>Deadline passed</b></td>";
                            }
                        } else {
                            if ($file_id == NULL) {
                                echo "<td>No Solution</td>";
                            } else {
                                echo "<td><a href='?page=group-solution&course_id=$session_course_id&group_id=$group_id'>View</a></td>";
                            }
                        }
                    } ?>
                    <td><a href='?page=group-discussion&task_id=<?= $task_id ?>'>Discussion</a></td>
                    <?php
                    if (isProfessor()) {
                        echo "<td><a href='?page=course-task&course_id=$session_course_id&download_file=$file_id'>Download</a></td>";
                        echo "<td><a href='?page=course-task&course_id=$session_course_id&update_view=true&update_id=$task_id&update_file=$file_id'>Update</a></td>";
                        echo "<td><a href='?page=course-task&course_id=$session_course_id&delete_id=$task_id&delete_file=$file_id' onclick='return confirm(&quot;Are you sure you want to delete?&quot;)'>Delete</a></td>";
                    } else {
                        echo "<td><a href='?page=course-task&course_id=$session_course_id&download_file=$file_id'>Download</a></td>";
                    }

                    ?>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <?php if (isProfessor()) { ?>
        <a href="?page=course-task&course_id=<?= $session_course_id ?>&upload_view=true">
            <button>Upload Task</button>
        </a>

        <?php if (isset($_GET['upload_view'])) { ?>
            <hr>
            <div class="form-container">
                <form class="form-body" action="" enctype="multipart/form-data" method="POST" onsubmit="return validateTask()">

                    <h3>Upload task</h3>

                    <div class="form-input">
                        <label for="task_type">Upload type</label>
                        <span>
                            <select name="task_type" id="task_type">
                                <option value="" selected hidden>Choose a type</option>
                                <option value="Assignment">Assignment</option>
                                <option value="Project">Project</option>
                            </select>
                        </span>
                    </div>

                    <div class="form-input">
                        <label>Description</label>
                        <span><input type="text" name="task_content" id="task_content"></span>
                    </div>

                    <div class="form-input">
                        <label>Deadline</label>
                        <span><input type="date" name="task_deadline" id="task_deadline"></span>
                    </div>

                    <div class="form-input">
                        <label>Select file</label>
                        <span><input type="file" name="file" id="file"> </span>
                    </div>

                    <div class="form-submit">
                        <input type="submit" name="upload_task" value="Upload">
                    </div>

                </form>
            </div>

        <?php } ?>

        <?php if (isset($_GET['update_view'])) { ?>

            <?php
            $task_id = mysqli_real_escape_string($conn, $_GET['update_id']);
            $query = "SELECT * FROM task WHERE task_id='$task_id'";

            $results = mysqli_query($conn, $query);

            foreach ($results as $row) {
                $task_type = $row['task_type'];
                $task_content = $row['task_content'];

                //converting date to dd/mm/yyyy - for display
                $task_deadline = date('Y-m-d', strtotime($row['task_deadline']));
            }

            ?>

            <hr>
            <div class="form-container">
                <form class="form-body" action="" enctype="multipart/form-data" method="POST" onsubmit="return validateTask()">

                    <h3>Update File</h3>

                    <div class="form-input">
                        <label for="task_type">Task type</label>
                        <span>
                            <select name="task_type" id="task_type">
                                <option value="" selected hidden>Choose a type</option>
                                <?php
                                $query = "SELECT DISTINCT task_type FROM task";
                                $task = mysqli_query($conn, $query);
                                foreach ($task as $row) {
                                    $task_type_check = $row['task_type'];
                                    if ($task_type_check == $task_type) {
                                        echo "<option value=$task_type_check selected>$task_type_check</option>";
                                    } else {
                                        echo "<option value=$task_type_check>$task_type_check</option>";
                                    }
                                }
                                ?>
                            </select>
                        </span>
                    </div>

                    <div class="form-input">
                        <label>Description</label>
                        <span><input type="text" name="task_content" id="task_content" value="<?= $task_content ?>"></span>
                    </div>

                    <div class="form-input">
                        <label>Deadline</label>
                        <span><input type="date" name="task_deadline" id="task_deadline" value="<?= $task_deadline ?>"></span>
                    </div>

                    <div class=" form-input">
                        <label>Select file</label>
                        <span><input type="file" name="file" id="file"></span>
                    </div>

                    <div class="form-submit">
                        <input type="submit" name="update_task" value="Update">
                    </div>

                </form>
            </div>

        <?php } ?>

    <?php } ?>

</div>