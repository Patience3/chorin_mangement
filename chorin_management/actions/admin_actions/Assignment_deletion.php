<?php
// Handle assignment deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_assignment'])) {
    $assignment_id = $_POST['assignment_id'];

    $sql = "DELETE FROM assignments WHERE assignment_id='$assignment_id'";
    $conn->query($sql);

    header("Location: admin_dashboard_view.php");
    exit();
}