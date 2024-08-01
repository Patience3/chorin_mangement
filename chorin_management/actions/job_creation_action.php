<?php
session_start();
include '../settings/connection.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $categoryId = $_POST['categoryId'];
    $job_address = $_POST['job_address'];
    $cleanerId = $_POST['cleanerId'];
    $description = $_POST['description'];
    $status = 'Pending';

    // Insert new job into Jobs table
    $insert_sql = "INSERT INTO Jobs (categoryId, job_address, status, cleanerId, description) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_sql);
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("issis", $categoryId, $job_address, $status, $cleanerId, $description);
    if ($stmt->execute() === false) {
        die("Execute failed: " . $stmt->error);
    }
    $jobId = $stmt->insert_id; // Get the jobId of the inserted job

    // Update cleaner's availability status to 'Unavailable'
    $update_sql = "UPDATE Cleaners SET availabilityStatus = 'Unavailable' WHERE cleanerId = ?";
    $stmt_update = $conn->prepare($update_sql);
    if ($stmt_update === false) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt_update->bind_param("i", $cleanerId);
    if ($stmt_update->execute() === false) {
        die("Execute failed: " . $stmt_update->error);
    }

    // Fetch cleaner's email and name
    $cleanerEmail = '';
    $cleanerName = '';
    $sql = "SELECT name, email FROM Cleaners WHERE cleanerId = ?";
    $stmt_cleaner = $conn->prepare($sql);
    $stmt_cleaner->bind_param("i", $cleanerId);
    if ($stmt_cleaner->execute()) {
        $result = $stmt_cleaner->get_result();
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $cleanerEmail = $row['email'];
            $cleanerName = $row['name'];
        }
    }
    // Insert into UserJobs table to link the user with the job
    $userEmail = $_SESSION['user_email'];
    $select_user_sql = "SELECT userId FROM Users WHERE email = ?";
    $stmt_user = $conn->prepare($select_user_sql);
    if ($stmt_user === false) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt_user->bind_param("s", $userEmail);
    if ($stmt_user->execute() === false) {
        die("Execute failed: " . $stmt_user->error);
    }
    $result_user = $stmt_user->get_result();
    if ($result_user->num_rows == 1) {
        $row = $result_user->fetch_assoc();
        $userId = $row['userId'];

        $insert_userjob_sql = "INSERT INTO UserJobs (userId, jobId) VALUES (?, ?)";
        $stmt_userjob = $conn->prepare($insert_userjob_sql);
        if ($stmt_userjob === false) {
            die("Prepare failed: " . $conn->error);
        }
        $stmt_userjob->bind_param("ii", $userId, $jobId);
        if ($stmt_userjob->execute() === false) {
            die("Execute failed: " . $stmt_userjob->error);
        }

        // Redirect to dashboard or another page after insertion
        header("Location: ../view/User/service.php");
        exit();
    } else {
        die("User not found.");
    }
    $clientName = $_SESSION['name']; // Assuming the client's name is stored in session
    $subject = "Job Assignment Notification";
    $message = "Dear $cleanerName,\n\nYou have been paired with client $clientName at the location $job_address for the job.\n\nJob Description:\n$description\n\nPlease contact the client     for further details.\n\nBest regards,\nChorIn Team";
    $headers = "From: ChorIn <Sombangpatience@gmail.com>";

    if (mail($cleanerEmail, $subject, $message, $headers)) {
        echo "Job created and notification sent successfully.";
    } else {
        echo "Job created but notification email failed to send.";
    }
}
