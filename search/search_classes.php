<?php
// search_classes.php
//  Handles cases where a tutor is not found, and adds more comments

$conn = new mysqli("localhost", "root", "", "fastdb");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Classes</title>
    <link rel="icon" type="image/x-icon" href="../images/FAST logo white trans.png">
    <link rel="stylesheet" href="search_classes.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <div class="navigation-bar">
            <div id="navigation-container">
                <img src="../images/FAST Logo Trans.png" alt="FAST Logo">
                <ul>
                    <li><a href="search_classes.php" aria-label="Search Classes">SEARCH CLASSES</a></li>
                    <li><a href="../logout.php">LOG OUT</a></li>
                </ul>
            </div>
        </div>
    </header>

    <div class="container">
        <h1>Available Classes</h1>
        <h2>List of Classes</h2>
        <input type="text" id="searchBar" placeholder="Search for classes...">
        <ul id="classes-list">
            <?php
            // Join classes and tutor_application table to get tutor's name
            $sql = "SELECT c.class_id, c.class_name, c.description, c.room, c.timeslot_day, TIME_FORMAT(c.timeslot_time, '%h:%i %p') AS timeslot_time, c.capacity, ta.fullname
                    FROM classes c
                    LEFT JOIN tutor_application ta ON c.tutor_id = ta.user_id
                    ORDER BY c.timeslot_day, c.timeslot_time";

            $result = $conn->query($sql);

            if (!$result) {
                echo "Error: " . $conn->error;
                // Consider NOT continuing if the query fails.  A die() or exit() might be appropriate
                // depending on your application's error handling policy.
                exit();
            }

            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $class_id = $row['class_id'];
                    $class_name = $row['class_name'];
                    $description = $row['description'];
                    $room = $row['room'];
                    $timeslot_day = $row['timeslot_day'];
                    $timeslot_time = $row['timeslot_time'];
                    $capacity = $row['capacity'];
                    $tutor_name = $row['fullname'];

                    echo "<li class='class-item' data-class-id='$class_id' data-class-name='$class_name'>";
                    echo "<h3>$class_name</h3>";
                    echo "<p><strong>Description:</strong> $description</p>";
                    echo "<p><strong>Room:</strong> $room</p>";
                    echo "<p><strong>Time:</strong> $timeslot_day at $timeslot_time</p>";
                    echo "<p><strong>Capacity:</strong> $capacity</p>";
                    // Display Tutor Name, If tutor_name is null, show "N/A"
                    echo "<p><strong>Tutor:</strong> " . ($tutor_name ? $tutor_name : "N/A") . "</p>";
                    echo "</li>";
                }
            } else {
                echo "<li class='class-item'>No classes found.</li>";
            }
            ?>
        </ul>
    </div>

    <script>
        $(document).ready(function() {
            $('#searchBar').on('input', function() {
                var query = $(this).val().toLowerCase();
                $('.class-item').each(function() {
                    var className = $(this).data('class-name').toLowerCase();
                    if (className.indexOf(query) > -1) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });
        });
    </script>
    <footer>
        <div class="footer-content">
            <p>&copy; <?php echo date("Y"); ?> Foundation of Ateneo Student Tutors</p>
        </div>
    </footer>
</body>
</html>
