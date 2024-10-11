<?php
session_start();

// Check if user is already logged in
if (isset($_SESSION['authenticated'])) {
    $_SESSION['status'] = "You are already logged in!";
    header('Location: dashboard-page.php');
    exit();
}

$page_title = "Login";
include('includes/header.php');
?>
<link rel="stylesheet" href="assets/css/login_register_page.css">
<link rel="stylesheet" href="./assets/css/page_transition.css">

<div class="py-5 px-2 vh-100 d-flex flex-column main">
    <div class="container flex-grow-1">
        <div class="row justify-content-center h-100">
            <div class="col-md-6 d-flex flex-column justify-content-between">

                <!-- Alert -->
                <?php
                if (isset($_SESSION['status'])) {
                    $status_class = ($_SESSION['status_type'] ?? 'danger');
                    echo "<div class='alert alert-{$status_class} alert-dismissible fade show' role='alert'>
                            <h5>{$_SESSION['status']}</h5>
                            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                          </div>";
                    unset($_SESSION['status']);
                    unset($_SESSION['status_type']);
                }
                ?>
                
                <!-- Login Form -->
                <h1>
                    <a href="index.php" class="text-decoration-none">
                        <i class="bi bi-arrow-left-circle icon-lg" style="color: black;"></i>
                    </a> 
                </h1>
                <!-- Centered Logo Section -->
                <div class="logo-container">
                    <h1 class="logo-text-upper">
                        IT
                    </h1>
                    <h1 class="logo-text-bottom">
                        PID
                    </h1>
                </div>
                <br>
                <form action="login-process.php" method="POST" class="d-flex flex-column flex-grow-1">
                    <div class="flex-grow-1">
                        <div class="form-group mb-3">
                            <label for="username" class="label-font">Username</label>
                            <input type="text" name="username" id="username" placeholder="Enter Username" class="form-control form-control-lg input-margin" required autocomplete="username">
                        </div>
                        <div class="form-group mb-3">
                            <label for="password" class="label-font">Password</label>
                            <input type="password" name="password" id="password" placeholder="Enter Password" class="form-control form-control-lg" required autocomplete="current-password">
                        </div>
                    </div>
                    <!-- Button at the bottom -->
                    <div class="form-group">
                        <button type="submit" name="login_btn" class="btn btn-custom-primary w-100 mt-auto">Log in</button>
                        <br><br>
                        <a href="forgot_your_password-page.php" class="font-sm text-decoration-none" style="color: #7E60BF;">Forgot your Password?</a>
                    </div>
                </form>
                <p class="mt-1 font-sm" style="color: #433878;">
                    Didn't receive your Verification Email?
                    <a href="resend_email_verification-page.php" class="text-decoration-none" style="color: #7E60BF;">Resend</a>
                </p>
            </div>
        </div>  
    </div>
</div>

<script src="./assets/js/page_transition.js"></script>

<?php include('includes/footer.php'); ?>