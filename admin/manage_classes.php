<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Classes - FAST Admin</title>
    <link rel="icon" type="image/x-icon" href="../images/FAST logo white trans.png">
    <link rel="icon" type="image/x-icon" href="../images/FAST logo white trans.png">
    <link rel="icon" type="image/x-icon" href="/Main-images/FAST logo white trans.png">
    <link rel="stylesheet" href="styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@200..700&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=PT+Sans:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style/manage_classes.css">
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
        <h1>Manage Classes</h1>

        <div class="add-new-class">
            <a href="add_class.php">Add New Class</a>
        </div>

        <div class="class-list">
        <?php
        if ($result_classes && $result_classes->num_rows > 0) {
            while ($row = $result_classes->fetch_assoc()) {
                echo '<table class="vertical-table">';
                echo '<tr><th>Class</th><td>' . htmlspecialchars($row['class_name']) . '</td></tr>';
                echo '<tr><th>Description</th><td>' . htmlspecialchars($row['description']) . '</td></tr>';
                echo '<tr><th>Room</th><td>' . htmlspecialchars($row['room']) . '</td></tr>';
                echo '<tr><th>Day</th><td>' . htmlspecialchars($row['timeslot_day']) . '</td></tr>';
                echo '<tr><th>Time</th><td>' . htmlspecialchars(date("h:i A", strtotime($row['timeslot_time']))) . '</td></tr>';
                echo '<tr><th>Tutor</th><td>' . htmlspecialchars($row['tutor_name'] ?: 'Not Assigned') . '</td></tr>';
                echo '<tr><th>Status</th><td>' . ($row['is_open'] ? 'Open' : 'Closed') . '</td></tr>';
                echo '<tr><th>Actions</th><td>';
                echo '<a href="edit_class.php?class_id=' . htmlspecialchars($row['class_id']) . '" class="edit-button">Edit</a>';
                echo '<form method="post" action="process_delete_class.php" style="display:inline;">';
                echo '<input type="hidden" name="class_id" value="' . htmlspecialchars($row['class_id']) . '">';
                echo '<button type="submit" class="delete-button" onclick="return confirm(\'Are you sure you want to delete this class?\');">Delete</button>';
                echo '</form>';
                echo '</td></tr>';
                echo '</table>';
            }
        } else {
            echo '<p>No classes available.</p>';
        }
        ?>

        </div>
    </div>
    <div class="carousel-image">
        <img src="../Main-images/carousel_1.jpg" alt="Hero Image 1" class="carousel-slide">
        <img src="../Main-images/carousel_2.jpg" alt="Hero Image 2" class="carousel-slide">
        <img src="../Main-images/carousel_3.jpg" alt="Hero Image 3" class="carousel-slide">
        <img src="../Main-images/carousel_4.jpg" alt="Hero Image 4" class="carousel-slide">
    </div>
    <div class="carousel-overlay"></div>

    <footer>
        <div class="footer-content">
            <p>&copy; <?php echo date("Y"); ?> Foundation of Ateneo Student Tutors - Admin Area</p>
        </div>
    </footer>

    <script src="JS_admin.js"></script>

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
