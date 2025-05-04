<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Database connection
$conn = new mysqli("localhost", "root", "", "fastdb");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to count the occurrences of each requested subject
$sql = "SELECT subject, COUNT(*) AS request_count
        FROM tutoring_requests
        GROUP BY subject
        ORDER BY request_count DESC";

$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Popular Subject Requests - FAST Admin</title>
    <link rel="icon" type="image/x-icon" href="../images/FAST logo white trans.png">
    <link rel="stylesheet" href="styles/popular_request.css">
</head>
<body>
    <header>
        <div class="navigation-bar">
            <div id="navigation-container">
                <img src="../images/FAST Logo Trans.png" alt="FAST Logo">
                <ul>
                    <li><a href="admin_dashboard.php" aria-label="Admin Dashboard">ADMIN DASHBOARD</a></li>
                    <li><a href="popular_requests.php" aria-label="Popular Requests">POPULAR REQUESTS</a></li>
                    <li><a href="manage_applications.php" aria-label="Manage Applications">MANAGE APPLICATIONS</a></li>
                    <li><a href="manage_tutor_applications.php" aria-label="Manage Tutor Applications">MANAGE TUTOR APPLICATIONS</a></li>
                    <li><a href="manage_classes.php" aria-label="Manage Classes">MANAGE CLASSES</a></li>
                    <li><a href="logout.php">LOG OUT</a></li>
                </ul>
            </div>
        </div>
    </header>

    <div class="container">
        <h1>Popular Subject Requests</h1>

        <div class="request-list">
            <?php
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="request-item">';
                    echo '<p>Subject: ' . htmlspecialchars($row['subject']) . '</p>';
                    echo '<span>Requests: ' . htmlspecialchars($row['request_count']) . '</span>';
                    echo '<form method="post" action="process_delete_request.php" style="display:inline;">';
                    echo '<input type="hidden" name="subject_to_delete" value="' . htmlspecialchars($row['subject']) . '">';
                    echo '<button type="submit" onclick="return confirm(\'Are you sure you want to delete all requests for this subject?\');">Delete</button>';
                    echo '</form>';
                    echo '</div>';
                }
            } else {
                echo '<p>No tutoring requests yet.</p>';
            }
            ?>
        </div>
    </div>

    <footer>
        <div class="footer-content">
            <p>&copy; <?php echo date("Y"); ?> Foundation of Ateneo Student Tutors - Admin Area</p>
        </div>
    </footer>

</body>
</html>

<?php
$conn->close();
?>