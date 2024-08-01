<?php
session_start();
include("../../settings/connection.php");
include "../../settings/core_admin.php";


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['addCleaner'])) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $bio = $_POST['bio'];
        // Default availability status
        $availabilityStatus = 'Available';

        $stmt = $conn->prepare("INSERT INTO Cleaners (name, email, bio, availabilityStatus) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $bio, $availabilityStatus);

        if ($stmt->execute()) {
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Cleaner Added',
                        text: 'The cleaner has been added successfully.',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location = 'manage_cleaners.php';
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
                        text: 'There was an error adding the cleaner. Please try again.',
                        confirmButtonText: 'OK'
                    });
                });
            </script>";
        }

        $stmt->close();
    } elseif (isset($_POST['deleteCleanerId'])) {
        $cleanerId = $_POST['deleteCleanerId'];

        // Check if the cleaner has any pending jobs
        $checkStmt = $conn->prepare("SELECT * FROM Jobs WHERE cleanerId = ? AND status = 'Pending'");
        $checkStmt->bind_param("i", $cleanerId);
        $checkStmt->execute();
        $result = $checkStmt->get_result();

        if ($result->num_rows > 0) {
            // Cleaner has pending jobs, cannot be deleted
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Cleaner Cannot Be Deleted',
                        text: 'This cleaner assigned to pending jobs.',
                        confirmButtonText: 'OK'
                    });
                });
            </script>";
        } else {
            // Cleaner has no pending jobs, proceed with deletion
            $deleteStmt = $conn->prepare("DELETE FROM Cleaners WHERE cleanerId = ?");
            $deleteStmt->bind_param("i", $cleanerId);

            if ($deleteStmt->execute()) {
                echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'success',
                            title: 'Cleaner Deleted',
                            text: 'The cleaner has been deleted successfully.',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location = 'manage_cleaners.php';
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
                            text: 'There was an error deleting the cleaner. Please try again.',
                            confirmButtonText: 'OK'
                        });
                    });
                </script>";
            }

            $deleteStmt->close();
        }

        $checkStmt->close();
    }
}


$cleaners = $conn->query("SELECT * FROM Cleaners");

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
    <link href="../../assets/images/img/favicon.ico" rel="icon">

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
                            <a href="#">ChorIn</a>
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

                                    <a href="admin_home_view.php" class="nav-item nav-link ">Home</a>
                                    <a href="job_listing_view.php" class="nav-item nav-link ">Job Listing</a>
                                    <div class="nav-item dropdown">
                                        <a href="#" class="nav-link dropdown-toggle active" data-toggle="dropdown">Manage</a>
                                        <div class="dropdown-menu">
                                            <a href="manage_categories.php" class="dropdown-item">Categories</a>
                                            <a href="manage_cleaners.php" class="dropdown-item active">Cleaners</a>
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
                        <h2>Manage Cleaners</h2>
                    </div>
                    <div class="col-12">
                        <a href="">Onboarded Cleaners</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Page Header End -->


        <div class="container">
            <form action="manage_cleaners.php" method="POST">
                <h3>Add Cleaner</h3>
                <label for="name">Name</label>
                <input type="text" id="name" name="name" required>

                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>

                <label for="bio">Bio</label>
                <input type="text" id="bio" name="bio" required>

                <button type="submit" name="addCleaner">Add Cleaner</button>
            </form>

            <h3>Cleaners List</h3>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>

                            <th>Name</th>
                            <th>Email</th>
                            <th>Bio</th>
                            <th>Availability Status</th>
                            <th>Completed Jobs Count</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $cleaners->fetch_assoc()) { ?>
                            <tr>

                                <td><?php echo $row['name']; ?></td>
                                <td><?php echo $row['email']; ?></td>
                                <td><?php echo $row['bio']; ?></td>
                                <td><?php echo $row['availabilityStatus']; ?></td>
                                <td><?php echo $row['completedJobsCount']; ?></td>
                                <td>
                                    <form method="POST" action="manage_cleaners.php" onsubmit="return confirmDelete(this);">
                                        <input type="hidden" name="deleteCleanerId" value="<?php echo $row['cleanerId']; ?>">
                                        <button type="submit">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <script>
                function confirmDelete(form) {
                    event.preventDefault();
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'No, cancel!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                    return false;
                }
            </script>
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