<?php
ob_start(); // Start output buffering
require('fpdf/fpdf.php');
include 'config.php';
header('Content-Type: text/html; charset=utf-8');
header('Content-Type: application/pdf');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $itr_form_num = $_GET['id'];
    $sql_business_info = "SELECT * FROM businessinfo WHERE itr_form_num ='$itr_form_num'";
    $result_business_info = $conn->query( $sql_business_info);

    if ( $result_business_info->num_rows > 0) {
            $data_business_info =  $result_business_info->fetch_assoc();

            $pdf = new FPDF('P', 'mm', 'Legal'); 
            $pdf->AddPage();

            $pdf->AddFont('arialnarrow', '', 'arialnarrow.php');
            $pdf->Image('..\itr\assets\img\doe.jpg', 12, 16.5, 17);
            $pdf->Image('..\itr\assets\img\itrf_p1_bg.jpg', 0, 0, 216, 356); 
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
           $pdf->Image('..\itr\assets\img\itrf_p2_bg.jpg', 0, 0, 216, 356); 
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
                $pdf->Cell($headercol_sampling + 9, $secondpageheight, $data_sampling['pump'], $secondpageborder, 0,'C');
            
                $i++; }
           
               
               
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
          
            $booleanFields_Compliance = [ 'coc_cert','coc_posted','valid_permit_LGU','valid_permit_BFP','valid_permit_DENR','appropriate_test','week_calib','outlet_identify','pdb_entry','pdb_updated','pdb_match','ron_label','e10_label','biofuels','consume_safety','cel_warn','smoke_sign','switch_eng',
                                          'straddle','post_unleaded','post_biodiesel','issue_receipt','non_refuse_inspect','non_refuse_sign','fixed_dispense','no_open_flame','max_length_dispense','peso_display','pump_island','lane_oriented_pump','pump_guard','m_ingress','m_edge','office_cashier','min_canopy','boundary_walls','master_switch',
                                           'clean_rest','underground_storage','m_distance','vent','transfer_dispense','no_drum','no_hoard','under_deliver', ];
            $allFieldsValid = true; // Assume true initially
            
            foreach ($booleanFields_Compliance as $field) {
                if (isset($data_checklist[$field]) && $data_checklist[$field] == 0) {
                    $allFieldsValid = false; // Set to false if any field is invalid
                    break;
                }
            }
            
            // With violation adjustment
            $x_add = ($allFieldsValid) ? 13 : -32;
            $pdf->SetXY($headerX2ndPage - 109 - $x_add, $headerY2ndPage + 71 + $headerXpadding);
            $pdf->Cell($headercol1 - 105, $secondpageheight, $PRODUCT_YES , $secondpageborder, 0);
            
            



            
            //Violations Summary--------------------------------->
            $pdf->SetFont('arialnarrow', '', 9);
            $sql_remarks_summary = "SELECT * FROM standardcompliancechecklist WHERE itr_form_num = '$itr_form_num'";

          // Violation pairs with labels and remarks fields


// Query the database
$sql_remarks_summary = "SELECT * FROM standardcompliancechecklist WHERE itr_form_num = '$itr_form_num'";
$result_remarks_summary = $conn->query($sql_remarks_summary);  

if ($result_remarks_summary === false) {
    die("Query failed: " . $conn->error);
}

$data_remarks_summary = $result_remarks_summary->fetch_assoc();

// Maintain the exact same cell formatting as original code
$violation_headerY = 90; 
$violation_headerX = 16;
$violation_cell_width = 85;
$violation_heightcell = 3.9;
$violation_border = 0;
$maxLength_violation = 200;

$pdf->SetXY($violation_headerX, $violation_headerY);

foreach ($violation_pairs as $pair) {
    $boolean_column = $pair[0]; 
    $label = $pair[1];
    $remarks_column = $pair[2];

    if (isset($data_remarks_summary[$boolean_column]) && $data_remarks_summary[$boolean_column] == 0) {
        $violation_text = $label;
        
        if (isset($data_remarks_summary[$remarks_column]) && !empty($data_remarks_summary[$remarks_column])) {
            $remark = $data_remarks_summary[$remarks_column];
            $remark = str_replace(".", ",", $remark);
            $violation_text .= ": " . $remark;
        }
        
        // Maintain original MultiCell parameters
        $pdf->MultiCell($violation_cell_width, $violation_heightcell, $violation_text, $violation_border, 0);
        
        // Maintain original position adjustment
        $violation_headerY += $violation_heightcell;
        $pdf->SetXY($violation_headerX, $violation_headerY);
    }
}
        
            
            //remarks & Action Required--------------------------------->
            $user_remarks = "SELECT * FROM summaryremarks WHERE itr_form_num ='$itr_form_num'";
            $result_remarks = $conn->query($user_remarks);

            if ($result_remarks === false) {
                die("Query failed: " . $conn->error);
            }
            $user_remarks = $result_remarks->fetch_assoc();


            $user_gen_remarks = $user_remarks['user_gen_remarks'];
            $action_required_text = $user_remarks['action_required'];
            
            $action_headerY =89.5;
            $action_headerX =110;
            $action_cell_width = 90;
            $action_heightcell = 3.9;
            $action_border = 0;
            $maxLength_action = 420;  ////action re
            $maxLength_action2 = 350;
            
        
            $truncated_action_Text1 = substr($user_gen_remarks, 0,  $maxLength_action);
            $truncated_action_Text2 = substr($action_required_text, 0,  $maxLength_action2);

           
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