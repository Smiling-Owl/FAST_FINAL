<?php
session_start();

// Check if the tutor is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit();
}

$tutor_id = $_SESSION['user_id'];

// Database connection
$conn = new mysqli("localhost", "root", "", "fastdb");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch classes assigned to the tutor
$sql_classes = "SELECT c.class_id, c.class_name, c.timeslot_day, TIME_FORMAT(c.timeslot_time, '%h:%i %p') AS timeslot_time, c.room
                FROM classes c
                WHERE c.tutor_id = ?";
$stmt_classes = $conn->prepare($sql_classes);
$stmt_classes->bind_param("i", $tutor_id);
$stmt_classes->execute();
$result_classes = $stmt_classes->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tutor Dashboard</title>
    <link rel="icon" type="image/x-icon" href="../images/FAST logo white trans.png">
    <link rel="stylesheet" href="../styles.css">
    <link rel="stylesheet" href="tutor_dashboard.css">
</head>
<body>
    <header>
        <div class="navigation-bar">
            <div id="navigation-container">
                <img src="../images/FAST Logo Trans.png" alt="FAST Logo">
                <ul>
                    <li><a href="tutor_dashboard.php" aria-label="Tutor Dashboard">TUTOR DASHBOARD</a></li>
                    <li><a href="../dashboard/dashboard.php">STUDENT DASHBOARD</a></li>
                    <li><a href="../logout.php">LOG OUT</a></li>
                </ul>
            </div>
        </div>
    </header>

    <div class="container">
        <h1>Your Teaching Schedule</h1>

        <?php if ($result_classes->num_rows > 0): ?>
            <?php while ($row = $result_classes->fetch_assoc()): ?>
                <div class="class-schedule">
                    <h3><?php echo htmlspecialchars($row['class_name']); ?></h3>
                    <p><strong>Day:</strong> <?php echo htmlspecialchars($row['timeslot_day']); ?></p>
                    <p><strong>Time:</strong> <?php echo htmlspecialchars($row['timeslot_time']); ?></p>
                    <p><strong>Room:</strong> <?php echo htmlspecialchars($row['room']); ?></p>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>You are not currently assigned to any classes.</p>
        <?php endif; ?>
    </div>

    <footer>
        <div class="footer-content">
            <p>&copy; <?php echo date("Y"); ?> Foundation of Ateneo Student Tutors</p>
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
