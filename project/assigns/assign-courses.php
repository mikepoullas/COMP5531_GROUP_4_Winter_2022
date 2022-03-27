<?php

$user_id = $course_id = "";

/*******************************************************
 * ADD SQL
 ********************************************************/

if (isset($_POST['assign'])) {

    if (empty($_POST['user_id'])) {
        array_push($errors, "Please select a user");
    } else {
        $user_id = mysqli_real_escape_string($conn, $_POST['user_id']);
    }

    if (empty($_POST['course_id'])) {
        array_push($errors, "Please select a course");
    } else {
        $course_id = mysqli_real_escape_string($conn, $_POST['course_id']);
    }

    $check = "SELECT * FROM user_course WHERE user_id = '$user_id' AND course_id = '$course_id'";
    if (mysqli_num_rows(mysqli_query($conn, $check)) > 0) {
        array_push($errors, "User already assigned to this course");
    }

    if (count($errors) == 0) {
        $add = "INSERT INTO user_course (user_id, course_id) VALUES('$user_id', '$course_id');";

        if (mysqli_query($conn, $add)) {
            array_push($success, "User assigned to course Successfully");
            // clear variables
            $user_id = $course_id = "";
        } else {
            array_push($errors, "Adding Error: " . mysqli_error($conn));
        }
    }
}

/*******************************************************
 * UPDATE SQL
 ********************************************************/

if (isset($_GET['update_view'])) {

    if (empty($_POST['user_id'])) {
        array_push($errors, "Please select a user");
    } else {
        $user_id = mysqli_real_escape_string($conn, $_POST['user_id']);
    }

    if (empty($_POST['course_id'])) {
        array_push($errors, "Please select a course");
    } else {
        $course_id = mysqli_real_escape_string($conn, $_POST['course_id']);
    }

    $check = "SELECT * FROM user_course WHERE user_id = '$user_id' AND course_id = '$course_id'";
    if (mysqli_num_rows(mysqli_query($conn, $check)) > 0) {
        array_push($errors, "User already assigned to this course");
    }

    if (count($errors) == 0) {
        $update = "UPDATE user_course set user_id = '$user_id', course_id = '$course_id'
                    WHERE user_id ='$id' AND course_id = '$course_id'";

        if (mysqli_query($conn, $update)) {
            array_push($success, "Updated Successfully");
            // clear variables
            $user_id = $course_id = "";
        } else {
            array_push($errors, "Error: " . mysqli_error($conn));
        }
    }
}

/*******************************************************
 * DELETE SQL
 ********************************************************/

if (isset($_GET['delete_view'])) {

    $user_id = mysqli_real_escape_string($conn, $_GET['user_id']);
    $course_id = mysqli_real_escape_string($conn, $_GET['course_id']);
    $delete = "DELETE FROM user_course WHERE user_id='$user_id' AND course_id='$course_id'";
    if (mysqli_query($conn, $delete)) {
        array_push($success, "Delete successful");
    } else {
        array_push($errors, "Delete error: " . mysqli_error($conn));
    }
}
?>

<!-- Table Section
Always visible and shows delete error if delete_view is set true -->

<div class="content-body">

    <?php
    if (isset($_GET['delete_view'])) {
        display_success();
        display_error();
    }

    $query = "SELECT * FROM user_course as uc
    JOIN users as u ON u.user_id = uc.user_id
    JOIN course as c ON c.course_id = uc.course_id
    ORDER BY u.user_id ASC";
    $results = mysqli_query($conn, $query);

    ?>

    <p><b>Users assigned to Courses</b></p>
    <br>

    <table>
        <thead>
            <tr>
                <th>User Name</th>
                <th>Role Name</th>
                <th>Course Name</th>
                <th colspan="2">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($results as $row) {
                $user_id = $row['user_id'];
                $course_id = $row['course_id'];
                $first_name = $row['first_name'];
                $last_name = $row['last_name'];
                $course_name = $row['course_name'];
                $role_id = $row['role_id'];
                $role_name = mysqli_fetch_assoc(get_records_where('roles', 'role_id', $role_id))['role_name'];
            ?>
                <tr>
                    <td><?php echo $first_name . " " . $last_name ?></td>
                    <td><?php echo $role_name ?></td>
                    <td><?php echo $course_name ?></td>
                    <td><a href="?page=assign-courses&update_view=true&user_id=<?= $user_id ?>&course_id=<?= $course_id ?>">Update</a></td>
                    <td><a href="?page=assign-courses&delete_view=true&user_id=<?= $user_id ?>&course_id=<?= $course_id ?>">Delete</a></td>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>

    <?php if (isAdmin()) { ?>
        <a href="?page=assign-courses&add_view=true">
            <button>Add New</button>
        </a>
    <?php } ?>

    <!-- Add Section
    Visible if add_view is set to true -->

    <?php if (isset($_GET['add_view'])) { ?>

        <div class="form-container">
            <form class="form-body" action="" method="post">

                <?php echo display_success(); ?>
                <?php echo display_error(); ?>

                <div class="form-input">
                    <p>Users</p>
                    <div class="scroll-list">
                        <select name=user_id>
                            <option value="" selected hidden>Choose a User</option>
                            <?php
                            $query = "SELECT * FROM users WHERE role_id != 1";
                            $users = mysqli_query($conn, $query);
                            foreach ($users as $user) {
                                $user_id = $user['user_id'];
                                $first_name = $user['first_name'];
                                $last_name = $user['last_name'];
                                $role_id = $user['role_id'];
                                $role_name = mysqli_fetch_assoc(get_records_where('roles', 'role_id', $role_id))['role_name'];
                                echo "<option value='$user_id'>$first_name $last_name ($role_name)</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="form-input">
                    <p>Courses</p>
                    <div class="scroll-list">
                        <select name=course_id>
                            <option value="" selected hidden>Choose a Course</option>
                            <?php
                            $query = "SELECT * FROM course";
                            $courses = mysqli_query($conn, $query);
                            foreach ($courses as $course) {
                                $course_id = $course['course_id'];
                                $course_name = $course['course_name'];
                                echo "<option value='$course_id'>$course_name</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="form-submit">
                    <input type="submit" name="assign" value="Assign">
                </div>
            </form>
        </div>

    <?php } ?>

    <!-- Update Section
    Visible if update_view is set to true -->

    <?php if (isset($_GET['update_view'])) { ?>

        <?php
        $user_id = mysqli_real_escape_string($conn, $_POST['user_id']);
        $course_id = mysqli_real_escape_string($conn, $_POST['course_id']);

        $query = "SELECT * FROM user_course as uc
        JOIN users as u ON u.user_id = uc.user_id
        JOIN course as c ON c.course_id = uc.course_id
        WHERE u.user_id='$user_id' AND c.course_id = '$course_id'";
        $results = mysqli_query($conn, $query);

        while ($row = mysqli_fetch_assoc($results)) {
            $id = $row['section_id'];
            $section_name = $row['section_name'];
            $update_course_id = $row['course_id'];
        }
        ?>

        <div class="form-container">
            <form class="form-body" action="" method="post">

                <?php display_success(); ?>
                <?php display_error(); ?>

                <div class="form-input">
                    <p>Users</p>
                    <div class="scroll-list">
                        <select name=user_id>
                            <option value="" selected hidden>Choose a User</option>
                            <?php
                            $query = "SELECT * FROM users WHERE role_id != 1";
                            $users = mysqli_query($conn, $query);
                            foreach ($users as $user) {
                                $user_id = $user['user_id'];
                                $first_name = $user['first_name'];
                                $last_name = $user['last_name'];
                                $role_id = $user['role_id'];
                                $role_name = mysqli_fetch_assoc(get_records_where('roles', 'role_id', $role_id))['role_name'];
                                echo "<option value='$user_id'>$first_name $last_name ($role_name)</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="form-input">
                    <p>Courses</p>
                    <div class="scroll-list">
                        <select name=course_id>
                            <option value="" selected hidden>Choose a Course</option>
                            <?php
                            $query = "SELECT * FROM course";
                            $courses = mysqli_query($conn, $query);
                            foreach ($courses as $course) {
                                $course_id = $course['course_id'];
                                $course_name = $course['course_name'];
                                echo "<option value='$course_id'>$course_name</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="form-submit">
                    <input type="submit" name="assign" value="Update">
                </div>
            </form>
        </div>

    <?php } ?>

</div>