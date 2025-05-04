<?php
session_start();

// If not logged in, redirect to login page
if (!isset($_SESSION['username'])) {
    header("Location: ../login.html"); // adjust path if needed
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link rel="icon" type="image/x-icon" href="../images/icon.png">
    <link rel="stylesheet" href="../styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400..700;1,400..700&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@200..700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=PT+Sans:ital,wght@0,400;0,700;1,400;1,700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
        integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
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

    <section class="main_title">
        <div class="intro">
            <div class="hero-text_container">
                <div class="overlay"></div>
                <div class="hero-text">
                    <h1>FOUNDATION OF ATENEO STUDENT TUTORS</h1>
                    <p>Together Inspiring Student Success</p>
                    <p class="welcome-text">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>
                </div>
            </div>

            <div class="carousel-image">
                <img src="../images/carousel_1.jpg" alt="Hero Image 1" class="carousel-slide">
                <img src="../images/carousel_2.jpg" alt="Hero Image 2" class="carousel-slide">
                <img src="../images/carousel_3.jpg" alt="Hero Image 3" class="carousel-slide">
                <img src="../images/carousel_4.jpg" alt="Hero Image 4" class="carousel-slide">
            </div>
        </div>
    </section>

    <section class="about_section" id="about">
        <h1 class="about-title"> ABOUT US </h1>
        <img class="about-us" src="#" alt="About Us">
        <p class="about-text">
            The Foundation of Ateneo Student Tutors is an organization that specializes in hosting and
            providing tutoring services from Ateneo students for Ateneo students. FAST upholds the priority
            of academic excellence, thus we provide a means of scholarly assistance to those in need. Our goal
            is to create an environment where students are supported in order for them to
            excel in their academic journey and also develop a culture of teamwork and mutual sharing of
            knowledge and experience within the Ateneo community.
        </p>
    </section>

    <section class="MV_section">
        <img class="mv_hero-image" src="../images/MV.jpg" alt="Mission Vision Hero">
        <div class="MV_container">
            <div class="mission__section card">
                <h1 class="M_title">MISSION</h1>
                <p>
                    FAST will serve as an organization of student tutors that students can seek to be
                    empowered to achieve academic success and to gain understanding of course-related concepts
                    and ideas through tutoring and collaborative learning.
                </p>
            </div>
            <div class="vision__section card">
                <h1 class="V_title">VISION</h1>
                <p>
                    FAST’s mission is to provide academic support to students in need by providing
                    accessible, high-quality tutoring services facilitated by fellow students who have
                    demonstrated proficiency in their respective fields and fostering a collaborative
                    learning environment to significantly improve the academic outcomes of the
                    University’s community.
                </p>
            </div>
        </div>
    </section>

    <section class="services_section">
        <h1 class="services-header">FAST Services</h1>
        <div class="services_container">
            <div class="services_card card">
                <h1 class="service-title">On-Request Private Tutoring</h1>
                <p class="service-description">One-on-one or one-on-group tutoring sessions tailored to the
                    students' specific subjects, topics, and schedules. Avail by approaching the AdZU Admissions
                    and Aid Office, or by messaging the FAST Facebook page.</p>
            </div>

            <div class="services_card card">
                <h1 class="service-title">Class-Based Tutoring</h1>
                <p class="service-description">Group tutoring sessions held by student tutors in a collaborative
                    environment, covering common subjects in the curriculum or shared topics of interest.</p>
            </div>

            <div class="services_card card">
                <h1 class="service-title">Seminars and Workshops</h1>
                <p class="service-description">Interactive, in-person events that provide knowledge and skills on
                    specialized topics.</p>
            </div>
        </div>
    </section>

    <section class="offi_section">
        <h1 class="offi-header">FAST Officiating Members</h1>
        <div class="offi_container">
            <div class="offi_card">
                <img src="../images/offi_temp6.jpg" alt="Officer 1" class="offi_image">
                <h2 class="offi-title">Clyde Xander I. Cielo</h2>
                <p class="offi-description">FAST President</p>
            </div>

            <div class="offi_card">
                <img src="../images/offi_temp6.jpg" alt="Officer 1" class="offi_image">
                <h2 class="offi-title">Whaine Krysthian C. Perocho</h2>
                <p class="offi-description">FAST Vice-President</p>
            </div>

            <div class="offi_card">
                <img src="../images/offi_temp6.jpg" alt="Officer 1" class="offi_image">
                <h2 class="offi-title">Shaima K. Marah</h2>
                <p class="offi-description">FAST Secretary</p>
            </div>

            <div class="offi_card">
                <img src="../images/offi_temp6.jpg" alt="Officer 1" class="offi_image">
                <h2 class="offi-title">Dee Marie Melangell J. Majorenos</h2>
                <p class="offi-description">FAST Treasurer</p>
            </div>

            <div class="offi_card">
                <img src="../images/offi_temp6.jpg" alt="Officer 1" class="offi_image">
                <h2 class="offi-title">Nahla A. Awang</h2>
                <p class="offi-description">FAST Auditor</p>
            </div>

            <div class="offi_card">
                <img src="../images/offi_temp6.jpg" alt="Officer 1" class="offi_image">
                <h2 class="offi-title">Remalyn P. Muyot</h2>
                <p class="offi-description">FAST Public Relations Officer</p>
            </div>

            <div class="offi_card">
                <img src="../images/offi_temp6.jpg" alt="Officer 1" class="offi_image">
                <h2 class="offi-title">Denzl John P. Ramos</h2>
                <p class="offi-description">FAST Logistics Head</p>
            </div>

            <div class="offi_card">
                <img src="../images/offi_temp6.jpg" alt="Officer 1" class="offi_image">
                <h2 class="offi-title">Axl Sayaddi</h2>
                <p class="offi-description">FAST Board Member</p>
            </div>
        </div>
    </section>

    <section class="report_section">
        <h1 class="report-header">File a Report</h1>
        <form action="submit_report.php" method="POST" class="report-form">
            <label for="subject">Subject:</label><br>
            <input type="text" id="subject" name="subject" required><br><br>

            <label for="message">Report Message:</label><br>
            <textarea id="message" name="message" rows="5" required></textarea><br><br>

            <button type="submit">Submit Report</button>
    </form>
    </section>


    <footer class="footer_section">
        <div class="footer-content" id="footer">
            <h1 class="footer_title">FOUNDATION OF ATENEO</h1>
            <h1 class="footer_title">STUDENT TUTORS</h1>
            <div class="social-icons">
                <a href="https://www.facebook.com/yourpage" target="_blank" aria-label="Facebook">
                    <i class="fab fa-facebook-f"></i>
                </a>
            </div>
            <p class="footer_contact">
                <a href="https://www.facebook.com/FASTAdZU" target="_blank">Facebook Page</a> | Email:
                fast.adzu@gmail.com
            </p>
            <p class="footer_copy">© 2023 Foundation of Ateneo Student Tutors. All rights reserved.</p>
        </div>
    </footer>

    <script src="../index.js"></script>
</body>

</html>
