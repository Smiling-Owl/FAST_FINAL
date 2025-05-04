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

// Fetch all classes with tutor information (joining tutors and tutor_application tables)
$sql_classes = "SELECT
                    c.class_id,
                    c.class_name,
                    c.description,
                    c.room,
                    c.timeslot_day,
                    c.timeslot_time,
                    ta.fullname AS tutor_name,
                    c.is_open
                FROM classes c
                LEFT JOIN tutors t ON c.tutor_id = t.user_id  -- Corrected JOIN condition
                LEFT JOIN tutor_application ta ON t.user_id = ta.user_id";
$result_classes = $conn->query($sql_classes);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Classes - FAST Admin</title>
    <link rel="icon" type="image/x-icon" href="../images/icon.png">
    <link rel="stylesheet" href="styles/manage_classes.css">
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


    <div class="carousel-image">
    <img src="../images/carousel_1.jpg" alt="Hero Image 1" class="carousel-slide active">
    <img src="../images/carousel_2.jpg" alt="Hero Image 2" class="carousel-slide">
    <img src="../images/carousel_3.jpg" alt="Hero Image 3" class="carousel-slide">
    <img src="../images/carousel_4.jpg" alt="Hero Image 4" class="carousel-slide">
  </div>
  
    <div class="container">
        <h1>Manage Classes</h1>

        <div class="add-new-class">
            <a href="add_class.php">Add New Class</a>
        </div>

        <div class="class-list">
            <?php
            if ($result_classes && $result_classes->num_rows > 0) {
                while ($row = $result_classes->fetch_assoc()) {
                    echo '<div class="class-item">';
                    echo '<div><strong>Class:</strong> ' . htmlspecialchars($row['class_name']) . '</div>';
                    echo '<div><strong>Description:</strong> ' . htmlspecialchars($row['description']) . '</div>';
                    echo '<div><strong>Room:</strong> ' . htmlspecialchars($row['room']) . '</div>';
                    echo '<div><strong>Day:</strong> ' . htmlspecialchars($row['timeslot_day']) . '</div>';
                    echo '<div><strong>Time:</strong> ' . htmlspecialchars(date("h:i A", strtotime($row['timeslot_time']))) . '</div>';
                    echo '<div><strong>Tutor:</strong> ' . htmlspecialchars($row['tutor_name'] ? $row['tutor_name'] : 'Not Assigned') . '</div>';
                    echo '<div><strong>Status:</strong> ' . (htmlspecialchars($row['is_open']) ? 'Open' : 'Closed') . '</div>';
                    
                    echo '<div class="button-container">';
                    echo '<a href="edit_class.php?class_id=' . htmlspecialchars($row['class_id']) . '" class="edit-button">EDIT</a>';
                    echo '<form method="post" action="process_delete_class.php">';
                    echo '<input type="hidden" name="class_id" value="' . htmlspecialchars($row['class_id']) . '">';
                    echo '<button type="submit" class="delete-button" onclick="return confirm(\'Are you sure you want to delete this class?\');">DELETE</button>';
                    echo '</form>';
                    echo '</div>';
                    
                    echo '</div>';
                }
            } else {
                echo '<p>No classes available.</p>';
            }
            ?>
        </div>
    </div>

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
