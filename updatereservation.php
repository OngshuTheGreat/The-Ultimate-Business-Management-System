<?php
include("restaurant_config.php");
session_start();

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid reservation ID.");
}

$reservation_id = intval($_GET['id']);
$sql = "SELECT customer_name, email, phone_number, reservation_date, reservation_time, guests FROM reservations WHERE reservation_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $reservation_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Reservation not found.");
}

$reservation = $result->fetch_assoc();

$reservation_date = date('Y-m-d', strtotime($reservation['reservation_date']));
$reservation_time = date('H:i', strtotime($reservation['reservation_time']));

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $customer_name = $_POST['customer_name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $reservation_date = $_POST['reservation_date'];
    $reservation_time = $_POST['reservation_time'];
    $guests = $_POST['guests'];

    $formatted_time = DateTime::createFromFormat('H:i', $reservation_time);
    if (!$formatted_time) {
        die("Invalid time format.");
    }

    $update_sql = "UPDATE reservations SET customer_name = ?, email = ?, phone_number = ?, reservation_date = ?, reservation_time = ?, guests = ? WHERE reservation_id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ssssisi", $customer_name, $email, $phone_number, $reservation_date, $formatted_time->format('H:i'), $guests, $reservation_id);

    if ($update_stmt->execute()) {
        echo "<script>alert('Reservation updated successfully.');</script>";
        header("Location: reservations.php");
        exit();
    } else {
        echo "<script>alert('Failed to update reservation.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Reservation</title>
    <link rel="icon" href="images/Skill++-01.png">
    <link rel="stylesheet" href="css/updatereservation.css">
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
            <h1>Update Reservation</h1>
        </header>

        <form method="post">
            <label for="customer_name">Customer Name</label>
            <input type="text" id="customer_name" name="customer_name" value="<?php echo htmlspecialchars($reservation['customer_name']); ?>" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($reservation['email']); ?>" required>

            <label for="phone_number">Phone Number</label>
            <input type="text" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($reservation['phone_number']); ?>" required>

            <label for="reservation_date">Reservation Date</label>
            <input type="date" id="reservation_date" name="reservation_date" value="<?php echo htmlspecialchars($reservation_date); ?>" required>

            <label for="reservation_time">Time</label>
            <input type="time" id="reservation_time" name="reservation_time" value="<?php echo htmlspecialchars($reservation_time); ?>" required>

            <label for="guests">Guests</label>
            <input type="number" id="guests" name="guests" value="<?php echo htmlspecialchars($reservation['guests']); ?>" required>

            <button type="submit">Update Reservation</button>
        </form>
    </div>
</body>

</html>

<?php
mysqli_close($conn);
?>