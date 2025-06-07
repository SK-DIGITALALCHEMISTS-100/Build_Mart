<!DOCTYPE html>
<html lang="en">
<head>
    <style>
       body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .product-list {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 80%;
            max-width: 900px;
            text-align: center;
        }

        h1 {
            margin-bottom: 20px;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 16px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
            vertical-align: middle;
        }

        th {
            background-color: #007bff;
            color: white;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
        }

        td {
            background-color: #f9f9f9;
        }

        tr:hover td {
            background-color: #f1f1f1;
        }

        button, input[type="number"], input[type="submit"] {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            font-size: 14px;
        }

        button {
            background-color: #007bff;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }

        input[type="number"] {
            width: 50px;
            border: 1px solid #ccc;
            border-radius: 4px;
            text-align: center;
        }

        img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 50%;
        }

        .cart-button {
            margin-top: 5px;
        }

        .back-button {
            margin-top: 20px;
            padding: 10px 15px;
            background-color: #6c757d;
            color: white;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
        }

        .back-button:hover {
            background-color: #5a6268;
        }

        .filter-form {
            margin-bottom: 20px;
            text-align: left;
        }

        .filter-form input[type="submit"] {
            background-color: #28a745;
            color: white;
            margin-right: 10px;
        }

        .filter-form input[type="submit"]:hover {
            background-color: #218838;
        }

        .filter-form .clear-button {
            background-color: #dc3545;
            color: white;
        }

        .filter-form .clear-button:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <div class="product-list">
        <h1>Steel</h1>

     

        <table>
            <thead>
                <tr>
                    <th>Product Type</th>
                    <th>Product Name</th>
                    <th>Email-Id</th>
                    <th>Amount</th>
                    <th>Image</th>
                    <th>StockStatus</th>
                    <th>Add to Cart</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    // Database connection details
                    $servername = "localhost";
                    $username = "root";
                    $password = "";
                    $dbname = "build_mart";

                    // Create connection
                    $conn = new mysqli($servername, $username, $password, $dbname);

                    // Check connection
                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }

                    // Get filter parameters from URL
                    $productName = isset($_GET['productName']) ? $conn->real_escape_string($_GET['productName']) : '';

                    // Prepare SQL query with filters
                    $sql = "SELECT * FROM product WHERE productType = 'steel'";

                    if (!empty($productName)) {
                        $sql .= " AND productName LIKE ?";
                    }

                    $stmt = $conn->prepare($sql);
                    
                    if (!empty($productName)) {
                        $search_term = "%$productName%";
                        $stmt->bind_param("s", $search_term);
                    }

                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            // Check stock status to determine if "Add to Cart" should be disabled
                            $isOutOfStock = ($row['stockStatus'] == 'Out of Stock') ? true : false;
                            echo "<tr>";
                            echo "<td>{$row['productType']}</td>";
                            echo "<td>{$row['productName']}</td>";
                            echo "<td>{$row['username']}</td>";
                            echo "<td>{$row['amount']}</td>";

                            // Make sure product_image contains the correct path to the image
                            $image_path = $row['productImage'];
                            echo "<td><img src='{$image_path}' alt='Product Image style='width:100px;height:100px;'></td>";
                            echo "<td>{$row['stockStatus']}</td>";
                            
                            // Add "disabled" attribute to button if out of stock
                            $disabledAttribute = $isOutOfStock ? 'disabled' : '';
                            $cartButtonClass = $isOutOfStock ? 'btn-secondary' : 'btn-primary';

                            // Form to send product data to checkout.php
                            echo "<td>
                                <form method='POST' action='checkout.php'>
                                    <input type='hidden' name='productName' value='{$row['productName']}' />
                                    <input type='hidden' name='amount' value='{$row['amount']}' />
                                    <label>Quantity: <input type='number' name='quantity' value='1' min='1' /></label>
                                    <button type='submit' name='add_to_cart' class='cart-button $cartButtonClass' $disabledAttribute>Add to Cart</button>
                                </form>
                            </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7'>No steel products found</td></tr>";
                    }

                    // Close the database connection
                    $stmt->close();
                    $conn->close();
                ?>
            </tbody>
        </table>
    </div>

    <button onclick="window.location.href='home.html'" class="back-button">Back to Home</button>
</body>
</html>
