<?php
// edit.php
header('Content-Type: application/json'); // Ensure we return JSON

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
        die(json_encode([
            'status' => 'error',
            'title' => 'Database Error',
            'message' => "Connection failed: " . $database->connect_error,
            'icon' => 'error'
        ]));
    }

    // Start transaction
    $database->begin_transaction();

    try {
        // Get form data
        $itr_form_num = $_POST['itr_form_num'] ?? '';
        
        // Validate required fields
        if (empty($itr_form_num)) {
            throw new Exception("ITR Form Number is required for all updates.");
        }
        // 1. Update businessinfo - only update changed fields
        // First get existing data
        $sql_get_business = "SELECT * FROM businessinfo WHERE itr_form_num = ?";
        $stmt_get_business = $database->prepare($sql_get_business);
        if (!$stmt_get_business) {
            throw new Exception("Failed to prepare businessinfo select: " . $database->error);
        }
        
        $stmt_get_business->bind_param("s", $itr_form_num);
        if (!$stmt_get_business->execute()) {
            throw new Exception("Failed to execute businessinfo select: " . $stmt_get_business->error);
        }
        
        $result = $stmt_get_business->get_result();
        $existing_business = $result->fetch_assoc();
        $stmt_get_business->close();
        
        if (!$existing_business) {
            throw new Exception("No existing record found for ITR Form Number: " . $itr_form_num);
        }
        
        // Prepare update query with only changed fields
        $update_fields = [];
        $update_values = [];
        $types = "";
        
        // List of fields to check
        $business_fields = [
            'business_name', 'dealer_operator', 'location', 'in_charge', 'designation',
            'sa_no', 'sa_date', 'outlet_class', 'company', 'contact_tel', 'email_add', 'sampling'
        ];
        
        foreach ($business_fields as $field) {
            $new_value = $_POST[$field] ?? null;
            
            // Handle special cases
            if ($field === 'sa_no' || $field === 'contact_tel') {
                $new_value = isset($_POST[$field]) ? intval($_POST[$field]) : 0;
            } elseif ($field === 'sampling') {
                $new_value = isset($_POST[$field]) ? 1 : 0;
            }
            
            // Compare with existing value
            if ($new_value !== null && $new_value != $existing_business[$field]) {
                $update_fields[] = "$field = ?";
                $update_values[] = $new_value;
                
                // Add type for bind_param
                if ($field === 'sa_no' || $field === 'contact_tel') {
                    $types .= "i";
                } else {
                    $types .= "s";
                }
            }
        }
        
        // Only update if there are changes
        if (!empty($update_fields)) {
            $types .= "s"; // for itr_form_num
            $sql_business = "UPDATE businessinfo SET " . implode(", ", $update_fields) . " WHERE itr_form_num = ?";
            $update_values[] = $itr_form_num;
            
            $stmt_business = $database->prepare($sql_business);
            if (!$stmt_business) {
                throw new Exception("Failed to prepare businessinfo update: " . $database->error);
            }
            
            // Dynamically bind parameters
            $bind_params = array_merge([$types], $update_values);
            $refs = [];
            foreach ($bind_params as $key => $value) {
                $refs[$key] = &$bind_params[$key];
            }
            
            call_user_func_array([$stmt_business, 'bind_param'], $refs);
            
            if (!$stmt_business->execute()) {
                throw new Exception("Failed to execute businessinfo update: " . $stmt_business->error);
            }
            $stmt_business->close();
        }

        // 2. Handle product quality data if sampling
        if (isset($_POST['sampling']) && $_POST['sampling'] == 1 && isset($_POST['code_value'])) {
            // First, delete existing product quality records for this form
            $sql_delete_products = "DELETE FROM productquality WHERE itr_form_num = ?";
            $stmt_delete = $database->prepare($sql_delete_products);
            if (!$stmt_delete) {
                throw new Exception("Failed to prepare productquality delete: " . $database->error);
            }
            
            $stmt_delete->bind_param("s", $itr_form_num);
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
                        $errors[] = "Row $i: Insert failed - " . $stmt_product->error;
                    }
                }
                
                $stmt_product->close();
                
                if (count($errors) > 0) {
                    throw new Exception("Product quality insert completed with some errors. Success: $successCount, Failures: " . 
                                      count($errors) . ". First error: " . $errors[0]);
                }
            }
        }

        // 3. Update standardcompliancechecklist - only update changed fields
        // First get existing data
        $sql_get_checklist = "SELECT * FROM standardcompliancechecklist WHERE itr_form_num = ?";
        $stmt_get_checklist = $database->prepare($sql_get_checklist);
        if (!$stmt_get_checklist) {
            throw new Exception("Failed to prepare standardcompliancechecklist select: " . $database->error);
        }
        
        $stmt_get_checklist->bind_param("s", $itr_form_num);
        if (!$stmt_get_checklist->execute()) {
            throw new Exception("Failed to execute standardcompliancechecklist select: " . $stmt_get_checklist->error);
        }
        
        $result = $stmt_get_checklist->get_result();
        $existing_checklist = $result->fetch_assoc();
        $stmt_get_checklist->close();
        
        if (!$existing_checklist) {
            throw new Exception("No existing checklist record found for ITR Form Number: " . $itr_form_num);
        }
        
        // Prepare update query with only changed fields
        $update_fields = [];
        $update_values = [];
        $types = "";
        
        // List of all checklist fields
        $checklist_fields = [
            'coc_cert', 'coc_cert_remarks', 'coc_posted', 'coc_posted_remarks',
            'valid_permit_LGU', 'valid_permit_LGU_remarks', 'valid_permit_BFP', 'valid_permit_BFP_remarks',
            'valid_permit_DENR', 'valid_permit_DENR_remarks',
            'appropriate_test', 'appropriate_test_remarks', 'week_calib', 'week_calib_remarks',
            'outlet_identify', 'outlet_identify_remarks',
            'pdb_entry', 'pdb_entry_remarks', 'pdb_updated', 'pdb_updated_remarks', 'pdb_match', 'pdb_match_remarks',
            'ron_label', 'ron_label_remarks', 'e10_label', 'e10_label_remarks', 'biofuels', 'biofuels_remarks',
            'consume_safety', 'cel_warn', 'cel_warn_remarks', 'smoke_sign', 'smoke_sign_remarks',
            'switch_eng', 'switch_eng_remarks', 'straddle', 'straddle_remarks',
            'post_unleaded', 'post_unleaded_remarks', 'post_biodiesel', 'post_biodiesel_remarks',
            'issue_receipt', 'issue_receipt_remarks', 'non_refuse_inspect', 'non_refuse_inspect_remarks',
            'non_refuse_sign', 'non_refuse_sign_remarks',
            'fixed_dispense', 'fixed_dispense_remarks', 'no_open_flame', 'no_open_flame_remarks',
            'max_length_dispense', 'max_length_dispense_remarks', 'peso_display', 'peso_display_remarks',
            'pump_island', 'pump_island_remarks', 'lane_oriented_pump', 'lane_oriented_pump_remarks',
            'pump_guard', 'pump_guard_remarks',
            'm_ingress', 'm_ingress_remarks', 'm_edge', 'm_edge_remarks',
            'office_cashier', 'office_cashier_remarks', 'min_canopy', 'min_canopy_remarks',
            'boundary_walls', 'boundary_walls_remarks', 'master_switch', 'master_switch_remarks',
            'clean_rest', 'clean_rest_remarks',
            'underground_storage', 'underground_storage_remarks', 'm_distance', 'm_distance_remarks',
            'vent', 'vent_remarks',
            'transfer_dispense', 'transfer_dispense_remarks', 'no_drum', 'no_drum_remarks',
            'no_hoard', 'no_hoard_remarks',
            'free_tire_press', 'free_tire_press_remarks', 'free_water', 'free_water_remarks',
            'basic_mechanical', 'basic_mechanical_remarks', 'first_aid', 'first_aid_remarks',
            'design_eval', 'design_eval_remarks', 'electric_eval', 'electric_eval_remarks',
            'under_deliver', 'under_deliver_remarks'
        ];
        
        foreach ($checklist_fields as $field) {
            $new_value = $_POST[$field] ?? null;
            
            // Handle checkbox fields (convert to 1 or 0)
            if (in_array($field, [
                'coc_cert', 'coc_posted', 'valid_permit_LGU', 'valid_permit_BFP', 'valid_permit_DENR',
                'appropriate_test', 'week_calib', 'outlet_identify', 'pdb_entry', 'pdb_updated', 'pdb_match',
                'ron_label', 'e10_label', 'biofuels', 'consume_safety', 'cel_warn', 'smoke_sign', 'switch_eng',
                'straddle', 'post_unleaded', 'post_biodiesel', 'issue_receipt', 'non_refuse_inspect',
                'non_refuse_sign', 'fixed_dispense', 'no_open_flame', 'max_length_dispense', 'peso_display',
                'pump_island', 'lane_oriented_pump', 'pump_guard', 'm_ingress', 'm_edge', 'office_cashier',
                'min_canopy', 'boundary_walls', 'master_switch', 'clean_rest', 'underground_storage',
                'm_distance', 'vent', 'transfer_dispense', 'no_drum', 'no_hoard', 'free_tire_press',
                'free_water', 'basic_mechanical', 'first_aid', 'design_eval', 'electric_eval', 'under_deliver'
            ])) {
                $new_value = isset($_POST[$field]) ? 1 : 0;
            }
            
            // Compare with existing value
            if ($new_value !== null && $new_value != $existing_checklist[$field]) {
                $update_fields[] = "$field = ?";
                $update_values[] = $new_value;
                
                // Add type for bind_param
                if (in_array($field, [
                    'coc_cert', 'coc_posted', 'valid_permit_LGU', 'valid_permit_BFP', 'valid_permit_DENR',
                    'appropriate_test', 'week_calib', 'outlet_identify', 'pdb_entry', 'pdb_updated', 'pdb_match',
                    'ron_label', 'e10_label', 'biofuels', 'consume_safety', 'cel_warn', 'smoke_sign', 'switch_eng',
                    'straddle', 'post_unleaded', 'post_biodiesel', 'issue_receipt', 'non_refuse_inspect',
                    'non_refuse_sign', 'fixed_dispense', 'no_open_flame', 'max_length_dispense', 'peso_display',
                    'pump_island', 'lane_oriented_pump', 'pump_guard', 'm_ingress', 'm_edge', 'office_cashier',
                    'min_canopy', 'boundary_walls', 'master_switch', 'clean_rest', 'underground_storage',
                    'm_distance', 'vent', 'transfer_dispense', 'no_drum', 'no_hoard', 'free_tire_press',
                    'free_water', 'basic_mechanical', 'first_aid', 'design_eval', 'electric_eval', 'under_deliver'
                ])) {
                    $types .= "i"; // integer
                } else {
                    $types .= "s"; // string
                }
            }
        }
        
        // Only update if there are changes
        if (!empty($update_fields)) {
            $types .= "s"; // for itr_form_num
            $sql_checklist = "UPDATE standardcompliancechecklist SET " . implode(", ", $update_fields) . " WHERE itr_form_num = ?";
            $update_values[] = $itr_form_num;
            
            $stmt_checklist = $database->prepare($sql_checklist);
            if (!$stmt_checklist) {
                throw new Exception("Failed to prepare standardcompliancechecklist update: " . $database->error);
            }
            
            // Dynamically bind parameters
            $bind_params = array_merge([$types], $update_values);
            $refs = [];
            foreach ($bind_params as $key => $value) {
                $refs[$key] = &$bind_params[$key];
            }
            
            call_user_func_array([$stmt_checklist, 'bind_param'], $refs);
            
            if (!$stmt_checklist->execute()) {
                throw new Exception("Failed to execute standardcompliancechecklist update: " . $stmt_checklist->error);
            }
            $stmt_checklist->close();
        }

        // 4. Update suppliersinfo - only update changed fields
        // First get existing data
        $sql_get_supplier = "SELECT * FROM suppliersinfo WHERE itr_form_num = ?";
        $stmt_get_supplier = $database->prepare($sql_get_supplier);
        if (!$stmt_get_supplier) {
            throw new Exception("Failed to prepare suppliersinfo select: " . $database->error);
        }
        
        $stmt_get_supplier->bind_param("s", $itr_form_num);
        if (!$stmt_get_supplier->execute()) {
            throw new Exception("Failed to execute suppliersinfo select: " . $stmt_get_supplier->error);
        }
        
        $result = $stmt_get_supplier->get_result();
        $existing_supplier = $result->fetch_assoc();
        $stmt_get_supplier->close();
        
        if (!$existing_supplier) {
            throw new Exception("No existing supplier record found for ITR Form Number: " . $itr_form_num);
        }
        
        // Prepare update query with only changed fields
        $update_fields = [];
        $update_values = [];
        $types = "";
        
        // List of supplier fields
        $supplier_fields = [
            'receipt_invoice', 'supplier', 'date_deliver', 'address', 'contact_num'
        ];
        
        foreach ($supplier_fields as $field) {
            $new_value = $_POST[$field] ?? null;
            
            // Compare with existing value
            if ($new_value !== null && $new_value != $existing_supplier[$field]) {
                $update_fields[] = "$field = ?";
                $update_values[] = $new_value;
                $types .= "s"; // all supplier fields are strings
            }
        }
        
        // Only update if there are changes
        if (!empty($update_fields)) {
            $types .= "s"; // for itr_form_num
            $sql_supplier = "UPDATE suppliersinfo SET " . implode(", ", $update_fields) . " WHERE itr_form_num = ?";
            $update_values[] = $itr_form_num;
            
            $stmt_supplier = $database->prepare($sql_supplier);
            if (!$stmt_supplier) {
                throw new Exception("Failed to prepare suppliersinfo update: " . $database->error);
            }
            
            // Dynamically bind parameters
            $bind_params = array_merge([$types], $update_values);
            $refs = [];
            foreach ($bind_params as $key => $value) {
                $refs[$key] = &$bind_params[$key];
            }
            
            call_user_func_array([$stmt_supplier, 'bind_param'], $refs);
            
            if (!$stmt_supplier->execute()) {
                throw new Exception("Failed to execute suppliersinfo update: " . $stmt_supplier->error);
            }
            $stmt_supplier->close();
        }

        // 5. Update productqualitycont - only update changed fields
        // First get existing data
        $sql_get_qualitycont = "SELECT * FROM productqualitycont WHERE itr_form_num = ?";
        $stmt_get_qualitycont = $database->prepare($sql_get_qualitycont);
        if (!$stmt_get_qualitycont) {
            throw new Exception("Failed to prepare productqualitycont select: " . $database->error);
        }
        
        $stmt_get_qualitycont->bind_param("s", $itr_form_num);
        if (!$stmt_get_qualitycont->execute()) {
            throw new Exception("Failed to execute productqualitycont select: " . $stmt_get_qualitycont->error);
        }
        
        $result = $stmt_get_qualitycont->get_result();
        $existing_qualitycont = $result->fetch_assoc();
        $stmt_get_qualitycont->close();
        
        if (!$existing_qualitycont) {
            throw new Exception("No existing productqualitycont record found for ITR Form Number: " . $itr_form_num);
        }
        
        // Prepare update query with only changed fields
        $update_fields = [];
        $update_values = [];
        $types = "";
        
        // List of qualitycont fields
        $qualitycont_fields = [
            'duplicate_retention_samples', 'appropriate_sampling'
        ];
        
        foreach ($qualitycont_fields as $field) {
            $new_value = isset($_POST[$field]) ? 1 : 0;
            
            // Compare with existing value
            if ($new_value != $existing_qualitycont[$field]) {
                $update_fields[] = "$field = ?";
                $update_values[] = $new_value;
                $types .= "i"; // both fields are integers (booleans)
            }
        }
        
        // Only update if there are changes
        if (!empty($update_fields)) {
            $types .= "s"; // for itr_form_num
            $sql_qualitycont = "UPDATE productqualitycont SET " . implode(", ", $update_fields) . " WHERE itr_form_num = ?";
            $update_values[] = $itr_form_num;
            
            $stmt_qualitycont = $database->prepare($sql_qualitycont);
            if (!$stmt_qualitycont) {
                throw new Exception("Failed to prepare productqualitycont update: " . $database->error);
            }
            
            // Dynamically bind parameters
            $bind_params = array_merge([$types], $update_values);
            $refs = [];
            foreach ($bind_params as $key => $value) {
                $refs[$key] = &$bind_params[$key];
            }
            
            call_user_func_array([$stmt_qualitycont, 'bind_param'], $refs);
            
            if (!$stmt_qualitycont->execute()) {
                throw new Exception("Failed to execute productqualitycont update: " . $stmt_qualitycont->error);
            }
            $stmt_qualitycont->close();
        }


        // 6. Update or insert generalremarks
// First check if there's an existing record
$sql_get_remarks = "SELECT * FROM generalremarks WHERE itr_form_num = ?";
$stmt_get_remarks = $database->prepare($sql_get_remarks);
if (!$stmt_get_remarks) {
    throw new Exception("Failed to prepare generalremarks select: " . $database->error);
}

$stmt_get_remarks->bind_param("s", $itr_form_num);
if (!$stmt_get_remarks->execute()) {
    throw new Exception("Failed to execute generalremarks select: " . $stmt_get_remarks->error);
}

$result = $stmt_get_remarks->get_result();
$existing_remarks = $result->fetch_assoc();
$stmt_get_remarks->close();

// Get the new values
$action_required = $_POST['action_required'] ?? '';
$user_gen_remarks = $_POST['user_gen_remarks'] ?? '';

// If record exists, update it
if ($existing_remarks) {
    // Only update if values have changed
    if ($action_required != $existing_remarks['action_required'] || 
        $user_gen_remarks != $existing_remarks['user_gen_remarks']) {
        
        $sql_update_remarks = "UPDATE generalremarks SET action_required = ?, user_gen_remarks = ? WHERE itr_form_num = ?";
        $stmt_update_remarks = $database->prepare($sql_update_remarks);
        if (!$stmt_update_remarks) {
            throw new Exception("Failed to prepare generalremarks update: " . $database->error);
        }
        
        $stmt_update_remarks->bind_param("sss", $action_required, $user_gen_remarks, $itr_form_num);
        if (!$stmt_update_remarks->execute()) {
            throw new Exception("Failed to execute generalremarks update: " . $stmt_update_remarks->error);
        }
        $stmt_update_remarks->close();
    }
} 
// If record doesn't exist, insert a new one
else {
    $sql_insert_remarks = "INSERT INTO generalremarks (itr_form_num, action_required, user_gen_remarks) VALUES (?, ?, ?)";
    $stmt_insert_remarks = $database->prepare($sql_insert_remarks);
    if (!$stmt_insert_remarks) {
        throw new Exception("Failed to prepare generalremarks insert: " . $database->error);
    }
    
    $stmt_insert_remarks->bind_param("sss", $itr_form_num, $action_required, $user_gen_remarks);
    if (!$stmt_insert_remarks->execute()) {
        throw new Exception("Failed to execute generalremarks insert: " . $stmt_insert_remarks->error);
    }
    $stmt_insert_remarks->close();
}

               // Commit transaction if all succeeded
        $database->commit();
        
        // Success response with SweetAlert-compatible format
        echo json_encode([
            'status' => 'success',
            'title' => 'Success!',
            'message' => 'ITR Form ' . $itr_form_num . ' has been successfully updated!',
            'icon' => 'success',
            'itr_form_num' => $itr_form_num
        ]);

    } catch (Exception $e) {
        // Rollback transaction if needed
        if (isset($database) && $database instanceof mysqli) {
            $database->rollback();
        }
        
        // Error response with SweetAlert-compatible format
        http_response_code(400);
        echo json_encode([
            'status' => 'error',
            'title' => 'Update Failed',
            'message' => $e->getMessage(),
            'icon' => 'error',
            'trace' => $e->getTraceAsString() // Only include in development
        ]);
        
    } finally {
        if (isset($database) && $database instanceof mysqli) {
            $database->close();
        }
    }
} else {
    // Method not allowed response
    http_response_code(405);
    echo json_encode([
        'status' => 'error',
        'title' => 'Invalid Request',
        'message' => 'Invalid request method. Only POST is allowed.',
        'icon' => 'error'
    ]);
}
?>