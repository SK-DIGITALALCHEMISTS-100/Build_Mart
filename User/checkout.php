<?php
session_start();

// Initialize an empty cart if not present
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Function to add an item to the cart
function addToCart($productName, $amount, $quantity) {
    // Calculate total price for the item
    $total_price = $amount * $quantity;

    // Create the cart item array
    $cart_item = [
        'productName' => $productName,
        'amount' => $amount,
        'quantity' => $quantity,
        'total_price' => $total_price
    ];

    // Add item to the cart session
    $_SESSION['cart'][] = $cart_item;
}

// Handle adding product to cart
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_to_cart'])) {
    $productName = $_POST['productName'];
    $amount = $_POST['amount'];
    $quantity = $_POST['quantity'];

    // Validate the input fields
    if (!empty($productName) && !empty($amount) && !empty($quantity)) {
        addToCart($productName, $amount, $quantity);
    } else {
        echo "<script>alert('Please enter valid product details.');</script>";
    }
}

// Handle removing product from cart
if (isset($_GET['action']) && $_GET['action'] == 'remove' && isset($_GET['index'])) {
    $index = $_GET['index'];

    // Remove the product from the cart
    if (isset($_SESSION['cart'][$index])) {
        unset($_SESSION['cart'][$index]);
        $_SESSION['cart'] = array_values($_SESSION['cart']); // Reindex the cart array
    }
}

// Handle checkout redirection
if (isset($_POST['checkout'])) {
    header('Location: bi.php'); // Redirect to checkout page
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Shopping Cart</title>
    <style>
        body {
            background-color: #f4f4f4;
            padding: 20px;
        }
        .cart-table {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
        }
        .btn-custom {
            background-color: #007bff;
            color: white;
        }
        .btn-custom:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Shopping Cart</h2>
    <div class="cart-table">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Amount</th>
                    <th>Quantity</th>
                    <th>Total Price</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!empty($_SESSION['cart'])) {
                    $total = 0;

                    // Loop through each item in the cart
                    foreach ($_SESSION['cart'] as $index => $item) {
                        echo "<tr>
                            <td>{$item['productName']}</td>
                            <td>{$item['amount']}</td>
                            <td>{$item['quantity']}</td>
                            <td>{$item['total_price']}</td>
                            <td><a href='checkout.php?action=remove&index={$index}' class='btn btn-danger'>Remove</a></td>
                        </tr>";
                        $total += $item['total_price'];
                    }

                    // Display total amount
                    echo "<tr>
                        <td colspan='3' align='right'><strong>Total</strong></td>
                        <td colspan='2'><strong>{$total}</strong></td>
                    </tr>";
                } else {
                    // If cart is empty
                    echo "<tr><td colspan='5' align='center'>Your cart is empty</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        <a href="Home.html" class="btn btn-custom">Continue Shopping</a>
        <form method="POST" style="display:inline;">
            <button type="submit" name="checkout" class="btn btn-success">Proceed to Checkout</button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
