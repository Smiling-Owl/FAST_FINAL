<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    $conn = new mysqli("localhost", "root", "", "fastdb");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $db_username, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $role_stmt = $conn->prepare("SELECT r.role_name FROM user_roles ur
                                        INNER JOIN roles r ON ur.role_id = r.role_id
                                        WHERE ur.user_id = ? AND r.role_name = 'admin'");
            $role_stmt->bind_param("i", $user_id);
            $role_stmt->execute();
            $role_stmt->store_result();

            if ($role_stmt->num_rows > 0) {
                $_SESSION['admin_username'] = $db_username;
                $_SESSION['admin_id'] = $user_id;
                $role_stmt->close();
                $stmt->close();
                $conn->close();
                header("Location: admin_dashboard.php");
                exit();
            } else {
                $role_stmt->close();
                $stmt->close();
                $conn->close();
                header("Location: admin_login.php?error=You do not have administrator privileges.");
                exit();
            }
        } else {
            $stmt->close();
            $conn->close();
            header("Location: admin_login.php?error=Invalid username or password.");
            exit();
        }
    } else {
        $stmt->close();
        $conn->close();
        header("Location: admin_login.php?error=Invalid username or password.");
        exit();
    }
} else {
    header("Location: admin_login.php");
    exit();
}
?>