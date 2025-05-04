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

// Pagination settings
$items_per_page = 12; // Number of classes per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $items_per_page;

// Get total number of classes
$count_sql = "SELECT COUNT(*) as total FROM classes";
$count_result = $conn->query($count_sql);
$total_classes = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_classes / $items_per_page);

// Fetch classes for current page
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
                LEFT JOIN tutors t ON c.tutor_id = t.user_id
                LEFT JOIN tutor_application ta ON t.user_id = ta.user_id
                ORDER BY c.class_id DESC
                LIMIT ? OFFSET ?";

$stmt = $conn->prepare($sql_classes);
$stmt->bind_param("ii", $items_per_page, $offset);
$stmt->execute();
$result_classes = $stmt->get_result();
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
        <h1>MANAGE CLASSES</h1>
        
        <div class="add-new-class">
            <a href="add_class.php">Add New Class</a>
        </div>

        <div class="class-list">
            <table class="classes-table">
                <thead>
                    <tr>
                        <th>Class</th>
                        <th>Description</th>
                        <th>Room</th>
                        <th>Day</th>
                        <th>Time</th>
                        <th>Tutor</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                if ($result_classes && $result_classes->num_rows > 0) {
                    while ($row = $result_classes->fetch_assoc()) {
                        echo '<tr>';
                        echo '<td>' . htmlspecialchars($row['class_name']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['description']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['room']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['timeslot_day']) . '</td>';
                        echo '<td>' . htmlspecialchars(date("h:i A", strtotime($row['timeslot_time']))) . '</td>';
                        echo '<td>' . htmlspecialchars($row['tutor_name'] ? $row['tutor_name'] : 'Not Assigned') . '</td>';
                        echo '<td><span class="status-badge ' . ($row['is_open'] ? 'status-open' : 'status-closed') . '">' . ($row['is_open'] ? 'Open' : 'Closed') . '</span></td>';
                        echo '<td><div class="action-buttons">';
                        echo '<a href="edit_class.php?class_id=' . htmlspecialchars($row['class_id']) . '" class="edit-button">EDIT</a>';
                        echo '<form method="post" action="process_delete_class.php" style="display:inline;">';
                        echo '<input type="hidden" name="class_id" value="' . htmlspecialchars($row['class_id']) . '">';
                        echo '<button type="submit" class="delete-button" onclick="return confirm(\'Are you sure you want to delete this class?\');">DELETE</button>';
                        echo '</form>';
                        echo '</div></td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="8" style="text-align:center;">No classes available.</td></tr>';
                }
                ?>
                </tbody>
            </table>
        </div>

        <?php if ($total_pages > 1): ?>
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?page=<?php echo $page - 1; ?>" class="pagination-button">Previous</a>
            <?php endif; ?>
            
            <?php
            $start_page = max(1, $page - 2);
            $end_page = min($total_pages, $page + 2);
            
            for ($i = $start_page; $i <= $end_page; $i++):
            ?>
                <a href="?page=<?php echo $i; ?>" class="pagination-button <?php echo $i == $page ? 'active' : ''; ?>">
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>
            
            <?php if ($page < $total_pages): ?>
                <a href="?page=<?php echo $page + 1; ?>" class="pagination-button">Next</a>
            <?php endif; ?>
        </div>
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

<?php
// Close the database connection
if (isset($conn)) {
    $conn->close();
}
?>
