<?php
include('config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $itemName = $_POST['item_name'];
    $itemPrice = $_POST['item_price'];
    $itemCategory = $_POST['item_category'];

    try {
        $insertQuery = "INSERT INTO items (name, price, category) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($insertQuery);
        $stmt->execute([$itemName, $itemPrice, $itemCategory]);

        header('Location: add_item.php?success=true');
        exit;
    } catch (Exception $e) {
        $errorMessage = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Menu Item</title>
    <link rel="stylesheet" href="css/add_item.css">
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
            <h1>Add New Item</h1>
        </header>

        <?php if (isset($errorMessage)): ?>
            <div class="error-message"><?= $errorMessage ?></div>
        <?php elseif (isset($_GET['success']) && $_GET['success'] == 'true'): ?>
            <div class="success-message">Item added successfully!</div>
        <?php endif; ?>

        <form action="add_item.php" method="POST" class="order-form">
            <h2>Add Item</h2>
            <div class="form-group">
                <label for="item_name">Item Name:</label>
                <input type="text" id="item_name" name="item_name" required>
            </div>

            <div class="form-group">
                <label for="item_price">Item Price:</label>
                <input type="number" id="item_price" name="item_price" required min="0.01" step="0.01">
            </div>

            <div class="form-group">
                <label for="item_category">Category:</label>
                <input type="text" id="item_category" name="item_category" required>
            </div>

            <button type="submit" class="submit-btn">Add Item</button>
        </form>
    </div>

</body>

</html>