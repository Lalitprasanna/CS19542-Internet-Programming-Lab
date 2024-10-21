<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "login";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    // Check if username already exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "Username already exists. Please choose another.";
    } else {
        // Hash the password for security
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare and bind
        $stmt = $conn->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $hashed_password, $email);

        // Execute the statement
        if ($stmt->execute()) {
            echo "Registration successful! You can now <a href='index.php'>login</a>.";
        } else {
            echo "Error: " . $stmt->error;
        }
    }

    // Close the statement and connection
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Page</title>
    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+Knujsl5+5hb5og2l5Xv15pGp5W4U1P2lsJOM3+jZx+dXy" crossorigin="anonymous">
    
    <style>
        /* Styling body to take full height and apply background */
        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f7f7f7;
            font-family: Arial, sans-serif;
            position: relative;
            overflow: hidden;
        }

        /* Styling form container */
        .register-container {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.2);
            max-width: 400px;
            width: 100%;
        }

        /* Bold labels */
        .form-group label {
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 10px;
        }

        /* Input fields styling */
        .form-control {
            height: 40px;
            font-size: 14px;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ced4da;
            margin-bottom: 15px;
        }

        /* Button styling */
        .btn-custom {
            background-color: #28a745;
            color: white;
            padding: 12px;
            font-size: 16px;
            border-radius: 5px;
            width: 100%;
        }

        /* Styling for the company name */
        .company-name {
            font-family: 'Times New Roman', serif;
            font-size: 28px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
        }

        /* Background motion balls */
        .ball {
            position: absolute;
            border-radius: 50%;
            background-color: rgba(40, 167, 69, 0.7); /* Greenish ball */
            animation: move 6s infinite ease-in-out;
        }

        .ball-1 {
            width: 150px;
            height: 150px;
            top: -75px;
            left: -75px;
        }

        .ball-2 {
            width: 100px;
            height: 100px;
            bottom: -50px;
            right: -50px;
        }

        /* Animation for motion effect */
        @keyframes move {
            0% { transform: translateY(0); }
            50% { transform: translateY(30px); }
            100% { transform: translateY(0); }
        }
    </style>
</head>
<body>
    <!-- Motion ball background -->
    <div class="ball ball-1"></div>
    <div class="ball ball-2"></div>

    <!-- Form container -->
    <div class="register-container">
        <div class="company-name">
            𝕮𝖊𝖒𝖊𝖓𝖙𝕮𝖔𝖗𝖕 €
        </div>
        <h2 class="text-center mb-4">Register</h2>
        <form action="" method="post">
            <div class="form-group mb-3">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" class="form-control" required>
            </div>
            <div class="form-group mb-3">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
            <div class="form-group mb-3">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>
            <div class="d-grid">
                <input type="submit" class="btn btn-success btn-custom" value="Register">
            </div>
        </form>
    </div>

    <!-- Bootstrap JS and Popper.js (optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-Q7I/7tnQ/mDfuTXjy8KzCk7eq9uJk6+KvIkPQgXl6G9y8FwTM30XwBs3LRKJEgdf" crossorigin="anonymous"></script>
</body>
</html>
