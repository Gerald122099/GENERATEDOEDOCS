<?php
header('Content-Type: application/json');

require 'config.php';

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Fetch business info
    $stmt = $conn->prepare("SELECT itr_form_num, business_name, dealer_operator, location, in_charge, designation, date_time, sa_no, sa_date,  outlet_class, company, contact_tel, email_add, sampling, inspector_name, createdAt, time_inserted    FROM businessinfo");
    $stmt->execute();
    
    $businesses = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($businesses);
    
} catch(PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}

$conn = null;
?>