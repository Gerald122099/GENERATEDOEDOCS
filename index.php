<?php
require_once "config.php";

if (isset($_SESSION['user_id'])) {
    header("Location: home.php");
    die();
}
else {





// Set default form to show
$show_form = isset($_GET['form']) ? $_GET['form'] : 'login';

// Initialize variables
$user_id = $password = "";
$user_id_err = $password_err = $login_err = "";

// Process login if form submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['user_id'])) {
    // Validate user ID
    if (empty(trim($_POST["user_id"]))) {
        $user_id_err = "Please enter your User ID.";
    } else {
        $user_id = trim($_POST["user_id"]);
    }
    
    // Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }
    
    // Validate credentials
    if (empty($user_id_err) && empty($password_err)) {
        $sql = "SELECT id, user_id, password, full_name, role FROM user WHERE user_id = ?";
        
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $param_user_id);
            $param_user_id = $user_id;
            
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    mysqli_stmt_bind_result($stmt, $id, $user_id, $hashed_password, $full_name, $role);
                    if (mysqli_stmt_fetch($stmt)) {
                        if (password_verify($password, $hashed_password)) {
                            session_regenerate_id();
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["user_id"] = $user_id;
                            $_SESSION["full_name"] = $full_name;
                            $_SESSION["role"] = $role;
                            
                            // Redirect based on role
                            switch($role) {
                                case 'admin':
                                    header("Location: admin_panel.php");
                                    break;
                                case 'legal':
                                    header("Location: home.php");
                                    break;
                                case 'inspector':
                                default:
                                    header("Location: home.php");
                            }
                            exit;
                        } else {
                            $login_err = "Invalid User ID or password.";
                        }
                    }
                } else {
                    $login_err = "Invalid User ID or password.";
                }
            } else {
                $login_err = "Oops! Something went wrong. Please try again later.";
            }
            mysqli_stmt_close($stmt);
        }
    }
    mysqli_close($conn);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COMDIMS | Login</title>
    <link rel="icon" type="image/x-icon" href="..\itr\assets\img\inspectlogo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .card { border-radius: 15px; }
        .min-vh-100 { min-height: 100vh; }
        .toggle-password { cursor: pointer; }

        :root {
            --primary: #2c3e50;
            --secondary: #3498db;
            --accent: #e74c3c;
            --light: #ecf0f1;
            --dark: #1a252f;
        }

        /* Override Bootstrap colors */
        .bg-primary, .btn-primary {
            background-color: var(--primary) !important;
            border-color: var(--primary) !important;
        }

        .text-primary {
            color: var(--primary) !important;
        }

        .btn-outline-primary {
            color: var(--primary);
            border-color: var(--primary);
        }

        .btn-outline-primary:hover {
            background-color: var(--primary);
            color: white;
        }

        /* Update card header colors */
        .card-header {
            background-color: var(--primary);
            color: white;
        }

        /* Update accent colors for alerts and highlights */
        .alert-danger {
            background-color: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
        }

        .alert-success {
            background-color: #d4edda;
            border-color: #c3e6cb;
            color: #155724;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-8 col-lg-6">
                <!-- Login Card -->
                <div class="card shadow-lg <?php echo $show_form == 'register' ? 'd-none' : ''; ?>" id="login-card">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <img src="..\itr\assets\img\inspectlogo.png" alt="Logo" class="mb-3" width="120px">
                            <h2 class="fw-bold text-primary">Compliance Monitoring Digital Management System</h2>
                            <p class="text-muted">Enter your credentials to access your account</p>
                        </div>
                        
                        <?php 
                        if (!empty($login_err)) {
                            echo '<div class="alert alert-danger">' . $login_err . '</div>';
                        }
                        if (!empty($user_id_err)) {
                            echo '<div class="alert alert-danger">' . $user_id_err . '</div>';
                        }
                        if (!empty($password_err)) {
                            echo '<div class="alert alert-danger">' . $password_err . '</div>';
                        }
                        if (isset($_SESSION['registration_success']) && $_SESSION['registration_success']) {
                            echo '<div class="alert alert-success">Registration successful! Please login.</div>';
                            unset($_SESSION['registration_success']);
                        }
                        ?>
                        
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                            <div class="mb-3">
                                <label for="login-id" class="form-label">User ID</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                    <input type="text" class="form-control <?php echo (!empty($user_id_err)) ? 'is-invalid' : ''; ?>" 
                                           id="login-id" name="user_id" value="<?php echo $user_id; ?>">
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="login-password" class="form-label">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" 
                                           id="login-password" name="password">
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
                            <img src="..\itr\assets\img\inspectlogo.png" alt="Logo" class="mb-3" width="120px">
                            <h2 class="fw-bold text-primary">Create Account</h2>
                            <p class="text-muted">Fill in your details to register</p>
                        </div>
                        
                        <form action="register.php" method="post">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="reg-id" class="form-label">User ID</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                        <input type="text" class="form-control" id="reg-id" name="user_id" required>
                                    </div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="reg-fullname" class="form-label">Full Name</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        <input type="text" class="form-control" id="reg-fullname" name="full_name" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="reg-email" class="form-label">Email Address</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    <input type="email" class="form-control" id="reg-email" name="email" required>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="reg-phone" class="form-label">Contact Number</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                    <input type="tel" class="form-control" id="reg-phone" name="contact_number" required>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="reg-password" class="form-label">Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                        <input type="password" class="form-control" id="reg-password" name="password" required>
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
                                        <input type="password" class="form-control" id="reg-confirm-password" name="confirm_password" required>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle password visibility
        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', function() {
                const input = this.parentElement.querySelector('input');
                const icon = this.querySelector('i');
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.replace('fa-eye', 'fa-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.replace('fa-eye-slash', 'fa-eye');
                }
            });
        });
    </script>
</body>
</html>

<?php } ?>