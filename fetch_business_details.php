<?php
header('Content-Type: application/json');

require 'config.php';

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $itrFormNum = $_POST['itr_form_num'];
    
    $response = [];
    
    // Fetch business info
    $stmt = $conn->prepare("SELECT * FROM businessinfo WHERE itr_form_num = ?");
    $stmt->execute([$itrFormNum]);
    $response['businessInfo'] = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Fetch general remarks
    $stmt = $conn->prepare("SELECT * FROM generalremarks WHERE itr_form_num = ?");
    $stmt->execute([$itrFormNum]);
    $response['generalRemarks'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Fetch product quality
    $stmt = $conn->prepare("SELECT * FROM productquality WHERE itr_form_num = ?");
    $stmt->execute([$itrFormNum]);
    $response['productQuality'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Fetch product quality control
    $stmt = $conn->prepare("SELECT * FROM productqualitycont WHERE itr_form_num = ?");
    $stmt->execute([$itrFormNum]);
    $response['productQualityCont'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Fetch standard compliance
  $stmt = $conn->prepare("SELECT * FROM standardcompliancechecklist WHERE itr_form_num = ?");
$stmt->execute([$itrFormNum]);
$standardCompliance = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Convert 1/0 to true/false for all fields in each row
$response['standardCompliance'] = array_map(function($row) {
    return array_map(function($value) {
        if ($value === '1') return true;
        if ($value === '0') return false;
        return $value;
    }, $row);
}, $standardCompliance);

    
    // Fetch suppliers info
    $stmt = $conn->prepare("SELECT * FROM suppliersinfo WHERE itr_form_num = ?");
    $stmt->execute([$itrFormNum]);
    $response['suppliersInfo'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Fetch summary remarks
    $stmt = $conn->prepare("SELECT * FROM summaryremarks WHERE itr_form_num = ?");
    $stmt->execute([$itrFormNum]);
    $response['summaryRemarks'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($response);
    
} catch(PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}

$conn = null;
?>