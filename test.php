<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "itrf_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Begin transaction
$conn->begin_transaction();

try {
    // Insert into businessinfo
    $stmt = $conn->prepare("INSERT INTO businessinfo (itr_form_num, business_name, dealer_operator, location, in_charge, designation, date_time, sa_no, sa_date, outlet_class, company, contact_tel, email_add, sampling, inspector_name, createdAt) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssssssssss", $itr_form_num, $business_name, $dealer_operator, $location, $in_charge, $designation, $date_time, $sa_no, $sa_date, $outlet_class, $company, $contact_tel, $email_add, $sampling, $inspector_name, $createdAt);

    // Sample Data
    $itr_form_num = "ITR-006";
    $business_name = "KMJS";
    $dealer_operator = "Petron";
    $location = "Escario St. Cebu City";
    $in_charge = "John Doe";
    $designation = "Manager";
    $date_time = "2025-03-26 13:22:00";
    $sa_no = "SA-001";
    $sa_date = "2025-03-26 01:00:00";
    $outlet_class = "COCO";
    $company = "Petron";
    $contact_tel = "";
    $email_add = "email@yahoo.com";
    $sampling = "1";
    $inspector_name = "Odhrey Lao";
    $createdAt = "2025-03-27 05:48:32";

    if (!$stmt->execute()) {
        throw new Exception("Error inserting into businessinfo: " . $stmt->error);
    }
    $stmt->close();

    // Insert into generalremarks (multiple rows)
    $remarks_data = [
        ['ff9355a46-9ec6-4943-bbef-3bd37b9s90s38bc', 'No Violations'],
        ['3835bebb3-78ca-4a85-9f3c-0e19sb5548s2d3c', 'Small font size for signage'],
        ['c4436cf3e-0183-421b-9859-7d8s7csc4b1733c', 'Several violations noted']
    ];

    $stmt = $conn->prepare("INSERT INTO generalremarks (id, itr_form_num, remarks, createdAt) VALUES (?, ?, ?, ?)");
    foreach ($remarks_data as $row) {
        $uuid = $row[0];  // Ensure unique ID
        $remarks = $row[1];
        $stmt->bind_param("ssss", $uuid, $itr_form_num, $remarks, $createdAt);
        if (!$stmt->execute()) {
            throw new Exception("Error inserting into generalremarks: " . $stmt->error);
        }
    }
    $stmt->close();

    // Insert into productquality (multiple rows)
    $product_data = [
        ['c5f99e24-4880-4705-86c5-ee604e7b8bd6s', 'CEB-2025-P-001', 'PREMIUM', '91', '5', '9']
    ];

    $stmt = $conn->prepare("INSERT INTO productquality (id, itr_form_num, code_value, product, ron_value, ugt, pump, createdAt) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    foreach ($product_data as $row) {
        $uuid = $row[0];
        $code_value = $row[1];
        $product = $row[2];
        $ron_value = $row[3];
        $ugt = $row[4];
        $pump = $row[5];
        $stmt->bind_param("ssssssss", $uuid, $itr_form_num, $code_value, $product, $ron_value, $ugt, $pump, $createdAt);
        if (!$stmt->execute()) {
            throw new Exception("Error inserting into productquality: " . $stmt->error);
        }
    }
    $stmt->close();

    // Insert into standardcompliancechecklist (multiple rows)
    $stmt = $conn->prepare("INSERT INTO standardcompliancechecklist (id, itr_form_num, coc_cert, coc_cert_remarks, post_unleaded, post_unleaded_remarks, clean_rest, clean_rest_remarks, createdAt) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $compliance_data = [
        ['932bedd8-6459-4bbf-8bde-7ed3fd665ae8a', '1', 'No coc posted', '0', 'No Post', '1', 'Clean', '2025-03-27 05:55:41']
    ];

    foreach ($compliance_data as $row) {
        $uuid = $row[0];
        $coc_cert = $row[1];
        $coc_cert_remarks = $row[2];
        $post_unleaded = $row[3];
        $post_unleaded_remarks = $row[4];
        $clean_rest = $row[5];
        $clean_rest_remarks = $row[6];
        $stmt->bind_param("sssssssss", $uuid, $itr_form_num, $coc_cert, $coc_cert_remarks, $post_unleaded, $post_unleaded_remarks, $clean_rest, $clean_rest_remarks, $createdAt);
        if (!$stmt->execute()) {
            throw new Exception("Error inserting into standardcompliancechecklist: " . $stmt->error);
        }
    }
    $stmt->close();

    // Commit transaction if everything is successful
    $conn->commit();
    echo "All data inserted successfully!";
} catch (Exception $e) {
    $conn->rollback();
    echo "Transaction failed: " . $e->getMessage();
}

// Close connection
$conn->close();
?>
