<?php
require('fpdf.php');
$font = 'DejaVuSans.ttf'; // Tu archivo TTF
$fontname = 'DejaVu'; // Nombre de la fuente
$pdf = new FPDF();
$pdf->AddFont($fontname, '', $font, true);
$pdf->Output();
?>
