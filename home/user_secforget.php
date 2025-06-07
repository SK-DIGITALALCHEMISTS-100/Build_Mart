<?php
session_start(); // Start the session to retrieve the phone number

// Redirect to the forgot password page if the session is not set
if (!isset($_SESSION['phone'])) {
    header("Location: forgotpassword.php");
    exit();
}

// Handle form submission (password reset)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database credentials
    $servername = "localhost";
    $username_db = "root";
    $password_db = "";
    $dbname = "build_mart";

    // Create connection
    $conn = new mysqli($servername, $username_db, $password_db, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get the new password and confirm password from the form
    $new_password = mysqli_real_escape_string($conn, $_POST['new_password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

    // Check if passwords match
    if ($new_password !== $confirm_password) {
        echo "<p style='color:red;'>Passwords do not match. Please try again.</p>";
    } else {
        // Hash the new password (for security)
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

     

        // Update the password in the database
        $stmt = $conn->prepare("UPDATE client SET password = ? ");
        $stmt->bind_param("s", $hashed_password);
        $stmt->execute();

        // Check if the update was successful
        if ($stmt->affected_rows > 0) {
           // echo "<p style='color:green;'>Your password has been updated successfully. <a href='loginfin.php'>Go to Login</a></p>";
            // Clear the session after password reset
            session_unset();
            session_destroy();
        } else {
            echo "<p style='color:red;'>Error resetting password. Please try again later.</p>";
        }

        // Close the prepared statement and connection
        $stmt->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
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

        .reset-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
        }

        input[type="password"], input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        input[type="submit"] {
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 18px;
        }

        input[type="submit"]:hover {
            background-color: #218838;
        }

        .back-link {
            margin-top: 20px;
            font-size: 14px;
        }

        .back-link a {
            color: #28a745;
            text-decoration: none;
        }

        p {
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="reset-container">
        <h2>Reset Your Password</h2>
        <form action="" method="post">
            <input type="password" name="new_password" placeholder="Enter new password" required>
            <input type="password" name="confirm_password" placeholder="Confirm new password" required>
            <input type="submit" value="Reset Password">
        </form>

        <div class="back-link">
            <p>Remember your password? <a href="loginfin.php">Back to Login</a></p>
        </div>
    </div>
</body>
</html>
