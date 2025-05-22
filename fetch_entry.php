<?php
header('Content-Type: application/json');

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "itr_database";

$database = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($database->connect_error) {
    die(json_encode(['error' => "Connection failed: " . $database->connect_error]));
}

$itr_form_num = $_GET['itr_form_num'] ?? '';

if (empty($itr_form_num)) {
    echo json_encode(['error' => 'ITR Form Number is required ' ]);
    exit;
}

try {
    // Fetch business info
    $stmt = $database->prepare("SELECT * FROM businessinfo WHERE itr_form_num = ?");
    $stmt->bind_param("s", $itr_form_num);
    $stmt->execute();
    $businessInfo = $stmt->get_result()->fetch_assoc();
    
    if (!$businessInfo) {
        echo json_encode(['error' => 'Record not found']);
        exit;
    }
    
    // Fetch standard compliance checklist
    $stmt = $database->prepare("SELECT * FROM standardcompliancechecklist WHERE itr_form_num = ?");
    $stmt->bind_param("s", $itr_form_num);
    $stmt->execute();
    $checklist = $stmt->get_result()->fetch_assoc();
    
    // Fetch supplier info
    $stmt = $database->prepare("SELECT * FROM suppliersinfo WHERE itr_form_num = ?");
    $stmt->bind_param("s", $itr_form_num);
    $stmt->execute();
    $supplierInfo = $stmt->get_result()->fetch_assoc();
    
    // Fetch product quality
    $stmt = $database->prepare("SELECT * FROM productquality WHERE itr_form_num = ?");
    $stmt->bind_param("s", $itr_form_num);
    $stmt->execute();
    $productQuality = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

      // Fetch action_required and user_gen_remarks
      $stmt = $database->prepare("SELECT action_required, user_gen_remarks FROM summaryremarks WHERE itr_form_num = ?");
      $stmt->bind_param("s", $itr_form_num);
      $stmt->execute();
      $result = $stmt->get_result();
      $generalremarks = $result->num_rows > 0 ? $result->fetch_assoc() : ['action_required' => '', 'user_gen_remarks' => ''];
    
    // Fetch product quality control
    $stmt = $database->prepare("SELECT * FROM productqualitycont WHERE itr_form_num = ?");
    $stmt->bind_param("s", $itr_form_num);
    $stmt->execute();
    $productQualityCont = $stmt->get_result()->fetch_assoc();
    
    // Merge product quality control with checklist
    if ($productQualityCont) {
        $checklist['duplicate_retention_samples'] = $productQualityCont['duplicate_retention_samples'];
        $checklist['appropriate_sampling'] = $productQualityCont['appropriate_sampling'];
    }
    
    echo json_encode([
        'businessInfo' => $businessInfo,
        'checklist' => $checklist,
        'supplierInfo' => $supplierInfo,
        'productQuality' => $productQuality,
        'generalremarks' => $generalremarks
    ]);
    
} catch (Exception $e) {
    echo json_encode(['error' => "Error: " . $e->getMessage()]);
} finally {
    $database->close();
}
?>