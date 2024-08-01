<?php
// Start session
session_start();

// Include the connection file
include '../settings/connection.php';
$userEmail = $_SESSION['user_email'];


if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['jobId'])) {
    $jobId = $_GET['jobId'];

    // Start a transaction
    $conn->begin_transaction();

    try {
        // Update job status to 'Completed'
        $update_sql = "UPDATE Jobs SET status = 'Completed' WHERE jobId = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("i", $jobId);
        $stmt->execute();

        // Get cleanerId for the completed job
        $select_sql = "SELECT cleanerId FROM Jobs WHERE jobId = ?";
        $stmt = $conn->prepare($select_sql);
        $stmt->bind_param("i", $jobId);
        $stmt->execute();
        $stmt->bind_result($cleanerId);
        $stmt->fetch();
        $stmt->close();

        if ($cleanerId !== null) {
            // Update cleaner's availability status to 'available' and increment completed jobs count
            $update_cleaner_sql = "UPDATE Cleaners SET availabilityStatus = 'Available', completedJobsCount = completedJobsCount + 1 WHERE cleanerId = ?";
            $stmt = $conn->prepare($update_cleaner_sql);
            $stmt->bind_param("i", $cleanerId);
            $stmt->execute();
        }

        // Commit transaction
        $conn->commit();

        // Redirect back to dashboard
        header("Location: ../view/User/service.php");
        exit();
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        die($e->getMessage());
    }
}

?>
