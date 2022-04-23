<?php

// ADD
if (isset($_POST['add_section'])) {


    $section_name = mysqli_real_escape_string($conn, $_POST['section_name']);
    $course_id = mysqli_real_escape_string($conn, $_POST['course_id']);



    if (empty($section_name)) {
        array_push($errors, "Section is required");
    }
    if (empty($course_id)) {
        array_push($errors, "Course is required");
    }

    if (count($errors) == 0) {
        $add = "INSERT INTO section (section_name, course_id) VALUES('$section_name', '$course_id');";

        if (mysqli_query($conn, $add)) {
            array_push($success, "Section added Successful");
        } else {
            array_push($errors, "Error adding sections: ", mysqli_error($conn));
        }
    }
}

// UPDATE
if (isset($_POST['update_section'])) {

    $id = mysqli_real_escape_string($conn, $_GET['update_id']);


    $section_name = mysqli_real_escape_string($conn, $_POST['section_name']);
    $course_id = mysqli_real_escape_string($conn, $_POST['course_id']);



    if (empty($section_name)) {
        array_push($errors, "Section is required");
    }
    if (empty($course_id)) {
        array_push($errors, "Course is required");
    }

    if (count($errors) == 0) {
        $update = "UPDATE section SET section_name = '$section_name', course_id = '$course_id' WHERE section_id ='$id'";

        if (mysqli_query($conn, $update)) {
            array_push($success, "Update Successful");
        } else {
            array_push($errors, "Error updating sections: ", mysqli_error($conn));
        }
    }
}

// DELETE
if (isset($_GET['delete_id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['delete_id']);
    $delete = "DELETE FROM section WHERE section_id='$id'";
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

    $query = "SELECT * FROM section as s
JOIN course as c ON c.course_id = s.course_id
ORDER BY section_id ASC";
    $results = mysqli_query($conn, $query);

    ?>
    <h2>Sections</h2>
    <hr>
    <table>
        <thead>
            <tr>
                <th>Section Name</th>
                <th>Course Name</th>
                <?php isAdmin() ? print '<th colspan="2">Action</th>' : ''; ?>

            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($results as $row) {
                $id = $row['section_id'];
                $section_name = $row['section_name'];
                $course_name = $row['course_name'];
            ?>
                <tr>
                    <td><?= $section_name ?></td>
                    <td><?= $course_name ?></td>
                    <?php if (isAdmin()) {
                        echo '<td><a href="?page=sections&update_view=true&update_id=' . $id . '">Update</a></td>';
                        echo "<td><a href='?page=sections&delete_id=" . $id . "' onclick='return confirm(&quot;Are you sure you want to delete?&quot;)'>Delete Section</a></td>";
                    } ?>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <?php if (isAdmin()) { ?>
        <a href="?page=sections&add_view=true">
            <button>Add Section</button>
        </a>

        <?php if (isset($_GET['add_view'])) { ?>
            <hr>
            <div class="form-container">
                <form class="form-body" action="" method="POST">

                    <h3>Add Section</h3>
                    <div class="form-input">
                        <label>Section Name</label>
                        <span><input type="text" name="section_name"></span>
                    </div>

                    <div class="form-input">
                        <p>Course Name</p>
                        <div class="scroll-list">
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
                        </div>
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
            $query = "SELECT * FROM section WHERE section_id='$id'";
            $results = mysqli_query($conn, $query);

            foreach ($results as $row) {
                $id = $row['section_id'];
                $section_name = $row['section_name'];
                $update_course_id = $row['course_id'];
            }
            ?>

            <hr>
            <div class="form-container">
                <form class="form-body" action="" method="POST">

                    <h3>Update Section</h3>
                    <div class="form-input">
                        <label>Section Name</label>
                        <span><input type="text" name="section_name" value='<?= $section_name ?>'></span>
                    </div>
                    <div class="form-input">
                        <p>Course Name</p>
                        <div class="scroll-list">
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
                        </div>
                    </div>
                    <div class="form-submit">
                        <input type="submit" name="update_section" value="Update">
                    </div>
                </form>
            </div>

        <?php } ?>

    <?php } ?>

</div>