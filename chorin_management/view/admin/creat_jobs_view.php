<?php
session_start();
include "../../settings/connection.php";

include "../../settings/core_admin.php";




// Fetch active jobs created by the current user
$jobs = [];
$sql = "SELECT Jobs.jobId, JobCategories.categoryName, Jobs.job_address, Jobs.status, Cleaners.name AS cleanerName, Jobs.description
        FROM Jobs
        INNER JOIN JobCategories ON Jobs.categoryId = JobCategories.categoryId
        LEFT JOIN Cleaners ON Jobs.cleanerId = Cleaners.cleanerId
        INNER JOIN UserJobs ON Jobs.jobId = UserJobs.jobId
        INNER JOIN Users ON UserJobs.userId = Users.userId
        WHERE Users.email = ? AND Jobs.status != 'Completed'";
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die("Error preparing statement: " . $conn->error);
}
$stmt->bind_param("s", $userEmail);
$stmt->execute();
$result = $stmt->get_result();
if ($result === false) {
    die("Error executing query: " . $stmt->error);
}
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $jobs[] = $row;
    }
}
$stmt->close();

// Fetch completed jobs created by the current user
$completedJobs = [];
$sql = "SELECT Jobs.jobId, JobCategories.categoryName, Jobs.job_address, Jobs.status, Cleaners.name AS cleanerName, Jobs.description
        FROM Jobs
        INNER JOIN JobCategories ON Jobs.categoryId = JobCategories.categoryId
        LEFT JOIN Cleaners ON Jobs.cleanerId = Cleaners.cleanerId
        INNER JOIN UserJobs ON Jobs.jobId = UserJobs.jobId
        INNER JOIN Users ON UserJobs.userId = Users.userId
        WHERE Users.email = ? AND Jobs.status = 'Completed'";
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die("Error preparing statement: " . $conn->error);
}
$stmt->bind_param("s", $userEmail);
$stmt->execute();
$result = $stmt->get_result();
if ($result === false) {
    die("Error executing query: " . $stmt->error);
}
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $completedJobs[] = $row;
    }
}
$stmt->close();
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
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;300;400&display=swap" rel="stylesheet">

    <!-- CSS Libraries -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="css/lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="css/lib/lightbox/css/lightbox.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
    <!-- Include SweetAlert CSS and JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div class="wrapper">
        <!-- Header Start -->
        <div class="header">
            <div class="container-fluid">
                <div class="header-top row align-items-center">
                    <div class="col-lg-3">
                        <div class="brand">
                            <a href="index.php">ChorIn</a>
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
                                    <a href="admin_homev_iew.php" class="nav-item nav-link active">Home</a>
                                    <!--<a href="service.php" class="nav-item nav-link ">Create Jobs</a>-->
                                    <a href="job_listing_view.php" class="nav-item nav-link ">Job Listing</a>
                                    <div class="nav-item dropdown">
                                        <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Manage</a>
                                        <div class="dropdown-menu">
                                            <a href="manage_categories.php" class="dropdown-item">Categories</a>
                                            <a href="manage_categories.php" class="dropdown-item">Cleaners</a>
                                        </div>
                                    </div>
                                    <a href="../../login/logout_view.php" class="btn">Logout</a>
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
                        <h2>My Dashboard</h2>
                    </div>
                    <div class="col-12">
                        <a href="">See your jobs and our service offernings</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Page Header End -->

        <!-- Jobs Table Start -->
        <div class="jobs-table">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <h3>Your Jobs</h3>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Category</th>
                                        <th>Address</th>
                                        <th>Status</th>
                                        <th>Cleaner</th>
                                        <th>Description</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($jobs as $index => $job) : ?>
                                        <tr>
                                            <td><?php echo $index + 1; ?></td>
                                            <td><?php echo $job['categoryName']; ?></td>
                                            <td><?php echo $job['job_address']; ?></td>
                                            <td><?php echo $job['status']; ?></td>
                                            <td><?php echo isset($job['cleanerName']) ? $job['cleanerName'] : 'Not assigned'; ?></td>
                                            <td><?php echo $job['description']; ?></td>
                                            <td>
                                                <a href="chorin_management/actions/mark_job_complete_action.php?jobId=<?php echo $job['jobId']; ?>">Mark Complete</a> |
                                                <a href="chorin_management/actions/edit_job_action.php?jobId=<?php echo $job['jobId']; ?>">Edit</a> |
                                                <a href="chorin_management/actions/delete_job_action.php?jobId=<?php echo $job['jobId']; ?>">Delete</a>
                                                <!-- Your form for deleting a job-->
                                               <!-- <form id="deleteForm" method="POST" action="">
                                                    <input type="hidden" name="jobId" value="<?php echo htmlspecialchars($_GET['jobId']); ?>">
                                                    <input type="button" value="Delete Job" onclick="confirmDelete()">
                                                </form>
                                                <script>
                                                    function confirmDelete() {
                                                        Swal.fire({
                                                            title: 'Are you sure?',
                                                            text: 'This entry will be permanently deleted',
                                                            icon: 'warning',
                                                            showCancelButton: true,
                                                            confirmButtonColor: '#3085d6',
                                                            cancelButtonColor: '#d33',
                                                            confirmButtonText: 'Yes, delete it!'
                                                        }).then((result) => {
                                                            if (result.isConfirmed) {
                                                                document.getElementById('deleteForm').submit();
                                                            }
                                                        });
                                                    }
                                                </script>-->
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Jobs Table End -->
        <!-- Completed Jobs Table Start -->
        <div class="jobs-table">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <h3>Completed Jobs</h3>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Category</th>
                                        <th>Address</th>
                                        <th>Cleaner</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($completedJobs as $index => $job) : ?>
                                        <tr>
                                            <td><?php echo $index + 1; ?></td>
                                            <td><?php echo $job['categoryName']; ?></td>
                                            <td><?php echo $job['job_address']; ?></td>
                                            <td><?php echo isset($job['cleanerName']) ? $job['cleanerName'] : 'Not assigned'; ?></td>
                                            <td><?php echo $job['description']; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Completed Jobs Table End -->


        <!-- Service Start -->
        <div class="service">
            <div class="container">
                <div class="section-header">
                    <p>Our Offering</p>
                    <h3>Provide Services Worldwide</h3>
                </div>
                <div class="row">
                    <div class="col-lg-3 col-md-6">
                        <div class="service-item">
                            <img src="img/service-1.jpg" alt="Service">
                            <h3>Floor Cleaning</h3>
                            <p>
                                Here we focus on all thypes of floors, cement, tiles and wood floors
                            </p>
                            <!--<a class="btn" href="chorin_management/view/User/form.php">Book a service</a>-->
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="service-item">
                            <img src="img/service-2.jpg" alt="Service">
                            <h3>Glass Cleaning</h3>
                            <p>
                                Here we focus on all types of glasses, windows and bulding with walls built from glass.
                            </p>
                            <!--<a class="btn" href="chorin_management/view/User/form.php">Book a service</a>-->
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="service-item">
                            <img src="img/service-3.jpg" alt="Service">
                            <h3>Carpet Cleaning</h3>
                            <p>
                                Here we focus on wool , grass and plastic carpets.
                            </p>
                            <!--<a class="btn" href="chorin_management/view/User/form.php">Book a service</a>-->
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="service-item">
                            <img src="img/service-4.jpg" alt="Service">
                            <h3>Toilet Cleaning</h3>
                            <p>
                                Here we focus on all types of toilets, pit, water closet and bucket latrines.
                            </p>
                            <!--<a class="btn" href="chorin_management/view/User/form.php">Book a service</a>-->
                        </div>
                        <a class="btn" href="chorin_management/view/User/form.php">Book a service</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Service End -->

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
    <script src="css/lib/easing/easing.min.js"></script>
    <script src="css/lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="css/lib/isotope/isotope.pkgd.min.js"></script>
    <script src="css/lib/lightbox/js/lightbox.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>

</html>