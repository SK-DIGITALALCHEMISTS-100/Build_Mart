<?php
session_start();

// Database connection details
$host = "localhost";
$dbname = "build_mart";
$db_username = "root"; // Replace with your database username
$db_password = ""; // Replace with your database password

try {
    // Connect to the database
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $db_username, $db_password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $unique_id = trim($_POST["unique_id"]);
        $password = trim($_POST["password"]);

        // Fetch the user from the database
        $stmt = $conn->prepare("SELECT * FROM users_regis WHERE unique_id = :unique_id");
        $stmt->bindParam(':unique_id', $unique_id);
        $stmt->execute();

        // Check if the user exists
        if ($stmt->rowCount() === 1) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verify the password
            if (password_verify($password, $user['password'])) {
                // Store user data in session
                $_SESSION['unique_id'] = $user['unique_id'];

                // Set a session variable to indicate successful login
                $_SESSION['login_success'] = true;

                // Redirect to user page
                header("Location: user_home.php"); // Or trigger JavaScript redirect after the message
                exit;
            } else {
                $error_message = "Invalid password. Please try again.";
            }
        } else {
            $error_message = "Invalid unique ID. Please try again.";
        }
    }
} catch (PDOException $e) {
    $error_message = "Database error: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(135deg, #6c63ff, #8a2be2);
            font-family: 'Arial', sans-serif;
            margin: 0;
        }
        .login-container {
            width: 100%;
            max-width: 400px;
            padding: 30px;
            border-radius: 15px;
            background: white;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
        }
        .login-container h3 {
            color: #333;
            font-weight: bold;
            margin-bottom: 20px;
            text-align: center;
        }
        .form-label {
            color: #555;
            font-weight: bold;
        }
        .form-control {
            border-radius: 8px;
            padding: 10px;
            border: 1px solid #ddd;
            font-size: 16px;
        }
        .form-control:focus {
            border-color: #6c63ff;
            box-shadow: 0 0 5px rgba(108, 99, 255, 0.5);
        }
        .btn-primary {
            width: 100%;
            padding: 10px;
            border-radius: 8px;
            background: #6c63ff;
            border: none;
            font-size: 16px;
            font-weight: bold;
            transition: background 0.3s;
        }
        .btn-primary:hover {
            background: #574bfa;
        }
        .text-danger {
            margin-top: 15px;
            font-size: 14px;
            color: #ff4d4d;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h3>Login</h3>
        <form action="" method="POST">
            <div class="mb-3">
                <label for="unique_id" class="form-label">Unique ID</label>
                <input type="text" id="unique_id" name="unique_id" class="form-control" placeholder="Enter Unique ID" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="Enter password" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
        <?php if (!empty($error_message)): ?>
            <div class="text-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>
    </div>

    <!-- JavaScript to show the success message and redirect -->
    <script>
        <?php if (isset($_SESSION['login_success']) && $_SESSION['login_success'] === true): ?>
            alert("Logged in successfully!");
            window.location.href = "User/Home.html";  // Redirect to the Home(1).html page
            <?php unset($_SESSION['login_success']); ?>  // Unset the session variable
        <?php endif; ?>
    </script>
</body>
</html>
