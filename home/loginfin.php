<?php
session_start();  // Start the session

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "build_mart";  // Ensure this database exists

$conn = new mysqli($servername, $username, $password, $dbname);

// Check if connection was successful
if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}

$error = "";  // For storing error messages

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email']) && isset($_POST['password'])) {
    $inputEmail = trim($_POST['email']);
    $inputPassword = trim($_POST['password']);

    // Sanitize input
    $inputEmail = mysqli_real_escape_string($conn, $inputEmail);
    
    // **Debugging: Check if email is received correctly**
    if (empty($inputEmail) || empty($inputPassword)) {
        $error = "Email and password fields cannot be empty.";
    } else {
        // **Check Admin Login (Email + Password)**
        $adminQuery = "SELECT * FROM admin WHERE email = ?";
        $stmt = $conn->prepare($adminQuery);

        if ($stmt) {
            $stmt->bind_param("s", $inputEmail);
            $stmt->execute();
            $adminResult = $stmt->get_result();

            if ($adminResult->num_rows > 0) {
                $admin = $adminResult->fetch_assoc();
                if (password_verify($inputPassword, $admin['password'])) {  
                    $_SESSION['admin'] = $admin['email'];
                    header("Location: /Build_Mart/Admin/admin_home.html");  
                    exit();
                } else {
                    $error = "Invalid email or password (Admin)";
                }
            }
        } else {
            $error = "Admin query failed: " . $conn->error;
        }

        // **Check User Login (Email + Password)**
        $userQuery = "SELECT * FROM users_regis WHERE email = ?";
        $stmt = $conn->prepare($userQuery);

        if ($stmt) {
            $stmt->bind_param("s", $inputEmail);
            $stmt->execute();
            $userResult = $stmt->get_result();

            if ($userResult->num_rows > 0) {
                $user = $userResult->fetch_assoc();
                if (password_verify($inputPassword, $user['password'])) {  
                    $_SESSION['user'] = $user['email'];
                    header("Location: /Build_Mart/User/Home.html");  
                    exit();
                } else {
                    $error = "Invalid email or password (User)";
                }
            }
        } else {
            $error = "User query failed: " . $conn->error;
        }

        // If login fails
        if (empty($error)) {
            $error = "Invalid email or password.";
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f4f4;
            padding: 20px;
        }
        .login-container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 300px;
            margin: 0 auto;
        }
        .btn-custom {
            background-color: #007bff;
            color: white;
        }
        .btn-custom:hover {
            background-color: #0056b3;
        }
        .error-message {
            color: red;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h3 class="text-center mb-4">Login</h3>
    
    <!-- Display error message if login fails -->
    <?php if (!empty($error)): ?>
        <div class="error-message"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <form method="POST" action="loginfin.php">
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" id="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-custom w-100">Login</button>
    </form>

    <div class="mt-3 text-center">
        <a href="user_registration.html"> Register here</a><br>
        <a href="forgotpassword.php">Forgot Password</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
