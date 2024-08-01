<?php
session_start();
include("../../settings/connection.php");
include "../../settings/core_user.php";

// Fetch job categories from the database
$jobCategories = [];
$sql = "SELECT * FROM JobCategories";
$result = mysqli_query($conn, $sql);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $jobCategories[] = $row;
    }
}

// Fetch cleaners who are available and not assigned to any pending job
$cleaners = [];
$sql = "SELECT cleanerId, name, email FROM Cleaners WHERE availabilityStatus = 'Available'";
// AND cleanerId NOT IN (SELECT cleanerId FROM Jobs WHERE status = 'Pending')";
$result = mysqli_query($conn, $sql);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $cleaners[] = $row;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $categoryId = $_POST['categoryId'];
    $job_address = $_POST['job_address'];
    $cleanerId = $_POST['cleanerId'];
    $description = $_POST['description'];

    // Insert the new job into the Jobs table
    $sql = "INSERT INTO Jobs (categoryId, job_address, status, cleanerId, description) VALUES ('$categoryId', '$job_address', 'Pending', '$cleanerId', '$description')";
    if (mysqli_query($conn, $sql)) {
        // Update cleaner's availability status to 'unavailable'
        $sql = "UPDATE Cleaners SET availabilityStatus = 'unavailable' WHERE cleanerId = '$cleanerId'";
        mysqli_query($conn, $sql);

        // Send email notification to the cleaner
        $cleanerEmail = '';
        $cleanerName = '';
        foreach ($cleaners as $cleaner) {
            if ($cleaner['cleanerId'] == $cleanerId) {
                $cleanerEmail = $cleaner['email'];
                $cleanerName = $cleaner['name'];
                break;
            }
        }

        $clientName = $_SESSION['name']; // Assuming the client's name is stored in session
        $subject = "Job Assignment Notification";
        $message = "Dear $cleanerName,\n\nYou have been paired with client $clientName at the location $job_address for the job.\n\nJob Description:\n$description\n\nPlease contact the client for further details.\n\nBest regards,\nChorIn Team";
        $headers = "From: ChorIn <Sombangpatience@gmail.com>";

        if (mail($cleanerEmail, $subject, $message, $headers)) {
            echo "Job created and notification sent successfully.";
        } else {
            echo "Job created but notification email failed to send.";
        }
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
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
                            <a href="index.php">
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
                                    <a href="about.php" class="nav-item nav-link ">About</a>
                                    <a href="service.php" class="nav-item nav-link">Dashboard</a>
                                    <a href="contact.php" class="nav-item nav-link">Contact</a>
                                    <div class="nav-item dropdown">
                                        <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Profile</a>
                                        <div class="dropdown-menu">
                                            <a href="../../login/logout_view.php" class="dropdown-item">Logout</a>
                                        </div>
                                    </div>


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
                        <h2>Contact Us</h2>
                    </div>
                    <div class="col-12">
                        <a href="">Home</a>
                        <a href="">Contact Us</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Page Header End -->


        <div class="hero align-items-center">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="form">
                            <h3>Create a Job</h3>
                            <form method="POST" action="../../actions/job_creation_action.php">
                                <div class="form-group">
                                    <label for="categoryId">Job Category:</label>
                                    <select class="form-control" id="categoryId" name="categoryId" required>
                                        <option value="">Select Job Category</option>
                                        <?php foreach ($jobCategories as $category) : ?>
                                            <option value="<?php echo $category['categoryId']; ?>"><?php echo $category['categoryName']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="job_address">Job Address:</label>
                                    <input type="text" class="form-control" id="job_address" name="job_address" required>
                                </div>
                                <div class="form-group">
                                    <label for="cleanerId">Select Cleaner:</label>
                                    <select class="form-control" id="cleanerId" name="cleanerId" required>
                                        <option value="">Select Cleaner</option>
                                        <?php foreach ($cleaners as $cleaner) : ?>
                                            <option value="<?php echo $cleaner['cleanerId']; ?>"><?php echo $cleaner['name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="description">Job Description:</label>
                                    <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-block btn-primary">Submit Job</button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer content here --><!-- Footer Start -->
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
    <script src="../../js/main.js"></script>
</body>

</html>
<?php
$conn->close();
?>