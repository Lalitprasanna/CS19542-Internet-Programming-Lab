<?php
session_start();
include 'admin_db_connect.php'; // Updated database connection file

// Initialize variables for error/success messages
$login_error = '';
$register_error = '';
$register_success = '';

// Handle Login
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $login_username = mysqli_real_escape_string($conn, $_POST['login_username']);
    $login_password = mysqli_real_escape_string($conn, $_POST['login_password']);

    // Check if the username and password match
    $sql = "SELECT * FROM admin_users WHERE username = ?";
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
            header('Location: login_register.php?login_error=Incorrect Password');
            exit();
        }
    } else {
        header('Location: login_register.php?login_error=Username not found');
        exit();
    }
}

// Handle Registration
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    $register_username = mysqli_real_escape_string($conn, $_POST['register_username']);
    $register_email = mysqli_real_escape_string($conn, $_POST['register_email']);
    $register_password = mysqli_real_escape_string($conn, $_POST['register_password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

    // Validate passwords match
    if ($register_password !== $confirm_password) {
        header('Location: login_register.php?register_error=Passwords do not match');
        exit();
    }

    // Check if the username or email is already taken
    $check_sql = "SELECT * FROM admin_users WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("ss", $register_username, $register_email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        header('Location: login_register.php?register_error=Username or Email already exists');
        exit();
    } else {
        // Hash the password and insert the user into the database
        $hashed_password = password_hash($register_password, PASSWORD_DEFAULT);
        $insert_sql = "INSERT INTO admin_users (username, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($insert_sql);
        $stmt->bind_param("sss", $register_username, $register_email, $hashed_password);

        if ($stmt->execute()) {
            header('Location: login_register.php?register_success=Registration successful! You can now login.');
            exit();
        } else {
            header('Location: login_register.php?register_error=Error registering user');
            exit();
        }
    }
}
