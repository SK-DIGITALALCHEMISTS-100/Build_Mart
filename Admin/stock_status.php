<?php
include('db1.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $stockStatus = $_POST['stockStatus'];

    // Update the stock status in the database
    $sql = "UPDATE product SET stockStatus = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $stockStatus, $id);

    if ($stmt->execute()) {
        echo "Stock status updated successfully!";
    } else {
        echo "Error updating stock status: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
