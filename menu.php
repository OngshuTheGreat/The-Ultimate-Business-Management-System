<?php
include("restaurant_config.php");

session_start();

// Check if there's a search query
$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';

// SQL query to fetch items, considering search query
$sql = "SELECT * FROM items WHERE name LIKE ? OR category LIKE ?";
$stmt = $conn->prepare($sql);
$searchTerm = "%" . $searchQuery . "%"; // Add % to allow for partial matching
$stmt->bind_param("ss", $searchTerm, $searchTerm);
$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

if (isset($_GET['remove_item'])) {
    $item_id = $_GET['remove_item'];

    $check_order_sql = "SELECT * FROM order_items WHERE item_id = ?";
    $stmt = $conn->prepare($check_order_sql);
    $stmt->bind_param("i", $item_id);
    $stmt->execute();
    $order_check = $stmt->get_result();

    if ($order_check->num_rows > 0) {
        echo "<script>alert('Item is currently in an order. Cannot remove it.');</script>";
    } else {
        $delete_sql = "DELETE FROM items WHERE id = ?";
        $delete_stmt = $conn->prepare($delete_sql);
        $delete_stmt->bind_param("i", $item_id);
        if ($delete_stmt->execute()) {
            echo "<script>alert('Item removed successfully.');</script>";
            header("Location: menu.php");
            exit();
        } else {
            echo "<script>alert('Failed to remove item.');</script>";
        }
    }
}

if (isset($_POST['update_item'])) {
    $item_id = $_POST['item_id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $category = $_POST['category'];

    $update_sql = "UPDATE items SET name = ?, price = ?, category = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("sdsi", $name, $price, $category, $item_id);
    if ($update_stmt->execute()) {
        echo "<script>alert('Item updated successfully.');</script>";
        header("Location: menu.php");
        exit();
    } else {
        echo "<script>alert('Failed to update item.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu</title>
    <link rel="icon" href="images/Skill++-01.png">
    <link rel="stylesheet" href="css/menu.css">
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
            <h1>Menu</h1>
        </header>

        <main>
            <div class="search-container">
                <form action="menu.php" method="GET">
                    <input type="text" name="search" placeholder="Search Here" value="<?php echo htmlspecialchars($searchQuery); ?>">
                    <button type="submit" class="search-btn">Search</button>
                </form>
            </div>

            <div class="add-item-btn-container">
                <a href="add_item.php" class="add-item-btn">Add New Item</a>
            </div>

            <h2>Menu Items</h2>
            <table>
                <thead>
                    <tr>
                        <th>Item ID</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Category</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $item_id = $row['id'];
                            $item_name = htmlspecialchars($row['name']);
                            $item_price = number_format($row['price'], 2);
                            $item_category = htmlspecialchars($row['category']);
                            echo "<tr>";
                            echo "<td>" . $item_id . "</td>";
                            echo "<td>" . $item_name . "</td>";
                            echo "<td>$" . $item_price . "</td>";
                            echo "<td>" . $item_category . "</td>";
                            echo "<td>
                                <a href='#' onclick='openUpdateForm($item_id, \"$item_name\", \"$item_price\", \"$item_category\")'>
                                    <button class='update-btn'>Update</button>
                                </a>
                                <a href='menu.php?remove_item=$item_id' onclick='return confirm(\"Are you sure you want to remove this item?\")'>
                                    <button class='remove-btn'>Remove</button>
                                </a>
                              </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>No items found</td></tr>";
                    }

                    mysqli_free_result($result);
                    ?>
                </tbody>
            </table>

            <div id="update-item-form" style="display: none;">
                <h3>Update Item</h3>
                <form action="menu.php" method="POST">
                    <input type="hidden" id="item_id" name="item_id">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" required>
                    <label for="price">Price:</label>
                    <input type="number" id="price" name="price" step="0.01" required>
                    <label for="category">Category:</label>
                    <input type="text" id="category" name="category" required>
                    <button type="submit" name="update_item">Update Item</button>
                    <button type="button" onclick="closeUpdateForm()">Cancel</button>
                </form>
            </div>

            <div class="go-back-btn-container">
                <a href="index.php" class="go-back-btn">Back</a>
            </div>

        </main>

    </div>

    <script>
        function openUpdateForm(id, name, price, category) {
            document.getElementById('update-item-form').style.display = 'block';
            document.getElementById('item_id').value = id;
            document.getElementById('name').value = name;
            document.getElementById('price').value = price;
            document.getElementById('category').value = category;
        }

        function closeUpdateForm() {
            document.getElementById('update-item-form').style.display = 'none';
        }
    </script>
</body>

</html>

<?php
mysqli_close($conn);
?>