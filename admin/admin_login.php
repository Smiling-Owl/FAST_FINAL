<!DOCTYPE html>
<html lang="en">
<head>
    <title>FAST Admin Login</title>
    <link rel="icon" type="image/x-icon" href="../images/icon.png">
    <link rel="stylesheet" type="text/css" href="styles/admin_login_style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100..900&family=Lora:wght@400..700&family=Oswald:wght@200..700&family=PT+Sans:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="carousel-image">
        <img src="../images/carousel_1.jpg" alt="Hero Image 1" class="carousel-slide">
        <img src="../images/carousel_2.jpg" alt="Hero Image 2" class="carousel-slide">
        <img src="../images/carousel_3.jpg" alt="Hero Image 3" class="carousel-slide">
        <img src="../images/carousel_4.jpg" alt="Hero Image 4" class="carousel-slide">
    </div>

    <div class="container">
        <div class="logo">
            <img src="../images/LOGIN FAST.png" alt="FAST Logo">
        </div>

        <?php
        if (isset($_GET['error'])) {
            echo '<div class="error-message">' . htmlspecialchars($_GET['error']) . '</div>';
        }
        ?>

        <form id="adminLoginForm" action="admin_auth.php" method="post">
            <input type="text" id="username" name="username" placeholder="Admin Username" required>
            <input type="password" id="password" name="password" placeholder="Password" required>
            <button type="submit">Admin Log In</button>
        </form>

        <div class="links">
            <a href="../login/login.php">Log in as Student</a>
        </div>
    </div>

    <script src="admin_login.js"></script>
</body>
</html>