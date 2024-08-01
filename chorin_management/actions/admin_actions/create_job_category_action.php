<?php
// Include database configuration file

session_start();
include("../settings/connection.php"); // Update the path as needed


// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $categoryName = $_POST['categoryName'];
    $description = $_POST['description'];

    // Validate form data
    if (!empty($categoryName) && !empty($description)) {
     
        // Prepare the SQL statement
        $stmt = $conn->prepare("INSERT INTO JobCategories (categoryName, description) VALUES (?, ?)");
        $stmt->bind_param("ss", $categoryName, $description);

        // Execute the statement
        if ($stmt->execute()) {
            $message = "New category created successfully.";
        } else {
            $message = "Error: " . $stmt->error;
        }

        // Close the statement and connection
        $stmt->close();
        $conn->close();
    } else {
        $message = "Please fill in all required fields.";
    }
} else {
    $message = "Invalid request.";
}

// Redirect back to the form with a message
header("Location: ../../view/admin/manage_categories.php?message=" . urlencode($message));
exit;
?>
