<?php if ($_SESSION['role'] == 'legal'): ?>
    <div class="container">
        <h2>Extracted Violations</h2>
        <div class="form-section">
            <table class="table table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>Violation</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Include database connection
                    require "config.php";
                    
                    // Get ITR number from URL
                    $itr_num = isset($_GET['itr_form_num']) ? $_GET['itr_form_num'] : '';
                    
                    if ($itr_num) {
                        // Fetch violations (where checkbox is in wrong state - data_state=2)
                        $sql = "SELECT 
                                    CASE 
                                        WHEN coc_cert = 0 THEN 'No Certificate of Compliance (COC)'
                                        WHEN coc_posted = 0 THEN 'No COC posted within business premises'
                                        WHEN valid_permit_LGU = 0 THEN 'Valid Permit LGU'
                                        WHEN valid_permit_BFP = 0 THEN 'Valid Permit BFP'
                                        WHEN valid_permit_DENR = 0 THEN 'Valid Permit DENR'
                                        WHEN appropriate_test = 0 THEN 'Appropriate Test Measure/Year Calibrated'
                                        WHEN week_calib = 0 THEN 'Weekly Calibration Record/Logbook'
                                        WHEN outlet_identify = 0 THEN 'Outlet\'s Identification/Trademark'
                                        WHEN pdb_entry = 0 THEN 'PDB w/ entry/ies'
                                        WHEN pdb_updated = 0 THEN 'PDB w/ updated prices'
                                        WHEN pdb_match = 0 THEN 'Price in PDB and dispensing pumps match'
                                        WHEN ron_label = 0 THEN 'Research Octane Number (RON) Labels for Gasoline'
                                        WHEN e10_label = 0 THEN 'E-10 Label (contains 10% Bio-Ethanol) for Gasoline'
                                        WHEN biofuels = 0 THEN 'Biofuels (B₂) Labels for Diesel'
                                        WHEN consume_safety = 0 THEN 'Consumer Safety and Informational Signs'
                                        WHEN cel_warn = 0 THEN 'No Cellphone Warning Sign'
                                        WHEN smoke_sign = 0 THEN 'No Smoking Sign'
                                        WHEN switch_eng = 0 THEN 'Switch Off Engine while Filling Sign'
                                        WHEN straddle = 0 THEN 'No Straddling Sign (motorbike/tricycle)'
                                        WHEN post_unleaded = 0 THEN 'Non-posting of the term unleaded'
                                        WHEN post_biodiesel = 0 THEN 'Non-posting of the term biodiesel'
                                        WHEN issue_receipt = 0 THEN 'Issuance of Official Receipts'
                                        WHEN non_refuse_inspect = 0 THEN 'Non-refusal to Conduct Inspection'
                                        WHEN non_refuse_sign = 0 THEN 'Non-refusal to Conduct Sign ITRF'
                                        WHEN fixed_dispense = 0 THEN 'Fixed & permanent dispensing pump 6 meters from any potential source of ignition'
                                        WHEN no_open_flame = 0 THEN 'No open flame within15 meters'
                                        WHEN max_length_dispense = 0 THEN '5.5-meter maximum length of dispensing hose'
                                        WHEN peso_display = 0 THEN 'Volume-Peso amount display up to two decimal places'
                                        WHEN pump_island = 0 THEN 'Pump Island'
                                        WHEN lane_oriented_pump = 0 THEN 'Lane-oriented pump with min. distance of 0.05 m from fixed object'
                                        WHEN pump_guard = 0 THEN 'Pump guard/column post as safety barrier'
                                        WHEN m_ingress = 0 THEN '7 m ingress/egress'
                                        WHEN m_edge = 0 THEN '6 m edge to edge distance between pump islands'
                                        WHEN office_cashier = 0 THEN 'Office/cashier\'s booth'
                                        WHEN min_canopy = 0 THEN '4.5 m minimum canopy height'
                                        WHEN boundary_walls = 0 THEN 'Boundary walls (concrete or cyclone fence)'
                                        WHEN master_switch = 0 THEN 'Master switch in case of emergency'
                                        WHEN clean_rest = 0 THEN 'Clean restroom'
                                        WHEN underground_storage = 0 THEN 'Underground storage tank (UGT) with rain tight fill sump and monitoring wells'
                                        WHEN m_distance = 0 THEN '1 m distance from property line and adjoining structure'
                                        WHEN vent = 0 THEN '3.65 m vent lines'
                                        WHEN transfer_dispense = 0 THEN 'Transfer/dispensing on approved containers only'
                                        WHEN no_drum = 0 THEN 'No Drumming / Bote-Bote of Liquid Fuels'
                                        WHEN no_hoard = 0 THEN 'No Hoarding'
                                        WHEN free_tire_press = 0 THEN 'Offers free tire pressure air filling'
                                        WHEN free_water = 0 THEN 'Offers free water for radiator'
                                        WHEN basic_mechanical = 0 THEN 'Basic mechanical services'
                                        WHEN first_aid = 0 THEN 'First aid kits'
                                        WHEN design_eval = 0 THEN 'Designated evacuation assembly area'
                                        WHEN electric_eval = 0 THEN 'Electric vehicle charging facility'
                                        WHEN under_deliver = 0 THEN 'Under Deliver'
                                        WHEN duplicate_retention_samples = 0 THEN 'Duplicate Retention Samples'
                                        WHEN appropriate_sampling = 0 THEN 'Appropriate Sampling'
                                        ELSE NULL
                                    END AS violation,
                                    CASE 
                                        WHEN coc_cert = 0 THEN coc_cert_remarks
                                        WHEN coc_posted = 0 THEN coc_posted_remarks
                                        WHEN valid_permit_LGU = 0 THEN valid_permit_LGU_remarks
                                        WHEN valid_permit_BFP = 0 THEN valid_permit_BFP_remarks
                                        WHEN valid_permit_DENR = 0 THEN valid_permit_DENR_remarks
                                        WHEN appropriate_test = 0 THEN appropriate_test_remarks
                                        WHEN week_calib = 0 THEN week_calib_remarks
                                        WHEN outlet_identify = 0 THEN outlet_identify_remarks
                                        WHEN pdb_entry = 0 THEN pdb_entry_remarks
                                        WHEN pdb_updated = 0 THEN pdb_updated_remarks
                                        WHEN pdb_match = 0 THEN pdb_match_remarks
                                        WHEN ron_label = 0 THEN ron_label_remarks
                                        WHEN e10_label = 0 THEN e10_label_remarks
                                        WHEN biofuels = 0 THEN biofuels_remarks
                                        WHEN consume_safety = 0 THEN consume_safety_remarks
                                        WHEN cel_warn = 0 THEN cel_warn_remarks
                                        WHEN smoke_sign = 0 THEN smoke_sign_remarks
                                        WHEN switch_eng = 0 THEN switch_eng_remarks
                                        WHEN straddle = 0 THEN straddle_remarks
                                        WHEN post_unleaded = 0 THEN post_unleaded_remarks
                                        WHEN post_biodiesel = 0 THEN post_biodiesel_remarks
                                        WHEN issue_receipt = 0 THEN issue_receipt_remarks
                                        WHEN non_refuse_inspect = 0 THEN non_refuse_inspect_remarks
                                        WHEN non_refuse_sign = 0 THEN non_refuse_sign_remarks
                                        WHEN fixed_dispense = 0 THEN fixed_dispense_remarks
                                        WHEN no_open_flame = 0 THEN no_open_flame_remarks
                                        WHEN max_length_dispense = 0 THEN max_length_dispense_remarks
                                        WHEN peso_display = 0 THEN peso_display_remarks
                                        WHEN pump_island = 0 THEN pump_island_remarks
                                        WHEN lane_oriented_pump = 0 THEN lane_oriented_pump_remarks
                                        WHEN pump_guard = 0 THEN pump_guard_remarks
                                        WHEN m_ingress = 0 THEN m_ingress_remarks
                                        WHEN m_edge = 0 THEN m_edge_remarks
                                        WHEN office_cashier = 0 THEN office_cashier_remarks
                                        WHEN min_canopy = 0 THEN min_canopy_remarks
                                        WHEN boundary_walls = 0 THEN boundary_walls_remarks
                                        WHEN master_switch = 0 THEN master_switch_remarks
                                        WHEN clean_rest = 0 THEN clean_rest_remarks
                                        WHEN underground_storage = 0 THEN underground_storage_remarks
                                        WHEN m_distance = 0 THEN m_distance_remarks
                                        WHEN vent = 0 THEN vent_remarks
                                        WHEN transfer_dispense = 0 THEN transfer_dispense_remarks
                                        WHEN no_drum = 0 THEN no_drum_remarks
                                        WHEN no_hoard = 0 THEN no_hoard_remarks
                                        WHEN free_tire_press = 0 THEN free_tire_press_remarks
                                        WHEN free_water = 0 THEN free_water_remarks
                                        WHEN basic_mechanical = 0 THEN basic_mechanical_remarks
                                        WHEN first_aid = 0 THEN first_aid_remarks
                                        WHEN design_eval = 0 THEN design_eval_remarks
                                        WHEN electric_eval = 0 THEN electric_eval_remarks
                                        WHEN under_deliver = 0 THEN under_deliver_remarks
                                        WHEN duplicate_retention_samples = 0 THEN duplicate_retention_samples_remarks
                                        WHEN appropriate_sampling = 0 THEN appropriate_sampling_remarks
                                        ELSE NULL
                                    END AS remarks
                                FROM standardcompliancechecklist 
                                WHERE itr_form_num = ?";
                        
                        $stmt =$pdo->prepare($sql);
                        $stmt->execute([$itr_num]);
                        $violations = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        
                        // Filter out NULL violations and display them
                        foreach ($violations as $violation) {
                            if ($violation['violation']) {
                                echo '<tr>';
                                echo '<td>' . htmlspecialchars($violation['violation']) . '</td>';
                                echo '<td>' . htmlspecialchars($violation['remarks']) . '</td>';
                                echo '</tr>';
                            }
                        }
                        
                        // Also show general remarks and action required
                        $sql_gen = "SELECT user_gen_remarks, action_required FROM generalremarks WHERE itr_form_num = ?";
                        $stmt_gen = $pdo->prepare($sql_gen);
                        $stmt_gen->execute([$itr_num]);
                        $general_remarks = $stmt_gen->fetch(PDO::FETCH_ASSOC);
                        
                        if ($general_remarks) {
                            echo '<tr class="table-info">';
                            echo '<td colspan="2"><strong>General Remarks:</strong> ' . htmlspecialchars($general_remarks['user_gen_remarks']) . '</td>';
                            echo '</tr>';
                            
                            echo '<tr class="table-warning">';
                            echo '<td colspan="2"><strong>Action Required:</strong> ' . htmlspecialchars($general_remarks['action_required']) . '</td>';
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr><td colspan="2">No ITR number provided</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>










<!-- COC Certificate Section -->
                                <div class="form-section">
                                    <div class="mb-3">
                                        <label for="coc_cert" class="form-label-mandatory">a.1 Certificate of Compliance (COC)</label>
                                        <input type="checkbox" class="form-check-input stateful-checkbox" id="coc_cert" name="coc_cert" value="1" data-state="0">
                                        <div class="remarks-container">
                                            <label for="coc_cert_remarks" class="label-remarks">Remarks</label>
                                            <input type="text" class="form-control-mandatory" id="coc_cert_remarks" name="coc_cert_remarks">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="coc_posted" class="form-label-mandatory">a.2 COC posted within business premises</label>
                                        <input type="checkbox" class="form-check-input stateful-checkbox" id="coc_posted" name="coc_posted" value="1" data-state="0">
                                        <div class="remarks-container">
                                            <label for="coc_posted_remarks" class="label-remarks">Remarks</label>
                                            <input type="text" class="form-control-mandatory" id="coc_posted_remarks" name="coc_posted_remarks">
                                        </div>
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
                                        <div class="remarks-container">
                                            <label for="valid_permit_LGU_remarks" class="label-remarks">Remarks</label>
                                            <input type="text" class="form-control-mandatory" id="valid_permit_LGU_remarks" name="valid_permit_LGU_remarks">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="valid_permit_BFP" class="form-label-mandatory">Valid Permit BFP</label>
                                        <input type="checkbox" class="form-check-input stateful-checkbox" id="valid_permit_BFP" name="valid_permit_BFP" value="1" data-state="0">
                                        <div class="remarks-container">
                                            <label for="valid_permit_BFP_remarks" class="label-remarks">Remarks</label>
                                            <input type="text" class="form-control-mandatory" id="valid_permit_BFP_remarks" name="valid_permit_BFP_remarks">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="valid_permit_DENR" class="form-label-mandatory">Valid Permit DENR</label>
                                        <input type="checkbox" class="form-check-input stateful-checkbox" id="valid_permit_DENR" name="valid_permit_DENR" value="1" data-state="0">
                                        <div class="remarks-container">
                                            <label for="valid_permit_DENR_remarks" class="label-remarks">Remarks</label>
                                            <input type="text" class="form-control-mandatory" id="valid_permit_DENR_remarks" name="valid_permit_DENR_remarks">
                                        </div>
                                    </div>
                                </div>

                                <!-- Testing and Calibration Section -->
                                <div class="form-section">
                                    <div class="mb-3">
                                        <label for="appropriate_test" class="form-label-mandatory">c. Appropriate Test Measure/Year Calibrated </label>
                                        <input type="checkbox" class="form-check-input stateful-checkbox" id="appropriate_test" name="appropriate_test" value="1" data-state="0">
                                        <div class="remarks-container">
                                            <label for="appropriate_test_remarks" class="label-remarks">Remarks</label>
                                            <input type="text" class="form-control-mandatory" id="appropriate_test_remarks" name="appropriate_test_remarks">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="week_calib" class="form-label-mandatory">d. Weekly Calibration Record/ Logbook</label>
                                        <input type="checkbox" class="form-check-input stateful-checkbox" id="week_calib" name="week_calib" value="1" data-state="0">
                                        <div class="remarks-container">
                                            <label for="week_calib_remarks" class="label-remarks">Remarks</label>
                                            <input type="text" class="form-control-mandatory" id="week_calib_remarks" name="week_calib_remarks">
                                        </div>
                                    </div>
                                </div>

                                <!-- Outlet Identification Section -->
                                <div class="form-section">
                                    <div class="mb-3">
                                        <label for="outlet_identify" class="form-label-mandatory">e. Outlet's Identification/Trademark</label>
                                        <input type="checkbox" class="form-check-input stateful-checkbox" id="outlet_identify" name="outlet_identify" value="1" data-state="0">
                                        <div class="remarks-container">
                                            <label for="outlet_identify_remarks" class="label-remarks">Remarks</label>
                                            <input type="text" class="form-control-mandatory" id="outlet_identify_remarks" name="outlet_identify_remarks">
                                        </div>
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
                                        <div class="remarks-container">
                                            <label for="pdb_entry_remarks" class="label-remarks">Remarks</label>
                                            <input type="text" class="form-control-mandatory" id="pdb_entry_remarks" name="pdb_entry_remarks">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="pdb_updated" class="form-label-mandatory">f.2. PDB w/ updated prices</label>
                                        <input type="checkbox" class="form-check-input stateful-checkbox" id="pdb_updated" name="pdb_updated" value="1" data-state="0">
                                        <div class="remarks-container">
                                            <label for="pdb_updated_remarks" class="label-remarks">Remarks</label>
                                            <input type="text" class="form-control-mandatory" id="pdb_updated_remarks" name="pdb_updated_remarks">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="pdb_match" class="form-label-mandatory">f.3. Price in PDB and dispensing pumps match</label>
                                        <input type="checkbox" class="form-check-input stateful-checkbox" id="pdb_match" name="pdb_match" value="1" data-state="0">
                                        <div class="remarks-container">
                                            <label for="pdb_match_remarks" class="label-remarks">Remarks</label>
                                            <input type="text" class="form-control-mandatory" id="pdb_match_remarks" name="pdb_match_remarks">
                                        </div>
                                    </div>
                                </div>


                            <!-- Labels and Biofuels Section -->
                            <div class="form-section">
                                    <div class="mb-3">
                                        <label for="ron_label" class="form-label-mandatory">g. Research Octane Number (RON) Labels for Gasoline</label>
                                        <input type="checkbox" class="form-check-input stateful-checkbox" id="ron_label" name="ron_label" value="1" data-state="0">
                                        <div class="remarks-container">
                                            <label for="ron_label_remarks" class="label-remarks">Remarks</label>
                                            <input type="text" class="form-control-mandatory" id="ron_label_remarks" name="ron_label_remarks">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="e10_label" class="form-label-mandatory">h. E-10 Label (contains 10% Bio-Ethanol) for Gasolinel</label>
                                        <input type="checkbox" class="form-check-input stateful-checkbox" id="e10_label" name="e10_label" value="1" data-state="0">
                                        <div class="remarks-container">
                                            <label for="e10_label_remarks" class="label-remarks">Remarks</label>
                                            <input type="text" class="form-control-mandatory" id="e10_label_remarks" name="e10_label_remarks">
                                        </div>
                                    </div>
                                
                                    <div class="mb-3">
                                        <label for="biofuels" class="form-label-mandatory">i. Biofuels (B₂) Labels for Diesel </label>
                                        <input type="checkbox" class="form-check-input stateful-checkbox" id="biofuels" name="biofuels" value="1" data-state="0">
                                        <div class="remarks-container">
                                            <label for="biofuels_remarks" class="label-remarks">Remarks</label>
                                            <input type="text" class="form-control-mandatory" id="biofuels_remarks" name="biofuels_remarks">
                                        </div>
                                    </div>
                                </div>

                                <!-- Consumer Safety Section -->
                                <div class="form-section">
                                    <div class="mb-3">
                                        <label for="consume_safety" class="form-label-mandatory">j. Consumer Safety and Informational Signs</label>
                                        <input type="checkbox" class="form-check-input stateful-checkbox" id="consume_safety" name="consume_safety" value="1" data-state="0">
                                        <div class="remarks-container">
                                            <label for="consume_safety_remarks" class="label-remarks">Remarks</label>
                                            <input type="text" class="form-control-mandatory" id="consume_safety_remarks" name="consume_safety_remarks">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="cel_warn" class="form-label-mandatory">j.1 No Cellphone Warning Sign</label>
                                        <input type="checkbox" class="form-check-input stateful-checkbox" id="cel_warn" name="cel_warn" value="1" data-state="0">
                                        <div class="remarks-container">
                                            <label for="cel_warn_remarks" class="label-remarks">Remarks</label>
                                            <input type="text" class="form-control-mandatory" id="cel_warn_remarks" name="cel_warn_remarks">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="smoke_sign" class="form-label-mandatory">j.2 No Smoking Sign</label>
                                        <input type="checkbox" class="form-check-input stateful-checkbox" id="smoke_sign" name="smoke_sign" value="1" data-state="0">
                                        <div class="remarks-container">
                                            <label for="smoke_sign_remarks" class="label-remarks">Remarks</label>
                                            <input type="text" class="form-control-mandatory" id="smoke_sign_remarks" name="smoke_sign_remarks">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="switch_eng" class="form-label-mandatory">j.3 Switch Off Engine while Filling Sign</label>
                                        <input type="checkbox" class="form-check-input stateful-checkbox" id="switch_eng" name="switch_eng" value="1" data-state="0">
                                        <div class="remarks-container">
                                            <label for="switch_eng_remarks" class="label-remarks">Remarks</label>
                                            <input type="text" class="form-control-mandatory" id="switch_eng_remarks" name="switch_eng_remarks">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="straddle" class="form-label-mandatory">j.4 No Straddling Sign (motorbike/tricycle)</label>
                                        <input type="checkbox" class="form-check-input stateful-checkbox" id="straddle" name="straddle" value="1" data-state="0">
                                        <div class="remarks-container">
                                            <label for="straddle_remarks" class="label-remarks">Remarks</label>
                                            <input type="text" class="form-control-mandatory" id="straddle_remarks" name="straddle_remarks">
                                        </div>
                                    </div>
                                
                                    <div class="mb-3">
                                        <label for="post_unleaded" class="form-label-mandatory">k. Non-posting of the term "unleaded" </label>
                                        <input type="checkbox" class="form-check-input stateful-checkbox" id="post_unleaded" name="post_unleaded" value="1" data-state="0">
                                        <div class="remarks-container">
                                            <label for="post_unleaded_remarks" class="label-remarks">Remarks</label>
                                            <input type="text" class="form-control-mandatory" id="post_unleaded_remarks" name="post_unleaded_remarks">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="post_biodiesel" class="form-label-mandatory">l. Non-posting of the term "biodiesel"</label>
                                        <input type="checkbox" class="form-check-input stateful-checkbox" id="post_biodiesel" name="post_biodiesel" value="1" data-state="0">
                                        <div class="remarks-container">
                                            <label for="post_biodiesel_remarks" class="label-remarks">Remarks</label>
                                            <input type="text" class="form-control-mandatory" id="post_biodiesel_remarks" name="post_biodiesel_remarks">
                                        </div>
                                    </div>
                                </div>

                                <!-- Receipts and Inspections Section -->
                                <div class="form-section">
                                    <div class="mb-3">
                                        <label for="issue_receipt" class="form-label-mandatory">m. Issuance of Official Receipts</label>
                                        <input type="checkbox" class="form-check-input stateful-checkbox" id="issue_receipt" name="issue_receipt" value="1" data-state="0">
                                        <div class="remarks-container">
                                            <label for="issue_receipt_remarks" class="label-remarks">Remarks</label>
                                            <input type="text" class="form-control-mandatory" id="issue_receipt_remarks" name="issue_receipt_remarks">
                                        </div>
                                    </div>
                                
                                    <div class="mb-3">
                                        <label for="non_refuse_inspect" class="form-label-mandatory">n. Non-refusal to Conduct Inspection</label>
                                        <input type="checkbox" class="form-check-input stateful-checkbox" id="non_refuse_inspect" name="non_refuse_inspect" value="1" data-state="0">
                                        <div class="remarks-container">
                                            <label for="non_refuse_inspect_remarks" class="label-remarks">Remarks</label>
                                            <input type="text" class="form-control-mandatory" id="non_refuse_inspect_remarks" name="non_refuse_inspect_remarks">
                                        </div>
                                    </div>
                                
                                    <div class="mb-3">
                                        <label for="non_refuse_sign" class="form-label-mandatory">n.1 Non-refusal to Conduct Sign ITRF</label>
                                        <input type="checkbox" class="form-check-input stateful-checkbox" id="non_refuse_sign" name="non_refuse_sign" value="1" data-state="0">
                                        <div class="remarks-container">
                                            <label for="non_refuse_sign_remarks" class="label-remarks">Remarks</label>
                                            <input type="text" class="form-control-mandatory" id="non_refuse_sign_remarks" name="non_refuse_sign_remarks">
                                        </div>
                                    </div>
                            
                                </div>

                                <!-- Dispensing and Safety Section -->
                                <div class="form-section">
                                    <div class="mb-3">
                                        <label for="fixed_dispense" class="form-label-mandatory">o.1 Fixed & permanent dispensing pump 6 meters from any potential source of ignition</label>
                                        <input type="checkbox" class="form-check-input stateful-checkbox" id="fixed_dispense" name="fixed_dispense" value="1" data-state="0">
                                        <div class="remarks-container">
                                            <label for="fixed_dispense_remarks" class="label-remarks">Remarks</label>
                                            <input type="text" class="form-control-mandatory" id="fixed_dispense_remarks" name="fixed_dispense_remarks">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="no_open_flame" class="form-label-mandatory">o.2 No open flame within15 meters</label>
                                        <input type="checkbox" class="form-check-input stateful-checkbox" id="no_open_flame" name="no_open_flame" value="1" data-state="0">
                                        <div class="remarks-container">
                                            <label for="no_open_flame_remarks" class="label-remarks">Remarks</label>
                                            <input type="text" class="form-control-mandatory" id="no_open_flame_remarks" name="no_open_flame_remarks">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="max_length_dispense" class="form-label-mandatory">o.3 5.5-meter maximum length of dispensing hosee</label>
                                        <input type="checkbox" class="form-check-input stateful-checkbox" id="max_length_dispense" name="max_length_dispense" value="1" data-state="0">
                                        <div class="remarks-container">
                                            <label for="max_length_dispense_remarks" class="label-remarks">Remarks</label>
                                            <input type="text" class="form-control-mandatory" id="max_length_dispense_remarks" name="max_length_dispense_remarks">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="peso_display" class="form-label-mandatory">o.4 Volume-Peso amount display up to two decimal places</label>
                                        <input type="checkbox" class="form-check-input stateful-checkbox" id="peso_display" name="peso_display" value="1" data-state="0">
                                        <div class="remarks-container">
                                            <label for="peso_display_remarks" class="label-remarks">Remarks</label>
                                            <input type="text" class="form-control-mandatory" id="peso_display_remarks" name="peso_display_remarks">
                                        </div>
                                    </div>
                                </div>

                               <!-- Pump and Infrastructure Section -->
                                <div class="form-section">
                                
                                    <div class="mb-3">
                                        <label for="pump_island" class="form-label-mandatory">p.1 Pump Island</label>
                                        <input type="checkbox" class="form-check-input stateful-checkbox" id="pump_island" name="pump_island" value="1" data-state="0">
                                        <div class="remarks-container">
                                            <label for="pump_island_remarks" class="label-remarks">Remarks</label>
                                            <input type="text" class="form-control-mandatory" id="pump_island_remarks" name="pump_island_remarks">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="lane_oriented_pump" class="form-label-mandatory">p.2 Lane-oriented pump with min. distance of 0.05 m from fixed object</label>
                                        <input type="checkbox" class="form-check-input stateful-checkbox" id="lane_oriented_pump" name="lane_oriented_pump" value="1" data-state="0">
                                        <div class="remarks-container">
                                            <label for="lane_oriented_pump_remarks" class="label-remarks">Remarks</label>
                                            <input type="text" class="form-control-mandatory" id="lane_oriented_pump_remarks" name="lane_oriented_pump_remarks">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="pump_guard" class="form-label-mandatory">p.3 Pump guard/column post as safety barrier</label>
                                        <input type="checkbox" class="form-check-input stateful-checkbox" id="pump_guard" name="pump_guard" value="1" data-state="0">
                                        <div class="remarks-container">
                                            <label for="pump_guard_remarks" class="label-remarks">Remarks</label>
                                            <input type="text" class="form-control-mandatory" id="pump_guard_remarks" name="pump_guard_remarks">
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="m_ingress" class="form-label-mandatory">p.4 7 m ingress/egress</label>
                                        <input type="checkbox" class="form-check-input stateful-checkbox" id="m_ingress" name="m_ingress" value="1" data-state="0">
                                        <div class="remarks-container">
                                            <label for="m_ingress_remarks" class="label-remarks">Remarks</label>
                                            <input type="text" class="form-control-mandatory" id="m_ingress_remarks" name="m_ingress_remarks">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="m_edge" class="form-label-mandatory">p.5 6 m edge to edge distance between pump islands</label>
                                        <input type="checkbox" class="form-check-input stateful-checkbox" id="m_edge" name="m_edge" value="1" data-state="0">
                                        <div class="remarks-container">
                                            <label for="m_edge_remarks" class="label-remarks">Remarks</label>
                                            <input type="text" class="form-control-mandatory" id="m_edge_remarks" name="m_edge_remarks">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="office_cashier" class="form-label-mandatory">q.1 Office/cashier's booth</label>
                                        <input type="checkbox" class="form-check-input stateful-checkbox" id="office_cashier" name="office_cashier" value="1" data-state="0">
                                        <div class="remarks-container">
                                            <label for="office_cashier_remarks" class="label-remarks">Remarks</label>
                                            <input type="text" class="form-control-mandatory" id="office_cashier_remarks" name="office_cashier_remarks">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="min_canopy" class="form-label-mandatory">q.2 4.5 m minimum canopy height</label>
                                        <input type="checkbox" class="form-check-input stateful-checkbox" id="min_canopy" name="min_canopy" value="1" data-state="0">
                                        <div class="remarks-container">
                                            <label for="min_canopy_remarks" class="label-remarks">Remarks</label>
                                            <input type="text" class="form-control-mandatory" id="min_canopy_remarks" name="min_canopy_remarks">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="boundary_walls" class="form-label-mandatory">q.3 Boundary walls (concrete or cyclone fence)s</label>
                                        <input type="checkbox" class="form-check-input stateful-checkbox" id="boundary_walls" name="boundary_walls" value="1" data-state="0">
                                        <div class="remarks-container">
                                            <label for="boundary_walls_remarks" class="label-remarks">Remarks</label>
                                            <input type="text" class="form-control-mandatory" id="boundary_walls_remarks" name="boundary_walls_remarks">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="master_switch" class="form-label-mandatory">q.4 Master switch in case of emergency</label>
                                        <input type="checkbox" class="form-check-input stateful-checkbox" id="master_switch" name="master_switch" value="1" data-state="0">
                                        <div class="remarks-container">
                                            <label for="master_switch_remarks" class="label-remarks">Remarks</label>
                                            <input type="text" class="form-control-mandatory" id="master_switch_remarks" name="master_switch_remarks">
                                        </div>
                                    </div>
                                
                                    <div class="mb-3">
                                        <label for="clean_rest" class="form-label-mandatory">q.5 Clean restroom</label>
                                        <input type="checkbox" class="form-check-input stateful-checkbox" id="clean_rest" name="clean_rest" value="1" data-state="0">
                                        <div class="remarks-container">
                                            <label for="clean_rest_remarks" class="label-remarks">Remarks</label>
                                            <input type="text" class="form-control-mandatory" id="clean_rest_remarks" name="clean_rest_remarks">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="underground_storage" class="form-label-mandatory">r.1 Underground storage tank (UGT) with rain tight fill sump and monitoring wells</label>
                                        <input type="checkbox" class="form-check-input stateful-checkbox" id="underground_storage" name="underground_storage" value="1" data-state="0">
                                        <div class="remarks-container">
                                            <label for="underground_storage_remarks" class="label-remarks">Remarks</label>
                                            <input type="text" class="form-control-mandatory" id="underground_storage_remarks" name="underground_storage_remarks">
                                        </div>
                                    </div>
                                
                                    <div class="mb-3">
                                        <label for="m_distance" class="form-label-mandatory">r.2 1 m distance from property line and adjoining structure</label>
                                        <input type="checkbox" class="form-check-input stateful-checkbox" id="m_distance" name="m_distance" value="1" data-state="0">
                                        <div class="remarks-container">
                                            <label for="m_distance_remarks" class="label-remarks">Remarks</label>
                                            <input type="text" class="form-control-mandatory" id="m_distance_remarks" name="m_distance_remarks">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="vent" class="form-label-mandatory">r.3 3.65 m vent lines</label>
                                        <input type="checkbox" class="form-check-input stateful-checkbox" id="vent" name="vent" value="1" data-state="0">
                                        <div class="remarks-container">
                                            <label for="vent_remarks" class="label-remarks">Remarks</label>
                                            <input type="text" class="form-control-mandatory" id="vent_remarks" name="vent_remarks">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="transfer_dispense" class="form-label-mandatory">s.1 Transfer/dispensing on approved containers only</label>
                                        <input type="checkbox" class="form-check-input stateful-checkbox" id="transfer_dispense" name="transfer_dispense" value="1" data-state="0">
                                        <div class="remarks-container">
                                            <label for="transfer_dispense_remarks" class="label-remarks">Remarks</label>
                                            <input type="text" class="form-control-mandatory" id="transfer_dispense_remarks" name="transfer_dispense_remarks">
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="no_drum" class="form-label-mandatory">s.2 No Drumming / "Bote-Bote" of Liquid Fuels</label>
                                        <input type="checkbox" class="form-check-input stateful-checkbox" id="no_drum" name="no_drum" value="1" data-state="0">
                                        <div class="remarks-container">
                                            <label for="no_drum_remarks" class="label-remarks">Remarks</label>
                                            <input type="text" class="form-control-mandatory" id="no_drum_remarks" name="no_drum_remarks">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="no_hoard" class="form-label-mandatory">t.1 No Hoarding</label>
                                        <input type="checkbox" class="form-check-input stateful-checkbox" id="no_hoard" name="no_hoard" value="1" data-state="0">
                                        <div class="remarks-container">
                                            <label for="no_hoard_remarks" class="label-remarks">Remarks</label>
                                            <input type="text" class="form-control-mandatory" id="no_hoard_remarks" name="no_hoard_remarks">
                                        </div>
                                    </div>
                                </div>

                                <!-- Free Services Section -->
                                <div class="form-section">
                                    <div class="mb-3">
                                        <label for="free_tire_press" class="form-label-mandatory">u1. Offers free tire pressure air filling</label>
                                        <input type="checkbox" class="form-check-input stateful-checkbox" id="free_tire_press" name="free_tire_press" value="1" data-state="0">
                                        <div class="remarks-container">
                                            <label for="free_tire_press_remarks" class="label-remarks">Remarks</label>
                                            <input type="text" class="form-control-mandatory" id="free_tire_press_remarks" name="free_tire_press_remarks">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="free_water" class="form-label-mandatory">u2. Offers free water for radiator</label>
                                        <input type="checkbox" class="form-check-input stateful-checkbox" id="free_water" name="free_water" value="1" data-state="0">
                                        <div class="remarks-container">
                                            <label for="free_water_remarks" class="label-remarks">Remarks</label>
                                            <input type="text" class="form-control-mandatory" id="free_water_remarks" name="free_water_remarks">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="basic_mechanical" class="form-label-mandatory">u3. Basic mechanical services</label>
                                        <input type="checkbox" class="form-check-input stateful-checkbox" id="basic_mechanical" name="basic_mechanical" value="1" data-state="0">
                                        <div class="remarks-container">
                                            <label for="basic_mechanical_remarks" class="label-remarks">Remarks</label>
                                            <input type="text" class="form-control-mandatory" id="basic_mechanical_remarks" name="basic_mechanical_remarks">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="first_aid" class="form-label-mandatory">u4. First aid kits</label>
                                        <input type="checkbox" class="form-check-input stateful-checkbox" id="first_aid" name="first_aid" value="1" data-state="0">
                                        <div class="remarks-container">
                                            <label for="first_aid_remarks" class="label-remarks">Remarks</label>
                                            <input type="text" class="form-control-mandatory" id="first_aid_remarks" name="first_aid_remarks">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="design_eval" class="form-label-mandatory">u5. Designated evacuation assembly area</label>
                                        <input type="checkbox" class="form-check-input stateful-checkbox" id="design_eval" name="design_eval" value="1" data-state="0">
                                        <div class="remarks-container">
                                            <label for="design_eval_remarks" class="label-remarks">Remarks</label>
                                            <input type="text" class="form-control-mandatory" id="design_eval_remarks" name="design_eval_remarks">
                                        </div>
                                    </div>
                            
                                    <div class="mb-3">
                                        <label for="electric_eval" class="form-label-mandatory">u6. Electric vehicle charging facility</label>
                                        <input type="checkbox" class="form-check-input stateful-checkbox" id="electric_eval" name="electric_eval" value="1" data-state="0">
                                        <div class="remarks-container">
                                            <label for="electric_eval_remarks" class="label-remarks">Remarks</label>
                                            <input type="text" class="form-control-mandatory" id="electric_eval_remarks" name="electric_eval_remarks">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="under_deliver" class="form-label-mandatory">Under Deliver</label>
                                        <input type="checkbox" class="form-check-input stateful-checkbox" id="under_deliver" name="under_deliver" value="1" data-state="0">
                                        <div class="remarks-container">
                                            <label for="under_deliver_remarks" class="label-remarks"> Remarks</label>
                                            <input type="text" class="form-control-mandatory" id="under_deliver_remarks" name="under_deliver_remarks">
                                        </div>
                                    </div>
                                </div>














etCheckboxState('coc_posted', data.checklist.coc_posted);
                    document.getElementById('coc_posted_remarks').value = data.checklist.coc_posted_remarks || '';
                    setCheckboxState('valid_permit_LGU', data.checklist.valid_permit_LGU);
                    document.getElementById('valid_permit_LGU_remarks').value = data.checklist.valid_permit_LGU_remarks || '';
                    setCheckboxState('valid_permit_BFP', data.checklist.valid_permit_BFP);
                    document.getElementById('valid_permit_BFP_remarks').value = data.checklist.valid_permit_BFP_remarks || '';
                    setCheckboxState('valid_permit_DENR', data.checklist.valid_permit_DENR);
                    document.getElementById('valid_permit_DENR_remarks').value = data.checklist.valid_permit_DENR_remarks || '';
                    setCheckboxState('appropriate_test', data.checklist.appropriate_test);
                    document.getElementById('appropriate_test_remarks').value = data.checklist.appropriate_test_remarks || '';
                    setCheckboxState('week_calib', data.checklist.week_calib);
                    document.getElementById('week_calib_remarks').value = data.checklist.week_calib_remarks || '';
                    setCheckboxState('outlet_identify', data.checklist.outlet_identify);
                    document.getElementById('outlet_identify_remarks').value = data.checklist.outlet_identify_remarks || '';
                    setCheckboxState('pdb_entry', data.checklist.pdb_entry);
                    document.getElementById('pdb_entry_remarks').value = data.checklist.pdb_entry_remarks || '';
                    setCheckboxState('pdb_updated', data.checklist.pdb_updated);
                    document.getElementById('pdb_updated_remarks').value = data.checklist.pdb_updated_remarks || '';
                    setCheckboxState('pdb_match', data.checklist.pdb_match);
                    document.getElementById('pdb_match_remarks').value = data.checklist.pdb_match_remarks || '';
                    setCheckboxState('ron_label', data.checklist.ron_label);
                    document.getElementById('ron_label_remarks').value = data.checklist.ron_label_remarks || '';
                    setCheckboxState('e10_label', data.checklist.e10_label);
                    document.getElementById('e10_label_remarks').value = data.checklist.e10_label_remarks || '';
                    setCheckboxState('biofuels', data.checklist.biofuels);
                    document.getElementById('biofuels_remarks').value = data.checklist.biofuels_remarks || '';
                    setCheckboxState('consume_safety', data.checklist.consume_safety);
                    document.getElementById('consume_safety_remarks').value = data.checklist.consume_safety_remarks || '';
                    setCheckboxState('cel_warn', data.checklist.cel_warn);
                    document.getElementById('cel_warn_remarks').value = data.checklist.cel_warn_remarks || '';
                    setCheckboxState('smoke_sign', data.checklist.smoke_sign);
                    document.getElementById('smoke_sign_remarks').value = data.checklist.smoke_sign_remarks || '';
                    setCheckboxState('switch_eng', data.checklist.switch_eng);
                    document.getElementById('switch_eng_remarks').value = data.checklist.switch_eng_remarks || '';
                    setCheckboxState('straddle', data.checklist.straddle);
                    document.getElementById('straddle_remarks').value = data.checklist.straddle_remarks || '';
                    setCheckboxState('post_unleaded', data.checklist.post_unleaded);
                    document.getElementById('post_unleaded_remarks').value = data.checklist.post_unleaded_remarks || '';
                    setCheckboxState('post_biodiesel', data.checklist.post_biodiesel);
                    document.getElementById('post_biodiesel_remarks').value = data.checklist.post_biodiesel_remarks || '';
                    setCheckboxState('issue_receipt', data.checklist.issue_receipt);
                    document.getElementById('issue_receipt_remarks').value = data.checklist.issue_receipt_remarks || '';
                    setCheckboxState('non_refuse_inspect', data.checklist.non_refuse_inspect);
                    document.getElementById('non_refuse_inspect_remarks').value = data.checklist.non_refuse_inspect_remarks || '';
                    setCheckboxState('non_refuse_sign', data.checklist.non_refuse_sign);
                    document.getElementById('non_refuse_sign_remarks').value = data.checklist.non_refuse_sign_remarks || '';
                    setCheckboxState('fixed_dispense', data.checklist.fixed_dispense);
                    document.getElementById('fixed_dispense_remarks').value = data.checklist.fixed_dispense_remarks || '';
                    setCheckboxState('no_open_flame', data.checklist.no_open_flame);
                    document.getElementById('no_open_flame_remarks').value = data.checklist.no_open_flame_remarks || '';
                    setCheckboxState('max_length_dispense', data.checklist.max_length_dispense);
                    document.getElementById('max_length_dispense_remarks').value = data.checklist.max_length_dispense_remarks || '';
                    setCheckboxState('peso_display', data.checklist.peso_display);
                    document.getElementById('peso_display_remarks').value = data.checklist.peso_display_remarks || '';
                    setCheckboxState('pump_island', data.checklist.pump_island);
                    document.getElementById('pump_island_remarks').value = data.checklist.pump_island_remarks || '';
                    setCheckboxState('lane_oriented_pump', data.checklist.lane_oriented_pump);
                    document.getElementById('lane_oriented_pump_remarks').value = data.checklist.lane_oriented_pump_remarks || '';
                    setCheckboxState('pump_guard', data.checklist.pump_guard);
                    document.getElementById('pump_guard_remarks').value = data.checklist.pump_guard_remarks || '';
                    setCheckboxState('m_ingress', data.checklist.m_ingress);
                    document.getElementById('m_ingress_remarks').value = data.checklist.m_ingress_remarks || '';
                    setCheckboxState('m_edge', data.checklist.m_edge);
                    document.getElementById('m_edge_remarks').value = data.checklist.m_edge_remarks || '';
                    setCheckboxState('office_cashier', data.checklist.office_cashier);
                    document.getElementById('office_cashier_remarks').value = data.checklist.office_cashier_remarks || '';
                    setCheckboxState('min_canopy', data.checklist.min_canopy);
                    document.getElementById('min_canopy_remarks').value = data.checklist.min_canopy_remarks || '';
                    setCheckboxState('boundary_walls', data.checklist.boundary_walls);
                    document.getElementById('boundary_walls_remarks').value = data.checklist.boundary_walls_remarks || '';
                    setCheckboxState('master_switch', data.checklist.master_switch);
                    document.getElementById('master_switch_remarks').value = data.checklist.master_switch_remarks || '';
                    setCheckboxState('clean_rest', data.checklist.clean_rest);
                    document.getElementById('clean_rest_remarks').value = data.checklist.clean_rest_remarks || '';
                    setCheckboxState('underground_storage', data.checklist.underground_storage);
                    document.getElementById('underground_storage_remarks').value = data.checklist.underground_storage_remarks || '';
                    setCheckboxState('m_distance', data.checklist.m_distance);
                    document.getElementById('m_distance_remarks').value = data.checklist.m_distance_remarks || '';
                    setCheckboxState('vent', data.checklist.vent);
                    document.getElementById('vent_remarks').value = data.checklist.vent_remarks || '';
                    setCheckboxState('transfer_dispense', data.checklist.transfer_dispense);
                    document.getElementById('transfer_dispense_remarks').value = data.checklist.transfer_dispense_remarks || '';
                    setCheckboxState('no_drum', data.checklist.no_drum);
                    document.getElementById('no_drum_remarks').value = data.checklist.no_drum_remarks || '';
                    setCheckboxState('no_hoard', data.checklist.no_hoard);
                    document.getElementById('no_hoard_remarks').value = data.checklist.no_hoard_remarks || '';
                    setCheckboxState('free_tire_press', data.checklist.free_tire_press);
                    document.getElementById('free_tire_press_remarks').value = data.checklist.free_tire_press_remarks || '';
                    setCheckboxState('free_water', data.checklist.free_water);
                    document.getElementById('free_water_remarks').value = data.checklist.free_water_remarks || '';
                    setCheckboxState('basic_mechanical', data.checklist.basic_mechanical);
                    document.getElementById('basic_mechanical_remarks').value = data.checklist.basic_mechanical_remarks || '';
                    setCheckboxState('first_aid', data.checklist.first_aid);
                    document.getElementById('first_aid_remarks').value = data.checklist.first_aid_remarks || '';
                    setCheckboxState('design_eval', data.checklist.design_eval);
                    document.getElementById('design_eval_remarks').value = data.checklist.design_eval_remarks || '';
                    setCheckboxState('electric_eval', data.checklist.electric_eval);
                    document.getElementById('electric_eval_remarks').value = data.checklist.electric_eval_remarks || '';
                    setCheckboxState('under_deliver', data.checklist.under_deliver);
                    document.getElementById('under_deliver_remarks').value = data.checklist.under_deliver_remarks || '';