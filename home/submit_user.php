<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

// Database Connection
$host = "localhost"; // Change if needed
$dbname = "build_mart";
$username = "root";  // Change if using different MySQL user
$password = "";      // Set your MySQL password

$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Database connection failed"]));
}

// Read JSON input from frontend
$data = json_decode(file_get_contents("php://input"), true);

// Validate input
if (isset($data["name"], $data["company"], $data["email"], $data["password"], $data["phone"])) {
    $name = $conn->real_escape_string($data["name"]);
    $company = $conn->real_escape_string($data["company"]);
    $email = $conn->real_escape_string($data["email"]);
    $password = $conn->real_escape_string($data["password"]);
    $phone = $conn->real_escape_string($data["phone"]);

    // Check if email already exists
    $check_query = "SELECT id FROM users_regis WHERE email = '$email'";
    $check_result = $conn->query($check_query);

    if ($check_result->num_rows > 0) {
        echo json_encode(["success" => false, "message" => "Email already registered"]);
    } else {
        // Insert into database
        $query = "INSERT INTO users_regis (name, company, email, password, phone) VALUES ('$name', '$company', '$email', '$password', '$phone')";
        
        if ($conn->query($query) === TRUE) {
            echo json_encode(["success" => true, "message" => "User registered successfully"]);
        } else {
            echo json_encode(["success" => false, "message" => "Error: " . $conn->error]);
        }
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid input data"]);
}

$conn->close();
?>
