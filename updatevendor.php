<?php
include("restaurant_config.php");

if (isset($_GET['id'])) {
    $vendor_id = $_GET['id'];

    // Fetch the vendor details from the database
    $sql = "SELECT * FROM vendor WHERE vendor_id = '$vendor_id'";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }

    $vendor = mysqli_fetch_assoc($result);

    // Handle form submission to update vendor details
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $phone = mysqli_real_escape_string($conn, $_POST['phone']);
        $category = mysqli_real_escape_string($conn, $_POST['category']);
        $location = mysqli_real_escape_string($conn, $_POST['location']);

        // Update the vendor record in the database
        $update_sql = "UPDATE vendor SET name='$name', phone='$phone', category='$category', location='$location' WHERE vendor_id='$vendor_id'";

        if (mysqli_query($conn, $update_sql)) {
            echo "Vendor updated successfully.";
            header("Location: vendors.php");  // Redirect back to vendor list
            exit();
        } else {
            echo "Error updating vendor: " . mysqli_error($conn);
        }
    }

    mysqli_free_result($result);
} else {
    echo "Vendor ID not specified.";
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Vendor</title>
    <link rel="stylesheet" href="css/updatevendor.css">
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
                <li><a href="vendors.php">Vendor Management</a></li>
            </ul>
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <div class="main-content">
        <header>
            <h1>Update Vendor Details</h1>
        </header>

        <form method="post">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($vendor['name']); ?>" required><br>

            <label for="phone">Phone:</label>
            <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($vendor['phone']); ?>" required><br>

            <label for="category">Category:</label>
            <input type="text" id="category" name="category" value="<?php echo htmlspecialchars($vendor['category']); ?>" required><br>

            <label for="location">Location:</label>
            <input type="text" id="location" name="location" value="<?php echo htmlspecialchars($vendor['location']); ?>" required><br>

            <button type="submit">Update Vendor</button>
        </form>
    </div>
</body>

</html>