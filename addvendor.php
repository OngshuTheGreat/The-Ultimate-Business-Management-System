<?php
include("restaurant_config.php");
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Vendor</title>
    <link rel="icon" href="images/Skill++-01.png">
    <link rel="stylesheet" href="css/addvendor.css">
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
            <h1>Add Vendor</h1>
        </header>

        <div class="form-container">

            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

                <h3>Fill Up The Information</h3>

                <input type="text" name="vendor_id" required placeholder="Enter vendor id here">

                <input type="text" name="vendor_name" required placeholder="Enter vendor name here">

                <input type="text" name="vendor_phone" required placeholder="Enter phone number here">

                <input type="text" name="category" required placeholder="Enter category here">

                <input type="text" name="location" required placeholder="Enter location here">

                <input type="submit" name="add" value="Add Vendor" required class="form-btn">

            </form>
        </div>
    </div>
</body>

</html>

<?php

$vendor_id = filter_input(INPUT_POST, "vendor_id", FILTER_SANITIZE_NUMBER_INT);
$vendor_name = filter_input(INPUT_POST, "vendor_name", FILTER_SANITIZE_SPECIAL_CHARS);
$vendor_phone = filter_input(INPUT_POST, "vendor_phone", FILTER_SANITIZE_SPECIAL_CHARS);
$category = filter_input(INPUT_POST, "category", FILTER_SANITIZE_SPECIAL_CHARS);
$location = filter_input(INPUT_POST, "location", FILTER_SANITIZE_SPECIAL_CHARS);

if (isset($_POST["add"])) {
    if (isset($vendor_id, $vendor_name, $vendor_phone, $category, $location)) {

        $sql = "SELECT * FROM vendor WHERE vendor_id = '$vendor_id'";

        $result = mysqli_query($conn, $sql);

        if ($result) {
            if (mysqli_num_rows($result) > 0) {
                echo '<script>alert("Vendor ID already exists!");</script>';
            } else {
                $sql = "INSERT INTO vendor (vendor_id, name, phone, category, location)
                        VALUES ('$vendor_id', '$vendor_name', '$vendor_phone', '$category', '$location')";

                mysqli_query($conn, $sql);
                echo '<script>alert("Vendor Added Successfully");</script>';
                session_reset();
                header("Location: vendors.php");
            }
        }
    } else {
        echo '<script>alert("Please fill all the information");</script>';
    }
}

mysqli_close($conn);
?>