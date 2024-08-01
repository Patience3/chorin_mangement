<?php
session_start();
include("../../settings/connection.php");
include "../../settings/core_admin.php";




if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['addCategory'])) {
        $categoryName = $_POST['categoryName'];
        $description = $_POST['description'];

        $stmt = $conn->prepare("INSERT INTO JobCategories (categoryName, description) VALUES (?, ?)");
        $stmt->bind_param("ss", $categoryName, $description);

        if ($stmt->execute()) {
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Category Added',
                        text: 'The category has been added successfully.',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location = 'manage_categories.php';
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
                        text: 'There was an error adding the category. Please try again.',
                        confirmButtonText: 'OK'
                    });
                });
            </script>";
        }

        $stmt->close();
    } elseif (isset($_POST['deleteCategoryId'])) {
        $categoryId = $_POST['deleteCategoryId'];

        $stmt = $conn->prepare("DELETE FROM JobCategories WHERE categoryId = ?");
        $stmt->bind_param("i", $categoryId);

        if ($stmt->execute()) {
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Category Deleted',
                        text: 'The category has been deleted successfully.',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location = 'manage_categories.php';
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
                        text: 'There was an error deleting the category. Please try again.',
                        confirmButtonText: 'OK'
                    });
                });
            </script>";
        }

        $stmt->close();
    }
}

$categories = $conn->query("SELECT * FROM JobCategories");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>ChorIn</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Cleaning Company Website Template" name="keywords">
    <meta content="Cleaning Company Website Template" name="description">
    <link href="../../assets/images/img/favicon.ico" rel="icon">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;300;400&display=swap" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="../../css/css/lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="../../css/css/lib/lightbox/css/lightbox.min.css" rel="stylesheet">
    <link href="../../css/css/style.css" rel="stylesheet">
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
                                    <a href="job_listing_view.php" class="nav-item nav-link">Job Listing</a>
                                    <div class="nav-item dropdown">
                                        <a href="#" class="nav-link dropdown-toggle active" data-toggle="dropdown">Manage</a>
                                        <div class="dropdown-menu">
                                            <a href="manage_categories.php" class="dropdown-item active">Categories</a>
                                            <a href="manage_cleaners.php" class="dropdown-item">Cleaners</a>
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
                        <h2>Manage Categories</h2>
                    </div>
                    <div class="col-12">
                        <a href="">See your jobs and our service offerings</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Page Header End -->

        <div class="container">
            <form action="manage_categories.php" method="POST">
                <h3>Add Category</h3>
                <label for="categoryName">Category Name</label>
                <input type="text" id="categoryName" name="categoryName" required>

                <label for="description">Description</label>
                <input id="description" name="description" required>

                <button type="submit" name="addCategory">Add Category</button>
            </form>

            <h3>Categories List</h3>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                           
                            <th>Category Name</th>
                            <th>Description</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $categories->fetch_assoc()) { ?>
                            <tr>
                               
                                <td><?php echo $row['categoryName']; ?></td>
                                <td><?php echo $row['description']; ?></td>
                                <td>
                                    <form method="POST" action="manage_categories.php" onsubmit="return confirmDelete(this);">
                                        <input type="hidden" name="deleteCategoryId" value="<?php echo $row['categoryId']; ?>">
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
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
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
