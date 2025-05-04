<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_GET['class_id'])) {
    echo json_encode(null); // Or an error message
    exit();
}

$student_id = $_SESSION['user_id'];
$class_id = $_GET['class_id'];

// Database connection
$conn = new mysqli("localhost", "root", "", "fastdb");
if ($conn->connect_error) {
    echo json_encode(null);
    exit();
}

$class_details = fetch_class_details($conn, $class_id, $student_id);

if ($class_details) {
    echo json_encode($class_details);
} else {
    echo json_encode(null);
}
$conn->close();

function fetch_class_details($conn, $class_id, $student_id) {
    $sql = "SELECT c.class_id, c.class_name, c.description, c.room, c.timeslot_day, TIME_FORMAT(c.timeslot_time, '%h:%i %p') AS timeslot_time,
                    ta.fullname AS tutor_name, c.capacity,
                    (SELECT COUNT(*) FROM student_classes sc WHERE sc.class_id = c.class_id) AS current_students,
                    (SELECT COUNT(*) FROM student_classes sc WHERE sc.class_id = c.class_id AND sc.student_id = ?) AS is_requested
            FROM classes c
            LEFT JOIN tutors t ON c.tutor_id = t.user_id
            LEFT JOIN tutor_application ta ON t.user_id = ta.user_id
            WHERE c.is_open = TRUE AND c.class_id = ?"; // Filter by class_id
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        error_log("Prepare failed: " . $conn->error);
        return null;
    }
    $stmt->bind_param("ii", $student_id, $class_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return null;
    }
}
?>