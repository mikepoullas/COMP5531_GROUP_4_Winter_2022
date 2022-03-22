<?php

// initializing variables
$course_name = $course_number = "";

if (isset($_POST['courses_add'])) {

    // Add Course

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

?>

<div class="form-container">

    <form class="form-body" action="" method="post">

        <?php echo display_success(); ?>
        <?php echo display_error(); ?>

        <div class="form-input">
            <p><b>Add a course</b></p>
            <label>Course Name</label>
            <span><input type="text" name="course_name" value='<?= $course_name ?>'></span>
        </div>

        <div class="form-input">
            <label>Course Number</label>
            <span><input type="number" name="course_number" value='<?= $course_number ?>'> </span>
        </div>
        <div class="form-submit">
            <input type="submit" name="courses_add" value="CourseAdd">
        </div>
    </form>
</div>