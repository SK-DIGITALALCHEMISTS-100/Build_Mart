<?php
// Include your database connection file
include 'db_connection.php';

// Check if the delete request is made
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deleteProduct'])) {
    // Get the product ID to delete
    $productId = $_POST['productId'];

    // Sanitize the product ID
    $sanitizedProductId = mysqli_real_escape_string($connection, $productId);

    // Delete query to remove the product from the database
    $query = "DELETE FROM product WHERE id = ?";
    $stmt = $connection->prepare($query);

    // Bind the product ID to the query
    $stmt->bind_param("i", $sanitizedProductId);

    // Execute the query
    if ($stmt->execute()) {
        echo "<script>alert('Product deleted successfully!'); window.location.href = 'product_management.php';</script>";
    } else {
        echo "<script>alert('Error deleting product: " . $stmt->error . "'); window.location.href = 'product_management.php';</script>";
    }

    // Close the statement
    $stmt->close();
}

// Close the database connection
mysqli_close($connection);
?>
