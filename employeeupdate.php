<?php
include("restaurant_config.php");

$employee_id = $_GET['id'];

$sql = "SELECT * FROM employee WHERE employee_id = '$employee_id'";
$result = mysqli_query($conn, $sql);
$employee = mysqli_fetch_assoc($result);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);
    $salary = mysqli_real_escape_string($conn, $_POST['salary']);
    $dob = mysqli_real_escape_string($conn, $_POST['dob']);
    $join_date = mysqli_real_escape_string($conn, $_POST['join_date']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    $update_sql = "UPDATE employee SET name='$name', email='$email', role='$role', salary='$salary', 
                   date_of_birth='$dob', joining_date='$join_date', status='$status' 
                   WHERE employee_id='$employee_id'";

    if (mysqli_query($conn, $update_sql)) {
        echo "Employee updated successfully.";
        header("Location: employeelist.php");
        exit();
    } else {
        echo "Error updating employee: " . mysqli_error($conn);
    }
}

mysqli_free_result($result);
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Employee</title>
    <link rel="stylesheet" href="css/employeeupdate.css">
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
            <h1>Update Employee Details</h1>
        </header>

        <form method="post">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($employee['name']); ?>" required><br>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($employee['email']); ?>" required><br>

            <label for="role">Role:</label>
            <input type="text" id="role" name="role" value="<?php echo htmlspecialchars($employee['role']); ?>" required><br>

            <label for="salary">Salary:</label>
            <input type="number" id="salary" name="salary" value="<?php echo htmlspecialchars($employee['salary']); ?>" required><br>

            <label for="dob">Date of Birth:</label>
            <input type="date" id="dob" name="dob" value="<?php echo htmlspecialchars($employee['date_of_birth']); ?>" required><br>

            <label for="join_date">Joining Date:</label>
            <input type="date" id="join_date" name="join_date" value="<?php echo htmlspecialchars($employee['joining_date']); ?>" required><br>

            <select name="status">
                <option value="Active">Active</option>
                <option value="Inactive">Inactive</option>
            </select>

            <button type="submit">Update</button>
        </form>
    </div>
</body>

</html>