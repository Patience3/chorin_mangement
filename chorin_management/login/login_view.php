<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ChorIn - Sign In</title>
    <link rel="stylesheet" href="../css/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="container">
        <h2>Sign In</h2>
        <form action="../actions/login_user_action.php" method="POST">
            <label for="email">Email:</label>
            <input type="text" id="email" name="email" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit">Sign In</button>
        </form>
        <p>Don't have an account? <a href="signup_view.php">Sign up</a></p>
    </div>

    <script>
    <?php
    // Check for session status and display appropriate alert
    if (isset($_SESSION['login_status'])) {
        if ($_SESSION['login_status'] == 'failed') {
            echo "Swal.fire({
                    icon: 'error',
                    title: 'Login Failed',
                    text: 'Incorrect email or password!',
                    showConfirmButton: true
                  });";
        } elseif ($_SESSION['login_status'] == 'not_registered') {
            echo "Swal.fire({
                    icon: 'warning',
                    title: 'No Account Found',
                    text: 'This email is not registered. Please sign up!',
                    showConfirmButton: true
                  });";
        } elseif ($_SESSION['login_status'] == 'success') {
            echo "Swal.fire({
                    icon: 'success',
                    title: 'Welcome Back!',
                    text: 'You have successfully logged in.',
                    showConfirmButton: true
                  }).then(() => {
                    window.location.href = '../view/User/service.php'; // Redirect after successful login
                  });";
        }
        // Unset the session status after displaying
        unset($_SESSION['login_status']);
    }
    ?>
    </script>
</body>
</html>
