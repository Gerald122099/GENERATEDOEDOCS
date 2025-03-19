<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "itrf_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $itr_form_num = $_POST['itr_form_num'];
    $business_name = $_POST['business_name'];
    $dealer_operator = $_POST['dealer_operator'];
    $location = $_POST['location'];
    $in_charge = $_POST['in_charge'];
    $designation = $_POST['designation'];
    $company = $_POST['company'];
    $contact_tel = $_POST['contact_tel'];
    $email_add = $_POST['email_add'];
    $outlet_classif = $_POST['outlet_classif'];
    
    // Insert into BusinessInfo
    $sql1 = "INSERT INTO BusinessInfo (itr_form_num, business_name, dealer_operator, location, in_charge, designation, company, contact_tel, email_add, outlet_classif)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt1 = $conn->prepare($sql1);
    $stmt1->bind_param("ssssssssss", $itr_form_num, $business_name, $dealer_operator, $location, $in_charge, $designation, $company, $contact_tel, $email_add, $outlet_classif);
    $stmt1->execute();
    
    // Insert into StandardsComplianceChecklist
    $coc_certificate = isset($_POST['coc_certificate']) ? 1 : 0;
    $valid_permits = isset($_POST['valid_permits']) ? 1 : 0;
    $sql2 = "INSERT INTO StandardsComplianceChecklist (itr_form_num, coc_certificate, valid_permits) VALUES (?, ?, ?)";
    $stmt2 = $conn->prepare($sql2);
    $stmt2->bind_param("sii", $itr_form_num, $coc_certificate, $valid_permits);
    $stmt2->execute();
    
    // Insert into GeneralRemarks
    $remarks = $_POST['general_remarks'];
    $sql3 = "INSERT INTO GeneralRemarks (itr_form_num, remarks, created_at) VALUES (?, ?, NOW())";
    $stmt3 = $conn->prepare($sql3);
    $stmt3->bind_param("ss", $itr_form_num, $remarks);
    $stmt3->execute();
    
    // Insert into SummaryRemarks
    $summary_field_name = $_POST['summary_field_name'];
    $summary_remarks = $_POST['summary_remarks'];
    $sql4 = "INSERT INTO SummaryRemarks (itr_form_num, field_name, remarks, created_at) VALUES (?, ?, ?, NOW())";
    $stmt4 = $conn->prepare($sql4);
    $stmt4->bind_param("sss", $itr_form_num, $summary_field_name, $summary_remarks);
    $stmt4->execute();
    
  
    
    echo "<script>alert('Records added successfully!'); window.location.href='form.html';</script>";
    
    $stmt1->close();
    $stmt2->close();
    $stmt3->close();
    $stmt4->close();
    $stmt5->close();
}

$conn->close();
?>
