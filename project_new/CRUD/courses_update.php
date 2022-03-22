<?php

// initializing variables
$course_id = $course_name = $course_number = "";

$id = $_GET['id'];
$query = "SELECT * FROM course WHERE course_id='$id'";
$results = mysqli_query($conn, $query);

while ($row = mysqli_fetch_assoc($results)) {
    $course_id = $row['course_id'];
    $course_name = $row['course_name'];
    $course_number = $row['course_number'];

}

if (isset($_POST['update_course'])) {
    // COURSE UPDATE

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

    $user_update = "UPDATE course set course_name = '$course_name', course_number = '$course_number' WHERE course_id ='$id'";
	
	if (mysqli_query($conn, $user_update)) {
		array_push($success, "Update Successful");
	} else {
		array_push($errors, "Error updating course: ", mysqli_error($conn));
	}
 }

?>

<div class="form-container">

    <form class="form-body" action="" method="post">

        <?php echo display_success(); ?>
        <?php echo display_error(); ?>

        <div class="form-input">
            <p><b>Update Course</b></p>
            <label>Course ID</label>
            <span><b><?= $course_id ?></b></span>
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
            <input type="submit" name="update_course" value="UpdateCourse">
        </div>
    </form>
</div>