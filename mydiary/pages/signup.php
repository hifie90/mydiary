<?php
require_once('../config/db.php'); 

$email = '';
$password = '';
$about_user = '';
$msg = '';

if (isset($_POST['signup'])) {
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $about_user = mysqli_real_escape_string($con, $_POST['about_user']);

    // Ensure password is hashed for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // SQL query to check if the user already exists
    $sql_fetch = mysqli_query($con, "SELECT * FROM users WHERE email = '$email'");
    if (mysqli_num_rows($sql_fetch) > 0) {
        $msg = "<div class='msg'>
                    <p>You have already signed up. Please <a href='login.php'>Login Now!</a></p>
                </div>";
    } else {
        // Insert the new user into the database
        $insert_query = "INSERT INTO users (email, password, about_user) VALUES ('$email', '$hashed_password', '$about_user')";
        if (mysqli_query($con, $insert_query)) {
            header("Location: login.php");
            exit(); // Make sure to exit after redirection
        } else {
            $msg = "<div class='msg'>
                        <p>Error: Could not execute $insert_query. " . mysqli_error($con) . "</p>
                    </div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup - My Diary</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet"href="../style/signup.css">
</head>
<body>

<!-- Signup Form -->
<div class="background-container">
    <div class="row justifyy w-100">
        <div class="col-md-6 col-lg-4">
            <div class="headding">
                <h1>My Diary</h1>
                <p>Store your thoughts permanently and securely</p>
                <h3>Interested? Sign Up Now.</h3>
                <?php echo $msg; ?>
            </div>
            <div class="form_body">
                <form method="POST" action="">
                    <div class="input">
                        <input type="email" name="email" placeholder="Your Email" required>
                    </div>
                    <div class="input">
                        <input type="password" name="password" placeholder="Password" required>
                    </div>
                    <div class="input">
                        <textarea name="about_user" placeholder="Tell us about yourself" required></textarea>
                    </div>
                    <div class="button_sing d-flex">
                        <button type="submit" name="signup">Signup Now</button>
                        <a href="login.php">Login Now</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>
