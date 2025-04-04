<?php
// Set default form to show
$show_form = isset($_GET['form']) ? $_GET['form'] : 'login';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inspector Portal</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Custom CSS -->
    <link href="assets/css/custom.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-8 col-lg-6">
                <!-- Login Card -->
                <div class="card shadow-lg <?php echo $show_form == 'register' ? 'd-none' : ''; ?>" id="login-card">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <img src="doe.jpg" alt="Logo" class="mb-3" width="80">
                            <h2 class="fw-bold text-primary">Inspector Login</h2>
                            <p class="text-muted">Enter your credentials to access your account</p>
                        </div>
                        
                        <form action="login.php" method="post">
                            <div class="mb-3">
                                <label for="login-id" class="form-label">Inspector ID</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                    <input type="text" class="form-control" id="login-id" name="inspector_id" placeholder="Enter your ID">
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="login-password" class="form-label">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" class="form-control" id="login-password" name="password" placeholder="Enter password">
                                    <button class="btn btn-outline-secondary toggle-password" type="button">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="remember-me">
                                    <label class="form-check-label" for="remember-me">Remember me</label>
                                </div>
                                <a href="#" class="text-decoration-none">Forgot password?</a>
                            </div>
                            
                            <button type="submit" class="btn btn-primary w-100 py-2 mb-3">Login</button>
                            
                            <div class="text-center">
                                <p class="text-muted">Don't have an account? <a href="?form=register" class="text-decoration-none">Register here</a></p>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Register Card -->
                <div class="card shadow-lg <?php echo $show_form == 'register' ? '' : 'd-none'; ?>" id="register-card">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <img src="https://via.placeholder.com/100" alt="Logo" class="mb-3" width="80">
                            <h2 class="fw-bold text-primary">Create Account</h2>
                            <p class="text-muted">Fill in your details to register</p>
                        </div>
                        
                        <form action="register.php" method="post">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="reg-id" class="form-label">Inspector ID</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                        <input type="text" class="form-control" id="reg-id" name="inspector_id" placeholder="Create your ID">
                                    </div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="reg-fullname" class="form-label">Full Name</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        <input type="text" class="form-control" id="reg-fullname" name="full_name" placeholder="Your full name">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="reg-email" class="form-label">Email Address</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    <input type="email" class="form-control" id="reg-email" name="email" placeholder="your@email.com">
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="reg-phone" class="form-label">Contact Number</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                    <input type="tel" class="form-control" id="reg-phone" name="contact_number" placeholder="+1234567890">
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="reg-password" class="form-label">Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                        <input type="password" class="form-control" id="reg-password" name="password" placeholder="Create password">
                                        <button class="btn btn-outline-secondary toggle-password" type="button">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <div class="form-text">Minimum 6 characters</div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="reg-confirm-password" class="form-label">Confirm Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                        <input type="password" class="form-control" id="reg-confirm-password" name="confirm_password" placeholder="Confirm password">
                                        <button class="btn btn-outline-secondary toggle-password" type="button">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-check mb-4">
                                <input class="form-check-input" type="checkbox" id="agree-terms" required>
                                <label class="form-check-label" for="agree-terms">I agree to the <a href="#" class="text-decoration-none">Terms and Conditions</a></label>
                            </div>
                            
                            <button type="submit" class="btn btn-primary w-100 py-2 mb-3">Register</button>
                            
                            <div class="text-center">
                                <p class="text-muted">Already have an account? <a href="?form=login" class="text-decoration-none">Login here</a></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="assets/js/main.js"></script>
</body>
</html>