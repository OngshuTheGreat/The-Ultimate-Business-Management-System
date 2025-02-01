<?php
include("restaurant_config.php");
session_start();

$update_success = false;

if (isset($_POST['update'])) {
    foreach ($_POST['new_quantity'] as $ingredient_id => $new_quantity) {
        $ingredient_id = mysqli_real_escape_string($conn, $ingredient_id);
        $new_quantity = mysqli_real_escape_string($conn, $new_quantity);

        $sql = "UPDATE inventory SET quantity_available = '$new_quantity' WHERE ingredient_id = '$ingredient_id'";

        if (mysqli_query($conn, $sql)) {
            $update_success = true;
        } else {
            echo "Error: " . mysqli_error($conn) . "<br>";
        }
    }

    header("Location: updateinventory.php");
    exit();
}

$sql = "SELECT ingredient_id, ingredient_name, quantity_available, category FROM inventory";
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
    <title>Update Inventory</title>
    <link rel="icon" href="images/Skill++-01.png">
    <link rel="stylesheet" href="css/inventory.css">
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
            <h1>Update Inventory</h1>
        </header>

        <main>
            <section>
                <h2>Current Inventory List</h2>

                <?php if ($update_success): ?>
                    <div class="success-message">Inventory updated successfully!</div>
                <?php endif; ?>

                <form method="POST" action="">
                    <table>
                        <thead>
                            <tr>
                                <th>Ingredient ID</th>
                                <th>Ingredient Name</th>
                                <th>Quantity Available</th>
                                <th>Category</th>
                                <th>Update</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            mysqli_data_seek($result, 0);

                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($row['ingredient_id']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['ingredient_name']) . "</td>";
                                    echo "<td><input type='number' name='new_quantity[" . $row['ingredient_id'] . "]' value='" . htmlspecialchars($row['quantity_available']) . "' min='0'></td>";
                                    echo "<td>" . htmlspecialchars($row['category']) . "</td>";
                                    echo "<td><button type='submit' name='update' value='" . $row['ingredient_id'] . "' class='action-button'>Update</button></td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='5' style='text-align: center;'>No ingredients found</td></tr>";
                            }
                            mysqli_free_result($result);
                            ?>
                        </tbody>
                    </table>
                </form>
            </section>
        </main>
    </div>

</body>

</html>

<?php
mysqli_close($conn);
?>