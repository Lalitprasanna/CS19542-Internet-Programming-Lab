<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+Knujsl5+5hb5og2l5Xv15pGp5W4U1P2lsJOM3+jZx+dXy" crossorigin="anonymous">
    
    <style>
        /* Background motion balls */
        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f7f7f7;
            position: relative;
        }

        /* Styling form container */
        .login-container {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
            position: relative;
            z-index: 1;
        }

        /* Company name styling */
        .company-name {
            font-family: 'Times New Roman', serif;
            font-size: 28px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 30px;
        }

        /* Styling buttons and labels */
        .btn-custom {
            background-color: #007bff;
            color: #fff;
            padding: 10px 0;
            font-size: 16px;
        }

        /* Spacing for form fields */
        .form-group label {
            font-weight: bold;
            margin-bottom: 10px;
        }

        .form-control {
            height: 40px;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            font-size: 14px;
        }

        /* Ball animation */
        .ball {
            position: absolute;
            border-radius: 50%;
            background-color: rgba(0, 123, 255, 0.7);
            animation: move 6s infinite;
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

        /* Motion ball animation */
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
    <div class="login-container">
        <div class="company-name">
            𝕮𝖊𝖒𝖊𝖓𝖙𝕮𝖔𝖗𝖕 €
        </div>
        <h2 class="text-center mb-4">Login</h2>
        <form action="login.php" method="post">
            <div class="form-group mb-3">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" class="form-control" required>
            </div>
            <div class="form-group mb-3">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
            <div class="d-grid">
                <input type="submit" class="btn btn-primary btn-custom btn-block" value="Login">
            </div>
        </form>
        <p class="text-center mt-3">Don't have an account? <a href="register.php">Register here</a></p>
    </div>

    <!-- Bootstrap JS and Popper.js (optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-Q7I/7tnQ/mDfuTXjy8KzCk7eq9uJk6+KvIkPQgXl6G9y8FwTM30XwBs3LRKJEgdf" crossorigin="anonymous"></script>
</body>
</html>
