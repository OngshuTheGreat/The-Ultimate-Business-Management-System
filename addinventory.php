<?php
include("restaurant_config.php");
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $ingredient_name = $_POST['ingredient_name'];
    $quantity_available = $_POST['quantity_available'];
    $category = $_POST['category'];

    $insert_sql = "INSERT INTO inventory (ingredient_name, quantity_available, category) VALUES (?, ?, ?)";
    $insert_stmt = $conn->prepare($insert_sql);
    $insert_stmt->bind_param("sis", $ingredient_name, $quantity_available, $category);

    if ($insert_stmt->execute()) {
        echo "<script>alert('New ingredient added successfully.'); window.location.href='inventory.php';</script>";
    } else {
        echo "<script>alert('Failed to add new ingredient.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Ingredient</title>
    <link rel="icon" href="images/Skill++-01.png">
    <link rel="stylesheet" href="css/addinventory.css">
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
            <h1>Add New Ingredient</h1>
        </header>

        <div class="form-container">
            <h2>Enter Ingredient Details</h2>
            <form action="addinventory.php" method="POST">
                <input type="text" id="ingredient_name" name="ingredient_name" placeholder="Ingredient Name" required>

                <input type="number" id="quantity_available" name="quantity_available" placeholder="Quantity Available" required>

                <input type="text" id="category" name="category" placeholder="Category" required>

                <button type="submit" class="form-btn">Add Ingredient</button>
            </form>
        </div>
    </div>

</body>

</html>

<?php
mysqli_close($conn);
?>