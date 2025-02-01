<?php
include('config.php');

$query = "SELECT * FROM items";
$items = $pdo->query($query)->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $customerName = $_POST['customer'];
    $tableNumber = $_POST['table_number'];
    $selectedItems = $_POST['selected_items'];
    $totalPrice = 0;

    $tableCheckQuery = "SELECT * FROM orders WHERE table_number = ? AND status != 'done'";
    $stmt = $pdo->prepare($tableCheckQuery);
    $stmt->execute([$tableNumber]);
    $existingOrder = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existingOrder) {
        $warningMessage = "The table is currently occupied. Please select another one.";
    } else {
        foreach ($selectedItems as $itemId => $quantity) {
            $stmt = $pdo->prepare("SELECT price FROM items WHERE id = ?");
            $stmt->execute([$itemId]);
            $item = $stmt->fetch(PDO::FETCH_ASSOC);
            $totalPrice += $item['price'] * $quantity;
        }

        $stmt = $pdo->prepare("INSERT INTO orders (customer, table_number, total_price) VALUES (?, ?, ?)");
        $stmt->execute([$customerName, $tableNumber, $totalPrice]);

        $orderId = $pdo->lastInsertId();

        foreach ($selectedItems as $itemId => $quantity) {
            $stmt = $pdo->prepare("INSERT INTO order_items (order_id, item_id, quantity) VALUES (?, ?, ?)");
            $stmt->execute([$orderId, $itemId, $quantity]);
        }

        header('Location: index.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Order</title>
    <link rel="stylesheet" href="css/add_order.css">
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
        <div class="header">Create New Order</div>

        <?php if (isset($warningMessage)): ?>
            <div class="warning-message">
                <?php echo $warningMessage; ?>
            </div>
        <?php endif; ?>

        <form action="add_order.php" method="POST" class="order-form">
            <div class="form-group">
                <label for="customer">Customer Name:</label>
                <input type="text" id="customer" name="customer" required>
            </div>

            <div class="form-group">
                <label for="table_number">Table Number:</label>
                <input type="number" id="table_number" name="table_number" required>
            </div>

            <div class="form-group">
                <label>Select Items:</label>
                <table class="items-table">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Price</th>
                            <th>Quantity</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item): ?>
                            <tr>
                                <td><?= $item['name']; ?></td>
                                <td>$<?= number_format($item['price'], 2); ?></td>
                                <td>
                                    <input type="number" name="selected_items[<?= $item['id']; ?>]" min="0" value="0">
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <button type="submit" class="submit-btn">Create Order</button>
        </form>
    </div>

    <script>
        function validateForm(event) {
            const quantityInputs = document.querySelectorAll("input[name^='selected_items']");
            let itemsSelected = false;

            for (let input of quantityInputs) {
                if (input.value > 0) {
                    itemsSelected = true;
                    break;
                }
            }

            if (!itemsSelected) {
                event.preventDefault();
                alert("Please select at least one item to create the order.");
            }
        }

        document.querySelector(".order-form").addEventListener("submit", validateForm);
    </script>
</body>

</html>