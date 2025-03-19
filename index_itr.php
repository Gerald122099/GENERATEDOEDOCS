<?php
session_start();

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
        $_SESSION['success'] = "Record inserted successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    
    $conn->close();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inspection Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="itrcss.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header" style="background-image: url('/generateDOEdocs/header.jpg'); background-size: cover; background-position: center; height: 200px;">
        </div>
        <div class="container mt-5">
        <h2>Business Information Form</h2>
        <form action="insert.php" method="POST">
            <div class="mb-3">
                <label class="form-label">ITR Form Number</label>
                <input type="text" class="form-control" name="itr_form_num" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Business Name</label>
                <input type="text" class="form-control" name="business_name" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Dealer Operator</label>
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
                <label class="form-label">Company</label>
                <input type="text" class="form-control" name="company" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Contact Telephone</label>
                <input type="number" class="form-control" name="contact_tel" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <input type="email" class="form-control" name="email_add" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Outlet Classification</label>
                <select class="form-control" name="outlet_classif" required>
                    <option value="COCO">COCO</option>
                    <option value="CODO">CODO</option>
                    <option value="DODO">DODO</option>
                </select>
            </div>
           
            <h4>Standards Compliance Checklist</h4>
            <div class="mb-3">
                <label class="form-label">COC Certificate</label><br>
                <input type="radio" name="coc_certificate" value="1"> Yes
                <input type="radio" name="coc_certificate" value="0"> No
            </div>
            <div class="mb-3">
                <label class="form-label">Valid Permits</label><br>
                <input type="radio" name="valid_permits" value="1"> Yes
                <input type="radio" name="valid_permits" value="0"> No
            </div>
            
            <h4>General Remarks</h4>
            <div class="mb-3">
                <label class="form-label">Remarks</label>
                <textarea class="form-control" name="general_remarks"></textarea>
            </div>
            
            <h4>Summary Remarks</h4>
            <div class="mb-3">
                <label class="form-label">Field Name</label>
                <input type="text" class="form-control" name="summary_field_name">
            </div>
            <div class="mb-3">
                <label class="form-label">Remarks</label>
                <textarea class="form-control" name="summary_remarks"></textarea>
            </div>
            
            <h4>Product Quality</h4>
            <div class="mb-3">
                <label class="form-label">Code Value</label>
                <input type="text" class="form-control" name="code_value">
            </div>
            <div class="mb-3">
                <label class="form-label">Product</label>
                <input type="text" class="form-control" name="product">
            </div>
            <div class="mb-3">
                <label class="form-label">RON Value</label>
                <input type="text" class="form-control" name="ron_value">
            </div>
            <div class="mb-3">
                <label class="form-label">Source</label>
                <select class="form-control" name="source">
                    <option value="UGT">UGT</option>
                    <option value="Pump">Pump</option>
                </select>
            </div>
            
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
        </div>


        <div class="columnlower1">
        <h2>Inspection Form1</h2>
  
</div>

<div class="columnlower2">
        <h2>Inspection Form</h2>  
</div>
        



    

</div>

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
    
</body>
</html>
