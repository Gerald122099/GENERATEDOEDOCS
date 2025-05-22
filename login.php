<?php

require_once "config.php";

// Initialize variables
$user_id = $password = "";
$user_id_err = $password_err = $login_err = "";

// Process form data when submitted
if($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Validate user ID
    if(empty(trim($_POST["user_id"]))) {
        $user_id_err = "Please enter your User ID.";
    } else {
        $user_id = trim($_POST["user_id"]);
    }
    
    // Validate password
    if(empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }
    
    // Validate credentials
    if(empty($user_id_err) && empty($password_err)) {
        // Prepare SQL with status and role check
        $sql = "SELECT id, user_id, password, full_name, role, status FROM user WHERE user_id = ?";
        
        if($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $param_user_id);
            $param_user_id = $user_id;
            
            if(mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1) {
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $user_id, $hashed_password, $full_name, $role, $status);
                    if(mysqli_stmt_fetch($stmt)) {
                        if(password_verify($password, $hashed_password)) {
                            // Check account status
                            if($status == 'disabled') {
                                $login_err = "Your account is disabled. Please contact administrator.";
                            } else {
                                // Account is active, create session
                                session_regenerate_id();
                                
                                // Store session variables
                                $_SESSION["loggedin"] = true;
                                $_SESSION["id"] = $id;
                                $_SESSION["user_id"] = $user_id;
                                $_SESSION["full_name"] = $full_name;
                                $_SESSION["role"] = $role;
                                $_SESSION["status"] = $status;
                                
                                // Redirect based on role (optional)
                                header("Location: home.php");
                                exit;
                            }
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
    <title>Login</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .wrapper { width: 360px; padding: 20px; margin: 100px auto; }
        .form-group { margin-bottom: 15px; }
        .help-block { color: red; font-size: 12px; }
        input[type="text"], input[type="password"] {
            width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;
        }
        .btn { 
            background: #007bff; color: white; border: none; 
            padding: 10px 15px; border-radius: 4px; cursor: pointer;
        }
        .alert { 
            padding: 10px; margin-bottom: 15px; border-radius: 4px; 
            color: #721c24; background-color: #f8d7da; border: 1px solid #f5c6cb;
        }
        .alert-success {
            color: #155724; background-color: #d4edda; border: 1px solid #c3e6cb;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Login</h2>
        <p>Please fill in your credentials to login.</p>
        
        <?php 
        if(!empty($login_err)) {
            echo '<div class="alert">' . $login_err . '</div>';
        }
        ?>
        
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>User ID</label>
                <input type="text" name="user_id" class="form-control <?php echo (!empty($user_id_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $user_id; ?>">
                <span class="help-block"><?php echo $user_id_err; ?></span>
            </div>    
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn" value="Login">
            </div>
            <p>Don't have an account? <a href="register.php">Sign up now</a>.</p>
        </form>
    </div>    
</body>
</html>