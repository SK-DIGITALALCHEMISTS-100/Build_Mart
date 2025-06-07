<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// Database credentials
$host = "localhost";
$dbname = "build_mart";
$username = "root";
$password = "";
$port = 3306; // Update if using a custom port

$conn = new mysqli($host, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Database connection failed: " . $conn->connect_error]);
    exit;
}

// Get JSON input
$data = json_decode(file_get_contents("php://input"), true);

// Validate input
if (isset($data["name"], $data["company"], $data["email"], $data["phone"])) {
    $name = $conn->real_escape_string($data["name"]);
    $company = $conn->real_escape_string($data["company"]);
    $email = $conn->real_escape_string($data["email"]);
    $phone = $conn->real_escape_string($data["phone"]);

    // Check for existing email
    $check = $conn->query("SELECT id FROM users_regis WHERE email = '$email'");
    if ($check && $check->num_rows > 0) {
        echo json_encode(["success" => false, "message" => "Email already registered"]);
    } else {
        // Insert data
        $query = "INSERT INTO users_regis (name, company, email, phone) VALUES ('$name', '$company', '$email', '$phone')";
        if ($conn->query($query)) {
            echo json_encode(["success" => true, "message" => "User registered successfully"]);
        } else {
            echo json_encode(["success" => false, "message" => "Insert failed: " . $conn->error]);
        }
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid input data"]);
}

$conn->close();
?>
