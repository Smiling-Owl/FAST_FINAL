<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Student Applications - FAST Admin</title>
    <link rel="icon" type="image/x-icon" href="/Main-images/FAST logo white trans.png">
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
    <link rel="icon" type="image/x-icon" href="../images/FAST logo white trans.png">
    <link rel="stylesheet" href="styles/manage_applications.css">
    <script>
        function processApplication(applicationId, action) {
            fetch('process_application.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'application_id=' + encodeURIComponent(applicationId) + '&action=' + encodeURIComponent(action)
            })
            .then(response => {
                if (!response.ok) {
                    return response.text().then(text => { throw new Error('HTTP error ' + response.status + ': ' + text); });
                }
                return response.text();
            })
            .then(data => {
                alert(data); // Show a success/error message
                // Optionally, you can reload the page or update the UI dynamically
                window.location.reload(); // Simple way to refresh the list
            })
            .catch(error => {
                alert('There was an error: ' + error);
            });
        }
    </script>
</head>
<body>
    <header>
        <div class = "page-wrapper">
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
            <h1>Manage Student Applications</h1>
        
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Application ID</th>
                            <th>Full Name</th>
                            <th>Student ID</th>
                            <th>Email</th>
                            <th>Application Date</th>
                            <th>Status</th>
                            <th>Student Username</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result_applications && $result_applications->num_rows > 0) {
                            while ($row = $result_applications->fetch_assoc()) {
                                echo '<tr>';
                                echo '<td>' . htmlspecialchars($row['application_id']) . '</td>';
                                echo '<td>' . htmlspecialchars($row['fullname']) . '</td>';
                                echo '<td>' . htmlspecialchars($row['student_id']) . '</td>';
                                echo '<td>' . htmlspecialchars($row['email']) . '</td>';
                                echo '<td>' . htmlspecialchars($row['application_date']) . '</td>';
                                echo '<td>' . htmlspecialchars($row['status']) . '</td>';
                                echo '<td>' . htmlspecialchars($row['student_username']) . '</td>';
                                echo '<td><button class="approve-button" onclick="processApplication(' . htmlspecialchars($row['application_id']) . ', \'approve\')">&#10003;</button><button class="reject-button" onclick="processApplication(' . htmlspecialchars($row['application_id']) . ', \'reject\')">&#10005;</button></td>';
                                echo '</tr>';
                            }
                        } else {
                            echo '<tr><td colspan="8">No pending student applications.</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        <div class="carousel-image">
            <img src="../Main-images/carousel_1.jpg" alt="Hero Image 1" class="carousel-slide">
            <img src="../Main-images/carousel_2.jpg" alt="Hero Image 2" class="carousel-slide">
            <img src="../Main-images/carousel_3.jpg" alt="Hero Image 3" class="carousel-slide">
            <img src="../Main-images/carousel_4.jpg" alt="Hero Image 4" class="carousel-slide">
          </div>
          <div class="carousel-overlay"></div>
        </div>
    <footer>
        <div class="footer-content">
            <p>&copy; <?php echo date("Y"); ?> Foundation of Ateneo Student Tutors - Admin Area</p>
        </div>
    </footer>
    <script src="JS_admin.js"></script>
</body>
</html>

<?php
// Close the database connection
if (isset($conn)) {
    $conn->close();
}
?>
