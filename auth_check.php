<?php
session_start();

// Define allowed roles for specific pages (customize per page)
$allowed_roles = ['admin', 'head', 'inspector', 'legal']; // Adjust as needed

// Redirect to login if not logged in
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Verify account is still active (real-time check)
require_once "config.php";
$sql = "SELECT status FROM users WHERE id = ?";
if($stmt = mysqli_prepare($conn, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $_SESSION["id"]);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    
    if(mysqli_stmt_num_rows($stmt) == 1) {
        mysqli_stmt_bind_result($stmt, $status);
        mysqli_stmt_fetch($stmt);
        if($status !== 'active') {
            session_destroy();
            header("location: login.php?error=disabled");
            exit;
        }
    } else {
        // User not found in database
        session_destroy();
        header("location: login.php?error=invalid");
        exit;
    }
    mysqli_stmt_close($stmt);
}
mysqli_close($conn);

// Verify user role has access to this page
if(!in_array($_SESSION["role"], $allowed_roles)) {
    header("location: unauthorized.php");
    exit;
}
?>