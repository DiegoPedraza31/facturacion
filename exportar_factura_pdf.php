<?php
session_start();
require('fpdf/fpdf.php');

$conexion = new mysqli("localhost", "root", "", "facturacion_db");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Obtener el ID de la factura de la URL o de la sesión
$id_factura = isset($_GET['id']) ? intval($_GET['id']) : (isset($_SESSION['id_factura']) ? intval($_SESSION['id_factura']) : 0);

// Verificar que el ID es válido
if ($id_factura == 0) {
    die("Error: No se especificó una factura válida.");
}

// Depuración (Descomentar si necesitas verificar el ID)
// var_dump($id_factura); die(); 

// Obtener la factura de la base de datos
$sql = "SELECT * FROM facturacion WHERE id = $id_factura";
$resultado = $conexion->query($sql);
$fila = $resultado->fetch_assoc();

if (!$fila) {
    die("Error: Factura no encontrada.");
}

// Crear el PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(190, 10, 'Factura #'.$fila['id'], 1, 1, 'C');
$pdf->Ln(10);

$pdf->SetFont('Arial', '', 12);
$pdf->Cell(50, 10, 'Nombre:', 1);
$pdf->Cell(140, 10, $fila['nombre']." ".$fila['apellido'], 1);
$pdf->Ln();

$pdf->Cell(50, 10, 'Cédula:', 1);
$pdf->Cell(140, 10, $fila['cedula'], 1);
$pdf->Ln();

$pdf->Cell(50, 10, 'Fecha:', 1);
$pdf->Cell(140, 10, $fila['fecha'], 1);
$pdf->Ln();

$pdf->Cell(50, 10, 'Teléfono:', 1);
$pdf->Cell(140, 10, $fila['telefono'], 1);
$pdf->Ln();

$pdf->Cell(50, 10, 'Empresa:', 1);
$pdf->Cell(140, 10, $fila['empresa'], 1);
$pdf->Ln();

$pdf->Cell(50, 10, 'Dirección:', 1);
$pdf->Cell(140, 10, $fila['direccion'], 1);
$pdf->Ln();

$pdf->Cell(50, 10, 'Valor:', 1);
$pdf->Cell(140, 10, '$'.number_format($fila['valor'], 2), 1);
$pdf->Ln();

$pdf->Cell(50, 10, 'Número de cuotas:', 1);
$pdf->Cell(140, 10, $fila['num_cuotas'], 1);
$pdf->Ln();

$pdf->Cell(50, 10, 'Observaciones:', 1);
$pdf->MultiCell(140, 10, $fila['observaciones'], 1);

$pdf->Output();
$conexion->close();
?>