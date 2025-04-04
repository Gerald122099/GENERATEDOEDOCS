<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inspection Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="itrcss.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
        }
        
        body {
            margin: 0;
            padding: 10px;
            line-height: 1.6;
        }
        
        .container {
            width: 100%;
            display:block;
            max-width: 1000px;
            margin: 0 auto;
            padding: 10px;
            background-color: #fcfcfc;
            border-radius:18px;
            color:black;
            justify-content: center;
            height: auto;
            place-items: center;
            border-style: solid;
            border-color:rgb(233, 238, 255);
        }
        
        .header {
            background-image: url('/generateDOEdocs/header.jpg');
            background-size: cover;
            background-position: center;
            height: 150px;
            width: 100%;
            margin-bottom: 15px;
        }
        
        h5 {
            text-align: center;
            margin: 15px 0;
            font-size: 1.2rem;
            color: #333;
        }
        
        .form-row {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -10px;
        }
        
        .div-form-checklist{
            padding: 0 10px;
            width: 100%;
        }
        .divcol1{
         padding: 0 10px;
         width:100%;   
        }

        .divcol2{
            padding: 0 10px;
            width:500px;
            border-style:solid;
            color:black;
        }
        .form-section {
            margin-bottom: 20px;
            padding: 15px;
            background: #f9f9f9;
            border-radius: 5px;
            border: 1px solid #ddd;
            width:100%;
            margin: 15px auto;
        }
        
        .mb-3 {
            margin-bottom: 15px;
            clear: both;
        }
        
        .form-label-mandatory, .label-remarks, .label-supplier, .label-sampling {
            display: block;
            margin-bottom: 5px;
            font-family: "Poppins", sans-serif;
            font-weight: 650;
            font-style: normal;
            float:left;  
            font-size: 16px;
        }
       
        .form-label-mandatory {
            color:rgb(61, 56, 96);
            float:left;  
            width: 70%;
        }
        
        .form-control, .form-control-mandatory, .form-control-sampling, .form-select {
            width: 80%;
            padding: 8px;
            font-size: 14px;
            border: none;
            border-bottom: 1px solid gray;
            background: #f9f9f9;
        }
        .form-control-supplier{
          width: 60%;
            padding: 8px;
            font-size: 14px;
            border: none;
            border-bottom: 1px solid gray;
            background: #f9f9f9;
        }
        
        /* Modified checkbox styles */
        .form-check-input {
            border-style: solid;
            border-color: #adb5bd;
            position:relative;
            left: 20px;
            height:25px;
            width: 25px;
            appearance: none;
            -webkit-appearance: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .form-check-input.checked {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }
        
        .form-check-input.wrong {
            background-color: #dc3545;
            border-color: #dc3545;
        }
        
        .form-check-input.wrong::after {
            content: "✕";
            color: white;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 16px;
            font-weight: bold;
        }
        
        .btn {
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
        }
        
        .btn-primary {
            background-color: #001f88;
            color: white;
        }
        
        .btn-secondary {
          background-color: #12ad00;
            color: white;
        }
        
        .sampling-section {
            display: none;
            margin-top: 20px;
        }
        
        .sampling-row {
            margin-bottom: 15px;
            padding: 10px;
            background: #f0f0f0;
            border-radius: 4px;
        }
        
        .form-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            width: 100%;
        }

        .form-column {
            flex: 1;
            min-width: 300px;
        }

        .form-group {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .form-label {
            width: 150px;
            margin-right: 10px;
            text-align: left;
        }

        .form-control, .form-select {
            flex: 1;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        @media (max-width: 768px) {
            .form-group {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .form-label {
                width: 100%;
                text-align: left;
                margin-bottom: 5px;
            }
            
            .form-control, .form-select {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <header><img src="header.jpg" alt="headerdoe" width="100%" height="auto" style="border-radius:15px"></header>
        <div style="text-align:center;">
            <h5>Business Information</h5>
        </div>
        <div class="divcol1">
            <form action="insert.php" method="POST">
                <div class="form-container">
                    <div class="form-column">
                        <!-- ITR Form Number -->
                        <div class="form-group">
                            <label for="itr_form_num" class="form-label">ITR Form Number</label>
                            <input type="text" class="form-control" id="itr_form_num" name="itr_form_num" required>
                        </div>

                        <!-- Business Name -->
                        <div class="form-group">
                            <label for="business_name" class="form-label">Business Name </label>
                            <input type="text" class="form-control" id="business_name" name="business_name">
                        </div>

                        <!-- Dealer/Operator -->
                        <div class="form-group">
                            <label for="dealer_operator" class="form-label">Dealer/Operator</label>
                            <input type="text" class="form-control" id="dealer_operator" name="dealer_operator">
                        </div>

                        <!-- Location -->
                        <div class="form-group">
                            <label for="location" class="form-label">Location</label>
                            <input type="text" class="form-control" id="location" name="location">
                        </div>

                        <!-- In Charge -->
                        <div class="form-group">
                            <label for="in_charge" class="form-label">In Charge</label>
                            <input type="text" class="form-control" id="in_charge" name="in_charge">
                        </div>

                        <!-- Designation -->
                        <div class="form-group">
                            <label for="designation" class="form-label">Designation</label>
                            <input type="text" class="form-control" id="designation" name="designation">
                        </div>
                    </div>

                    <div class="form-column">
                        <!-- SA Number -->
                        <div class="form-group">
                            <label for="sa_no" class="form-label">SA Number</label>
                            <input type="number" class="form-control" id="sa_no" name="sa_no">
                        </div>

                        <!-- SA Date -->
                        <div class="form-group">
                            <label for="sa_date" class="form-label">SA Date</label>
                            <input type="date" class="form-control" id="sa_date" name="sa_date">
                        </div>

                        <!-- Outlet Classification -->
                        <div class="form-group">
                            <label for="outlet_classif" class="form-label">Outlet Classification</label>
                            <select class="form-select" id="outlet_classif" name="outlet_classif">
                                <option value="COCO">COCO</option>
                                <option value="CODO">CODO</option>
                                <option value="DODO">DODO</option>
                            </select>
                        </div>

                        <!-- Company -->
                        <div class="form-group">
                            <label for="company" class="form-label">Company</label>
                            <input type="text" class="form-control" id="company" name="company">
                        </div>

                        <!-- Contact Telephone -->
                        <div class="form-group">
                            <label for="contact_tel" class="form-label">Contact Telephone</label>
                            <input type="number" class="form-control" id="contact_tel" name="contact_tel">
                        </div>

                        <!-- Email Address -->
                        <div class="form-group">
                            <label for="email_add" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email_add" name="email_add">
                        </div>
                    </div>
                </div>
                
                <div class="mandatorytitle">
                    <h5>MANDATORY AND MINIMUM STANDARDS & REQUIREMENTS</h5>
                </div>
                <div class="div-form-checklist">
                    <!-- COC Certificate Section -->
                    <div class="form-section">
                        <div class="mb-3">
                            <label for="coc_certificate" class="form-label-mandatory">a.1 Certificate of Compliance (COC)</label>
                            <input type="checkbox" class="form-check-input stateful-checkbox" id="coc_certificate" name="coc_certificate" value="1" data-state="0">
                        </div>
                        <div class="mb-3">
                            <label for="coc_cert_remarks" class="label-remarks">Remarks</label>
                            <input type="text" class="form-control-mandatory" id="coc_cert_remarks" name="coc_cert_remarks">
                        </div>
                        <div class="mb-3">
                            <label for="coc_posted" class="form-label-mandatory">a.2 COC posted within business premises</label>
                            <input type="checkbox" class="form-check-input stateful-checkbox" id="coc_posted" name="coc_posted" value="1" data-state="0">
                        </div>
                    </div>

                    <!-- Valid Permits Section -->
                    <div class="form-section">
                        <div class="mb-3">
                            <label for="valid_permits" class="form-label-mandatory">b. Valid Permits:</label>
                        </div>
                        <div class="mb-3">
                            <label for="valid_permit_LGU" class="form-label-mandatory">Valid Permit LGU</label>
                            <input type="checkbox" class="form-check-input stateful-checkbox" id="valid_permit_LGU" name="valid_permit_LGU" value="1" data-state="0">
                        </div>
                        <div class="mb-3">
                            <label for="valid_permit_BFP" class="form-label-mandatory">Valid Permit BFP</label>
                            <input type="checkbox" class="form-check-input stateful-checkbox" id="valid_permit_BFP" name="valid_permit_BFP" value="1" data-state="0">
                        </div>
                        <div class="mb-3">
                            <label for="valid_permit_DENR" class="form-label-mandatory">Valid Permit DENR</label>
                            <input type="checkbox" class="form-check-input stateful-checkbox" id="valid_permit_DENR" name="valid_permit_DENR" value="1" data-state="0">
                        </div>
                        <div class="mb-3">
                            <label for="valid_permit_LGU_remarks" class="label-remarks">Remarks</label>
                            <input type="text" class="form-control-mandatory" id="valid_permit_LGU_remarks" name="valid_permit_LGU_remarks">
                        </div>
                    </div>

                    <!-- Testing and Calibration Section -->
                    <div class="form-section">
                        <div class="mb-3">
                            <label for="appropriate_test" class="form-label-mandatory">c. Appropriate Test Measure/Year Calibrated </label>
                            <input type="checkbox" class="form-check-input stateful-checkbox" id="appropriate_test" name="appropriate_test" value="1" data-state="0">
                        </div>
                        <div class="mb-3">
                            <label for="appropriate_test_remarks" class="label-remarks">Remarks</label>
                            <input type="text" class="form-control-mandatory" id="appropriate_test_remarks" name="appropriate_test_remarks">
                        </div>
                        <div class="mb-3">
                            <label for="week_calib" class="form-label-mandatory">d. Weekly Calibration Record/ Logbook</label>
                            <input type="checkbox" class="form-check-input stateful-checkbox" id="week_calib" name="week_calib" value="1" data-state="0">
                        </div>
                    </div>

                    <!-- Outlet Identification Section -->
                    <div class="form-section">
                        <div class="mb-3">
                            <label for="outlet_identify" class="form-label-mandatory">e. Outlet's Identification/Trademark</label>
                            <input type="checkbox" class="form-check-input stateful-checkbox" id="outlet_identify" name="outlet_identify" value="1" data-state="0">
                        </div>
                    </div>

                    <!-- Price and PDB Section -->
                    <div class="form-section">
                        <div class="mb-3">
                            <label for="price_display" class="form-label-mandatory">f. Price Display Board (PDB)</label>
                        </div>
                        <div class="mb-3">
                            <label for="pdb_entry" class="form-label-mandatory">f.1. PDB w/ entry/ies</label>
                            <input type="checkbox" class="form-check-input stateful-checkbox" id="pdb_entry" name="pdb_entry" value="1" data-state="0">
                        </div>
                        <div class="mb-3">
                            <label for="pdb_updated" class="form-label-mandatory">f.2. PDB w/ updated prices</label>
                            <input type="checkbox" class="form-check-input stateful-checkbox" id="pdb_updated" name="pdb_updated" value="1" data-state="0">
                        </div>
                        <div class="mb-3">
                            <label for="pdb_match" class="form-label-mandatory">f.3. Price in PDB and dispensing pumps match</label>
                            <input type="checkbox" class="form-check-input stateful-checkbox" id="pdb_match" name="pdb_match" value="1" data-state="0">
                        </div>
                        <div class="mb-3">
                            <label for="pdb_match_remarks" class="label-remarks">Remarks</label>
                            <input type="text" class="form-control-mandatory" id="price_remarks" name="pdb_match_remarks">
                        </div>
                    </div>

                    <!-- Labels and Biofuels Section -->
                    <div class="form-section">
                        <div class="mb-3">
                            <label for="ron_label" class="form-label-mandatory">g. Research Octane Number (RON) Labels for Gasoline</label>
                            <input type="checkbox" class="form-check-input stateful-checkbox" id="ron_label" name="ron_label" value="1" data-state="0">
                        </div>

                        <div class="mb-3">
                            <label for="e10_label" class="form-label-mandatory">h. E-10 Label (contains 10% Bio-Ethanol) for Gasolinel</label>
                            <input type="checkbox" class="form-check-input stateful-checkbox" id="e10_label" name="e10_label" value="1" data-state="0">
                        </div>
                    
                        <div class="mb-3">
                            <label for="biofuels" class="form-label-mandatory">i. Biofuels (B₂) Labels for Diesel </label>
                            <input type="checkbox" class="form-check-input stateful-checkbox" id="biofuels" name="biofuels" value="1" data-state="0">
                        </div>
                    </div>

                    <!-- Consumer Safety Section -->
                    <div class="form-section">
                        <div class="mb-3">
                            <label for="consumer_safety" class="form-label-mandatory">j. Consumer Safety and Informational Signs</label>
                            <input type="checkbox" class="form-check-input stateful-checkbox" id="consumer_safety" name="consumer_safety" value="1" data-state="0">
                        </div>
                        <div class="mb-3">
                            <label for="no_cel_warn" class="form-label-mandatory">j.1 No Cellphone Warning Sign</label>
                            <input type="checkbox" class="form-check-input stateful-checkbox" id="no_cel_warn" name="no_cel_warn" value="1" data-state="0">
                        </div>
                        <div class="mb-3">
                            <label for="no_straddle_remarks" class="label-remarks">Remarks</label>
                            <input type="text" class="form-control-mandatory" id="consumer_remarks" name="consumer_remarks">
                        </div>
                        <div class="mb-3">
                            <label for="no_smoke_sign" class="form-label-mandatory">j.2 No Smoking Sign</label>
                            <input type="checkbox" class="form-check-input stateful-checkbox" id="no_smoke_sign" name="no_smoke_sign" value="1" data-state="0">
                        </div>
                        <div class="mb-3">
                            <label for="switch_eng" class="form-label-mandatory">j.3 Switch Off Engine while Filling Sign</label>
                            <input type="checkbox" class="form-check-input stateful-checkbox" id="switch_eng" name="switch_eng" value="1" data-state="0">
                        </div>
                        <div class="mb-3">
                            <label for="no_straddle" class="form-label-mandatory">j.4 No Straddling Sign (motorbike/tricycle)</label>
                            <input type="checkbox" class="form-check-input stateful-checkbox" id="no_straddle" name="no_straddle" value="1" data-state="0">
                        </div>
                    
                        <div class="mb-3">
                            <label for="non_post_unleaded" class="form-label-mandatory">k. Non-posting of the term "unleaded" </label>
                            <input type="checkbox" class="form-check-input stateful-checkbox" id="non_post_unleaded" name="non_post_unleaded" value="1" data-state="0">
                        </div>
                        <div class="mb-3">
                            <label for="non_post_biodiesel" class="form-label-mandatory">l. Non-posting of the term "biodiesel"</label>
                            <input type="checkbox" class="form-check-input stateful-checkbox" id="non_post_biodiesel" name="non_post_biodiesel" value="1" data-state="0">
                        </div>
                    </div>
                    
                    <!-- Receipts and Inspections Section -->
                    <div class="form-section">
                        <div class="mb-3">
                            <label for="issue_receipt" class="form-label-mandatory">m. Issuance of Official Receipts</label>
                            <input type="checkbox" class="form-check-input stateful-checkbox" id="issue_receipt" name="issue_receipt" value="1" data-state="0">
                        </div>
                    
                        <div class="mb-3">
                            <label for="non_refuse_inspect" class="form-label-mandatory">n. Non-refusal to Conduct Inspection/Sign ITRF</label>
                            <input type="checkbox" class="form-check-input stateful-checkbox" id="non_refuse_inspect" name="non_refuse_inspect" value="1" data-state="0">
                        </div>
                    </div>

                    <!-- Dispensing and Safety Section -->
                    <div class="form-section">
                        <div class="mb-3">
                            <label for="fixed_dispense" class="form-label-mandatory">o.1 Fixed & permanent dispensing pump 6 meters from any potential source of ignition</label>
                            <input type="checkbox" class="form-check-input stateful-checkbox" id="fixed_dispense" name="fixed_dispense" value="1" data-state="0">
                        </div>
                        <div class="mb-3">
                            <label for="fixed_dispense_remarks" class="label-remarks">Remarks</label>
                            <input type="text" class="form-control-mandatory" id="fixed_dispense_remarks" name="fixed_dispense_remarks">
                        </div>
                        <div class="mb-3">
                            <label for="no_open_flame" class="form-label-mandatory">o.2 No open flame within15 meters</label>
                            <input type="checkbox" class="form-check-input stateful-checkbox" id="no_open_flame" name="no_open_flame" value="1" data-state="0">
                        </div>
                        <div class="mb-3">
                            <label for="max_length_dispense" class="form-label-mandatory">o.3 5.5-meter maximum length of dispensing hosee</label>
                            <input type="checkbox" class="form-check-input stateful-checkbox" id="max_length_dispense" name="max_length_dispense" value="1" data-state="0">
                        </div>
                        <div class="mb-3">
                            <label for="max_length_dispense_remarks" class="label-remarks">Remarks</label>
                            <input type="text" class="form-control-mandatory" id="max_length_dispense_remarks" name="max_length_dispense_remarks">
                        </div>
                        <div class="mb-3">
                            <label for="peso_display" class="form-label-mandatory">o.4 Volume-Peso amount display up to two decimal places</label>
                            <input type="checkbox" class="form-check-input stateful-checkbox" id="peso_display" name="peso_display" value="1" data-state="0">
                        </div>
                    </div>

                    <!-- Pump and Infrastructure Section -->
                    <div class="form-section">
                    
                        <div class="mb-3">
                            <label for="pump_island" class="form-label-mandatory">p.1 Pump Island with minimum 3.5 m x 1.2 m dimension</label>
                            <input type="checkbox" class="form-check-input stateful-checkbox" id="pump_island" name="pump_island" value="1" data-state="0">
                        </div>
                        <div class="mb-3">
                            <label for="pump_island_remarks" class="label-remarks">Remarks</label>
                            <input type="text" class="form-control-mandatory" id="pump_island_remarks" name="pump_island_remarks">
                        </div>
                        <div class="mb-3">
                            <label for="lane_oriented_pump" class="form-label-mandatory">p.2 Lane-oriented pump with min. distance of 0.05 m from fixed object</label>
                            <input type="checkbox" class="form-check-input stateful-checkbox" id="lane_oriented_pump" name="lane_oriented_pump" value="1" data-state="0">
                        </div>
                        <div class="mb-3">
                            <label for="lane_oriented_pump_remarks" class="label-remarks">Remarks</label>
                            <input type="text" class="form-control-mandatory" id="lane_oriented_pump_remarks" name="lane_oriented_pump_remarks">
                        </div>
                        <div class="mb-3">
                            <label for="pump_guard" class="form-label-mandatory">p.3 Pump guard/column post as safety barrier</label>
                            <input type="checkbox" class="form-check-input stateful-checkbox" id="pump_guard" name="pump_guard" value="1" data-state="0">
                        </div>
                        
                        <div class="mb-3">
                            <label for="m_ingress" class="form-label-mandatory">p.4 7 m ingress/egress</label>
                            <input type="checkbox" class="form-check-input stateful-checkbox" id="m_ingress" name="m_ingress" value="1" data-state="0">
                        </div>
                        <div class="mb-3">
                            <label for="m_ingress_remarks" class="label-remarks">Remarks</label>
                            <input type="text" class="form-control-mandatory" id="m_ingress_remarks" name="m_ingress_remarks">
                        </div>
                        <div class="mb-3">
                            <label for="m_edge" class="form-label-mandatory">p.5 6 m edge to edge distance between pump islands</label>
                            <input type="checkbox" class="form-check-input stateful-checkbox" id="m_edge" name="m_edge" value="1" data-state="0">
                        </div>
                        <div class="mb-3">
                            <label for="m_edge_remarks" class="label-remarks">Remarks</label>
                            <input type="text" class="form-control-mandatory" id="m_edge_remarks" name="m_edge_remarks">
                        </div>
                        <div class="mb-3">
                            <label for="office_cashier" class="form-label-mandatory">q.1 Office/cashier's booth</label>
                            <input type="checkbox" class="form-check-input stateful-checkbox" id="office_cashier" name="office_cashier" value="1" data-state="0">
                        </div>
                        <div class="mb-3">
                            <label for="min_canopy" class="form-label-mandatory">q.2 4.5 m minimum canopy height</label>
                            <input type="checkbox" class="form-check-input stateful-checkbox" id="min_canopy" name="min_canopy" value="1" data-state="0">
                        </div>
                        <div class="mb-3">
                            <label for="min_canopy_remarks" class="label-remarks">Remarks</label>
                            <input type="text" class="form-control-mandatory" id="min_canopy_remarks" name="min_canopy_remarks">
                        </div>
                        <div class="mb-3">
                            <label for="boundary_walls" class="form-label-mandatory">q.3 Boundary walls (concrete or cyclone fence)s</label>
                            <input type="checkbox" class="form-check-input stateful-checkbox" id="boundary_walls" name="boundary_walls" value="1" data-state="0">
                        </div>
                        <div class="mb-3">
                            <label for="master_switch" class="form-label-mandatory">q.4 Master switch in case of emergency</label>
                            <input type="checkbox" class="form-check-input stateful-checkbox" id="master_switch" name="master_switch" value="1" data-state="0">
                        </div>
                    
                        <div class="mb-3">
                            <label for="clean_rest" class="form-label-mandatory">q.5 Clean restroom</label>
                            <input type="checkbox" class="form-check-input stateful-checkbox" id="clean_rest" name="clean_rest" value="1" data-state="0">
                        </div>
                        <div class="mb-3">
                            <label for="underground_storage" class="form-label-mandatory">r.1 Underground storage tank (UGT) with rain tight fill sump and monitoring wells</label>
                            <input type="checkbox" class="form-check-input stateful-checkbox" id="underground_storage" name="underground_storage" value="1" data-state="0">
                        </div>
                    
                        <div class="mb-3">
                            <label for="m_distance" class="form-label-mandatory">r.2 1 m distance from property line and adjoining structure</label>
                            <input type="checkbox" class="form-check-input stateful-checkbox" id="m_distance" name="m_distance" value="1" data-state="0">
                        </div>
                        <div class="mb-3">
                            <label for="m_distance_remarks" class="label-remarks">Remarks</label>
                            <input type="text" class="form-control-mandatory" id="m_distance_remarks" name="m_distance_remarks">
                        </div>
                        <div class="mb-3">
                            <label for="vent" class="form-label-mandatory">r.3 3.65 m vent lines</label>
                            <input type="checkbox" class="form-check-input stateful-checkbox" id="vent" name="vent" value="1" data-state="0">
                        </div>
                        <div class="mb-3">
                            <label for="vent_remarks" class="label-remarks">Remarks</label>
                            <input type="text" class="form-control-mandatory" id="vent_remarks" name="vent_remarks">
                        </div>
                        <div class="mb-3">
                            <label for="transfer_dispense" class="form-label-mandatory">s.1 Transfer/dispensing on approved containers only</label>
                            <input type="checkbox" class="form-check-input stateful-checkbox" id="transfer_dispense" name="transfer_dispense" value="1" data-state="0">
                        </div>
                        
                        <div class="mb-3">
                            <label for="no_drum" class="form-label-mandatory">s.2 No Drumming / "Bote-Bote" of Liquid Fuels</label>
                            <input type="checkbox" class="form-check-input stateful-checkbox" id="no_drum" name="no_drum" value="1" data-state="0">
                        </div>
                        <div class="mb-3">
                            <label for="no_drum_remarks" class="label-remarks">Remarks</label>
                            <input type="text" class="form-control-mandatory" id="no_drum_remarks" name="no_drum_remarks">
                        </div>
                        <div class="mb-3">
                            <label for="no_hoard" class="form-label-mandatory">t.1 No Hoarding</label>
                            <input type="checkbox" class="form-check-input stateful-checkbox" id="no_hoard" name="no_hoard" value="1" data-state="0">
                        </div>
                    </div>

                    <!-- Free Services Section -->
                    <div class="form-section">
                        <div class="mb-3">
                            <label for="free_tire_press" class="form-label-mandatory">u1. Offers free tire pressure air filling</label>
                            <input type="checkbox" class="form-check-input stateful-checkbox" id="free_tire_press" name="free_tire_press" value="1" data-state="0">
                        </div>
                        <div class="mb-3">
                            <label for="free_water" class="form-label-mandatory">u2. Offers free water for radiator</label>
                            <input type="checkbox" class="form-check-input stateful-checkbox" id="free_water" name="free_water" value="1" data-state="0">
                        </div>
                        <div class="mb-3">
                            <label for="basic_mechanical" class="form-label-mandatory">u3. Basic mechanical services</label>
                            <input type="checkbox" class="form-check-input stateful-checkbox" id="basic_mechanical" name="basic_mechanical" value="1" data-state="0">
                        </div>
                        <div class="mb-3">
                            <label for="first_aid" class="form-label-mandatory">u4. First aid kits</label>
                            <input type="checkbox" class="form-check-input stateful-checkbox" id="first_aid" name="first_aid" value="1" data-state="0">
                        </div>
                        <div class="mb-3">
                            <label for="design_eval" class="form-label-mandatory">u5. Designated evacuation assembly area</label>
                            <input type="checkbox" class="form-check-input stateful-checkbox" id="design_eval" name="design_eval" value="1" data-state="0">
                        </div>
                
                        <div class="mb-3">
                            <label for="electric_eval" class="form-label-mandatory">u6. Electric vehicle charging facility</label>
                            <input type="checkbox" class="form-check-input stateful-checkbox" id="electric_eval" name="electric_eval" value="1" data-state="0">
                        </div>

                        <div class="mb-3">
                            <label for="under_deliver" class="form-label-mandatory">Under Deliver</label>
                            <input type="checkbox" class="form-check-input stateful-checkbox" id="under_deliver" name="under_deliver" value="1" data-state="0">
                        </div>
                        <div class="mb-3">
                            <label for="under_deliver_remarks" class="label-remarks"> Remarks</label>
                            <input type="text" class="form-control-mandatory" id="under_deliver_remarks" name="under_deliver_remarks">
                        </div>
                    </div>
        
                    <div class="form-section">
                        <!-- Sampling -->
                        <label style="float:left" class="label-supplier" for="sampling">Sampling</label>
                        <input type="checkbox" class="form-check-input stateful-checkbox" id="sampling" name="sampling" value="1" data-state="0">
                    
                        <!-- Sampling Section (Hidden by Default) -->
                        <div id="samplingSection" class="sampling-section">
                            <h5 class="mb-3">Product Sampling</h5>
                            <div id="samplingRows">
                                <!-- Sampling Row Template -->
                                <div class="sampling-row mb-3">
                                    <div class="row">
                                        <div class="col">
                                            <label for="code_value" class="label-sampling">Code Value</label>
                                            <input type="text" class="form-control-sampling" name="code_value[]">
                                        </div>
                                        <div class="col">
                                            <label for="product" class="label-sampling">Product</label>
                                            <input type="text" class="form-control-sampling" name="product[]">
                                        </div>
                                        <div class="col">
                                            <label for="ron_value" class="label-sampling">RON Value</label>
                                            <input type="text" class="form-control-sampling" name="ron_value[]">
                                        </div>
                                        <div class="col">
                                            <label for="UGT" class="label-sampling">UGT</label>
                                            <input type="text" class="form-control-sampling" name="UGT[]">
                                        </div>
                                        <div class="col">
                                            <label for="pump" class="label-sampling">Pump</label>
                                            <input type="text" class="form-control-sampling" name="pump[]">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Add Row Button -->
                            <button type="button" id="addRow" class="btn btn-secondary mb-3">Add Row</button>
                        </div>
                    </div>

                    <!-- Product Samples Check-->
                    <div class="form-section">
                        <div class="mb-3">
                            <label for="duplicate_retention_samples" class="form-label-mandatory">Duplicate Retention Samples</label>
                            <input type="checkbox" class="form-check-input stateful-checkbox" id="duplicate_retention_samples" name="duplicate_retention_samples" value="1" data-state="0">
                        </div>
                        <div class="mb-3">
                            <label for="appropriate_sampling" class="form-label-mandatory">Appropriate Sampling</label>
                            <input type="checkbox" class="form-check-input stateful-checkbox" id="appropriate_sampling" name="appropriate_sampling" value="1" data-state="0">
                        </div>
                    </div>
                
                    <div class="form-section">
                        <h4 class="mb-3">SUPPLIERS INFORMATION</h4>
                        <div class="mb-3">
                            <label for="receipt_invoice" style="width:30%" class="label-supplier">Receipt Invoice:</label>
                            <input type="text" id="receipt_invoice" class="form-control-supplier" name="receipt_invoice" required><br>
                        </div>
                        <div class="mb-3">
                            <label for="supplier" style="width:30%" class="label-supplier">Supplier:</label>
                            <input type="text" id="supplier" class="form-control-supplier" name="supplier" required><br>
                        </div>
                        <div class="mb-3">
                            <label for="date_delivery" style="width:30%" class="label-supplier">Date of Delivery:</label>
                            <input type="text" id="date_delivery" class="form-control-supplier" name="date_delivery" required><br>
                        </div>
                        <div class="mb-3">
                            <label for="address" style="width:30%" class="label-supplier">Address:</label>
                            <input type="text" id="address"class="form-control-supplier" name="address" required><br>
                        </div>
                        <div class="mb-3">
                            <label for="contact_number" style="width:30%" class="label-supplier">Contact Number:</label>
                            <input type="text" id="contact_number" class="form-control-supplier"name="contact_number" required>
                        </div>
                    </div>
                    
                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Stateful checkbox functionality
            document.querySelectorAll('.stateful-checkbox').forEach(checkbox => {
                checkbox.addEventListener('click', function() {
                    const currentState = parseInt(this.getAttribute('data-state'));
                    const nextState = (currentState + 1) % 3; // Cycle through 0, 1, 2
                    
                    this.setAttribute('data-state', nextState);
                    
                    // Remove all classes first
                    this.classList.remove('checked', 'wrong');
                    
                    // Apply appropriate class based on state
                    if (nextState === 1) {
                        this.classList.add('checked');
                        this.checked = true;
                    } else if (nextState === 2) {
                        this.classList.add('wrong');
                        this.checked = false;
                    } else {
                        this.checked = false;
                    }
                });
            });
            
            // Sampling section functionality
            const samplingCheckbox = document.getElementById('sampling');
            const samplingSection = document.getElementById('samplingSection');
            const samplingRows = document.getElementById('samplingRows');
            const addRowButton = document.getElementById('addRow');

            samplingCheckbox.addEventListener('change', function () {
                if (this.checked) {
                    samplingSection.style.display = 'block';
                } else {
                    samplingSection.style.display = 'none';
                    samplingRows.innerHTML = '';
                }
            });

            addRowButton.addEventListener('click', function () {
    const rowCount = document.querySelectorAll('.sampling-row').length;
    if (rowCount < 5) {
        const newRow = document.createElement('div');
        newRow.classList.add('sampling-row', 'mb-3');
        newRow.innerHTML = `
            <div class="row">
                <div class="col">
                    <label for="code_value" class="label-sampling">Code Value</label>
                    <input type="text" class="form-control-sampling" name="code_value[]">
                </div>
                <div class="col">
                    <label for="product" class="label-sampling">Product</label>
                    <input type="text" class="form-control-sampling" name="product[]">
                </div>
                <div class="col">
                    <label for="ron_value" class="label-sampling">RON Value</label>
                    <input type="text" class="form-control-sampling" name="ron_value[]">
                </div>
                <div class="col">
                    <label for="UGT" class="label-sampling">UGT</label>
                    <input type="text" class="form-control-sampling" name="UGT[]">
                </div>
                <div class="col">
                    <label for="pump" class="label-sampling">Pump</label>
                    <input type="text" class="form-control-sampling" name="pump[]">
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
</body>
</html>