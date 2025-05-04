<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['subject_to_delete'])) {
    $subject_to_delete = $_POST['subject_to_delete'];

    $conn = new mysqli("localhost", "root", "", "fastdb");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and execute the delete query
    $stmt = $conn->prepare("DELETE FROM tutoring_requests WHERE subject = ?");
    $stmt->bind_param("s", $subject_to_delete);

    if ($stmt->execute()) {
        echo "All requests for subject '" . htmlspecialchars($subject_to_delete) . "' deleted successfully!";
    } else {
        echo "Error deleting requests: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request.";
}

echo '<br><a href="popular_requests.php">Back to Popular Requests</a>';
?>