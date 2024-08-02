<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session
session_start();

// Include the connection file
include '../settings/connection.php';

$response = array('status' => 'error', 'message' => 'Unknown error occurred');

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
        $response['status'] = 'mismatch';
        $response['message'] = 'Passwords do not match';
    } elseif (!preg_match("/^[a-zA-Z ]*$/", $name)) {
        $response['status'] = 'invalid_name';
        $response['message'] = 'Name can only contain alphabets';
    } elseif (strlen($password) < 8) {
        $response['status'] = 'short_password';
        $response['message'] = 'Password must be at least 8 characters long';
    } elseif (preg_match('/^(?=.*[a-zA-Z])(?=.*[0-9])/', $password) === 0) {
        $response['status'] = 'pattern_password';
        $response['message'] = 'Password must include both letters and numbers';
    } else {
        // Check if user with the same username or email already exists
        $check_user_sql = "SELECT * FROM Users WHERE email='$email'";
        $result = mysqli_query($conn, $check_user_sql);

        if (mysqli_num_rows($result) != 0) {
            $response['status'] = 'exists';
            $response['message'] = 'User with the same email already exists';
        } else {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT); // Hashing the password for security
            $reg_sql = "INSERT INTO Users (name, password, email, user_address) VALUES ('$name', '$hashed_password', '$email', '$home_address')";

            if ($conn->query($reg_sql) === TRUE) {
                $response['status'] = 'success';
                $response['message'] = 'Your account has been successfully created!';
                unset($_SESSION['form_data']); // Clear form data on success
            } else {
                $response['status'] = 'error';
                $response['message'] = $conn->error;
            }
        }
    }

    // Ensure no additional output before this
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}
?>
