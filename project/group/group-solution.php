<?php

$user_id = $_SESSION['user_id'];

if (isset($_GET['course_id'])) {
    $course_id = $_GET['course_id'];
}

// ADD
if (isset($_POST['upload_file'])) {

    pre_print($_REQUEST);

    $task_id = $_GET['task_id'];

    // receive all input values from the form
    $solution_type = mysqli_real_escape_string($conn, $_POST['solution_type']);
    $solution_content = mysqli_real_escape_string($conn, $_POST['solution_content']);

    // form validation: ensure that the form is correctly filled ...
    // by adding (array_push()) corresponding error unto $errors array
    if (empty($solution_type)) {
        array_push($errors, "Type is required");
    }
    if (empty($solution_content)) {
        array_push($errors, "Content is required");
    }

    if (count($errors) == 0) {

        $file_id = upload_file('solution');

        $add = "INSERT INTO solution (solution_type, solution_content, task_id, file_id)
                VALUES('$solution_type', '$solution_content', '$task_id', '$file_id')";

        if (mysqli_query($conn, $add)) {
            array_push($success, "solution added Successful");
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
if (isset($_POST['update_file'])) {

    $id = mysqli_real_escape_string($conn, $_GET['update_id']);

    // receive all input values from the form
    $solution_type = mysqli_real_escape_string($conn, $_POST['solution_type']);
    $solution_content = mysqli_real_escape_string($conn, $_POST['solution_content']);
    $today = date('Y-m-d', time());

    // form validation: ensure that the form is correctly filled ...
    // by adding (array_push()) corresponding error unto $errors array
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

    $file_id = $_GET['delete_file'];

    $delete = "DELETE FROM solution WHERE solution_id='$id'";
    if (mysqli_query($conn, $delete)) {
        delete_file($file_id);
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

    $query = "SELECT t.*, c.*, f.*, s.solution_id, s.solution_type, s.solution_content, u.* FROM task as t
    JOIN course as c ON c.course_id = t.course_id
	JOIN user_course_section as ucs ON ucs.course_id = c.course_id
	JOIN users as us ON us.user_id = ucs.user_id
    LEFT JOIN solution as s ON s.task_id = t.task_id
	LEFT JOIN files as f ON f.file_id = s.file_id
    LEFT JOIN users as u ON u.user_id = f.uploaded_by_uid
    WHERE us.user_id = '$user_id' AND c.course_id = '$course_id'
    ORDER BY t.task_id ASC";
    $results = mysqli_query($conn, $query);

    $course_name = mysqli_fetch_assoc($results)['course_name'];

    ?>
    <h2><?= $course_name ?> solutions</h2>
    <hr>
    <table>
        <thead>
            <tr>
                <th>Task</th>
                <th>Type</th>
                <th>Content</th>
                <th>Uploaded by</th>
                <th>Uploaded on</th>
                <th>File Name</th>
                <?php
                if (isStudent()) {
                    echo '<th colspan="4">Action</th>';
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

                $solution_type = $row['solution_type'];
                $solution_id = $row['solution_id'];
                $solution_content = $row['solution_content'];
                $uploaded_by_uid = $row['username'];
                if ($row['uploaded_on'] !== NULL) {
                    $uploaded_on = date_convert($row['uploaded_on']);
                } else {
                    $uploaded_on = '';
                }
                $file_id = $row['file_id'];
                $file_name = $row['file_name'];
                $task_content = $row['task_content'];
                $course_id = $row['course_id'];
            ?>
                <tr>
                    <td><b><a href='?page=course-task&course_id=<?= $course_id ?>'><?= $task_content ?></a></b></td>
                    <td><?= $solution_type ?></td>
                    <td><?= $solution_content ?></td>
                    <td><?= $uploaded_by_uid ?></td>
                    <td><?= $uploaded_on ?></td>
                    <td><?= $file_name ?></td>
                    <?php
                    if (isStudent()) {
                        if ($file_id == NULL && $solution_id == NULL) {
                            echo "<td><a href='?page=group-solution&course_id=$course_id&task_id=$task_id&upload_view=true'>Upload</a></td>";
                        } else {
                            echo "<td><a href='?page=group-solution&course_id=$course_id&download_file=$file_id'>Download</a></td>";
                            echo "<td><a href='?page=group-solution&course_id=$course_id&update_id=$solution_id&update_file=$file_id&update_view=true'>Update</a></td>";
                            echo "<td><a href='?page=group-solution&course_id=$course_id&delete_id=$solution_id&delete_file=$file_id' onclick='return confirm(&quot;Are you sure you want to delete?&quot;)'>Delete</a></td>";
                        }
                    } elseif ($file_id !== NULL) {
                        echo "<td><a href='?page=group-solution&course_id=$course_id&download_file=$file_id'>Download</a></td>";
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
                <form class="form-body" action="" enctype="multipart/form-data" method="POST">

                    <h3>Upload solution</h3>

                    <div class="form-input">
                        <label>Solution for</label>
                        <span><b><?= $task_content ?></b></span>
                    </div>

                    <div class="form-input">
                        <label for="solution_type">solution type</label>
                        <span>
                            <select name="solution_type">
                                <option value="<?= $task_type ?>" selected><?= $task_type ?></option>
                            </select>
                        </span>
                    </div>

                    <div class="form-input">
                        <label>Description</label>
                        <span><input type="text" name="solution_content"></span>
                    </div>

                    <div class="form-input">
                        <label>Select file</label>
                        <span><input type="file" name="file"></span>
                    </div>

                    <div class="form-submit">
                        <input type="submit" name="upload_file" value="Upload">
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
                <form class="form-body" action="" enctype="multipart/form-data" method="POST">

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
                        <span><input type="text" name="solution_content" value="<?= $solution_content ?>"></span>
                    </div>


                    <div class=" form-input">
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