<?php
include("restaurant_config.php");

session_start();

$search = '';

if (isset($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $sql = "SELECT * FROM vendor WHERE vendor_id LIKE '%$search%' 
            OR name LIKE '%$search%' 
            OR category LIKE '%$search%' 
            OR location LIKE '%$search%'";
} else {
    $sql = "SELECT * FROM vendor";
}

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
    <title>Vendor Details</title>
    <link rel="icon" href="images/Skill++-01.png">
    <link rel="stylesheet" href="css/vendors.css">
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
            <h1>Vendor Details</h1>
        </header>

        <main>

            <div class="search-container">
                <form method="GET" action="vendors.php">
                    <input type="text" name="search" placeholder="Search Here" class="search-bar" value="<?php echo htmlspecialchars($search); ?>">
                    <button type="submit" class="search-btn">Search</button>
                </form>
            </div>

            <section>
                <h2>List of Vendors</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Vendor ID</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Category</th>
                            <th>Location</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                $vendor_id = intval($row['vendor_id']);
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($vendor_id) . "</td>";
                                echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['phone']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['category']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['location']) . "</td>";
                                echo "<td>
                                    <a href='updatevendor.php?id=$vendor_id'><button>Update</button></a> 
                                    <a href='vendorremove.php?id=$vendor_id'><button>Remove</button></a>
                                  </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6' style='text-align: center;'>No vendors found</td></tr>";
                        }

                        mysqli_free_result($result);
                        ?>
                    </tbody>
                </table>
            </section>

            <div class="add-vendor-btn-container">
                <a href="addvendor.php" class="add-vendor-btn">Add New Vendor</a>
            </div>

        </main>
    </div>

</body>

</html>

<?php
mysqli_close($conn);
?>