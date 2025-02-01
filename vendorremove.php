<?php
include("restaurant_config.php");

if (isset($_GET['id'])) {
    $vendor_id = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM vendor WHERE vendor_id = ?");
    $stmt->bind_param("i", $vendor_id);

    if ($stmt->execute()) {
        echo "Vendor removed successfully.";
        header("Location: vendors.php");
        exit();
    } else {
        echo "Error removing vendor: " . $stmt->error;
    }

    $stmt->close();
}

mysqli_close($conn);
