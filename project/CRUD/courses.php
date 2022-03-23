<?php

// initializing variables
$id = $course_name = $course_number = "";

// ADD
if (isset($_POST['courses_add'])) {

    // receive all input values from the form
    $course_name = mysqli_real_escape_string($conn, $_POST['course_name']);
    $course_number = mysqli_real_escape_string($conn, $_POST['course_number']);

    // form validation: ensure that the form is correctly filled ...
    // by adding (array_push()) corresponding error unto $errors array
    if (empty($course_name)) {
        array_push($errors, "Course Name is required");
    }
    if (empty($course_number)) {
        array_push($errors, "Course Number is required");
    }

    $course_add = "INSERT INTO course (course_name, course_number) VALUES('$course_name', '$course_number');";

    if (mysqli_query($conn, $course_add)) {
        array_push($success, "Course added Successful");
        // clear variables
        $course_name = $course_number = "";
    } else {
        array_push($errors, "Error adding course: ", mysqli_error($conn));
    }
}

// UPDATE
if (isset($_POST['update_course'])) {

    $id = $_GET['update_id'];

    // receive all input values from the form
    $course_name = mysqli_real_escape_string($conn, $_POST['course_name']);
    $course_number = mysqli_real_escape_string($conn, $_POST['course_number']);

    // form validation: ensure that the form is correctly filled ...
    // by adding (array_push()) corresponding error unto $errors array
    if (empty($course_name)) {
        array_push($errors, "Course Name is required");
    }
    if (empty($course_number)) {
        array_push($errors, "Course Number is required");
    }

    $update = "UPDATE course set course_name = '$course_name', course_number = '$course_number' WHERE id$id ='$id'";

    if (mysqli_query($conn, $update)) {
        array_push($success, "Update Successful");
        // clear variables
        $course_name = $course_number = "";
    } else {
        array_push($errors, "Error updating course: ", mysqli_error($conn));
    }
}

// DELETE
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $query = "DELETE FROM course WHERE id$id='$id'";
    if (mysqli_query($conn, $query)) {
        array_push($success, "Delete successful");
    } else {
        array_push($errors, "Delete error: " . mysqli_error($conn));
    }
}

$query = "SELECT * FROM course ORDER BY course_name ASC";
$results = mysqli_query($conn, $query);

?>

<div class="content-body">
    <?php if (isset($_GET['delete_view']))
        display_success();
    display_error();
    ?>
    <p><b>Courses</b></p>
    <table>
        <thead>
            <tr>
                <?php isAdmin() ? print '<th>Course ID</th>' : ''; ?>
                <th>Course ID</th>
                <th>Course Name</th>
                <th>Course Number</th>
                <?php isAdmin() ? print '<th colspan="2">Action</th>' : ''; ?>

            </tr>
        </thead>
        <tbody>
            <?php
            while ($users = mysqli_fetch_assoc($results)) {
                $id = $users['id$id'];
                $course_name = $users['course_name'];
                $course_number = $users['course_number'];
            ?>
                <tr>
                    <?php if (isAdmin()) {
                        echo '<td>' . $id . '</td>';
                    } ?>
                    <td><?php echo $course_name ?></td>
                    <td><?php echo $course_number ?></td>
                    <?php if (isAdmin()) {
                        echo '<td><a href="?page=courses&update_view=true&update_id=' . $id . '">Update</a></td>';
                        echo '<td><a href="?page=courses&delete_view=true&delete_id=' . $id . '">Delete</a></td>';
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
    <?php } ?>

    <?php if (isset($_GET['add_view'])) { ?>
        <hr>
        <div class="form-container">
            <form class="form-body" action="" method="post">
                <?php echo display_success(); ?>
                <?php echo display_error(); ?>
                <div class="form-input">
                    <p><b>Add a course</b></p>
                    <label>Course Name</label>
                    <span><input type="text" name="course_name"></span>
                </div>
                <div class="form-input">
                    <label>Course Number</label>
                    <span><input type="number" name="course_number"> </span>
                </div>
                <div class="form-submit">
                    <input type="submit" name="courses_add" value="Add">
                </div>
            </form>
        </div>

    <?php } ?>

    <?php if (isset($_GET['update_view'])) { ?>

        <?php
        $id = $_GET['update_id'];
        $query = "SELECT * FROM course WHERE id$id='$id'";
        $results = mysqli_query($conn, $query);

        while ($row = mysqli_fetch_assoc($results)) {
            $id = $row['id$id'];
            $course_name = $row['course_name'];
            $course_number = $row['course_number'];
        }
        ?>

        <hr>
        <div class="form-container">
            <form class="form-body" action="" method="post">
                <?php echo display_success(); ?>
                <?php echo display_error(); ?>
                <div class="form-input">
                    <p><b>Update Course</b></p>
                    <label>Course ID</label>
                    <span><b><?= $id ?></b></span>
                </div>
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

</div>