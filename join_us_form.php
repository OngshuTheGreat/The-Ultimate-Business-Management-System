<?php

include("skill_config.php");
session_start();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join Request Form</title>
    <link rel="icon" href="images/Skill++-01.png">
    <link rel="stylesheet" href="css/join_us_form.css">
</head>

<body>
    <div class="form-container">

        <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">

            <h3>Fill Up The Form</h3>

            <input type="text" name="name" placeholder="Enter business name here" required>

            <input type="email" name="email" placeholder="Enter business email here" required>

            <input type="text" name="phone" placeholder="Enter phone number here" required>

            <input type="text" name="address" placeholder="Enter address here" required>

            <input type="text" name="type" placeholder="Enter business type here" required>

            <input type="submit" name="request" value="Send Request" class="form-btn">

        </form>
    </div>
</body>

</html>

<?php

$name = filter_input(INPUT_POST, "name", FILTER_SANITIZE_SPECIAL_CHARS);

$email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);

$phone = filter_input(INPUT_POST, "phone", FILTER_SANITIZE_NUMBER_INT);

$address = filter_input(INPUT_POST, "address", FILTER_SANITIZE_SPECIAL_CHARS);

$type = filter_input(INPUT_POST, "type", FILTER_SANITIZE_SPECIAL_CHARS);


if (isset($_POST["request"])) {
    if (isset($name, $email, $phone, $address, $type)) {
        $sql = "INSERT INTO join_req (name, email, phone, address, type)
            VALUES ('$name', '$email', '$phone', '$address', '$type')";

        mysqli_query($conn, $sql);
        header('Location: request_successful.php');
        echo '<script>alert("Request Succesfull");</script>';
        session_reset();
    } else {
        echo '<script>alert("Please fill all the information");</script>';
    }
}

mysqli_close($conn);

?>