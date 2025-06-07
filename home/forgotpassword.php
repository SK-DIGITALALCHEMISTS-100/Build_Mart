<?php 
session_start(); // Start session to store mobile number
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Forgot Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('bg.jpg') center/cover no-repeat; /* Update to your background image */
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .forgot-password-container {
            background-color: #021522;
            color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
        }

        input[type="tel"] {
            box-sizing: border-box;
            width: 100%;
            font-size: 18px;
            border: none;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            color: #4a4a4a;
            background: #fff;
        }

        input[type="submit"] {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 5px;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s;
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
    </style>
</head>
<body>
    <div class="forgot-password-container">
        <h2>Forgot Password</h2>
        <form action="" method="post">
            <input type="tel" name="phone" placeholder="Enter your mobile number" required>
            <input type="submit" value="Next">
        </form>
        <div class="back-link">
            <p>Remembered your password? <a href="loginfin.php">Back to Login</a></p>
        </div>

        <?php
        // Handle form submission
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

            // Clean and validate phone number
            $phoneno = mysqli_real_escape_string($conn, $_POST["phone"]);
            if (!preg_match("/^[0-9]{10}$/", $phoneno)) {  // Assuming 10-digit mobile number
                echo "<p style='color:red;'>Please enter a valid 10-digit mobile number.</p>";
            } else {
                // Prepared statement for security
                $stmt = $conn->prepare("SELECT * FROM users_regis WHERE phone = ?");
                $stmt->bind_param("s", $phoneno);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    // Store the mobile number in the session
                    $_SESSION['phone'] = $phoneno;
                    header("Location: user_secforget.php"); // Redirect to change password page
                    exit();
                } else {
                    echo "<p style='color:red;'>Mobile number not found.</p>";
                }

                // Close the prepared statement and connection
                $stmt->close();
            }

            // Close the database connection
            $conn->close();
        }
        ?>
    </div>
</body>
</html>
