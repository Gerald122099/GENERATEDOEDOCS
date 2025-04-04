<?php
// Initialize the session
session_start();

// Check if the user is already logged in, redirect to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("Location: welcome.php");
    exit;
}

// Include config file
require_once "config.php";

// Initialize variables
$inspector_id = $password = "";
$inspector_id_err = $password_err = "";

// Check for successful registration redirect
if(isset($_SESSION['registration_success']) && $_SESSION['registration_success']) {
    $registration_success = true;
    unset($_SESSION['registration_success']); // Clear the flag
}

// Process form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    
    // Validate inspector ID
    if(empty(trim($_POST["inspector_id"]))){
        $inspector_id_err = "Please enter your Inspector ID.";
    } else {
        $inspector_id = trim($_POST["inspector_id"]);
    }
    
    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }
    
    // Validate credentials
    if(empty($inspector_id_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT id, inspector_id, password, full_name FROM inspectors WHERE inspector_id = ?";
        
        if($stmt = mysqli_prepare($conn, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_inspector_id);
            
            // Set parameters
            $param_inspector_id = $inspector_id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);
                
                // Check if inspector ID exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $inspector_id, $hashed_password, $full_name);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            // Password is correct, so start a new session
                            session_regenerate_id(); // Security enhancement
                            
                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["inspector_id"] = $inspector_id;
                            $_SESSION["full_name"] = $full_name;
                            
                            // Redirect user to welcome page
                            header("Location: welcome.php");
                            exit;
                        } else{
                            // Display an error message if password is not valid
                            $password_err = "The password you entered was not valid.";
                        }
                    }
                } else{
                    // Display an error message if inspector ID doesn't exist
                    $inspector_id_err = "No account found with that Inspector ID.";
                }
            } else{
                $error = "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($conn);
}
?>