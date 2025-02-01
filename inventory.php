<?php
include("restaurant_config.php");
session_start();

$searchTerm = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

$sql = "SELECT ingredient_id, ingredient_name, quantity_available, category FROM inventory WHERE ingredient_name LIKE '%$searchTerm%' OR category LIKE '%$searchTerm%'";

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
    <title>Inventory</title>
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
            <h1>Inventory</h1>
        </header>

        <main>
            <div class="search-container">
                <input type="text" id="search" placeholder="Search Here" oninput="searchItems()">
                <button onclick="searchItems()">Search</button>
            </div>

            <div class="inventory-actions">
                <a href="addinventory.php"><button class="action-button">Add Item</button></a>
                <a href="updateinventory.php"><button class="action-button">Update Stock</button></a>
            </div>

            <section>
                <h2>List of Ingredients</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Ingredient ID</th>
                            <th>Ingredient Name</th>
                            <th>Quantity Available</th>
                            <th>Category</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['ingredient_id']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['ingredient_name']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['quantity_available']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['category']) . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4' style='text-align: center;'>No ingredients found</td></tr>";
                        }

                        mysqli_free_result($result);
                        ?>
                    </tbody>
                </table>
            </section>
        </main>
    </div>

    <script>
        function searchItems() {
            var searchTerm = document.getElementById('search').value.toLowerCase();
            var rows = document.querySelectorAll('table tbody tr');

            rows.forEach(function(row) {
                var ingredientName = row.cells[1].textContent.toLowerCase();
                var category = row.cells[3].textContent.toLowerCase();

                if (ingredientName.includes(searchTerm) || category.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }
    </script>

</body>

</html>

<?php
mysqli_close($conn);
?>