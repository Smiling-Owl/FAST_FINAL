<?php
session_start();

// Ensure that the user is logged in and that the necessary data is sent
if (!isset($_SESSION['user_id'])) {
    echo json_encode(array('success' => false, 'message' => 'error: Invalid request. User not logged in.'));
    exit();
}

if (!isset($_POST['class_id']) || !isset($_POST['student_id'])) {
    echo json_encode(array('success' => false, 'message' => 'error: Invalid request. Missing class_id or student_id.'));
    exit();
}

$student_id = $_POST['student_id'];
$class_id = $_POST['class_id'];

// Connect to the database
$conn = new mysqli("localhost", "root", "", "fastdb");
if ($conn->connect_error) {
    echo json_encode(array('success' => false, 'message' => 'error: Database connection failed: ' . $conn->connect_error));
    exit();
}

// Use prepared statements to prevent SQL injection
$stmt_check = $conn->prepare("SELECT * FROM student_classes WHERE student_id = ? AND class_id = ?");
$stmt_check->bind_param("ii", $student_id, $class_id);
$stmt_check->execute();
$stmt_check->store_result();

if ($stmt_check->num_rows > 0) {
    echo json_encode(array('success' => false, 'message' => 'already_enrolled'));
    $stmt_check->close();
    $conn->close();
    exit();
}
$stmt_check->close();

// Check if the class is full
$stmt_capacity = $conn->prepare("SELECT capacity, (SELECT COUNT(*) FROM student_classes WHERE class_id = ?) AS current_students FROM classes WHERE class_id = ?");
$stmt_capacity->bind_param("ii", $class_id, $class_id);
$stmt_capacity->execute();
$result_capacity = $stmt_capacity->get_result();

if ($row_capacity = $result_capacity->fetch_assoc()) {
    if ($row_capacity['current_students'] >= $row_capacity['capacity']) {
        echo json_encode(array('success' => false, 'message' => 'full'));
        $conn->close();
        exit();
    }
}
$stmt_capacity->close(); // Close the statement.

// Insert the enrollment record
$stmt_insert = $conn->prepare("INSERT INTO student_classes (student_id, class_id, enrollment_status) VALUES (?, ?, 'enrolled')");
$stmt_insert->bind_param("ii", $student_id, $class_id);

if ($stmt_insert->execute()) {
    // Also return the current student count and capacity, so javascript can update the display
    $stmt_current_count = $conn->prepare("SELECT capacity, (SELECT COUNT(*) FROM student_classes WHERE class_id = ?) AS current_students FROM classes WHERE class_id = ?");
    $stmt_current_count->bind_param("ii", $class_id, $class_id);
    $stmt_current_count->execute();
    $result_current_count = $stmt_current_count->get_result();
    $row_current_count = $result_current_count->fetch_assoc();
    $current_students = $row_current_count['current_students'];
    $capacity = $row_current_count['capacity'];
    $stmt_current_count->close();

    echo json_encode(array(
        'success' => true,
        'message' => 'Successfully enrolled in the class!',
        'current_students' => $current_students,
        'capacity' => $capacity
    ));
} else {
    echo json_encode(array(
        'success' => false,
        'message' => 'An error occurred: ' . $stmt_insert->error
    ));
}

$stmt_insert->close();
$conn->close();
?>
