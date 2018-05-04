<?php
$prod = $code = "";
if(isset($_GET["prod"])){
    $prod = $_GET["prod"];
}
if(isset($_GET["code"])){
    $code = $_GET["code"];
}
require('FPDF/fpdf.php');

$pdf = new FPDF();
$pdf->SetMargins(0, 5);
$pdf->SetAutoPageBreak(false);
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);
$pdf->Cell(0,5,'  '.$prod);
$pdf->Ln();

$margin = 15;
$w = $h = 30;
for ($i = 0; $i < 9; $i++) {
    for ($j = 0; $j < 7; $j++) {
        $pdf->Image($code,$w*$j,$margin+$h*$i,$w,$h);
    }   
}
$pdf->Output();
?>

