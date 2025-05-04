<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../login.html");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_SESSION['username'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    $conn = new mysqli("localhost", "root", "", "fastdb");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("INSERT INTO reports (username, subject, message, date_submitted) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("sss", $username, $subject, $message);

    if ($stmt->execute()) {
        echo "<script>alert('Report submitted successfully!'); window.location.href='dashboard.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
