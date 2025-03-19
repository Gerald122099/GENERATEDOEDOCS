<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "inspection_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $itr_form_number = $_POST['itr_form_number'];
    $business_name = $_POST['business_name'];
    $dealer_operator = $_POST['dealer_operator'];
    $location = $_POST['location'];
    $in_charge = $_POST['in_charge'];
    $designation = $_POST['designation'];
    $inspection_datetime = $_POST['inspection_datetime'];
    $sa_no_date = $_POST['sa_no_date'];
    $outlet_classification = $_POST['outlet_classification'];
    $company = $_POST['company'];
    $contact_no = $_POST['contact_no'];
    $email = $_POST['email'];
    
    $checkboxes = [];
    for ($i = 1; $i <= 45; $i++) {
        $checkboxes["item_$i"] = isset($_POST["item_$i"]) ? $_POST["item_$i"] : 'No';
    }
    
    $sql = "INSERT INTO inspection (itr_form_number, business_name, dealer_operator, location, in_charge, designation, inspection_datetime, sa_no_date, outlet_classification, company, contact_no, email, " .
        implode(", ", array_keys($checkboxes)) . ") VALUES (" .
        "'$itr_form_number', '$business_name', '$dealer_operator', '$location', '$in_charge', '$designation', '$inspection_datetime', '$sa_no_date', '$outlet_classification', '$company', '$contact_no', '$email', '" .
        implode("', '", $checkboxes) . "')";
    
    if ($conn->query($sql) === TRUE) {
        echo "Record inserted successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inspection Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Inspection Form</h2>
    <form method="post">
        <div class="mb-3">
            <label class="form-label">ITR Form Number</label>
            <input type="text" class="form-control" name="itr_form_number" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Business Name</label>
            <input type="text" class="form-control" name="business_name" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Dealer/Operator</label>
            <input type="text" class="form-control" name="dealer_operator" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Location</label>
            <input type="text" class="form-control" name="location" required>
        </div>
        <div class="mb-3">
            <label class="form-label">In Charge</label>
            <input type="text" class="form-control" name="in_charge" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Designation</label>
            <input type="text" class="form-control" name="designation" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Date/Time of Inspection</label>
            <input type="datetime-local" class="form-control" name="inspection_datetime" required>
        </div>
        <div class="mb-3">
            <label class="form-label">S/A No. / Date</label>
            <input type="text" class="form-control" name="sa_no_date" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Outlet Classification</label>
            <select class="form-control" name="outlet_classification">
                <option value="COCO">COCO</option>
                <option value="CODO">CODO</option>
                <option value="DODO">DODO</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Company</label>
            <input type="text" class="form-control" name="company" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Contact / Tel. No.</label>
            <input type="text" class="form-control" name="contact_no" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email Address</label>
            <input type="email" class="form-control" name="email" required>
        </div>
        
        <h4>Inspection Checklist</h4>
        <?php 
        $items = [
            "Certificate of Compliance (COC)",
            "COC posted within business premises",
            "Valid Permits: (LGU / BFP / DENR)",
            "Appropriate Test Measure/Year Calibrated",
            "Weekly Calibration Record/ Logbook",
            "Outlet's Identification/Trademark",
            "Price Display Board (PDB)",
            "PDB w/ entry/ies",
            "PDB w/ updated prices",
            "Price in PDB and dispensing pumps match",
            "Research Octane Number (RON) Labels for Gasoline",
            "E-10 Label (contains 10% Bio-Ethanol) for Gasoline",
            "Biofuels (B₂) Labels for Diesel",
            "Consumer Safety and Informational Signs",
            "No Cellphone Warning Sign",
            "No Smoking Sign",
            "Switch Off Engine while Filling Sign",
            "No Straddling Sign (motorbike/tricycle)",
            "Non-posting of the term 'unleaded'",
            "Non-posting of the term 'biodiesel'",
            "Issuance of Official Receipts",
        ];
        
        foreach ($items as $index => $item): ?>
            <div class="mb-2">
                <label><?php echo ($index + 1) . ". " . $item; ?></label>
                <input type="checkbox" name="item_<?php echo ($index + 1); ?>" value="Yes"> Yes
                <input type="checkbox" name="item_<?php echo ($index + 1); ?>" value="No"> No
            </div>
        <?php endforeach; ?>
        
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
    <div class="container mt-5">
    <h2>Generate Inspection Report</h2>
    <form action="generate.php" method="post">
        <div class="mb-3">
            <label class="form-label">Select ITR Form Number</label>
            <select class="form-control" name="itr_form_number_selected">
                <?php include 'fetch_itr.php'; ?>
            </select>
        </div>
        <button type="submit" name="generate_pdf" class="btn btn-success">Generate PDF</button>
    </form>
</div>
</div>
</body>
</html>
