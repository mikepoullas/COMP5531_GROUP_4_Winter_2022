<?php

// initializing variables
$id = $section_name = $course_id = "";

// ADD
if (isset($_POST['add_section'])) {

    // receive all input values from the form
    $section_name = mysqli_real_escape_string($conn, $_POST['section_name']);
    $course_id = mysqli_real_escape_string($conn, $_POST['course_id']);

    // form validation: ensure that the form is correctly filled ...
    // by adding (array_push()) corresponding error unto $errors array
    if (empty($section_name)) {
        array_push($errors, "Section is required");
    }
    if (empty($course_id)) {
        array_push($errors, "Course is required");
    }

    if (count($errors) == 0) {
        $add = "INSERT INTO course_section (section_name, course_id) VALUES('$section_name', '$course_id');";

        if (mysqli_query($conn, $add)) {
            array_push($success, "Section added Successful");
            // clear variables
            $section_name = $course_id = "";
        } else {
            array_push($errors, "Error adding sections: ", mysqli_error($conn));
        }
    }
}

// UPDATE
if (isset($_POST['update_section'])) {

    $id = mysqli_real_escape_string($conn, $_GET['update_id']);

    // receive all input values from the form
    $section_name = mysqli_real_escape_string($conn, $_POST['section_name']);
    $course_id = mysqli_real_escape_string($conn, $_POST['course_id']);

    // form validation: ensure that the form is correctly filled ...
    // by adding (array_push()) corresponding error unto $errors array
    if (empty($section_name)) {
        array_push($errors, "Section is required");
    }
    if (empty($course_id)) {
        array_push($errors, "Course is required");
    }

    if (count($errors) == 0) {
        $update = "UPDATE course_section set section_name = '$section_name', course_id = '$course_id' WHERE section_id ='$id'";

        if (mysqli_query($conn, $update)) {
            array_push($success, "Update Successful");
            // clear variables
            $section_name = $course_id = "";
        } else {
            array_push($errors, "Error updating sections: ", mysqli_error($conn));
        }
    }
}

// DELETE
if (isset($_GET['delete_id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['delete_id']);
    $delete = "DELETE FROM course_section WHERE section_id='$id'";
    if (mysqli_query($conn, $delete)) {
        array_push($success, "Delete successful");
    } else {
        array_push($errors, "Delete error: " . mysqli_error($conn));
    }
}

?>

<div class="content-body">
    <?php if (isset($_GET['delete_view'])) {
        display_success();
        display_error();
    }

    $query = "SELECT * FROM course_section as cs JOIN course as c
            WHERE cs.course_id = c.course_id
            ORDER BY section_id ASC";
    $results = mysqli_query($conn, $query);

    ?>
    <p><b>Sections</b></p>
    <table>
        <thead>
            <tr>
                <?php isAdmin() ? print '<th>Section ID</th>' : ''; ?>
                <th>Section Name</th>
                <th>Course Name</th>
                <?php isAdmin() ? print '<th colspan="2">Action</th>' : ''; ?>

            </tr>
        </thead>
        <tbody>
            <?php
            while ($row = mysqli_fetch_assoc($results)) {
                $id = $row['section_id'];
                $section_name = $row['section_name'];
                $course_name = $row['course_name'];
            ?>
                <tr>
                    <?php if (isAdmin()) {
                        echo '<td>' . $id . '</td>';
                    } ?>
                    <td><?php echo $section_name ?></td>
                    <td><?php echo $course_name ?></td>
                    <?php if (isAdmin()) {
                        echo '<td><a href="?page=sections&update_view=true&update_id=' . $id . '">Update</a></td>';
                        echo '<td><a href="?page=sections&delete_view=true&delete_id=' . $id . '">Delete</a></td>';
                    } ?>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>

    <?php if (isAdmin()) { ?>
        <a href="?page=sections&add_view=true">
            <button>Add Section</button>
        </a>
    <?php } ?>

    <?php if (isset($_GET['add_view'])) { ?>
        <hr>
        <div class="form-container">
            <form class="form-body" action="" method="post">
                <?php echo display_success(); ?>
                <?php echo display_error(); ?>
                <div class="form-input">
                    <p><b>Add a Section</b></p>
                    <label>Section Name</label>
                    <span><input type="text" name="section_name"></span>
                </div>
                <div class="form-input">
                    <label for="course">Choose a Course</label>
                    <span>
                        <select name="course_id">
                            <option value="" selected hidden>Choose a Course</option>
                            <?php
                            $courses = get_table_array('course');
                            foreach ($courses as $row) {
                                $course_id = $row['course_id'];
                                $course_name = $row['course_name'];
                                echo "<option name=course_id value='$course_id'>$course_name</option>";
                            }
                            ?>
                        </select>
                    </span>
                </div>
                <div class="form-submit">
                    <input type="submit" name="add_section" value="Add">
                </div>
            </form>
        </div>

    <?php } ?>

    <?php if (isset($_GET['update_view'])) { ?>

        <?php
        $id = mysqli_real_escape_string($conn, $_GET['update_id']);
        $query = "SELECT * FROM course_section WHERE section_id='$id'";
        $results = mysqli_query($conn, $query);

        while ($row = mysqli_fetch_assoc($results)) {
            $id = $row['section_id'];
            $section_name = $row['section_name'];
            $update_course_id = $row['course_id'];
        }
        ?>

        <hr>
        <div class="form-container">
            <form class="form-body" action="" method="post">
                <?php echo display_success(); ?>
                <?php echo display_error(); ?>
                <div class="form-input">
                    <p><b>Update Section</b></p>
                    <label>Section ID</label>
                    <span><b><?= $id ?></b></span>
                </div>
                <div class="form-input">
                    <label>Section Name</label>
                    <span><input type="text" name="section_name" value='<?= $section_name ?>'></span>
                </div>
                <div class="form-input">
                    <label for="course">Choose a Course</label>
                    <span>
                        <select name="course_id">
                            <?php
                            $courses = get_table_array('course');
                            foreach ($courses as $row) {
                                $course_id = $row['course_id'];
                                $course_name = $row['course_name'];
                                if ($update_course_id == $course_id) {
                                    echo "<option name=course_id value='$course_id' selected>$course_name</option>";
                                } else {
                                    echo "<option name=course_id value='$course_id'>$course_name</option>";
                                }
                            }
                            ?>
                        </select>
                    </span>
                </div>
                <div class="form-submit">
                    <input type="submit" name="update_section" value="Update">
                </div>
            </form>
        </div>

    <?php } ?>

</div>