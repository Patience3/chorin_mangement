<?php
// Start session
session_start();

// Include the connection file
include '../settings/connection.php';

// Handling form submission for sign up
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $home_address = $conn->real_escape_string($_POST['user_address']);

    // Save form data in session
    $_SESSION['form_data'] = $_POST;

    // Check if passwords match
    if ($password !== $confirm_password) {
        $_SESSION['status'] = 'mismatch';
        header("Location: ../login/signup_view.php");
        exit();
    }

    // Check if name contains only alphabets
    if (!preg_match("/^[a-zA-Z ]*$/", $name)) {
        $_SESSION['status'] = 'invalid_name';
        header("Location: ../login/signup_view.php");
        exit();
    }

    // Check password length and complexity
    if (strlen($password) < 8) {
        $_SESSION['status'] = 'short_password';
        header("Location: ../login/signup_view.php");
        exit();
    }
    
    if (preg_match('/^(?=.*[a-zA-Z])(?=.*[0-9])/', $password) === 0) {
        $_SESSION['status'] = 'pattern_password';
        header("Location: ../login/signup_view.php");
        exit();
    }

    // Check if user with the same username or email already exists
    $check_user_sql = "SELECT * FROM Users WHERE email='$email'";
    $result = mysqli_query($conn, $check_user_sql);

    if (mysqli_num_rows($result) != 0) {
        $_SESSION['status'] = 'exists';
    } else {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT); // Hashing the password for security
        $reg_sql = "INSERT INTO Users (name, password, email, user_address) VALUES ('$name', '$hashed_password', '$email', '$home_address')";

        if ($conn->query($reg_sql) === TRUE) {
            $_SESSION['status'] = 'success';
            unset($_SESSION['form_data']); // Clear form data on success
        } else {
            $_SESSION['status'] = 'error';
            $_SESSION['error_msg'] = $conn->error;
        }
    }

    header("Location: ../login/signup_view.php");
    exit();
}
?>
