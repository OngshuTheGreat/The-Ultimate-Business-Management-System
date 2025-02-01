<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="icon" href="images/Skill++-01.png">
    <link rel="stylesheet" href="css/login_form.css">
</head>

<body>
    <nav class="navbar">
        <ul class="nav-links">
            <li><a href="#about">About Us</a></li>
            <li><a href="#contact">Contact Us</a></li>
        </ul>
    </nav>

    <div class="container">
        <div class="title-container">
            <h5>Welcome To The</h5>
            <h3>Ultimate Business</h3>
            <h3>Management System</h3>
            <button><a href="join_us_form.php" class="join-btn">Join Us</a></button>
        </div>

        <div class="form-container">
            <form action="login_form.php" method="post">

                <h3>Log In Here</h3>

                <input type="text" name="username" required placeholder="Enter your username here">

                <input type="password" name="password" required placeholder="Enter your password here">

                <input type="submit" name="submit" value="login" class="form-btn">

            </form>
        </div>
    </div>
</body>

</html>

<?php

if (isset($_POST["submit"])) {
    $user = $_POST["username"];
    $pass = $_POST["password"];

    if ($user == "admin" && $pass == "ongshu101") {
        $_SESSION["loggedin"] = true;
        header("Location: index.php");
    } else {
        echo '<script>alert("Invalid username or password.");</script>';
    }
}
?>