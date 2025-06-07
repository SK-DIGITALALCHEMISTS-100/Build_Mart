<?php
include('db1.php');

$sql = "SELECT * FROM product";  // Fetch all products in the stock
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Management | BUILD MART</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            padding: 15px;
            text-align: center;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        button {
            width: 100%;
        }
        
    </style>
</head>

<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Manage Stock</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Product Name</th>
                    <th>Stock Status</th>
                    <th>Quantity</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['productName']; ?></td>
                        <td>
                            <button class="btn <?php echo $row['stockStatus'] == 'In Stock' ? 'btn-success' : 'btn-danger'; ?> stock-status-btn"
                                    data-id="<?php echo $row['id']; ?>"
                                    data-status="<?php echo $row['stockStatus']; ?>">
                                <?php echo $row['stockStatus']; ?>
                            </button>
                        </td>
                        <td><input type="number" class="form-control" id="quantity-<?php echo $row['id']; ?>" value="<?php echo $row['quantity']; ?>"></td>
                        <td>
                            <button class="btn btn-success update-btn" data-id="<?php echo $row['id']; ?>">Update Quantity</button>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <div class="mt-3">
        <a href="admin_home.html" class="btn btn-secondary">Back</a>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Handle stock status button click
            const stockStatusButtons = document.querySelectorAll('.stock-status-btn');

            stockStatusButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const id = this.getAttribute('data-id');
                    const currentStatus = this.getAttribute('data-status');
                    const newStatus = currentStatus === 'In Stock' ? 'Out of Stock' : 'In Stock';

                    // Send stock status change request to the server
                    const xhr = new XMLHttpRequest();
                    xhr.open('POST', 'stock_status.php', true);
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    xhr.onload = function () {
                        if (xhr.status === 200) {
                            // Update the button text and class after successful update
                            button.textContent = newStatus;
                            button.classList.toggle('btn-success');
                            button.classList.toggle('btn-danger');
                            button.setAttribute('data-status', newStatus);
                        } else {
                            alert('Error updating stock status');
                        }
                    };
                    xhr.send('id=' + id + '&stockStatus=' + newStatus);
                });
            });

            // Handle quantity update button click
            const updateButtons = document.querySelectorAll('.update-btn');

            updateButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const id = this.getAttribute('data-id');
                    const quantity = document.getElementById('quantity-' + id).value;

                    // Send the data to the server using AJAX for updating quantity
                    const xhr = new XMLHttpRequest();
                    xhr.open('POST', 'update_stock.php', true);
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    xhr.onload = function () {
                        if (xhr.status === 200) {
                            alert(xhr.responseText);  // Show success or failure message
                        }
                    };
                    xhr.send('id=' + id + '&quantity=' + quantity);
                });
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
