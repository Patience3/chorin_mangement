<?php
// Start session
session_start();

// Include the connection file
include '../settings/connection.php';

// Process the delete request if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['jobId'])) {
    $jobId = $_POST['jobId'];

    // Retrieve the cleanerId associated with the job
    $select_sql = "SELECT cleanerId FROM Jobs WHERE jobId = ?";
    $stmt = $conn->prepare($select_sql);
    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }
    $stmt->bind_param("i", $jobId);
    if ($stmt->execute() === false) {
        die("Error executing query: " . $stmt->error);
    }
    $result = $stmt->get_result();
    $job = $result->fetch_assoc();
    $cleanerId = $job['cleanerId'];

    // Close the statement
    $stmt->close();

    // Delete job from Jobs table
    $delete_sql = "DELETE FROM Jobs WHERE jobId = ?";
    $stmt = $conn->prepare($delete_sql);
    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }
    $stmt->bind_param("i", $jobId);
    if ($stmt->execute() === false) {
        die("Error executing query: " . $stmt->error);
    }

    // Close the statement
    $stmt->close();

    // Update the cleaner's availability status to 'available'
    $update_sql = "UPDATE Cleaners SET availabilityStatus = 'Available' WHERE cleanerId = ?";
    $stmt = $conn->prepare($update_sql);
    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }
    $stmt->bind_param("i", $cleanerId);
    if ($stmt->execute() === false) {
        die("Error executing query: " . $stmt->error);
    }

    // Close the statement
    $stmt->close();

    // Set a session variable for success message
    $_SESSION['success_message'] = 'The job has been deleted successfully and the cleaner\'s availability status has been updated.';

    // Redirect back to the dashboard
    header('Location: ../view/User/service.php');
    exit();
}
?>
