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
    <link rel="icon" type="image/x-icon" href="../images/icon.png">
    <link rel="stylesheet" href="styles/popular_request.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&family=Lora:wght@400;700&family=Oswald:wght@400;700&family=PT+Sans:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <div class="navigation-bar">
            <div id="navigation-container">
            <img src="../images/icon.png" alt="FAST Logo">
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

    <div class="container">
        <h1>Popular Subject Requests</h1>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Subject</th>
                        <th>Requests</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result && $result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo '<tr>';
                            echo '<td>' . htmlspecialchars($row['subject']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['request_count']) . '</td>';
                            echo '<td>
                                    <form method="post" action="process_delete_request.php" style="display:inline;">
                                        <input type="hidden" name="subject_to_delete" value="' . htmlspecialchars($row['subject']) . '">
                                        <button type="submit" onclick="return confirm(\'Are you sure you want to delete all requests for this subject?\');">Delete</button>
                                    </form>
                                  </td>';
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr><td colspan="3">No tutoring requests yet.</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
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
