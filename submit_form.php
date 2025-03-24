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

       // Get form data
       $coc_certificate = isset($_POST['coc_certificate']) ? 1 : 0;
       $coc_cert_remarks = $_POST['coc_cert_remarks'] ?? '';
       $coc_posted = isset($_POST['coc_posted']) ? 1 : 0;
       $coc_posted_remarks = $_POST['coc_posted_remarks'] ?? '';
       $valid_permits = $_POST['valid_permits'] ?? '';
       $valid_permit_LGU = isset($_POST['valid_permit_LGU']) ? 1 : 0;
       $valid_permit_LGU_remarks = $_POST['valid_permit_LGU_remarks'] ?? '';
       $valid_permit_BFP = isset($_POST['valid_permit_BFP']) ? 1 : 0;
       $valid_permit_BFP_remarks = $_POST['valid_permit_BFP_remarks'] ?? '';
       $valid_permit_DENR = isset($_POST['valid_permit_DENR']) ? 1 : 0;
       $valid_permit_DENR_remarks = $_POST['valid_permit_DENR_remarks'] ?? '';
       $appropriate_test = isset($_POST['appropriate_test']) ? 1 : 0;
       $appropriate_test_remarks = $_POST['appropriate_test_remarks'] ?? '';
       $week_calib = isset($_POST['week_calib']) ? 1 : 0;
       $week_calib_remarks = $_POST['week_calib_remarks'] ?? '';
       $outlet_identify = isset($_POST['outlet_identify']) ? 1 : 0;
       $outlet_identify_remarks = $_POST['outlet_identify_remarks'] ?? '';
       $price_display = isset($_POST['price_display']) ? 1 : 0;
       $price_display_remarks = $_POST['price_display_remarks'] ?? '';
       $pdb_entry = isset($_POST['pdb_entry']) ? 1 : 0;
       $pdb_entry_remarks = $_POST['pdb_entry_remarks'] ?? '';
       $pdb_updated = isset($_POST['pdb_updated']) ? 1 : 0;
       $pdb_updated_remarks = $_POST['pdb_updated_remarks'] ?? '';
       $pdb_match = isset($_POST['pdb_match']) ? 1 : 0;
       $pdb_match_remarks = $_POST['pdb_match_remarks'] ?? '';
       $ron_label = isset($_POST['ron_label']) ? 1 : 0;
       $ron_label_remarks = $_POST['ron_label_remarks'] ?? '';
       $e10_label = isset($_POST['e10_label']) ? 1 : 0;
       $e10_label_remarks = $_POST['e10_label_remarks'] ?? '';
       $biofuels = isset($_POST['biofuels']) ? 1 : 0;
       $biofuels_remarks = $_POST['biofuels_remarks'] ?? '';
       $consumer_safety = isset($_POST['consumer_safety']) ? 1 : 0;
       $consume_safety_remarks = $_POST['consume_safety_remarks'] ?? '';
       $no_cel_warn = isset($_POST['no_cel_warn']) ? 1 : 0;
       $no_cel_warn_remarks = $_POST['no_cel_warn_remarks'] ?? '';
       $no_smoke_sign = isset($_POST['no_smoke_sign']) ? 1 : 0;
       $no_smoke_sign_remarks = $_POST['no_smoke_sign_remarks'] ?? '';
       $switch_eng = isset($_POST['switch_eng']) ? 1 : 0;
       $switch_eng_remarks = $_POST['switch_eng_remarks'] ?? '';
       $no_straddle = isset($_POST['no_straddle']) ? 1 : 0;
       $no_straddle_remarks = $_POST['no_straddle_remarks'] ?? '';
       $non_post_unleaded = isset($_POST['non_post_unleaded']) ? 1 : 0;
       $non_post_unleaded_remarks = $_POST['non_post_unleaded_remarks'] ?? '';
       $non_post_biodiesel = isset($_POST['non_post_biodiesel']) ? 1 : 0;
       $non_post_biodiesel_remarks = $_POST['non_post_biodiesel_remarks'] ?? '';
       $issue_receipt = isset($_POST['issue_receipt']) ? 1 : 0;
       $issue_receipt_remarks = $_POST['issue_receipt_remarks'] ?? '';
       $non_refuse_inspect = isset($_POST['non_refuse_inspect']) ? 1 : 0;
       $non_refuse_inspect_remarks = $_POST['non_refuse_inspect_remarks'] ?? '';
       $fixed_dispense = isset($_POST['fixed_dispense']) ? 1 : 0;
       $fixed_dispense_remarks = $_POST['fixed_dispense_remarks'] ?? '';
       $no_open_flame = isset($_POST['no_open_flame']) ? 1 : 0;
       $no_open_flame_remarks = $_POST['no_open_flame_remarks'] ?? '';
       $max_length_dispense = isset($_POST['max_length_dispense']) ? 1 : 0;
       $max_length_dispense_remarks = $_POST['max_length_dispense_remarks'] ?? '';
       $peso_display = isset($_POST['peso_display']) ? 1 : 0;
       $peso_display_remarks = $_POST['peso_display_remarks'] ?? '';
       $pump_island = isset($_POST['pump_island']) ? 1 : 0;
       $pump_island_remarks = $_POST['pump_island_remarks'] ?? '';
       $lane_oriented_pump = isset($_POST['lane_oriented_pump']) ? 1 : 0;
       $lane_oriented_pump_remarks = $_POST['lane_oriented_pump_remarks'] ?? '';
       $pump_guard = isset($_POST['pump_guard']) ? 1 : 0;
       $pump_guard_remarks = $_POST['pump_guard_remarks'] ?? '';
       $m_ingress = isset($_POST['m_ingress']) ? 1 : 0;
       $m_ingress_remarks = $_POST['m_ingress_remarks'] ?? '';
       $m_edge = isset($_POST['m_edge']) ? 1 : 0;
       $m_edge_remarks = $_POST['m_edge_remarks'] ?? '';
       $office_cashier = isset($_POST['office_cashier']) ? 1 : 0;
       $office_cashier_remarks = $_POST['office_cashier_remarks'] ?? '';
       $min_canopy = isset($_POST['min_canopy']) ? 1 : 0;
       $min_canopy_remarks = $_POST['min_canopy_remarks'] ?? '';
       $boundary_walls = isset($_POST['boundary_walls']) ? 1 : 0;
       $boundary_walls_remarks = $_POST['boundary_walls_remarks'] ?? '';
       $master_switch = isset($_POST['master_switch']) ? 1 : 0;
       $master_switch_remarks = $_POST['master_switch_remarks'] ?? '';
       $clean_rest = isset($_POST['clean_rest']) ? 1 : 0;
       $clean_rest_remarks = $_POST['clean_rest_remarks'] ?? '';
       $underground_storage = isset($_POST['underground_storage']) ? 1 : 0;
       $underground_storage_remarks = $_POST['underground_storage_remarks'] ?? '';
       $m_distance = isset($_POST['m_distance']) ? 1 : 0;
       $m_distance_remarks = $_POST['m_distance_remarks'] ?? '';
       $vent = isset($_POST['vent']) ? 1 : 0;
       $vent_remarks = $_POST['vent_remarks'] ?? '';
       $transfer_dispense = isset($_POST['transfer_dispense']) ? 1 : 0;
       $transfer_dispense_remarks = $_POST['transfer_dispense_remarks'] ?? '';
       $no_drum = isset($_POST['no_drum']) ? 1 : 0;
       $no_drum_remarks = $_POST['no_drum_remarks'] ?? '';
       $no_hoard = isset($_POST['no_hoard']) ? 1 : 0;
       $no_hoard_remarks = $_POST['no_hoard_remarks'] ?? '';
       $free_tire_press = isset($_POST['free_tire_press']) ? 1 : 0;
       $free_tire_press_remarks = $_POST['free_tire_press_remarks'] ?? '';
       $free_water = isset($_POST['free_water']) ? 1 : 0;
       $free_water_remarks = $_POST['free_water_remarks'] ?? '';
       $basic_mechanical = isset($_POST['basic_mechanical']) ? 1 : 0;
       $basic_mechanical_remarks = $_POST['basic_mechanical_remarks'] ?? '';
       $first_aid = isset($_POST['first_aid']) ? 1 : 0;
       $first_aid_remarks = $_POST['first_aid_remarks'] ?? '';
       $design_eval = isset($_POST['design_eval']) ? 1 : 0;
       $design_eval_remarks = $_POST['design_eval_remarks'] ?? '';
       $electric_eval = isset($_POST['electric_eval']) ? 1 : 0;
       $electric_eval_remarks = $_POST['electric_eval_remarks'] ?? '';
       $under_deliver = isset($_POST['under_deliver']) ? 1 : 0;
       $under_deliver_remarks = $_POST['under_deliver_remarks'] ?? '';
   // Prepare and execute the INSERT statement for standardcompliancechecklist
   $sql_standardcompliancechecklist = "INSERT INTO standardcompliancechecklist ( 
     coc_certificate, coc_cert_remarks,
      coc_posted, coc_posted_remarks,
     valid_permits, 
     valid_permit_LGU, valid_permit_LGU_remarks,
      valid_permit_BFP, valid_permit_BFP_remarks,
     valid_permit_DENR, valid_permit_DENR_remarks,
      appropriate_test, appropriate_test_remarks,
     week_calib, week_calib_remarks,
      outlet_identify, outlet_identify_remarks,
     price_display, price_display_remarks,
      pdb_entry, pdb_entry_remarks,
     pdb_updated, pdb_updated_remarks,
      pdb_match, pdb_match_remarks,
     ron_label, ron_label_remarks,
      e10_label, e10_label_remarks,
     biofuels, biofuels_remarks,
      consumer_safety, consume_safety_remarks,
     no_cel_warn, no_cel_warn_remarks,
      no_smoke_sign, no_smoke_sign_remarks,
     switch_eng, switch_eng_remarks,
    no_straddle, no_straddle_remarks,
     non_post_unleaded, non_post_unleaded_remarks,
      non_post_biodiesel, non_post_biodiesel_remarks,
     issue_receipt, issue_receipt_remarks,
      non_refuse_inspect, non_refuse_inspect_remarks,
     fixed_dispense, fixed_dispense_remarks,
      no_open_flame, no_open_flame_remarks,
     max_length_dispense, max_length_dispense_remarks,
      peso_display, peso_display_remarks,
     pump_island, pump_island_remarks,
      lane_oriented_pump, lane_oriented_pump_remarks,
     pump_guard, pump_guard_remarks,
      m_ingress, m_ingress_remarks,
     m_edge, m_edge_remarks, office_cashier,
      office_cashier_remarks,
     min_canopy, min_canopy_remarks,
      boundary_walls, boundary_walls_remarks,
     master_switch, master_switch_remarks,
      clean_rest, clean_rest_remarks,
     underground_storage, underground_storage_remarks,
      m_distance, m_distance_remarks,
     vent, vent_remarks,
      transfer_dispense, transfer_dispense_remarks,
     no_drum, no_drum_remarks,
      no_hoard, no_hoard_remarks,
     free_tire_press, free_tire_press_remarks,
      free_water, free_water_remarks,
     basic_mechanical, basic_mechanical_remarks,
      first_aid, first_aid_remarks,
     design_eval, design_eval_remarks,
      electric_eval, electric_eval_remarks,
     under_deliver, under_deliver_remarks
 ) VALUES (
    ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
 )";
   $stmt_standard_compliance_checklist = $database->prepare($sql_standardcompliancechecklist);
   if (!$stmt_standard_compliance_checklist) {
       die("Error preparing statement: " . $database->error);
   }
     $stmt_standard_compliance_checklist->bind_param(
       "ississsississississississississississississississississississississississississississississississississississississississississississississississississississississississississississississississississississississississississississississississississississ",
       $coc_certificate, $coc_cert_remarks, $coc_posted, $coc_posted_remarks,
       $valid_permits, $valid_permit_LGU, $valid_permit_LGU_remarks, $valid_permit_BFP, $valid_permit_BFP_remarks,
       $valid_permit_DENR, $valid_permit_DENR_remarks, $appropriate_test, $appropriate_test_remarks, $week_calib, $week_calib_remarks,
       $outlet_identify, $outlet_identify_remarks, $price_display, $price_display_remarks, $pdb_entry, $pdb_entry_remarks,
       $pdb_updated, $pdb_updated_remarks, $pdb_match, $pdb_match_remarks, $ron_label, $ron_label_remarks,
       $e10_label, $e10_label_remarks, $biofuels, $biofuels_remarks, $consumer_safety, $consume_safety_remarks,
       $no_cel_warn, $no_cel_warn_remarks, $no_smoke_sign, $no_smoke_sign_remarks, $switch_eng, $switch_eng_remarks,
       $no_straddle, $no_straddle_remarks, $non_post_unleaded, $non_post_unleaded_remarks, $non_post_biodiesel, $non_post_biodiesel_remarks,
       $issue_receipt, $issue_receipt_remarks, $non_refuse_inspect, $non_refuse_inspect_remarks, $fixed_dispense, $fixed_dispense_remarks,
       $no_open_flame, $no_open_flame_remarks, $max_length_dispense, $max_length_dispense_remarks, $peso_display, $peso_display_remarks,
       $pump_island, $pump_island_remarks, $lane_oriented_pump, $lane_oriented_pump_remarks, $pump_guard, $pump_guard_remarks,
       $m_ingress, $m_ingress_remarks, $m_edge, $m_edge_remarks, $office_cashier, $office_cashier_remarks,
       $min_canopy, $min_canopy_remarks, $boundary_walls, $boundary_walls_remarks, $master_switch, $master_switch_remarks,
       $clean_rest, $clean_rest_remarks, $underground_storage, $underground_storage_remarks, $m_distance, $m_distance_remarks,
       $vent, $vent_remarks, $transfer_dispense, $transfer_dispense_remarks, $no_drum, $no_drum_remarks,
       $no_hoard, $no_hoard_remarks, $free_tire_press, $free_tire_press_remarks, $free_water, $free_water_remarks,
       $basic_mechanical, $basic_mechanical_remarks, $first_aid, $first_aid_remarks, $design_eval, $design_eval_remarks,
       $electric_eval, $electric_eval_remarks, $under_deliver, $under_deliver_remarks
   );
   
     

    $database->close();
} else {
    echo "Form not submitted.";
}
?>
