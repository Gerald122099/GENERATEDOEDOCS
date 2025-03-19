<?php
require('fpdf/fpdf.php');
include 'config.php';
header('Content-Type: text/html; charset=utf-8');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['generate_pdf'])) {
    $itr_form_number = $_POST['itr_form_number_selected'];
    $sql = "SELECT * FROM inspection WHERE itr_form_number='$itr_form_number'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();

        $pdf = new FPDF('P', 'mm', 'Legal'); 
        $pdf->AddPage();

        $pdf->Image('doe.jpg', 12, 16.5, 17);


        $pdf->AddFont('CenturyGothic', '', 'GOTHIC.php');
        $pdf->AddFont('Canterbury', '', 'Canterbury.php');
        $pdf->AddFont('BOOKOSB', '', 'BOOKOSB.php');
        $pdf->AddFont('GOTHICB0', '', 'GOTHICB0.php');
        
        //header
        $pdf->Cell(60, 5, '', 0, 0);
        $pdf->Cell(60, 5, '', 0, 0);
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(65, 5, 'FO-OIMB-A1-001', 0, 1    , 'R');


        $pdf->SetFont('Canterbury', '', 10);
        $pdf->Cell(63, 5, 'Republic of the Philippines', 0, 0 ,'R');
        $pdf->Cell(60, 5, '', 0, 0);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(50, 5, 'DOE-VFO-EIMD-QF-50 Rev. A', 0, 1, 'R');




        $pdf->SetFont('BOOKOSB', '', 10);
        $pdf->Cell(77.5, 5, 'DEPARTMENT OF ENERGY', 0, 0, 'R');
        $pdf->Cell(20, 5, '', 0, 0);
        $pdf->Cell(20, 5, '', 0, 1);
        
        $pdf->SetFont('CenturyGothic', '', 9);
        $pdf->Cell(125, 5, '3rd Floor Escario Building, 731 Escario St., Capitol Site, Cebu City', 0, 0, 'R');
        $pdf->Cell(10, 5, '', 0, 0);
        $pdf->SetFont('GOTHICB0', '', 10);
        $pdf->Cell(50, 5, "ITR FORM No. " . $data['itr_form_number'], 0, 1, 'R');
     
        $pdf->SetFont('CenturyGothic', '', 9);
        $pdf->Cell(74.5, 5, '(032) 253-2150 / (032) 253-7222', 0, 0, 'R');

        $pdf->Ln(7);

        // Title
        $pdf->SetFont('BOOKOSB', '', 10);
        $pdf->Cell(0, 7, 'INSPECTION / INVESTIGATION AND TESTING REPORT FORM (ITRF)', 0, 1, 'C');
        $pdf->SetFont('CenturyGothic', '', 10);
        $pdf->Cell(0, 5, 'RETAIL OUTLET', 0, 1, 'C');
        $pdf->Ln(10);

       // <------------------------------------------------------------------------------------------------------------------------------->
       

        $col1_width = 95;
        $col2_width = 100;

        $pdf->Cell($col1_width, 7, "Business Name: " . $data['business_name'], 0, 0);
        $pdf->Cell(20, 5, '', 0, 0);
        $pdf->Cell($col1_width, 7, "Date/Time of Inspection: " . $data['sa_no_date'], 0, 1);

        

        // Checklist Section (Two Columns)
        $pdf->SetFont('CenturyGothic', '', 10);
        $pdf->Cell(0, 10, "I. MANDATORY AND MINIMUM STANDARDS & REQUIREMENTS (Put ✓ if YES & ✕ if NO)", 0, 1);
        $pdf->SetFont('CenturyGothic', '', 8); // Smaller font size

        
          
        
        $pdf->Output();
        exit;
    }
}
?>