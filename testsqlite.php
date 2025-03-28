<?php
require 'config.php';
$uploadedFile = "uploads/sqlite_db.sqlite";
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!is_dir("uploads")) {
    if (!mkdir("uploads", 0777, true)) {
        die("Failed to create upload directory");
    }
}

// Initialize variables
$tables = [];
$sqlite = null;
$importErrors = []; // Array to store import errors
$success = false; // Flag to track successful import
$duplicateCount = 0; // Counter for duplicate entries

// Handle file upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['sqlite_db'])) {
    if (!move_uploaded_file($_FILES['sqlite_db']['tmp_name'], $uploadedFile)) {
        die("Failed to upload file");
    }
    
    $sqlite = new PDO("sqlite:" . $uploadedFile);
    $tables = $sqlite->query("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'android_metadata' AND name NOT LIKE 'sqlite_%' AND name NOT LIKE 'room_%' AND name NOT LIKE 'ITRFormHistory'")->fetchAll(PDO::FETCH_COLUMN);
}

// Handle data insertion
if (isset($_POST['insert_data'])) {
    $sqlite = new PDO("sqlite:" . $uploadedFile);
    $mysql = new mysqli($servername, $username, $password, $dbname);
    
    if ($mysql->connect_error) {
        die("MySQL Connection failed: " . $mysql->connect_error);
    }
    
    // Set UTF-8 encoding and disable foreign key checks
    $mysql->set_charset("utf8mb4");
    $mysql->query("SET FOREIGN_KEY_CHECKS=0");
    
    $tables = $sqlite->query("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'android_metadata' AND name NOT LIKE 'sqlite_%' AND name NOT LIKE 'room_%' AND name NOT LIKE 'ITRFormHistory'")->fetchAll(PDO::FETCH_COLUMN);
    
    foreach ($tables as $table) {
        $table = strtolower($table);
        
        if (strtolower($table) === 'itrformhistory') {
            continue;
        }
        
        $columns = $sqlite->query("PRAGMA table_info(`$table`)")->fetchAll(PDO::FETCH_ASSOC);
        $columnNames = array_column($columns, 'name');
        
        if (!$mysql->query("SELECT 1 FROM `$table` LIMIT 1")) {
            $importErrors[] = "Table $table doesn't exist in MySQL - skipped";
            continue;
        }
        
        $rows = $sqlite->query("SELECT * FROM `$table`")->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($rows as $row) {
            $timestampFields = ['date_time', 'sa_date', 'createdAt', 'updatedAt'];
            foreach ($timestampFields as $field) {
                if (isset($row[$field])) {
                    if (is_numeric($row[$field])) {
                        $row[$field] = date('Y-m-d H:i:s', $row[$field] / 1000);
                    } elseif (strtotime($row[$field])) {
                        $row[$field] = date('Y-m-d H:i:s', strtotime($row[$field]));
                    }
                }
            }
            
            $values = [];
            foreach ($columnNames as $colName) {
                $value = $row[$colName] ?? null;
                
                if ($value === null) {
                    $values[] = 'NULL';
                } elseif (is_numeric($value)) {
                    $values[] = $value;
                } else {
                    $values[] = "'" . $mysql->real_escape_string($value) . "'";
                }
            }
            
            $sql = "INSERT INTO `$table` (`" . implode("`,`", $columnNames) . "`) VALUES (" . implode(",", $values) . ")";
            
            if (!$mysql->query($sql)) {
                // Check for duplicate entry error (MySQL error code 1062)
                if ($mysql->errno == 1062) {
                    $duplicateCount++;
                    $importErrors[] = "Duplicate entry in $table: " . $mysql->error;
                } else {
                    $importErrors[] = "Error inserting into $table: " . $mysql->error;
                }
            }
        }
    }
    
    $mysql->query("SET FOREIGN_KEY_CHECKS=1");
    
    if (empty($importErrors)) {
        $success = true;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SQLite to MySQL Import</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .container { max-width: 1200px; margin-top: 30px; }
        .table { font-size: 14px; margin-bottom: 20px; }
        .alert { margin-top: 20px; }
        .card-header { font-weight: bold; }
        .table-structure th { background-color: #f8f9fa; }
        .nav-tabs .nav-link.active { font-weight: bold; background-color: #f8f9fa; }
        .tab-content { border: 1px solid #dee2e6; border-top: none; padding: 20px; }
        .error-details { background: #f8f9fa; padding: 10px; border-radius: 5px; margin-top: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="mb-4">SQLite to MySQL Import Tool</h2>
        
        <div class="card">
            <div class="card-header bg-primary text-white">
                Upload SQLite Database
            </div>
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label">Select SQLite Database File</label>
                        <input type="file" name="sqlite_db" class="form-control" required accept=".sqlite,.db">
                    </div>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </form>
            </div>
        </div>
        
        <?php if (!empty($tables) && $sqlite) : ?>
        <div class="card mt-4">
            <div class="card-header bg-success text-white">
                Database Content Preview
            </div>
            <div class="card-body">
                <form method="POST" id="importForm">
                    <input type="hidden" name="insert_data" value="1">
                    <button type="submit" class="btn btn-success mb-3" id="importButton">Import to MySQL</button>
                    
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <?php foreach ($tables as $i => $table): ?>
                        <?php if (strtolower($table) !== 'itrformhistory'): ?>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link <?php echo $i === 0 ? 'active' : ''; ?>" 
                                    id="tab-<?php echo $table; ?>" 
                                    data-bs-toggle="tab" 
                                    data-bs-target="#content-<?php echo $table; ?>" 
                                    type="button" role="tab">
                                <?php echo $table; ?>
                            </button>
                        </li>
                        <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                    
                    <div class="tab-content" id="myTabContent">
                        <?php foreach ($tables as $i => $table): ?>
                        <?php if (strtolower($table) !== 'itrformhistory'): ?>
                        <div class="tab-pane fade <?php echo $i === 0 ? 'show active' : ''; ?>" 
                             id="content-<?php echo $table; ?>" 
                             role="tabpanel">
                             
                    
                            <h5>Sample Data (First 5 Rows)</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <?php 
                                            $columns = $sqlite->query("PRAGMA table_info(`$table`)")->fetchAll(PDO::FETCH_ASSOC);
                                            foreach ($columns as $col): ?>
                                            <th><?php echo $col['name']; ?></th>
                                            <?php endforeach; ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $rows = $sqlite->query("SELECT * FROM `$table` LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
                                        foreach ($rows as $row): ?>
                                        <tr>
                                            <?php foreach ($row as $value): ?>
                                            <td><?php echo htmlspecialchars($value); ?></td>
                                            <?php endforeach; ?>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </form>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        <?php if ($success): ?>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Success!',
                text: 'Data import completed successfully!',
                icon: 'success',
                confirmButtonText: 'OK'
            });
        });
        <?php elseif (!empty($importErrors)): ?>
        document.addEventListener('DOMContentLoaded', function() {
            // Group errors by type
            const errorGroups = {
                duplicates: [],
                other: []
            };
            
            <?php foreach ($importErrors as $error): ?>
                <?php if (strpos($error, 'Duplicate entry') !== false): ?>
                    errorGroups.duplicates.push("<?php echo addslashes($error); ?>");
                <?php else: ?>
                    errorGroups.other.push("<?php echo addslashes($error); ?>");
                <?php endif; ?>
            <?php endforeach; ?>
            
            // Build error message
            let errorMessage = '';
            
            // Show duplicate errors first if they exist
            if (errorGroups.duplicates.length > 0) {
                errorMessage += `<h5>Duplicate Entries (${errorGroups.duplicates.length})</h5>`;
                errorMessage += `<div style="max-height: 200px; overflow-y: auto; margin-bottom: 20px;">`;
                errorMessage += `<ul style="text-align: left;">`;
                
                const maxDuplicatesToShow = 5;
                const duplicatesToShow = errorGroups.duplicates.slice(0, maxDuplicatesToShow);
                
                duplicatesToShow.forEach(error => {
                    errorMessage += `<li>${error}</li>`;
                });
                
                if (errorGroups.duplicates.length > maxDuplicatesToShow) {
                    errorMessage += `<li>+ ${errorGroups.duplicates.length - maxDuplicatesToShow} more duplicates...</li>`;
                }
                
                errorMessage += `</ul>`;
                errorMessage += `</div>`;
            }
            
            // Show other errors if they exist
            if (errorGroups.other.length > 0) {
                errorMessage += `<h5>Other Errors (${errorGroups.other.length})</h5>`;
                errorMessage += `<div style="max-height: 200px; overflow-y: auto;">`;
                errorMessage += `<ul style="text-align: left;">`;
                
                const maxOtherToShow = 5;
                const otherToShow = errorGroups.other.slice(0, maxOtherToShow);
                
                otherToShow.forEach(error => {
                    errorMessage += `<li>${error}</li>`;
                });
                
                if (errorGroups.other.length > maxOtherToShow) {
                    errorMessage += `<li>+ ${errorGroups.other.length - maxOtherToShow} more errors...</li>`;
                }
                
                errorMessage += `</ul>`;
                errorMessage += `</div>`;
            }
            
            Swal.fire({
                title: 'Import failed: Duplicate ID',
                html: errorMessage,
                icon: 'warning',
                confirmButtonText: 'OK',
                width: '800px',
                customClass: {
                    content: 'text-left'
                }
            });
        });
        <?php endif; ?>
        
        // Prevent form resubmission on page refresh
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>
</body>
</html>