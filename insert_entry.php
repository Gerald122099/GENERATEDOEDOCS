<?php
// submit_form.php

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "itr_database";

    $database = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($database->connect_error) {
        die(json_encode(['status' => 'error', 'message' => "Connection failed: " . $database->connect_error]));
    }

    // Start transaction
    $database->begin_transaction();
    function uuidv4() {
        $data = random_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

   
    $uuidChecklist = uuidv4();
    $uuidSupplier = uuidv4();
    $uuidRetention = uuidv4();
    $uuidSampling = uuidv4();


    try {
        // Get form data
        $itr_form_num = $_POST['itr_form_num'] ?? '';
        $business_name = $_POST['business_name'] ?? '';
        $dealer_operator = $_POST['dealer_operator'] ?? '';
        $location = $_POST['location'] ?? '';
        $in_charge = $_POST['in_charge'] ?? '';
        $designation = $_POST['designation'] ?? '';
        $sa_no = isset($_POST['sa_no']) ? intval($_POST['sa_no']) : 0;
        $sa_date = $_POST['sa_date'] ?? '';
        $outlet_class = $_POST['outlet_class'] ?? '';
        $company = $_POST['company'] ?? '';
        $contact_tel = isset($_POST['contact_tel']) ? intval($_POST['contact_tel']) : 0;
        $email_add = $_POST['email_add'] ?? '';
        $sampling = isset($_POST['sampling']) ? 1 : 0;

        // Validate required fields
        if (empty($itr_form_num)) {
            throw new Exception("Error: ITR Form Number is required.");
        }

        // 1. Insert into businessinfo
        $sql_business = "INSERT INTO businessinfo (itr_form_num, business_name, dealer_operator, location, in_charge, designation, sa_no, sa_date, outlet_class, company, contact_tel, email_add, sampling) 
                         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt_business = $database->prepare($sql_business);
        if (!$stmt_business) {
            throw new Exception("Prepare failed: " . $database->error);
        }
        $stmt_business->bind_param("ssssssisssssi", $itr_form_num, $business_name, $dealer_operator, $location, $in_charge, $designation, $sa_no, $sa_date, $outlet_class, $company, $contact_tel, $email_add, $sampling);
        if (!$stmt_business->execute()) {
            throw new Exception("Execute failed: " . $stmt_business->error);
        }
        
      
        // 2. Insert into productquality if sampling
if ($sampling == 1 && isset($_POST['code_value'])) {
    $code_values = $_POST['code_value'] ?? [];
    $products = $_POST['product'] ?? [];
    $ron_values = $_POST['ron_value'] ?? [];
    $UGTs = $_POST['UGT'] ?? [];
    $pumps = $_POST['pump'] ?? [];
    
    // Verify all arrays have same length
    $count = count($code_values);
    if ($count > 0 && 
        $count === count($products) && 
        $count === count($ron_values) && 
        $count === count($UGTs) && 
        $count === count($pumps)) {
        
        // Prepare statement ONCE outside the loop
        $sql_product = "INSERT INTO productquality 
                       (id, itr_form_num, code_value, product, ron_value, UGT, pump) 
                       VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt_product = $database->prepare($sql_product);
        
        if (!$stmt_product) {
            throw new Exception("Prepare failed: " . $database->error);
        }
        
        $successCount = 0;
        for ($i = 0; $i < $count; $i++) {
            // Generate new UUID for each row
            $row_uuid = uuidv4();
            
            // Bind parameters for current row
            $stmt_product->bind_param("sssssss", 
                $row_uuid,
                $itr_form_num, 
                $code_values[$i], 
                $products[$i], 
                $ron_values[$i], 
                $UGTs[$i], 
                $pumps[$i]);
                
            if ($stmt_product->execute()) {
                $successCount++;
            } else {
                error_log("Failed to insert row $i: " . $stmt_product->error);
                // Add more detailed error logging:
                error_log("Row data: " . print_r([
                    'id' => $row_uuid,
                    'itr_form_num' => $itr_form_num,
                    'code_value' => $code_values[$i],
                    'product' => $products[$i],
                    'ron_value' => $ron_values[$i],
                    'UGT' => $UGTs[$i],
                    'pump' => $pumps[$i]
                ], true));
            }
        }
        
        $stmt_product->close();
        // Success message will be included in the final response
    } else {
        throw new Exception("Form data arrays are inconsistent");
    }
}
        // 3. Insert into standardcompliancechecklist
        $coc_cert = isset($_POST['coc_cert']) ? 1 : 0;
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
        $consume_safety = isset($_POST['consume_safety']) ? 1 : null;
        $cel_warn = isset($_POST['cel_warn']) ? 1 : 0;
        $smoke_sign = isset($_POST['smoke_sign']) ? 1 : 0;
        $switch_eng = isset($_POST['switch_eng']) ? 1 : 0;
        $straddle = isset($_POST['straddle']) ? 1 : 0;
        $post_unleaded = isset($_POST['post_unleaded']) ? 1 : 0;
        $post_biodiesel = isset($_POST['post_biodiesel']) ? 1 : 0;
        $issue_receipt = isset($_POST['issue_receipt']) ? 1 : 0;
        $non_refuse_inspect = isset($_POST['non_refuse_inspect']) ? 1 : 0;
        $non_refuse_sign = isset($_POST['non_refuse_sign']) ? 1 : 0;
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
        $coc_cert_remarks = $_POST['coc_cert_remarks'] ?? '';
        $coc_posted_remarks = $_POST['coc_posted_remarks'] ?? '';
        $valid_permit_LGU_remarks = $_POST['valid_permit_LGU_remarks'] ?? '';
        $valid_permit_BFP_remarks = $_POST['valid_permit_BFP_remarks'] ?? '';
        $valid_permit_DENR_remarks = $_POST['valid_permit_DENR_remarks'] ?? '';
        $appropriate_test_remarks = $_POST['appropriate_test_remarks'] ?? '';
        $week_calib_remarks = $_POST['week_calib_remarks'] ?? '';
        $outlet_identify_remarks = $_POST['outlet_identify_remarks'] ?? '';
        $pdb_entry_remarks = $_POST['pdb_entry_remarks'] ?? '';
        $pdb_updated_remarks = $_POST['pdb_updated_remarks'] ?? '';
        $pdb_match_remarks = $_POST['pdb_match_remarks'] ?? '';
        $ron_label_remarks = $_POST['ron_label_remarks'] ?? '';
        $e10_label_remarks = $_POST['e10_label_remarks'] ?? '';
        $biofuels_remarks = $_POST['biofuels_remarks'] ?? '';
        $cel_warn_remarks = $_POST['cel_warn_remarks'] ?? '';
        $smoke_sign_remarks = $_POST['smoke_sign_remarks'] ?? '';
        $switch_eng_remarks = $_POST['switch_eng_remarks'] ?? '';
        $straddle_remarks = $_POST['straddle_remarks'] ?? '';
        $post_unleaded_remarks = $_POST['post_unleaded_remarks'] ?? '';
        $post_biodiesel_remarks = $_POST['post_biodiesel_remarks'] ?? '';
        $issue_receipt_remarks = $_POST['issue_receipt_remarks'] ?? '';
        $non_refuse_inspect_remarks = $_POST['non_refuse_inspect_remarks'] ?? '';
        $non_refuse_sign_remarks = $_POST['non_refuse_sign_remarks'] ?? '';
        $fixed_dispense_remarks = $_POST['fixed_dispense_remarks'] ?? '';
        $no_open_flame_remarks = $_POST['no_open_flame_remarks'] ?? '';
        $max_length_dispense_remarks = $_POST['max_length_dispense_remarks'] ?? '';
        $peso_display_remarks = $_POST['peso_display_remarks'] ?? '';
        $pump_island_remarks = $_POST['pump_island_remarks'] ?? '';
        $lane_oriented_pump_remarks = $_POST['lane_oriented_pump_remarks'] ?? '';
        $pump_guard_remarks = $_POST['pump_guard_remarks'] ?? '';
        $m_ingress_remarks = $_POST['m_ingress_remarks'] ?? '';
        $m_edge_remarks = $_POST['m_edge_remarks'] ?? '';
        $office_cashier_remarks = $_POST['office_cashier_remarks'] ?? '';
        $min_canopy_remarks = $_POST['min_canopy_remarks'] ?? '';
        $boundary_walls_remarks = $_POST['boundary_walls_remarks'] ?? '';
        $master_switch_remarks = $_POST['master_switch_remarks'] ?? '';
        $clean_rest_remarks = $_POST['clean_rest_remarks'] ?? '';
        $underground_storage_remarks = $_POST['underground_storage_remarks'] ?? '';
        $m_distance_remarks = $_POST['m_distance_remarks'] ?? '';
        $vent_remarks = $_POST['vent_remarks'] ?? '';
        $transfer_dispense_remarks = $_POST['transfer_dispense_remarks'] ?? '';
        $no_drum_remarks = $_POST['no_drum_remarks'] ?? '';
        $no_hoard_remarks = $_POST['no_hoard_remarks'] ?? '';
        $free_tire_press_remarks = $_POST['free_tire_press_remarks'] ?? '';
        $free_water_remarks = $_POST['free_water_remarks'] ?? '';
        $basic_mechanical_remarks = $_POST['basic_mechanical_remarks'] ?? '';
        $first_aid_remarks = $_POST['first_aid_remarks'] ?? '';
        $design_eval_remarks = $_POST['design_eval_remarks'] ?? '';
        $electric_eval_remarks = $_POST['electric_eval_remarks'] ?? '';
        $under_deliver_remarks = $_POST['under_deliver_remarks'] ?? '';
        
        $sql_standardcompliancechecklist = "INSERT INTO standardcompliancechecklist ( id,
            itr_form_num,
            coc_cert, coc_cert_remarks, coc_posted,coc_posted_remarks,
            valid_permit_LGU, valid_permit_LGU_remarks, valid_permit_BFP, valid_permit_BFP_remarks, valid_permit_DENR,valid_permit_DENR_remarks,
            appropriate_test, appropriate_test_remarks, week_calib,week_calib_remarks,
            outlet_identify,outlet_identify_remarks,
            pdb_entry,pdb_entry_remarks, pdb_updated, pdb_updated_remarks, pdb_match, pdb_match_remarks,
            ron_label, ron_label_remarks, e10_label, e10_label_remarks, biofuels, biofuels_remarks,
            consume_safety, cel_warn, cel_warn_remarks, smoke_sign, smoke_sign_remarks, switch_eng, switch_eng_remarks, straddle, straddle_remarks,
            post_unleaded, post_unleaded_remarks, post_biodiesel, post_biodiesel_remarks,
            issue_receipt, issue_receipt_remarks, non_refuse_inspect, non_refuse_inspect_remarks, non_refuse_sign, non_refuse_sign_remarks,
            fixed_dispense, fixed_dispense_remarks, no_open_flame, no_open_flame_remarks, max_length_dispense, max_length_dispense_remarks, peso_display, peso_display_remarks,
            pump_island, pump_island_remarks, lane_oriented_pump, lane_oriented_pump_remarks, pump_guard, pump_guard_remarks,
            m_ingress, m_ingress_remarks, m_edge, m_edge_remarks,
            office_cashier, office_cashier_remarks, min_canopy, min_canopy_remarks, boundary_walls, boundary_walls_remarks, master_switch, master_switch_remarks, clean_rest, clean_rest_remarks,
            underground_storage, underground_storage_remarks, m_distance, m_distance_remarks, vent, vent_remarks,
            transfer_dispense, transfer_dispense_remarks, no_drum, no_drum_remarks, no_hoard, no_hoard_remarks,
            free_tire_press, free_tire_press_remarks, free_water, free_water_remarks, basic_mechanical, basic_mechanical_remarks, first_aid, first_aid_remarks, design_eval,  design_eval_remarks, electric_eval, electric_eval_remarks,
            under_deliver, under_deliver_remarks
        ) VALUES (
        ?, ?, ?, ?, ?,?, ?,?, ?, ?, ?,?,?,?, ?,?,?,
        ?,  ?, ?, ?, ?, ?, ?,?,?,?,?,?,?,
        ?,  ? ,?, ?, ?,
        ?, ?, ?,  ?,
         ?, ?, ?, ?, ?, ?, ?,
          ?,  ?, ?, ?,
          ?,  ?, ?, ?, ?, ?, ?,
           ?, ?, ?,
          ?,  ?, ?,
           ?, ?, ?, ?, ?, ?, ?,
           ?, ?, ?, ?, ?, ?,
            ?, ?, ?, ?,
            ?, ?, ?, ?, ?, ?,
            ?, ?, ?, ?, ?,
            ?, ?, ?, ?,
            ?, ?, ?, ?, ?, ?,
            ?, ?
        )";
        
        $types = "s"."s".                 // itr_form_num (1)
        "isis".               // coc_cert, coc_cert_remarks, coc_posted ,coc_posted_remarks(4)
        "isisis".              // valid_permit_LGU,LGU_remarks , BFP,valid_permit_BFP_remarks ,  DENR,  (4)
        "isis".               // appropriate_test, test_remarks, week_calib, week_calib_remarks(4)
        "is".                 // outlet_identify, Outlet_identify_remarks (2)
        "isisis".              // pdb_entry, pdb_enrty_remarks, updated, pdb_updated_remarks, match, match_remarks (6)
        "isisis".               // ron_label, ron_label_remarks, e10_label, e10_label_remarks, biofuels, biofuel_remarks (6)
        "iisisisis".            // consume_safety, cel_warn, cel_warn_remarks, smoke_sign,smoke_sign_remarks, switch_eng, switch_eng_remarks, straddle, straddle_remarks (10)
        "isis".                // post_unleaded, post_unleaded_remarks, post_biodiesel , post_biodiesel_remarks(4)
        "isisis".                // issue_receipt, issue_reciept_remarks, non_refuse_inspect, non_refuse_inspect_remarks , non_refuse_sign , non_refuse_sign_remarks(6)
        "isisisis".            // fixed_dispense, dispense_remarks, no_open_flame, no_open_flame_remarks, max_length_dispense, length_remarks, peso_display, peso_display_remarks (7)
        "isisis".             // pump_island, island_remarks, lane_oriented_pump, oriented_remarks, pump_guard, pump_guard_remarks(6)
        "isis".              // m_ingress, ingress_remarks, m_edge, edge_remarks (4)
        "isisisisis".            // office_cashier, office_cashier_remarks, min_canopy, canopy_remarks, boundary_walls, boundary_wall_remarks, master_switch, master_switch_remarks, clean_rest, clean_rest_remarks (10)
        "isisis".             // underground_storage, underground_storage_remarks, m_distance, distance_remarks, vent, vent_remarks (6)
        "isisis".              // transfer_dispense, transfer_dispense_remarks, no_drum, drum_remarks, no_hoard , no_hoard_remarks (5)
        "isisisisisis".            // free_tire_press,free_tire_press_remarks free_water, free_water_remarks, basic_mechanical, basic_mechanical_remarks,  first_aid, first_aid_remarks, design_eval, design_eval_remarks, electric_eval , electric_eval_remarks()
        "is";                // under_deliver, under_deliver_remarks (2)
        
        $stmt_standardcompliancechecklist = $database->prepare($sql_standardcompliancechecklist);
        if (!$stmt_standardcompliancechecklist) {
            throw new Exception("Prepare failed: " . $database->error);
        }
        
        $stmt_standardcompliancechecklist->bind_param( 
            $types,
            $uuidChecklist,
            $itr_form_num,
            $coc_cert, $coc_cert_remarks, $coc_posted,$coc_posted_remarks,
            $valid_permit_LGU, $valid_permit_LGU_remarks, $valid_permit_BFP, $valid_permit_BFP_remarks, $valid_permit_DENR, $valid_permit_DENR_remarks,
            $appropriate_test, $appropriate_test_remarks, $week_calib,$week_calib_remarks,
            $outlet_identify,$outlet_identify_remarks,
            $pdb_entry, $pdb_entry_remarks, $pdb_updated, $pdb_updated_remarks, $pdb_match, $pdb_match_remarks,
            $ron_label, $ron_label_remarks, $e10_label,  $e10_label_remarks, $biofuels,$biofuels_remarks,
            $consume_safety, $cel_warn, $cel_warn_remarks, $smoke_sign, $smoke_sign_remarks, $switch_eng, $switch_eng_remarks , $straddle, $straddle_remarks,
            $post_unleaded,$post_unleaded_remarks, $post_biodiesel, $post_biodiesel_remarks,
            $issue_receipt,$issue_receipt_remarks, $non_refuse_inspect,$non_refuse_inspect_remarks, $non_refuse_sign, $non_refuse_sign_remarks,
            $fixed_dispense, $fixed_dispense_remarks, $no_open_flame, $no_open_flame_remarks , $max_length_dispense, $max_length_dispense_remarks, $peso_display,$peso_display_remarks,
            $pump_island, $pump_island_remarks, $lane_oriented_pump, $lane_oriented_pump_remarks, $pump_guard, $pump_guard_remarks,
            $m_ingress, $m_ingress_remarks, $m_edge, $m_edge_remarks,
            $office_cashier, $office_cashier_remarks, $min_canopy, $min_canopy_remarks, $boundary_walls, $boundary_walls_remarks, $master_switch,$master_switch_remarks, $clean_rest, $clean_rest_remarks,
            $underground_storage, $underground_storage_remarks, $m_distance, $m_distance_remarks, $vent, $vent_remarks,
            $transfer_dispense, $transfer_dispense_remarks, $no_drum, $no_drum_remarks, $no_hoard,$no_hoard_remarks,
            $free_tire_press, $free_tire_press_remarks, $free_water, $free_water_remarks, $basic_mechanical, $basic_mechanical_remarks, $first_aid, $first_aid_remarks, $design_eval, $design_eval_remarks, $electric_eval, $electric_eval_remarks,
            $under_deliver, $under_deliver_remarks
        );
        
        if (!$stmt_standardcompliancechecklist->execute()) {
            throw new Exception("Execute failed: " . $stmt_standardcompliancechecklist->error);
        }

        // 4. Insert into suppliersinfo
        
        $receipt_invoice = $_POST['receipt_invoice'] ?? '';
        $supplier = $_POST['supplier'] ?? '';
        $date_deliver = $_POST['date_deliver'] ?? '';
        $address = $_POST['address'] ?? '';
        $contact_num = $_POST['contact_num'] ?? '';
        
        $sql_supplier = "INSERT INTO suppliersinfo 
            (id, itr_form_num, receipt_invoice, supplier, date_deliver, address, contact_num) 
            VALUES (?,?, ?, ?, ?, ?, ?)";
        $stmt_supplier = $database->prepare($sql_supplier);
        if (!$stmt_supplier) {
            throw new Exception("Prepare failed: " . $database->error);
        }
       
        $stmt_supplier->bind_param("sssssss",   $uuidSupplier, $itr_form_num, $receipt_invoice, $supplier, $date_deliver, $address, $contact_num);
        if (!$stmt_supplier->execute()) {
            throw new Exception("Execute failed: " . $stmt_supplier->error);
        }


        //Duplicate retention and appropriate
        $retention = isset($_POST['duplicate_retention_samples']) ? 1 : 0;
        $appropriate = isset($_POST['appropriate_sampling']) ? 1 : 0;

        // Prepare SQL statement
        $stmt_retention = $database->prepare("INSERT INTO productqualitycont 
        (id, itr_form_num, duplicate_retention_samples, appropriate_sampling) 
        VALUES (?, ?, ?, ?)");

        if ($stmt_retention === false) {
            throw new Exception("Prepare failed: " . $database->error);
        }

        // Bind parameters
        $stmt_retention->bind_param("ssii", 
            $uuidRetention,
            $itr_form_num,
            $retention,
            $appropriate
        );

        // Execute the statement
        if (!$stmt_retention->execute()) {
            throw new Exception("Execute failed: " . $stmt_retention->error);
        }


      // 5. Insert into generalremarks table
$user_gen_remarks = $_POST['user_gen_remarks'] ?? '';
$action_required = $_POST['action_required'] ?? '';

// Prepare SQL statement for general remarks
$stmt_remarks = $database->prepare("INSERT INTO summaryremarks 
    (id, itr_form_num, user_gen_remarks, action_required) 
    VALUES (?, ?, ?,?)");

if ($stmt_remarks === false) {
    throw new Exception("Prepare failed for remarks: " . $database->error);
}

// Generate UUID for remarks
$uuidRemarks = uuidv4();

// Bind parameters
$stmt_remarks->bind_param("ssss", 
    $uuidRemarks,
    $itr_form_num,
    $user_gen_remarks,
    $action_required
);

// Execute the statement
if (!$stmt_remarks->execute()) {
    throw new Exception("Execute failed for remarks: " . $stmt_remarks->error);
}

// Don't forget to close the statement
$stmt_remarks->close();

        // Commit transaction if all succeeded
        $database->commit();
        
        // Enhanced success message for SweetAlert
        $successMessage = [
            'status' => 'success', 
            'message' => 'ITR Form ' . $itr_form_num . ' has been successfully saved!',
            'title' => 'Success',
            'icon' => 'success',
            'itr_num' => $itr_form_num
        ];
        
        echo json_encode($successMessage);

    } catch (Exception $e) {
        // Rollback transaction on any error
        $database->rollback();
        
        // Enhanced error message for SweetAlert
        $errorMessage = [
            'status' => 'error',
            'message' => $e->getMessage(),
            'title' => 'Error',
            'icon' => 'error'
        ];
        
        echo json_encode($errorMessage);
    } finally {
        // Close connection
        $database->close();
    }
} else {
    // Form not submitted error for SweetAlert
    $notSubmittedError = [
        'status' => 'error',
        'message' => 'The form was not submitted properly. Please try again.',
        'title' => 'Submission Error',
        'icon' => 'warning'
    ];
    
    echo json_encode($notSubmittedError);
}