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
$importErrors = [];
$success = false;
$duplicateCount = 0;
$updatedRows = [];
$insertedRows = [];

// Handle file upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['sqlite_db'])) {
    if (!move_uploaded_file($_FILES['sqlite_db']['tmp_name'], $uploadedFile)) {
        die("Failed to upload file");
    }
    
    $fileData = file_get_contents($uploadedFile);
    $fileName = basename($uploadedFile);
    $fileSize = filesize($uploadedFile);
    
    $mysql = new mysqli($servername, $username, $password, $dbname);
    $stmt = $mysql->prepare("INSERT INTO sqlite_files (filename, file_size, file_data, upload_date) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("sis", $fileName, $fileSize, $fileData);
    $stmt->execute();
    $stmt->close();
    
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
        
        $primaryKey = 'id';
        foreach ($columns as $col) {
            if ($col['pk'] == 1) {
                $primaryKey = $col['name'];
                break;
            }
        }
        
        $hasId = in_array('id', $columnNames);
        $hasItrFormNum = in_array('itr_form_num', $columnNames);
        
        $uniqueIdentifier = ($hasId && $hasItrFormNum) ? ['id', 'itr_form_num'] : 
                          ($hasId ? ['id'] : 
                          ($hasItrFormNum ? ['itr_form_num'] : [$primaryKey]));
        
        if (!$mysql->query("SELECT 1 FROM `$table` LIMIT 1")) {
            $importErrors[] = "Table $table doesn't exist in MySQL - skipped";
            continue;
        }
        
        $rows = $sqlite->query("SELECT * FROM `$table`")->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($rows as $row) {
            $timestampFields = ['date_time', 'sa_date', 'createdAt', 'updatedAt', 'date_deliver'];
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
            $updateParts = [];
            foreach ($columnNames as $colName) {
                $value = $row[$colName] ?? null;
                
                if ($value === null) {
                    $values[] = 'NULL';
                    $updateParts[] = "`$colName` = NULL";
                } elseif (is_numeric($value)) {
                    $values[] = $value;
                    $updateParts[] = "`$colName` = $value";
                } else {
                    $escapedValue = $mysql->real_escape_string($value);
                    $values[] = "'" . $escapedValue . "'";
                    $updateParts[] = "`$colName` = '" . $escapedValue . "'";
                }
            }
            
            $sql = "INSERT INTO `$table` (`" . implode("`,`", $columnNames) . "`) VALUES (" . implode(",", $values) . ")";
            
            if ($mysql->query($sql)) {
                $insertedRows[] = [
                    'table' => $table,
                    'identifier' => implode(', ', $uniqueIdentifier),
                    'value' => implode(', ', array_map(function($id) use ($row) { return $row[$id]; }, $uniqueIdentifier)),
                    'columns' => $columnNames,
                    'values' => $row
                ];
            } else {
                if ($mysql->errno == 1062) {
                    $duplicateCount++;
                    
                    $whereClauses = [];
                    foreach ($uniqueIdentifier as $idField) {
                        if (isset($row[$idField])) {
                            $whereClauses[] = "`$idField` = '" . $mysql->real_escape_string($row[$idField]) . "'";
                        }
                    }
                    
                    $attempts = [];
                    if (count($whereClauses) > 1) {
                        $attempts[] = implode(' AND ', $whereClauses);
                        foreach ($whereClauses as $clause) {
                            $attempts[] = $clause;
                        }
                    } else {
                        $attempts = $whereClauses;
                    }
                    
                    $updated = false;
                    $existingRow = null;
                    
                    foreach ($attempts as $where) {
                        $existingRow = $mysql->query("SELECT * FROM `$table` WHERE $where LIMIT 1")->fetch_assoc();
                        if ($existingRow) {
                            break;
                        }
                    }
                    
                    if ($existingRow) {
                        $changedColumns = [];
                        $emptyColumnsFilled = [];
                        
                        foreach ($columnNames as $colName) {
                            $newValue = $row[$colName] ?? null;
                            $oldValue = $existingRow[$colName] ?? null;
                            
                            if (in_array($colName, $uniqueIdentifier)) {
                                continue;
                            }
                            
                            $oldValueDisplay = ($oldValue === null) ? 'NULL' : $oldValue;
                            $newValueDisplay = ($newValue === null) ? 'NULL' : $newValue;
                            
                            if ($oldValue != $newValue) {
                                $changedColumns[$colName] = [
                                    'old' => $oldValueDisplay,
                                    'new' => $newValueDisplay
                                ];
                                
                                if (($oldValue === null || $oldValue === '') && ($newValue !== null && $newValue !== '')) {
                                    $emptyColumnsFilled[$colName] = [
                                        'old' => $oldValueDisplay,
                                        'new' => $newValueDisplay
                                    ];
                                }
                            }
                        }
                        
                        $updateSql = "UPDATE `$table` SET " . implode(", ", $updateParts) . 
                                     " WHERE " . $where;
                        
                        if ($mysql->query($updateSql)) {
                            $updated = true;
                            $updatedRows[] = [
                                'table' => $table,
                                'identifier' => implode(', ', $uniqueIdentifier),
                                'value' => implode(', ', array_map(function($id) use ($row) { return $row[$id]; }, $uniqueIdentifier)),
                                'columns' => $columnNames,
                                'values' => $row,
                                'changed_columns' => $changedColumns,
                                'empty_columns_filled' => $emptyColumnsFilled,
                                'where_used' => $where
                            ];
                        }
                    }
                    
                    if (!$updated) {
                        $importErrors[] = "Error updating duplicate in $table (ID: " . 
                                          implode(', ', array_map(function($id) use ($row) { 
                                              return isset($row[$id]) ? $row[$id] : 'NULL'; 
                                          }, $uniqueIdentifier)) . 
                                          "): " . $mysql->error;
                    }
                } else {
                    $importErrors[] = "Error inserting into $table: " . $mysql->error;
                }
            }
        }
    }
    
    $status = empty($importErrors) ? 'success' : (count($importErrors) == $duplicateCount ? 'success_with_duplicates' : 'failed');

    if ($status === 'success' || $status === 'success_with_duplicates') {
        $updateStmt = $mysql->prepare("UPDATE sqlite_files SET import_date = NOW(), import_status = ? WHERE filename = ? ORDER BY id DESC LIMIT 1");
        $updateStmt->bind_param("ss", $status, basename($uploadedFile));
        $updateStmt->execute();
        $updateStmt->close();
    }

    $mysql->query("SET FOREIGN_KEY_CHECKS=1");

    if (empty($importErrors) || count($importErrors) == $duplicateCount) {
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
        .table { font-size: 13px; margin-bottom: 20px; }
        .alert { margin-top: 20px; }
        .card-header { font-weight: bold; }
        .table-structure th { background-color: #f8f9fa; }
        .nav-tabs .nav-link.active { font-weight: bold; background-color: #f8f9fa; }
        .tab-content { border: 1px solid #dee2e6; border-top: none; padding: 20px; }
        .error-details { background: #f8f9fa; padding: 10px; border-radius: 5px; margin-top: 5px; }
        .updated-row { background-color: #e6ffe6; }
        .inserted-row { background-color: #e6f3ff; }
        .diff-old { color: #dc3545; text-decoration: line-through; }
        .diff-new { color: #28a745; font-weight: bold; }
        .diff-empty { color: #6c757d; font-style: italic; }
        .changes-table { width: 100%; margin-bottom: 15px; }
        .changes-table th { background-color: #f8f9fa; text-align: left; padding: 8px; }
        .changes-table td { padding: 8px; border-bottom: 1px solid #dee2e6; }
        .swal2-popup { text-align: left !important; }
        .alert-heading { font-size: 20px;}
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
    
    <div class="alert alert-warning d-flex align-items-center mb-3">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2" viewBox="0 0 16 16">
            <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
        </svg>
        <div>
            <strong>Important Notice:</strong> 
            <ol class="mb-2">
                <li>Please carefully review all data in the preview below before importing.</li>
                <li>Double-check for accuracy, especially for:
                    <ul>
                        <li>Correct ITR Form Numbers</li>
                        <li>Accurate business information</li>
                        <li>Proper date formatting</li>
                        <li>Complete data in all required fields</li>
                    </ul>
                </li>
                <li class="fw-bold">After verifying the data is correct, click the "Import to MySQL" button below to proceed.</li>
            </ol>
            <div class="alert alert-info mt-2 p-2">
                <i class="bi bi-info-circle-fill me-2"></i>
                The import process may take several minutes depending on the data volume. Please don't close this page during import.
            </div>
        </div>
    </div>
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
                                        foreach ($rows as $row): 
                                            $isUpdated = false;
                                            $isInserted = false;

                                            $timestampFields = ['date_time', 'sa_date', 'createdAt', 'updatedAt', 'date_deliver'];
                                            foreach ($timestampFields as $field) {
                                                if (isset($row[$field])) {
                                                    if (is_numeric($row[$field])) {
                                                        $row[$field] = date('Y-m-d H:i:s', $row[$field] / 1000);
                                                    } elseif (strtotime($row[$field])) {
                                                        $row[$field] = date('Y-m-d H:i:s', strtotime($row[$field]));
                                                    }
                                                }
                                            }
                                
                                            
                                            foreach ($updatedRows as $updated) {
                                                if (strtolower($updated['table']) === strtolower($table) && 
                                                    $updated['value'] == $row[$updated['identifier']]) {
                                                    $isUpdated = true;
                                                    break;
                                                }
                                            }
                                            
                                            foreach ($insertedRows as $inserted) {
                                                if (strtolower($inserted['table']) === strtolower($table) && 
                                                    $inserted['value'] == $row[$inserted['identifier']]) {
                                                    $isInserted = true;
                                                    break;
                                                }
                                            }
                                        ?>
                                        <tr class="<?php echo $isUpdated ? 'updated-row' : ($isInserted ? 'inserted-row' : ''); ?>">
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

    <?php
// [Previous PHP code remains the same until the script section]
?>

<script>
    <?php if ($success): ?>
    document.addEventListener('DOMContentLoaded', function() {
        const insertedRowsData = <?php echo json_encode($insertedRows); ?>;
        const updatedRowsData = <?php echo json_encode($updatedRows); ?>;
        const duplicateCount = <?php echo $duplicateCount; ?>;
        
        // Check if there were any actual changes
        const hasNewData = insertedRowsData.length > 0;
        const hasUpdates = updatedRowsData.length > 0;
        const hasOnlyDuplicates = !hasNewData && !hasUpdates && duplicateCount > 0;
        
        if (!hasNewData && !hasUpdates) {
            if (hasOnlyDuplicates) {
                // Case 1: Only duplicate records found (no updates made)
                Swal.fire({
                    title: 'No New Data Imported',
                    html: `<div style="text-align: left;">
                        <div class="alert alert-info">
                            <h5>Duplicate Records Found</h5>
                            <p>${duplicateCount} records already exist in the database and were not modified because:</p>
                            <ul>
                                <li>No changes were detected in the existing records</li>
                                <li>Or the records were identical to existing ones</li>
                            </ul>
                        </div>
                    </div>`,
                    icon: 'info',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.reload();
                });
            } else {
                // Case 2: No changes at all
                Swal.fire({
                    title: 'No Changes Detected',
                    text: 'The database was imported successfully, but no new or modified records were found.',
                    icon: 'info',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.reload();
                });
            }
        } else {
            // Case 3: Some changes were made (inserts or updates)
            let successContent = `<div style="text-align: left;">`;
            
            if (hasNewData) {
                const businessInfoInserts = insertedRowsData.filter(row => 
                    row.table.toLowerCase() === 'businessinfo' && row.values.itr_form_num
                );
                
                if (businessInfoInserts.length > 0) {
                    successContent += `
                        <div class="alert alert-success">
                            <h5>✅ New Business Records Added (${businessInfoInserts.length})</h5>
                            <div style="max-height: 300px; overflow-y: auto;">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>ITR Form #</th>
                                            <th>Business Name</th>
                                            <th>Location</th>
                                        </tr>
                                    </thead>
                                    <tbody>`;
                    
                    businessInfoInserts.slice(0, 10).forEach(row => {
                        successContent += `
                            <tr>
                                <td>${row.values.itr_form_num || 'N/A'}</td>
                                <td>${row.values.business_name || 'N/A'}</td>
                                <td>${row.values.location || 'N/A'}</td>
                            </tr>`;
                    });
                    
                    successContent += `</tbody></table>`;
                    
                    if (businessInfoInserts.length > 10) {
                        successContent += `<p>+ ${businessInfoInserts.length - 10} more records</p>`;
                    }
                    
                    successContent += `</div></div>`;
                }
            }
            
            if (hasUpdates) {
                const meaningfulUpdates = updatedRowsData.filter(row => 
                    Object.values(row.changed_columns).some(change => 
                        change.old !== 'NULL' && change.old !== '' && 
                        change.new !== 'NULL' && change.new !== ''
                    )
                );
                
                if (meaningfulUpdates.length > 0) {
                    successContent += `
                        <div class="alert alert-warning">
                            <h5>🔄 Updated Records (${meaningfulUpdates.length})</h5>
                            <p>The following records were updated because changes were detected:</p>
                            <div style="max-height: 300px; overflow-y: auto;">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Table</th>
                                            <th>ITR Form #</th>
                                            <th>Changes</th>
                                        </tr>
                                    </thead>
                                    <tbody>`;

                 
                    
                    meaningfulUpdates.slice(0, 5).forEach(row => {
                        successContent += `
                            <tr>
                                <td>${row.table}</td>
                                <td>${row.values.itr_form_num || 'N/A'}</td>
                                <td>
                                    <ul style="padding-left: 20px; margin-bottom: 0;">`;
                        
                        Object.entries(row.changed_columns).slice(0, 3).forEach(([col, values]) => {
                            if (values.old !== 'NULL' && values.old !== '' && 
                                values.new !== 'NULL' && values.new !== '') {
                                successContent += `
                                    <li>
                                        <strong>${col}:</strong> 
                                        <span class="diff-old">${values.old}</span> → 
                                        <span class="diff-new">${values.new}</span>
                                    </li>`;
                            }
                        });
                        
                        if (Object.keys(row.changed_columns).length > 3) {
                            successContent += `<li>+ ${Object.keys(row.changed_columns).length - 3} more changes</li>`;
                        }
                        
                        successContent += `</ul></td></tr>`;
                    });
                    
                    successContent += `</tbody></table>`;
                    
                    if (meaningfulUpdates.length > 5) {
                        successContent += `<p>+ ${meaningfulUpdates.length - 5} more records</p>`;
                    }
                    
                    successContent += `</div></div>`;
                }
            }
            
            if (duplicateCount > 0) {
                successContent += `
                
                    <div class="alert alert-info">
                        <h5>⚠️ ${duplicateCount} Existing Records Not Modified</h5>
                        <p>These records already existed in the database and were identical to the imported data.</p>
                    </div>`

                    Swal.fire({
                    title: 'No New Data Imported',
                    html: `<div style="text-align: left;">
                        <div class="alert alert-info">
                            <h5>Duplicate Records Found</h5>
                            <p>${duplicateCount} records already exist in the database and were not modified because:</p>
                            <ul>
                                <li>No changes were detected in the existing records</li>
                                <li>Or the records were identical to existing ones</li>
                            </ul>
                        </div>
                    </div>`,
                    icon: 'info',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.reload();
                });
            }
            
            successContent += `</div>`;
            
            Swal.fire({
    title: 'Import Results',
    html: successContent,
    icon: (duplicateCount && !hasNewData && !hasUpdates) ? 'warning' : 'success',
    confirmButtonText: 'OK',
    width: '800px'
}).then(() => {
    window.location.reload();
});
        }
    });
    <?php elseif (!empty($importErrors)): ?>
    document.addEventListener('DOMContentLoaded', function() {
        const errorGroups = {
            duplicates: [],
            schema: [],
            data: [],
            other: []
        };
        
        <?php foreach ($importErrors as $error): ?>
            <?php if (strpos($error, 'duplicate') !== false || strpos($error, 'existing') !== false): ?>
                errorGroups.duplicates.push("<?php echo addslashes($error); ?>");
            <?php elseif (strpos($error, 'Table') !== false && strpos($error, "doesn't exist") !== false): ?>
                errorGroups.schema.push("<?php echo addslashes($error); ?>");
            <?php elseif (strpos($error, 'Error inserting') !== false || strpos($error, 'Error updating') !== false): ?>
                errorGroups.data.push("<?php echo addslashes($error); ?>");
            <?php else: ?>
                errorGroups.other.push("<?php echo addslashes($error); ?>");
            <?php endif; ?>
        <?php endforeach; ?>
        
        let errorContent = `<div style="text-align: left;">`;
        
        // Schema errors (missing tables)
        if (errorGroups.schema.length > 0) {
            errorContent += `
                <div class="alert alert-danger">
                    <h5>Database Structure Issues</h5>
                    <p>The following tables are missing in MySQL:</p>
                    <div style="max-height: 200px; overflow-y: auto;">
                        <ul>
                            ${errorGroups.schema.slice(0, 10).map(error => `
                                <li>${error.replace("Table ", "").replace(" doesn't exist in MySQL - skipped", "")}</li>
                            `).join('')}
                        </ul>
                    </div>
                    <p class="mt-2"><strong>Solution:</strong> Ensure all required tables exist in the MySQL database.</p>
                </div>`;
        }
        
        // Data errors
        if (errorGroups.data.length > 0) {
            errorContent += `
                <div class="alert alert-warning">
                    <h5>Data Import Issues</h5>
                    <p>The following data could not be imported:</p>
                    <div style="max-height: 200px; overflow-y: auto;">
                        <ul>
                            ${errorGroups.data.slice(0, 10).map(error => `
                                <li>${error}</li>
                            `).join('')}
                        </ul>
                    </div>
                    <p class="mt-2"><strong>Possible causes:</strong> Data format mismatch, constraints violation, or missing references.</p>
                </div>`;
        }
        
        // Other errors
        if (errorGroups.other.length > 0) {
            errorContent += `
                <div class="alert alert-danger">
                    <h5>Other Errors</h5>
                    <div style="max-height: 200px; overflow-y: auto;">
                        <ul>
                            ${errorGroups.other.slice(0, 10).map(error => `
                                <li>${error}</li>
                            `).join('')}
                        </ul>
                    </div>
                </div>`;
        }
        
        // Duplicate warnings (not really errors)
        if (errorGroups.duplicates.length > 0) {
            errorContent += `
                <div class="alert alert-info">
                    <h5>Duplicate Records</h5>
                    <p>${errorGroups.duplicates.length} records already existed in the database.</p>
                    <div style="max-height: 200px; overflow-y: auto;">
                        <ul>
                            ${errorGroups.duplicates.slice(0, 5).map(error => `
                                <li>${error.replace("Error updating duplicate in ", "").replace(": Duplicate entry", "Record exists")}</li>
                            `).join('')}
                        </ul>
                    </div>
                    <p class="mt-2">These records were not modified as they were identical to existing data.</p>
                </div>`;
        }
        
        errorContent += `</div>`;
        
        Swal.fire({
            title: 'Import Completed with Issues',
            html: errorContent,
            icon: 'warning',
            confirmButtonText: 'OK',
            width: '800px'
        }).then(() => {
            window.location.reload();
        });
    });
    <?php endif; ?>
    
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>