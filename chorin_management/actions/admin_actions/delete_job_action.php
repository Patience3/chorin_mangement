<?php
session_start();
include("../../settings/connection.php");

if (!isset($_SESSION['email'])) {
    header("Location: ../../login/logout_view.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['deleteCleanerId'])) {
    $cleanerId = $_POST['deleteCleanerId'];

    $stmt = $conn->prepare("DELETE FROM Cleaners WHERE cleanerId = ?");
    $stmt->bind_param("i", $cleanerId);

    if ($stmt->execute()) {
        $stmt->close();
        header("Location: ../../view/admin/manage_cleaners.php");
        exit();
    } else {
        $stmt->close();
        header("Location: ../../view/admin/manage_cleaners.php?error=deletion_failed");
        exit();
    }
} else {
    header("Location: ../../view/admin/manage_cleaners.php");
    exit();
}
?>
