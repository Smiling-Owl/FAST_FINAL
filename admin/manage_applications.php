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

// Fetch pending student applications with student username
$sql_applications = "SELECT sa.id AS application_id,
                             sa.fullname,
                             sa.student_id,
                             sa.email,
                             sa.application_date,
                             sa.status,
                             u.username AS student_username
                     FROM student_application sa
                     INNER JOIN users u ON sa.user_id = u.id
                     WHERE sa.status = 'pending'";
$result_applications = $conn->query($sql_applications);

if ($result_applications === false) {
    echo "Error executing query: " . $conn->error;
    die();
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Student Applications - FAST Admin</title>
    <link rel="icon" type="image/x-icon" href="../images/icon.png">
    <link rel="stylesheet" href="styles/manage_applications.css">
    <script>
        function processApplication(applicationId, action) {
            fetch('process_application.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'application_id=' + encodeURIComponent(applicationId) + '&action=' + encodeURIComponent(action)
            })
            .then(response => {
                if (!response.ok) {
                    return response.text().then(text => { throw new Error('HTTP error ' + response.status + ': ' + text); });
                }
                return response.text();
            })
            .then(data => {
                alert(data); // Show a success/error message
                // Optionally, you can reload the page or update the UI dynamically
                window.location.reload(); // Simple way to refresh the list
            })
            .catch(error => {
                alert('There was an error: ' + error);
            });
        }
    </script>
</head>
<body>
    <header>
        <div class="navigation-bar">
            <div id="navigation-container">
                <img src="../images/icon.png" alt="FAST Logo">
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
        <h1>Manage Student Applications</h1>

        <div class="application-list">
            <?php
            if ($result_applications && $result_applications->num_rows > 0) {
                while ($row = $result_applications->fetch_assoc()) {
                    echo '<div class="application-item">';
                    echo '<div class="application-card-header">';
                    echo '<span class="applicant-name">' . htmlspecialchars($row['fullname']) . '</span>';
                    echo '<span class="status-badge ' . htmlspecialchars($row['status']) . '">' . ucfirst(htmlspecialchars($row['status'])) . '</span>';
                    echo '</div>';
                    echo '<div class="application-row"><span class="label">Application ID:</span><span class="value">' . htmlspecialchars($row['application_id']) . '</span></div>';
                    echo '<div class="application-row"><span class="label">Student ID:</span><span class="value">' . htmlspecialchars($row['student_id']) . '</span></div>';
                    echo '<div class="application-row"><span class="label">Email:</span><span class="value">' . htmlspecialchars($row['email']) . '</span></div>';
                    echo '<div class="application-row"><span class="label">Applied On:</span><span class="value">' . htmlspecialchars($row['application_date']) . '</span></div>';
                    echo '<div class="application-row"><span class="label">Student Username:</span><span class="value">' . htmlspecialchars($row['student_username']) . '</span></div>';
                    echo '<div class="action-buttons">';
                    echo '<button class="approve-button" onclick="processApplication(' . htmlspecialchars($row['application_id']) . ', \'approve\')">Approve</button>';
                    echo '<button class="reject-button" onclick="processApplication(' . htmlspecialchars($row['application_id']) . ', \'reject\')">Reject</button>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo '<p>No pending student applications.</p>';
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
// Close the database connection
if (isset($conn)) {
    $conn->close();
}
?>