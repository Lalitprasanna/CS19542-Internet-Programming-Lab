<?php
session_start();
include 'admin_db_connect.php'; // Ensure this file connects to your database

// Initialize variables for error/success messages
$login_error = '';
$register_error = '';
$register_success = '';

// Handle Login
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $login_username = mysqli_real_escape_string($conn, $_POST['login_username']);
    $login_password = mysqli_real_escape_string($conn, $_POST['login_password']);

    // Check if the username and password match
    $sql = "SELECT * FROM admin_user WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $login_username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($login_password, $row['password'])) {
            $_SESSION['username'] = $login_username; // Start session
            header('Location: admin_dashboard.php'); // Redirect to dashboard
            exit();
        } else {
            $login_error = 'Incorrect Password';
        }
    } else {
        $login_error = 'Username not found';
    }
}

// Handle Registration
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    $register_username = mysqli_real_escape_string($conn, $_POST['register_username']);
    $register_email = mysqli_real_escape_string($conn, $_POST['register_email']);
    $register_password = mysqli_real_escape_string($conn, $_POST['register_password']);

    // Check if the username or email is already taken
    $check_sql = "SELECT * FROM admin_user WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("ss", $register_username, $register_email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $register_error = 'Username or Email already exists';
    } else {
        // Hash the password and insert the user into the database
        $hashed_password = password_hash($register_password, PASSWORD_DEFAULT);
        $insert_sql = "INSERT INTO admin_user (username, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($insert_sql);
        $stmt->bind_param("sss", $register_username, $register_email, $hashed_password);

        if ($stmt->execute()) {
            $register_success = 'Registration successful! You can now login.';
        } else {
            $register_error = 'Error registering user';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login & Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            overflow: hidden; /* Prevent scrolling */
            position: relative;
        }
        .container {
            width: 300px;
            padding: 40px;
            background-color: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            position: relative; /* Ensure it stays above the balls */
            z-index: 1;
        }
        .container h2 {
            margin-bottom: 20px;
            text-align: center;
        }
        .input-box {
            margin-bottom: 15px;
        }
        .input-box input {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
        }
        .btn {
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #218838;
        }
        .error, .success {
            text-align: center;
            margin-bottom: 10px;
            font-weight: bold;
        }
        .error {
            color: red;
        }
        .success {
            color: green;
        }
        .toggle-link {
            text-align: center;
            margin-top: 10px;
            cursor: pointer;
            color: #007bff;
        }
        .toggle-link:hover {
            text-decoration: underline;
        }

        /* Motion ball styling */
        .ball {
            position: absolute;
            width: 50px;
            height: 50px;
            background-color: #ff5722;
            border-radius: 50%;
            z-index: 0; /* Behind the content */
        }

        /* Company name styling */
        .company-name {
            position: absolute;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            text-align: center;
            font-size: 30px;
            font-weight: bold;
            color: #333;
            font-family: 'Courier New', Courier, monospace;
        }
    </style>
</head>
<body>

    <!-- Random motion balls will be generated dynamically -->
    <div id="ball-container"></div>

    <!-- Company Name -->
    <div class="company-name">𝕮𝖊𝖒𝖊𝖓𝖙𝕮𝖔𝖗𝖕 €</div>

    <!-- Login Section -->
    <div class="container" id="login-section">
        <h2>Admin Login</h2>
        <form method="POST" action="">
            <div class="input-box">
                <input type="text" name="login_username" placeholder="Username" required>
            </div>
            <div class="input-box">
                <input type="password" name="login_password" placeholder="Password" required>
            </div>
            <button type="submit" name="login" class="btn">Login</button>
        </form>

        <!-- Display error message for login -->
        <?php if (!empty($login_error)) { ?>
            <p class="error"><?php echo $login_error; ?></p>
        <?php } ?>

        <!-- Link to switch to Register -->
        <div class="toggle-link" onclick="toggleForm('register')">Don't have an account? Register here</div>
    </div>

    <!-- Register Section -->
    <div class="container" id="register-section" style="display: none;">
        <h2>Register</h2>
        <form method="POST" action="">
            <div class="input-box">
                <input type="text" name="register_username" placeholder="Username" required>
            </div>
            <div class="input-box">
                <input type="email" name="register_email" placeholder="Email" required>
            </div>
            <div class="input-box">
                <input type="password" name="register_password" placeholder="Password" required>
            </div>
            <button type="submit" name="register" class="btn">Register</button>
        </form>

        <!-- Display error or success message for registration -->
        <?php if (!empty($register_error)) { ?>
            <p class="error"><?php echo $register_error; ?></p>
        <?php } elseif (!empty($register_success)) { ?>
            <p class="success"><?php echo $register_success; ?></p>
        <?php } ?>

        <!-- Link to switch to Login -->
        <div class="toggle-link" onclick="toggleForm('login')">Already have an account? Login here</div>
    </div>

<script>
    function toggleForm(form) {
        if (form === 'login') {
            document.getElementById('login-section').style.display = 'block';
            document.getElementById('register-section').style.display = 'none';
        } else {
            document.getElementById('login-section').style.display = 'none';
            document.getElementById('register-section').style.display = 'block';
        }
    }

    // Function to generate random motion for multiple balls
    function generateRandomBalls(numBalls) {
        const container = document.getElementById('ball-container');
        for (let i = 0; i < numBalls; i++) {
            const ball = document.createElement('div');
            ball.classList.add('ball');
            ball.style.width = `${Math.random() * 40 + 10}px`; // Random size
            ball.style.height = ball.style.width;
            ball.style.top = `${Math.random() * 100}vh`; // Random initial position
            ball.style.left = `${Math.random() * 100}vw`;
            container.appendChild(ball);

            animateBall(ball); // Call the function to animate the ball
        }
    }

    // Function to animate a single ball with random motion
    function animateBall(ball) {
        const randomX = Math.random() * (window.innerWidth - parseFloat(ball.style.width));
        const randomY = Math.random() * (window.innerHeight - parseFloat(ball.style.height));
        const randomDuration = Math.random() * 5 + 3; // Between 3 and 8 seconds

        ball.animate([
            { transform: `translate(${randomX}px, ${randomY}px)` },
            { transform: `translate(${Math.random() * window.innerWidth}px, ${Math.random() * window.innerHeight}px)` }
        ], {
            duration: randomDuration * 1000,
            iterations: Infinity,
            direction: 'alternate',
            easing: 'ease-in-out'
        });
    }

    // Generate 10 random balls
    generateRandomBalls(10);
</script>

</body>
</html>
