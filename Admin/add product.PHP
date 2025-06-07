<!DOCTYPE html>
<html lang="en">

<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('steel image.php') no-repeat center/cover;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .product-form {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
        }

        input,
        select {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            box-sizing: border-box;
        }

        button {
            background-color: #007bff;
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 30px;
            cursor: pointer;
            margin-top: 10px;
        }

        button:hover {
            background-color: #0056b3;
        }

        #submitModal {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="product-form">
        <h2>Construction Product Details Form</h2>
        <form id="productForm" action="" method="post" enctype="multipart/form-data">
            <label for="productType">Product Type:</label>
            <select name="productType" id="productType" required>
                <option value="" disabled selected>Select Type</option>
                <option value="Steel">Steel</option>
                <option value="Cement">Cement</option>
                <option value="Bricks">Bricks</option>
            </select>
            <br>

            <label for="productName">Product Brand:</label>
            <input type="text" name="productName" required>

            <label for="username">Username:</label>
            <input type="text" name="username" required>

            <label for="amount">Price:</label>
            <input type="number" name="amount" required>

            <label for="quantity">Quantity:</label>
            <input type="number" name="quantity" required>

            <label for="stockStatus">Stock Status:</label>
            <select name="stockStatus" id="stockStatus" required>
                <option value="" disabled selected>Select Stock Status</option>
                <option value="In Stock">In Stock</option>
                <option value="Out of Stock">Out of Stock</option>
            </select>

            <label for="productImage">Product Image:</label>
            <input type="file" name="productImage" accept="image/*" required>

            <button type="submit">Submit</button>
        </form>

        <!-- Back to Home Button -->
        <button onclick="window.location.href='Admin_home.html'">Back to Home</button>

        <!-- Modal for success message -->
        <div id="submitModal">
            <p>Product added successfully!</p>
            <button onclick="closeModal()">Close</button>
        </div>
    </div>

    <script>
        function openModal() {
            document.getElementById('submitModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('submitModal').style.display = 'none';
        }
    </script>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $servername = "localhost";
        $dbUsername = "root";
        $password = "";
        $dbname = "build_mart";

        $conn = new mysqli($servername, $dbUsername, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $productType = $_POST['productType'];
        $productName = $_POST['productName'];
        $username = $_POST['username'];
        $amount = $_POST['amount'];
        $quantity = $_POST['quantity'];
        $stockStatus = $_POST['stockStatus'];  // Get stock status from form

        // Check if a valid image is uploaded
        if (isset($_FILES["productImage"])) {
            $targetDir = "uploads/"; // Relative path for the uploads folder
            $targetFile = $targetDir . basename($_FILES["productImage"]["name"]);
            $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

            // Check if the file is an image
            if (!in_array($imageFileType, ["jpg", "jpeg", "png", "gif"])) {
                echo "<script>alert('Only image files are allowed.');</script>";
                exit();
            }

            // Check if the uploads folder exists, if not, create it
            if (!file_exists($targetDir)) {
                mkdir($targetDir, 0777, true); // Create the uploads folder if it doesn't exist
            }

            // Try to move the uploaded file to the uploads folder
            if (move_uploaded_file($_FILES["productImage"]["tmp_name"], $targetFile)) {
                // Check if the username exists in the users table
                $checkUsernameQuery = $conn->prepare("SELECT * FROM client WHERE username = ?");
                $checkUsernameQuery->bind_param("s", $username);
                $checkUsernameQuery->execute();
                $result = $checkUsernameQuery->get_result();

                if ($result->num_rows > 0) {
                    // Insert product details into the product table including stockStatus
                    $insertQuery = $conn->prepare("INSERT INTO product (productType, productName, username, amount, quantity, stockStatus, productImage) VALUES (?, ?, ?, ?, ?, ?, ?)");
                    $insertQuery->bind_param("sssiiss", $productType, $productName, $username, $amount, $quantity, $stockStatus, $targetFile);

                    if ($insertQuery->execute()) {
                        echo "<script>openModal();</script>";
                    } else {
                        echo "<script>alert('Error adding product: " . $insertQuery->error . "');</script>";
                    }
                    $insertQuery->close();
                } else {
                    echo "<script>alert('Error: Username not found in users table. Please check your username.');</script>";
                }
                $checkUsernameQuery->close();
            } else {
                echo "<script>alert('Error: Unable to upload file. Check folder permissions.');</script>";
            }
        }

        $conn->close();
    }
    ?>

</body>

</html>
