<?php
// Start session
session_start();
// Include the connection file
include '../../settings/connection.php';
include "../../settings/core_admin.php";



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['deleteJobId'])) {
        $jobId = $_POST['deleteJobId'];

        $stmt = $conn->prepare("DELETE FROM Jobs WHERE JobId = ?");
        $stmt->bind_param("i", $jobId);

        if ($stmt->execute()) {
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Job Deleted',
                        text: 'The Job has been deleted successfully.',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location = 'job_listing_view.php';
                        }
                    });
                });
            </script>";
        } else {
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'There was an error deleting the Job. Please try again.',
                        confirmButtonText: 'OK'
                    });
                });
            </script>";
        }

        $stmt->close();
    }
}



$search = "";
if (isset($_GET['search'])) {
    $search = $_GET['search'];
}

$statusFilter = "";
if (isset($_GET['status'])) {
    $statusFilter = $_GET['status'];
}

$query = "SELECT Jobs.jobId, Jobs.job_address, Jobs.status, Users.name AS userName, Cleaners.name AS cleanerName, JobCategories.categoryName 
          FROM Jobs 
          LEFT JOIN UserJobs ON Jobs.jobId = UserJobs.jobId 
          LEFT JOIN Users ON UserJobs.userId = Users.userId 
          LEFT JOIN Cleaners ON Jobs.cleanerId = Cleaners.cleanerId 
          LEFT JOIN JobCategories ON Jobs.categoryId = JobCategories.categoryId 
          WHERE Jobs.job_address LIKE ?";

if ($statusFilter) {
    $query .= " AND Jobs.status = ?";
}

$stmt = $conn->prepare($query);
$searchParam = "%$search%";

if ($statusFilter) {
    $stmt->bind_param("ss", $searchParam, $statusFilter);
} else {
    $stmt->bind_param("s", $searchParam);
}

$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>ManageJobs</title>
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
    <link href="../../css/css/lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="../../css/css/lib/lightbox/css/lightbox.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="../../css/css/style.css" rel="stylesheet">
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
                                    <a href="admin_home_view.php" class="nav-item nav-link">Home</a>
                                    <a href="job_listing_view.php" class="nav-item nav-link active">Job Listing</a>
                                    <div class="nav-item dropdown">
                                        <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Manage</a>
                                        <div class="dropdown-menu">
                                            <a href="manage_categories.php" class="dropdown-item">Categories</a>
                                            <a href="manage_cleaners.php" class="dropdown-item">Cleaners</a>
                                        </div>
                                    </div>
                                    <a href="../../login/logout_view.php" class="btn">logout</a>
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
                        <h2>Job Listings</h2>
                    </div>
                    <div class="col-12">
                        <a href="">See your jobs and our service offernings</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Page Header End -->

        <!-- Manage table starts-->
        <div class="jobs-table">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <h3>Job list</h3>
                        <form method="GET" action="job_listing_view.php">
                            <input type="text" name="search" placeholder="Search by address" value="<?php echo htmlspecialchars($search); ?>">
                            <select name="status">
                                <option value="">All</option>
                                <option value="Pending" <?php if ($statusFilter == "Pending") echo 'selected'; ?>>Pending</option>
                                <option value="Completed" <?php if ($statusFilter == "Completed") echo 'selected'; ?>>Completed</option>
                            </select>
                            <button type="submit">Search</button>
                        </form>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>

                                        <th>Address</th>
                                        <th>Status</th>
                                        <th>Description</th>
                                        <th>User</th>
                                        <th>Cleaner</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = $result->fetch_assoc()) { ?>
                                        <tr>

                                            <td><?php echo htmlspecialchars($row['job_address']); ?></td>
                                            <td><?php echo htmlspecialchars($row['status']); ?></td>
                                            <td><?php echo htmlspecialchars($row['categoryName']); ?></td>
                                            <td><?php echo htmlspecialchars($row['userName']); ?></td>
                                            <td><?php echo htmlspecialchars($row['cleanerName']); ?></td>
                                            <td>
                                                <form method="POST" class="delete-job-form" onsubmit="return confirmDelete(event);">
                                                    <input type="hidden" name="deleteJobId" value="<?php echo htmlspecialchars($row['jobId']); ?>">
                                                    <button type="submit">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Manage table ends-->
    </div>

    <script>
        function confirmDelete(event) {
            event.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    event.target.submit();
                }
            });
        }
    </script>
  

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
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="../../css/css/lib/easing/easing.min.js"></script>
    <script src="../../css/css/lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="../../css/css/lib/isotope/isotope.pkgd.min.js"></script>
    <script src="../../css/css/lib/lightbox/js/lightbox.min.js"></script>
    <!-- Contact Javascript File -->
    <script src="../../css/mail/jqBootstrapValidation.min.js"></script>
    <script src="../../css/mail/contact.js"></script>
    <!-- Template Javascript -->
    <script src="../../css/js/main.js"></script>
</body>

</html>