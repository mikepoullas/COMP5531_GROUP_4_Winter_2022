<script>
    function valdiateSolution() {

        var solution_content, file;

        solution_content = document.getElementById("solution_content").value;
        file = document.getElementById("file").value;

        if (solution_content == '') {
            alert("Please enter a solution content.");
            document.getElementById("solution_content").focus();
            return false;
        } else if (file == '') {
            alert("Please enter file.");
            document.getElementById("file").focus();
            return false;
        } else
            return true;
    }

    function validateGrade() {

        var grade;

        grade = document.getElementById("grade").value;

        if (grade == '') {
            alert("Please enter a grade.");
            document.getElementById("grade").focus();
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
if (isset($_GET['group_id'])) {
    $session_group_id = $_GET['group_id'];
}
if (isset($_GET['task_id'])) {
    $session_task_id = $_GET['task_id'];
}
// ADD
if (isset($_POST['upload_solution'])) {

    $task_id = $_GET['task_id'];
    $group_id = $_GET['group_id'];


    $solution_type = mysqli_real_escape_string($conn, $_POST['solution_type']);
    $solution_content = mysqli_real_escape_string($conn, $_POST['solution_content']);



    if (empty($solution_type)) {
        array_push($errors, "Type is required");
    }
    if (empty($solution_content)) {
        array_push($errors, "Content is required");
    }

    if (count($errors) == 0) {

        $file_id = upload_file('solution');

        $add = "INSERT INTO solution (solution_type, solution_content, task_id, group_id, file_id)
                VALUES('$solution_type', '$solution_content', '$task_id', '$group_id', '$file_id')";

        if (mysqli_query($conn, $add)) {
            array_push($success, "Solution added Successful");
            header("location: ?page=group-solution&course_id=$session_course_id&group_id=$session_group_id");
        } else {
            array_push($errors, "Error adding solution: " . mysqli_error($conn));
        }
    }
}

//DOWNLOAD
if (isset($_GET['download_file'])) {
    download_file($_GET['download_file']);
}

//UPDATE
if (isset($_POST['update_solution'])) {

    $id = mysqli_real_escape_string($conn, $_GET['update_id']);


    $solution_type = mysqli_real_escape_string($conn, $_POST['solution_type']);
    $solution_content = mysqli_real_escape_string($conn, $_POST['solution_content']);
    $today = date('Y-m-d', time());



    if (empty($solution_type)) {
        array_push($errors, "Type is required");
    }
    if (empty($solution_content)) {
        array_push($errors, "Content is required");
    }

    if (count($errors) == 0) {

        $update = "UPDATE solution SET solution_type='$solution_type', solution_content='$solution_content'
                WHERE solution_id='$id'";

        $file_id = $_GET['update_file'];

        if (mysqli_query($conn, $update)) {
            array_push($success, "solution update Successful");
            update_file('solution', $file_id);
        } else {
            array_push($errors, "Error adding solution: " . mysqli_error($conn));
        }
    }
}

// DELETE
if (isset($_GET['delete_id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['delete_id']);
    $delete = "DELETE FROM solution WHERE solution_id='$id'";

    $file_id = $_GET['delete_file'];

    if (mysqli_query($conn, $delete)) {
        delete_file($file_id);
        array_push($success, "Delete successful");
        header("location: ?page=group-solution&course_id=$session_course_id&group_id=$session_group_id");
    } else {
        array_push($errors, "Delete error: " . mysqli_error($conn));
    }
}

// ADD GRADE
if (isset($_POST['add_grade'])) {

    $group_id = $_GET['group_id'];
    $solution_id = $_GET['solution_id'];


    $grade = mysqli_real_escape_string($conn, $_POST['grade']);



    if (empty($grade)) {
        array_push($errors, "Grade is required");
    } elseif ($grade > 100 || $grade < 0) {
        array_push($errors, "Invalid grade");
    }

    $query = "SELECT * FROM student_groups as g
                JOIN member_of_group as mg ON mg.group_id = g.group_id
                    JOIN student as st ON st.student_id = mg.student_id
                    WHERE g.group_id = '$group_id'";

    $groupArr = mysqli_query($conn, $query);

    foreach ($groupArr as $row) {
        $student_id = $row['student_id'];

        if (count($errors) == 0) {
            $add_grade = "INSERT INTO grades (grade, student_id, solution_id)
                            VALUES('$grade', '$student_id', '$solution_id')";
            if (!mysqli_query($conn, $add_grade)) {
                array_push($errors, "Error adding grade: " . mysqli_error($conn));
            }
        }
    }

    array_push($success, "Grade added Successful");
}

?>

<div class="content-body">
    <?php

    display_success();
    display_error();

    if (isset($_GET['group_id'])) {
        $query = "SELECT t.*, c.*, f.*, s.solution_id, s.solution_type, s.solution_content, s.group_id, u.*, g.* FROM task as t
        JOIN course as c ON c.course_id = t.course_id
        JOIN group_of_course as gc ON gc.course_id = c.course_id
        JOIN student_groups as g ON g.group_id = gc.group_id
        JOIN user_course_section as ucs ON ucs.course_id = c.course_id
        JOIN users as us ON us.user_id = ucs.user_id
        LEFT JOIN solution as s ON s.task_id = t.task_id
        LEFT JOIN files as f ON f.file_id = s.file_id
        LEFT JOIN users as u ON u.user_id = f.uploaded_by_uid
        WHERE us.user_id = '$session_user_id' AND c.course_id = '$session_course_id' AND s.group_id = '$session_group_id' AND g.group_id = '$session_group_id'
        ORDER BY t.task_id ASC";
    } else {
        $query = "SELECT t.*, c.*, f.*, s.solution_id, s.solution_type, s.solution_content, s.group_id, u.* FROM task as t
        JOIN course as c ON c.course_id = t.course_id
        JOIN user_course_section as ucs ON ucs.course_id = c.course_id
        JOIN users as us ON us.user_id = ucs.user_id
        LEFT JOIN solution as s ON s.task_id = t.task_id
        LEFT JOIN files as f ON f.file_id = s.file_id
        LEFT JOIN users as u ON u.user_id = f.uploaded_by_uid
        WHERE us.user_id = '$session_user_id' AND c.course_id = '$session_course_id'
        ORDER BY t.task_id ASC";
    }

    $results = mysqli_query($conn, $query);

    if (mysqli_num_rows($results) > 0) {
        $course_name = mysqli_fetch_assoc($results)['course_name'];
    } else {
        $course_name = "No";
    }

    if (isset($_GET['group_id'])) {
        $group_name = mysqli_fetch_assoc(get_records_where('student_groups', 'group_id', $session_group_id))['group_name'];
        echo "<h2>$course_name Solutions - $group_name</h2>";
    } else {
        echo "<h2>$course_name Solutions - All Groups</h2>";
    }

    ?>

    <hr>
    <table>
        <thead>
            <tr>
                <th>Task</th>
                <th>Group Name</th>
                <th>Type</th>
                <th>Content</th>
                <th>Uploaded by</th>
                <th>Uploaded on</th>
                <th>Files</th>
                <?php
                if (isStudent()) {
                    echo '<th colspan="4">Action</th>';
                } else if (isProfessor()) {
                    echo '<th colspan="2">Action</th>';
                } else {
                    echo '<th>Action</th>';
                }
                ?>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($results as $row) {

                $task_id = $row['task_id'];
                $task_content = $row['task_content'];
                $task_deadline = $row['task_deadline'];
                $today = date('Y-m-d', time());

                $solution_type = $row['solution_type'];
                $solution_id = $row['solution_id'];
                $solution_content = $row['solution_content'];

                $uploaded_by_uid = $row['uploaded_by_uid'];
                $uploaded_by = $row['username'];
                $uploaded_on = date_convert($row['uploaded_on']);

                if (isStudent()) {
                    $group_id = $row['group_id'];
                    $group_name = $row['group_name'];
                    $group_leader_sid = $row['group_leader_sid'];
                } else {
                    $group_id = mysqli_fetch_assoc(get_records_where('solution', 'solution_id', $solution_id))['group_id'];
                    $group_name = mysqli_fetch_assoc(get_records_where('student_groups', 'group_id', $group_id))['group_name'];
                }

                $file_id = $row['file_id'];
                $file_name = $row['file_name'];
                $course_id = $row['course_id'];
            ?>
                <tr>
                    <td><b><a href='?page=course-task&course_id=<?= $course_id ?>'><?= $task_content ?></a></b></td>
                    <td><?= $group_name ?></td>
                    <td><?= $solution_type ?></td>
                    <td><?= $solution_content ?></td>
                    <td><?= $uploaded_by ?></td>
                    <td><?= $uploaded_on ?></td>
                    <td><?= $file_name ?></td>

                    <?php
                    if (isStudent()) {
                        $session_student_id = mysqli_fetch_assoc(get_records_where('student', 'user_id', $session_user_id))['student_id'];
                        if (isGroupLeader($session_student_id, $session_group_id)) {
                            if ($file_id == NULL && $solution_id == NULL) {
                                if ($task_deadline >= $today) {
                                    echo "<td><a href='?page=group-solution&course_id=$course_id&task_id=$task_id&group_id=$session_group_id&upload_view=true'>Upload</a></td>";
                                } else {
                                    echo "<td><b style='color:red;'>Deadline passed</b></td>";
                                }
                            } else {
                                echo "<td><a href='?page=group-solution&course_id=$course_id&download_file=$file_id'>Download</a></td>";
                                echo "<td><a href='?page=group-solution&course_id=$course_id&update_id=$solution_id&update_file=$file_id&update_view=true&group_id=$group_id'>Update</a></td>";
                                echo "<td><a href='?page=group-solution&course_id=$course_id&delete_id=$solution_id&delete_file=$file_id' onclick='return confirm(&quot;Are you sure you want to delete?&quot;)'>Delete</a></td>";
                            }
                        } else {
                            if ($file_id == NULL && $solution_id == NULL) {
                                echo "<td>No Solution</td>";
                            } else {
                                echo "<td><a href='?page=group-solution&course_id=$course_id&download_file=$file_id'>Download</a></td>";
                            }
                        }
                    } elseif ($file_id !== NULL) {
                        echo "<td><a href='?page=group-solution&course_id=$course_id&download_file=$file_id'>Download</a></td>";
                        if (isProfessor() && isset($_GET['group_id'])) {
                            echo "<td><a href='?page=group-solution&course_id=$course_id&group_id=$session_group_id&solution_id=$solution_id&grade_view=true'>Grade</a></td>";
                        }
                    } else {
                        echo "<td>No Solution</td>";
                    }
                    ?>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <?php if (isStudent()) { ?>

        <?php if (isset($_GET['upload_view'])) { ?>

            <?php

            $task_id = $_GET['task_id'];

            $task = get_records_where('task', 'task_id', $task_id);
            foreach ($task as $row) {
                $task_type = $row['task_type'];
                $task_content = $row['task_content'];
            }

            ?>

            <hr>
            <div class="form-container">
                <form class="form-body" action="" enctype="multipart/form-data" method="POST" onsubmit="return valdiateSolution()">

                    <h3>Upload solution</h3>

                    <div class="form-input">
                        <label>Solution for</label>
                        <span><b><?= $task_content ?></b></span>
                    </div>

                    <div class="form-input">
                        <label for="solution_type">Solution type</label>
                        <span>
                            <select name="solution_type">
                                <option value="<?= $task_type ?>" selected><?= $task_type ?></option>
                            </select>
                        </span>
                    </div>

                    <div class="form-input">
                        <label>Description</label>
                        <span><input type="text" name="solution_content" id="solution_content"></span>
                    </div>

                    <div class="form-input">
                        <label>Select file</label>
                        <span><input type="file" name="file" id="file"></span>
                    </div>

                    <div class="form-submit">
                        <input type="submit" name="upload_solution" value="Upload">
                    </div>

                </form>
            </div>

        <?php } ?>

        <?php if (isset($_GET['update_view'])) { ?>

            <?php
            $solution_id = mysqli_real_escape_string($conn, $_GET['update_id']);
            $query = "SELECT * FROM solution WHERE solution_id='$solution_id'";

            $results = mysqli_query($conn, $query);

            foreach ($results as $row) {
                $solution_type = $row['solution_type'];
                $solution_content = $row['solution_content'];
            }

            ?>

            <hr>
            <div class="form-container">
                <form class="form-body" action="" enctype="multipart/form-data" method="POST" onsubmit="return valdiateSolution()">

                    <h3>Update File</h3>

                    <div class="form-input">
                        <label for="solution_type">Solution type</label>
                        <span>
                            <select name="solution_type">
                                <option value="<?= $solution_type ?>" selected><?= $solution_type ?></option>
                            </select>
                        </span>
                    </div>

                    <div class="form-input">
                        <label>Description</label>
                        <span><input type="text" name="solution_content" id="solution_content" value="<?= $solution_content ?>"></span>
                    </div>


                    <div class=" form-input">
                        <label>Select file</label>
                        <span><input type="file" name="file" id="file"> </span>
                    </div>

                    <div class="form-submit">
                        <input type="submit" name="update_solution" value="Update">
                    </div>

                </form>
            </div>

        <?php } ?>

    <?php } ?>

    <?php if (isProfessor()) { ?>

        <?php if (isset($_GET['grade_view'])) { ?>

            <?php

            $group_id = $_GET['group_id'];
            $group_name = mysqli_fetch_assoc(get_records_where('student_groups', 'group_id', $group_id))['group_name'];

            $solution_id = $_GET['solution_id'];
            $solution_content = mysqli_fetch_assoc(get_records_where('solution', 'solution_id', $solution_id))['solution_content'];

            ?>

            <hr>
            <div class="form-container">
                <form class="form-body" action="" enctype="multipart/form-data" method="POST" onsubmit="return validateGrade()">

                    <h3>Grade solution</h3>

                    <div class="form-input">
                        <label>Group</label>
                        <span><b><?= $group_name ?></b></span>
                    </div>

                    <div class="form-input">
                        <label>Solution for</label>
                        <span><b><?= $solution_content ?></b></span>
                    </div>

                    <div class="form-input">
                        <label>Grade</label>
                        <span><input type="number" name="grade" id="grade"></span>
                    </div>

                    <div class="form-submit">
                        <input type="submit" name="add_grade" value="Add Grade">
                    </div>

                </form>
            </div>

        <?php } ?>

    <?php } ?>

</div>