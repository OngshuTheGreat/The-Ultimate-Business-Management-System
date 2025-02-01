<?php
include("restaurant_config.php");
session_start();

// Check if search term is provided
$search_term = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

// Modify the query based on the search term
$sql = "SELECT reservation_id, customer_name, email, phone_number, reservation_date, reservation_time, guests, reservation_status 
        FROM reservations 
        WHERE reservation_status = 'active'";

if ($search_term) {
    $sql .= " AND (customer_name LIKE '%$search_term%' OR phone_number LIKE '%$search_term%')";
}

$result = mysqli_query($conn, $sql);
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

if (isset($_GET['done_reservation'])) {
    $reservation_id = intval($_GET['done_reservation']);
    $done_sql = "UPDATE reservations SET reservation_status = 'done' WHERE reservation_id = ?";
    $done_stmt = $conn->prepare($done_sql);
    $done_stmt->bind_param("i", $reservation_id);
    if ($done_stmt->execute()) {
        echo "<script>alert('Reservation marked as done.');</script>";
        header("Location: reservations.php");
        exit();
    } else {
        echo "<script>alert('Failed to mark reservation as done.');</script>";
    }
}

if (isset($_GET['remove_reservation'])) {
    $reservation_id = intval($_GET['remove_reservation']);
    $remove_sql = "DELETE FROM reservations WHERE reservation_id = ?";
    $remove_stmt = $conn->prepare($remove_sql);
    $remove_stmt->bind_param("i", $reservation_id);
    if ($remove_stmt->execute()) {
        echo "<script>alert('Reservation removed successfully.');</script>";
        header("Location: reservations.php");
        exit();
    } else {
        echo "<script>alert('Failed to remove reservation.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation List</title>
    <link rel="icon" href="images/Skill++-01.png">
    <link rel="stylesheet" href="css/reservations.css">
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
            <h1>Reservation List</h1>
        </header>

        <main>
            <div class="search-container">
                <form method="GET" class="search-container">
                    <input type="text" name="search" placeholder="Search Here" value="<?php echo htmlspecialchars($search_term); ?>" />
                    <button type="submit">Search</button>
            </div>

            <section>
                <h2>List of Reservations</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Reservation ID</th>
                            <th>Customer Name</th>
                            <th>Email</th>
                            <th>Phone Number</th>
                            <th>Reservation Date</th>
                            <th>Time</th>
                            <th>Guests</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                $reservation_id = intval($row['reservation_id']);
                                $reservation_date = date('F j, Y', strtotime($row['reservation_date']));
                                $time = date('h:i A', strtotime($row['reservation_time']));
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($reservation_id) . "</td>";
                                echo "<td>" . htmlspecialchars($row['customer_name']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['phone_number']) . "</td>";
                                echo "<td>" . htmlspecialchars($reservation_date) . "</td>";
                                echo "<td>" . htmlspecialchars($time) . "</td>";
                                echo "<td>" . htmlspecialchars($row['guests']) . "</td>";
                                echo "<td>
                                    <a href='updatereservation.php?id=$reservation_id'><button>Update</button></a> 
                                    <a href='reservations.php?done_reservation=$reservation_id'><button>Done</button></a>
                                    <a href='reservations.php?remove_reservation=$reservation_id' onclick='return confirm(\"Are you sure you want to remove this reservation?\")'><button>Cancel</button></a>
                                  </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='8' style='text-align: center;'>No active reservations found</td></tr>";
                        }

                        mysqli_free_result($result);
                        ?>
                    </tbody>
                </table>
            </section>

            <div class="add-reservation-btn-container">
                <a href="add_reservation.php" class="add-reservation-btn">Add New Reservation</a>
            </div>

        </main>
    </div>

</body>

</html>

<?php
mysqli_close($conn);
?>