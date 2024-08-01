<?php
session_start();
include("../settings/connection.php"); // Update the path as needed

// Get POST data
$email = $_POST['email'];
$password = $_POST['password'];

// Function to show alert and redirect
function redirectWithMessage($message, $location)
{
    echo "<script type='text/javascript'>alert('$message'); window.location.href='$location';</script>";
    exit();
}

// Check in Admin table first
$sql = "SELECT adminId, email, password FROM Admin WHERE email = ?";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die("Error preparing statement: " . $conn->error);
}

$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $admin = $result->fetch_assoc();


    // Verify password
    $hashed_passwd = password_hash($password, PASSWORD_DEFAULT);
    if (password_verify($admin["password"], $hashed_passwd)) {
        // Store admin data in session
        $_SESSION['admin_email'] = $admin['email']; // Separate admin email
        $_SESSION['adminId'] = $admin['adminId'];
        $_SESSION['userType'] = 'admin';

        // Set success status for SweetAlert
        $_SESSION['login_status'] = 'success';
        
        // Redirect to admin dashboard
        header("Location: ../view/admin/admin_home_view.php");
        exit();
    } else {
        // Incorrect password for admin
        $_SESSION['login_status'] = 'failed';
    }
} else {
    // Email not found in Admin table, check in Users table
    $sql = "SELECT userId, email, password FROM Users WHERE email = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['password'])) {
            // Store user data in session
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['userId'] = $user['userId'];
            $_SESSION['userType'] = 'user'; // Add user type to session

            // Set success status for SweetAlert
            $_SESSION['login_status'] = 'success';
        } else {
            $_SESSION['login_status'] = 'failed';
        }
    } else {
        $_SESSION['login_status'] = 'not_registered';
    }

    $stmt->close();
}

// Redirect back to the login view
header("Location: ../login/login_view.php");
exit();
