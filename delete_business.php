<?php
header('Content-Type: application/json');
require 'config.php';

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Start transaction
    $conn->beginTransaction();
    
    $itrFormNum = $_POST['itr_form_num'];
    
    // Delete related records first (to maintain referential integrity)
    $tables = [
        'generalremarks',
        'productquality',
        'productqualitycont',
        'standardcompliancechecklist',
        'suppliersinfo',
        'summaryremarks'
    ];
    
    foreach ($tables as $table) {
        $stmt = $conn->prepare("DELETE FROM $table WHERE itr_form_num = ?");
        $stmt->execute([$itrFormNum]);
    }
    
    // Finally delete the business info
    $stmt = $conn->prepare("DELETE FROM businessinfo WHERE itr_form_num = ?");
    $stmt->execute([$itrFormNum]);
    
    // Commit transaction
    $conn->commit();
    
    echo json_encode(['success' => true]);
    
} catch(PDOException $e) {
    $conn->rollBack();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

$conn = null;
?>