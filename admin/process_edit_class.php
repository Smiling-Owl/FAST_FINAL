<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $class_id = $_POST['class_id'];
    $class_name = $_POST['class_name'];
    $description = $_POST['description'];
    $room = $_POST['room'];
    $timeslot_day = $_POST['timeslot_day'];
    $timeslot_time = $_POST['timeslot_time'];
    $tutor_id = $_POST['tutor_id'];
    $capacity = $_POST['capacity'];
    $is_open = isset($_POST['is_open']) ? 1 : 0;
    $is_done = isset($_POST['is_done']) ? 1 : 0;

    $conn = new mysqli("localhost", "root", "", "fastdb");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("UPDATE classes SET class_name=?, description=?, room=?, timeslot_day=?, timeslot_time=?, tutor_id=?, capacity=?, is_open=?, is_done=? WHERE class_id=?");
    $stmt->bind_param("sssssiiiii", $class_name, $description, $room, $timeslot_day, $timeslot_time, $tutor_id, $capacity, $is_open, $is_done, $class_id);

    if ($stmt->execute()) {
        // Redirect back to manage_classes.php on success
        header("Location: manage_classes.php?edit_success=1");
        exit();
    } else {
        // Display an error message and a link back
        echo "Error updating class: " . $stmt->error;
        echo '<br><a href="manage_classes.php">Back to Manage Classes</a>';
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request.";
    echo '<br><a href="manage_classes.php">Back to Manage Classes</a>';
}
?>