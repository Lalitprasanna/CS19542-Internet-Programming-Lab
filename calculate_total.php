<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php"); // Redirect to login page if not logged in
    exit();
}

// Define the cement prices
$cementPrices = [
    "portland_cement" => 100,
    "ready_mix" => 120,
    "white_cement" => 150,
    "hydraulic_cement" => 130,
    "fly_ash_cement" => 110,
    "colored_cement" => 140,
];

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve the selected cement type and quantity
    $cementType = $_POST['cement_type'];
    $quantity = intval($_POST['quantity']);
    
    // Calculate the total price
    $totalPrice = $cementPrices[$cementType] * $quantity;

    // Display the result
    echo "<!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Total Price Calculation</title>
        <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css' rel='stylesheet'>
        <style>
            body {
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                color: #2c3e50;
                background-color: #f4f7f6;
            }
            .container {
                max-width: 600px;
                margin: 50px auto;
                padding: 20px;
                background-color: white;
                border-radius: 10px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }
        </style>
    </head>
    <body>
        <div class='container'>
            <h2>Total Price Calculation</h2>
            <p>You have selected <strong>{$quantity}</strong> bags of <strong>{$cementType}</strong>.</p>
            <p>The total price is: <strong>₹{$totalPrice}</strong></p>
            <a href='admin_dashboard.php' class='btn btn-primary'>Back to Dashboard</a>
        </div>
    </body>
    </html>";
}
?>
