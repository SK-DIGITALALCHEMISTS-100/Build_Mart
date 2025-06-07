<?php
$host = "localhost"; 
$dbname = "build_mart";
$username = "root";  
$password = "";    

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Fetch users who haven't received credentials
$query = "SELECT * FROM users_regis WHERE username IS NULL";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel | Build Mart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">User Registrations</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Company</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?= $row['name'] ?></td>
                        <td><?= $row['company'] ?></td>
                        <td><?= $row['email'] ?></td>
                        <td><?= $row['phone'] ?></td>
                        <td>
                            <form action="generate_credentials.php" method="POST">
                                <input type="hidden" name="user_id" value="<?= $row['id'] ?>">
                                <input type="hidden" name="email" value="<?= $row['email'] ?>">
                                <input type="hidden" name="name" value="<?= $row['name'] ?>">
                                <a href="generate_credentials.php" class="btn btn-primary">Generate Credentials</a>

                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <div class="mt-3">
        <a href="admin_home.html" class="btn btn-secondary">Back</a>
    </div>
    </div>
</body>
</html>

<?php $conn->close(); ?>
