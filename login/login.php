<?php
session_start();
$conn = new mysqli("localhost", "root", "", "fastdb");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error = ""; // variable to hold error messages

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['username'] = $username;
            $_SESSION['user_id'] = $id;
            header("Location: ../dashboard/dashboard.php");
            exit();
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "User not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>FAST Student Login</title>
    <link rel="icon" type="image/x-icon" href="../images/FAST Logo Trans.png">
    <link rel="stylesheet" type="text/css" href="login_style.css">

    <!-- Google Fonts (combined) -->
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

        <!-- Display the error message if exists -->
        <?php if (!empty($error)): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>

        <form id="loginForm" action="login.php" method="post">
            <input type="text" id="username" name="username" placeholder="Student's Username" required>
            <input type="password" id="password" name="password" placeholder="Password" required>
            <button type="submit">Log In</button>
        </form>

        <div class="links">
            <a href="#FAST OFFICER CONTACT INFO">Forget My Password</a>
            <a href="../register/register.php">No Account? Be a FAST's STUDENT Now!</a>
        </div>
    </div>

    <script src="login_js.js"></script>
</body>
</html>
