<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "fastdb");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch tutors for the dropdown
$sql_tutors = "SELECT t.id AS tutor_id, ta.fullname AS tutor_fullname FROM tutors t INNER JOIN tutor_application ta ON t.user_id = ta.user_id";
$result_tutors = $conn->query($sql_tutors);

// Fetch class details if editing
if (isset($_GET['class_id'])) {
    $class_id_edit = $_GET['class_id'];
    $sql_class = "SELECT * FROM classes WHERE class_id = ?";
    $stmt_class = $conn->prepare($sql_class);
    $stmt_class->bind_param("i", $class_id_edit);
    $stmt_class->execute();
    $result_class = $stmt_class->get_result();
    if ($result_class->num_rows === 1) {
        $class_data = $result_class->fetch_assoc();
    } else {
        echo "Class not found.";
        exit();
    }
    $stmt_class->close();
} else {
    echo "No class ID specified.";
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Class - FAST Admin</title>
    <link rel="icon" type="image/x-icon" href="../images/FAST logo white trans.png">
    <link href="https://fonts.googleapis.com/css2?family=PT+Sans&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles/edit_class.css">
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
        <h1>Edit Class</h1>

        <div class="form-container">
            <form method="POST" action="process_edit_class.php">
                <input type="hidden" name="class_id" value="<?php echo htmlspecialchars($class_data['class_id']); ?>">

                <div class="form-group">
                    <label for="class_name">Class Name:</label>
                    <input type="text" id="class_name" name="class_name" value="<?php echo htmlspecialchars($class_data['class_name']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea id="description" name="description"><?php echo htmlspecialchars($class_data['description']); ?></textarea>
                </div>
                <div class="form-group">
                    <label for="room">Room:</label>
                    <input type="text" id="room" name="room" value="<?php echo htmlspecialchars($class_data['room']); ?>">
                </div>
                <div class="form-group">
                    <label for="timeslot_day">Day:</label>
                    <select id="timeslot_day" name="timeslot_day">
                        <option value="Monday" <?php if ($class_data['timeslot_day'] === 'Monday') echo 'selected'; ?>>Monday</option>
                        <option value="Tuesday" <?php if ($class_data['timeslot_day'] === 'Tuesday') echo 'selected'; ?>>Tuesday</option>
                        <option value="Wednesday" <?php if ($class_data['timeslot_day'] === 'Wednesday') echo 'selected'; ?>>Wednesday</option>
                        <option value="Thursday" <?php if ($class_data['timeslot_day'] === 'Thursday') echo 'selected'; ?>>Thursday</option>
                        <option value="Friday" <?php if ($class_data['timeslot_day'] === 'Friday') echo 'selected'; ?>>Friday</option>
                        <option value="Saturday" <?php if ($class_data['timeslot_day'] === 'Saturday') echo 'selected'; ?>>Saturday</option>
                        <option value="Sunday" <?php if ($class_data['timeslot_day'] === 'Sunday') echo 'selected'; ?>>Sunday</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="timeslot_time">Time:</label>
                    <input type="time" id="timeslot_time" name="timeslot_time" value="<?php echo htmlspecialchars($class_data['timeslot_time']); ?>">
                </div>
                <div class="form-group">
                    <label for="tutor_id">Tutor:</label>
                    <select id="tutor_id" name="tutor_id" required>
                        <option value="">-- Select a Tutor --</option>
                        <?php
                        if ($result_tutors && $result_tutors->num_rows > 0) {
                            while ($row_tutor = $result_tutors->fetch_assoc()) {
                                $selected = ($row_tutor['tutor_id'] == $class_data['tutor_id']) ? 'selected' : '';
                                echo '<option value="' . htmlspecialchars($row_tutor['tutor_id']) . '" ' . $selected . '>' . htmlspecialchars($row_tutor['tutor_fullname']) . '</option>';
                            }
                        } else {
                            echo '<option value="">No approved tutors yet.</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="capacity">Class Capacity:</label>
                    <input type="number" id="capacity" name="capacity" min="1" value="<?php echo htmlspecialchars($class_data['capacity']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="is_open">Open for Enrollment:</label>
                    <input type="checkbox" id="is_open" name="is_open" value="1" <?php if ($class_data['is_open']) echo 'checked'; ?>>
                </div>
                <div class="form-group">
                    <label for="is_done">Class Done:</label>
                    <input type="checkbox" id="is_done" name="is_done" value="1" <?php if ($class_data['is_done']) echo 'checked'; ?>>
                </div>
                <div class="form-group">
                    <button type="submit">Save Changes</button>
                </div>
            </form>
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