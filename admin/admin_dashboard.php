<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$admin_username = $_SESSION['admin_username'] ?? 'Admin';

// Database connection
$conn = new mysqli("localhost", "root", "", "fastdb");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch count of pending student applications
$sql_applications = "SELECT COUNT(*) AS pending_applications FROM student_application WHERE status = 'pending'";
$result_applications = $conn->query($sql_applications);
if ($result_applications === false) {
    echo "Error executing query (applications): " . $conn->error;
    die();
}
$pending_applications = $result_applications->fetch_assoc()['pending_applications'] ?? 0;

// Fetch count of open classes
$sql_classes = "SELECT COUNT(*) AS open_classes FROM classes WHERE is_open = TRUE";
$result_classes = $conn->query($sql_classes);
if ($result_classes === false) {
    echo "Error executing query (classes): " . $conn->error;
    die();
}
$open_classes = $result_classes->fetch_assoc()['open_classes'] ?? 0;

// Fetch count of pending join requests
$sql_requests = "SELECT COUNT(*) AS pending_requests FROM student_classes WHERE enrollment_status = 'pending'";
$result_requests = $conn->query($sql_requests);
if ($result_requests === false) {
    echo "Error executing query (requests): " . $conn->error;
    die();
}
$pending_requests = $result_requests->fetch_assoc()['pending_requests'] ?? 0;

// Fetch count of total users
$sql_users = "SELECT COUNT(*) AS total_users FROM users";
$result_users = $conn->query($sql_users);
if ($result_users === false) {
    echo "Error executing query (users): " . $conn->error;
    die();
}
$total_users = $result_users->fetch_assoc()['total_users'] ?? 0;

// Fetch submitted reports
$sql_reports = "SELECT username, subject, message, date_submitted FROM reports ORDER BY date_submitted DESC";
$result_reports = $conn->query($sql_reports);
if ($result_reports === false) {
    echo "Error executing query (reports): " . $conn->error;
    die();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAST Admin Dashboard</title>
    <link rel="icon" type="image/x-icon" href="../images/FAST logo white trans.png">
    <link rel="stylesheet" href="styles/admin_dashboard_style.css">
</head>

<body>
    <header>
      <div class="navigation_bar">
        <img src="../images/FAST logo white trans.png" alt="FAST Logo">
      
        <div class="nav_menu_wrapper">
          <ul>
            <li><a href="admin_dashboard.php">ADMIN DASHBOARD</a></li>
            <li><a href="popular_requests.php">POPULAR REQUESTS</a></li>
            <li><a href="manage_applications.php">MANAGE APPLICATIONS</a></li>
            <li><a href="manage_tutor_applications.php">MANAGE TUTOR APPLICATIONS</a></li>
            <li><a href="manage_classes.php">MANAGE CLASSES</a></li>
            <li><a href="logout.php">LOG OUT</a></li>
          </ul>
        </div>
      </div>
    </header>
    
  <div class="carousel-image">
    <img src="../images/carousel_1.jpg" alt="Hero Image 1" class="carousel-slide active">
    <img src="../images/carousel_2.jpg" alt="Hero Image 2" class="carousel-slide">
    <img src="../images/carousel_3.jpg" alt="Hero Image 3" class="carousel-slide">
    <img src="../images/carousel_4.jpg" alt="Hero Image 4" class="carousel-slide">
  </div>

    <div class="container">
        <h1>Welcome, <?php echo htmlspecialchars($admin_username); ?>!</h1>

        <div class="dashboard-widgets">
            <div class="widget">
                <h3>Pending Student Applications</h3>
                <p><?php echo htmlspecialchars($pending_applications); ?></p>
            </div>
            <div class="widget">
                <h3>Open Classes</h3>
                <p><?php echo htmlspecialchars($open_classes); ?></p>
            </div>
            <div class="widget">
                <h3>Pending Join Requests</h3>
                <p><?php echo htmlspecialchars($pending_requests); ?></p>
            </div>
            <div class="widget">
                <h3>Total Users</h3>
                <p><?php echo htmlspecialchars($total_users); ?></p>
            </div>
        </div>
    </div>

    <div class="reports-section">
    <h2 id="reports_header">SUBMITTED REPORTS!</h2>
    <?php if ($result_reports->num_rows > 0): ?>
        <div class="reports-table-container">
            <table class="reports-table">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Subject</th>
                        <th>Message</th>
                        <th>Date Submitted</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result_reports->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['username']); ?></td>
                            <td><?php echo htmlspecialchars($row['subject']); ?></td>
                            <td><?php echo nl2br(htmlspecialchars($row['message'])); ?></td>
                            <td><?php echo htmlspecialchars($row['date_submitted']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p>No reports have been submitted.</p>
    <?php endif; ?>
</div>



    <footer>
        <div class="footer-content">
            <p>&copy; <?php echo date("Y"); ?> Foundation of Ateneo Student Tutors - Admin Area</p>
        </div>
    </footer>

    <script src="../index.js"></script>

</body>
</html>
