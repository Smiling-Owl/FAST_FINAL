<?php
// Start the session (if not already started)
session_start();

// Check if the admin is logged in (you might want to reuse your admin authentication logic here)
if (!isset($_SESSION['admin_id'])) {
    http_response_code(403); // Forbidden
    echo "Access denied.";
    exit();
}

// Check if the application ID and action are provided via POST
if (isset($_POST['application_id']) && isset($_POST['action'])) {
    $applicationId = $_POST['application_id'];
    $action = $_POST['action'];

    // Database connection
    $conn = new mysqli("localhost", "root", "", "fastdb");

    if ($conn->connect_error) {
        http_response_code(500); // Internal Server Error
        echo "Database connection failed: " . $conn->connect_error;
        exit();
    }

    $newStatus = '';
    if ($action === 'approve') {
        $newStatus = 'approved';
    } elseif ($action === 'reject') {
        $newStatus = 'rejected';
    } else {
        http_response_code(400); // Bad Request
        echo "Invalid action.";
        $conn->close();
        exit();
    }

    // Update the student_application table
    $sql = "UPDATE student_application SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $newStatus, $applicationId);

    if ($stmt->execute()) {
        echo "Application " . htmlspecialchars($action) . " successfully.";
    } else {
        http_response_code(500); // Internal Server Error
        echo "Error updating application status: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();

} else {
    http_response_code(400); // Bad Request
    echo "Application ID or action not provided.";
}
?>