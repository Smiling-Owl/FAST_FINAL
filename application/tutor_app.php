<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$conn = new mysqli("localhost", "root", "", "fastdb");

if ($conn->connect_error) {
    die("<p style='color:red;'>Connection failed: " . $conn->connect_error . "</p>");
}

// Check if the user is already in the tutors table
$stmt_check_tutor = $conn->prepare("SELECT user_id FROM tutors WHERE user_id = ?");
$stmt_check_tutor->bind_param("i", $user_id);
$stmt_check_tutor->execute();
$stmt_check_tutor->store_result();

if ($stmt_check_tutor->num_rows > 0) {
    // User is a tutor, redirect to dashboard
    header("Location: ../tutor_dashboard/tutor_dashboard.php");
    exit();
}
$stmt_check_tutor->close();

// Check for existing tutor applications that are not rejected
$stmt_check_app = $conn->prepare("SELECT status FROM tutor_application WHERE user_id = ? AND status != 'rejected'");
$stmt_check_app->bind_param("i", $user_id);
$stmt_check_app->execute();
$result_check_app = $stmt_check_app->get_result(); // Get the result set

if ($result_check_app->num_rows > 0) {
    $row_check_app = $result_check_app->fetch_assoc(); // Fetch from the result set
     if($row_check_app['status'] == 'approved'){
      header("Location: ../tutor_dashboard/tutor_dashboard.php");
      exit();
     }
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Tutor's Application Page</title>
        <link rel="icon" type="image/x-icon" href="/Main-images/FAST logo white trans.png">
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Oswald:wght@400;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="application.css">
    </head>
    <body>
        <div class="Coloryey"></div>
        <div class="Form_Contents">
            <p style='color:blue; text-align:center;'>You have already submitted a tutor application that is pending or has been approved.</p>
            <p style='text-align:center;'><a href='../dashboard/dashboard.php'>Back to Dashboard</a></p>
        </div>
        <script src="Tutor-application.js"></script>
    </body>
    </html>
    <?php
    $conn->close();
    exit();
}
$stmt_check_app->close();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = $_POST['fullname'];
    $student_id = $_POST['student_id'];
    $birthdate = $_POST['birthdate'];
    $email = $_POST['email'];
    $contact_number = $_POST['contact_number'];
    $year_course = $_POST['year_course'];
    $fb_link = $_POST['fb_link'];
    $course_excel = $_POST['course_excel'];
    $why = $_POST['why'];

    // Include user_id and set initial status to 'pending'
    $status = 'pending'; // Define the $status variable
    $stmt = $conn->prepare("INSERT INTO tutor_application (user_id, fullname, student_id, birthdate, email, contact_number, year_course, fb_link, course_excel, why, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssssssss", $user_id, $fullname, $student_id, $birthdate, $email, $contact_number, $year_course, $fb_link, $course_excel, $why, $status);

    if ($stmt->execute()) {
        //set session variable
        $_SESSION['show_congrats'] = true;
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>Tutor's Application Page</title>
            <link rel="icon" type="image/x-icon" href="/Main-images/FAST logo white trans.png">
            <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Oswald:wght@400;700&display=swap" rel="stylesheet">
            <link rel="stylesheet" href="application.css">
        </head>
        <body>
            <div class="Coloryey"></div>
            <div class="Form_Contents">
                <p style='color:green; text-align:center;'>Application submitted successfully!</p>
                <p style='text-align:center;'><a href='../dashboard/dashboard.php'>Back to Dashboard</a></p>
            </div>
            <script src="Tutor-application.js"></script>
        </body>
        </html>
        <?php
        $stmt->close();
        $conn->close();
        exit();
    } else {
        echo "<p style='color:red; text-align:center;'>Error: " . $stmt->error . "</p>";
    }

    $stmt->close();
    $conn->close();
} else {
    // Display the form if not already a tutor and no pending/approved application
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Tutor's Application Page</title>
        <link rel="icon" type="image/x-icon" href="/Main-images/FAST logo white trans.png">
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Oswald:wght@400;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="application.css">
    </head>
    <body>
        <div class="Coloryey"></div>
        <div class="carousel-image">
            <img src="../images/carousel_1.jpg" alt="Hero Image 1" class="carousel-slide">
            <img src="../images/carousel_2.jpg" alt="Hero Image 2" class="carousel-slide">
            <img src="../images/carousel_3.jpg" alt="Hero Image 3" class="carousel-slide">
            <img src="../images/carousel_4.jpg" alt="Hero Image 4" class="carousel-slide">
        </div>
        <div class="Form_Contents">
            <form method="POST" action="">
                <h2>TUTOR'S APPLICATION FORM</h2>
                <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id'] ?? ''; ?>">
                <div class="Form_Info">
                    <label>Full Name:</label>
                    <input type="text" name="fullname" required>
                </div>
                <div class="Form_Info">
                    <label>ID Number:</label>
                    <input type="text" name="student_id" required>
                </div>
                <div class="Form_Info">
                    <label>Birthdate:</label>
                    <input type="date" name="birthdate" required>
                </div>
                <div class="Form_Info">
                    <label>Email Address:</label>
                    <input type="email" name="email" required>
                </div>
                <div class="Form_Info">
                    <label>Contact Number:</label>
                    <input type="tel" name="contact_number" required>
                </div>
                <div class="Form_Info">
                    <label>Year & Course:</label>
                    <input type="text" name="year_course" required>
                </div>
                <div class="Form_Info">
                    <label>FB Account Link (for chat):</label>
                    <input type="url" name="fb_link" placeholder="https://facebook.com/yourprofile" required>
                </div>
                <div class="Form_Info">
                    <label>Course/Subjects you Excel at:</label>
                    <input type="text" name="course_excel" required>
                </div>
                <div class="Form_Info">
                    <label>Why do you want to be a tutor?</label>
                    <textarea name="why" rows="4" required></textarea>
                </div>
                <button type="submit">Submit Application</button>
            </form>
        </div>
        <script src="Tutor-application.js"></script>
    </body>
    </html>
    <?php
}
?>
