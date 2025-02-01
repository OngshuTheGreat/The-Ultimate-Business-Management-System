<?php
include("restaurant_config.php");

session_start();

if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $sql = "SELECT * FROM employee WHERE 
            name LIKE '%$search%' OR 
            employee_id LIKE '%$search%' OR 
            role LIKE '%$search%' OR 
            status LIKE '%$search%'";
} else {
    $sql = "SELECT * FROM employee";
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
    <title>Employee Details</title>
    <link rel="icon" href="images/Skill++-01.png">
    <link rel="stylesheet" href="css/employeelist.css">
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
            <h1>Employee Details</h1>
        </header>

        <main>

            <div class="search-container">
                <form method="GET" action="employeelist.php">
                    <input type="text" name="search" placeholder="Search Here" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" />
                    <button type="submit">Search</button>
                </form>
            </div>

            <section>
                <h2>List of Employees</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Employee ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Salary</th>
                            <th>Date of Birth</th>
                            <th>Joining Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                $employee_id = intval($row['employee_id']);
                                $salary = number_format($row['salary'], 2);
                                $dob = date('F j, Y', strtotime($row['date_of_birth']));
                                $joining_date = date('F j, Y', strtotime($row['joining_date']));
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($employee_id) . "</td>";
                                echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['role']) . "</td>";
                                echo "<td>" . htmlspecialchars($salary) . "</td>";
                                echo "<td>" . htmlspecialchars($dob) . "</td>";
                                echo "<td>" . htmlspecialchars($joining_date) . "</td>";
                                echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                                echo "<td>
                                    <a href='employeeupdate.php?id=$employee_id'><button>Update</button></a> 
                                    <a href='employeeremove.php?id=$employee_id'><button>Remove</button></a>
                                  </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='9' style='text-align: center;'>No employees found</td></tr>";
                        }

                        mysqli_free_result($result);
                        ?>
                    </tbody>
                </table>
            </section>

            <div class="add-employee-btn-container">
                <a href="add_employee.php" class="add-employee-btn">Add New Employee</a>
            </div>

        </main>
    </div>

</body>

</html>

<?php
mysqli_close($conn);
?>