

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inspection Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="itrcss.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
   <style>
    body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
}

.container {
    width: 100%;
    height:8000px;
    margin: 0 auto;
    border: 2px solid #ffffff;
    border-radius:10;
    padding: 10px;
    border-style: solid;
  border-color: red;
   
}
.divcol1{
  width: 50%;
  height: 600px;
    
    border: 2px solidrgb(115, 0, 0);
    border-radius:10;
    padding: 10px;
    border-style: solid;
  border-color: red;
  float: left;
}
.divcol2{
  width: 50%;
    border: 2px solidrgb(115, 0, 0);
    border-radius:10;
    padding: 10px;
    border-style: solid;
  border-color: red;
  height: 600px;
  float: right;
}

  .divcol3{
    width: 50%;
    border: 2px solidrgb(115, 0, 0);
    border-radius:10;
    padding: 10px;
    border-style: solid;
  border-color: red;
  float:left;
}
.divcol4{
    width: 50%;
    border: 2px solidrgb(115, 0, 0);
    border-radius:10;
    padding: 10px;
    border-style: solid;
    border-color: red;
    float:right;
  
 
  
}
.header {
    text-align: center;
    padding: 10px;
    background-color: #ffffff;
    border: 2px solid #ffffff;
    margin-bottom: 10px;
    border-radius: 10px;
}

.nav {
    display: flex;
    justify-content: space-between;
    margin-bottom: 10px;
}

    

.mb-3 {
    margin-bottom: 1rem; 
    text-align: left;
}
.col1 {
   width: 400px;
   margin-bottom: 1rem; 
   text-align: left;
}


.mandatorytitle{
  width: 100%;
  border-style: solid;
  border-color: red;
  padding: 2rem 2rem;
  text-align: center;
  float:right;
  
}
h5{
  text-align:center;
  color:red;
  border-style:solid;
 
  font-size: 30px;

}
.form-group {
    display: flex;
    align-items: left; /* Vertically centers the label and input */
    margin-bottom: 1rem; /* Add margin-bottom to increase spacing */
}

.form-label {
    margin-right: 10px; 
    width: 70%;
    text-align: left;
    border-style: solid;
    color: blue;

}


.form-control {
    flex: 1; /* Allows the input to take up remaining space */
    padding: 0.375rem 0.75rem;
    font-size: 1rem;
    line-height: 1;
   
    border-radius: 0.25rem;
    border: none;
    border-bottom: 2px solid black; /* Bottom border only */
    outline: none; /* Removes the default focus outline */
    padding: 5px; /* Optional: Adds spacing */
}


.sampling-section {
    display: none; /* Hide sampling section by default */
  }

    </style>
</head>
<body>
    <div class="container" >
        <!-- Header -->
        <div class="header" style="background-image: url('/generateDOEdocs/header.jpg'); background-size: cover; background-position: center; height: 200px;">
</div>
<h2 class="mb-4">Business Information Form</h2>
 <div class= "divcol1">
    <form action="submit_form.php" method="POST">
      <!-- ITR Form Number -->
      <div class="col1"  >
        <label for="itr_form_num" class="form-label">ITR Form Number</label>
        <input type="text" class="form-control" id="itr_form_num" name="itr_form_num" required>
      </div>

      <!-- Business Name -->
      <div class="col1">
        <label for="business_name" class="form-label">Business Name</label>
        <input type="text" class="form-control" id="business_name" name="business_name">
      </div>

      <!-- Dealer/Operator -->
      <div class="col1">
        <label for="dealer_operator" class="form-label">Dealer/Operator</label>
        <input type="text" class="form-control" id="dealer_operator" name="dealer_operator">
      </div>

      <!-- Location -->
      <div class="col1">
        <label for="location" class="form-label">Location</label>
        <input type="text" class="form-control" id="location" name="location">
      </div>

      <!-- In Charge -->
      <div class="col1">
        <label for="in_charge" class="form-label">In Charge</label>
        <input type="text" class="form-control" id="in_charge" name="in_charge">
      </div>

      <!-- Designation -->
      <div class="mb-3">
        <label for="designation" class="form-label">Designation</label>
        <input type="text" class="form-control" id="designation" name="designation">
      </div>

</div>

<div class= "divcol2">
      <!-- SA Number -->
      <div class="mb-3">
        <label for="sa_no" class="form-label">SA Number</label>
        <input type="number" class="form-control" id="sa_no" name="sa_no">
      </div>

      <!-- SA Date -->
      <div class="mb-3">
        <label for="sa_date" class="form-label">SA Date</label>
        <input type="date" class="form-control" id="sa_date" name="sa_date">
      </div>

      <!-- Outlet Classification -->
      <div class="mb-3">
        <label for="outlet_classif" class="form-label">Outlet Classification</label>
        <select class="form-select" id="outlet_classif" name="outlet_classif">
          <option value="COCO">COCO</option>
          <option value="CODO">CODO</option>
          <option value="DODO">DODO</option>
        </select>
      </div>

      <!-- Company -->
      <div class="mb-3">
        <label for="company" class="form-label">Company</label>
        <input type="text" class="form-control" id="company" name="company">
      </div>

      <!-- Contact Telephone -->
      <div class="mb-3">
        <label for="contact_tel" class="form-label">Contact Telephone</label>
        <input type="number" class="form-control" id="contact_tel" name="contact_tel">
      </div>

      <!-- Email Address -->
      <div class="mb-3">
        <label for="email_add" class="form-label">Email Address</label>
        <input type="email" class="form-control" id="email_add" name="email_add">
      </div>
</div>
<div class="mandatorytitle">
  <h5>MANDATORY AND MINIMUM STANDARDS & REQUIREMENTS</h5>
</div>
<div class="divcol3">
       <!-- COC Certificate Section -->
       <div class="form-section">
        <div class="mb-3">
          <label for="coc_certificate" class="form-label">a.1 Certificate of Compliance (COC)</label>
          <input type="checkbox" class="form-check-input" id="coc_certificate" name="coc_certificate" value="1">
        </div>
        <div class="mb-3">
          <label for="coc_cert_remarks" class="form-label">Remarks</label>
          <input type="text" class="form-control" id="coc_cert_remarks" name="coc_cert_remarks">
        </div>
        <div class="mb-3">
          <label for="coc_posted" class="form-label">a.2 COC posted within business premises</label>
          <input type="checkbox" class="form-check-input" id="coc_posted" name="coc_posted" value="1">
        </div>
</div>

      <!-- Valid Permits Section -->
      <div class="form-section">
        <div class="mb-3">
          <label for="valid_permits" class="form-label">b. Valid Permits:</label>
        </div>
        <div class="mb-3" style="float:left">
          <label for="valid_permit_LGU" style= "width:160px"class="form-label">Valid Permit LGU</label>
          <input type="checkbox"  class="form-check-input" id="valid_permit_LGU" name="valid_permit_LGU" value="1">
      </div>
        <div class="mb-3" style="float:left">
          <label for="valid_permit_BFP" style= "width:160px" class="form-label">Valid Permit BFP</label>
          <input type="checkbox" class="form-check-input" id="valid_permit_BFP" name="valid_permit_BFP" value="1">
        </div>
        <div class="mb-3" style="float:rigth">
          <label for="valid_permit_DENR" style= "width:160px" class="form-label">Valid Permit DENR</label>
          <input type="checkbox" class="form-check-input" id="valid_permit_DENR" name="valid_permit_DENR" value="1">
        </div>
        <div class="mb-3">
          <label for="valid_permit_LGU_remarks" class="form-label">Remarks</label>
          <input type="text" class="form-control" id="valid_permit_LGU_remarks" name="valid_permit_LGU_remarks">
        </div>
      </div>

      <!-- Testing and Calibration Section -->
      <div class="form-section">
        <div class="mb-3">
          <label for="appropriate_test" class="form-label">c. Appropriate Test Measure/Year Calibrated </label>
          <input type="checkbox" class="form-check-input" id="appropriate_test" name="appropriate_test" value="1">
        </div>
        <div class="mb-3">
          <label for="appropriate_test_remarks" class="form-label">Remarks</label>
          <input type="text" class="form-control" id="appropriate_test_remarks" name="appropriate_test_remarks">
        </div>
        <div class="mb-3">
          <label for="week_calib" class="form-label">d. Weekly Calibration Record/ Logbook</label>
          <input type="checkbox" class="form-check-input" id="week_calib" name="week_calib" value="1">
        </div>
</div>
    

      <!-- Outlet Identification Section -->
      <div class="form-section">
        <div class="mb-3">
          <label for="outlet_identify" class="form-label">e. Outlet's Identification/Trademark</label>
          <input type="checkbox" class="form-check-input" id="outlet_identify" name="outlet_identify" value="1">
        </div>
</div>
   

      <!-- Price and PDB Section -->
      <div class="form-section">
        <div class="mb-3">
          <label for="price_display" class="form-label">f. Price Display Board (PDB)</label>
        </div>
        <div class="mb-3">
          <label for="pdb_entry" class="form-label">f.1. PDB w/ entry/ies</label>
          <input type="checkbox" class="form-check-input" id="pdb_entry" name="pdb_entry" value="1">
        </div>
        <div class="mb-3">
          <label for="pdb_updated" class="form-label">f.2. PDB w/ updated prices</label>
          <input type="checkbox" class="form-check-input" id="pdb_updated" name="pdb_updated" value="1">
        </div>
        <div class="mb-3">
          <label for="pdb_match" class="form-label">f.3. Price in PDB and dispensing pumps match</label>
          <input type="checkbox" class="form-check-input" id="pdb_match" name="pdb_match" value="1">
        </div>
        <div class="mb-3">
          <label for="pdb_match_remarks" class="form-label">Remarks</label>
          <input type="text" class="form-control" id="price_remarks" name="pdb_match_remarks">
        </div>
      </div>

      <!-- Labels and Biofuels Section -->
      <div class="form-section">
        <div class="mb-3">
          <label for="ron_label" class="form-label">g. Research Octane Number (RON) Labels for Gasoline</label>
          <input type="checkbox" class="form-check-input" id="ron_label" name="ron_label" value="1">
        </div>

        <div class="mb-3">
          <label for="e10_label" class="form-label">h. E-10 Label (contains 10% Bio-Ethanol) for Gasolinel</label>
          <input type="checkbox" class="form-check-input" id="e10_label" name="e10_label" value="1">
        </div>
       
        <div class="mb-3">
          <label for="biofuels" class="form-label">i. Biofuels (B₂) Labels for Diesel </label>
          <input type="checkbox" class="form-check-input" id="biofuels" name="biofuels" value="1">
        </div>
       
      </div>

      <!-- Consumer Safety Section -->
      <div class="form-section">
        <div class="mb-3">
          <label for="consumer_safety" class="form-label">j. Consumer Safety and Informational Signs</label>
          <input type="checkbox" class="form-check-input" id="consumer_safety" name="consumer_safety" value="1">
        </div>
        <div class="mb-3">
          <label for="no_cel_warn" class="form-label">j.1 No Cellphone Warning Sign</label>
          <input type="checkbox" class="form-check-input" id="no_cel_warn" name="no_cel_warn" value="1">
        </div>
        <div class="mb-3">
          <label for="no_straddle_remarks" class="form-label">Remarks</label>
          <input type="text" class="form-control" id="consumer_remarks" name="consumer_remarks">
        </div>
        <div class="mb-3">
          <label for="no_smoke_sign" class="form-label">j.2 No Smoking Sign</label>
          <input type="checkbox" class="form-check-input" id="no_smoke_sign" name="no_smoke_sign" value="1">
        </div>
        <div class="mb-3">
          <label for="switch_eng" class="form-label">j.3 Switch Off Engine while Filling Sign</label>
          <input type="checkbox" class="form-check-input" id="switch_eng" name="switch_eng" value="1">
        </div>
        <div class="mb-3">
          <label for="no_straddle" class="form-label">j.4 No Straddling Sign (motorbike/tricycle)</label>
          <input type="checkbox" class="form-check-input" id="no_straddle" name="no_straddle" value="1">
        </div>
    
        <div class="mb-3">
          <label for="non_post_unleaded" class="form-label">k. Non-posting of the term "unleaded" </label>
          <input type="checkbox" class="form-check-input" id="non_post_unleaded" name="non_post_unleaded" value="1">
        </div>
        <div class="mb-3">
          <label for="non_post_biodiesel" class="form-label">l. Non-posting of the term "biodiesel"</label>
          <input type="checkbox" class="form-check-input" id="non_post_biodiesel" name="non_post_biodiesel" value="1">
        </div>
       
      </div>
      
      <!-- Receipts and Inspections Section -->
      <div class="form-section">
        <div class="mb-3">
          <label for="issue_receipt" class="form-label">m. Issuance of Official Receipts</label>
          <input type="checkbox" class="form-check-input" id="issue_receipt" name="issue_receipt" value="1">
        </div>
       
        <div class="mb-3">
          <label for="non_refuse_inspect" class="form-label">n. Non-refusal to Conduct Inspection/Sign ITRF</label>
          <input type="checkbox" class="form-check-input" id="non_refuse_inspect" name="non_refuse_inspect" value="1">
        </div>
      </div>

      <!-- Dispensing and Safety Section -->
      <div class="form-section">
        <div class="mb-3">
          <label for="fixed_dispense" class="form-label">o.1 Fixed & permanent dispensing pump 6 meters from any potential source of ignition</label>
          <input type="checkbox" class="form-check-input" id="fixed_dispense" name="fixed_dispense" value="1">
        </div>
        <div class="mb-3">
          <label for="fixed_dispense_remarks" class="form-label">Remarks</label>
          <input type="text" class="form-control" id="fixed_dispense_remarks" name="fixed_dispense_remarks">
        </div>
        <div class="mb-3">
          <label for="no_open_flame" class="form-label">o.2 No open flame within15 meters</label>
          <input type="checkbox" class="form-check-input" id="no_open_flame" name="no_open_flame" value="1">
        </div>
        <div class="mb-3">
          <label for="max_length_dispense" class="form-label">o.3 5.5-meter maximum length of dispensing hosee</label>
          <input type="checkbox" class="form-check-input" id="max_length_dispense" name="max_length_dispense" value="1">
        </div>
        <div class="mb-3">
          <label for="max_length_dispense_remarks" class="form-label">Remarks</label>
          <input type="text" class="form-control" id="max_length_dispense_remarks" name="max_length_dispense_remarks">
        </div>
        <div class="mb-3">
          <label for="peso_display" class="form-label">o.4 Volume-Peso amount display up to two decimal places</label>
          <input type="checkbox" class="form-check-input" id="peso_display" name="peso_display" value="1">
        </div>
      
      </div>

</div>


<div class="divcol4">

      <!-- Pump and Infrastructure Section -->
      <div class="form-section">
       
        <div class="mb-3">
          <label for="pump_island" class="form-label">p.1 Pump Island with minimum 3.5 m x 1.2 m dimension</label>
          <input type="checkbox" class="form-check-input" id="pump_island" name="pump_island" value="1">
        </div>
        <div class="mb-3">
          <label for="pump_island_remarks" class="form-label">Remarks</label>
          <input type="text" class="form-control" id="pump_island_remarks" name="pump_island_remarks">
        </div>
        <div class="mb-3">
          <label for="lane_oriented_pump" class="form-label">p.2 Lane-oriented pump with min. distance of 0.05 m from fixed object
          </label>
          <input type="checkbox" class="form-check-input" id="lane_oriented_pump" name="lane_oriented_pump" value="1">
        </div>
        <div class="mb-3">
          <label for="lane_oriented_pump_remarks" class="form-label">Remarks</label>
          <input type="text" class="form-control" id="lane_oriented_pump_remarks" name="lane_oriented_pump_remarks">
        </div>
        <div class="mb-3">
          <label for="pump_guard" class="form-label">p.3 Pump guard/column post as safety barrier</label>
          <input type="checkbox" class="form-check-input" id="pump_guard" name="pump_guard" value="1">
        </div>
        
        <div class="mb-3">
          <label for="m_ingress" class="form-label">p.4 7 m ingress/egress</label>
          <input type="checkbox" class="form-check-input" id="m_ingress" name="m_ingress" value="1">
        </div>
        <div class="mb-3">
          <label for="m_ingress_remarks" class="form-label">Remarks</label>
          <input type="text" class="form-control" id="m_ingress_remarks" name="m_ingress_remarks">
        </div>
        <div class="mb-3">
          <label for="m_edge" class="form-label">p.5 6 m edge to edge distance between pump islands</label>
          <input type="checkbox" class="form-check-input" id="m_edge" name="m_edge" value="1">
        </div>
        <div class="mb-3">
          <label for="m_edge_remarks" class="form-label">Remarks</label>
          <input type="text" class="form-control" id="m_edge_remarks" name="m_edge_remarks">
        </div>
        <div class="mb-3">
          <label for="office_cashier" class="form-label">q.1 Office/cashier's booth</label>
          <input type="checkbox" class="form-check-input" id="office_cashier" name="office_cashier" value="1">
        </div>
        <div class="mb-3">
          <label for="min_canopy" class="form-label">q.2 4.5 m minimum canopy height</label>
          <input type="checkbox" class="form-check-input" id="min_canopy" name="min_canopy" value="1">
        </div>
        <div class="mb-3">
          <label for="min_canopy_remarks" class="form-label">Remarks</label>
          <input type="text" class="form-control" id="min_canopy_remarks" name="min_canopy_remarks">
        </div>
        <div class="mb-3">
          <label for="boundary_walls" class="form-label">q.3 Boundary walls (concrete or cyclone fence)s</label>
          <input type="checkbox" class="form-check-input" id="boundary_walls" name="boundary_walls" value="1">
        </div>
        <div class="mb-3">
          <label for="master_switch" class="form-label">q.4 Master switch in case of emergency</label>
          <input type="checkbox" class="form-check-input" id="master_switch" name="master_switch" value="1">
        </div>
       
        <div class="mb-3">
          <label for="clean_rest" class="form-label">q.5 Clean restroom</label>
          <input type="checkbox" class="form-check-input" id="clean_rest" name="clean_rest" value="1">
        </div>
        <div class="mb-3">
          <label for="underground_storage" class="form-label">r.1 Underground storage tank (UGT) with rain tight fill sump and monitoring wells
          </label>
          <input type="checkbox" class="form-check-input" id="underground_storage" name="underground_storage" value="1">
        </div>
      
        <div class="mb-3">
          <label for="m_distance" class="form-label">r.2 1 m distance from property line and adjoining structure</label>
          <input type="checkbox" class="form-check-input" id="m_distance" name="m_distance" value="1">
        </div>
        <div class="mb-3">
          <label for="m_distance_remarks" class="form-label">Remarks</label>
          <input type="text" class="form-control" id="m_distance_remarks" name="m_distance_remarks">
        </div>
        <div class="mb-3">
          <label for="vent" class="form-label">r.3 3.65 m vent lines</label>
          <input type="checkbox" class="form-check-input" id="vent" name="vent" value="1">
        </div>
        <div class="mb-3">
          <label for="vent_remarks" class="form-label">Remarks</label>
          <input type="text" class="form-control" id="vent_remarks" name="vent_remarks">
        </div>
        <div class="mb-3">
          <label for="transfer_dispense" class="form-label">s.1 Transfer/dispensing on approved containers only</label>
          <input type="checkbox" class="form-check-input" id="transfer_dispense" name="transfer_dispense" value="1">
        </div>
        
        <div class="mb-3">
          <label for="no_drum" class="form-label">s.2 No Drumming / "Bote-Bote" of Liquid Fuels</label>
          <input type="checkbox" class="form-check-input" id="no_drum" name="no_drum" value="1">
        </div>
        <div class="mb-3">
          <label for="no_drum_remarks" class="form-label">Remarks</label>
          <input type="text" class="form-control" id="no_drum_remarks" name="no_drum_remarks">
        </div>
        <div class="mb-3">
          <label for="no_hoard" class="form-label">t.1 No Hoarding</label>
          <input type="checkbox" class="form-check-input" id="no_hoard" name="no_hoard" value="1">
        </div>
  

      <!-- Free Services Section -->
      <div class="form-section">
        <div class="mb-3">
          <label for="free_tire_press" class="form-label">u1. Offers free tire pressure air filling</label>
          <input type="checkbox" class="form-check-input" id="free_tire_press" name="free_tire_press" value="1">
        </div>
        <div class="mb-3">
          <label for="free_water" class="form-label">u2. Offers free water for radiator
          </label>
          <input type="checkbox" class="form-check-input" id="free_water" name="free_water" value="1">
        </div>
        <div class="mb-3">
          <label for="basic_mechanical" class="form-label">u3.  Basic mechanical services</label>
          <input type="checkbox" class="form-check-input" id="basic_mechanical" name="basic_mechanical" value="1">
        </div>
        <div class="mb-3">
          <label for="first_aid" class="form-label">u4. First aid kits</label>
          <input type="checkbox" class="form-check-input" id="first_aid" name="first_aid" value="1">
        </div>
        <div class="mb-3">
          <label for="design_eval" class="form-label">u5. Designated evacuation assembly area</label>
          <input type="checkbox" class="form-check-input" id="design_eval" name="design_eval" value="1">
        </div>
  
        <div class="mb-3">
          <label for="electric_eval" class="form-label">u6. Electric vehicle charging facility</label>
          <input type="checkbox" class="form-check-input" id="electric_eval" name="electric_eval" value="1">
        </div>

        <div class="mb-3">
          <label for="under_deliver" class="form-label">Under Deliver</label>
          <input type="checkbox" class="form-check-input" id="under_deliver" name="under_deliver" value="1">
        </div>
        <div class="mb-3">
          <label for="under_deliver_remarks" class="form-label"> Remarks</label>
          <input type="text" class="form-control" id="under_deliver_remarks" name="under_deliver_remarks">
        </div>
      </div>


      




        <!-- Sampling -->
      <div class="mb-3 form-check">
        <input type="checkbox" class="form-check-input" id="sampling" name="sampling" value="1">
        <label class="form-check-label" for="sampling">Sampling</label>
      </div>
   


        <!-- Sampling Section (Hidden by Default) -->
        <div id="samplingSection" class="sampling-section">
        <h4 class="mb-3">Product Sampling</h4>
        <div id="samplingRows">
          <!-- Sampling Row Template -->
          <div class="sampling-row mb-3">
            <div class="row">
              <div class="col">
                <label for="code_value" class="form-label">Code Value</label>
                <input type="text" class="form-control" name="code_value[]">
              </div>
              <div class="col">
                <label for="product" class="form-label">Product</label>
                <input type="text" class="form-control" name="product[]">
              </div>
              <div class="col">
                <label for="ron_value" class="form-label">RON Value</label>
                <input type="text" class="form-control" name="ron_value[]">
              </div>
              <div class="col">
                <label for="UGT" class="form-label">UGT</label>
                <input type="text" class="form-control" name="UGT[]">
              </div>
              <div class="col">
                <label for="pump" class="form-label">Pump</label>
                <input type="text" class="form-control" name="pump[]">
              </div>
            </div>
          </div>
        </div>
        <!-- Add Row Button -->
        <button type="button" id="addRow" class="btn btn-secondary mb-3">Add Row</button>
      </div>

      <!-- Submit Button -->
      <button type="submit" class="btn btn-primary">Submit</button>
    </form>

    

    <script>
    document.addEventListener('DOMContentLoaded', function () {
      const samplingCheckbox = document.getElementById('sampling');
      const samplingSection = document.getElementById('samplingSection');
      const samplingRows = document.getElementById('samplingRows');
      const addRowButton = document.getElementById('addRow');

      // Show/hide sampling section based on checkbox
      samplingCheckbox.addEventListener('change', function () {
        if (this.checked) {
          samplingSection.style.display = 'block';
        } else {
          samplingSection.style.display = 'none';
          samplingRows.innerHTML = ''; // Clear rows when hiding
        }
      });

      // Add new row
      addRowButton.addEventListener('click', function () {
        const rowCount = document.querySelectorAll('.sampling-row').length;
        if (rowCount < 5) { // Limit to 5 rows
          const newRow = document.createElement('div');
          newRow.classList.add('sampling-row', 'mb-3');
          newRow.innerHTML = `
            <div class="row">
              <div class="col">
                <label for="code_value" class="form-label">Code Value</label>
                <input type="text" class="form-control" name="code_value[]">
              </div>
              <div class="col">
                <label for="product" class="form-label">Product</label>
                <input type="text" class="form-control" name="product[]">
              </div>
              <div class="col">
                <label for="ron_value" class="form-label">RON Value</label>
                <input type="text" class="form-control" name="ron_value[]">
              </div>
              <div class="col">
                <label for="UGT" class="form-label">UGT</label>
                <input type="text" class="form-control" name="UGT[]">
              </div>
              <div class="col">
                <label for="pump" class="form-label">Pump</label>
                <input type="text" class="form-control" name="pump[]">
              </div>
            </div>
          `;
          samplingRows.appendChild(newRow);
        } else {
          alert('Maximum of 5 rows allowed.');
        }
      });
    });
  </script>
  

  <script>
// Get the modal
var modal = document.getElementById("myModal");

// Get the button that opens the modal
var btn = document.getElementById("myBtn");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks the button, open the modal 
btn.onclick = function() {
  modal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
  modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}
</script>

</div>
    
</body>
</html>
