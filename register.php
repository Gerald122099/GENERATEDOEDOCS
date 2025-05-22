<?php
// Start the session


// Include config file
require_once "config.php";

// Initialize variables
$user_id = $password = $confirm_password = $full_name = $email = $contact_number = "";
$role = 'inspector'; // Default role
$errors = [];

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate User ID (previously Inspector ID)
    if(empty(trim($_POST["user_id"]))) {
        $errors['user_id'] = "Please enter a User ID.";
    } elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["user_id"]))) {
        $errors['user_id'] = "ID can only contain letters, numbers, and underscores.";
    } else {
        // Prepare a select statement
        $sql = "SELECT id FROM user WHERE user_id = ?";
        
        if($stmt = mysqli_prepare($conn, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_user_id);
            
            // Set parameters
            $param_user_id = trim($_POST["user_id"]);
            if(strlen($password) < 8) {
    $error = "Password must be at least 8 characters";
}
if(!preg_match("/[A-Z]/", $password) || !preg_match("/[0-9]/", $password)) {
    $error = "Password must contain uppercase and numbers";
}
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)) {
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1) {
                    $errors['user_id'] = "This User ID is already taken.";
                } else {
                    $user_id = trim($_POST["user_id"]);
                }
            } else {
                $errors['database'] = "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Validate password
    if(empty(trim($_POST["password"]))) {
        $errors['password'] = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 6) {
        $errors['password'] = "Password must have at least 6 characters.";
    } else {
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))) {
        $errors['confirm_password'] = "Please confirm password.";     
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($errors['password']) && ($password != $confirm_password)) {
            $errors['confirm_password'] = "Password did not match.";
        }
    }
    
    // Validate full name
    if(empty(trim($_POST["full_name"]))) {
        $errors['full_name'] = "Please enter your full name.";     
    } else {
        $full_name = trim($_POST["full_name"]);
    }
    
    // Validate email
    if(empty(trim($_POST["email"]))) {
        $errors['email'] = "Please enter your email.";     
    } elseif(!filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Please enter a valid email address.";
    } else {
        // Check if email exists
        $sql = "SELECT id FROM user WHERE email = ?";
        
        if($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $param_email);
            $param_email = trim($_POST["email"]);
            
            if(mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1) {
                    $errors['email'] = "This email is already registered.";
                } else {
                    $email = trim($_POST["email"]);
                }
            } else {
                $errors['database'] = "Oops! Something went wrong. Please try again later.";
            }

            mysqli_stmt_close($stmt);
        }
    }
    
    // Validate contact number
    if(empty(trim($_POST["contact_number"]))) {
        $errors['contact_number'] = "Please enter your contact number.";     
    } else {
        $contact_number = trim($_POST["contact_number"]);
    }
    
    // Check input errors before inserting in database
    if(empty($errors)) {
        
        // Prepare an insert statement
        $sql = "INSERT INTO user (user_id, password, full_name, email, contact_number, role) VALUES (?, ?, ?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($conn, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssssss", $param_user_id, $param_password, $param_full_name, $param_email, $param_contact_number, $param_role);
            
            // Set parameters
            $param_user_id = $user_id;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            $param_full_name = $full_name;
            $param_email = $email;
            $param_contact_number = $contact_number;
            $param_role = $role; // Default role is 'inspector'
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)) {
                // Set session variable for success message
                $_SESSION['registration_success'] = true;
                
                // Redirect to login page
                header("location: index.php");
                exit();
            } else {
                $errors['database'] = "Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #f8f9fc 0%, #d1d9f0 100%);
            min-height: 100vh;
        }
        .card {
            border-radius: 1rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
        }
        .form-control:focus {
            border-color: #4e73df;
            box-shadow: 0 0 0 0.25rem rgba(78, 115, 223, 0.25);
        }
        .btn-primary {
            background-color: #4e73df;
            border-color: #4e73df;
        }
        .btn-primary:hover {
            background-color: #2e59d9;
            border-color: #2e59d9;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <img src="https://via.placeholder.com/100" alt="Logo" class="mb-3" width="80">
                            <h2 class="fw-bold text-primary">Create Account</h2>
                            <p class="text-muted">Fill in your details to register</p>
                        </div>

                        <?php if(!empty($errors['database'])): ?>
                            <div class="alert alert-danger"><?php echo $errors['database']; ?></div>
                        <?php endif; ?>

                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="reg-id" class="form-label">User ID</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                        <input type="text" class="form-control <?php echo (!empty($errors['user_id'])) ? 'is-invalid' : ''; ?>" 
                                               id="reg-id" name="user_id" value="<?php echo $user_id; ?>" placeholder="Create your ID">
                                    </div>
                                    <?php if(!empty($errors['user_id'])): ?>
                                        <div class="invalid-feedback d-block"><?php echo $errors['user_id']; ?></div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="reg-fullname" class="form-label">Full Name</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        <input type="text" class="form-control <?php echo (!empty($errors['full_name'])) ? 'is-invalid' : ''; ?>" 
                                               id="reg-fullname" name="full_name" value="<?php echo $full_name; ?>" placeholder="Your full name">
                                    </div>
                                    <?php if(!empty($errors['full_name'])): ?>
                                        <div class="invalid-feedback d-block"><?php echo $errors['full_name']; ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="reg-email" class="form-label">Email Address</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    <input type="email" class="form-control <?php echo (!empty($errors['email'])) ? 'is-invalid' : ''; ?>" 
                                           id="reg-email" name="email" value="<?php echo $email; ?>" placeholder="your@email.com">
                                </div>
                                <?php if(!empty($errors['email'])): ?>
                                    <div class="invalid-feedback d-block"><?php echo $errors['email']; ?></div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="mb-3">
                                <label for="reg-phone" class="form-label">Contact Number</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                    <input type="tel" class="form-control <?php echo (!empty($errors['contact_number'])) ? 'is-invalid' : ''; ?>" 
                                           id="reg-phone" name="contact_number" value="<?php echo $contact_number; ?>" placeholder="+1234567890">
                                </div>
                                <?php if(!empty($errors['contact_number'])): ?>
                                    <div class="invalid-feedback d-block"><?php echo $errors['contact_number']; ?></div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="reg-password" class="form-label">Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                        <input type="password" class="form-control <?php echo (!empty($errors['password'])) ? 'is-invalid' : ''; ?>" 
                                               id="reg-password" name="password" placeholder="Create password">
                                        <button class="btn btn-outline-secondary toggle-password" type="button">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <div class="form-text">Minimum 6 characters</div>
                                    <?php if(!empty($errors['password'])): ?>
                                        <div class="invalid-feedback d-block"><?php echo $errors['password']; ?></div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="reg-confirm-password" class="form-label">Confirm Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                        <input type="password" class="form-control <?php echo (!empty($errors['confirm_password'])) ? 'is-invalid' : ''; ?>" 
                                               id="reg-confirm-password" name="confirm_password" placeholder="Confirm password">
                                        <button class="btn btn-outline-secondary toggle-password" type="button">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <?php if(!empty($errors['confirm_password'])): ?>
                                        <div class="invalid-feedback d-block"><?php echo $errors['confirm_password']; ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="form-check mb-4">
                                <input class="form-check-input <?php echo (!empty($errors['terms'])) ? 'is-invalid' : ''; ?>" 
                                       type="checkbox" id="agree-terms" name="terms" required>
                                <label class="form-check-label" for="agree-terms">I agree to the <a href="#" class="text-decoration-none">Terms and Conditions</a></label>
                            </div>
                            
                            <button type="submit" class="btn btn-primary w-100 py-2 mb-3">Register</button>
                            
                            <div class="text-center">
                                <p class="text-muted">Already have an account? <a href="login.php" class="text-decoration-none">Login here</a></p>
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
    <script>
        // Toggle password visibility
        document.querySelectorAll('.toggle-password').forEach(function(button) {
            button.addEventListener('click', function() {
                const input = this.parentElement.querySelector('input');
                const icon = this.querySelector('i');
                
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        });
    </script>
</body>
</html>