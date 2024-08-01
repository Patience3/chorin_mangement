<?php
// session_start();
// include("../../settings/connection.php");

// if (!isset($_SESSION['email'])) {
//     header("Location: ../../login/logout_view.php");
//     exit();
// }

// Query to get the number of job categories
$categoryQuery = "SELECT COUNT(*) as count FROM JobCategories";
$categoryResult = $conn->query($categoryQuery);
$categoryCount = $categoryResult->fetch_assoc()['count'];

// Query to get the number of cleaners
$cleanerQuery = "SELECT COUNT(*) as count FROM Cleaners";
$cleanerResult = $conn->query($cleanerQuery);
$cleanerCount = $cleanerResult->fetch_assoc()['count'];

// Query to get the number of completed jobs
$completedJobsQuery = "SELECT COUNT(*) as count FROM Jobs WHERE status = 'Completed'";
$completedJobsResult = $conn->query($completedJobsQuery);
$completedJobsCount = $completedJobsResult->fetch_assoc()['count'];

// Query to get the number of pending jobs
$pendingJobsQuery = "SELECT COUNT(*) as count FROM Jobs WHERE status = 'Pending'";
$pendingJobsResult = $conn->query($pendingJobsQuery);
$pendingJobsCount = $pendingJobsResult->fetch_assoc()['count'];

// Close the database connection
$conn->close();
?>
