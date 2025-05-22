<?php

require "config.php";
checkLogin();

// Define admin roles - only admin can delete
$is_admin = ($_SESSION['role'] === 'admin');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Business Information System</title>
    <link rel="icon" type="image/x-icon" href="..\itr\assets\img\inspectlogo.png">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="//cdn.datatables.net/2.2.2/css/dataTables.dataTables.min.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
     <!-- Bootstrap 5 JS Bundle with Popper -->
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
     <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
     <!-- Font Awesome -->
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --primary: #2c3e50;
            --secondary: #3498db;
            --accent: #e74c3c;
            --light: #ecf0f1;
            --dark: #1a252f;
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --accent-color: #4895ef;
            --dark-color: #2b2d42;
            --light-color: #f8f9fa;
            --success-color: #4cc9f0;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f5f7fa;
            color: var(--dark);
        }
        
        .container-wrapper {
            display: flex;
            flex-direction: column;
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
        
        .menu-toggle {
            display: block;
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
            padding: 5px;
        }
        
        .sidebar-menu {
            padding: 0;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
        }
        
        .sidebar-menu.active {
            max-height: 1000px;
            padding: 10px 0;
        }
        
        .sidebar-menu li {
            list-style: none;
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
            padding: 15px;
            background-color: #f5f7fa;
        }

        .details-row {
            background-color: #f9f9f9;
        }
        .details-table {
            width: 100%;
        }
        .action-buttons {
            white-space: nowrap;
        }
        .tab-content {
            max-height: 500px;
            overflow-y: auto;
        }
        .form-section {
            margin-bottom: 20px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
        .form-section h5 {
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
    /* Add to your existing style section */
#pdfFrame {
    width: 100%;
    min-height: 500px;
    border: 1px solid #ddd;
    border-radius: 4px;
}

.pdf-view-btn {
    margin-right: 5px;
}

#pdfViewerContainer {
        width: 100%;
        height: 80vh;
        overflow: auto;
    }
    .pdf-page {
        margin-bottom: 20px;
        box-shadow: 0 0 5px rgba(0,0,0,0.3);
    }

/* For Bootstrap Icons (if using) */
@import url("https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css");

/* Modal nav tabs styling */
.modal-nav-tabs {
    background-color: #f8f9fa;
    border-radius: 6px 6px 0 0;
}

.modal-nav-tabs .nav-link {
    color: #495057 !important;
    font-weight: 500;
    border: 1px solid transparent;
    border-bottom: none;
    margin: 0;
    border-radius: 0;
}

.modal-nav-tabs .nav-link:hover {
    background-color: #e9ecef;
    color: #212529 !important;
}

.modal-nav-tabs .nav-link.active {
    background-color: white;
    color: #4361ee !important;
    border-color: #dee2e6;
    border-bottom-color: white !important;
    font-weight: 500;
}

.modal-nav-tabs .nav-item:first-child .nav-link {
    border-radius: 6px 0 0 0;
}

.modal-nav-tabs .nav-item:last-child .nav-link {
    border-radius: 0 6px 0 0;
}

/* Media Queries */
@media (min-width: 576px) {
    .header {
        flex-direction: row;
        align-items: center;
    }
    
    .header h2 {
        margin-bottom: 0;
    }
    
    .activity-item {
        flex-direction: row;
        align-items: center;
        padding: 15px 20px;
    }
    
    .activity-main {
        margin-bottom: 0;
        flex: 1;
    }
    
    .activity-time {
        margin-left: 15px;
    }
}

@media (min-width: 768px) {
    .container-wrapper {
        flex-direction: row;
    }
    
    .sidebar {
        width: 250px;
        height: 100vh;
        position: sticky;
        top: 0;
    }
    
    .menu-toggle {
        display: none;
    }
    
    .sidebar-menu {
        max-height: none;
        padding: 20px 0;
        display: block !important;
    }
    
    .main-content {
        padding: 20px;
    }
    
    .header {
        padding: 15px 20px;
    }
}

@media (min-width: 992px) {
    .stats-cards {
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
    }
    
    .stat-card {
        padding: 20px;
    }
    
    .stat-card p {
        font-size: 1.8rem;
    }
}
    </style>

      <!-- PDF.js Library -->
      <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.min.js"></script>
        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <!-- DataTables JS -->
        <script src="//cdn.datatables.net/2.2.2/js/dataTables.min.js"></script>
        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="container-wrapper">
        <!-- Sidebar Navigation -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <div style="display: flex; align-items: center;">
                    <img src="..\itr\assets\img\inspectlogo.png" alt="Logo" class="mb-3" width="65px">
                    <h3>DataSpect</h3>
                </div>
                
                <button class="menu-toggle" id="menuToggle">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
            
            <ul class="sidebar-menu" id="sidebarMenu">
                <li>
                    <a href="home.php">
                        <i class="fas fa-home"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="import_sql_lite.php" >
                        <i class="fas fa-database"></i>
                        <span>Import Data</span>
                    </a>
                </li>
                <li>
                     <a href="itr_form.php?" >
                        <i class="fas fa-file-alt"></i>
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
            </ul>
        </aside>
        
        <!-- Main Content Area -->
        <main class="main-content">
            <div class="container mt-4">
                <h2>Business Information</h2>
                <table id="businessTable" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th></th>
                            <th>ITR Form Number</th>
                            <th>Business Name</th>
                            <th>Dealer/Operator</th>
                            <th>Location</th>
                            <th>Outlet Class</th>
                            <th>Company</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data will be loaded via AJAX -->
                    </tbody>
                </table>
            </div>

           

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this business record? This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="pdfViewerModal" tabindex="-1" aria-labelledby="pdfViewerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="pdfViewerModalLabel">PDF Viewer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="pdfViewerContainer"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    
   
    <script>

        
   $(document).ready(function() {
    // Initialize DataTable
    let table = new DataTable('#businessTable', {
        ajax: {
            url: 'fetch_business.php',
            dataSrc: ''
        },
        columns: [
            {
                className: 'dt-control',
                orderable: false,
                data: null,
                defaultContent: '',
                width: '20px'
            },
            { data: 'itr_form_num' },
            { data: 'business_name' },
            { data: 'dealer_operator' },
            { data: 'location' },
            { data: 'outlet_class' },
            { data: 'company' },
            {
                data: null,
                className: 'action-buttons',
                render: function(data, type, row) {
                    let buttons = `
                        <a href="generate_pdf.php?id=${row.itr_form_num}" target="_blank" class="btn btn-sm btn-info pdf-view-btn" data-id="${row.itr_form_num}" title="View PDF">
                            <i class="bi bi-file-earmark-pdf"></i> View
                        </a>
                        
                    `;

                    
                     if ('<?php echo $_SESSION["role"]; ?>' !== 'legal') {
                        buttons += ` <a href="itr_form.php?edit=true&itr_form_num=${row.itr_form_num}" target="_blank" class="btn btn-sm btn-info pdf-view-btn" data-id="${row.itr_form_num}" title="View PDF">
                            <i class="bi bi-pencil-square"></i> Edit
                        </a>`;
                    }
                    
                    // Only show delete button for admin role
                    if ('<?php echo $_SESSION["role"]; ?>' === 'admin') {
                        buttons += `<button class="btn btn-sm btn-danger delete-btn" data-id="${row.itr_form_num}">Delete</button>`;
                    }
                       // Only show delete button for admin role
                    if ('<?php echo $_SESSION["role"]; ?>' === 'legal') {
                        buttons += ` <a href="itr_form.php?legal=true&itr_form_num=${row.itr_form_num}" target="_blank" class="btn btn-sm btn-danger pdf-view-btn" data-id="${row.itr_form_num}" title="View PDF">
                            <i class="bi bi-file-arrow-up"></i> Legal
                        </a>`;
                    }
                    
                    return buttons;
                },
                orderable: false
            }
        ],
        responsive: true
    });

        // Add event listener for opening and closing details
        $('#businessTable tbody').on('click', 'td.dt-control', function() {
            let tr = $(this).closest('tr');
            let row = table.row(tr);
            let itrFormNum = row.data().itr_form_num;

            if (row.child.isShown()) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('details-row'); 
            } else {
                // Open this row
                showChildRow(row, itrFormNum);
                tr.addClass('details-row');
            }
        });

        // Function to show child row with related data
        function showChildRow(row, itrFormNum) {
            // Format the child row data
            let childRow = `
                <div class="details-container">
                    <div class="text-center my-2">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            `;

            // Display the row
            row.child(childRow).show();

            // Load the data via AJAX
            $.ajax({
                url: 'fetch_related_entry.php',
                method: 'POST',
                data: { itr_form_num: itrFormNum },
                dataType: 'json',
                success: function(response) {
                    let content = `
                        <div class="details-container p-3">
                            <h5>Related Data for ${itrFormNum}</h5>
                            
                            <h6 class="mt-3">General Remarks</h6>
                            <table class="details-table table table-sm table-bordered">
                                <thead>
                                    <tr>
                                        <th>Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>`;
                    
                    if (response.generalRemarks && response.generalRemarks.length > 0) {
                        response.generalRemarks.forEach(remark => {
                            content += `<tr><td>${remark.remarks || 'N/A'}</td></tr>`;
                        });
                    } else {
                        content += `<tr><td>No general remarks found</td></tr>`;
                    }
                    
                    content += `</tbody></table>
                            
                            <h6 class="mt-3">Product Quality</h6>
                            <table class="details-table table table-sm table-bordered">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>RON Value</th>
                                        <th>UGT</th>
                                        <th>Pump</th>
                                        <th>Code Value</th>
                                    </tr>
                                </thead>
                                <tbody>`;
                    
                    if (response.productQuality && response.productQuality.length > 0) {
                        response.productQuality.forEach(product => {
                            content += `
                                <tr>
                                    <td>${product.product || 'N/A'}</td>
                                    <td>${product.ron_value || 'N/A'}</td>
                                    <td>${product.UGT || 'N/A'}</td>
                                    <td>${product.pump || 'N/A'}</td>
                                    <td>${product.code_value || 'N/A'}</td>
                                </tr>`;
                        });
                    } else {
                        content += `<tr><td colspan="5">No product quality data found</td></tr>`;
                    }
                    
                    content += `</tbody></table>
                            
                            <h6 class="mt-3">Product Quality Control</h6>
                            <table class="details-table table table-sm table-bordered">
                                <thead>
                                    <tr>
                                        <th>Duplicate Retention Samples</th>
                                        <th>Retention Retail</th>
                                        <th>Appropriate Sampling</th>
                                        <th>Inappropriate Sampling</th>
                                    </tr>
                                </thead>
                                <tbody>`;
                    
                    if (response.productQualityCont && response.productQualityCont.length > 0) {
                        const pqc = response.productQualityCont[0];
                        content += `
                            <tr>
                                <td>${pqc.duplicate_retention_samples ? 'Yes' : 'No'}</td>
                                <td>${pqc.retention_retail ? 'Yes' : 'No'}</td>
                                <td>${pqc.appropriate_sampling ? 'Yes' : 'No'}</td>
                                <td>${pqc.inappropriate_sampling ? 'Yes' : 'No'}</td>
                            </tr>`;
                    } else {
                        content += `<tr><td colspan="4">No product quality control data found</td></tr>`;
                    }
                    
                    content += `</tbody></table>
                            
                            <h6 class="mt-3">Standard Compliance Checklist</h6>
                            <div class="table-responsive">
                                <table class="details-table table table-sm table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Check Item</th>
                                            <th>Compliant</th>
                                            <th>Remarks</th>
                                        </tr>
                                    </thead>
                                    <tbody>`;
                    
                    if (response.standardCompliance && response.standardCompliance.length > 0) {
                        const sc = response.standardCompliance[0];
                        // Add all compliance checklist items\
                        
                        content += `
                            <tr><td>COC Certificate</td><td>${sc.coc_cert ? 'Yes' : 'No'}</td><td>${sc.coc_cert_remarks || 'N/A'}</td></tr>
                            <tr><td>COC Posted</td><td>${sc.coc_posted ? 'Yes' : 'No'}</td><td>${sc.coc_posted_remarks || 'N/A'}</td></tr>
                            <tr><td>Valid Permit (LGU)</td><td>${sc.valid_permit_LGU ? 'Yes' : 'No'}</td><td>${sc.valid_permit_LGU_remarks || 'N/A'}</td></tr>
                            <tr><td>Valid Permit (BFP)</td><td>${sc.valid_permit_BFP ? 'Yes' : 'No'}</td><td>${sc.valid_permit_BFP_remarks || 'N/A'}</td></tr>
                            <tr><td>Valid Permit (DENR)</td><td>${sc.valid_permit_DENR ? 'Yes' : 'No'}</td><td>${sc.valid_permit_DENR_remarks || 'N/A'}</td></tr>
                            <tr><td>Appropriate Test</td><td>${sc.appropriate_test ? 'Yes' : 'No'}</td><td>${sc.appropriate_test_remarks || 'N/A'}</td></tr>
                            <tr><td>Weekly Calibration</td><td>${sc.week_calib ? 'Yes' : 'No'}</td><td>${sc.week_calib_remarks || 'N/A'}</td></tr>
                            <tr><td>Outlet Identification</td><td>${sc.outlet_identify ? 'Yes' : 'No'}</td><td>${sc.outlet_identify_remarks || 'N/A'}</td></tr>
                            <tr><td>Price Display</td><td>${sc.price_display ? 'Yes' : 'No'}</td><td>${sc.price_display_remarks || 'N/A'}</td></tr>
                            <tr><td>PDB Entry</td><td>${sc.pdb_entry ? 'Yes' : 'No'}</td><td>${sc.pdb_entry_remarks || 'N/A'}</td></tr>
                            <tr><td>PDB Updated</td><td>${sc.pdb_updated ? 'Yes' : 'No'}</td><td>${sc.pdb_updated_remarks || 'N/A'}</td></tr>
                            <tr><td>PDB Match</td><td>${sc.pdb_match ? 'Yes' : 'No'}</td><td>${sc.pdb_match_remarks || 'N/A'}</td></tr>
                            <tr><td>RON Label</td><td>${sc.ron_label ? 'Yes' : 'No'}</td><td>${sc.ron_label_remarks || 'N/A'}</td></tr>
                            <tr><td>E10 Label</td><td>${sc.e10_label ? 'Yes' : 'No'}</td><td>${sc.e10_label_remarks || 'N/A'}</td></tr>
                            <tr><td>Biofuels</td><td>${sc.biofuels ? 'Yes' : 'No'}</td><td>${sc.biofuels_remarks || 'N/A'}</td></tr>
                            <tr><td>Consumer Safety</td><td>${sc.consume_safety ? 'Yes' : 'No'}</td><td>${sc.consume_safety_remarks || 'N/A'}</td></tr>
                            <tr><td>CEL Warning</td><td>${sc.cel_warn ? 'Yes' : 'No'}</td><td>${sc.cel_warn_remarks || 'N/A'}</td></tr>
                            <tr><td>No Smoking Sign</td><td>${sc.smoke_sign ? 'Yes' : 'No'}</td><td>${sc.smoke_sign_remarks || 'N/A'}</td></tr>
                            <tr><td>Switch Off Engine</td><td>${sc.switch_eng ? 'Yes' : 'No'}</td><td>${sc.switch_eng_remarks || 'N/A'}</td></tr>
                            <tr><td>Straddle</td><td>${sc.straddle ? 'Yes' : 'No'}</td><td>${sc.straddle_remarks || 'N/A'}</td></tr>
                            <tr><td>Post Unleaded</td><td>${sc.post_unleaded ? 'Yes' : 'No'}</td><td>${sc.post_unleaded_remarks || 'N/A'}</td></tr>
                            <tr><td>Post Biodiesel</td><td>${sc.post_biodiesel ? 'Yes' : 'No'}</td><td>${sc.post_biodiesel_remarks || 'N/A'}</td></tr>
                            <tr><td>Issue Receipt</td><td>${sc.issue_receipt ? 'Yes' : 'No'}</td><td>${sc.issue_receipt_remarks || 'N/A'}</td></tr>
                            <tr><td>Non-Refuse Inspection</td><td>${sc.non_refuse_inspect ? 'Yes' : 'No'}</td><td>${sc.non_refuse_inspect_remarks || 'N/A'}</td></tr>
                            <tr><td>Non-Refuse Sign</td><td>${sc.non_refuse_sign ? 'Yes' : 'No'}</td><td>${sc.non_refuse_sign_remarks || 'N/A'}</td></tr>
                            <tr><td>Fixed Dispense</td><td>${sc.fixed_dispense ? 'Yes' : 'No'}</td><td>${sc.fixed_dispense_remarks || 'N/A'}</td></tr>
                            <tr><td>No Open Flame</td><td>${sc.no_open_flame ? 'Yes' : 'No'}</td><td>${sc.no_open_flame_remarks || 'N/A'}</td></tr>
                            <tr><td>Max Length Dispense</td><td>${sc.max_length_dispense ? 'Yes' : 'No'}</td><td>${sc.max_length_dispense_remarks || 'N/A'}</td></tr>
                            <tr><td>Peso Display</td><td>${sc.peso_display ? 'Yes' : 'No'}</td><td>${sc.peso_display_remarks || 'N/A'}</td></tr>
                            <tr><td>Pump Island</td><td>${sc.pump_island ? 'Yes' : 'No'}</td><td>${sc.pump_island_remarks || 'N/A'}</td></tr>
                            <tr><td>Lane Oriented Pump</td><td>${sc.lane_oriented_pump ? 'Yes' : 'No'}</td><td>${sc.lane_oriented_pump_remarks || 'N/A'}</td></tr>
                            <tr><td>Pump Guard</td><td>${sc.pump_guard ? 'Yes' : 'No'}</td><td>${sc.pump_guard_remarks || 'N/A'}</td></tr>
                            <tr><td>Ingress</td><td>${sc.m_ingress ? 'Yes' : 'No'}</td><td>${sc.m_ingress_remarks || 'N/A'}</td></tr>
                            <tr><td>Edge</td><td>${sc.m_edge ? 'Yes' : 'No'}</td><td>${sc.m_edge_remarks || 'N/A'}</td></tr>
                            <tr><td>Office/Cashier</td><td>${sc.office_cashier ? 'Yes' : 'No'}</td><td>${sc.office_cashier_remarks || 'N/A'}</td></tr>
                            <tr><td>Minimum Canopy</td><td>${sc.min_canopy ? 'Yes' : 'No'}</td><td>${sc.min_canopy_remarks || 'N/A'}</td></tr>
                            <tr><td>Boundary Walls</td><td>${sc.boundary_walls ? 'Yes' : 'No'}</td><td>${sc.boundary_walls_remarks || 'N/A'}</td></tr>
                            <tr><td>Master Switch</td><td>${sc.master_switch ? 'Yes' : 'No'}</td><td>${sc.master_switch_remarks || 'N/A'}</td></tr>
                            <tr><td>Clean Restroom</td><td>${sc.clean_rest ? 'Yes' : 'No'}</td><td>${sc.clean_rest_remarks || 'N/A'}</td></tr>
                            <tr><td>Underground Storage</td><td>${sc.underground_storage ? 'Yes' : 'No'}</td><td>${sc.underground_storage_remarks || 'N/A'}</td></tr>
                            <tr><td>Minimum Distance</td><td>${sc.m_distance ? 'Yes' : 'No'}</td><td>${sc.m_distance_remarks || 'N/A'}</td></tr>
                            <tr><td>Vent</td><td>${sc.vent ? 'Yes' : 'No'}</td><td>${sc.vent_remarks || 'N/A'}</td></tr>
                            <tr><td>Transfer Dispense</td><td>${sc.transfer_dispense ? 'Yes' : 'No'}</td><td>${sc.transfer_dispense_remarks || 'N/A'}</td></tr>
                            <tr><td>No Drum</td><td>${sc.no_drum ? 'Yes' : 'No'}</td><td>${sc.no_drum_remarks || 'N/A'}</td></tr>
                            <tr><td>No Hoard</td><td>${sc.no_hoard ? 'Yes' : 'No'}</td><td>${sc.no_hoard_remarks || 'N/A'}</td></tr>
                            <tr><td>Free Tire Pressure</td><td>${sc.free_tire_press ? 'Yes' : 'No'}</td><td>${sc.free_tire_press_remarks || 'N/A'}</td></tr>
                            <tr><td>Free Water</td><td>${sc.free_water ? 'Yes' : 'No'}</td><td>${sc.free_water_remarks || 'N/A'}</td></tr>
                            <tr><td>Basic Mechanical</td><td>${sc.basic_mechanical ? 'Yes' : 'No'}</td><td>${sc.basic_mechanical_remarks || 'N/A'}</td></tr>
                            <tr><td>First Aid</td><td>${sc.first_aid ? 'Yes' : 'No'}</td><td>${sc.first_aid_remarks || 'N/A'}</td></tr>
                            <tr><td>Design Evaluation</td><td>${sc.design_eval ? 'Yes' : 'No'}</td><td>${sc.design_eval_remarks || 'N/A'}</td></tr>
                            <tr><td>Electric Evaluation</td><td>${sc.electric_eval ? 'Yes' : 'No'}</td><td>${sc.electric_eval_remarks || 'N/A'}</td></tr>
                            <tr><td>Under Deliver</td><td>${sc.under_deliver ? 'Yes' : 'No'}</td><td>${sc.under_deliver_remarks || 'N/A'}</td></tr>
                        `;
                    } else {
                        content += `<tr><td colspan="3">No standard compliance data found</td></tr>`;
                    }
                    
                    content += `</tbody></table></div>
                            
                            <h6 class="mt-3">Suppliers</h6>
                            <table class="details-table table table-sm table-bordered">
                                <thead>
                                    <tr>
                                        <th>Supplier</th>
                                        <th>Receipt/Invoice</th>
                                        <th>Date Delivered</th>
                                        <th>Address</th>
                                        <th>Contact Number</th>
                                    </tr>
                                </thead>
                                <tbody>`;
                    
                    if (response.suppliersInfo && response.suppliersInfo.length > 0) {
                        response.suppliersInfo.forEach(supplier => {
                            content += `
                                <tr>
                                    <td>${supplier.supplier || 'N/A'}</td>
                                    <td>${supplier.receipt_invoice || 'N/A'}</td>
                                    <td>${supplier.date_deliver || 'N/A'}</td>
                                    <td>${supplier.address || 'N/A'}</td>
                                    <td>${supplier.contact_num || 'N/A'}</td>
                                </tr>`;
                        });
                    } else {
                        content += `<tr><td colspan="5">No supplier data found</td></tr>`;
                    }
                    
                    content += `</tbody></table>
                            
                            <h6 class="mt-3">Summary Remarks</h6>
                            <table class="details-table table table-sm table-bordered">
                                <thead>
                                    <tr>
                                        <th>Extracted Violations</th>
                                        <th>Extracted General Remarks</th>
                                        <th>User Violations</th>
                                        <th>User General Remarks</th>
                                        <th>Action Required</th>
                                    </tr>
                                </thead>
                                <tbody>`;
                    
                    if (response.summaryRemarks && response.summaryRemarks.length > 0) {
                        const summary = response.summaryRemarks[0];
                        content += `
                            <tr>
                                <td>${summary.extracted_violations || 'N/A'}</td>
                                <td>${summary.extracted_gen_remarks || 'N/A'}</td>
                                <td>${summary.user_violations || 'N/A'}</td>
                                <td>${summary.user_gen_remarks || 'N/A'}</td>
                                <td>${summary.action_required || 'N/A'}</td>
                            </tr>`;
                    } else {
                        content += `<tr><td colspan="5">No summary remarks found</td></tr>`;
                    }
                    
                    content += `</tbody></table>
                        </div>
                    `;
                    
                    // Update the child row with the actual content
                    row.child().find('.details-container').html(content);
                },
                error: function(xhr, status, error) {
                    row.child().find('.details-container').html(`
                        <div class="alert alert-danger">
                            Error loading related data: ${error}
                        </div>
                    `);
                }
            });
        }

        // Handle view button click
        $('#businessTable').on('click', '.view-btn', function() {
            let itrFormNum = $(this).data('id');
            viewBusiness(itrFormNum);
        });

        // Handle delete button click
        $('#businessTable').on('click', '.delete-btn', function() {
            let itrFormNum = $(this).data('id');
            $('#deleteModal').data('id', itrFormNum).modal('show');
        });

        // Confirm delete
        $('#confirmDelete').click(function() {
            let itrFormNum = $('#deleteModal').data('id');
            deleteBusiness(itrFormNum);
        });

        // Save business data
        $('#saveBusiness').click(function() {
            saveBusiness();
        });

        // Handle edit button click
$('#businessTable').on('click', '.edit-btn', function() {
    let itrFormNum = $(this).data('id');
    // Navigate to itr_form.php with the ITR number as a parameter
    window.location.href = 'itr_form.php?edit=true&itr_form_num=' + encodeURIComponent(itrFormNum);
});
        

        // Function to delete business
        function deleteBusiness(itrFormNum) {
            $.ajax({
                url: 'delete_entry.php',
                method: 'POST',
                data: { itr_form_num: itrFormNum },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted',
                            text: 'Business deleted successfully'
                        });
                        $('#deleteModal').modal('hide');
                        table.ajax.reload();
                     
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error deleting business: ' + response.message
                        });
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error deleting business: ' + error
                    });
                }
            });
        }
    });
    </script>





<script>

    const userRole = '<?php echo isset($_SESSION["role"]) ? $_SESSION["role"] : ""; ?>';


    
// Then in your DataTable initialization, modify the actions column render function:
{
    data: null,
    className: 'action-buttons',
    render: function(data, type, row) {
        let buttons = `
            <a href="generate_pdf.php?id=${row.itr_form_num}" target="_blank" class="btn btn-sm btn-info pdf-view-btn" data-id="${row.itr_form_num}" title="View PDF">
                <i class="bi bi-file-earmark-pdf"></i> View
            </a>
            <button class="btn btn-sm btn-warning edit-btn" data-id="${row.itr_form_num}">Edit</button>
        `;
        
        // Only show delete button for admin role
        if (userRole === 'admin') {
            buttons += `<button class="btn btn-sm btn-danger delete-btn" data-id="${row.itr_form_num}">Delete</button>`;
        }
        
        return buttons;
    },
    orderable: false
}
    // Highlight active navigation item based on current page
    document.addEventListener('DOMContentLoaded', function() {
        const currentPage = window.location.pathname.split('/').pop();
        const navLinks = document.querySelectorAll('.nav-link');
        
        navLinks.forEach(link => {
            const linkPage = link.getAttribute('href').split('/').pop();
            if (linkPage === currentPage) {
                link.classList.add('active');
            } else {
                link.classList.remove('active');
            }
        });
        
        // Logout button functionality
        document.getElementById('logoutBtn').addEventListener('click', function() {
            // In a real application, this would redirect to logout endpoint
            alert('You have been logged out');
            // window.location.href = '/logout';
        });
    });
</script>

<script>
    // Toggle mobile menu
    const menuToggle = document.getElementById('menuToggle');
    const sidebarMenu = document.getElementById('sidebarMenu');
    
    // Initialize menu state based on screen size
    function initMenu() {
        if (window.innerWidth >= 768) {
            sidebarMenu.classList.add('active');
        } else {
            sidebarMenu.classList.remove('active');
        }
    }
    
    // Set initial state
    initMenu();
    
    // Toggle menu when button is clicked
    menuToggle.addEventListener('click', function() {
        sidebarMenu.classList.toggle('active');
    });
    
    // Close menu when clicking on a link (for mobile)
    const menuLinks = document.querySelectorAll('.sidebar-menu a');
    menuLinks.forEach(link => {
        link.addEventListener('click', function() {
            if (window.innerWidth < 768) {
                sidebarMenu.classList.remove('active');
            }
        });
    });
    
    // Update menu state when window is resized
    window.addEventListener('resize', function() {
        initMenu();
    });

</script>

</body>
</html>