<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    die("Acceso denegado. Inicia sesión primero.");
}

$id_usuario = $_SESSION['id_usuario']; // Obtener ID del usuario autenticado

// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "facturacion_db");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Configurar la codificación UTF-8
$conexion->set_charset("utf8");

// Obtener la factura específica del usuario
$id_factura = $_GET['id']; // ID de la factura que se quiere exportar
$sql = "SELECT id, nombre, cedula, fecha,fecha_entrega, telefono, empresa, direccion, valor, num_cuotas, observaciones 
        FROM facturacion WHERE id = ? AND id_usuario = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("ii", $id_factura, $id_usuario);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {
    $factura = $resultado->fetch_assoc();
} else {
    die("Factura no encontrada.");
}

require 'vendor/autoload.php'; // Cargar autoloader de Composer

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Crear una nueva hoja de cálculo
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Establecer los encabezados de las columnas
$sheet->setCellValue('A1', 'Factura' . $factura['id'])
      ->setCellValue('A2', 'Nombre: ' . $factura['nombre'])
      ->setCellValue('A3', 'Cédula: ' . $factura['cedula'])
      ->setCellValue('A4', 'Fecha: ' . date('d-m-Y', strtotime($factura['fecha'])))
      ->setCellValue('A5', 'Fecha de entrega: ' . date('d-m-Y', strtotime($factura['fecha_entrega'])))
      ->setCellValue('A6', 'Teléfono: ' . $factura['telefono'])
      ->setCellValue('A7', 'Empresa: ' . $factura['empresa'])
      ->setCellValue('A8', 'Dirección: ' . $factura['direccion'])
      ->setCellValue('A9', 'Valor: ' . number_format($factura['valor'], 2, ',', '.'))
      ->setCellValue('A10', 'Número de cuotas: ' . $factura['num_cuotas'])
      ->setCellValue('A11', 'Observaciones: ' . $factura['observaciones']);

// Establecer los estilos para la cabecera
$sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
$sheet->getStyle('A2:A11')->getFont()->setSize(12);
$sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

// Aplicar color de fondo y bordes a la cabecera
$sheet->getStyle('A1')->getFill()
      ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
      ->getStartColor()->setRGB('4CAF50'); // Verde para la cabecera

$sheet->getStyle('A1')->getBorders()->getAllBorders()
      ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN)
      ->getColor()->setRGB('000000'); // Bordes finos de color negro

// Establecer bordes en las celdas con la información
$sheet->getStyle('A2:A11')->getBorders()->getAllBorders()
      ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN)
      ->getColor()->setRGB('000000');

// Crear el escritor para guardar el archivo Excel
$writer = new Xlsx($spreadsheet);

// Establecer el nombre del archivo
$filename = "Factura_" . $factura['id'] . ".xlsx";

// Enviar el archivo Excel al navegador para su descarga
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');

// Guardar el archivo
$writer->save('php://output');

// Cerrar la conexión
$conexion->close();
?>
