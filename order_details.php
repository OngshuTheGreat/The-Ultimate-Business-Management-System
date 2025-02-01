<?php
include('config.php');

if (isset($_GET['order_id'])) {
    $orderId = $_GET['order_id'];

    $orderQuery = "SELECT * FROM orders WHERE id = ?";
    $orderStmt = $pdo->prepare($orderQuery);
    $orderStmt->execute([$orderId]);
    $order = $orderStmt->fetch(PDO::FETCH_ASSOC);

    $itemsQuery = "
        SELECT oi.quantity, i.name, i.price 
        FROM order_items oi
        JOIN items i ON oi.item_id = i.id
        WHERE oi.order_id = ? AND oi.quantity > 0
    ";
    $itemsStmt = $pdo->prepare($itemsQuery);
    $itemsStmt->execute([$orderId]);
    $orderItems = $itemsStmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
    <link rel="stylesheet" href="css/order_details.css">
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
        <div class="header">
            Order Details - Order #<?php echo $order['id']; ?>
        </div>

        <div class="order-details">
            <h3>Customer Information</h3>
            <p><strong>Customer Name:</strong> <?php echo htmlspecialchars($order['customer']); ?></p>
            <p><strong>Table Number:</strong> <?php echo htmlspecialchars($order['table_number']); ?></p>
            <p><strong>Order Date:</strong> <?php echo date('Y-m-d', strtotime($order['created_at'])); ?></p>
            <p><strong>Order Time:</strong> <?php echo date('H:i', strtotime($order['created_at'])); ?></p>
            <p><strong>Total Price:</strong> $<?php echo number_format($order['total_price'], 2); ?></p>

            <h3>Items Ordered</h3>
            <table class="items-table">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($orderItems) > 0): ?>
                        <?php foreach ($orderItems as $item): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item['name']); ?></td>
                                <td>$<?php echo number_format($item['price'], 2); ?></td>
                                <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                                <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4">No items have been ordered for this order.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <a href="index.php" class="back-btn">Back</a>
    </div>

</body>

</html>