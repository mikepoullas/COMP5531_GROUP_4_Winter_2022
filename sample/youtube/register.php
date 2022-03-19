<?php include('server.php') ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Registration</title>
</head>

<body>
    <div class="header">
        <h2>Registration</h2>
    </div>

    <form method="post" action="register.php">
        <?php echo display_error(); ?>

        <div class="input-group">
            <label>First Name</label>
            <input type="text" name="firstname" value="<?php echo $first_name; ?>">
        </div>

        <div class="input-group">
            <label>Last Name</label>
            <input type="text" name="lastname" value="<?php echo $last_name; ?>">
        </div>

        <div class="input-group">
            <label>Date of Birth</label>
            <input type="date" name="dob" value="<?php echo $dob; ?>">
        </div>

        <div class="input-group">
            <label>Email</label>
            <input type="email" name="email" value="<?php echo $email; ?>">
        </div>

        <div class="input-group">
            <label>Username</label>
            <input type="text" name="username" value="<?php echo $username; ?>">
        </div>

        <div class="input-group">
            <label>Password</label>
            <input type="password" name="password_1">
        </div>

        <div class="input-group">
            <label>Confirm password</label>
            <input type="password" name="password_2">
        </div>

        <div class="input-group">
            <label for="roles">Choose a Role:</label>
            <select id="roles" name="role">
                <option value="1">Admin</option>
                <option value="2" selected>Student</option>
                <option value="3">Teaching Assistant</option>
                <option value="4">Professor</option>
            </select>
        </div>

        <div class="input-group">
            <button type="submit" class="btn" name="reg_user">Register</button>
        </div>

        <p>
            Already a member? <a href="login.php">Sign in</a>
        </p>

    </form>
</body>

</html>