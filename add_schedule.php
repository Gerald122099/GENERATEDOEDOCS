<?php
session_start();
require "config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $location = $_POST['location'];
    $inspector = $_POST['inspector'];
    $date = $_POST['date'];
    $notes = $_POST['notes'] ?? '';
    
    $stmt = $conn->prepare("INSERT INTO inspections (location, inspector, inspection_date, notes) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $location, $inspector, $date, $notes);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'id' => $stmt->insert_id]);
    } else {
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }
    $stmt->close();
}
$conn->close();