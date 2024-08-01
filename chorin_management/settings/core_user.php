<?php

// Function to check if user is logged in

    $userEmail = $_SESSION['user_email'];

    // Check if the user is logged in and is a regular user
    if (!isset($_SESSION['user_email']) || !isset($_SESSION['userId']) || $_SESSION['userType'] !== 'user') {
        // Redirect to login page if not logged in as a regular user
        header("Location: ../../login/login_view.php");
        exit();
}
