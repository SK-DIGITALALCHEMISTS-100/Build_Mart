<?php
include('db1.php');

// Check if request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $quantity = $_POST['quantity'];

    // Update the stock quantity
    $sql = "UPDATE product SET quantity = '$quantity' WHERE id = '$id'";

    if ($conn->query($sql) === TRUE) {
        echo "Stock updated successfully";
    } else {
        echo "Error: " . $conn->error;
    }
}

$conn->close();
?>
