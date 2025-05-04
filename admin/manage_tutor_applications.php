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

// Fetch pending tutor applications
$sql_tutor_application = "SELECT ta.id AS application_id,
                                   ta.fullname,
                                   ta.student_id,
                                   ta.birthdate,
                                   ta.email,
                                   ta.fb_link,
                                   ta.application_date,
                                   ta.course_excel,
                                   ta.why,
                                   ta.status
                            FROM tutor_application ta
                            WHERE ta.status = 'pending'";
$result_tutor_application = $conn->query($sql_tutor_application);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Tutor Applications - FAST Admin</title>
    <link rel="icon" type="image/x-icon" href="../images/FAST logo white trans.png">
    <link href="https://fonts.googleapis.com/css2?family=PT+Sans&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles/manage_tutor_applications.css">
   
    <script>
        function processTutorApplication(applicationId, action) {
            fetch('process_tutor_application.php', { // New PHP file for tutor applications
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
                window.location.reload(); // Reload the page
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
                    <li><a href="admin_logout.php">LOG OUT</a></li>
                </ul>
            </div>
        </div>
    </header>

    <div class="container">
        <h1>Manage Tutor Applications</h1>

        <div class="application-list">
            <?php
            if (
                $result_tutor_application && $result_tutor_application->num_rows > 0
            ) {
                while ($row = $result_tutor_application->fetch_assoc()) {
                    echo '<div class="application-item">';
                    echo '<div class="application-row"><span class="label">Application ID:</span><span class="value">' . htmlspecialchars($row['application_id']) . '</span></div>';
                    echo '<div class="application-row"><span class="label">Full Name:</span><span class="value">' . htmlspecialchars($row['fullname']) . '</span></div>';
                    echo '<div class="application-row"><span class="label">Student ID:</span><span class="value">' . htmlspecialchars($row['student_id']) . '</span></div>';
                    echo '<div class="application-row"><span class="label">Birthdate:</span><span class="value">' . htmlspecialchars($row['birthdate']) . '</span></div>';
                    echo '<div class="application-row"><span class="label">Email:</span><span class="value">' . htmlspecialchars($row['email']) . '</span></div>';
                    echo '<div class="application-row"><span class="label">Facebook Link:</span><span class="value">' . htmlspecialchars($row['fb_link']) . '</span></div>';
                    echo '<div class="application-row"><span class="label">Applied On:</span><span class="value">' . htmlspecialchars($row['application_date']) . '</span></div>';
                    echo '<div class="application-row"><span class="label">Expertise:</span><span class="value">' . htmlspecialchars($row['course_excel']) . '</span></div>';
                    echo '<div class="application-row"><span class="label">Why Tutor:</span><span class="value">' . htmlspecialchars($row['why']) . '</span></div>';
                    echo '<div class="application-row"><span class="label">Status:</span><span class="value">' . htmlspecialchars($row['status']) . '</span></div>';
                    echo '<div class="action-buttons">';
                    echo '<button class="approve-button" onclick="processTutorApplication(' . htmlspecialchars($row['application_id']) . ', \'approve\')">Approve</button>';
                    echo '<button class="reject-button" onclick="processTutorApplication(' . htmlspecialchars($row['application_id']) . ', \'reject\')">Reject</button>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo '<p>No pending tutor applications.</p>';
            }
            ?>
        </div>
    </div>

    <div class="carousel-image">
        <img src="../images/carousel_1.jpg" alt="Hero Image 1" class="carousel-slide">
        <img src="../images/carousel_2.jpg" alt="Hero Image 2" class="carousel-slide">
        <img src="../images/carousel_3.jpg" alt="Hero Image 3" class="carousel-slide">
        <img src="../images/carousel_4.jpg" alt="Hero Image 4" class="carousel-slide">
    </div>
    <div class="carousel-overlay"></div>

    <footer>
        <div class="footer-content">
            <p>&copy; <?php echo date("Y"); ?> Foundation of Ateneo Student Tutors - Admin Area</p>
        </div>
    </footer>

    <script src="../index.js"></script>
</body>
</html>

<?php
// Close the database connection
if (isset($conn)) {
    $conn->close();
}
?>