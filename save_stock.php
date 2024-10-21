<?php
// Database connection
$host = 'localhost';
$dbname = 'cement_orders';
$username = 'root'; // Your MySQL username
$password = ''; // Your MySQL password (empty for XAMPP)

$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Debugging: Log the request method and POST data
error_log("Request method: " . $_SERVER['REQUEST_METHOD']); // Logs the request method
error_log("POST data: " . print_r($_POST, true)); // Logs all POST data

// Check if POST request is made
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    echo json_encode(["status" => "error", "message" => "Invalid request method: " . $_SERVER['REQUEST_METHOD']]);
    exit;
}

if (empty($_POST)) {
    echo json_encode(["status" => "error", "message" => "No POST data received."]);
    exit;
}

// Get the data from the POST request
$cementType = $_POST['cement_type'];
$stock = intval($_POST['stock']);

// Check if the cement type already exists
$checkStock = "SELECT * FROM stock WHERE cement_type = ?";
$stmt = $conn->prepare($checkStock);
$stmt->bind_param('s', $cementType);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // If cement type exists, update the stock
    $updateStock = "UPDATE stock SET stock = stock + ? WHERE cement_type = ?";
    $stmt = $conn->prepare($updateStock);
    $stmt->bind_param('is', $stock, $cementType);
    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Stock updated successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to update stock."]);
    }
} else {
    // If cement type does not exist, insert new stock
    $insertStock = "INSERT INTO stock (cement_type, stock) VALUES (?, ?)";
    $stmt = $conn->prepare($insertStock);
    $stmt->bind_param('si', $cementType, $stock);
    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Stock added successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to add stock."]);
    }
}

$conn->close();
?>
