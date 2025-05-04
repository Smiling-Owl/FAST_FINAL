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
    <link rel="stylesheet" href="../styles.css">
    <link rel="stylesheet" href="search_classes.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background-color: #FFF5E3;
            font-family: 'PT Sans', sans-serif;
            margin: 0;
            padding-top: 60px; /* Reduced from 500px to just account for navbar */
            position: relative; /* Add this for footer positioning */
        }
        header {
            background-color: #003366;
            color: white;
            padding: 1rem 0;
            text-align: center;
        }
        header .navigation-bar #navigation-container ul {
            list-style: none;
            display: flex;
            justify-content: center;
            margin: 0;
            padding: 0;
        }
        header .navigation-bar #navigation-container ul li {
            margin: 0 1rem;
        }
        header .navigation-bar #navigation-container ul li a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            font-size: 1.1rem;
            transition: color 0.3s ease;
        }
        header .navigation-bar #navigation-container ul li a:hover {
            color: #FF6600;
        }
        .container {
            max-width: 1000px;
            margin: 2rem auto;
            padding: 1.5rem;
            background-color: rgba(255, 245, 227, 0.9);
            border-radius: 0.75rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        .container h1 {
            color: #003366;
            text-align: center;
            margin-bottom: 1.5rem;
            font-size: 2.25rem;
            font-weight: 600;
        }
        .container h2 {
            color: #003366;
            margin-bottom: 1rem;
            font-size: 1.5rem;
            font-weight: 600;
        }
        #searchBar {
            width: 100%;
            padding: 0.75rem;
            margin-bottom: 1.5rem;
            border: 1px solid #e2e8f0;
            border-radius: 0.375rem;
            font-size: 1rem;
            outline: none;
            transition: border-color 0.2s ease;
        }
        #searchBar:focus {
            border-color: #FF6600;
            box-shadow: 0 0 0 3px rgba(255, 102, 0, 0.15);
        }
        #classes-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .class-item {
            background-color: #f7fafc;
            padding: 1.25rem;
            margin-bottom: 1rem;
            border-radius: 0.5rem;
            border: 1px solid #e2e8f0;
            box-shadow: 0 2px 4px -1px rgba(0, 0, 0, 0.08), 0 1px 2px -1px rgba(0, 0, 0, 0.06);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .class-item:hover {
            transform: translateY(-4px);
            box-shadow: 0 6px 8px -2px rgba(0, 0, 0, 0.1), 0 3px 4px -1px rgba(0, 0, 0, 0.08);
        }
        .class-item h3 {
            color: #003366;
            margin-bottom: 0.75rem;
            font-size: 1.25rem;
            font-weight: 600;
        }
        .class-item p {
            margin-bottom: 0.5rem;
            font-size: 1rem;
            color: #4a5568;
        }
        .class-item p strong {
            color: #003366;
            font-weight: 600;
        }
        footer {
            background-color: #7e461e;
            color: #f4f4f4;
            text-align: center;
            padding: 1.5rem 0;
            font-family: 'PT Sans', sans-serif;
            width: 100%;
            margin-top: auto; /* This pushes the footer to the bottom */
        }
        footer .footer-content p {
            font-size: 0.9rem;
        }
    </style>
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
