<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Foundation of Ateneo Student Tutors</title>
  <link rel="icon" type="image/x-icon" href="../images/icon.png">
  <link rel="stylesheet" href="../styles.css">
  <link rel="stylesheet" href="request_style.css">
  <link rel="icon" type="image/x-icon" href="/Main-images/FAST logo white trans.png">
    <link rel="stylesheet" href="styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@200..700&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=PT+Sans:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = new mysqli("localhost", "root", "", "fastdb");

    // Check connection
    if ($conn->connect_error) {
        die("<p style='color:red;'>Connection failed: " . $conn->connect_error . "</p>");
    }

    // Sanitize and validate inputs
    $course = mysqli_real_escape_string($conn, trim($_POST['course']));
    $subject = mysqli_real_escape_string($conn, trim($_POST['subject']));
    $topic = mysqli_real_escape_string($conn, trim($_POST['topic']));
    $freetime = mysqli_real_escape_string($conn, trim($_POST['freetime']));
    $reason = mysqli_real_escape_string($conn, trim($_POST['reason']));

    // Validate inputs
    if (empty($course) || empty($subject) || empty($topic) || empty($freetime) || empty($reason)) {
        echo "<p style='color:red; text-align:center;'>All fields are required. Please fill them out.</p>";
    } else {
        // Prepare and bind the SQL statement
        $stmt = $conn->prepare("INSERT INTO tutoring_requests (course, subject, topic, freetime, reason) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $course, $subject, $topic, $freetime, $reason);

        // Execute the query and give feedback
        if ($stmt->execute()) {
            echo "<p style='color:green; text-align:center;'>Request submitted successfully!</p>";
        } else {
            echo "<p style='color:red; text-align:center;'>Error: " . $stmt->error . "</p>";
        }

        $stmt->close();
    }

    // Close the connection
    $conn->close();
}
?>



<header>
        <div class="navigation-bar">
            <div id="navigation-container">
                <img src="../images/icon.png" alt="FAST Logo">
                <ul>
                    <li><a href="#about" aria-label="About FAST">ABOUT</a></li>
                    <li><a href="../request/request.php" aria-label="Request Tutoring">REQUEST</a></li>
                    <li>
                        <div class="dropdown">
                            <button class="DropApplication"><a>APPLY NOW!</a>
                                <i class="down"></i>
                            </button>
                            <div class="Dropdown_Application">
                                <a href="../application/apply_student.php" target="_blank" aria-label="Student Application">STUDENT</a>
                                <a href="../application/tutor_app.php" target="_blank"
                                   aria-label="Tutor Application (opens in a new tab)">TUTORS</a>
                            </div>
                        </div>
                    </li>
                    <li><a href="../logout.php">LOG OUT</a></li>
                </ul>
            </div>
        </div>
    </header>

<div class="request-form">
  <div class="form-wrapper">
    <div class="registration-container">
      <h2>TUTORING REQUEST FORM</h2>
      <form method="POST" action="">

        <div class="form-group">
          <label for="course">Course</label>
          <input type="text" id="course" name="course" required placeholder="Major"><br>
        </div>

        <div class="form-group">
          <label for="subject">Subject</label>
          <input type="text" id="subject" name="subject" required placeholder="Feild" ><br>
        </div>

        <div class="form-group">
          <label for="topic">Topic</label>
          <input type="text" id="topic" name="topic" required placeholder="Topics to focus on"><br>
        </div>

        <div class="form-group">
          <label for="freetime">Schedule Tutoring Time</label>
          <input type="text" id="freetime" name="freetime" required placeholder="DD/MM/YR-AM/PM"><br>
        </div>

        <div class="form-group">
          <label for="reason">What would you like help with?</label>
          <textarea id="reason" name="reason" rows="4" required placeholder="Describe where you need help with"></textarea><br>
        </div>

        <button type="submit" class="submit-btn">Submit</button><br>

      </form>
    </div>
  </div>
</div>

<div class="carousel-image">
  <img src="../images/carousel_1.jpg" alt="Hero Image 1" class="carousel-slide">
  <img src="../images/carousel_2.jpg" alt="Hero Image 2" class="carousel-slide">
  <img src="../images/carousel_3.jpg" alt="Hero Image 3" class="carousel-slide">
  <img src="../images/carousel_4.jpg" alt="Hero Image 4" class="carousel-slide">
</div>
<div class="carousel-overlay"></div>

<script src="../index.js"></script>

</body>
</html>
