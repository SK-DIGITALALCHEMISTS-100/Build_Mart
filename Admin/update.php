<?php
// Include your database connection file
include 'db_connection.php';

// Check if form data is posted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateProduct'])) {
    // Get the updated product data from the form
    $productId = $_POST['productId'];
    $newProductType = $_POST['newProductType'];
    $newProductName = $_POST['newProductName'];
    $newAmount = $_POST['newAmount'];
    $newStockStatus = $_POST['newStockStatus'];
    
    // Check if an image file is uploaded
    if (isset($_FILES['newImg']) && $_FILES['newImg']['error'] == 0) {
        $imgTmpName = $_FILES['newImg']['tmp_name'];
        $imgName = $_FILES['newImg']['name'];
        $imgPath = 'uploads/' . basename($imgName); // Define the path to save the uploaded file

        // Move the uploaded file to the 'uploads' directory
        if (move_uploaded_file($imgTmpName, $imgPath)) {
            $newImgPath = $imgPath;
        } else {
            echo "<script>alert('Error uploading image.'); window.location.href = 'product_management.php';</script>";
            exit;
        }
    } else {
        // If no image is uploaded, retain the old image
        $query = "SELECT productImage FROM product WHERE id = ?";
        $stmt = $connection->prepare($query);
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $stmt->bind_result($existingImg);
        $stmt->fetch();
        $stmt->close();
        $newImgPath = $existingImg; // Use existing image if no new image is uploaded
    }

    // Sanitize inputs to prevent SQL Injection
    $sanitizedProductType = mysqli_real_escape_string($connection, $newProductType);
    $sanitizedProductName = mysqli_real_escape_string($connection, $newProductName);
    $sanitizedAmount = mysqli_real_escape_string($connection, $newAmount);
    $sanitizedStockStatus = mysqli_real_escape_string($connection, $newStockStatus);

    // Update query to update the product details
    $query = "UPDATE product SET productType = ?, productName = ?, amount = ?, productImage = ?, stockStatus = ? WHERE id = ?";
    $stmt = $connection->prepare($query);

    // Bind the parameters to the query
    $stmt->bind_param("ssisss", $sanitizedProductType, $sanitizedProductName, $sanitizedAmount, $newImgPath, $sanitizedStockStatus, $productId);

    // Execute the query
    if ($stmt->execute()) {
        echo "<script>alert('Product updated successfully!'); window.location.href = 'product_management.php';</script>";
    } else {
        echo "<script>alert('Error updating product: " . $stmt->error . "'); window.location.href = 'product_management.php';</script>";
    }

    // Close the statement
    $stmt->close();
}

// Close the database connection
mysqli_close($connection);
?>
