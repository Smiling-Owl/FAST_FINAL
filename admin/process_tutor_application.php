<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    http_response_code(403); // Forbidden
    echo "Access denied.";
    exit();
}

// Check if application ID and action are provided
if (isset($_POST['application_id']) && isset($_POST['action'])) {
    $applicationId = $_POST['application_id'];
    $action = $_POST['action'];

    // Database connection
    $conn = new mysqli("localhost", "root", "", "fastdb");

    if ($conn->connect_error) {
        http_response_code(500); // Internal Server Error
        echo "Database connection failed: " . $conn->connect_error;
        exit();
    }

    if ($action === 'approve') {
        // Fetch the user_id from the tutor_application table
        $sql_select_user = "SELECT user_id FROM tutor_application WHERE id = ?"; // This line is now correct
        $stmt_select_user = $conn->prepare($sql_select_user);

        if ($stmt_select_user === false) {
            http_response_code(500);
            echo "Error preparing SQL: " . $conn->error;
            $conn->close();
            exit();
        }

        $stmt_select_user->bind_param("i", $applicationId);
        $stmt_select_user->execute();
        $result_user = $stmt_select_user->get_result();

        if ($result_user->num_rows === 1) {
            $row_user = $result_user->fetch_assoc();
            $tutor_user_id = $row_user['user_id'];

            // Insert the user_id into the tutors table
            $sql_insert_tutor = "INSERT INTO tutors (user_id) VALUES (?)";
            $stmt_insert_tutor = $conn->prepare($sql_insert_tutor);

            if ($stmt_insert_tutor === false) {
                http_response_code(500);
                echo "Error preparing SQL (insert): " . $conn->error;
                $stmt_select_user->close();
                $conn->close();
                exit();
            }

            $stmt_insert_tutor->bind_param("i", $tutor_user_id);

            if ($stmt_insert_tutor->execute()) {
                // Update the status in the tutor_application table to 'approved'
                $sql_update_application = "UPDATE tutor_application SET status = 'approved' WHERE id = ?";
                $stmt_update_application = $conn->prepare($sql_update_application);

                if ($stmt_update_application === false) {
                    http_response_code(500);
                    echo "Error preparing SQL (update): " . $conn->error;
                    $stmt_select_user->close();
                    $stmt_insert_tutor->close();
                    $conn->close();
                    exit();
                }

                $stmt_update_application->bind_param("i", $applicationId);
                $stmt_update_application->execute();
                echo "Tutor application approved and added to tutors.";
            } else {
                http_response_code(500); // Internal Server Error
                echo "Error adding tutor: " . $stmt_insert_tutor->error;
            }
            $stmt_insert_tutor->close();
            $stmt_update_application->close();

        } else {
            http_response_code(404); // Not Found
            echo "Tutor application not found.";
        }
        $stmt_select_user->close();

    } elseif ($action === 'reject') {
        // Update the status in the tutor_application table to 'rejected'
        $sql_update_application = "UPDATE tutor_application SET status = 'rejected' WHERE id = ?";
        $stmt_update_application = $conn->prepare($sql_update_application);

        if ($stmt_update_application === false) {
            http_response_code(500);
            echo "Error preparing SQL (reject): " . $conn->error;
            $conn->close();
            exit();
        }

        $stmt_update_application->bind_param("i", $applicationId);

        if ($stmt_update_application->execute()) {
            echo "Tutor application rejected.";
        } else {
            http_response_code(500); // Internal Server Error
            echo "Error rejecting application: " . $stmt_update_application->error;
        }
        $stmt_update_application->close();

    } else {
        http_response_code(400); // Bad Request
        echo "Invalid action.";
    }

    $conn->close();

} else {
    http_response_code(400); // Bad Request
    echo "Application ID or action not provided.";
}
?>