<?php

include("restaurant_config.php");
session_start();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Employee</title>
    <link rel="icon" href="images/Skill++-01.png">
    <link rel="stylesheet" href="css/add_employee.css">
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
            <h1>Add Employee</h1>
        </header>

        <div class="form-container">

            <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">

                <h3>Fill Up The Information</h3>

                <input type="text" name="id" required placeholder="Enter employee id here">

                <input type="text" name="name" required placeholder="Enter name here">

                <input type="email" name="email" required placeholder="Enter email here">

                <input type="text" name="role" required placeholder="Enter role here">

                <input type="text" name="salary" required placeholder="Enter salary here">

                <label for="dob">Date of Birth</label>
                <input type="date" name="dob" required placeholder="Enter date of birth">

                <label for="joining_date">Joining Date</label>
                <input type="date" name="joining_date" required>

                <select name="status">
                    <option value="Active">Active</option>
                    <option value="Inactive">Inactive</option>
                </select>

                <input type="submit" name="add" value="Add Employee" required class="form-btn">

            </form>
        </div>
    </div>
</body>

</html>

<?php

$id = filter_input(INPUT_POST, "id", FILTER_SANITIZE_NUMBER_INT);

$name = filter_input(INPUT_POST, "name", FILTER_SANITIZE_SPECIAL_CHARS);

$email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);

$role = filter_input(INPUT_POST, "role", FILTER_SANITIZE_SPECIAL_CHARS);

$salary = filter_input(INPUT_POST, "salary", FILTER_SANITIZE_NUMBER_INT);

$dob = filter_input(INPUT_POST, "dob", FILTER_SANITIZE_SPECIAL_CHARS);

$joining_date = filter_input(INPUT_POST, "joining_date", FILTER_SANITIZE_SPECIAL_CHARS);

$status = filter_input(INPUT_POST, "status", FILTER_SANITIZE_SPECIAL_CHARS);


if (isset($_POST["add"])) {
    if (isset($id, $name, $email, $role, $salary, $dob, $joining_date, $status)) {

        $sql = "SELECT * FROM employee WHERE employee_id = '$id'";

        $result = mysqli_query($conn, $sql);

        if ($result) {
            if (mysqli_num_rows($result) > 0) {
                echo '<script>alert("ID already exists!");</script>';
            } else {
                $sql = "INSERT INTO employee (employee_id, name, email, role, salary, status, date_of_birth, joining_date)
            VALUES ('$id', '$name', '$email', '$role', '$salary', '$status', '$dob', '$joining_date')";

                mysqli_query($conn, $sql);
                echo '<script>alert("Added Succesfully");</script>';
                session_reset();
                header("Location: employeelist.php");
            }
        }
    } else {
        echo '<script>alert("Please fill all the information");</script>';
    }
}

mysqli_close($conn);

?>