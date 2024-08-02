<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ChorIn - Sign Up</title>
<link rel="stylesheet" href="../css/styles.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div class="container">
    <h2>Sign Up</h2>
    <form id="signupForm" action="../actions/signup_user_action.php" method="POST">
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
$(document).ready(function() {
    $('#email').on('blur', function() {
        var email = $(this).val();
        if (email.length > 0) {
            $.ajax({
                url: '../actions/signup_user_action.php',
                type: 'POST',
                data: { email: email },
                dataType: 'json',
                success: function(response) {
                    if (response.status == 'exists') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Registration Failed',
                            text: response.message,
                            showConfirmButton: true
                        });
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('AJAX error:', textStatus, errorThrown);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An unexpected error occurred during email validation.',
                        showConfirmButton: true
                    });
                }
            });
        }
    });

    $('#signupForm').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        $.ajax({
            url: form.attr('action'),
            type: form.attr('method'),
            data: form.serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.status == 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Account Created',
                        text: 'Your account has been successfully created!',
                        showConfirmButton: true
                    }).then(() => {
                        window.location.href = '../login/login_view.php';
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Registration Failed',
                        text: response.message,
                        showConfirmButton: true
                    });
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('AJAX error:', textStatus, errorThrown);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An unexpected error occurred during form submission.',
                    showConfirmButton: true
                });
            }
        });
    });
});
</script>
</body>
</html>
