<?php
include '../config/db.php'; 
session_start();

$email = '';
$password = '';
$msg = '';

if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);

    // Fetch the user from the database
    $sql_fetch = mysqli_query($con, "SELECT * FROM users WHERE email = '$email'");
    if (mysqli_num_rows($sql_fetch) > 0) {
        $row = mysqli_fetch_assoc($sql_fetch);
        if (password_verify($password, $row['password'])) {
    
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['email'] = $row['email'];
            header("Location: index.php");
            exit();
        } else {
            $msg = "<div class='msg'>
                        <p>Incorrect password. Please try again.</p>
                    </div>";
        }
    } else {
        $msg = "<div class='msg'>
                    <p>No account found with that email. Please <a href='signup.php'>Sign Up</a>.</p>
                </div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - My Diary</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet"href="../style/login.css">
</head>
<body>

<!-- Login Form -->
<div class="background-container">
    <div class="row justifyy w-100">
        <div class="col-md-6 col-lg-4">
            <div class="headding">
                <h1>My Diary</h1>
                <p>Store your thoughts permanently and securely</p>
                <h3>Login</h3>
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
                    <div class="button_sing d-flex">
                        <button type="submit" name="login">Login</button>
                        <a href="signup.php">Sign Up</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>
