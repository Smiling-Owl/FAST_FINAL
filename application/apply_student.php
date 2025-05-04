<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    $conn->close(); // Close connection before exiting
    header("Location: ../login/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$conn = new mysqli("localhost", "root", "", "fastdb");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the student has already submitted an application
$stmt_check_app = $conn->prepare("SELECT status FROM student_application WHERE user_id = ?");
$stmt_check_app->bind_param("i", $user_id);
$stmt_check_app->execute();
$stmt_check_app->store_result();

if ($stmt_check_app->num_rows > 0) {
    $stmt_check_app->bind_result($status);
    $stmt_check_app->fetch();
    $stmt_check_app->close(); // Close the statement

    if ($status === 'pending') {
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Application Pending</title>
        </head>
        <body>
            <p style='color:orange; text-align:center;'>Your student application is currently pending admin approval. You will be able to search for classes once approved.</p>
            <p style='text-align:center;'><a href='../dashboard/dashboard.php'>Back to Dashboard</a></p>
        </body>
        </html>
        <?php
    } elseif ($status === 'approved') {
        header("Location: ../search/search_classes.php");
    } elseif ($status === 'rejected') {
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Application Rejected</title>
        </head>
        <body>
            <p style='color:red; text-align:center;'>Your student application was rejected. Please contact the admin for more information.</p>
            <p style='text-align:center;'><a href='../dashboard/dashboard.php'>Back to Dashboard</a></p>
        </body>
        </html>
        <?php
    } else {
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Application Status</title>
        </head>
        <body>
            <p style='text-align:center;'>An unexpected application status occurred. Please contact the admin.</p>
            <p style='text-align:center;'><a href='../dashboard/dashboard.php'>Back to Dashboard</a></p>
        </body>
        </html>
        <?php
    }
} else {
    // No application found, redirect to the student application form
    header("Location: student_app.php");
}

$conn->close(); // Connection closure after the main logic
exit();
?>