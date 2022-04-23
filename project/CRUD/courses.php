<?php

// ADD
if (isset($_POST['add_course'])) {


    $course_name = mysqli_real_escape_string($conn, $_POST['course_name']);
    $course_number = mysqli_real_escape_string($conn, $_POST['course_number']);



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

// UPDATE
if (isset($_POST['update_course'])) {

    $id = mysqli_real_escape_string($conn, $_GET['update_id']);


    $course_name = mysqli_real_escape_string($conn, $_POST['course_name']);
    $course_number = mysqli_real_escape_string($conn, $_POST['course_number']);



    if (empty($course_name)) {
        array_push($errors, "Course Name is required");
    }
    if (empty($course_number)) {
        array_push($errors, "Course Number is required");
    }

    if (count($errors) == 0) {
        $update = "UPDATE course SET course_name = '$course_name', course_number = '$course_number' WHERE course_id ='$id'";

        if (mysqli_query($conn, $update)) {
            array_push($success, "Update Successful");
        } else {
            array_push($errors, "Error updating course: ", mysqli_error($conn));
        }
    }
}

// DELETE
if (isset($_GET['delete_id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['delete_id']);
    $delete = "DELETE FROM course WHERE course_id='$id'";
    if (mysqli_query($conn, $delete)) {
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

    $query = "SELECT * FROM course ORDER BY course_id ASC";
    $results = mysqli_query($conn, $query);

    ?>
    <h2>Courses</h2>
    <hr>
    <table>
        <thead>
            <tr>
                <th>Course Name</th>
                <th>Course Number</th>
                <?php isAdmin() ? print '<th colspan="2">Action</th>' : ''; ?>

            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($results as $row) {
                $id = $row['course_id'];
                $course_name = $row['course_name'];
                $course_number = $row['course_number'];
            ?>
                <tr>
                    <td><?= $course_name ?></td>
                    <td><?= $course_number ?></td>
                    <?php if (isAdmin()) {
                        echo '<td><a href="?page=courses&update_view=true&update_id=' . $id . '">Update</a></td>';
                        echo "<td><a href='?page=courses&delete_id=" . $id . "' onclick='return confirm(&quot;Are you sure you want to delete?&quot;)'>Delete Course</a></td>";
                    } ?>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>

    <?php if (isAdmin()) { ?>
        <a href="?page=courses&add_view=true">
            <button>Add Course</button>
        </a>

        <?php if (isset($_GET['add_view'])) { ?>
            <hr>
            <div class="form-container">
                <form class="form-body" action="" method="POST">

                    <h3>Add Course</h3>

                    <div class="form-input">
                        <label>Course Name</label>
                        <span><input type="text" name="course_name"></span>
                    </div>
                    <div class="form-input">
                        <label>Course Number</label>
                        <span><input type="number" name="course_number"> </span>
                    </div>
                    <div class="form-submit">
                        <input type="submit" name="add_course" value="Add">
                    </div>
                </form>
            </div>

        <?php } ?>

        <?php if (isset($_GET['update_view'])) { ?>

            <?php
            $id = mysqli_real_escape_string($conn, $_GET['update_id']);
            $query = "SELECT * FROM course WHERE course_id='$id'";
            $results = mysqli_query($conn, $query);

            foreach ($results as $row) {
                $id = $row['course_id'];
                $course_name = $row['course_name'];
                $course_number = $row['course_number'];
            }
            ?>

            <hr>
            <div class="form-container">
                <form class="form-body" action="" method="POST">

                    <h3>Update Course</h3>

                    <div class="form-input">
                        <label>Course Name</label>
                        <span><input type="text" name="course_name" value='<?= $course_name ?>'></span>
                    </div>
                    <div class="form-input">
                        <label>Course Number</label>
                        <span><input type="number" name="course_number" value='<?= $course_number ?>'> </span>
                    </div>
                    <div class="form-submit">
                        <input type="submit" name="update_course" value="Update">
                    </div>
                </form>
            </div>

        <?php } ?>

    <?php } ?>

</div>