<html>
<head>
<title>JavaScript/PHP Submit Test</title>
</head>
<body>
<?php
	
	if (isset($_POST['Submit'])) {
		
		$first_name = $_POST['fname'];
		$last_name = $_POST['lname'];
		$country = $_POST['country'];

		if (!empty($first_name) && !empty($last_name) && !empty($country)) {
			echo "Hello, " . $first_name . " " . $last_name . " From " . $country;
			$first_name = $last_name = $country = "";
		} else {
			echo "Missing data. Can't perform Server Side processing";
		}
	}

?>
<script>

function validateInput() {
	
	var first_name = document.getElementById("fname").value;
	var last_name = document.getElementById("lname").value;
	
	if (first_name == '') {
		alert("Please enter your first name");
		document.getElementById("fname").focus();
		return false;
	} else if (last_name == '') {
		alert("Please enter your last name");
		document.getElementById("lname").focus();
		return false;
	} else {
		return true;
	}
		
}

</script>

<form class="form-body" action="" method="post" onSubmit="return validateInput()">

	<div>
		<select name='country'>
			<option value="Canada" selected>Canada</option>
			<option value="USA">USA</option>
			<option value="Greece">Greece</option>
		</select>
	<div>

	<label for="fname">First name:</label><br>
	<input type="text" id="fname" name="fname" value=""><br>
	<label for="lname">Last name:</label><br>
	<input type="text" id="lname" name="lname" value=""><br>

	<div class="form-group">		
		<input type="submit" name="Submit" value="Submit" />
	</div>

</form> 


</body>
</html>