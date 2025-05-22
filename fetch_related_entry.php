<?php
header('Content-Type: application/json');

// Database connection
require 'config.php';

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $itrFormNum = $_POST['itr_form_num'];
    
    $response = [];
    
    // Fetch general remarks
    $stmt = $conn->prepare("SELECT remarks FROM generalremarks WHERE itr_form_num = ?");
    $stmt->execute([$itrFormNum]);
    $response['generalRemarks'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Fetch product quality
    $stmt = $conn->prepare("SELECT product, ron_value, UGT, pump FROM productquality WHERE itr_form_num = ?");
    $stmt->execute([$itrFormNum]);
    $response['productQuality'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Fetch standard compliance
    $stmt = $conn->prepare("SELECT *
                           FROM standardcompliancechecklist  WHERE itr_form_num = ?");
    $stmt->execute([$itrFormNum]);
    $response['standardCompliance'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Fetch suppliers info
    $stmt = $conn->prepare("SELECT supplier, date_deliver, address FROM suppliersinfo WHERE itr_form_num = ?");
    $stmt->execute([$itrFormNum]);
    $response['suppliersInfo'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Fetch summary remarks
    $stmt = $conn->prepare("SELECT extracted_violations, action_required FROM summaryremarks WHERE itr_form_num = ?");
    $stmt->execute([$itrFormNum]);
    $response['summaryRemarks'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($response);
    
} catch(PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}

$conn = null;
?>