<?php
// Start a session (optional if you're using session data)
session_start();

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "build_mart";  // Change to your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch data from the orders table
$sql = "SELECT * FROM users_regis";  // Adjust query based on your needs
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Orders</title>
    <!-- Add Bootstrap for better styling -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f4f4;
            padding: 20px;
        }
        .table-container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>View Orders</h2>

    <div class="table-container mt-4">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th> Name</th>
                    <th>Company Name</th>
                    <th>Mobile No</th>
                    
                </tr>
            </thead>
            <tbody>
                
                <?php
                if ($result->num_rows > 0) {
                    // Fetch each row of data
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['id']}</td>
                                <td>{$row['name']}</td>
                                <td>{$row['company']}</td>
                                <td>{$row['email']}</td>
                                <td>{$row['phone']}</td>
                       
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No orders found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <div class="mt-3">
        <a href="admin_home.html" class="btn btn-secondary">Back</a>
    </div>  
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
