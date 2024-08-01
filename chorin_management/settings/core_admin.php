<?php


    $userEmail = $_SESSION['admin_email'];

    // Check if the user is logged in and is an admin
    if (!isset($_SESSION['admin_email']) || !isset($_SESSION['userId']) || $_SESSION['userType'] !== 'admin') {
        // Redirect to login page if not logged in as admin
        header("Location: ../../login/login_view.php");
        exit();
    
}
