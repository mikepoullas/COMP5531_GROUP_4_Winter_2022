<script>
	function validateStudentToCourse() {

		var student_id, course_id, can_enroll;

		student_id = document.getElementById("student").value;
		course_id = document.getElementById("course").value;

		if (student_id == '') {
			alert("Please select a Student from the list.");
		} else if (course_id == '') {
			alert("Please select a Course from the list.");
		} else if (student_id == '' && course_id == '') {
			alert("You must select a Student and a Course from the list.");
		}

	}

	function getStudentDetails() {

		var user_id;
		user_id = document.getElementById("student").value;

		//alert("User: " + user_id);

	}
</script>


<?php

// DELETE
if (isset($_GET['remove_course'])) {

	$user_id = mysqli_real_escape_string($conn, $_GET['delete_id']);
	$course_id = mysqli_real_escape_string($conn, $_GET['course_id']);
	$table_name = "";

	//Delete User from Student, TA or Professor table accordingly first to avoid constraint issue

	$delete = "DELETE FROM user_course WHERE user_id = '$user_id' and course_id = '$course_id'";

	if (!mysqli_query($conn, $delete)) {
		array_push($errors, "Error deleting course " . $course_id . " for user " . $course_id . " from user_course table", mysqli_error($conn));
	}

	if (mysqli_query($conn, $delete)) {
		array_push($success, "Course successfully removed.");
		// clear variables
		$id = $role_id = $table_name = "";
	} else {
		array_push($errors, "Delete user error: " . mysqli_error($conn));
	}
}
?>

<div>

	<form class="form-body" action="" method="POST">

		<h2>Assign Student to a Course</h2>

		<div class="form-input">
			<b>Select a student</b>
			<div class="scroll-list">
				<span>
					<select size="5" onclick="getStudentDetails()" name="student" id="student">
						<optgroup label="Student ID | Lastname, Firstname">
							<?php

							$query = "SELECT s.user_id as uid, s.student_id as sid, u.first_name as fn, u.last_name as ln 
										FROM student as s,users as u 
										WHERE s.user_id = u.user_id and u.role_id = 4 
										ORDER BY u.last_name ";

							$students = mysqli_query($conn, $query);

							if (!$students) {
								array_push($errors, "Error with Student and User databases: ", mysqli_error($conn));
							}

							foreach ($students as $student) {
								$user_id = $student['uid'];
								$student_id = $student['sid'];
								$first_name = $student['fn'];
								$last_name = $student['ln'];
								echo "<option value='" . $user_id . "'>" . $student_id . ' | ' . $last_name . ', ' . $first_name . "</option>";
							}
							?>
					</select>
				</span>
			</div>
		</div>

		<div class="form-submit">
			<input type="submit" name="enrollment" value="List Enrollment">
		</div>

		<?php
		// List Enrollment
		if (isset($_POST['enrollment'])) {

			$nothing_to_display = True;

			if (!empty($_POST['student'])) {
				$user_id = $_POST['student'];
			} else {
				array_push($errors, "You must select a student from the list.");
			}

			if (count($errors) == 0) {
				$query = "SELECT * FROM users as u
		JOIN user_course as uc
		ON u.user_id = uc. user_id
		JOIN course as c
		ON uc.course_id = c.course_id
		JOIN course_section as cs
		ON c.course_id = cs.course_id
		WHERE u.user_id = '$user_id'
		ORDER BY u.user_id ASC";

				$user_info = mysqli_query($conn, $query);

				if (!$user_info) {
					array_push($errors, "Error with retrieving user info: ", mysqli_error($conn));
					$nothing_to_display = True;
				} else {
					$nothing_to_display = False;
				}

				if (mysqli_num_rows($user_info) < 1) {
					$nothing_to_display = True;
				}

				echo "<div class='user-info-content'>";
				if ($nothing_to_display) {
					echo "<h2>Not enrolled in any courses. A maximum of five allowed.<h2>";
				} else {
					echo "<h2>Currently enrolled in</h2><br>";

					echo "<table>";
					echo "<thead>";
					echo "<tr>";
					echo "<th>Course Number</th>";
					echo "<th>Course Name</th>";
					echo "<th>Section</th>";
					echo "<th colspan='2'>Action</th>";
					echo "</tr>";
					echo "</thead>";
					echo "<tbody>";
					foreach ($user_info as $user) {
						echo "<tr>";
						echo "<td>" . $user['course_number'] . "</td>";
						echo "<td>" . $user['course_name'] .  "</td>";
						echo "<td>" . $user['section_name'] . "</td>";
						echo '<td><a href="?page=assign-students&remove_course=true&delete_id=' . $user_id . '&course_id=' . $user['course_id'] . '">Remove</a></td>';
						echo "</tr>";
					}
					echo "</tbody>";
					echo "</table>";
				}
				echo "</div>";
			}
		}
		?>
		<div class="form-input">

			<?php
			echo display_success();
			echo display_error();
			?>

			<b>Add a course</b>
			<div class="scroll-list">
				<span>
					<select size="5" name="course" id="course">
						<optgroup label="Course Number | Course Name | Course Section">
							<?php

							$query = "SELECT c.course_id as id, c.course_number as cnum, c.course_name as cname, cs.section_name as csname FROM course as c, course_section as cs where c.course_id = cs.course_id order by cname, csname  ";
							$courses = mysqli_query($conn, $query);

							if (!$courses) {
								array_push($errors, "Error with Courses and Course Section databases: ", mysqli_error($conn));
							}

							foreach ($courses as $course) {
								$course_id = $course['id'];
								$course_number = $course['cnum'];
								$course_name = $course['cname'];
								$cours_section_name = $course['csname'];
								echo "<option value='" . $course_id . "'>" . $course_number . ' | ' . $course_name . ' | ' . $cours_section_name . "</option>";
							}
							?>
					</select>
				</span>
			</div>
		</div>

		<div class="form-submit">
			<input type="submit" name="enroll" value="Enroll">
		</div>

		<?php
		// Enroll Student in a Course
		if (isset($_POST['enroll'])) {

			if (!empty($_POST['student'])) {
				$user_id = $_POST['student'];
			} else {
				array_push($errors, "You must select a student from the list.");
			}

			if (!empty($_POST['course'])) {
				$course_id = $_POST['course'];
			} else {
				array_push($errors, "You must select a course from the list.");
			}

			if (count($errors) == 0) {

				$query = "INSERT INTO user_course (user_id, course_id) VALUES ('$user_id', '$course_id');";

				$add = mysqli_query($conn, $query);

				if (mysqli_errno($conn) == 1062) {
					array_push($errors, "Student already enrolled in this course!");
				} else {

					if (mysqli_query($conn, $add)) {
						array_push($success, "Student was added to course.");
						// clear variables
						$user_id = $course_id = "";
					} else {
						array_push($errors, "Error adding student to user_course table: ", mysqli_error($conn));
					}
				}
			}
		}
		?>

		<?php
		echo display_success();
		echo display_error();
		?>

	</form>
</div>