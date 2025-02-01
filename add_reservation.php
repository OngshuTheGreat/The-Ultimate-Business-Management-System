<?php
include("restaurant_config.php");
session_start();

if (isset($_POST['add'])) {

    $customer_name = filter_input(INPUT_POST, "customer_name", FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
    $phone_number = filter_input(INPUT_POST, "phone_number", FILTER_SANITIZE_SPECIAL_CHARS);
    $reservation_date = filter_input(INPUT_POST, "reservation_date", FILTER_SANITIZE_SPECIAL_CHARS);
    $reservation_time = filter_input(INPUT_POST, "reservation_time", FILTER_SANITIZE_SPECIAL_CHARS);
    $guests = filter_input(INPUT_POST, "guests", FILTER_SANITIZE_NUMBER_INT);

    if ($customer_name && $email && $phone_number && $reservation_date && $reservation_time && $guests) {

        $sql = "INSERT INTO reservations (customer_name, email, phone_number, reservation_date, reservation_time, guests)
                VALUES ('$customer_name', '$email', '$phone_number', '$reservation_date', '$reservation_time', '$guests')";

        if (mysqli_query($conn, $sql)) {
            header("Location: reservations.php");
        } else {
            echo '<script>alert("Error adding reservation: ' . mysqli_error($conn) . '");</script>';
        }
    } else {
        echo '<script>alert("Please fill all the fields!");</script>';
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Reservation</title>
    <link rel="icon" href="images/Skill++-01.png">
    <link rel="stylesheet" href="css/add_reservation.css">
</head>

<body>

    <div class="sidebar">
        <div class="logo">
            <a href="index.php"><img src="images/Skill++.png" alt="Logo" height="50px" width="120px"></a>
        </div>

        <div class="sidebar-options">
            <ul class="nav nav-pills">
                <li class="nav-item"><a href="index.php">Order Management</a></li>
                <li class="nav-item"><a href="orderlist.php">Previous Orders</a></li>
                <li class="nav-item"><a href="menu.php">Menu</a></li>
                <li class="nav-item"><a href="inventory.php">Inventory</a></li>
                <li class="nav-item"><a href="employeelist.php">Employee Management</a></li>
                <li class="nav-item"><a href="reservations.php">Reservations</a></li>
                <li class="nav-item"><a href="vendors.php">Vendor Management</a></li>
            </ul>

            <a href="logout.php">Logout</a>
        </div>
    </div>

    <div class="main-content">
        <header>
            <h1>Add Reservation</h1>
        </header>

        <div class="form-container">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <h3>Fill Up The Reservation Details</h3>

                <input type="text" name="customer_name" required placeholder="Enter customer name">

                <input type="email" name="email" required placeholder="Enter customer email">

                <input type="text" name="phone_number" required placeholder="Enter customer phone number">

                <label for="reservation_date">Reservation Date</label>
                <input type="date" name="reservation_date" required>

                <label for="reservation_time">Reservation Time</label>
                <input type="time" name="reservation_time" required>

                <input type="number" name="guests" required placeholder="Enter number of guests" min="1">

                <input type="submit" name="add" value="Add Reservation" class="form-btn">
            </form>
        </div>
    </div>

</body>

</html>