<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "itr_database";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


session_start();
function checkLogin() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: index.php");
        exit();
    }
}



function allowAccess(){
$allowed_roles = ['admin', 'head', 'inspector', 'legal'];
if(!isset($_SESSION['role']) || !in_array($_SESSION['role'], $allowed_roles)) {
    header("location: index.php");
    exit;
}
 }


 function requireAdmin() {
    if ($_SESSION['role'] !== 'admin') {
        header('Location: index.php');
        exit();
    }
}


$violation_pairs = [
    ['coc_cert', 'a.1 Certificate of Compliance (COC)', 'coc_cert_remarks'],
    ['coc_posted', 'a.2 COC posted within business premises', 'coc_posted_remarks'],
    ['valid_permit_LGU', 'b.1 Valid Permit LGU', 'valid_permit_LGU_remarks'],
    ['valid_permit_BFP', 'b.2 Valid Permit BFP', 'valid_permit_BFP_remarks'],
    ['valid_permit_DENR', 'b.3 Valid Permit DENR', 'valid_permit_DENR_remarks'],
    ['appropriate_test', 'c. Appropriate Test Measure/Year Calibrated', 'appropriate_test_remarks'],
    ['week_calib', 'd. Weekly Calibration Record/ Logbook', 'week_calib_remarks'],
    ['outlet_identify', 'e. Outlet\'s Identification/Trademark', 'outlet_identify_remarks'],
    ['pdb_entry', 'f.1 PDB w/ entry/ies', 'pdb_entry_remarks'],
    ['pdb_updated', 'f.2 PDB w/ updated prices', 'pdb_updated_remarks'],
    ['pdb_match', 'f.3 Price in PDB and dispensing pumps match', 'pdb_match_remarks'],
    ['ron_label', 'g. Research Octane Number (RON) Labels for Gasoline', 'ron_label_remarks'],
    ['e10_label', 'h. E-10 Label (contains 10% Bio-Ethanol) for Gasoline', 'e10_label_remarks'],
    ['biofuels', 'i. Biofuels (B₂) Labels for Diesel', 'biofuels_remarks'],
    ['consume_safety', 'j. Consumer Safety and Informational Signs', 'consume_safety_remarks'],
    ['cel_warn', 'j.1 No Cellphone Warning Sign', 'cel_warn_remarks'],
    ['smoke_sign', 'j.2 No Smoking Sign', 'smoke_sign_remarks'],
    ['switch_eng', 'j.3 Switch Off Engine while Filling Sign', 'switch_eng_remarks'],
    ['straddle', 'j.4 No Straddling Sign (motorbike/tricycle)', 'straddle_remarks'],
    ['post_unleaded', 'k. Non-posting of the term "unleaded"', 'post_unleaded_remarks'],
    ['post_biodiesel', 'l. Non-posting of the term "biodiesel"', 'post_biodiesel_remarks'],
    ['issue_receipt', 'm. Issuance of Official Receipts', 'issue_receipt_remarks'],
    ['non_refuse_inspect', 'n. Non-refusal to Conduct Inspection', 'non_refuse_inspect_remarks'],
    ['non_refuse_sign', 'n.1 Non-refusal to Conduct Sign ITRF', 'non_refuse_sign_remarks'],
    ['fixed_dispense', 'o.1 Fixed & permanent dispensing pump 6 meters from any potential source of ignition', 'fixed_dispense_remarks'],
    ['no_open_flame', 'o.2 No open flame within 15 meters', 'no_open_flame_remarks'],
    ['max_length_dispense', 'o.3 5.5-meter maximum length of dispensing hose', 'max_length_dispense_remarks'],
    ['peso_display', 'o.4 Volume-Peso amount display up to two decimal places', 'peso_display_remarks'],
    ['pump_island', 'p.1 Pump Island', 'pump_island_remarks'],
    ['lane_oriented_pump', 'p.2 Lane-oriented pump with min. distance of 0.05 m from fixed object', 'lane_oriented_pump_remarks'],
    ['pump_guard', 'p.3 Pump guard/column post as safety barrier', 'pump_guard_remarks'],
    ['m_ingress', 'p.4 7 m ingress/egress', 'm_ingress_remarks'],
    ['m_edge', 'p.5 6 m edge to edge distance between pump islands', 'm_edge_remarks'],
    ['office_cashier', 'q.1 Office/cashier\'s booth', 'office_cashier_remarks'],
    ['min_canopy', 'q.2 4.5 m minimum canopy height', 'min_canopy_remarks'],
    ['boundary_walls', 'q.3 Boundary walls (concrete or cyclone fence)', 'boundary_walls_remarks'],
    ['master_switch', 'q.4 Master switch in case of emergency', 'master_switch_remarks'],
    ['clean_rest', 'q.5 Clean restroom', 'clean_rest_remarks'],
    ['underground_storage', 'r.1 Underground storage tank (UGT) with rain tight fill sump and monitoring wells', 'underground_storage_remarks'],
    ['m_distance', 'r.2 1 m distance from property line and adjoining structure', 'm_distance_remarks'],
    ['vent', 'r.3 3.65 m vent lines', 'vent_remarks'],
    ['transfer_dispense', 's.1 Transfer/dispensing on approved containers only', 'transfer_dispense_remarks'],
    ['no_drum', 's.2 No Drumming / "Bote-Bote" of Liquid Fuels', 'no_drum_remarks'],
    ['no_hoard', 't.1 No Hoarding', 'no_hoard_remarks'],
    ['free_tire_press', 'u.1 Offers free tire pressure air filling', 'free_tire_press_remarks'],
    ['free_water', 'u.2 Offers free water for radiator', 'free_water_remarks'],
    ['basic_mechanical', 'u.3 Basic mechanical services', 'basic_mechanical_remarks'],
    ['first_aid', 'u.4 First aid kits', 'first_aid_remarks'],
    ['design_eval', 'u.5 Designated evacuation assembly area', 'design_eval_remarks'],
    ['electric_eval', 'u.6 Electric vehicle charging facility', 'electric_eval_remarks'],
    ['under_deliver', 'Under Deliver', 'under_deliver_remarks']
];




function logout() {
    // Start the session if not already started
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    // Unset all session variables
    $_SESSION = array();
    
    // Destroy the session
    session_destroy();
    
    // Redirect to login page or home page
    header("Location: home.php"); // Change this to your desired redirect location
    exit();
}


function DeleteEntry(){
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Start transaction
    $conn->beginTransaction();
    
    $itrFormNum = $_POST['itr_form_num'];
    
    // Delete relat  records first (to maintain referential integrity)
    $tables = [
        'generalremarks',
        'productquality',
        'productqualitycont',
        'standardcompliancechecklist',
        'suppliersinfo',
        'summaryremarks'
    ];
    
    foreach ($tables as $table) {
        $stmt = $conn->prepare("DELETE FROM $table WHERE itr_form_num = ?");
        $stmt->execute([$itrFormNum]);
    }
    
    // Finally delete the business info
    $stmt = $conn->prepare("DELETE FROM businessinfo WHERE itr_form_num = ?");
    $stmt->execute([$itrFormNum]);
    
    // Commit transaction
    $conn->commit();
    
    echo json_encode(['success' => true]);
    
} catch(PDOException $e) {
    $conn->rollBack();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

$conn = null;
}
?>


