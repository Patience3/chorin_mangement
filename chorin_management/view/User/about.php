<?php

session_start();
include("../../settings/connection.php");
include "../../settings/core_user.php";



// Fetch cleaners from the database
$cleaners = [];
$sql = "SELECT * FROM Cleaners";
$result = mysqli_query($conn, $sql);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $cleaners[] = $row;
    }
} else {
    echo "Error fetching cleaners: " . mysqli_error($conn);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>ChorIn</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Cleaning Company Website Template" name="keywords">
    <meta content="Cleaning Company Website Template" name="description">

    <!-- Favicon -->
    <link href="../../img/favicon.ico" rel="icon">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;300;400&display=swap" rel="stylesheet">

    <!-- CSS Libraries -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="../../css/css/lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="../../css/css/lib/lightbox/css/lightbox.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="../../css/css/style.css" rel="stylesheet">
</head>

<body>
    <div class="wrapper">
        <!-- Header Start -->
        <div class="header">
            <div class="container-fluid">
                <div class="header-top row align-items-center">
                    <div class="col-lg-3">
                        <div class="brand">
                            <!--<a href="index.php">-->
                            ChorIn
                            <!-- <img src="img/logo.png" alt="Logo"> -->
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-9">
                        <div class="topbar">
                            <div class="topbar-col">
                                <a href="tel:+012 345 67890"><i class="fa fa-phone-alt"></i>+233205552678</a>
                            </div>
                            <div class="topbar-col">
                                <a href="mailto:info@example.com"><i class="fa fa-envelope"></i>ChorIn@gmail.com</a>
                            </div>
                            <div class="topbar-col">
                                <div class="topbar-social">
                                    <a href=""><i class="fab fa-twitter"></i></a>
                                    <a href=""><i class="fab fa-facebook-f"></i></a>
                                    <a href=""><i class="fab fa-youtube"></i></a>
                                    <a href=""><i class="fab fa-instagram"></i></a>
                                    <a href=""><i class="fab fa-linkedin-in"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="navbar navbar-expand-lg bg-light navbar-light">
                            <a href="#" class="navbar-brand">MENU</a>
                            <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
                                <span class="navbar-toggler-icon"></span>
                            </button>

                            <div class="collapse navbar-collapse justify-content-between" id="navbarCollapse">
                                <div class="navbar-nav ml-auto">
                                    <!-- <a href="index.php" class="nav-item nav-link">Home</a>-->
                                    <a href="about.php" class="nav-item nav-link active">About</a>
                                    <a href="service.php" class="nav-item nav-link">Dashboard</a>
                                    <a href="contact.php" class="nav-item nav-link">Contact</a>
                                    <div class="nav-item dropdown">
                                        <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Profile</a>
                                        <div class="dropdown-menu">
                                            <!--<a href="User_dashboar.php" class="dropdown-item">Dashboard</a>-->
                                            <a href="../../login/logout_view.php" class="dropdown-item">Logout</a>
                                        </div>
                                    </div>
                                    <a href="form.php" class="btn">Get a service</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Header End -->


        <!-- Page Header Start -->
        <div class="page-header">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <h2>About Us</h2>
                    </div>
                    <div class="col-12">
                        <a href="">Home</a>
                        <a href="">About Us</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Page Header End -->


        <!-- About Start -->
        <div class="about">
            <div class="container">
                <div class="row">
                  
                    <div class="col-lg-8 col-md-12">
                        <div class="about-text">
                           
                            <p>
                                Welcome to ChorIn, where we are dedicated to bringing a radiant glow to your home or office. Our commitment to excellence is driven by our unwavering belief that "The customer is the King." This mantra guides every aspect of our service, ensuring that your satisfaction is always our top priority.
                            </p>
                            <p>
                                At ChorIn, we understand the importance of a clean and inviting space. Our team of professionals is passionate about delivering exceptional cleaning services that meet and exceed your expectations. Whether it's your home or office, we take pride in our meticulous attention to detail and our personalized approach to every job.
                            </p>
                            <p>
                                We invite you to experience the difference that [Your Company Name] can make. Give us a try, and let us transform your space into a shining example of cleanliness and comfort.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- About End -->

        <style>
            .team .team-item {
                margin-bottom: 100px;
                /* Adjust the value to increase or decrease the spacing */
            }
        </style>

        <!-- Team Start -->
        <div class="team">
            <div class="container">
                <div class="section-header">
                    <p>Team Member</p>
                    <h2>Meet Our Expert Cleaners</h2>
                </div>
                <div class="row">
                    <?php foreach ($cleaners as $cleaner) : ?>
                        <div class="col-lg-3 col-md-6">
                            <div class="team-item">
                                <div class="team-img">
                                    <img src="../../img/team-<?= $cleaner['cleanerId'] ?>.jpg" alt="Team Image"> <!-- Adjust the image source as needed -->
                                </div>
                                <div class="team-text">
                                    <h2><?= htmlspecialchars($cleaner['name']) ?></h2>
                                    <h3><?= htmlspecialchars($cleaner['bio']) ?: 'Cleaner' ?></h3> <!-- Default to 'Cleaner' if bio is not set -->
                                    <div class="team-social">
                                        <a class="social-tw" href="#"><i class="fab fa-twitter"></i></a>
                                        <a class="social-fb" href="#"><i class="fab fa-facebook-f"></i></a>
                                        <a class="social-li" href="#"><i class="fab fa-linkedin-in"></i></a>
                                        <a class="social-in" href="#"><i class="fab fa-instagram"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <!-- Team End -->


        <!-- Footer Start -->
        <div class="footer">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 col-lg-3">
                        <div class="footer-contact">
                            <h2>Get In Touch</h2>
                            <p><i class="fa fa-map-marker-alt"></i>247 street Ghana</p>
                            <p><i class="fa fa-phone-alt"></i>+233205552678</p>
                            <p><i class="fa fa-envelope"></i>ChorIn@gmail.com</p>
                            <div class="footer-social">
                                <a href=""><i class="fab fa-twitter"></i></a>
                                <a href=""><i class="fab fa-facebook-f"></i></a>
                                <a href=""><i class="fab fa-youtube"></i></a>
                                <a href=""><i class="fab fa-instagram"></i></a>
                                <a href=""><i class="fab fa-linkedin-in"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="footer-link">
                            <h2>Useful Link</h2>
                            <a href="about.php">About Us</a>
                            <a href="service.php">Our Services</a>
                            <a href="contact.php">Contact Us</a>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="footer-form">
                            <h2>Stay Updated</h2>
                            <p>
                                Ask us anything
                            </p>
                            <input class="form-control" placeholder="Email here">
                            <button class="btn">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container footer-menu">
                <div class="f-menu">

                    <a href="contact.php">Contact us</a>
                </div>
            </div>
            <div class="container copyright">
                <div class="row">
                    <div class="col-md-6">
                        <p>&copy; <a href="https://htmlcodex.com">HTML Codex</a>, All Right Reserved.</p>
                    </div>
                    <div class="col-md-6">
                        <p>Designed By <a href="https://htmlcodex.com">HTML Codex</a></p>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer End -->

        <a href="#" class="back-to-top"><i class="fa fa-chevron-up"></i></a>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="../../css/css/lib/easing/easing.min.js"></script>
    <script src="../../css/css/lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="../../css/css/lib/isotope/isotope.pkgd.min.js"></script>
    <script src="../../css/css/lib/lightbox/js/lightbox.min.js"></script>

    <!-- Template Javascript -->
    <script src="../..js/main.js"></script>
</body>

</html>