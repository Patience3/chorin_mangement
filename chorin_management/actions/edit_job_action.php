<?php
// Start session
session_start();

// Include the connection file
include '../settings/connection.php';

// Initialize variables
$jobId = $categoryId = $job_address = $description = $cleanerId = '';
$jobCategories = [];
$cleaners = [];

// Fetch job categories
$category_sql = "SELECT categoryId, categoryName FROM JobCategories";
$result = $conn->query($category_sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $jobCategories[] = $row;
    }
}

// Fetch available cleaners
$cleaner_sql = "SELECT cleanerId, name AS cleanerName FROM Cleaners WHERE availabilityStatus = 'available'";
$result = $conn->query($cleaner_sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $cleaners[] = $row;
    }
}

// Check if job ID is provided and fetch job details if it is
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['jobId'])) {
    $jobId = $_GET['jobId'];

    // Fetch job details
    $select_sql = "SELECT jobId, categoryId, job_address, description, cleanerId FROM Jobs WHERE jobId = ?";
    $stmt = $conn->prepare($select_sql);
    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }
    $stmt->bind_param("i", $jobId);
    if ($stmt->execute() === false) {
        die("Error executing query: " . $stmt->error);
    }
    $result = $stmt->get_result();
    if ($result === false) {
        die("Error fetching result set: " . $stmt->error);
    }
    $job = $result->fetch_assoc();
    $stmt->close();

    // Assign fetched values to variables for form population
    if ($job) {
        $categoryId = $job['categoryId'];
        $job_address = $job['job_address'];
        $description = $job['description'];
        $cleanerId = $job['cleanerId']; // Fetch the cleaner ID as well
    } else {
        die("Job not found.");
    }
}

// Handle form submission for updating job
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['jobId'])) {
    $jobId = $_POST['jobId'];
    $categoryId = $_POST['categoryId'];
    $job_address = $_POST['job_address'];
    $description = $_POST['description'];
    $cleanerId = $_POST['cleanerId']; // Get the selected cleaner ID

    // Update job details in Jobs table
    $update_sql = "UPDATE Jobs SET categoryId = ?, job_address = ?, description = ?, cleanerId = ? WHERE jobId = ?";
    $stmt = $conn->prepare($update_sql);
    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }
    $stmt->bind_param("issii", $categoryId, $job_address, $description, $cleanerId, $jobId);
    if ($stmt->execute() === false) {
        die("Error executing query: " . $stmt->error);
    }
    $stmt->close();

    // Redirect back to dashboard after updating
    header("Location: ../view/User/service.php");
    exit();
}

// Close database connection
$conn->close();
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
    <link href="../img/favicon.ico" rel="icon">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;300;400&display=swap" rel="stylesheet">

    <!-- CSS Libraries -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="../css/css/lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="../css/css/lib/lightbox/css/lightbox.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="../css/css/style.css" rel="stylesheet">
</head>

<body>
    <div class="wrapper">
        <!-- Header Start -->
        <div class="header">
            <div class="container-fluid">
                <div class="header-top row align-items-center">
                    <div class="col-lg-3">
                        <div class="brand">
                            <a href="#">
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
                                    <!--<a href="index.php" class="nav-item nav-link">Home</a>-->
                                    <a href="about.php" class="nav-item nav-link ">About</a>
                                    <a href="service.php" class="nav-item nav-link">Dashboard</a>
                                    <a href="contact.php" class="nav-item nav-link active">Contact</a>
                                    <div class="nav-item dropdown">
                                        <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Profile</a>
                                        <div class="dropdown-menu">
                                            <!--<a href="User_dashboar.php" class="dropdown-item">Dashboard</a>-->
                                            <a href="../../login/logout_view.php" class="dropdown-item">Logout</a>
                                        </div>
                                    </div>

                                    <!--<a href="chorin_management/view/User/form.php" class="btn">Get a service</a>-->
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


        <body>
            <div class="hero align-items-center">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-md-6">
                            <div class="form">
                                <h3>Edit Job</h3>
                                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                    <input type="hidden" name="jobId" value="<?php echo $jobId; ?>">

                                    <div class="form-group">
                                        <label for="categoryId">Job Category:</label>
                                        <select class="form-control" id="categoryId" name="categoryId" required>
                                            <option value="">Select Job Category</option>
                                            <?php foreach ($jobCategories as $category) : ?>
                                                <option value="<?php echo $category['categoryId']; ?>" <?php echo $categoryId == $category['categoryId'] ? 'selected' : ''; ?>>
                                            <?php echo $category['categoryName']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="job_address">Job Address:</label>
                                    <input type="text" class="form-control" id="job_address" name="job_address" value="<?php echo $job_address; ?>" required>
                                </div>

                                <div class="form-group">
                                    <label for="description">Job Description:</label>
                                    <textarea class="form-control" id="description" name="description" rows="4" required><?php echo $description; ?></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="cleanerId">Select Cleaner:</label>
                                    <select class="form-control" id="cleanerId" name="cleanerId" required>
                                        <option value="">Select Cleaner</option>
                                        <?php foreach ($cleaners as $cleaner) : ?>
                                            <option value="<?php echo $cleaner['cleanerId']; ?>" <?php echo $cleanerId == $cleaner['cleanerId'] ? 'selected' : ''; ?>>
                                                <?php echo $cleaner['cleanerName']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <button type="submit" class="btn btn-block btn-primary">Update Job</button>
                            </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
                                <a href="#">About Us</a>
                                <a href="#">Our Services</a>
                                <a href="#">Contact Us</a>
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
    <script src="../css/css/lib/easing/easing.min.js"></script>
    <script src="../css/css/lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="../css/css/lib/isotope/isotope.pkgd.min.js"></script>
    <script src="../css/css/lib/lightbox/js/lightbox.min.js"></script>

    <!-- Template Javascript -->
    <script src="../js/main.js"></script>
</body>

</html>