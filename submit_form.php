<?php
// submit_form.php

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "itrf_db";

    $database = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($database->connect_error) {
        die("Connection failed: " . $database->connect_error);
    }

    // Get form data
    $itr_form_num = $_POST['itr_form_num'] ?? '';
    $business_name = $_POST['business_name'] ?? '';
    $dealer_operator = $_POST['dealer_operator'] ?? '';
    $location = $_POST['location'] ?? '';
    $in_charge = $_POST['in_charge'] ?? '';
    $designation = $_POST['designation'] ?? '';
    $sa_no = isset($_POST['sa_no']) ? intval($_POST['sa_no']) : 0;
    $sa_date = $_POST['sa_date'] ?? '';
    $outlet_classif = $_POST['outlet_classif'] ?? '';
    $company = $_POST['company'] ?? '';
    $contact_tel = isset($_POST['contact_tel']) ? intval($_POST['contact_tel']) : 0;
    $email_add = $_POST['email_add'] ?? '';
    $sampling = isset($_POST['sampling']) ? 1 : 0;

    // Validate required fields
    if (empty($itr_form_num)) {
        die("Error: ITR Form Number is required.");
    }

    // Prepare and execute the INSERT statement for businessinfo
    $sql_business = "INSERT INTO businessinfo (itr_form_num, business_name, dealer_operator, location, in_charge, designation, sa_no, sa_date, outlet_classif, company, contact_tel, email_add, sampling) 
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt_business = $database->prepare($sql_business);
    $stmt_business->bind_param("ssssssisssssi", $itr_form_num, $business_name, $dealer_operator, $location, $in_charge, $designation, $sa_no, $sa_date, $outlet_classif, $company, $contact_tel, $email_add, $sampling);

    if ($stmt_business->execute()) {
       

        // Insert productquality data if sampling is checked
        if ($sampling == 1 && isset($_POST['code_value'])) {
            $code_values = $_POST['code_value'];
            $products = $_POST['product'];
            $ron_values = $_POST['ron_value'];
            $UGTs = $_POST['UGT'];
            $pumps = $_POST['pump'];

            $sql_product = "INSERT INTO productquality (itr_form_num, code_value, product, ron_value, UGT, pump) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt_product = $database->prepare($sql_product);

            for ($i = 0; $i < count($code_values); $i++) {
                $code_value = $code_values[$i];
                $product = $products[$i];
                $ron_value = $ron_values[$i];
                $UGT = $UGTs[$i];
                $pump = $pumps[$i];
                
                $stmt_product->bind_param("ssssss", $itr_form_num, $code_value, $product, $ron_value, $UGT, $pump);
                if (!$stmt_product->execute()) {
                    echo "Error inserting productquality data: " . $stmt_product->error;
                }
            }
            echo "New record created successfully in inserted.<br>";
            $stmt_product->close();
        }
    } else {
        echo "Error: " . $database->error;
    }

    $coc_certificate = isset($_POST['coc_certificate']) ? 1 : 0;
    $coc_posted = isset($_POST['coc_posted']) ? 1 : 0;
    $valid_permit_LGU = isset($_POST['valid_permit_LGU']) ? 1 : 0;
    $valid_permit_BFP = isset($_POST['valid_permit_BFP']) ? 1 : 0;
    $valid_permit_DENR = isset($_POST['valid_permit_DENR']) ? 1 : 0;
    $appropriate_test = isset($_POST['appropriate_test']) ? 1 : 0;
    $week_calib = isset($_POST['week_calib']) ? 1 : 0;
    $outlet_identify = isset($_POST['outlet_identify']) ? 1 : 0;
    $pdb_entry = isset($_POST['pdb_entry']) ? 1 : 0;
    $pdb_updated = isset($_POST['pdb_updated']) ? 1 : 0;
    $pdb_match = isset($_POST['pdb_match']) ? 1 : 0;
    $ron_label = isset($_POST['ron_label']) ? 1 : 0;
    $e10_label = isset($_POST['e10_label']) ? 1 : 0;
    $biofuels = isset($_POST['biofuels']) ? 1 : 0;
    $consumer_safety = isset($_POST['consumer_safety']) ? 1 : 0;
    $no_cel_warn = isset($_POST['no_cel_warn']) ? 1 : 0;
    $no_smoke_sign = isset($_POST['no_smoke_sign']) ? 1 : 0;
    $switch_eng = isset($_POST['switch_eng']) ? 1 : 0;
    $no_straddle = isset($_POST['no_straddle']) ? 1 : 0;
    $non_post_unleaded = isset($_POST['non_post_unleaded']) ? 1 : 0;
    $non_post_biodiesel = isset($_POST['non_post_biodiesel']) ? 1 : 0;
    $issue_receipt = isset($_POST['issue_receipt']) ? 1 : 0;
    $non_refuse_inspect = isset($_POST['non_refuse_inspect']) ? 1 : 0;
    $fixed_dispense = isset($_POST['fixed_dispense']) ? 1 : 0;
    $no_open_flame = isset($_POST['no_open_flame']) ? 1 : 0;
    $max_length_dispense = isset($_POST['max_length_dispense']) ? 1 : 0;
    $peso_display = isset($_POST['peso_display']) ? 1 : 0;
    $pump_island = isset($_POST['pump_island']) ? 1 : 0;
    $lane_oriented_pump = isset($_POST['lane_oriented_pump']) ? 1 : 0;
    $pump_guard = isset($_POST['pump_guard']) ? 1 : 0;
    $m_ingress = isset($_POST['m_ingress']) ? 1 : 0;
    $m_edge = isset($_POST['m_edge']) ? 1 : 0;
    $office_cashier = isset($_POST['office_cashier']) ? 1 : 0;
    $min_canopy = isset($_POST['min_canopy']) ? 1 : 0;
    $boundary_walls = isset($_POST['boundary_walls']) ? 1 : 0;
    $master_switch = isset($_POST['master_switch']) ? 1 : 0;
    $clean_rest = isset($_POST['clean_rest']) ? 1 : 0;
    $underground_storage = isset($_POST['underground_storage']) ? 1 : 0;
    $m_distance = isset($_POST['m_distance']) ? 1 : 0;
    $vent = isset($_POST['vent']) ? 1 : 0;
    $transfer_dispense = isset($_POST['transfer_dispense']) ? 1 : 0;
    $no_drum = isset($_POST['no_drum']) ? 1 : 0;
    $no_hoard = isset($_POST['no_hoard']) ? 1 : 0;
    $free_tire_press = isset($_POST['free_tire_press']) ? 1 : 0;
    $free_water = isset($_POST['free_water']) ? 1 : 0;
    $basic_mechanical = isset($_POST['basic_mechanical']) ? 1 : 0;
    $first_aid = isset($_POST['first_aid']) ? 1 : 0;
    $design_eval = isset($_POST['design_eval']) ? 1 : 0;
    $electric_eval = isset($_POST['electric_eval']) ? 1 : 0;
    $under_deliver = isset($_POST['under_deliver']) ? 1 : 0;
    
    // Text fields (remarks)
    $coc_cert_remarks = $_POST['coc_cert_remarks'];
    $valid_permit_LGU_remarks = $_POST['valid_permit_LGU_remarks'];
    $appropriate_test_remarks = $_POST['appropriate_test_remarks'];
    $pdb_match_remarks = $_POST['pdb_match_remarks'];
    $consumer_remarks = $_POST['consumer_remarks'];
    $fixed_dispense_remarks = $_POST['fixed_dispense_remarks'];
    $max_length_dispense_remarks = $_POST['max_length_dispense_remarks'];
    $pump_island_remarks = $_POST['pump_island_remarks'];
    $lane_oriented_pump_remarks = $_POST['lane_oriented_pump_remarks'];
    $m_ingress_remarks = $_POST['m_ingress_remarks'];
    $m_edge_remarks = $_POST['m_edge_remarks'];
    $min_canopy_remarks = $_POST['min_canopy_remarks'];
    $m_distance_remarks = $_POST['m_distance_remarks'];
    $vent_remarks = $_POST['vent_remarks'];
    $no_drum_remarks = $_POST['no_drum_remarks'];
    $under_deliver_remarks = $_POST['under_deliver_remarks'];
    
    // SQL insert statement
    $sql_standardcompliancechecklist = "INSERT INTO standardcompliancechecklist (
        itr_form_num,
        coc_certificate, coc_cert_remarks, coc_posted,
        valid_permit_LGU, valid_permit_BFP, valid_permit_DENR, valid_permit_LGU_remarks,
        appropriate_test, appropriate_test_remarks, week_calib,
        outlet_identify,
        pdb_entry, pdb_updated, pdb_match, pdb_match_remarks,
        ron_label, e10_label, biofuels,
        consumer_safety, no_cel_warn, consumer_remarks, no_smoke_sign, switch_eng, no_straddle,
        non_post_unleaded, non_post_biodiesel,
        issue_receipt, non_refuse_inspect,
        fixed_dispense, fixed_dispense_remarks, no_open_flame, max_length_dispense, max_length_dispense_remarks, peso_display,
        pump_island, pump_island_remarks, lane_oriented_pump, lane_oriented_pump_remarks, pump_guard,
        m_ingress, m_ingress_remarks, m_edge, m_edge_remarks,
        office_cashier, min_canopy, min_canopy_remarks, boundary_walls, master_switch, clean_rest,
        underground_storage, m_distance, m_distance_remarks, vent, vent_remarks,
        transfer_dispense, no_drum, no_drum_remarks, no_hoard,
        free_tire_press, free_water, basic_mechanical, first_aid, design_eval, electric_eval,
        under_deliver, under_deliver_remarks
    ) VALUES (
        ?, ?, ?, ?,
        ?, ?, ?, ?,
        ?, ?, ?,
        ?,
        ?, ?, ?, ?,
        ?, ?, ?,
        ?, ?, ?, ?, ?, ?,
        ?, ?,
        ?, ?,
        ?, ?, ?, ?, ?, ?,
        ?, ?, ?, ?, ?,
        ?, ?, ?, ?,
        ?, ?, ?, ?, ?, ?,
        ?, ?, ?, ?, ?,
        ?, ?, ?, ?,
        ?, ?, ?, ?, ?, ?,
        ?, ?
    )";
    
    $types = "s".                 // itr_form_num (1)
    "isi".               // coc_certificate, coc_cert_remarks, coc_posted (3)
    "iiis".              // valid_permit_LGU, BFP, DENR, LGU_remarks (4)
    "isi".               // appropriate_test, test_remarks, week_calib (3)
    "i".                 // outlet_identify (1)
    "iiis".              // pdb_entry, updated, match, match_remarks (4)
    "iii".               // ron_label, e10_label, biofuels (3)
    "iisiii".            // consumer_safety, no_cel_warn, consumer_remarks, no_smoke_sign, switch_eng, no_straddle (6)
    "ii".                // non_post_unleaded, non_post_biodiesel (2)
    "ii".                // issue_receipt, non_refuse_inspect (2)
    "isiiis".            // fixed_dispense, dispense_remarks, no_open_flame, max_length_dispense, length_remarks, peso_display (6)
    "isisi".             // pump_island, island_remarks, lane_oriented_pump, oriented_remarks, pump_guard (5)
    "isis".              // m_ingress, ingress_remarks, m_edge, edge_remarks (4)
    "iisiii".            // office_cashier, min_canopy, canopy_remarks, boundary_walls, master_switch, clean_rest (6)
    "isisi".             // underground_storage, m_distance, distance_remarks, vent, vent_remarks (5)
    "iisi".              // transfer_dispense, no_drum, drum_remarks, no_hoard (4)
    "iiiiii".            // free_tire_press, free_water, basic_mechanical, first_aid, design_eval, electric_eval (6)
    "is";   
$stmt_standardcompliancechecklist = $database->prepare($sql_standardcompliancechecklist);
if (!$stmt_standardcompliancechecklist) {
    die("SQL Error: " . $database->error);
}

$stmt_standardcompliancechecklist->bind_param(
    $types,
    $itr_form_num,
    $coc_certificate, $coc_cert_remarks, $coc_posted,
    $valid_permit_LGU, $valid_permit_BFP, $valid_permit_DENR, $valid_permit_LGU_remarks,
    $appropriate_test, $appropriate_test_remarks, $week_calib,
    $outlet_identify,
    $pdb_entry, $pdb_updated, $pdb_match, $pdb_match_remarks,
    $ron_label, $e10_label, $biofuels,
    $consumer_safety, $no_cel_warn, $consumer_remarks, $no_smoke_sign, $switch_eng, $no_straddle,
    $non_post_unleaded, $non_post_biodiesel,
    $issue_receipt, $non_refuse_inspect,
    $fixed_dispense, $fixed_dispense_remarks, $no_open_flame, $max_length_dispense, $max_length_dispense_remarks, $peso_display,
    $pump_island, $pump_island_remarks, $lane_oriented_pump, $lane_oriented_pump_remarks, $pump_guard,
    $m_ingress, $m_ingress_remarks, $m_edge, $m_edge_remarks,
    $office_cashier, $min_canopy, $min_canopy_remarks, $boundary_walls, $master_switch, $clean_rest,
    $underground_storage, $m_distance, $m_distance_remarks, $vent, $vent_remarks,
    $transfer_dispense, $no_drum, $no_drum_remarks, $no_hoard,
    $free_tire_press, $free_water, $basic_mechanical, $first_aid, $design_eval, $electric_eval,
    $under_deliver, $under_deliver_remarks
);

   // Prepare and bind
  // 1. Get values from POST request (with validation)
$itr_form_num = $_POST['itr_form_num'] ?? '';
$receipt_invoice = $_POST['receipt_invoice'] ?? '';
$supplier = $_POST['supplier'] ?? '';
$date_delivery = $_POST['date_delivery'] ?? '';
$address = $_POST['address'] ?? '';
$contact_number = $_POST['contact_number'] ?? '';

// 2. Prepare SQL statement (id is auto-increment, created_at is automatic)
$sql_supplier = $database->prepare("INSERT INTO suppliersinfo 
    (itr_form_num, receipt_invoice, supplier, date_delivery, address, contact_number) 
    VALUES (?, ?, ?, ?, ?, ?)
");

// 3. Check for preparation errors
if (!$sql_supplier) {
    die("SQL Error: " . $database->error);
}

// 4. Bind parameters
$sql_supplier->bind_param(
    "ssssss",  // All parameters are strings
    $itr_form_num, 
    $receipt_invoice, 
    $supplier, 
    $date_delivery, 
    $address, 
    $contact_number
);

// 5. Execute and check for errors
if (!$sql_supplier->execute()) {
    die("Execution Error: " . $sql_supplier->error);
}

echo "Supplier information saved successfully!";;


    $database->close();
} else {
    echo "Form not submitted.";
}
?>
