<?php
ob_start(); // Start output buffering
require('fpdf/fpdf.php');
include 'config.php';
header('Content-Type: text/html; charset=utf-8');

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $itr_form_num = $_GET['id'];
    $sql_business_info = "SELECT * FROM businessinfo WHERE itr_form_num ='$itr_form_num'";
    $result_business_info = $conn->query( $sql_business_info);

    if ( $result_business_info->num_rows > 0) {
            $data_business_info =  $result_business_info->fetch_assoc();

            $pdf = new FPDF('P', 'mm', 'Legal'); 
            $pdf->AddPage();

            $pdf->AddFont('arialnarrow', '', 'arialnarrow.php');
            $pdf->Image('doe.jpg', 12, 16.5, 17);
            $pdf->Image('itrf_p1_bg.jpg', 0, 0, 216, 356); 
            $pdf->SetFont('arialnarrow', '', 9);
            
              //variables data
            $data_business_info['outlet_classification'] = "X";
            $height = 3.2;
            $headerX= 49.5;
            $headerY= 34.5;
            $headerXpadding= 0.9;
            $bordersize= 0;
            $headercol1 = 109.5;
            $headercol2 = 35;
           
            //1st row
            $pdf->SetTextColor(1,1,1);
            $pdf->SetXY($headerX + 97, $headerY + $height  + $headerXpadding - 15.5); 
            $pdf->Cell( $headercol1 - 80, $height, $data_business_info['itr_form_num'] ,  $bordersize, 0);

            $pdf->SetXY($headerX , $headerY + $height * 1 + $headerXpadding ); 
            $pdf->Cell( $headercol1, $height, $data_business_info['business_name'] ,  $bordersize, 0);
            $pdf->Cell($headercol2, $height,  $data_business_info['date_time'],  $bordersize, 0);

            //2nd row
            $pdf->SetXY($headerX , $headerY + $height * 2 + $headerXpadding ); 
            $pdf->Cell( $headercol1, $height, $data_business_info['dealer_operator'] ,  $bordersize, 0);
            $combinedSA = $data_business_info['sa_no'] . '/' .  $data_business_info['sa_date'];
            $pdf->Cell($headercol2, $height,  $combinedSA,  $bordersize, 0);

            //3rd row
            $pdf->SetXY($headerX , $headerY + $height * 6 + $headerXpadding ); 
            $pdf->Cell( $headercol1, $height, $data_business_info['designation'] ,  $bordersize, 0);
             $pdf->Cell($headercol2, $height,  $data_business_info['email_add'],  $bordersize, 0);
             
            //4th row
            $pdf->SetXY($headerX , $headerY + $height * 3 + $headerXpadding ); 
            $strlength = strlen($data_business_info['location']);
            //conditional if greater than 57
            if ($strlength > 57) {
                
            // Find the position of the last space within the first 57 characters
            $last_space_position = strrpos(substr($data_business_info['location'], 0, 57), ' ');
            

            // If a space is found, split the string at the last space
             if ($last_space_position !== false) 
                {
            $first_part = substr($data_business_info['location'], 0, $last_space_position);
            $second_part = substr($data_business_info['location'], $last_space_position + 1);
        } 
             else
        {
            // If no space is found, split the string at the 57th character
             $first_part = substr($data_business_info['location'], 0, 57);
             $second_part = substr($data_business_info['location'],0, 57);
             
        }

            // Add a hyphen to the first part if it is split in the middle of a word
            if ($last_space_position === false || $last_space_position < 57) 
        {
          


             $pdf->Cell($headercol1, $height, $first_part, $bordersize, 0);

             // Move to the next line and print the second part
            $pdf->SetXY($headerX , $headerY + $height * 4 + $headerXpadding ); 
            $second_sub_part = substr($second_part, 0,  60);
            $pdf->Cell($headercol1, $height, $second_sub_part, $bordersize, 0);
        }
        } 
            else
        {
             
            
             $pdf->Cell($headercol1 , $height ,$data_business_info['location'], $bordersize, 0);     
        }

        //Outlet Classif
        $pdf->SetFont('ZapfDingbats', '', 10.5);
        $outletClassifications = [
            'COCO' => 0,  
            'CODO' => 12,  
            'DODO' => 23,  
        ];
        
        $outletclassif = $data_business_info['outlet_class'];
        foreach ($outletClassifications as $classification => $xOffset) {
            $xPosition = $headerX + 109.5 + $xOffset; 
            $yPosition = $headerY + $height * 3 + $headerXpadding; 
        
            $pdf->SetXY($xPosition, $yPosition - 0.5);
        
            if ($outletclassif == $classification) {
                $content = chr(51); 
            } else {
                $content = ''; 
            }
            $pdf->Cell(11.5, $height, $content, $bordersize, 0);
        }

       
        $pdf->SetFont('arialnarrow', '', 9);
        //5th row
            
         $pdf->SetXY($headerX + 109.5 , $headerY + $height * 4 + $headerXpadding ); 
         $pdf->Cell($headercol2, $height,  $data_business_info['company'],  $bordersize, 0);

        //6th row
         $pdf->SetXY($headerX , $headerY + $height * 5 + $headerXpadding ); 
         $pdf->Cell( $headercol1, $height, $data_business_info['in_charge'] ,  $bordersize, 0);
         $pdf->Cell($headercol2, $height,  $data_business_info['contact_tel'],  $bordersize, 0);




         //Standard Compliance Checklist--------------------------------->
            $colchecklist1 = 88.5;
            $colchecklist2 = 10;
            $bordersize_cheklist =0;
            $sql_chekclist = "SELECT * FROM standardcompliancechecklist WHERE itr_form_num ='$itr_form_num'";
            $result_checklist = $conn->query($sql_chekclist);

            if ($result_checklist === false) {
                die("Query failed: " . $conn->error);
            }
            $data_checklist = $result_checklist->fetch_assoc();


           
            $pdf->SetFont('ZapfDingbats', '', 10.5);

            $YES = chr(51); // ✔ (Check mark)
            $NO =  chr(53); // ✘ (Cross mark)

             $arr_col1 = [
                'coc_cert',
                'coc_posted',
                'appropriate_test',
                'valid_permits',
                'week_calib',
                'outlet_identify',
                'price_display',
                'pdb_entry',
                'pdb_updated',
                'pdb_match',
                'ron_label',
                'e10_label',
                'biofuels',
                'consume_safety',
                'cel_warn',
                'smoke_sign',
                'switch_eng',
                'straddle',
                'post_unleaded',
                'post_biodiesel',
                'issue_receipt',
                'non_refuse_inspect',
                'fixed_dispense',
                 null,
                'no_open_flame',
                'max_length_dispense',
                'peso_display'     
                
            ];

             $arr_col2 = [
                'pump_island',
                'lane_oriented_pump',
                null,
                'pump_guard',
                'm_ingress',
                'm_edge',
                'office_cashier',
                'min_canopy',
                'boundary_walls',
                'master_switch',
                'clean_rest',
                'underground_storage',
                null,
                'm_distance',
                'vent',
                'transfer_dispense',
                'no_drum',
                'no_hoard',
                 null,
                'free_tire_press',
                'free_water',
                'basic_mechanical',
                'first_aid',
                'design_eval',
                'electric_eval',];
               // 'under_deliver'
            
            for ($i = 0; $i < count($arr_col1); $i++) {
                $j = 9 + $i;
            
                $pdf->SetXY($headerX + 48, $headerY - .5 + $height * ($j) + $headerXpadding);
            
                if ($arr_col1[$i] != null) {
                    $value1 = ($data_checklist[$arr_col1[$i]] ? $YES : $NO);
            
                    if ($value1 == $NO) {
                        $pdf->SetTextColor(255, 0, 0); 
                    } else {
                        $pdf->SetTextColor(0, 0, 0);
                    }

                } 
                else {
                    $value1 = "";
                }
                $pdf->Cell($colchecklist1, $height, $value1, $bordersize_cheklist, 0);
            
                if ($arr_col2[$i] != null) {
                    $value = ($data_checklist[$arr_col2[$i]] ? $YES : $NO);
                    if ($value == $NO) {
                        $pdf->SetTextColor(255, 0, 0); 
                    } else {
                        $pdf->SetTextColor(0, 0, 0); 
                    }
                } else {
                    $value = "";
                }
            
                $pdf->Cell($colchecklist2, $height, $value, $bordersize_cheklist, 0);
            }
            

           $valid_permits = [
            'valid_permit_LGU',
            'valid_permit_BFP',
            'valid_permit_DENR',
           ];
               
            
           $pdf->SetFont('ZapfDingbats','',9.5);
            $permit_yes =(chr(51)); // ✔ (Check mark)
           $permit_no = (chr(53)); // ✘ (Cross mark)
           $permitcol = 7;

           for ($i=0; $i<3; $i++){
            $h= 2.5+ ($i*7) ;
            $pdf->SetXY($headerX + $h, $headerY + $height * 10.7 + $headerXpadding); 

            if($data_checklist[$valid_permits[$i]] == 1) {  
                $pdf->Cell($permitcol+10, $height, $permit_yes, $bordersize_checklist, 0);
            }
            else{
                $pdf->Cell($permitcol, $height, $permit_no, $bordersize_checklist, 0);
            }
           }


        
           // if($permits == 1){
            //    $pdf->SetXY($headerX + 2, $headerY + $height * 10.7 + $headerXpadding); 
            //    $pdf->Cell($permitcol - 1, $height, $permit_yes, $bordersize_checklist, 1);
              //  }
            //    $pdf->SetXY($headerX + 2, $headerY + $height * 10.7 + $headerXpadding); 
                //$pdf->Cell($permitcol - 1, $height, $permit_no, $bordersize_checklist, 1);
     
           


          //Second Page-------------------------------------------------------------------------------------// 
            $pdf->AddPage();

            $pdf->AddFont('arialnarrow', '', 'arialnarrow.php');
           // $pdf->Image('doe.jpg', 12, 16.5, 17);
           $pdf->Image('itrf_p2_bg.jpg', 0, 0, 216, 356); 
            $pdf->SetFont('arialnarrow', '', 9);
            
            $secondpageborder=0;
            $secondpageheight=3.5;
            $headerX2ndPage =170;
            $headerY2ndPage =11.5  ;

             //1st row itrform num and page no.
             $pdf->SetTextColor(1,1,1);
             $pdf->SetXY( $headerX2ndPage,   $headerY2ndPage + $headerXpadding ); 
             $pdf->Cell( $headercol1 - 80 ,  $secondpageheight, $itr_form_num ,  $secondpageborder, 0);
             $pdf->SetXY( $headerX2ndPage,  $headerY2ndPage+3.5 + $headerXpadding ); 
             $pdf->Cell( $headercol1 - 80,  $secondpageheight, $itr_form_num ,  $secondpageborder, 0);

            //allowedd sampling prodcut quality--------------------------------->
            $pdf->SetFont('arialnarrow', '', 9);
            $sql_sampling = "SELECT id, code_value, product, ron_value, UGT, pump FROM productquality WHERE itr_form_num = '$itr_form_num'";
            $result_sampling = $conn->query($sql_sampling);
            $headerX2ndPage =18;
             $headercol_sampling = 10;
            
            $i = 0;
            while ($data_sampling = $result_sampling->fetch_assoc()) {
                $pdf->SetXY($headerX2ndPage, $headerY2ndPage + 22 + $secondpageheight * $i + $headerXpadding);
                $pdf->Cell($headercol_sampling + 23, $secondpageheight, $data_sampling['code_value'], $secondpageborder, 0);
                $pdf->Cell($headercol_sampling + 8, $secondpageheight, $data_sampling['product'], $secondpageborder, 0);
                $pdf->Cell($headercol_sampling + 4.5, $secondpageheight, $data_sampling['ron_value'], $secondpageborder, 0, 'C');
                $pdf->Cell($headercol_sampling + 7, $secondpageheight, $data_sampling['UGT'], $secondpageborder, 0, 'C');
                $pdf->Cell($headercol_sampling + 9, $secondpageheight, $sukod, $secondpageborder, 0,'C');
            
                $i++; }
           
                $sukod = strlen($result_supp_info['supplier']);
                 
             //Suppliers Information--------------------------------->
            $pdf->SetFont('arialnarrow', '', 9);
            $sql_supp_info = "SELECT id, receipt_invoice, supplier, date_deliver, address ,contact_num  FROM suppliersinfo WHERE itr_form_num = '$itr_form_num'";
            $result_supp_info = $conn->query($sql_supp_info);  

            
            if ($result_supp_info === false) {
                die("Query failed: " . $conn->error);
            }
            $data_supp_info = $result_supp_info->fetch_assoc();

              $arrays_supp_info = [
                 'receipt_invoice',
                 'supplier',
                'date_deliver',
                'address',
                null,
                'contact_num'
               ];        

             for($i=0; $i < count($arrays_supp_info); $i++){
                $h=7+$i;
                $pdf->SetXY($headerX2ndPage -183 , $headerY2ndPage +22+$secondpageheight * $h   + $headerXpadding ); 
                if($arrays_supp_info[$i] != null){

                    $data_supp_info_blank = $data_supp_info[$arrays_supp_info[$i]];
                }
                 else {
                    $data_supp_info_blank = "";
                 
                
                }
                $pdf->MultiCell( $headercol1-41,  $secondpageheight,  $data_supp_info_blank,  $secondpageborder, 0, 0,5);
            }


             //with and without sample retentions--------------------------------->
             $sql_prod_quality = "SELECT duplicate_retention_samples,retention_retail, appropriate_sampling, inappropriate_sampling  FROM productqualitycont WHERE itr_form_num ='$itr_form_num'";
             $result_prod_quality = $conn->query($sql_prod_quality);
             if ($result_prod_quality === false) {
                die("Query failed: " . $conn->error);
            }
             $data_product_quality = $result_prod_quality->fetch_assoc();
             $array_prod = [
                 'duplicate_retention_samples',
                  'retention_retail',
                 'appropriate_sampling',
                 'inappropriate_sampling'
             ];
             
             $pdf->SetFont('ZapfDingbats','',10.5);
             $PRODUCT_YES =(chr(51)); // ✔ (Check mark)
             $NO = chr(032); // ✘ (Cross mark)
             
             for ($i = 0; $i < count($array_prod); $i++) {
                $p = 18 + $i * 4;
                $offsetX = ($i == 2 || $i == 3) ? 5 : 0; 
                $offsetY = ($i ==2 || $i== 3) ? -1 : 0;
            
                $pdf->SetXY($headerX2ndPage - 110.5 + $offsetX, $headerY2ndPage + $p + $headerXpadding + $offsetY); 
                
                if ($array_prod[$i] != null) {
                    $valueprod = $data_product_quality[$array_prod[$i]] ? $PRODUCT_YES : $NO;
                } else {
                    $valueprod = "";
                }
            
                $pdf->Cell($headercol1 - 105, $secondpageheight, $valueprod, $secondpageborder, 0);
            }
            

            //with and without violations -------------------------->
            $pdf->SetFont('ZapfDingbats', '', 10.5);
            $PRODUCT_YES = chr(51); // ✔ (Check mark)
            $PRODUCT_NO = chr(032); // Blank
            // Boolean fields array
            
            $booleanFields_Compliance = [ 'coc_certificate', 'coc_posted', 'valid_permit_LGU', 'valid_permit_BFP', 'valid_permit_DENR', 
            'appropriate_test', 'week_calib', 'outlet_identify', 'price_display', 'pdb_entry', 
            'pdb_updated', 'pdb_match', 'ron_label', 'e10_label', 'biofuels', 'consumer_safety', 
            'no_cel_warn', 'no_smoke_sign', 'switch_eng', 'no_straddle', 'non_post_unleaded', 
            'non_post_biodiesel', 'issue_receipt', 'non_refuse_inspect', 'fixed_dispense', 
            'no_open_flame', 'max_length_dispense', 'peso_display', 'pump_island', 
            'lane_oriented_pump', 'pump_guard', 'm_ingress', 'm_edge', 'office_cashier', 
            'min_canopy', 'boundary_walls', 'master_switch', 'clean_rest', 'underground_storage', 
            'm_distance', 'vent', 'transfer_dispense', 'no_drum', 'no_hoard', 'free_tire_press', 
            'free_water', 'basic_mechanical', 'first_aid', 'design_eval', 'electric_eval', 
            'under_deliver'
            ];
            $allFieldsValid = 1;
            foreach ($booleanFields_Compliance as $field) {
                if (!isset ($row[$field]) ||  $row[$field] == 0) { // Assuming $row contains the database row
                    $allFieldsValid = 0;
                    break;
                }
            }
            // With violation   
            $x_add = ($allFieldsValid) ? 13  :  -32;

            $pdf->SetXY($headerX2ndPage -109 - $x_add, $headerY2ndPage + 71 + $headerXpadding);
            $pdf->Cell($headercol1 - 105, $secondpageheight, $PRODUCT_YES, $secondpageborder, 0);

            
            //Violations Summary--------------------------------->
            $pdf->SetFont('arialnarrow', '', 9);
            $sql_remarks_summary = "SELECT * FROM standardcompliancechecklist WHERE itr_form_num = '$itr_form_num'";

            $boolean_remarks_pairs = [
                ['coc_cert', 'coc_cert_remarks'], ['coc_posted', 'coc_posted_remarks'],
                ['valid_permit_LGU', 'valid_permit_LGU_remarks'],
                ['valid_permit_BFP', 'valid_permit_BFP_remarks'],
                ['valid_permit_DENR', 'valid_permit_DENR_remarks'],
                ['appropriate_test', 'appropriate_test_remarks'],
                ['week_calib', 'week_calib_remarks'],
                ['outlet_identify', 'outlet_identify_remarks'],
                ['price_display', 'price_display_remarks'],
                ['pdb_entry', 'pdb_entry_remarks'],
                ['pdb_updated', 'pdb_updated_remarks'],
                ['pdb_match', 'pdb_match_remarks'],
                ['ron_label', 'ron_label_remarks'],
                ['e10_label', 'e10_label_remarks'],
                ['biofuels', 'biofuels_remarks'],
                ['consume_safety', 'consume_safety_remarks'],
                ['cel_warn', 'cel_warn_remarks'],
                ['smoke_sign', 'smoke_sign_remarks'],
                ['switch_eng', 'switch_eng_remarks'],
                ['straddle', 'straddle_remarks'],
                ['post_unleaded', 'post_unleaded_remarks'],
                ['post_biodiesel', 'post_biodiesel_remarks'],
                ['issue_receipt', 'issue_receipt_remarks'],
                ['non_refuse_inspect', 'non_refuse_inspect_remarks'],
                ['fixed_dispense', 'fixed_dispense_remarks'],
                ['no_open_flame', 'no_open_flame_remarks'],
                ['max_length_dispense', 'max_length_dispense_remarks'],
                ['peso_display', 'peso_display_remarks'],
                ['pump_island', 'pump_island_remarks'],
                ['lane_oriented_pump', 'lane_oriented_pump_remarks'],
                ['pump_guard', 'pump_guard_remarks'],
                ['m_ingress', 'm_ingress_remarks'],
                ['m_edge', 'm_edge_remarks'],
                ['office_cashier', 'office_cashier_remarks'],
                ['min_canopy', 'min_canopy_remarks'],
                ['boundary_walls', 'boundary_walls_remarks'],
                ['master_switch', 'master_switch_remarks'],
                ['clean_rest', 'clean_rest_remarks'],
                ['underground_storage', 'underground_storage_remarks'],
                ['m_distance', 'm_distance_remarks'],
                ['vent', 'vent_remarks'],
                ['transfer_dispense', 'transfer_dispense_remarks'],
                ['no_drum', 'no_drum_remarks'],
                ['no_hoard', 'no_hoard_remarks'],
                ['free_tire_press', 'free_tire_press_remarks'],
                ['free_water', 'free_water_remarks'],
                ['basic_mechanical', 'basic_mechanical_remarks'],
                ['first_aid', 'first_aid_remarks'],
                ['design_eval', 'design_eval_remarks'],
                ['electric_eval', 'electric_eval_remarks'],
                ['under_deliver', 'under_deliver_remarks']
            ];

            $result_remarks_summary = $conn->query($sql_remarks_summary);  
            if ($result_remarks_summary === false) {
                die("Query failed: " . $conn->error);
            }
            
            $data_remarks_summary = $result_remarks_summary->fetch_assoc();
            $violation_headerY = 90; 
            $violation_headerX = 16;
            $violation_cell_width = 85;
            $violation_heightcell = 3.9;
            $violation_border = 0;
            $maxLength_violation = 200;    
            
            $pdf->SetXY($violation_headerX, $violation_headerY);
            
            foreach ($boolean_remarks_pairs as $pair) {
                $boolean_column = $pair[0]; 
                $remarks_column = $pair[1];
            
                if (isset($data_remarks_summary[$boolean_column]) && $data_remarks_summary[$boolean_column] == 0) {
                    if (isset($data_remarks_summary[$remarks_column]) && !empty($data_remarks_summary[$remarks_column])) {
                        $remark = $data_remarks_summary[$remarks_column];
                        $remark = str_replace(".", ",", $remark);
                        
                        // Output each remark in a separate cell
                        $pdf->MultiCell($violation_cell_width, $violation_heightcell, $remark, $violation_border, 0);
                        
                        // Move Y position down for next remark
                        $violation_headerY += $violation_heightcell;
                        $pdf->SetXY($violation_headerX, $violation_headerY);
                    }
                }
            }
        
        
            
            //Action Required--------------------------------->
            $text_action = 'Post COC visibly. Maintain calibration logs. Add missing fuel labels (RON/E-10/Biofuels) and safety signs. Issue official receipts. Ensure pumps meet safety standards (6m clearance, <5.5m hoses). Establish cashier booth. Install boundary walls. Fix storage tank vents. Stop fuel hoarding. Provide first aid kit. Priority: Safety and legal compliance first. Resolve within 7 days to avoid penalties.
';
            $text_action2 = 'Several compliance gaps were noted, including missing/expired permits, inconsistent fuel pricing, and absent safety signage. Infrastructure shortcomings include non-compliant pump island dimensions and low canopy heights. Operational risks involve improper fuel transfer and inadequate tank venting. Additional lapses include unclean restrooms.'; 
            $action_headerY =89.5;
            $action_headerX =110;
            $action_cell_width = 90;
            $action_heightcell = 3.9;
            $action_border = 0;
            $maxLength_action = 420;  ////action re
            $maxLength_action2 = 350;
            
        
            $truncated_action_Text1 = substr($text_action, 0,  $maxLength_action);
            $truncated_action_Text2 = substr($text_action2, 0,  $maxLength_action2);

           
             $pdf->SetXY( $action_headerX, $action_headerY );
             $pdf->MultiCell($action_cell_width,$action_heightcell, $truncated_action_Text1, $action_border, 0);
             
             //Observation
             $pdf->SetXY( $action_headerX, $action_headerY + 28 );
             $pdf->MultiCell($action_cell_width,$action_heightcell, $truncated_action_Text2, $action_border, 0);
             
          
             ob_end_clean();
$pdf->Output();


        exit;
    }
}

?>