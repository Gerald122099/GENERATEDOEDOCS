<?php
ini_set('display_errors', 0);
error_reporting(0);
header('Content-Type: application/json');

require 'config.php';


require 'config.php';

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Start transaction
    $conn->beginTransaction();
    
    $data = $_POST;
    $itrFormNum = $data['itr_form_num'];
    
     // 1. Save business info (one-to-one relationship)
     $stmt = $conn->prepare("INSERT INTO businessinfo (itr_form_num, business_name, dealer_operator, location, 
     in_charge, designation, outlet_class, company, contact_tel, email_add, sampling, inspector_name)
     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
     ON DUPLICATE KEY UPDATE 
     business_name = VALUES(business_name), dealer_operator = VALUES(dealer_operator),
     location = VALUES(location), in_charge = VALUES(in_charge),
     designation = VALUES(designation), outlet_class = VALUES(outlet_class),
     company = VALUES(company), contact_tel = VALUES(contact_tel),
     email_add = VALUES(email_add), sampling = VALUES(sampling),
     inspector_name = VALUES(inspector_name)");

$stmt->execute([
$itrFormNum,
$data['business_name'],
$data['dealer_operator'],
$data['location'],
$data['in_charge'],
$data['designation'],
$data['outlet_class'],
$data['company'],
$data['contact_tel'],
$data['email_add'],
$data['sampling'],
$data['inspector_name']
]);

// 2. Save general remarks (update if exists)
if (!empty($data['remarks'])) {
// Check if record exists
$stmt = $conn->prepare("SELECT id FROM generalremarks WHERE itr_form_num = ? LIMIT 1");
$stmt->execute([$itrFormNum]);
$existingId = $stmt->fetchColumn();

if ($existingId) {
// Update existing record
$stmt = $conn->prepare("UPDATE generalremarks SET remarks = ? WHERE id = ?");
$stmt->execute([$data['remarks'], $existingId]);
} else {
// Insert new record
$stmt = $conn->prepare("INSERT INTO generalremarks (id, itr_form_num, remarks) VALUES (UUID(), ?, ?)");
$stmt->execute([$itrFormNum, $data['remarks']]);
}
}

// 3. Save product quality (update if exists)
if (!empty($data['product'])) {
// Check if record exists
$stmt = $conn->prepare("SELECT id FROM productquality WHERE itr_form_num = ? LIMIT 1");
$stmt->execute([$itrFormNum]);
$existingId = $stmt->fetchColumn();

if ($existingId) {
// Update existing record
$stmt = $conn->prepare("UPDATE productquality SET product = ?, ron_value = ?, UGT = ?, pump = ? WHERE id = ?");
$stmt->execute([
$data['product'],
$data['ron_value'],
$data['UGT'],
$data['pump'],
$existingId
]);
} else {
// Insert new record
$stmt = $conn->prepare("INSERT INTO productquality (id, itr_form_num, product, ron_value, UGT, pump) VALUES (UUID(), ?, ?, ?, ?, ?)");
$stmt->execute([
$itrFormNum,
$data['product'],
$data['ron_value'],
$data['UGT'],
$data['pump']
]);
}
}

// 4. Save product quality control (update if exists)
// Check if record exists
$stmt = $conn->prepare("SELECT id FROM productqualitycont WHERE itr_form_num = ? LIMIT 1");
$stmt->execute([$itrFormNum]);
$existingId = $stmt->fetchColumn();

if ($existingId) {
// Update existing record
$stmt = $conn->prepare("UPDATE productqualitycont SET 
         duplicate_retention_samples = ?,
         retention_retail = ?,
         appropriate_sampling = ?,
         inappropriate_sampling = ?
         WHERE id = ?");
$stmt->execute([
$data['duplicate_retention_samples'],
$data['retention_retail'],
$data['appropriate_sampling'],
$data['inappropriate_sampling'],
$existingId
]);
} else {
// Insert new record
$stmt = $conn->prepare("INSERT INTO productqualitycont (id, itr_form_num, duplicate_retention_samples, retention_retail, appropriate_sampling, inappropriate_sampling)
         VALUES (UUID(), ?, ?, ?, ?, ?)");
$stmt->execute([
$itrFormNum,
$data['duplicate_retention_samples'],
$data['retention_retail'],
$data['appropriate_sampling'],
$data['inappropriate_sampling']
]);
}

// 5. Save standard compliance (update if exists)
// Check if record exists
$stmt = $conn->prepare("SELECT id FROM standardcompliancechecklist WHERE itr_form_num = ? LIMIT 1");
$stmt->execute([$itrFormNum]);
$existingId = $stmt->fetchColumn();

if ($existingId) {
// Update existing record (prepared statement with all fields)
$updateQuery = "UPDATE standardcompliancechecklist SET 
coc_cert = ?, coc_cert_remarks = ?, coc_posted = ?, coc_posted_remarks = ?,
valid_permit_LGU = ?, valid_permit_LGU_remarks = ?, valid_permit_BFP = ?, valid_permit_BFP_remarks = ?,
valid_permit_DENR = ?, valid_permit_DENR_remarks = ?, appropriate_test = ?, appropriate_test_remarks = ?,
week_calib = ?, week_calib_remarks = ?, outlet_identify = ?, outlet_identify_remarks = ?,
price_display = ?, price_display_remarks = ?, pdb_entry = ?, pdb_entry_remarks = ?,
pdb_updated = ?, pdb_updated_remarks = ?, pdb_match = ?, pdb_match_remarks = ?,
ron_label = ?, ron_label_remarks = ?, e10_label = ?, e10_label_remarks = ?,
biofuels = ?, biofuels_remarks = ?, consume_safety = ?, consume_safety_remarks = ?,
cel_warn = ?, cel_warn_remarks = ?, smoke_sign = ?, smoke_sign_remarks = ?,
switch_eng = ?, switch_eng_remarks = ?, straddle = ?, straddle_remarks = ?,
post_unleaded = ?, post_unleaded_remarks = ?, post_biodiesel = ?, post_biodiesel_remarks = ?,
issue_receipt = ?, issue_receipt_remarks = ?, non_refuse_inspect = ?, non_refuse_inspect_remarks = ?,
non_refuse_sign = ?, non_refuse_sign_remarks = ?, fixed_dispense = ?, fixed_dispense_remarks = ?,
no_open_flame = ?, no_open_flame_remarks = ?, max_length_dispense = ?, max_length_dispense_remarks = ?,
peso_display = ?, peso_display_remarks = ?, pump_island = ?, pump_island_remarks = ?,
lane_oriented_pump = ?, lane_oriented_pump_remarks = ?, pump_guard = ?, pump_guard_remarks = ?,
m_ingress = ?, m_ingress_remarks = ?, m_edge = ?, m_edge_remarks = ?,
office_cashier = ?, office_cashier_remarks = ?, min_canopy = ?, min_canopy_remarks = ?,
boundary_walls = ?, boundary_walls_remarks = ?, master_switch = ?, master_switch_remarks = ?,
clean_rest = ?, clean_rest_remarks = ?, underground_storage = ?, underground_storage_remarks = ?,
m_distance = ?, m_distance_remarks = ?, vent = ?, vent_remarks = ?,
transfer_dispense = ?, transfer_dispense_remarks = ?, no_drum = ?, no_drum_remarks = ?,
no_hoard = ?, no_hoard_remarks = ?, free_tire_press = ?, free_tire_press_remarks = ?,
free_water = ?, free_water_remarks = ?, basic_mechanical = ?, basic_mechanical_remarks = ?,
first_aid = ?, first_aid_remarks = ?, design_eval = ?, design_eval_remarks = ?,
electric_eval = ?, electric_eval_remarks = ?, under_deliver = ?, under_deliver_remarks = ?
WHERE id = ?";

$stmt = $conn->prepare($updateQuery);
$stmt->execute([
// All the values from your original execute() call
$data['coc_cert'], $data['coc_cert_remarks'], $data['coc_posted'], $data['coc_posted_remarks'],
$data['valid_permit_LGU'], $data['valid_permit_LGU_remarks'], $data['valid_permit_BFP'], $data['valid_permit_BFP_remarks'],
$data['valid_permit_DENR'], $data['valid_permit_DENR_remarks'], $data['appropriate_test'], $data['appropriate_test_remarks'],
$data['week_calib'], $data['week_calib_remarks'], $data['outlet_identify'], $data['outlet_identify_remarks'],
$data['price_display'], $data['price_display_remarks'], $data['pdb_entry'], $data['pdb_entry_remarks'],
$data['pdb_updated'], $data['pdb_updated_remarks'], $data['pdb_match'], $data['pdb_match_remarks'],
$data['ron_label'], $data['ron_label_remarks'], $data['e10_label'], $data['e10_label_remarks'],
$data['biofuels'], $data['biofuels_remarks'], $data['consume_safety'], $data['consume_safety_remarks'],
$data['cel_warn'], $data['cel_warn_remarks'], $data['smoke_sign'], $data['smoke_sign_remarks'],
$data['switch_eng'], $data['switch_eng_remarks'], $data['straddle'], $data['straddle_remarks'],
$data['post_unleaded'], $data['post_unleaded_remarks'], $data['post_biodiesel'], $data['post_biodiesel_remarks'],
$data['issue_receipt'], $data['issue_receipt_remarks'], $data['non_refuse_inspect'], $data['non_refuse_inspect_remarks'],
$data['non_refuse_sign'], $data['non_refuse_sign_remarks'], $data['fixed_dispense'], $data['fixed_dispense_remarks'],
$data['no_open_flame'], $data['no_open_flame_remarks'], $data['max_length_dispense'], $data['max_length_dispense_remarks'],
$data['peso_display'], $data['peso_display_remarks'], $data['pump_island'], $data['pump_island_remarks'],
$data['lane_oriented_pump'], $data['lane_oriented_pump_remarks'], $data['pump_guard'], $data['pump_guard_remarks'],
$data['m_ingress'], $data['m_ingress_remarks'], $data['m_edge'], $data['m_edge_remarks'],
$data['office_cashier'], $data['office_cashier_remarks'], $data['min_canopy'], $data['min_canopy_remarks'],
$data['boundary_walls'], $data['boundary_walls_remarks'], $data['master_switch'], $data['master_switch_remarks'],
$data['clean_rest'], $data['clean_rest_remarks'], $data['underground_storage'], $data['underground_storage_remarks'],
$data['m_distance'], $data['m_distance_remarks'], $data['vent'], $data['vent_remarks'],
$data['transfer_dispense'], $data['transfer_dispense_remarks'], $data['no_drum'], $data['no_drum_remarks'],
$data['no_hoard'], $data['no_hoard_remarks'], $data['free_tire_press'], $data['free_tire_press_remarks'],
$data['free_water'], $data['free_water_remarks'], $data['basic_mechanical'], $data['basic_mechanical_remarks'],
$data['first_aid'], $data['first_aid_remarks'], $data['design_eval'], $data['design_eval_remarks'],
$data['electric_eval'], $data['electric_eval_remarks'], $data['under_deliver'], $data['under_deliver_remarks'],
$existingId
]);
} else {
// Insert new record (your original insert code)
$stmt = $conn->prepare("INSERT INTO standardcompliancechecklist (
id, itr_form_num, 
coc_cert, coc_cert_remarks, coc_posted, coc_posted_remarks,
valid_permit_LGU, valid_permit_LGU_remarks, valid_permit_BFP, valid_permit_BFP_remarks,
valid_permit_DENR, valid_permit_DENR_remarks, appropriate_test, appropriate_test_remarks,
week_calib, week_calib_remarks, outlet_identify, outlet_identify_remarks,
price_display, price_display_remarks, pdb_entry, pdb_entry_remarks,
pdb_updated, pdb_updated_remarks, pdb_match, pdb_match_remarks,
ron_label, ron_label_remarks, e10_label, e10_label_remarks,
biofuels, biofuels_remarks, consume_safety, consume_safety_remarks,
cel_warn, cel_warn_remarks, smoke_sign, smoke_sign_remarks,
switch_eng, switch_eng_remarks, straddle, straddle_remarks,
post_unleaded, post_unleaded_remarks, post_biodiesel, post_biodiesel_remarks,
issue_receipt, issue_receipt_remarks, non_refuse_inspect, non_refuse_inspect_remarks,
non_refuse_sign, non_refuse_sign_remarks, fixed_dispense, fixed_dispense_remarks,
no_open_flame, no_open_flame_remarks, max_length_dispense, max_length_dispense_remarks,
peso_display, peso_display_remarks, pump_island, pump_island_remarks,
lane_oriented_pump, lane_oriented_pump_remarks, pump_guard, pump_guard_remarks,
m_ingress, m_ingress_remarks, m_edge, m_edge_remarks,
office_cashier, office_cashier_remarks, min_canopy, min_canopy_remarks,
boundary_walls, boundary_walls_remarks, master_switch, master_switch_remarks,
clean_rest, clean_rest_remarks, underground_storage, underground_storage_remarks,
m_distance, m_distance_remarks, vent, vent_remarks,
transfer_dispense, transfer_dispense_remarks, no_drum, no_drum_remarks,
no_hoard, no_hoard_remarks, free_tire_press, free_tire_press_remarks,
free_water, free_water_remarks, basic_mechanical, basic_mechanical_remarks,
first_aid, first_aid_remarks, design_eval, design_eval_remarks,
electric_eval, electric_eval_remarks, under_deliver, under_deliver_remarks
) VALUES (
UUID(), ?,
?, ?, ?, ?,
?, ?, ?, ?,
?, ?, ?, ?,
?, ?, ?, ?,
?, ?, ?, ?,
?, ?, ?, ?,
?, ?, ?, ?,
?, ?, ?, ?,
?, ?, ?, ?,
?, ?, ?, ?,
?, ?, ?, ?,
?, ?, ?, ?,
?, ?, ?, ?,
?, ?, ?, ?,
?, ?, ?, ?,
?, ?, ?, ?,
?, ?, ?, ?,
?, ?, ?, ?,
?, ?, ?, ?,
?, ?, ?, ?,
?, ?, ?, ?,
?, ?, ?, ?,
?, ?, ?, ?,
?, ?, ?, ?,
?, ?, ?, ?,
?, ?, ?, ?,
?, ?, ?, ?,
?, ?, ?, ?
)");

$stmt->execute([
$itrFormNum,
// All the values from your original execute() call
$data['coc_cert'], $data['coc_cert_remarks'], $data['coc_posted'], $data['coc_posted_remarks'],
$data['valid_permit_LGU'], $data['valid_permit_LGU_remarks'], $data['valid_permit_BFP'], $data['valid_permit_BFP_remarks'],
$data['valid_permit_DENR'], $data['valid_permit_DENR_remarks'], $data['appropriate_test'], $data['appropriate_test_remarks'],
$data['week_calib'], $data['week_calib_remarks'], $data['outlet_identify'], $data['outlet_identify_remarks'],
$data['price_display'], $data['price_display_remarks'], $data['pdb_entry'], $data['pdb_entry_remarks'],
$data['pdb_updated'], $data['pdb_updated_remarks'], $data['pdb_match'], $data['pdb_match_remarks'],
$data['ron_label'], $data['ron_label_remarks'], $data['e10_label'], $data['e10_label_remarks'],
$data['biofuels'], $data['biofuels_remarks'], $data['consume_safety'], $data['consume_safety_remarks'],
$data['cel_warn'], $data['cel_warn_remarks'], $data['smoke_sign'], $data['smoke_sign_remarks'],
$data['switch_eng'], $data['switch_eng_remarks'], $data['straddle'], $data['straddle_remarks'],
$data['post_unleaded'], $data['post_unleaded_remarks'], $data['post_biodiesel'], $data['post_biodiesel_remarks'],
$data['issue_receipt'], $data['issue_receipt_remarks'], $data['non_refuse_inspect'], $data['non_refuse_inspect_remarks'],
$data['non_refuse_sign'], $data['non_refuse_sign_remarks'], $data['fixed_dispense'], $data['fixed_dispense_remarks'],
$data['no_open_flame'], $data['no_open_flame_remarks'], $data['max_length_dispense'], $data['max_length_dispense_remarks'],
$data['peso_display'], $data['peso_display_remarks'], $data['pump_island'], $data['pump_island_remarks'],
$data['lane_oriented_pump'], $data['lane_oriented_pump_remarks'], $data['pump_guard'], $data['pump_guard_remarks'],
$data['m_ingress'], $data['m_ingress_remarks'], $data['m_edge'], $data['m_edge_remarks'],
$data['office_cashier'], $data['office_cashier_remarks'], $data['min_canopy'], $data['min_canopy_remarks'],
$data['boundary_walls'], $data['boundary_walls_remarks'], $data['master_switch'], $data['master_switch_remarks'],
$data['clean_rest'], $data['clean_rest_remarks'], $data['underground_storage'], $data['underground_storage_remarks'],
$data['m_distance'], $data['m_distance_remarks'], $data['vent'], $data['vent_remarks'],
$data['transfer_dispense'], $data['transfer_dispense_remarks'], $data['no_drum'], $data['no_drum_remarks'],
$data['no_hoard'], $data['no_hoard_remarks'], $data['free_tire_press'], $data['free_tire_press_remarks'],
$data['free_water'], $data['free_water_remarks'], $data['basic_mechanical'], $data['basic_mechanical_remarks'],
$data['first_aid'], $data['first_aid_remarks'], $data['design_eval'], $data['design_eval_remarks'],
$data['electric_eval'], $data['electric_eval_remarks'], $data['under_deliver'], $data['under_deliver_remarks']
]);
}

// 6. Save suppliers info (update if exists)
if (!empty($data['supplier'])) {
// Check if record exists
$stmt = $conn->prepare("SELECT id FROM suppliersinfo WHERE itr_form_num = ? LIMIT 1");
$stmt->execute([$itrFormNum]);
$existingId = $stmt->fetchColumn();

if ($existingId) {
// Update existing record
$stmt = $conn->prepare("UPDATE suppliersinfo SET 
            supplier = ?,
            date_deliver = ?,
            address = ?,
            contact_num = ?
            WHERE id = ?");
$stmt->execute([
$data['supplier'],
$data['date_deliver'],
$data['address'],
$data['contact_num'],
$existingId
]);
} else {
// Insert new record
$stmt = $conn->prepare("INSERT INTO suppliersinfo (id, itr_form_num, supplier, date_deliver, address, contact_num)
            VALUES (UUID(), ?, ?, ?, ?, ?)");
$stmt->execute([
$itrFormNum,
$data['supplier'],
$data['date_deliver'],
$data['address'],
$data['contact_num']
]);
}
}

// 7. Save summary remarks (update if exists)
// Check if record exists
$stmt = $conn->prepare("SELECT id FROM summaryremarks WHERE itr_form_num = ? LIMIT 1");
$stmt->execute([$itrFormNum]);
$existingId = $stmt->fetchColumn();

if ($existingId) {
// Update existing record
$stmt = $conn->prepare("UPDATE summaryremarks SET 
        extracted_violations = ?,
        action_required = ?
        WHERE id = ?");
$stmt->execute([
$data['extracted_violations'],
$data['action_required'],
$existingId
]);
} else {
// Insert new record
$stmt = $conn->prepare("INSERT INTO summaryremarks (id, itr_form_num, extracted_violations, action_required)
        VALUES (UUID(), ?, ?, ?)");
$stmt->execute([
$itrFormNum,
$data['extracted_violations'],
$data['action_required']
]);
}

    
    // Commit transaction
    $conn->commit();
    
    echo json_encode(['success' => true]);
    
} catch(PDOException $e) {
    $conn->rollBack();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

$conn = null;
?>