<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: login_form.php");
    exit;
}

include('config.php');

$query = "
    SELECT o.id, o.customer, o.total_price, o.created_at, o.status, o.table_number
    FROM orders o
    ORDER BY o.created_at DESC
";
$stmt = $pdo->query($query);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (isset($_GET['done'])) {
    $orderId = $_GET['done'];
    $doneQuery = "UPDATE orders SET status = 'done' WHERE id = ?";
    $doneStmt = $pdo->prepare($doneQuery);
    $doneStmt->execute([$orderId]);
    header('Location: index.php');
    exit;
}

if (isset($_GET['cancel'])) {
    $orderId = $_GET['cancel'];
    $cancelQuery = "DELETE FROM orders WHERE id = ?";
    $cancelStmt = $pdo->prepare($cancelQuery);
    $cancelStmt->execute([$orderId]);
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant Orders Dashboard</title>
    <link rel="stylesheet" href="css/index.css">
</head>

<body>

    <div class="sidebar">
        <div class="logo">
            <a href="index.php"><img src="images/Skill++.png" alt="Logo" height="50px" width="120px"></a>
        </div>
        <div class="sidebar-options">
            <ul>
                <li><a href="index.php">Order Management</a></li>
                <li><a href="orderlist.php">Previous Orders</a></li>
                <li><a href="menu.php">Menu</a></li>
                <li><a href="inventory.php">Inventory</a></li>
                <li><a href="employeelist.php">Employee Management</a></li>
                <li><a href="reservations.php">Reservations</a></li>
                <li><a href="vendors.php">Vendor Management</a></li>

            </ul>
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <div class="main-content">
        <div class="header">Order Management</div>

        <a href="add_order.php" class="add-order-btn">Add Order</a>

        <div class="order-cards">
            <?php foreach ($orders as $order): ?>
                <?php if ($order['status'] != 'done'): ?>
                    <div class="card">
                        <h2>Order #<?php echo htmlspecialchars($order['id']); ?></h2>
                        <p><strong>Customer:</strong> <?php echo htmlspecialchars($order['customer']); ?></p>
                        <p><strong>Total Bill:</strong> $<?php echo number_format($order['total_price'], 2); ?></p>
                        <p><strong>Created At:</strong> <?php echo date('h:i A', strtotime($order['created_at'])); ?></p>
                        <p><strong>Table No:</strong> <?php echo htmlspecialchars($order['table_number']); ?></p>

                        <div class="button-container">
                            <a href="order_details.php?order_id=<?php echo $order['id']; ?>" class="view-details-btn">View Details</a>
                            <a href="?done=<?php echo $order['id']; ?>" class="done-btn">Done</a>
                            <a href="?cancel=<?php echo $order['id']; ?>" class="cancel-btn" onclick="return confirm('Are you sure you want to cancel this order?')">Cancel</a>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>

</body>

</html>