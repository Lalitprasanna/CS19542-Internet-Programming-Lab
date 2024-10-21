<?php
session_start();

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

// Fetch product data for stock updates
$products_query = "SELECT * FROM products";
$products_result = mysqli_query($conn, $products_query);

// Set a maximum stock level for percentage calculations
$max_stock = 500;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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
        .stock-percentage {
            color: green;
            font-weight: bold;
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

        <!-- Stock Update Section -->
        <div class="card p-4 mt-4">
    <h2>Update Stock Levels</h2>
    <form method="POST" action="update_stock.php">
        <div class="row g-3">
            <div class="col-md-6">
                <label for="product_id" class="form-label">Select Product</label>
                <select id="product_id" name="product_id" class="form-select" required>
                    <option value="" disabled selected>Select a product</option>
                    <option value="1">Portland Cement (Price: ₹100)</option>
                    <option value="2">Ready Mix (Price: ₹120)</option>
                    <option value="3">White Cement (Price: ₹150)</option>
                    <option value="4">Hydraulic Cement (Price: ₹130)</option>
                    <option value="5">Fly Ash Cement (Price: ₹110)</option>
                    <option value="6">Colored Cement (Price: ₹140)</option>
                </select>
            </div>
            <div class="col-md-6">
                <label for="new_stock" class="form-label">New Stock Level</label>
                <input type="number" id="new_stock" name="new_stock" class="form-control" required>
            </div>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Update Stock</button>
    </form>
</div>

</script>


        <!-- Cement Type Selection for Total Amount Calculation -->
        <div class="card p-4 mt-4">
            <h2>Select Cement Type and Quantity</h2>
            <form method="POST" action="calculate_total.php">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="cementType" class="form-label">Select Cement Type</label>
                        <select id="cementType" name="cement_type" class="form-select">
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
                        <input type="number" id="quantity" name="quantity" class="form-control" min="1" value="1">
                    </div>
                </div>
                <button type="submit" class="btn btn-success mt-3">Calculate Total</button>
            </form>
        </div>
    </div>

    <!-- Stock and Cement Price Calculation JavaScript -->
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
