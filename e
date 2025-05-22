[33mcommit 28cb4218ed6a1c7ce35a5ae451ae705b777b1354[m[33m ([m[1;36mHEAD[m[33m -> [m[1;32mmain[m[33m, [m[1;31morigin/main[m[33m)[m
Author: Your Name <you@example.com>
Date:   Thu May 22 12:01:56 2025 +0800

    Initial commit

[1mdiff --git a/auth_check.php b/auth_check.php[m
[1mnew file mode 100644[m
[1mindex 0000000..99d7505[m
[1m--- /dev/null[m
[1m+++ b/auth_check.php[m
[36m@@ -0,0 +1,44 @@[m
[32m+[m[32m<?php[m
[32m+[m[32msession_start();[m
[32m+[m
[32m+[m[32m// Define allowed roles for specific pages (customize per page)[m
[32m+[m[32m$allowed_roles = ['admin', 'head', 'inspector', 'legal']; // Adjust as needed[m
[32m+[m
[32m+[m[32m// Redirect to login if not logged in[m
[32m+[m[32mif(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {[m
[32m+[m[32m    header("location: login.php");[m
[32m+[m[32m    exit;[m
[32m+[m[32m}[m
[32m+[m
[32m+[m[32m// Verify account is still active (real-time check)[m
[32m+[m[32mrequire_once "config.php";[m
[32m+[m[32m$sql = "SELECT status FROM users WHERE id = ?";[m
[32m+[m[32mif($stmt = mysqli_prepare($conn, $sql)) {[m
[32m+[m[32m    mysqli_stmt_bind_param($stmt, "i", $_SESSION["id"]);[m
[32m+[m[32m    mysqli_stmt_execute($stmt);[m
[32m+[m[32m    mysqli_stmt_store_result($stmt);[m
[32m+[m[41m    [m
[32m+[m[32m    if(mysqli_stmt_num_rows($stmt) == 1) {[m
[32m+[m[32m        mysqli_stmt_bind_result($stmt, $status);[m
[32m+[m[32m        mysqli_stmt_fetch($stmt);[m
[32m+[m[32m        if($status !== 'active') {[m
[32m+[m[32m            session_destroy();[m
[32m+[m[32m            header("location: login.php?error=disabled");[m
[32m+[m[32m            exit;[m
[32m+[m[32m        }[m
[32m+[m[32m    } else {[m
[32m+[m[32m        // User not found in database[m
[32m+[m[32m        session_destroy();[m
[32m+[m[32m        header("location: login.php?error=invalid");[m
[32m+[m[32m        exit;[m
[32m+[m[32m    }[m
[32m+[m[32m    mysqli_stmt_close($stmt);[m
[32m+[m[32m}[m
[32m+[m[32mmysqli_close($conn);[m
[32m+[m
[32m+[m[32m// Verify user role has access to this page[m
[32m+[m[32mif(!in_array($_SESSION["role"], $allowed_roles)) {[m
[32m+[m[32m    header("location: unauthorized.php");[m
[32m+[m[32m    exit;[m
[32m+[m[32m}[m
[32m+[m[32m?>[m
\ No newline at end of file[m
[1mdiff --git a/config.php b/config.php[m
[1mindex 75123c7..6980cc1 100644[m
[1m--- a/config.php[m
[1m+++ b/config.php[m
[36m@@ -38,10 +38,6 @@[m [mif(!isset($_SESSION['role']) || !in_array($_SESSION['role'], $allowed_roles)) {[m
 }[m
 [m
 [m
[31m-[m
[31m-[m
[31m-  [m
[31m-[m
 $violation_pairs = [[m
     ['coc_cert', 'a.1 Certificate of Compliance (COC)', 'coc_cert_remarks'],[m
     ['coc_posted', 'a.2 COC posted within business premises', 'coc_posted_remarks'],[m
[1mdiff --git a/import_sql_lite.php b/import_sql_lite.php[m
[1mindex b3a5a18..bfec345 100644[m
[1m--- a/import_sql_lite.php[m
[1m+++ b/import_sql_lite.php[m
[36m@@ -2,7 +2,7 @@[m
 require 'config.php';[m
 checkLogin();[m
 allowAccess();[m
[31m-$uploadedFile = "uploads/sqlite_db.sqlite";[m
[32m+[m
 error_reporting(E_ALL);[m
 ini_set('display_errors', 1);[m
 [m
[36m@@ -21,8 +21,13 @@[m [m$duplicateCount = 0;[m
 $updatedRows = [];[m
 $insertedRows = [];[m
 [m
[32m+[m
[32m+[m
 // Handle file upload[m
 if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['sqlite_db'])) {[m
[32m+[m[32m    $uploadedFile = "uploads/" . $_FILES['sqlite_db']['name'];[m
[32m+[m
[32m+[m
     if (!move_uploaded_file($_FILES['sqlite_db']['tmp_name'], $uploadedFile)) {[m
         die("Failed to upload file");[m
     }[m
[36m@@ -37,202 +42,204 @@[m [mif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['sqlite_db'])) {[m
     $stmt->execute();[m
     $stmt->close();[m
     [m
[31m-    $sqlite = new PDO("sqlite:" . $uploadedFile);[m
[31m-    $tables = $sqlite->query("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'android_metadata' AND name NOT LIKE 'sqlite_%' AND name NOT LIKE 'room_%' AND name NOT LIKE 'ITRFormHistory'")->fetchAll(PDO::FETCH_COLUMN);[m
[31m-}[m
[31m-[m
[31m-// Handle data insertion[m
[31m-if (isset($_POST['insert_data'])) {[m
[31m-    $sqlite = new PDO("sqlite:" . $uploadedFile);[m
[31m-    $mysql = new mysqli($servername, $username, $password, $dbname);[m
[31m-    [m
[31m-    if ($mysql->connect_error) {[m
[31m-        die("MySQL Connection failed: " . $mysql->connect_error);[m
[31m-    }[m
[31m-    [m
[31m-    $mysql->set_charset("utf8mb4");[m
[31m-    $mysql->query("SET FOREIGN_KEY_CHECKS=0");[m
[31m-    [m
[31m-    $tables = $sqlite->query("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'android_metadata' AND name NOT LIKE 'sqlite_%' AND name NOT LIKE 'room_%' AND name NOT LIKE 'ITRFormHistory'")->fetchAll(PDO::FETCH_COLUMN);[m
[31m-    [m
[31m-    foreach ($tables as $table) {[m
[31m-        $table = strtolower($table);[m
[31m-        [m
[31m-        if (strtolower($table) === 'itrformhistory') {[m
[31m-            continue;[m
[32m+[m[32m    //$sqlite = new PDO("sqlite:" . $uploadedFile);[m
[32m+[m[32m    //$tables = $sqlite->query("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'android_metadata' AND name NOT LIKE 'sqlite_%' AND name NOT LIKE 'room_%' AND name NOT LIKE 'ITRFormHistory'")->fetchAll(PDO::FETCH_COLUMN);[m
[32m+[m[32m    print_r ($_FILES);[m
[32m+[m[32m    die();[m
[32m+[m[32m    // Handle data insertion[m
[32m+[m[32m    if (isset($_POST['insert_data'])) {[m
[32m+[m[41m        [m
[32m+[m[32m        $sqlite = new PDO("sqlite:" . $uploadedFile);[m
[32m+[m[32m        $mysql = new mysqli($servername, $username, $password, $dbname);[m
[32m+[m[41m        [m
[32m+[m[32m        if ($mysql->connect_error) {[m
[32m+[m[32m            die("MySQL Connection failed: " . $mysql->connect_error);[m
         }[m
         [m
[31m-        $columns = $sqlite->query("PRAGMA table_info(`$table`)")->fetchAll(PDO::FETCH_ASSOC);[m
[31m-        $columnNames = array_column($columns, 'name');[m
[31m-        [m
[31m-        $primaryKey = 'id';[m
[31m-        foreach ($columns as $col) {[m
[31m-            if ($col['pk'] == 1) {[m
[31m-                $primaryKey = $col['name'];[m
[31m-                break;[m
[31m-            }[m
[31m-        }[m
[31m-        [m
[31m-        $hasId = in_array('id', $columnNames);[m
[31m-        $hasItrFormNum = in_array('itr_form_num', $columnNames);[m
[31m-        [m
[31m-        $uniqueIdentifier = ($hasId && $hasItrFormNum) ? ['id', 'itr_form_num'] : [m
[31m-                          ($hasId ? ['id'] : [m
[31m-                          ($hasItrFormNum ? ['itr_form_num'] : [$primaryKey]));[m
[32m+[m[32m        $mysql->set_charset("utf8mb4");[m
[32m+[m[32m        $mysql->query("SET FOREIGN_KEY_CHECKS=0");[m
         [m
[31m-        if (!$mysql->query("SELECT 1 FROM `$table` LIMIT 1")) {[m
[31m-            $importErrors[] = "Table $table doesn't exist in MySQL - skipped";[m
[31m-            continue;[m
[31m-        }[m
[31m-        [m
[31m-        $rows = $sqlite->query("SELECT * FROM `$table`")->fetchAll(PDO::FETCH_ASSOC);[m
[32m+[m[32m        $tables = $sqlite->query("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'android_metadata' AND name NOT LIKE 'sqlite_%' AND name NOT LIKE 'room_%' AND name NOT LIKE 'ITRFormHistory'")->fetchAll(PDO::FETCH_COLUMN);[m
         [m
[31m-        foreach ($rows as $row) {[m
[31m-            $timestampFields = ['date_time', 'sa_date', 'createdAt', 'updatedAt', 'date_deliver'];[m
[31m-            foreach ($timestampFields as $field) {[m
[31m-                if (isset($row[$field])) {[m
[31m-                    if (is_numeric($row[$field])) {[m
[31m-                        $row[$field] = date('Y-m-d H:i:s', $row[$field] / 1000);[m
[31m-                    } elseif (strtotime($row[$field])) {[m
[31m-                 