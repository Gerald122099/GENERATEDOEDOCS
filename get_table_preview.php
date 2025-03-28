<?php
require 'config.php';
header('Content-Type: text/html');

if (!isset($_GET['table'])) {
    die('Table parameter missing');
}

$uploadedFile = "uploads/sqlite_db.sqlite";
$table = $_GET['table'];

try {
    $sqlite = new PDO("sqlite:" . $uploadedFile);
    $stmt = $sqlite->query("SELECT * FROM $table LIMIT 10");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($rows)) {
        echo "<p>No data found in table</p>";
        return;
    }
    
    echo '<table class="table table-bordered table-striped">';
    echo '<thead><tr>';
    foreach (array_keys($rows[0]) as $column) {
        echo '<th>'.htmlspecialchars($column).'</th>';
    }
    echo '</tr></thead>';
    echo '<tbody>';
    foreach ($rows as $row) {
        echo '<tr>';
        foreach ($row as $value) {
            echo '<td>'.htmlspecialchars($value).'</td>';
        }
        echo '</tr>';
    }
    echo '</tbody></table>';
} catch (Exception $e) {
    echo "<p>Error loading table preview: ".htmlspecialchars($e->getMessage())."</p>";
}