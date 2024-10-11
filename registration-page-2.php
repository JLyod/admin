<?php
// Secure cookie settings
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 1);

session_start();

// Regenerate session ID periodically
if (!isset($_SESSION['last_regeneration'])) {
    session_regenerate_id(true);
    $_SESSION['last_regeneration'] = time();
} elseif (time() - $_SESSION['last_regeneration'] > 300) {
    session_regenerate_id(true);
    $_SESSION['last_regeneration'] = time();
}
$page_title = "Join IT-PID · IT-PID";
include('includes/header.php');

// Check if the user came from step 1
if (!isset($_POST['f_name']) || !isset($_POST['l_name']) || !isset($_POST['email'])) {
    header("Location: registration-page-1.php");
    exit();
}

// Sanitize input
$f_name = filter_input(INPUT_POST, 'f_name');
$l_name = filter_input(INPUT_POST, 'l_name');
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
?>

<link rel="stylesheet" href="./assets/css/login_register_page.css">
<link rel="stylesheet" href="./assets/css/page_transition.css">

<div class="py-5 px-2 vh-100 d-flex flex-column main" style="color: #433878;"> <!-- Set text color for the entire container -->
    <div class="container flex-grow-1">
        <div class="row justify-content-center h-100">
            <div class="col-md-6 d-flex flex-column justify-content-between">

                <!-- Alert -->
                <?php
                if (isset($_SESSION['status'])) {
                    echo '<div class="alert alert-primary"><h4>' . htmlspecialchars($_SESSION['status']) . '</h4></div>';
                    unset($_SESSION['status']);
                }
                ?>

                <!-- Registration Form -->
                <h1>
                    <a href="registration-page-1.php" class="text-decoration-none">
                        <i class="bi bi-arrow-left-circle icon-lg" style= "color:black;"></i>
                    </a> 
                </h1>
                <br>
                <form action="registration-process.php" method="POST" class="d-flex flex-column flex-grow-1">
                    <input type="hidden" name="f_name" value="<?= htmlspecialchars($f_name) ?>">
                    <input type="hidden" name="l_name" value="<?= htmlspecialchars($l_name) ?>">
                    <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>">
                    <br>
                <br>
                <br>
                    <div class="flex-grow-1">
                        <h1 style="color: black; font-size: 2.5rem;">Register</h1>
                        <br>
                        <div class="form-group mb-3">
                            <label for="username" class="label-font">Username</label>
                            <input type="text" name="username" id="username" placeholder="Enter Username" class="form-control form-control-lg input-margin" required autocomplete="on">
                        </div>
                        <div class="form-group mb-3">
                            <label for="password" class="label-font">Password</label>
                            <input type="password" name="password" id="password" placeholder="Enter Password" class="form-control form-control-lg input-margin" required onkeyup="checkPasswordStrength()">
                            <meter max="5" id="password-strength-meter"></meter>
                            <p id="password-strength-message"></p>
                        </div>
                        <div class="form-group mb-3">
                            <label for="c_password" class="label-font">Confirm Password</label>
                            <input type="password" name="c_password" id="c_password" placeholder="Enter Confirm Password" class="form-control form-control-lg" required>
                        </div>
                    </div>
                    <!-- Button at the bottom -->
                    <div class="form-group">
                        <button type="submit" name="register_btn" class="btn btn-custom-primary w-100 mt-auto register-btn">Register</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="./assets/js/page_transition.js"></script>
<script>
    function checkPasswordStrength() {
        var password = document.getElementById('password').value;
        var strength = 0;
        var messages = [];

        // Length check
        if (password.length < 12) {
            messages.push('Password should be at least 12 characters long');
        } else {
            strength += 1;
        }

        // Uppercase check
        if (password.match(/[A-Z]/)) {
            strength += 1;
        } else {
            messages.push('Add uppercase letters');
        }

        // Lowercase check
        if (password.match(/[a-z]/)) {
            strength += 1;
        } else {
            messages.push('Add lowercase letters');
        }

        // Number check
        if (password.match(/\d/)) {
            strength += 1;
        } else {
            messages.push('Add numbers');
        }

        // Special character check
        if (password.match(/[^A-Za-z0-9]/)) {
            strength += 1;
        } else {
            messages.push('Add special characters');
        }

        // Update the strength meter
        var strengthMeter = document.getElementById('password-strength-meter');
        var messageElement = document.getElementById('password-strength-message');

        strengthMeter.value = strength;

        if (strength < 2) {
            messageElement.textContent = 'Weak password';
            messageElement.style.color = 'red';
        } else if (strength < 4) {
            messageElement.textContent = 'Medium strength password';
            messageElement.style.color = 'yellow';
        } else {
            messageElement.textContent = 'Strong password';
            messageElement.style.color = 'green';
        }

        if (messages.length > 0) {
            messageElement.textContent += ': ' + messages.join(', ');
        }
    }
</script>

<?php include('includes/footer.php'); ?>