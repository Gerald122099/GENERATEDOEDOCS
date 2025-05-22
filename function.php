<?php
/* function get_post_data($post_data,$table,$return_what='column_names')         {
    // This function will return the column names or the values of the post data array
    // $post_data = $_POST; // Assuming this is your POST data array
    // $return_what = 'column_names'; // or 'values'
    
    if ($return_what == 'column_names'){
        $col_string = "";
        foreach ($_POST as $field => $val){
           
            if( str_starts_with(strval($field), $table))
                $col_string .= strval($field) . ", ";
               
            }
        $col_string = rtrim($col_string, ", ");
        $col_string = str_replace($table.'_','',$col_string );
        

       return $col_string;
        }
       
       else if ($return_what == 'values')
       {
        $val_string = "";
        foreach ($_POST as $field => $val){
            if( str_starts_with(strval($val), $table))
                $val_string .= strval($val).", ";

            }
        $val_string = rtrim($val_string, ", ");
        $val_string = str_replace($table.'_','',$val_string );
        $val_string = str_replace(' ', '', $val_string );
        $val_string = str_replace('(', '', $val_string );
        $val_string = str_replace(')', '', $val_string );
        return $val_string;
        
    }

    
    // Return column names as string
    // Example: 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15
    // Column names:
//itr_form_num, business_name, dealer_operator, location, in_charge, designation, sa_no, sa_date, outlet_class, company, contact_tel, email_add, sampling
}

*/

?>

<?php
// edit.php

// Check if the form has been submitted in edit mode
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_mode']) && $_POST['edit_mode'] === 'true') {
    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "itr_database";

    $database = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($database->connect_error) {
        die("Database connection failed: " . $database->connect_error);
    }

    // Start transaction
    $database->begin_transaction();

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
            throw new Exception("Error: ITR Form Number is required for all updates.");
        }

        // 1. Update businessinfo
        $sql_business = "UPDATE businessinfo SET 
                         business_name = ?, 
                         dealer_operator = ?, 
                         location = ?, 
                         in_charge = ?, 
                         designation = ?, 
                         sa_no = ?, 
                         sa_date = ?, 
                         outlet_class = ?, 
                         company = ?, 
                         contact_tel = ?, 
                         email_add = ?, 
                         sampling = ? 
                         WHERE itr_form_num = ?";
        
        $stmt_business = $database->prepare($sql_business);
        if (!$stmt_business) {
            throw new Exception("Failed to prepare businessinfo update: " . $database->error);
        }
        
        $bind_result = $stmt_business->bind_param("ssssssisssisi", 
            $business_name, 
            $dealer_operator, 
            $location, 
            $in_charge, 
            $designation, 
            $sa_no, 
            $sa_date, 
            $outlet_class, 
            $company, 
            $contact_tel, 
            $email_add, 
            $sampling,
            $itr_form_num
        );
        
        if (!$bind_result) {
            throw new Exception("Failed to bind parameters for businessinfo update: " . $stmt_business->error);
        }
        
        if (!$stmt_business->execute()) {
            throw new Exception("Failed to execute businessinfo update: " . $stmt_business->error);
        }

        // 2. Handle product quality data if sampling
        if ($sampling == 1 && isset($_POST['code_value'])) {
            // First, delete existing product quality records for this form
            $sql_delete_products = "DELETE FROM productquality WHERE itr_form_num = ?";
            $stmt_delete = $database->prepare($sql_delete_products);
            if (!$stmt_delete) {
                throw new Exception("Failed to prepare productquality delete: " . $database->error);
            }
            
            $bind_result = $stmt_delete->bind_param("s", $itr_form_num);
            if (!$bind_result) {
                throw new Exception("Failed to bind parameters for productquality delete: " . $stmt_delete->error);
            }
            
            if (!$stmt_delete->execute()) {
                throw new Exception("Failed to delete existing productquality records: " . $stmt_delete->error);
            }
            $stmt_delete->close();
            
            // Then insert new records
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
                
                // Function to generate UUID
                function uuidv4() {
                    $data = random_bytes(16);
                    $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
                    $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
                    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
                }
                
                // Prepare statement ONCE outside the loop
                $sql_product = "INSERT INTO productquality 
                               (id, itr_form_num, code_value, product, ron_value, UGT, pump) 
                               VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt_product = $database->prepare($sql_product);
                
                if (!$stmt_product) {
                    throw new Exception("Failed to prepare productquality insert: " . $database->error);
                }
                
                $successCount = 0;
                $errors = [];
                for ($i = 0; $i < $count; $i++) {
                    // Generate new UUID for each row
                    $row_uuid = uuidv4();
                    
                    // Bind parameters for current row
                    $bind_result = $stmt_product->bind_param("sssssss", 
                        $row_uuid,
                        $itr_form_num, 
                        $code_values[$i], 
                        $products[$i], 
                        $ron_values[$i], 
                        $UGTs[$i], 
                        $pumps[$i]);
                        
                    if (!$bind_result) {
                        $errors[] = "Row $i: Bind failed - " . $stmt_product->error;
                        continue;
                    }
                    
                    if ($stmt_product->execute()) {
                        $successCount++;
                    } else {
                        $errors[] = "Row $i: Insert failed - " . $stmt_product->error;
                    }
                }
                
                $stmt_product->close();
                
                if (count($errors) > 0) {
                    throw new Exception("Product quality insert completed with some errors. Success: $successCount, Failures: " . 
                                      count($errors) . ". First error: " . $errors[0]);
                }
            } else {
                throw new Exception("Product quality form data arrays are inconsistent. Array counts: code_values($count), " .
                                   "products(" . count($products) . "), ron_values(" . count($ron_values) . "), " .
                                   "UGTs(" . count($UGTs) . "), pumps(" . count($pumps) . ")");
            }
        }

        // 3. Update standardcompliancechecklist
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
        
        $sql_standardcompliancechecklist = "UPDATE standardcompliancechecklist SET
            coc_cert = ?, coc_cert_remarks = ?, coc_posted = ?, coc_posted_remarks = ?,
            valid_permit_LGU = ?, valid_permit_LGU_remarks = ?, valid_permit_BFP = ?, valid_permit_BFP_remarks = ?, 
            valid_permit_DENR = ?, valid_permit_DENR_remarks = ?,
            appropriate_test = ?, appropriate_test_remarks = ?, week_calib = ?, week_calib_remarks = ?,
            outlet_identify = ?, outlet_identify_remarks = ?,
            pdb_entry = ?, pdb_entry_remarks = ?, pdb_updated = ?, pdb_updated_remarks = ?, pdb_match = ?, pdb_match_remarks = ?,
            ron_label = ?, ron_label_remarks = ?, e10_label = ?, e10_label_remarks = ?, biofuels = ?, biofuels_remarks = ?,
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
        
        $stmt_standardcompliancechecklist = $database->prepare($sql_standardcompliancechecklist);
        if (!$stmt_standardcompliancechecklist) {
            throw new Exception("Failed to prepare standardcompliancechecklist update: " . $database->error);
        }
        
        $bind_result = $stmt_standardcompliancechecklist->bind_param(
            "isisisisisisisisisisisisisisiisisisisisisisisisisisisisisisisisisisisisisisisisisisisisisisisisis", 
            $coc_cert, $coc_cert_remarks, $coc_posted, $coc_posted_remarks,
            $valid_permit_LGU, $valid_permit_LGU_remarks, $valid_permit_BFP, $valid_permit_BFP_remarks, 
            $valid_permit_DENR, $valid_permit_DENR_remarks,
            $appropriate_test, $appropriate_test_remarks, $week_calib, $week_calib_remarks,
            $outlet_identify, $outlet_identify_remarks,
            $pdb_entry, $pdb_entry_remarks, $pdb_updated, $pdb_updated_remarks, $pdb_match, $pdb_match_remarks,
            $ron_label, $ron_label_remarks, $e10_label, $e10_label_remarks, $biofuels, $biofuels_remarks,
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
        );
        
        if (!$bind_result) {
            throw new Exception("Failed to bind parameters for standardcompliancechecklist update: " . 
                              $stmt_standardcompliancechecklist->error);
        }
        
        if (!$stmt_standardcompliancechecklist->execute()) {
            throw new Exception("Failed to execute standardcompliancechecklist update: " . 
                              $stmt_standardcompliancechecklist->error);
        }

        // 4. Update suppliersinfo
        $receipt_invoice = $_POST['receipt_invoice'] ?? '';
        $supplier = $_POST['supplier'] ?? '';
        $date_deliver = $_POST['date_deliver'] ?? '';
        $address = $_POST['address'] ?? '';
        $contact_num = $_POST['contact_num'] ?? '';
        
        $sql_supplier = "UPDATE suppliersinfo SET
            receipt_invoice = ?, 
            supplier = ?, 
            date_deliver = ?, 
            address = ?, 
            contact_num = ?
            WHERE itr_form_num = ?";
            
        $stmt_supplier = $database->prepare($sql_supplier);
        if (!$stmt_supplier) {
            throw new Exception("Failed to prepare suppliersinfo update: " . $database->error);
        }
        
        $bind_result = $stmt_supplier->bind_param("ssssss", 
            $receipt_invoice, 
            $supplier, 
            $date_deliver, 
            $address, 
            $contact_num,
            $itr_form_num
        );
        
        if (!$bind_result) {
            throw new Exception("Failed to bind parameters for suppliersinfo update: " . $stmt_supplier->error);
        }
        
        if (!$stmt_supplier->execute()) {
            throw new Exception("Failed to execute suppliersinfo update: " . $stmt_supplier->error);
        }

        // 5. Update productqualitycont
        $retention = isset($_POST['duplicate_retention_samples']) ? 1 : 0;
        $appropriate = isset($_POST['appropriate_sampling']) ? 1 : 0;

        $sql_retention = "UPDATE productqualitycont SET
            duplicate_retention_samples = ?,
            appropriate_sampling = ?
            WHERE itr_form_num = ?";
            
        $stmt_retention = $database->prepare($sql_retention);
        if (!$stmt_retention) {
            throw new Exception("Failed to prepare productqualitycont update: " . $database->error);
        }
        
        $bind_result = $stmt_retention->bind_param("iis", 
            $retention,
            $appropriate,
            $itr_form_num
        );
        
        if (!$bind_result) {
            throw new Exception("Failed to bind parameters for productqualitycont update: " . $stmt_retention->error);
        }
        
        if (!$stmt_retention->execute()) {
            throw new Exception("Failed to execute productqualitycont update: " . $stmt_retention->error);
        }

        // Commit transaction if all succeeded
        $database->commit();
        echo "Record updated successfully for ITR Form #$itr_form_num";

    } catch (Exception $e) {
        // Rollback transaction on any error
        $database->rollback();
        http_response_code(400); // Bad request
        die("Error updating record: " . $e->getMessage());
    } finally {
        // Close connection
        $database->close();
    }
} else {
    http_response_code(400); // Bad request
    die("Invalid request: Form not submitted in edit mode or missing required parameters.");
}
?>