<?php
// Include your database connection file
include 'db_connection.php';

// Fetch all products from the database
$query = "SELECT * FROM product";
$result = mysqli_query($connection, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>

<div class="container mt-5">
    <h1 class="text-center">Product Management</h1>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Product Type</th>
                <th>Product Name</th>
                <th>Amount</th>
                <th>Image</th>
                <th>Stock Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Check if products are found
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($row['productType']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['productName']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['amount']) . '</td>';
                    echo '<td><img src="' . htmlspecialchars($row['productImage']) . '" alt="Product Image" style="width:100px;"></td>';
                    echo '<td>' . htmlspecialchars($row['stockStatus']) . '</td>';
                    echo '<td>';
                    ?>

                    <!-- Update Product Form -->
                    <form method="POST" action="update.php" enctype="multipart/form-data" style="display:inline-block; margin-right:10px;">
    <input type="hidden" name="productId" value="<?php echo $row['id']; ?>">

    <label for="newProductType">New Product Type:</label><br>
    <input type="text" name="newProductType" value="<?php echo $row['productType']; ?>" required><br>
    
    <label for="newProductName">New Product Name:</label><br>
    <input type="text" name="newProductName" value="<?php echo $row['productName']; ?>" required><br>
    
    <label for="newAmount">New Amount:</label><br>
    <input type="number" name="newAmount" value="<?php echo $row['amount']; ?>" required><br>
    
    <label for="newImg">New Image:</label><br>
    <input type="file" name="newImg" accept="image/*" required><br>
    
    <label for="newStockStatus">Stock Status:</label><br>
    <select name="newStockStatus" required><br>
        <option value="In Stock" <?php echo ($row['stockStatus'] == 'In Stock') ? 'selected' : ''; ?>>In Stock</option>
        <option value="Out of Stock" <?php echo ($row['stockStatus'] == 'Out of Stock') ? 'selected' : ''; ?>>Out of Stock</option>
    </select>

    <button type="submit" name="updateProduct" class="btn btn-warning btn-sm">Update</button>
</form>


                    <!-- Delete Product Form -->
                    <form method="POST" action="delete.php" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to delete this product?');">
                        <input type="hidden" name="productId" value="<?php echo htmlspecialchars($row['id']); ?>">
                        <button type="submit" name="deleteProduct" class="btn btn-danger btn-sm">Delete</button>
                    </form>

                    <?php
                    echo '</td>';
                    echo '</tr>';
                }
            } else {
                echo '<tr><td colspan="6" class="text-center">No products found.</td></tr>';
            }
            ?>
        </tbody>
    </table>

    <!-- Link back to admin dashboard -->
    <div class="text-center">
        <button onclick="window.location.href='admin_home.html'" class="btn btn-secondary">Back</button>
    </div>
</div>

<!-- Bootstrap JS and Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

</body>
</html>

<?php
// Close the database connection
mysqli_close($connection);
?>
