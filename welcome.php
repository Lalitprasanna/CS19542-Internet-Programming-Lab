<?php
session_start();

// Initialize cart session if not already done
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (!isset($_SESSION['username'])) {
    header("Location: index.php"); // Redirect to login page if not logged in
    exit();
}

include 'db_connect.php'; // Database connection file

// Fetch user orders from the database
$username = $_SESSION['username'];
$sql = "SELECT * FROM orders WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

// Check if a cart item is being added
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_to_cart'])) {
    $cart_item = [
        'cement_type' => $_POST['cement_type'],
        'quantity' => (int)$_POST['quantity'],
        'total_price' => $_POST['total_price']
    ];
    // Add to session cart
    $_SESSION['cart'][] = $cart_item;
}

// Place Order
if (isset($_POST['place_order'])) {
    // Loop through the cart and process each item
    foreach ($_SESSION['cart'] as $item) {
        // Insert order into the database
        $order_sql = "INSERT INTO orders (username, cement_type, quantity, total_price, order_date) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($order_sql);
        $order_date = date('Y-m-d');
        $stmt->bind_param("ssids", $username, $item['cement_type'], $item['quantity'], $item['total_price'], $order_date);
        $stmt->execute();
    }
    // Clear the cart after placing the order
    $_SESSION['cart'] = [];
    header("Location: index.php?order_success=1"); // Redirect to a success page
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f7f6;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #2c3e50;
            overflow-x: hidden;
        }
        header {
            background-color: #2980b9;
            padding: 20px;
            text-align: center;
            color: white;
        }
        .container {
            max-width: 1200px;
            margin: 30px auto;
        }
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
            background-color: white;
        }
        .table thead th {
            background-color: #2980b9;
            color: white;
            text-align: center;
            padding: 15px;
        }
        .table tbody td {
            text-align: center;
            padding: 15px;
        }
    </style>
</head>
<body>
    <header>
        <h1>Welcome, <?php echo $_SESSION['username']; ?>!</h1>
    </header>

    <div class="container">
        <!-- Orders Section -->
        <div class="card p-4">
            <h2>Your Orders</h2>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Total Price</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>" . $row['order_id'] . "</td>
                                    <td>" . $row['cement_type'] . "</td>
                                    <td>" . $row['quantity'] . "</td>
                                    <td>₹" . $row['total_price'] . "</td>
                                    <td>" . $row['order_date'] . "</td>
                                    <td><form method='post' action='remove_order.php'><input type='hidden' name='order_id' value='" . $row['order_id'] . "'><button class='btn btn-danger'>Cancel</button></form></td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>No orders found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Order Form -->
        <div class="card p-4 mt-4">
            <h2>Order Cement</h2>
            <form action="" method="post">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="cementType" class="form-label">Select Cement Type</label>
                        <select id="cementType" name="cement_type" class="form-select" required>
                            <option value="portland_cement">Portland Cement (₹100)</option>
                            <option value="ready_mix">Ready Mix Cement (₹120)</option>
                            <option value="white_cement">White Cement (₹150)</option>
                            <option value="hydraulic_cement">Hydraulic Cement (₹130)</option>
                            <option value="fly_ash_cement">Fly Ash Cement (₹110)</option>
                            <option value="colored_cement">Colored Cement (₹140)</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="quantity" class="form-label">Quantity (in bags)</label>
                        <input type="number" id="quantity" name="quantity" class="form-control" min="1" value="1" required>
                    </div>
                    <input type="hidden" name="total_price" id="totalPrice">
                </div>
                <button type="submit" class="btn btn-primary mt-3" name="add_to_cart">Add to Cart</button>
            </form>
        </div>

        <!-- Cart Section -->
        <div class="card p-4 mt-4">
            <h2>Your Cart</h2>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Cement Type</th>
                        <th>Quantity</th>
                        <th>Total Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($_SESSION['cart'])): ?>
                        <?php foreach ($_SESSION['cart'] as $item): ?>
                            <tr>
                                <td><?php echo $item['cement_type']; ?></td>
                                <td><?php echo $item['quantity']; ?></td>
                                <td>₹<?php echo $item['total_price']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3">No items in cart.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <form method="post">
                <button type="submit" name="place_order" class="btn btn-success">Place Order</button>
            </form>
        </div>
    </div>

    <script>
        const cementPrices = {
            portland_cement: 100,
            ready_mix: 120,
            white_cement: 150,
            hydraulic_cement: 130,
            fly_ash_cement: 110,
            colored_cement: 140
        };

        function calculateTotalPrice() {
            const cementType = document.getElementById('cementType').value;
            const quantity = parseInt(document.getElementById('quantity').value);
            const totalPrice = quantity * cementPrices[cementType];
            document.getElementById('totalPrice').value = totalPrice.toFixed(2);
        }

        document.getElementById('cementType').addEventListener('change', calculateTotalPrice);
        document.getElementById('quantity').addEventListener('input', calculateTotalPrice);
        calculateTotalPrice();
    </script>
</body>
</html>
