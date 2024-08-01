<?php
// Start the session
session_start();

// Include the connection file
include("../../settings/connection.php");

// Check if the user is an admin
if (!isset($_SESSION['email'])) {
    header("Location: ../../login/logout_view.php");
    exit();
}

// Fetch jobs
$sql = "SELECT j.jobId, j.job_address, j.status, j.description, u.name AS userName, c.name AS cleanerName 
        FROM Jobs j
        LEFT JOIN UserJobs uj ON j.jobId = uj.jobId
        LEFT JOIN Users u ON uj.userId = u.userId
        LEFT JOIN Cleaners c ON j.cleanerId = c.cleanerId";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Jobs</title>
    <link rel="stylesheet" href="../../css/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="container">
        <h1>Manage Jobs</h1>
        <table>
            <thead>
                <tr>
                    <th>Job ID</th>
                    <th>Address</th>
                    <th>Status</th>
                    <th>Description</th>
                    <th>User</th>
                    <th>Cleaner</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['jobId']; ?></td>
                        <td><?php echo $row['job_address']; ?></td>
                        <td><?php echo $row['status']; ?></td>
                        <td><?php echo $row['description']; ?></td>
                        <td><?php echo $row['userName']; ?></td>
                        <td><?php echo $row['cleanerName']; ?></td>
                        <td>
                            <a href="delete_job.php?jobId=<?php echo $row['jobId']; ?>" onclick="return confirmDelete()">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <script>
        function confirmDelete() {
            return Swal.fire({
                title: 'Are you sure?',
                text: "This action cannot be undone.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                return result.isConfirmed;
            });
        }
    </script>
</body>
</html>
