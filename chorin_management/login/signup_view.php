<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ChorIn - Sign Up</title>
<link rel="stylesheet" href="../css/styles.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
</head>
<body>
<div class="container">
    <h2>Sign Up</h2>
    <form action="../actions/signup_user_action.php" method="POST">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?php echo isset($_SESSION['form_data']['name']) ? $_SESSION['form_data']['name'] : ''; ?>" required>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo isset($_SESSION['form_data']['email']) ? $_SESSION['form_data']['email'] : ''; ?>" required>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <label for="confirm_password">Confirm Password:</label>
        <input type="password" id="confirm_password" name="confirm_password" required>
        <label for="user_address">Address:</label>
        <input type="text" id="user_address" name="user_address" value="<?php echo isset($_SESSION['form_data']['user_address']) ? $_SESSION['form_data']['user_address'] : ''; ?>" required>
        <button type="submit" name="signup">Sign Up</button>
    </form>
    <p>Already have an account? <a href="login_view.php">Sign in</a></p>
</div>

<script>
<?php
if (isset($_SESSION['status'])) {
    if ($_SESSION['status'] == 'exists') {
        echo "Swal.fire({
                icon: 'error',
                title: 'Registration Failed',
                text: 'User with the same email already exists',
                showConfirmButton: true
              });";
    } elseif ($_SESSION['status'] == 'mismatch') {
        echo "Swal.fire({
                icon: 'error',
                title: 'Registration Failed',
                text: 'Passwords do not match',
                showConfirmButton: true
              });";
    } elseif ($_SESSION['status'] == 'invalid_name') {
        echo "Swal.fire({
                icon: 'error',
                title: 'Registration Failed',
                text: 'Name can only contain alphabets',
                showConfirmButton: true
              });";
    } elseif ($_SESSION['status'] == 'short_password') {
        echo "Swal.fire({
                icon: 'error',
                title: 'Registration Failed',
                text: 'Password must be at least 8 characters long',
                showConfirmButton: true
              });";
    } elseif ($_SESSION['status'] == 'pattern_password') {
        echo "Swal.fire({
                icon: 'error',
                title: 'Registration Failed',
                text: 'Password must include both letters and numbers',
                showConfirmButton: true
              });";
    } elseif ($_SESSION['status'] == 'success') {
        echo "Swal.fire({
                icon: 'success',
                title: 'Account Created',
                text: 'Your account has been successfully created!',
                showConfirmButton: true
              }).then(() => {
                window.location.href = '../login/login_view.php';
              });";
    } elseif ($_SESSION['status'] == 'error') {
        $errorMsg = $_SESSION['error_msg'];
        echo "Swal.fire({
                icon: 'error',
                title: 'Registration Failed',
                text: 'Error: $errorMsg',
                showConfirmButton: true
              });";
    }
    unset($_SESSION['status']);
    unset($_SESSION['error_msg']);
    unset($_SESSION['form_data']); // Clear form data after displaying the message
}
?>
</script>
</body>
</html>
