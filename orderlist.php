<?php
include("restaurant_config.php");

session_start();

$sql = "SELECT * FROM orders ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Orders</title>
    <link rel="icon" href="images/Skill++-01.png">
    <link rel="stylesheet" href="css/orderlist.css">
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
            <h1>All Orders</h1>
        </header>

        <main>
            <section>
                <h2>List of Previous Orders</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer Name</th>
                            <th>Table Number</th>
                            <th>Order Date</th>
                            <th>Order Time</th>
                            <th>Total Bill</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                $order_id = intval($row['id']);
                                $customer_name = htmlspecialchars($row['customer']);
                                $table_number = htmlspecialchars($row['table_number']);
                                $order_date = date('F j, Y', strtotime($row['created_at']));
                                $order_time = date('g:i A', strtotime($row['created_at'])); // Formatted for time
                                $total_price = number_format($row['total_price'], 2);
                                echo "<tr>";
                                echo "<td>" . $order_id . "</td>";
                                echo "<td>" . $customer_name . "</td>";
                                echo "<td>" . $table_number . "</td>";
                                echo "<td>" . $order_date . "</td>";
                                echo "<td>" . $order_time . "</td>";
                                echo "<td>$" . $total_price . "</td>";
                                echo "<td>
                                    <a href='order_details.php?order_id=$order_id'><button>View Details</button></a>
                                  </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='7' style='text-align: center;'>No orders found</td></tr>";
                        }

                        mysqli_free_result($result);
                        ?>
                    </tbody>
                </table>
            </section>
        </main>
    </div>

</body>

</html>

<?php
mysqli_close($conn);
?>