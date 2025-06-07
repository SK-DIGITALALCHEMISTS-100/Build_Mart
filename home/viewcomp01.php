<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'Build_mart');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch complaints data
$sql = "SELECT * FROM complaints ORDER BY complaint_date DESC";
$result = $conn->query($sql);

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Complaints | build_mart</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        h2 {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Complaints List</h2>

        <!-- Complaints Data Table -->
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>username</th>
                    <th>mobileno</th>
                    <th>Details</th>
                    <th>Date Submitted</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (isset($result) && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $row['complaint_id']; ?></td>
                            <td><?php echo $row['username']; ?></td>
                            <td><?php echo $row['mobileno']; ?></td>
                            <td><?php echo $row['complaintdetails']; ?></td>
                            <td><?php echo $row['complaint_date']; ?></td>
                            <td><?php echo $row['status']; ?></td>
                        </tr>
                    <?php }
                } else { ?>
                    <tr><td colspan="7" class="text-center">No complaints found.</td></tr>
                <?php } ?>
            </tbody>
        </table>

        <!-- Back Button -->
        <div class="mt-3">
            <button onclick="window.location.href='home.html'" class="btn btn-secondary">Back</button>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>