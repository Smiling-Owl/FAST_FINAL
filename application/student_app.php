<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student's Application Page</title>
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
        <?php
        session_start(); // Start the session

        // Check if the student is logged in
        if (!isset($_SESSION['user_id'])) {
            echo "<p style='color:red; text-align:center;'>You must be logged in to submit an application.</p>";
            exit();
        }

        $student_user_id = $_SESSION['user_id'];
        $conn = new mysqli("localhost", "root", "", "fastdb");

        if ($conn->connect_error) {
            die("<p style='color:red;'>Connection failed: " . $conn->connect_error . "</p>");
        }

        // Check if the student has already submitted an application
        $stmt_check_existing = $conn->prepare("SELECT student_id FROM student_application WHERE user_id = ?");
        $stmt_check_existing->bind_param("i", $student_user_id);
        $stmt_check_existing->execute();
        $stmt_check_existing->store_result();

        if ($stmt_check_existing->num_rows > 0) {
            echo "<p style='color:blue; text-align:center;'>You have already submitted your student application. Please wait for admin approval.</p>";
            echo "<p style='text-align:center;'><a href='../dashboard/dashboard.php'>Back to Dashboard</a></p>";
        } else {
            // Application form processing
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $fullname = $_POST['fullname'];
                $student_id = $_POST['student_id'];
                $birthdate = $_POST['birthdate'];
                $email = $_POST['email'];
                $contact_number = $_POST['contact_number'];
                $year_course = $_POST['year_course'];
                $fb_link = $_POST['fb_link'];

                $stmt = $conn->prepare("INSERT INTO student_application (fullname, student_id, birthdate, email, contact_number, year_course, fb_link, user_id, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending')");
                $stmt->bind_param("sssssssi", $fullname, $student_id, $birthdate, $email, $contact_number, $year_course, $fb_link, $student_user_id);

                if ($stmt->execute()) {
                    echo "<p style='color:green; text-align:center;'>Application submitted successfully! You will be redirected to your dashboard.</p>";
                    echo "<script>setTimeout(function(){ window.location.href = '../dashboard/dashboard.php'; }, 3000);</script>"; // Redirect to dashboard
                    exit();
                } else {
                    echo "<p style='color:red; text-align:center;'>Error: " . $stmt->error . "</p>";
                }

                $stmt->close();
            }
            ?>

            <form method="POST" action="">
                <h2>STUDENT'S APPLICATION FORM</h2>

                <div class="Form_Info">
                    <label>Full Name:</label>
                    <input type="text" name="fullname" required>
                </div>

                <div class="Form_Info">
                    <label>Student ID Number:</label>
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

                <button type="submit">Submit Application</button>
            </form>

            <?php
        } // End of check for existing application
        $conn->close();
        ?>
    </div>

    <script src="application.js"></script>
</body>
</html>