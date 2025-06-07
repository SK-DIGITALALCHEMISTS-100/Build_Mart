<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'build_mart');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if (isset($_POST['submit_complaint'])) {
    $username = $_POST['username'];
    $mobileno = $_POST['mobileno'];
    $complaintdetails = $_POST['complaintdetails'];

    // Insert complaint into the database
    $sql = "INSERT INTO complaints (username, mobileno, complaintdetails) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $mobileno, $complaintdetails);

    if ($stmt->execute()) {
        echo "<script>alert('Complaint submitted successfully!');</script>";
    } else {
        echo "<script>alert('Error submitting complaint: " . $stmt->error . "');</script>";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Raise a Complaint | Build Mart</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
        }
        h2 {
            margin-bottom: 20px;
        }
        .form-control, .form-select {
            background-color: #ffffff;
            border: 1px solid #ced4da;
            border-radius: 4px;
        }
        button {
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Raise a Complaint</h2>

        <!-- Complaint Form -->
        <form id="complaintForm" method="POST" action="">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>

            <div class="mb-3">
                <label for="mobileno" class="form-label">Mobile No.</label>
                <input type="text" class="form-control" id="mobileno" name="mobileno" required>
            </div>

            <div class="mb-3">
                <label for="complaintDetails" class="form-label">Complaint Details</label>
                <textarea class="form-control" id="complaintDetails" name="complaintdetails" rows="5" required></textarea>
            </div>

            <button type="submit" name="submit_complaint" class="btn btn-primary">Submit Complaint</button>
        </form>

        <!-- Back Button -->
        <div class="mt-3">
            <button onclick="window.location.href='home.html'" class="btn btn-secondary">Back</button>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
