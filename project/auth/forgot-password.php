<?php
// Initialize the session
session_start();

require_once('../configs/config.php');
require_once('../helper/functions.php');

$email = $password = "";

if (isset($_POST['recover_password'])) {

    $email = mysqli_real_escape_string($conn, $_POST['email']);

    if (empty(trim($_POST["email"]))) {
        array_push($errors, "Email is required");
    }

    if (count($errors) == 0) {

        $username = mysqli_fetch_assoc(get_records_where('users', 'email', $email))['username'];
        $password = mysqli_fetch_assoc(get_records_where('users', 'email', $email))['password'];

        //DOESNOT WORK - need to setup local mail server
        if (mysqli_num_rows(get_records_where('users', 'email', $email)) == 1) {

            $to = $email;
            $subject = "Your Recovered Password";
            $message = "User info recovered\n\nusername: '$username' \npassword: '$password'";
            $headers = "From: shafiqimtiaz@hotmail.com";

            if (mail($to, $subject, $message, $headers)) {
                array_push($success, "Recovery mail sent, check email");
            } else {
                array_push($errors, "Recovery mail not sent, try again");
            }
        } else {
            array_push($errors, "Email not in database");
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/style.css">
    <title>Forgot Password</title>
</head>

<body>

    <header>
        <h1>Forgot Password ?</h1>
    </header>

    <main>

        <div class="form-container">

            <form class="form-body" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

                <?php echo display_error(); ?>
                <?php echo display_success(); ?>

                <div class="form-input">
                    <label>Email</label>
                    <span>
                        <input type="email" name="email">
                    </span>
                </div>

                <div class="form-submit">
                    <input type="submit" name="recover_password" value="Recover">
                    <br><br>
                    <a href="login.php">Back to login</a>
                </div>

            </form>
        </div>

    </main>

    <?php require("../includes/footer.php") ?>