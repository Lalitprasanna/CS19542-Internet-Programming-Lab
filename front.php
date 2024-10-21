<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to 𝕮𝖊𝖒𝖊𝖓𝖙𝕮𝖔𝖗𝖕 €</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            height: 100vh;
            background: url('background_image.png.jpg') no-repeat center center fixed; /* Background image */
            background-size: cover; /* Cover the entire viewport */
            overflow: hidden; /* Prevent scrolling */
        }
        .main-container {
            position: relative;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #fff;
            text-align: center;
        }
        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5); /* Dark overlay */
            z-index: 1;
        }
        .content-box {
            position: relative;
            z-index: 2; /* Above the overlay */
            background: rgba(255, 255, 255, 0.8);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            max-width: 600px;
        }
        .company-name {
            font-size: 36px;
            margin-bottom: 20px;
            font-weight: bold;
        }
        .section-title {
            font-size: 24px;
            margin-top: 20px;
            color: #333;
        }
        .section-content {
            font-size: 18px;
            color: #555;
            margin-bottom: 20px;
        }
        .btn {
            font-size: 18px;
            padding: 10px 20px;
            margin: 10px 5px;
            transition: background-color 0.3s ease;
        }
        .btn-admin {
            background-color: #007bff; /* Admin button color */
            color: white;
        }
        .btn-user {
            background-color: #28a745; /* User button color */
            color: white;
        }
        .btn:hover {
            opacity: 0.8; /* Hover effect */
        }
    </style>
</head>
<body>

<div class="main-container">
    <div class="overlay"></div>
    <div class="content-box">
        <div class="company-name">𝕮𝖊𝖒𝖊𝖓𝖙𝕮𝖔𝖗𝖕 €</div>

        <div class="section-title">Welcome to Our Cement Ordering System</div>
        <div class="section-content">
            This platform simplifies the management of cement orders and stock for both users and administrators. Users can log in to track their orders, while administrators can manage inventory and deliveries.
        </div>

        <div class="section-title">Features</div>
        <div class="section-content">
            - Real-time stock updates<br>
            - User-friendly order tracking<br>
            - Admin management tools for inventory and orders<br>
            - Simple and intuitive interface
        </div>

        <a href="login_register.php" class="btn btn-admin">Admin Login</a>
        <a href="index.php" class="btn btn-user">User Login</a>
    </div>
</div>

<script>
    // Function to generate random motion for multiple balls
    function generateRandomBalls(numBalls) {
        const container = document.createElement('div');
        container.setAttribute('id', 'ball-container');
        document.body.appendChild(container);

        for (let i = 0; i < numBalls; i++) {
            const ball = document.createElement('div');
            ball.classList.add('ball');
            ball.style.width = `${Math.random() * 40 + 20}px`; // Random size
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

    // Generate 15 random motion balls
    generateRandomBalls(15);
</script>

</body>
</html>
