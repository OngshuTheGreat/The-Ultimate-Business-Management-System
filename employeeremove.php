<?php
include("restaurant_config.php");

$employee_id = $_GET['id'];

$delete_sql = "DELETE FROM employee WHERE employee_id = '$employee_id'";

if (mysqli_query($conn, $delete_sql)) {
    echo "Employee removed successfully.";
    header("Location: employeelist.php");
    exit();
} else {
    echo "Error removing employee: " . mysqli_error($conn);
}

mysqli_close($conn);
