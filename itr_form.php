       
       
       
<?php
require "config.php";
checkLogin();
allowAccess();
?>
       <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, shrink-to-fit=no">
            <title>Inspection Form</title>
            <link rel="icon" type="image/x-icon" href="..\itr\assets\img\inspectlogo.png">
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
            <!-- Bootstrap Icons -->
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
            <link rel="stylesheet" href="itrcss.css">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
            <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
            <link rel="preconnect" href="https://fonts.googleapis.com">
            <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
            <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.min.css">
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.all.min.js"></script>
            <style>
                :root {
                    --primary: #2c3e50;
                    --secondary: #3498db;
                    --accent: #e74c3c;
                    --light: #ecf0f1;
                    --dark: #1a252f;
                }
                
                * {
                    box-sizing: border-box;
                    font-family: "Poppins", sans-serif;
                }
                
                body {
                    margin: 0;
                    padding: 0;
                    line-height: 1.6;
                    background-color: #f5f7fa;
                    display: flex;
                    min-height: 100vh;
                }
                
                /* Sidebar Styles */
                .sidebar {
                    width: 250px;
                    background: var(--primary);
                    color: white;
                    transition: all 0.3s;
                    position: relative;
                    box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
                }
                
                .sidebar-header {
                    padding: 15px 20px;
                    background: white;
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                }
                
                .sidebar-header h3 {
                    margin-left: 10px;
                    font-size: 1.5rem;
                    font-weight: 200;
                    color: rgb(24, 15, 103);
                    font-family: Impact, Haettenschweiler, 'Arial Narrow Bold', sans-serif;
                }
                
                .sidebar-menu {
                    padding: 20px 0;
                }
                
                .sidebar-menu li {
                    list-style: none;
                    margin-bottom: 5px;
                }
                
                .sidebar-menu a {
                    display: flex;
                    align-items: center;
                    padding: 12px 20px;
                    color: var(--light);
                    text-decoration: none;
                    transition: all 0.3s;
                    font-size: 15px;
                }
                
                .sidebar-menu a:hover, .sidebar-menu a.active {
                    background: rgba(255, 255, 255, 0.1);
                    border-left: 4px solid var(--secondary);
                }
                
                .sidebar-menu a i {
                    margin-right: 10px;
                    font-size: 18px;
                }
                
                /* Main Content Styles */
                .main-content {
                    flex: 1;
                    padding: 20px;
                    background-color: #f5f7fa;
                }
                
                .header {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    padding: 15px 20px;
                    background: white;
                    border-radius: 8px;
                    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
                    margin-bottom: 20px;
                }
                
                .user-actions {
                    display: flex;
                    align-items: center;
                }
                
                .logout-btn {
                    background: var(--accent);
                    color: white;
                    border: none;
                    padding: 8px 15px;
                    border-radius: 4px;
                    cursor: pointer;
                    display: flex;
                    align-items: center;
                    transition: background 0.3s;
                }
                
                .logout-btn:hover {
                    background: #c0392b;
                }
                
                .logout-btn i {
                    margin-right: 5px;
                }

        /* Toggle Switch - Right Side */
        .mode-toggle-wrapper {
                    display: flex;
                    justify-content: flex-end;
                    margin-bottom: 15px;
                }
                
                .mode-toggle-container {
                    display: flex;
                    align-items: center;
                    gap: 10px;
                }
                
        /* Updated Toggle Switch Styles */
        .toggle-switch {
                position: relative;
                display: inline-block;
                width: 50px;
                height: 24px;
            }
            
            .toggle-switch input {
                opacity: 0;
                width: 0;
                height: 0;
            }
            
            .toggle-slider {
                position: absolute;
                cursor: pointer;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: #4CAF50; /* Green for create mode */
                transition: .4s;
                border-radius: 24px;
            }
            
            .toggle-slider:before {
                position: absolute;
                content: "";
                height: 18px;
                width: 18px;
                left: 3px;
                bottom: 3px;
                background-color: white;
                transition: .4s;
                border-radius: 50%;
            }
            
            input:checked + .toggle-slider {
                background-color: #FFC107; /* Yellow for edit mode */
            }
            
            input:checked + .toggle-slider:before {
                transform: translateX(26px);
            }
            
            .mode-label {
    font-weight: 500;
    font-size: 14px;
    margin-left: 8px;
    transition: color 0.3s;
}

.column {
  float: left;
  width: 20%;

  border-radius: 5px;
}

/* Clear floats after the columns */
.row:after {
  content: "";
  display: table;
  clear: both;
}
            
            /* Create mode - green text */
            .mode-label.create-mode {
    color: #4CAF50;
}
            
            /* Edit mode - yellow text */
            mode-label.edit-mode {
    color: #FFC107;
}
            
            /* Hide the appropriate label based on mode */
            input:checked ~ .mode-label.create-mode {
                display: none;
            }
            
            input:not(:checked) ~ .mode-label.edit-mode {
                display: none;
            }
                
                    /* Form container */
                    .form-container {
                    background: white;
                    padding: 20px;
                    border-radius: 8px;
                    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
                }
                
                /* ITR input field */
                #itr_form_num {
                    transition: all 0.3s;
                }
                
                .edit-mode-hint {
                    font-size: 0.85rem;
                    color: #6c757d;
                    display: none;
                    margin-top: 5px;
                }



                /* Original Form Styles */
                html {
                    -webkit-text-size-adjust: 100%; /* Prevent font scaling in landscape */
                    touch-action: manipulation; /* Disable double-tap zoom */
                }
                
                .container {
                    width: 100%;
                    display: block;
                    max-width: 1000px;
                    margin: 0 auto;
                    padding: 10px;
                    background-color: #fcfcfc;
                    border-radius: 18px;
                    color: black;
                    justify-content: center;
                    height: auto;
                    place-items: center;
                    border-style: solid;
                    border-color: rgb(233, 238, 255);
                }
                
                .header-img {
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
                
                .div-form-checklist {
                    padding: 0 10px;
                    width: 100%;
                }
                
                .divcol1 {
                    padding: 0 10px;
                    width: 100%;   
                }

                .divcol2 {
                    padding: 0 10px;
                    width: 500px;
                    border-style: solid;
                    color: black;
                }
                
                .form-section {
                    margin-bottom: 20px;
                    padding: 15px;
                    background: #f9f9f9;
                    border-radius: 5px;
                    border: 1px solid #ddd;
                    width: 100%;
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
                    float: left;  
                    font-size: 16px;
                }
            
                .form-label-mandatory {
                    color: rgb(61, 56, 96);
                    float: left;  
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
                
                .form-control-supplier {
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
                    position: relative;
                    left: 20px;
                    height: 25px;
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

                /* Remarks section styling */
                .remarks-container {
                    display: none;
                    margin-top: 5px;
                    margin-left: 45px;
                }

                /* Search section styling */
                #searchSection {
                    margin-bottom: 20px;
                    padding: 15px;
                    background: #f0f8ff;
                    border-radius: 5px;
                    border: 1px solid #d0e0ff;
                }

                .input-group {
                    display: flex;
                    width: 100%;
                }

                .input-group input {
                    flex: 1;
                    border-top-right-radius: 0;
                    border-bottom-right-radius: 0;
                }

                .input-group button {
                    border-top-left-radius: 0;
                    border-bottom-left-radius: 0;
                }

                @media (max-width: 768px) {
                    body {
                        flex-direction: column;
                    }
                    
                    .sidebar {
                        width: 100%;
                        height: auto;
                    }
                    
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

/* ITR Input Wrapper (FIXED) */
.input-wrapper {
    position: relative;
    width: 80%; /* Now matches other inputs */
}

/* Input Field (No change needed) */
.input-wrapper .form-control {
    width: 100%;
    padding: 8px 35px 8px 8px;
    font-size: 14px;
    border: none;
    border-bottom: 1px solid gray;
    background: #f9f9f9;
}

/* Search Button (IMPROVED) */
.search-button {
    position: absolute;
    right: 8px; /* Fixed spacing */
    top: 50%;
    transform: translateY(-50%);
    background: transparent;
    border: none;
    cursor: pointer;
    padding: 5px;
    font-size: 16px;
    color: #6c757d;
    transition: color 0.3s;
}

.search-button:hover {
    color: #495057;
}

/* Focus State (NEW) */
.input-wrapper .form-control:focus {
    outline: none;
    border-bottom-color: #3498db; /* Blue highlight */
}

/* Mobile Fix (NEW) */
@media (max-width: 768px) {
    .input-wrapper {
        width: 100%;
    }
}

/* Edit Mode (No change needed) */
.edit-mode .input-wrapper .form-control {
    border-color: #FFC107;
}

.edit-mode .search-button {
    color: #FFC107;
}

/* Toggle switch styles */
.toggle-switch {
    position: relative;
    display: inline-block;
    width: 50px;
    height: 24px;
}

.toggle-slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #4CAF50; /* Green for create mode */
    transition: .4s;
    border-radius: 24px;
}

input:checked + .toggle-slider {
    background-color: #FFC107; /* Yellow for edit mode */
}

/* Show/hide appropriate label based on mode */
input:checked ~ .mode-label.create-mode {
    display: none;
}

input:not(:checked) ~ .mode-label.edit-mode {
    display: none;
}
            </style>
        </head>
        <body>
            <!-- Sidebar Navigation -->
            
            <aside class="sidebar">
                <div class="sidebar-header">
                    <div style="display: flex; align-items: center;">
                            <img src="..\itr\assets\img\inspectlogo.png" alt="Logo" class="mb-3" width="65px">
                            <h3>DataSpect</h3>
                        </div>
                </div>
                
                <ul class="sidebar-menu">
                    <li>
                        <a href="home.php">
                            <i class="fas fa-home"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="import_sql_lite.php">
                            <i class="fas fa-database"></i>
                            <span>Import Data</span>
                        </a>
                    </li>
                    <li>
                        <a href="itr_form.php " class="active">
                           <i class="bi bi-file-earmark-plus-fill"></i>
                            <span>New Entry</span>
                        </a>
                    </li>
                    <li>
                        <a href="tables.php">
                            <i class="fas fa-table"></i>
                            <span>Inspection Tables</span>
                        </a>
                    </li>
                    <li>
                            <a href="logout.php">
                                <i class="fa-solid fa-right-from-bracket"></i>
                                <span>Logout</span>
                            </a>
                        </li>

                        <?php echo '<li><a href="user_profile.php"><i class="fas fa-user"></i> <span>' . $_SESSION['role'] . '</span></a></li>'; ?>
                </ul>
            </aside>
            
            <!-- Main Content Area -->
            <main class="main-content"> 
                <div class="header">
                    <h2> ITR Form</h2>
                
                    </div>
                </div>
                
                <div class="container">
                    <!-- Header -->
                    <header><img src="..\itr\assets\img\header.jpg" alt="headerdoe" width="100%" height="auto" style="border-radius:15px"></header>
                    
                <div class="mode-toggle-container">
                <div class="toggle-switch-wrapper">
                    <label class="toggle-switch">
                      <input type="checkbox" id="modeToggle" onchange="toggleMode()">
                      <span class="toggle-slider"></span>
                     </label>
                </div>
                <div class="mode-labels">
                    <span class="mode-label create-mode" id="create-mode-label">Create New Record</span>
                    <span class="mode-label edit-mode" id="edit-mode-label">Edit Existing Record</span>
                    </div>
                </div>
                    <div style="text-align:center;">
                        <h5>Business Information</h5>
                    </div>
                    <div class="divcol1">
                    <form method="POST" id="itrForm" action="insert_entry.php">
                        <input type="hidden" name="edit_mode" id="edit_mode_field" value="false">
                            <!-- Hidden field to track existing ITR number in edit mode -->
                            <input type="hidden" id="existing_itr_num" name="existing_itr_num" value="">
                            <div id="editHint" class="edit-hint"></div>
                            <div class="form-container">
                            
                                <div class="form-column">
                                    <!-- ITR Form Number -->
                                
                                    <div class="form-group">
    <label for="itr_form_num" class="form-label">ITR Form Number</label>
    <div class="input-wrapper">
        <input type="text" class="form-control" id="itr_form_num" name="itr_form_num" required>
        <button type="button" id="search-icon" class="search-button" style="display: none;">
            <i class="fas fa-search"></i>
        </button>
    </div>
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
                                        <label for="outlet_class" class="form-label">Outlet Classification</label>
                                        <select class="form-select" id="outlet_class" name="outlet_class" style="width: 65%; ">
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
                          
                      <?php if ($_SESSION['role'] !== 'legal'): ?>
                            <div class="mandatorytitle">
                                <h5>MANDATORY AND MINIMUM STANDARDS & REQUIREMENTS</h5>
                            </div>
                            <div class="div-form-checklist">
                                <div class="form-section">
                                    <?php foreach ($violation_pairs as $pair) {
                                        $boolean_column = $pair[0]; 
                                        $label = $pair[1];
                                        $remarks_column = $pair[2];
                                    ?>
                                        <div class="mb-3">
                                            <label for="<?php echo $boolean_column ?>" class="form-label-mandatory"><?php echo $label ?></label>
                                            <input type="checkbox" class="form-check-input stateful-checkbox" id="<?php echo $boolean_column ?>" name="<?php echo $boolean_column ?>" value="1" data-state="0">
                                            <div class="remarks-container">
                                                <label for="<?php echo $remarks_column ?>" class="label-remarks"> Remarks</label>
                                                <input type="text" class="form-control-mandatory" id="<?php echo $remarks_column ?>" name="<?php echo $remarks_column ?>">
                                            </div>
                                        </div>
                                    <?php } ?>


                                </div>


                                <div class="form-section">
                                    <div class="mb-3">
                                        <label for="user_gen_remarks" style="width:30%" class="label-supplier">General Remarks:</label>
                                        <textarea id="user_gen_remarks" name="user_gen_remarks" rows="4" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; background-color: #f9f9f9; resize: vertical;">
                                        </textarea>
                                    </div>
                                </div>
            
                    
                                <div class="form-section">
                                    <!-- Sampling -->
                                    <label style="float:left" class="label-supplier" for="sampling">Sampling</label>
                                    <input type="checkbox" class="form-check-input stateful-checkbox" id="sampling" name="sampling" value="1" data-state="0">
                                
                                    <!-- Sampling Section (Hidden by Default) - UPDATED WITH DROPDOWNS -->
                                    <div id="samplingSection" class="sampling-section">
                                        <h5 class="mb-3">Product Sampling</h5>
                                        <div id="samplingRows">
                                            <!-- Sampling Row Template with Dropdowns -->
                                            <div class="sampling-row mb-3">
                                                <div class="row">
                                                    <div class="col">
                                                        <label for="code_value" class="label-sampling">Code Value</label>
                                                        <input type="text" class="form-control-sampling" name="code_value[]">
                                                    </div>
                                                    <div class="col">
                                                        <label for="product" class="label-sampling">Product</label>
                                                        <select class="form-control-sampling" name="product[]">
                                                            <option value="">Select Product</option>
                                                            <option value="Diesel">Diesel</option>
                                                            <option value="Premium">Premium</option>
                                                            <option value="Regular">Regular</option>
                                                        </select>
                                                    </div>
                                                    <div class="col">
                                                        <label for="ron_value" class="label-sampling">RON Value</label>
                                                        <select class="form-control-sampling" name="ron_value[]">
                                                            <option value="">Select RON</option>
                                                            <option value="91">91</option>
                                                            <option value="92">92</option>
                                                            <option value="93">93</option>
                                                            <option value="94">94</option>
                                                            <option value="95">95</option>
                                                            <option value="96">96</option>
                                                            <option value="97">97</option>
                                                            <option value="98">98</option>
                                                            <option value="99">99</option>
                                                            <option value="100">100</option>
                                                            <option value="N/A">N/A (Diesel)</option>
                                                        </select>
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
                                        <label for="date_deliver" style="width:30%" class="label-supplier">Date of Delivery:</label>
                                        <input type="date" id="date_deliver" class="form-control-supplier" name="date_deliver" required><br>
                                    </div>
                                    <div class="mb-3">
                                        <label for="address" style="width:30%" class="label-supplier">Address:</label>
                                        <input type="text" id="address"class="form-control-supplier" name="address" required><br>
                                    </div>
                                    <div class="mb-3">
                                        <label for="contact_num" style="width:30%" class="label-supplier">Contact Number:</label>
                                        <input type="text" id="contact_num" class="form-control-supplier"name="contact_num" required>
                                    </div>
                                </div>

                                <div class="form-section">
                        <div class="mb-3">
                            <label for="action_required" style="width:30%" class="label-supplier">Action Required:</label>
                            <textarea id="action_required" name="action_required" rows="4" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; background-color: #f9f9f9; resize: vertical;">
                            </textarea>
                        </div>
                    </div>
                    <?php endif; ?>

<?php if ($_SESSION['role'] === 'legal'): ?>
<style>
    .violation-row {
        width: 100%;
        margin: 0;
        padding: 0;
    }
    .violation-row:after {
        content: "";
        display: table;
        clear: both;
    }
    .violation-column {
        float: left;
        width: 33.33%;
        box-sizing: border-box;
        padding: 12px 15px;
    }
    .violation-header {
        font-weight: bold;
        background: #f8f9fa;
        border-bottom: 1px solid #ddd;
    }
    .violation-item {
        border-bottom: 1px solid #ddd;
        font-weight: 500;
    }
    .violation-remarks {
        color: #666;
        text-align: center;
    }
    .violation-action {
        text-align: center;
    }
    .violation-input {
        width: 100%;
        max-width: 250px;
        padding: 8px 12px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }
</style>

<div class="form-section" style="margin: 20px auto; max-width: 900px; font-family: Arial, sans-serif;">
    <div style="border: 1px solid #ddd; border-radius: 4px; overflow: hidden;">
        <!-- Header Row -->
        <div class="violation-row violation-header">
            <div class="violation-column">Violation</div>
            <div class="violation-column violation-remarks">Remarks</div>
            <div class="violation-column violation-action">Legal Action</div>
        </div>
        
        <!-- Data Rows -->
        <?php foreach ($violation_pairs as $pair): 
            $boolean_column = $pair[0]; 
            $label = $pair[1];
            $remarks_column = $pair[2];
        ?>
            <div id="div_<?php echo htmlspecialchars($boolean_column); ?>" class="violation-row violation-item">
                <!-- Violation Column -->
                <div class="violation-column">
                    <?php echo htmlspecialchars($label); ?>
                </div>
                
                <!-- Remarks Column -->
                <div id="remarks_<?php echo htmlspecialchars($remarks_column); ?>" class="violation-column violation-remarks">
                    <?php /* Remarks content appears here */ ?>
                </div>
                
                <!-- Legal Action Column -->
                <div class="violation-column violation-action">
                    <input type="text" 
                           name="legal_action_<?php echo htmlspecialchars($boolean_column); ?>"
                           class="violation-input" 
                           placeholder="Enter legal action">
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>
                         

                    
                        


                         <!-- Submit Button -->
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </main>

            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    toggleMode();
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

                            // Show/hide remarks container based on checkbox state
                            const remarksContainer = this.nextElementSibling;
                            if (remarksContainer && remarksContainer.classList.contains('remarks-container')) {
                                remarksContainer.style.display = this.classList.contains('wrong') ? 'block' : 'none';
                            }
                        });
                    });
                    
                    // Initialize remarks containers based on initial checkbox states
                    document.querySelectorAll('.stateful-checkbox').forEach(checkbox => {
                        const remarksContainer = checkbox.nextElementSibling;
                        if (remarksContainer && remarksContainer.classList.contains('remarks-container')) {
                            remarksContainer.style.display = checkbox.classList.contains('wrong') ? 'block' : 'none';
                        }
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

                    // Modified Add Row Function for Dropdowns
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
                                        <select class="form-control-sampling" name="product[]">
                                            <option value="">Select Product</option>
                                            <option value="Diesel">Diesel</option>
                                            <option value="Premium">Premium</option>
                                            <option value="Regular">Regular</option>
                                        </select>
                                    </div>
                                    <div class="col">
                                        <label for="ron_value" class="label-sampling">RON Value</label>
                                        <select class="form-control-sampling" name="ron_value[]">
                                            <option value="">Select RON</option>
                                            <option value="91">91</option>
                                            <option value="92">92</option>
                                            <option value="93">93</option>
                                            <option value="94">94</option>
                                            <option value="95">95</option>
                                            <option value="96">96</option>
                                            <option value="97">97</option>
                                            <option value="98">98</option>
                                            <option value="99">99</option>
                                            <option value="100">100</option>
                                            <option value="N/A">N/A (Diesel)</option>
                                        </select>
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

                            // Setup product-RON interaction for the new row
                            const productSelect = newRow.querySelector('select[name="product[]"]');
                            const ronSelect = newRow.querySelector('select[name="ron_value[]"]');
                            setupProductRonInteraction(productSelect, ronSelect);
                        } else {
                            alert('Maximum of 5 rows allowed.');
                        }
                    });

        document.getElementById('itrForm').addEventListener('submit', function(event) {
            // Convert N/A option values to empty string before submission
            const ronSelects = document.querySelectorAll('select[name="ron_value[]"]');
            ronSelects.forEach(select => {
                const selectedOption = select.options[select.selectedIndex];
                if (selectedOption && selectedOption.text === 'N/A (Diesel)') {
                    selectedOption.value = ''; // Ensure value is empty string (will become NULL)
                }
            });
        });

                    // Setup interaction for initial rows when page loads
                    const initialRows = document.querySelectorAll('.sampling-row');
                    initialRows.forEach(row => {
                        const productSelect = row.querySelector('select[name="product[]"]');
                        const ronSelect = row.querySelector('select[name="ron_value[]"]');
                        setupProductRonInteraction(productSelect, ronSelect);
                    });
                });

                // Modified populateForm function for product quality section
                function populateForm(data) {
                    console.log("Full data object:", data);
                    // Set the existing ITR number (for updates)
                    document.getElementById('existing_itr_num').value = data.businessInfo.itr_form_num;
                    
                    // Business Info
                    document.getElementById('itr_form_num').value = data.businessInfo.itr_form_num || '';
                    document.getElementById('business_name').value = data.businessInfo.business_name || '';
                    document.getElementById('dealer_operator').value = data.businessInfo.dealer_operator || '';
                    document.getElementById('location').value = data.businessInfo.location || '';
                    document.getElementById('in_charge').value = data.businessInfo.in_charge || '';
                    document.getElementById('designation').value = data.businessInfo.designation || '';
                    document.getElementById('sa_no').value = data.businessInfo.sa_no || '';
                    document.getElementById('sa_date').value = data.businessInfo.sa_date || '';
                    document.getElementById('outlet_class').value = data.businessInfo.outlet_class || 'COCO';
                    document.getElementById('company').value = data.businessInfo.company || '';
                    document.getElementById('contact_tel').value = data.businessInfo.contact_tel || '';
                    document.getElementById('email_add').value = data.businessInfo.email_add || '';
                    

                    <?php if($_SESSION['role'] == 'legal'): ?>
                     

                    
                    
                    <?php foreach ($violation_pairs as $pair) {
                        $boolean_column = $pair[0]; 
                        $label = $pair[1];
                        $remarks_column = $pair[2];
                    ?>
                    document.getElementById('div_<?php echo $boolean_column ?>').style.display = "none";
                        if (data["checklist"]["<?php echo $boolean_column ?>"] == 0){
                            
                            document.getElementById('div_<?php echo $boolean_column ?>').style.display = "inline";
                            document.getElementById('remarks_<?php echo $remarks_column ?>').innerHTML   = data["checklist"]["<?php echo $remarks_column ?>"] || '';
                        }
                        
                    
                    <?php } 
                    endif ?>


                    
                    <?php if($_SESSION['role'] !== 'legal'): ?>
                     

                    
                    
                    <?php foreach ($violation_pairs as $pair) {
                        $boolean_column = $pair[0]; 
                        $label = $pair[1];
                        $remarks_column = $pair[2];
                    ?>
                        setCheckboxState("<?php echo $boolean_column ?>", data["checklist"]["<?php echo $boolean_column ?>"]);
                        document.getElementById('<?php echo $remarks_column ?>').value = data["checklist"]["<?php echo $remarks_column ?>"] || '';

                    
                    <?php } ?>

                    
                    setCheckboxState('duplicate_retention_samples', data.checklist.duplicate_retention_samples);
                    setCheckboxState('appropriate_sampling', data.checklist.appropriate_sampling);
                    
                    // Supplier Info
                    document.getElementById('receipt_invoice').value = data.supplierInfo.receipt_invoice || '';
                    document.getElementById('supplier').value = data.supplierInfo.supplier || '';
                    document.getElementById('date_deliver').value = data.supplierInfo.date_deliver || '';
                    document.getElementById('address').value = data.supplierInfo.address || '';
                    document.getElementById('contact_num').value = data.supplierInfo.contact_num || '';
                    
                    // Product Quality (Sampling) with Dropdowns
                    if (data.productQuality && data.productQuality.length > 0) {
                        document.getElementById('sampling').checked = true;
                        document.getElementById('samplingSection').style.display = 'block';
                        
                        const samplingRows = document.getElementById('samplingRows');
                        samplingRows.innerHTML = ''; // Clear existing rows
                        
                        data.productQuality.forEach((product, index) => {
                            const newRow = document.createElement('div');
                            newRow.classList.add('sampling-row', 'mb-3');
                            newRow.innerHTML = `
                                <div class="row">
                                    <div class="col">
                                        <label for="code_value" class="label-sampling">Code Value</label>
                                        <input type="text" class="form-control-sampling" name="code_value[]" value="${product.code_value || ''}">
                                    </div>
                                    <div class="col">
                                        <label for="product" class="label-sampling">Product</label>
                                        <select class="form-control-sampling" name="product[]">
                                            <option value="">Select Product</option>
                                            <option value="Diesel" ${product.product === 'Diesel' ? 'selected' : ''}>Diesel</option>
                                            <option value="Premium" ${product.product === 'Premium' ? 'selected' : ''}>Premium</option>
                                            <option value="Regular" ${product.product === 'Regular' ? 'selected' : ''}>Regular</option>
                                        </select>
                                    </div>
                                    <div class="col">
                                        <label for="ron_value" class="label-sampling">RON Value</label>
                                        <select class="form-control-sampling" name="ron_value[]">
                                            <option value="">Select RON</option>
                                            <option value="91" ${product.ron_value === '91' ? 'selected' : ''}>91</option>
                                            <option value="92" ${product.ron_value === '92' ? 'selected' : ''}>92</option>
                                            <option value="93" ${product.ron_value === '93' ? 'selected' : ''}>93</option>
                                            <option value="94" ${product.ron_value === '94' ? 'selected' : ''}>94</option>
                                            <option value="95" ${product.ron_value === '95' ? 'selected' : ''}>95</option>
                                            <option value="96" ${product.ron_value === '96' ? 'selected' : ''}>96</option>
                                            <option value="97" ${product.ron_value === '97' ? 'selected' : ''}>97</option>
                                            <option value="98" ${product.ron_value === '98' ? 'selected' : ''}>98</option>
                                            <option value="99" ${product.ron_value === '99' ? 'selected' : ''}>99</option>
                                            <option value="100" ${product.ron_value === '100' ? 'selected' : ''}>100</option>
                                            <option value="N/A" ${product.ron_value === 'N/A' ? 'selected' : ''}>N/A (Diesel)</option>
                                        </select>
                                    </div>
                                    <div class="col">
                                        <label for="UGT" class="label-sampling">UGT</label>
                                        <input type="text" class="form-control-sampling" name="UGT[]" value="${product.UGT || ''}">
                                    </div>
                                    <div class="col">
                                        <label for="pump" class="label-sampling">Pump</label>
                                        <input type="text" class="form-control-sampling" name="pump[]" value="${product.pump || ''}">
                                    </div>
                                </div>
                            `;
                            samplingRows.appendChild(newRow);
                            
                            // Set up product-RON interaction
                            const productSelect = newRow.querySelector('select[name="product[]"]');
                            const ronSelect = newRow.querySelector('select[name="ron_value[]"]');
                            setupProductRonInteraction(productSelect, ronSelect);
                        });
                    } else {
                        document.getElementById('sampling').checked = false;
                        document.getElementById('samplingSection').style.display = 'none';
                    }

                    if (data.generalremarks) {
                        console.log("General remarks data found:", data.generalremarks);
                        document.getElementById('action_required').value = data.generalremarks.action_required || '';
                        document.getElementById('user_gen_remarks').value = data.generalremarks.user_gen_remarks || '';
                    } else {
                        console.log("No general remarks data found in response");
                        // Clear the fields when no data is available
                        document.getElementById('action_required').value = '';
                        document.getElementById('user_gen_remarks').value = '';
                    }
                    <?php endif; ?>

                    <?php if($_SESSION['role'] == 'legal'): ?>
                        console.log(data.checklist);
                    <?php endif; ?>

                }

                function setCheckboxState(id, value) {
                    const checkbox = document.getElementById(id);
                    if (!checkbox) return;
                    
                    if (value === 1) {
                        checkbox.setAttribute('data-state', '1');
                        checkbox.classList.add('checked');
                        checkbox.checked = true;
                    } else if (value === 0) {
                        checkbox.setAttribute('data-state', '2');
                        checkbox.classList.add('wrong');
                        checkbox.checked = false;
                    } else {
                        checkbox.setAttribute('data-state', '0');
                        checkbox.classList.remove('checked', 'wrong');
                        checkbox.checked = false;
                    }
                    
                    // Show/hide remarks based on state
                    const remarksContainer = checkbox.nextElementSibling;
                    if (remarksContainer && remarksContainer.classList.contains('remarks-container')) {
                        remarksContainer.style.display = checkbox.classList.contains('wrong') ? 'block' : 'none';
                    }
                }

        // Toggle between create/edit modes
   // Define toggleSearchIcon first
function toggleSearchIcon() {
    const isEditMode = document.getElementById('modeToggle').checked;
    const searchIcon = document.getElementById('search-icon');
    
    if (isEditMode) {
        searchIcon.style.display = 'block';
    } else {
        searchIcon.style.display = 'none';
    }
}

// Enhanced toggleMode function
function toggleMode() {
    const isEditMode = document.getElementById('modeToggle').checked;
    const form = document.getElementById('itrForm');
    const itrInput = document.getElementById('itr_form_num');
    const createModeLabel = document.getElementById('create-mode-label');
    const editModeLabel = document.getElementById('edit-mode-label');
    
    if (isEditMode) {
        // Edit mode - enable search functionality
        document.getElementById('edit_mode_field').value = 'true';
        form.action = 'edit_entry.php';
        itrInput.placeholder = "Enter existing ITR number";
        
        // Update UI
        createModeLabel.style.display = 'none';
        editModeLabel.style.display = 'inline-block';
        document.querySelector('.header h2').textContent = "Edit ITR Entry";
        
        // Show confirmation if switching from create to edit
        if (itrInput.value) {
            Swal.fire({
                title: 'Switch to Edit Mode?',
                text: 'Any unsaved changes will be lost. Enter an ITR number to load an existing record.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Continue',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (!result.isConfirmed) {
                    document.getElementById('modeToggle').checked = false;
                    return;
                }
                toggleSearchIcon();
            });
        } else {
            Swal.fire({
                title: 'Edit Mode Activated',
                text: 'Enter an ITR number to load an existing record.',
                icon: 'info',
                confirmButtonText: 'OK'
            });
            toggleSearchIcon();
        }
    } else {
        // Create mode - reset form and disable search
        document.getElementById('edit_mode_field').value = 'false';
        form.action = 'insert_entry.php';
        itrInput.placeholder = "";
        itrInput.value = '';
        document.getElementById('existing_itr_num').value = '';
        form.reset();
        
        // Reset all checkboxes to default state
        document.querySelectorAll('.stateful-checkbox').forEach(checkbox => {
            checkbox.setAttribute('data-state', '0');
            checkbox.classList.remove('checked', 'wrong');
            checkbox.checked = false;
            
            // Hide remarks containers
            const remarksContainer = checkbox.nextElementSibling;
            if (remarksContainer && remarksContainer.classList.contains('remarks-container')) {
                remarksContainer.style.display = 'none';
            }
        });
        
        // Hide sampling section
        document.getElementById('sampling').checked = false;
        document.getElementById('samplingSection').style.display = 'none';
        document.getElementById('samplingRows').innerHTML = '';
        
        // Update UI
        createModeLabel.style.display = 'inline-block';
        editModeLabel.style.display = 'none';
        document.querySelector('.header h2').textContent = "ITR Form";
        toggleSearchIcon();
        
        // Show confirmation if switching from edit to create
        Swal.fire({
            title: 'Create New Entry',
            text: 'The form has been reset for a new inspection record.',
            icon: 'success',
            timer: 1500,
            showConfirmButton: false
        });
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    // Set up the toggle switch
    const modeToggle = document.getElementById('modeToggle');
    if (modeToggle) {
        modeToggle.addEventListener('change', toggleMode);
    }
    
    // Set up search icon click
    const searchIcon = document.getElementById('search-icon');
    if (searchIcon) {
        searchIcon.addEventListener('click', function() {
            const itrNumber = document.getElementById('itr_form_num').value.trim();
            if (itrNumber) {
                 Swal.fire({
            title: 'Record Found',
            text: 'Successfully loaded ITR #' + itrNumber,
            icon: 'success',
            timer: 1500,
            showConfirmButton: false
        });
                fetchExistingRecordWithAlert(itrNumber);
            } else {
                Swal.fire({
                    title: 'Empty ITR Number',
                    text: 'Please enter an ITR number to search',
                    icon: 'warning'
                });
            }
        });
    }
    
    // Initialize the mode
    toggleMode();
});
// Add event listener for search icon click
document.addEventListener('DOMContentLoaded', function() {
    const searchIcon = document.getElementById('search-icon');
    
    searchIcon.addEventListener('click', function() {
        const itrNumber = document.getElementById('itr_form_num').value.trim();
        if (itrNumber.length > 0) {
            // Fetch the record
            fetchExistingRecordWithAlert(itrNumber);
        } 
    });
    
    // Initialize the search icon visibility on page load
    toggleSearchIcon();
});

// Existing fetchExistingRecordWithAlert function
async function fetchExistingRecordWithAlert(itrNumber) {
    try {
        const response = await fetch(`fetch_entry.php?itr_form_num=${encodeURIComponent(itrNumber)}`);
        if (!response.ok) throw new Error('Network error');
        
        const data = await response.json();
        if (data.error) {
            Swal.fire({
                title: 'Record Not Found',
                text: 'No record found with ITR #' + itrNumber,
                icon: 'warning'
            });
            throw new Error(data.error);
        }
        console.log("Fetched data:", data);
        // Populate form with existing data
        populateForm(data);
        
        Swal.fire({
            title: 'Record Found',
            text: 'Successfully loaded ITR #' + itrNumber,
            icon: 'success',
            timer: 1500,
            showConfirmButton: false
        });
    } catch (error) {
        console.error('Error fetching record:', error);
    }
}

// Remove the automatic search on input
function checkForExistingRecord() {
    // This function is intentionally empty to disable automatic search
    // We're now using the search icon click instead
}
                
                // Fetch existing record automatically
                async function fetchExistingRecord(itrNumber) {
                    try {
                        const response = await fetch(`fetch_entry.php?itr_form_num=${encodeURIComponent(itrNumber)}`);
                        if (!response.ok) throw new Error('Network error');
                        
                        const data = await response.json();
                        if (data.error) throw new Error(data.error);
                        
                        // Populate form with existing data
                        populateForm(data);
                    } catch (error) {
                        console.error('Error fetching record:', error);
                        // Optionally show error to user
                    }
                }
                
                // Check for URL parameters when page loads
                const urlParams = new URLSearchParams(window.location.search);
                const editMode = urlParams.get('edit');
                const itrNum = urlParams.get('itr_form_num');

                if (editMode === 'true' && itrNum) {
                    // Set edit mode and ITR number
                    document.getElementById('modeToggle').checked = true;
                    document.getElementById('itr_form_num').value = itrNum;
                    document.getElementById('existing_itr_num').value = itrNum;
                    document.getElementById('edit_mode_field').value = 'true';
                    
                    // Trigger the mode change UI update
                    toggleMode();
                    
                    // Fetch and populate the record data
                    fetch(`fetch_entry.php?itr_form_num=${encodeURIComponent(itrNum)}`)
                        .then(response => {
                            if (!response.ok) throw new Error('Network response was not ok');
                            return response.json();
                        })
                        .then(data => {
                            if (data.error) throw new Error(data.error);
                            populateForm(data);
                        })
                        .catch(error => {
                            console.error('Error loading record:', error);
                            alert('Error loading record data. Please try again.');
                        });
                }

                document.getElementById('itrForm').addEventListener('submit', function(event) {
        // Enable all disabled selects so they get submitted
        document.querySelectorAll('select:disabled').forEach(select => {
            select.disabled = false;
        });
        // Form submits normally
    });
                
            </script>

            
    <script>
  
        document.addEventListener('DOMContentLoaded', function () {
            // Get the form
            const itrForm = document.getElementById('itrForm');
            
            fetchExistingRecordWithAlert("<?php echo $_GET["itr_form_num"] ?>")
            // Override the form submission
            itrForm.addEventListener('submit', function(event) {
                event.preventDefault(); // Prevent the default form submission
                
                // Enable all disabled selects so they get submitted
                document.querySelectorAll('select:disabled').forEach(select => {
                    select.disabled = false;
                });
                
                // Show confirmation dialog before submitting
                Swal.fire({
                    title: 'Submit Form?',
                    text: "Please confirm that all information is correct.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, submit it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading state
                        Swal.fire({
                            title: 'Processing...',
                            html: 'Please wait while we submit your form.',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        
                        // Submit the form programmatically
                        const formData = new FormData(itrForm);
                        
                        fetch(itrForm.action, {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === 'success') {
                                Swal.fire({
                                    title: data.title || 'Success!',
                                    text: data.message || 'Form submitted successfully!',
                                    icon: data.icon || 'success',
                                    confirmButtonText: 'OK'
                                }).then(() => {
                                    // Reset form or redirect as needed
                                    const isEditMode = document.getElementById('modeToggle').checked;
                                    if (!isEditMode) {
                                        itrForm.reset(); // Reset form only if in create mode
                                    }
                                    // Optionally redirect to another page
                                    // window.location.href = 'confirmation.php';
                                });
                            } else {
                                Swal.fire({
                                    title: data.title || 'Error!',
                                    text: data.message || 'Something went wrong.',
                                    icon: data.icon || 'error',
                                    confirmButtonText: 'OK'
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire({
                                title: 'Error!',
                                text: 'An unexpected error occurred. Please try again.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        });
                    }
                });
            });
            
            // Add a sweet alert for toggling mode
            const modeToggle = document.getElementById('modeToggle');
            if (modeToggle) {
                const originalToggleMode = window.toggleMode;
                
                // Override the toggle mode function
                window.toggleMode = function() {
                    const isEditMode = modeToggle.checked;
                    
                    if (isEditMode) {
                        Swal.fire({
                            title: 'Edit Mode Activated',
                            text: 'Enter an ITR number to load an existing record.',
                            icon: 'info',
                            confirmButtonText: 'OK'
                        });
                    }
                    
                    // Call the original function
                    if (originalToggleMode) {
                        originalToggleMode();
                    }
                };
            }
            
            // Add SweetAlert for search results in edit mode
            window.checkForExistingRecord = function() {
                const isEditMode = document.getElementById('modeToggle').checked;
                if (!isEditMode) return;
                
                const itrNumber = document.getElementById('itr_form_num').value.trim();
                if (itrNumber.length > 2) {
                    // Show loading state
                    Swal.fire({
                        title: 'Searching...',
                        html: 'Looking for ITR #' + itrNumber,
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        },
                        timer: 1000,
                        timerProgressBar: true
                    });
                    
                    // Fetch after a short delay to allow the loading state to be shown
                    setTimeout(() => {
                        fetchExistingRecordWithAlert(itrNumber);
                    }, 1000);
                }
            };
            
            // Function to fetch with SweetAlert feedback
            async function fetchExistingRecordWithAlert(itrNumber) {
                try {
                    const response = await fetch(`fetch_entry.php?itr_form_num=${encodeURIComponent(itrNumber)}`);
                    if (!response.ok) throw new Error('Network error');
                    
                    const data = await response.json();
                    if (data.error) {
                        Swal.fire({
                            title: 'Record Not Found',
                            text: 'No record found with ITR #' + itrNumber,
                            icon: 'warning'
                        });
                        throw new Error(data.error);
                    }
                    
                    // Populate form with existing data
                    populateForm(data);
                    
                    Swal.fire({
                        title: 'Record Found',
                        text: 'Successfully loaded ITR #' + itrNumber,
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false
                    });
                } catch (error) {
                    console.error('Error fetching record:', error);
                }
            }
        });



        function setupProductRonInteraction(productSelect, ronSelect) {
    if (!productSelect || !ronSelect) return;
    
    productSelect.addEventListener('change', function() {
        const selectedProduct = this.value;
        
        // Reset to default state
        ronSelect.disabled = false;
        
        // If Diesel is selected, set to N/A and disable
        if (selectedProduct === 'Diesel') {
            ronSelect.value = 'N/A';  // Use N/A value for Diesel
            ronSelect.disabled = true;
        }
    });
    
    // Initial setup based on current value
    if (productSelect.value === 'Diesel') {
        ronSelect.value = 'N/A';
        ronSelect.disabled = true;
    }
}

function toggleSearchIcon() {
    const isEditMode = document.getElementById('modeToggle').checked;
    const searchIcon = document.getElementById('search-icon');
    
    if (isEditMode) {
        searchIcon.style.display = 'block';
    } else {
        searchIcon.style.display = 'none';
    }
}

const itrForm = document.getElementById('itrForm');
    
    itrForm.addEventListener('submit', async function(event) {
        event.preventDefault();
        
        // Enable disabled elements for submission
        document.querySelectorAll('select:disabled, input:disabled').forEach(el => {
            el.disabled = false;
        });

        // Confirmation dialog
        const { isConfirmed } = await Swal.fire({
            title: 'Confirm Submission',
            text: 'Are you sure you want to submit this ITR form?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#001f88',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, submit!',
            cancelButtonText: 'Cancel',
            reverseButtons: true
        });

        if (!isConfirmed) {
            await Swal.fire('Cancelled', 'Your submission was cancelled', 'info');
            return;
        }

        // Show loading state
        Swal.fire({
            title: 'Processing...',
            html: 'Please wait while we save your ITR form',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        try {
            const formData = new FormData(itrForm);
            const response = await fetch(itrForm.action, {
                method: 'POST',
                body: formData
            });

            if (!response.ok) throw new Error('Network response was not ok');
            
            const result = await response.json();

            if (result.status === 'success') {
                await Swal.fire({
                    title: result.title || 'Success!',
                    text: result.message,
                    icon: result.icon || 'success',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#001f88'
                });

                // Reset form if in create mode
                if (!document.getElementById('modeToggle').checked) {
                    itrForm.reset();
                    // Reset checkboxes to default state
                    document.querySelectorAll('.stateful-checkbox').forEach(checkbox => {
                        checkbox.setAttribute('data-state', '0');
                        checkbox.classList.remove('checked', 'wrong');
                        checkbox.checked = false;
                        const remarksContainer = checkbox.nextElementSibling;
                        if (remarksContainer.classList.contains('remarks-container')) {
                            remarksContainer.style.display = 'none';
                        }
                    });
                }
                
                // Optionally redirect or update UI
                // window.location.href = `view_entry.php?itr_form_num=${result.itr_num}`;
            } else {
                throw new Error(result.message || 'Unknown error occurred');
            }
        } catch (error) {
            console.error('Submission error:', error);
            await Swal.fire({
                title: 'Error!',
                text: error.message || 'Failed to submit form. Please try again.',
                icon: 'error',
                confirmButtonText: 'OK',
                confirmButtonColor: '#dc3545'
            });
        }
    });

    

    </script>
        </body>
        </html>