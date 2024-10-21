<?php
session_start();
include 'db_connect.php'; // Database connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_SESSION['username'];
    $cement_type = $_POST['cement_type'];
    $quantity = $_POST['quantity'];
    $total_price = $_POST['total_price'];
    $order_date = $_POST['order_date'];
    $order_id = $_POST['order_id'];

    $sql = "INSERT INTO orders (order_id, username, cement_type, quantity, total_price, order_date) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issids", $order_id, $username, $cement_type, $quantity, $total_price, $order_date);

    if ($stmt->execute()) {
        header("Location: welcome.php?msg=Order placed successfully");
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
