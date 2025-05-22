<?php
// save_record.php

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
        die("Connection failed: " . $database->connect_error);
    }

    // Start transaction
    $database->begin_transaction();
    
    // Check if we're updating an existing record
    $isUpdate = isset($_POST['existing_itr_num']) && !empty($_POST['existing_itr_num']);
    $itr_form_num = $isUpdate ? $_POST['existing_itr_num'] : $_POST['itr_form_num'];

    try {
        // 1. Handle businessinfo (update or insert)
        $sql_business = $isUpdate ? 
            "UPDATE businessinfo SET 
                business_name = ?, dealer_operator = ?, location = ?, in_charge = ?, 
                designation = ?, sa_no = ?, sa_date = ?, outlet_class = ?, 
                company = ?, contact_tel = ?, email_add = ?, sampling = ? 
             WHERE itr_form_num = ?" :
            "INSERT INTO businessinfo 
                (itr_form_num, business_name, dealer_operator, location, in_charge, 
                 designation, sa_no, sa_date, outlet_class, company, contact_tel, email_add, sampling) 
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
             
        $stmt_business = $database->prepare($sql_business);
        
        // Get all business info values
        $business_values = [
            $_POST['business_name'] ?? '',
            $_POST['dealer_operator'] ?? '',
            $_POST['location'] ?? '',
            $_POST['in_charge'] ?? '',
            $_POST['designation'] ?? '',
            isset($_POST['sa_no']) ? intval($_POST['sa_no']) : 0,
            $_POST['sa_date'] ?? '',
            $_POST['outlet_class'] ?? '',
            $_POST['company'] ?? '',
            isset($_POST['contact_tel']) ? intval($_POST['contact_tel']) : 0,
            $_POST['email_add'] ?? '',
            isset($_POST['sampling']) ? 1 : 0
        ];
        
        if ($isUpdate) {
            $business_values[] = $itr_form_num;
        } else {
            array_unshift($business_values, $itr_form_num);
        }
        
        $types = str_repeat('s', count($business_values));
        $stmt_business->bind_param($types, ...$business_values);
        
        if (!$stmt_business->execute()) {
            throw new Exception("Business info " . ($isUpdate ? "update" : "insert") . " failed: " . $stmt_business->error);
        }
        
        // 2. Handle standardcompliancechecklist (update or insert)
        // Generate standard compliance fields
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
        
        // Generate UUID for checklist if it's a new record
        $uuidChecklist = $isUpdate ? null : uniqid('checklist_', true);
        
        // If updating, check if a record exists and decide whether to update or insert
        if ($isUpdate) {
            // Check if standardcompliancechecklist record exists
            $check_exists = $database->prepare("SELECT COUNT(*) FROM standardcompliancechecklist WHERE itr_form_num = ?");
            $check_exists->bind_param("s", $itr_form_num);
            $check_exists->execute();
            $check_exists->bind_result($record_count);
            $check_exists->fetch();
            $check_exists->close();
            
            if ($record_count > 0) {
                // Update existing record
                $sql_standardcompliancechecklist = "UPDATE standardcompliancechecklist SET 
                    coc_cert = ?, coc_cert_remarks = ?, coc_posted = ?, coc_posted_remarks = ?,
                    valid_permit_LGU = ?, valid_permit_LGU_remarks = ?, valid_permit_BFP = ?, valid_permit_BFP_remarks = ?, 
                    valid_permit_DENR = ?, valid_permit_DENR_remarks = ?,
                    appropriate_test = ?, appropriate_test_remarks = ?, week_calib = ?, week_calib_remarks = ?,
                    outlet_identify = ?, outlet_identify_remarks = ?,
                    pdb_entry = ?, pdb_entry_remarks = ?, pdb_updated = ?, pdb_updated_remarks = ?, 
                    pdb_match = ?, pdb_match_remarks = ?,
                    ron_label = ?, ron_label_remarks = ?, e10_label = ?, e10_label_remarks = ?, 
                    biofuels = ?, biofuels_remarks = ?,
                    consume_safety = ?, cel_warn = ?, cel_warn_remarks = ?, smoke_sign = ?, smoke_sign_remarks = ?, 
                    switch_eng = ?, switch_eng_remarks = ?, straddle = ?, straddle_remarks = ?,
                    post_unleaded = ?, post_unleaded_remarks = ?, post_biodiesel = ?, post_biodiesel_remarks = ?,
                    issue_receipt = ?, issue_receipt_remarks = ?, non_refuse_inspect = ?, non_refuse_inspect_remarks = ?, 
                    non_refuse_sign = ?, non_refuse_sign_remarks = ?,
                    fixed_dispense = ?, fixed_dispense_remarks = ?, no_open_flame = ?, no_open_flame_remarks = ?, 
                    max_length_dispense = ?, max_length_dispense_remarks = ?, peso_display = ?, peso_display_remarks = ?,
                    pump_island = ?, pump_island_remarks = ?, lane_oriented_pump = ?, lane_oriented_pump_remarks = ?, 
                    pump_guard = ?, pump_guard_remarks = ?,
                    m_ingress = ?, m_ingress_remarks = ?, m_edge = ?, m_edge_remarks = ?,
                    office_cashier = ?, office_cashier_remarks = ?, min_canopy = ?, min_canopy_remarks = ?, 
                    boundary_walls = ?, boundary_walls_remarks = ?, master_switch = ?, master_switch_remarks = ?, 
                    clean_rest = ?, clean_rest_remarks = ?,
                    underground_storage = ?, underground_storage_remarks = ?, m_distance = ?, m_distance_remarks = ?, 
                    vent = ?, vent_remarks = ?,
                    transfer_dispense = ?, transfer_dispense_remarks = ?, no_drum = ?, no_drum_remarks = ?, 
                    no_hoard = ?, no_hoard_remarks = ?,
                    free_tire_press = ?, free_tire_press_remarks = ?, free_water = ?, free_water_remarks = ?, 
                    basic_mechanical = ?, basic_mechanical_remarks = ?, first_aid = ?, first_aid_remarks = ?, 
                    design_eval = ?, design_eval_remarks = ?, electric_eval = ?, electric_eval_remarks = ?,
                    under_deliver = ?, under_deliver_remarks = ?
                WHERE itr_form_num = ?";

                $types = "isisisisisi"."sisisisisi"."sisisisii"."sisisisisisi"."sisisisisisi"."sisisisisis"."isisisisisisi"."sisisisisisis";

                $params = [
                    $coc_cert, $coc_cert_remarks, $coc_posted, $coc_posted_remarks,
                    $valid_permit_LGU, $valid_permit_LGU_remarks, $valid_permit_BFP, $valid_permit_BFP_remarks, 
                    $valid_permit_DENR, $valid_permit_DENR_remarks,
                    $appropriate_test, $appropriate_test_remarks, $week_calib, $week_calib_remarks,
                    $outlet_identify, $outlet_identify_remarks,
                    $pdb_entry, $pdb_entry_remarks, $pdb_updated, $pdb_updated_remarks, 
                    $pdb_match, $pdb_match_remarks,
                    $ron_label, $ron_label_remarks, $e10_label, $e10_label_remarks, 
                    $biofuels, $biofuels_remarks,
                    $consume_safety, $cel_warn, $cel_warn_remarks, $smoke_sign, $smoke_sign_remarks, 
                    $switch_eng, $switch_eng_remarks, $straddle, $straddle_remarks,
                    $post_unleaded, $post_unleaded_remarks, $post_biodiesel, $post_biodiesel_remarks,
                    $issue_receipt, $issue_receipt_remarks, $non_refuse_inspect, $non_refuse_inspect_remarks, 
                    $non_refuse_sign, $non_refuse_sign_remarks,
                    $fixed_dispense, $fixed_dispense_remarks, $no_open_flame, $no_open_flame_remarks, 
                    $max_length_dispense, $max_length_dispense_remarks, $peso_display, $peso_display_remarks,
                    $pump_island, $pump_island_remarks, $lane_oriented_pump, $lane_oriented_pump_remarks, 
                    $pump_guard, $pump_guard_remarks,
                    $m_ingress, $m_ingress_remarks, $m_edge, $m_edge_remarks,
                    $office_cashier, $office_cashier_remarks, $min_canopy, $min_canopy_remarks, 
                    $boundary_walls, $boundary_walls_remarks, $master_switch, $master_switch_remarks, 
                    $clean_rest, $clean_rest_remarks,
                    $underground_storage, $underground_storage_remarks, $m_distance, $m_distance_remarks, 
                    $vent, $vent_remarks,
                    $transfer_dispense, $transfer_dispense_remarks, $no_drum, $no_drum_remarks, 
                    $no_hoard, $no_hoard_remarks,
                    $free_tire_press, $free_tire_press_remarks, $free_water, $free_water_remarks, 
                    $basic_mechanical, $basic_mechanical_remarks, $first_aid, $first_aid_remarks, 
                    $design_eval, $design_eval_remarks, $electric_eval, $electric_eval_remarks,
                    $under_deliver, $under_deliver_remarks,
                    $itr_form_num
                ];
            } else {
                // Insert a new record for an existing ITR
                $sql_standardcompliancechecklist = "INSERT INTO standardcompliancechecklist (
                    itr_form_num,
                    coc_cert, coc_cert_remarks, coc_posted, coc_posted_remarks,
                    valid_permit_LGU, valid_permit_LGU_remarks, valid_permit_BFP, valid_permit_BFP_remarks, 
                    valid_permit_DENR, valid_permit_DENR_remarks,
                    appropriate_test, appropriate_test_remarks, week_calib, week_calib_remarks,
                    outlet_identify, outlet_identify_remarks,
                    pdb_entry, pdb_entry_remarks, pdb_updated, pdb_updated_remarks, 
                    pdb_match, pdb_match_remarks,
                    ron_label, ron_label_remarks, e10_label, e10_label_remarks, 
                    biofuels, biofuels_remarks,
                    consume_safety, cel_warn, cel_warn_remarks, smoke_sign, smoke_sign_remarks, 
                    switch_eng, switch_eng_remarks, straddle, straddle_remarks,
                    post_unleaded, post_unleaded_remarks, post_biodiesel, post_biodiesel_remarks,
                    issue_receipt, issue_receipt_remarks, non_refuse_inspect, non_refuse_inspect_remarks, 
                    non_refuse_sign, non_refuse_sign_remarks,
                    fixed_dispense, fixed_dispense_remarks, no_open_flame, no_open_flame_remarks, 
                    max_length_dispense, max_length_dispense_remarks, peso_display, peso_display_remarks,
                    pump_island, pump_island_remarks, lane_oriented_pump, lane_oriented_pump_remarks, 
                    pump_guard, pump_guard_remarks,
                    m_ingress, m_ingress_remarks, m_edge, m_edge_remarks,
                    office_cashier, office_cashier_remarks, min_canopy, min_canopy_remarks, 
                    boundary_walls, boundary_walls_remarks, master_switch, master_switch_remarks, 
                    clean_rest, clean_rest_remarks,
                    underground_storage, underground_storage_remarks, m_distance, m_distance_remarks, 
                    vent, vent_remarks,
                    transfer_dispense, transfer_dispense_remarks, no_drum, no_drum_remarks, 
                    no_hoard, no_hoard_remarks,
                    free_tire_press, free_tire_press_remarks, free_water, free_water_remarks, 
                    basic_mechanical, basic_mechanical_remarks, first_aid, first_aid_remarks, 
                    design_eval, design_eval_remarks, electric_eval, electric_eval_remarks,
                    under_deliver, under_deliver_remarks
                ) VALUES (
                    ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
                    ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
                    ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
                    ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
                )";

                $types = "sisisisisisi"."sisisisisi"."sisisisii"."sisisisisisi"."sisisisisisi"."sisisisisis"."isisisisisisi"."sisisis";

                $params = [
                    $itr_form_num,
                    $coc_cert, $coc_cert_remarks, $coc_posted, $coc_posted_remarks,
                    $valid_permit_LGU, $valid_permit_LGU_remarks, $valid_permit_BFP, $valid_permit_BFP_remarks, 
                    $valid_permit_DENR, $valid_permit_DENR_remarks,
                    $appropriate_test, $appropriate_test_remarks, $week_calib, $week_calib_remarks,
                    $outlet_identify, $outlet_identify_remarks,
                    $pdb_entry, $pdb_entry_remarks, $pdb_updated, $pdb_updated_remarks, 
                    $pdb_match, $pdb_match_remarks,
                    $ron_label, $ron_label_remarks, $e10_label, $e10_label_remarks, 
                    $biofuels, $biofuels_remarks,
                    $consume_safety, $cel_warn, $cel_warn_remarks, $smoke_sign, $smoke_sign_remarks, 
                    $switch_eng, $switch_eng_remarks, $straddle, $straddle_remarks, 
                    $post_unleaded, $post_unleaded_remarks, $post_biodiesel, $post_biodiesel_remarks,
                    $issue_receipt, $issue_receipt_remarks, $non_refuse_inspect, $non_refuse_inspect_remarks, 
                    $non_refuse_sign, $non_refuse_sign_remarks,
                    $fixed_dispense, $fixed_dispense_remarks, $no_open_flame, $no_open_flame_remarks, 
                    $max_length_dispense, $max_length_dispense_remarks, $peso_display, $peso_display_remarks,
                    $pump_island, $pump_island_remarks, $lane_oriented_pump, $lane_oriented_pump_remarks, 
                    $pump_guard, $pump_guard_remarks,
                    $m_ingress, $m_ingress_remarks, $m_edge, $m_edge_remarks,
                    $office_cashier, $office_cashier_remarks, $min_canopy, $min_canopy_remarks, 
                    $boundary_walls, $boundary_walls_remarks, $master_switch, $master_switch_remarks, 
                    $clean_rest, $clean_rest_remarks,
                    $underground_storage, $underground_storage_remarks, $m_distance, $m_distance_remarks, 
                    $vent, $vent_remarks,
                    $transfer_dispense, $transfer_dispense_remarks, $no_drum, $no_drum_remarks, 
                    $no_hoard, $no_hoard_remarks,
                    $free_tire_press, $free_tire_press_remarks, $free_water, $free_water_remarks, 
                    $basic_mechanical, $basic_mechanical_remarks, $first_aid, $first_aid_remarks, 
                    $design_eval, $design_eval_remarks, $electric_eval, $electric_eval_remarks,
                    $under_deliver, $under_deliver_remarks
                ];
            }
        } else {
            // Insert new record with id field
            $sql_standardcompliancechecklist = "INSERT INTO standardcompliancechecklist (
                id, itr_form_num,
                coc_cert, coc_cert_remarks, coc_posted, coc_posted_remarks,
                valid_permit_LGU, valid_permit_LGU_remarks, valid_permit_BFP, valid_permit_BFP_remarks, 
                valid_permit_DENR, valid_permit_DENR_remarks,
                appropriate_test, appropriate_test_remarks, week_calib, week_calib_remarks,
                outlet_identify, outlet_identify_remarks,
                pdb_entry, pdb_entry_remarks, pdb_updated, pdb_updated_remarks, 
                pdb_match, pdb_match_remarks,
                ron_label, ron_label_remarks, e10_label, e10_label_remarks, 
                biofuels, biofuels_remarks,
                consume_safety, cel_warn, cel_warn_remarks, smoke_sign, smoke_sign_remarks, 
                switch_eng, switch_eng_remarks, straddle, straddle_remarks,
                post_unleaded, post_unleaded_remarks, post_biodiesel, post_biodiesel_remarks,
                issue_receipt, issue_receipt_remarks, non_refuse_inspect, non_refuse_inspect_remarks, 
                non_refuse_sign, non_refuse_sign_remarks,
                fixed_dispense, fixed_dispense_remarks, no_open_flame, no_open_flame_remarks, 
                max_length_dispense, max_length_dispense_remarks, peso_display, peso_display_remarks,
                pump_island, pump_island_remarks, lane_oriented_pump, lane_oriented_pump_remarks, 
                pump_guard, pump_guard_remarks,
                m_ingress, m_ingress_remarks, m_edge, m_edge_remarks,
                office_cashier, office_cashier_remarks, min_canopy, min_canopy_remarks, 
                boundary_walls, boundary_walls_remarks, master_switch, master_switch_remarks, 
                clean_rest, clean_rest_remarks,
                underground_storage, underground_storage_remarks, m_distance, m_distance_remarks, 
                vent, vent_remarks,
                transfer_dispense, transfer_dispense_remarks, no_drum, no_drum_remarks, 
                no_hoard, no_hoard_remarks,
                free_tire_press, free_tire_press_remarks, free_water, free_water_remarks, 
                basic_mechanical, basic_mechanical_remarks, first_aid, first_aid_remarks, 
                design_eval, design_eval_remarks, electric_eval, electric_eval_remarks,
                under_deliver, under_deliver_remarks
            ) VALUES (
                ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
                ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
                ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
                ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
            )";

            $types = "ssisisisisisi"."sisisisisi"."sisisisii"."sisisisisisi"."sisisisisisi"."sisisisisis"."isisisisisisi"."sisisis";

            $params = [
                $uuidChecklist, $itr_form_num,
                $coc_cert, $coc_cert_remarks, $coc_posted, $coc_posted_remarks,
                $valid_permit_LGU, $valid_permit_LGU_remarks, $valid_permit_BFP, $valid_permit_BFP_remarks, 
                $valid_permit_DENR, $valid_permit_DENR_remarks,
                $appropriate_test, $appropriate_test_remarks, $week_calib, $week_calib_remarks,
                $outlet_identify, $outlet_identify_remarks,
                $pdb_entry, $pdb_entry_remarks, $pdb_updated, $pdb_updated_remarks, 
                $pdb_match, $pdb_match_remarks,
                $ron_label, $ron_label_remarks, $e10_label, $e10_label_remarks, 
                $biofuels, $biofuels_remarks,
                $consume_safety, $cel_warn, $cel_warn_remarks, $smoke_sign, $smoke_sign_remarks, 
                $switch_eng, $switch_eng_remarks, $straddle, $straddle_remarks,
                $post_unleaded, $post_unleaded_remarks, $post_biodiesel, $post_biodiesel_remarks,
                $issue_receipt, $issue_receipt_remarks, $non_refuse_inspect, $non_refuse_inspect_remarks, 
                $non_refuse_sign, $non_refuse_sign_remarks,
                $fixed_dispense, $fixed_dispense_remarks, $no_open_flame, $no_open_flame_remarks, 
                $max_length_dispense, $max_length_dispense_remarks, $peso_display, $peso_display_remarks,
                $pump_island, $pump_island_remarks, $lane_oriented_pump, $lane_oriented_pump_remarks, 
                $pump_guard, $pump_guard_remarks,
                $m_ingress, $m_ingress_remarks, $m_edge, $m_edge_remarks,
                $office_cashier, $office_cashier_remarks, $min_canopy, $min_canopy_remarks, 
                $boundary_walls, $boundary_walls_remarks, $master_switch, $master_switch_remarks, 
                $clean_rest, $clean_rest_remarks,
                $underground_storage, $underground_storage_remarks, $m_distance, $m_distance_remarks, 
                $vent, $vent_remarks,
                $transfer_dispense, $transfer_dispense_remarks, $no_drum, $no_drum_remarks, 
                $no_hoard, $no_hoard_remarks,
                $free_tire_press, $free_tire_press_remarks, $free_water, $free_water_remarks, 
                $basic_mechanical, $basic_mechanical_remarks, $first_aid, $first_aid_remarks, 
                $design_eval, $design_eval_remarks, $electric_eval, $electric_eval_remarks,
                $under_deliver, $under_deliver_remarks
            ];
        }
        
        $stmt_standardcompliancechecklist = $database->prepare($sql_standardcompliancechecklist);
        if (!$stmt_standardcompliancechecklist) {
            throw new Exception("Prepare failed for standardcompliancechecklist: " . $database->error);
        }
        
        $stmt_standardcompliancechecklist->bind_param($types, ...$params);
        
        if (!$stmt_standardcompliancechecklist->execute()) {
            throw new Exception("Execute failed for standardcompliancechecklist: " . $stmt_standardcompliancechecklist->error);
        }
        
        // 3. Handle suppliersinfo
        // (Similar approach as above for update/insert)
        
        // 4. Handle productquality (delete existing and insert new if sampling)
        if ($isUpdate) {
            // Delete existing product quality records
            $delete_stmt = $database->prepare("DELETE FROM productquality WHERE itr_form_num = ?");
            $delete_stmt->bind_param("s", $itr_form_num);
            if (!$delete_stmt->execute()) {
                throw new Exception("Failed to delete existing product quality records: " . $delete_stmt->error);
            }
        }
        
        // Insert new product quality records (same as your existing code)
        if (isset($_POST['sampling']) && $_POST['sampling'] == 1 && isset($_POST['code_value'])) {
            // ... [your existing product quality insertion code]
        }
        
        // Commit transaction if all succeeded
        $database->commit();
        echo "Record " . ($isUpdate ? "updated" : "created") . " successfully!";

    } catch (Exception $e) {
        // Rollback transaction on any error
        $database->rollback();
        die("Error: " . $e->getMessage());
    } finally {
        // Close connection
        $database->close();
    }
} else {
    echo "Form not submitted.";
}
?>