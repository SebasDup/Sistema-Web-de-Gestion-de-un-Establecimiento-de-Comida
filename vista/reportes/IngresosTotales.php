<?php
ob_clean();
require_once(__DIR__ . '/fpdf.php');
require_once(__DIR__ . '/../../modelo/Conexion.php');

class PDF extends FPDF {
    function Header() {
        $this->SetFont('Arial', 'B', 19);
        $this->Cell(0, 15, utf8_decode('Establecimiento de comida'), 0, 1, 'C', 0);
        $this->Ln(3);

        $periodo = isset($_GET['periodo']) ? $_GET['periodo'] : 'dia';
        $titulo = "Reporte de Ingresos Totales por " . ucfirst($periodo);

        $this->SetTextColor(228, 100, 0);
        $this->Cell(0, 10, utf8_decode($titulo), 0, 1, 'C', 0);
        $this->Ln(7);

        $this->SetFillColor(228, 100, 0);
        $this->SetTextColor(255, 255, 255);
        $this->SetDrawColor(163, 163, 163);
        $this->SetFont('Arial', 'B', 11);

        $this->Cell(45, 10, 'Periodo', 1, 0, 'C', 1);
        $this->Cell(60, 10, 'Fecha', 1, 0, 'C', 1);
        $this->Cell(35, 10, 'Total Comandas', 1, 0, 'C', 1);
        $this->Cell(40, 10, 'Ingresos Totales', 1, 1, 'C', 1);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C');
        $this->SetY(-15);
        $this->SetX(-60);
        $this->Cell(50, 10, utf8_decode(date('d/m/Y H:i:s')), 0, 0, 'R');
    }
}

$periodo = isset($_GET['periodo']) ? $_GET['periodo'] : 'dia';

mysqli_query($conn, "SET lc_time_names = 'es_ES'");

$query = match($periodo) {
    'dia' => "SELECT DATE(fecha) as periodo, 
             CONCAT(DAYNAME(fecha), ', ', DATE_FORMAT(fecha, '%d de %M %Y')) as descripcion,
             COUNT(*) as total_comandas,
             SUM(total) as ingresos_totales
             FROM comandas 
             GROUP BY DATE(fecha)
             ORDER BY fecha DESC",
    'mes' => "SELECT DATE_FORMAT(fecha, '%Y-%m') as periodo,
             DATE_FORMAT(fecha, '%M %Y') as descripcion,
             COUNT(*) as total_comandas,
             SUM(total) as ingresos_totales
             FROM comandas 
             GROUP BY DATE_FORMAT(fecha, '%Y-%m')
             ORDER BY periodo DESC",
    'anio' => "SELECT YEAR(fecha) as periodo,
             CONCAT('Año ', YEAR(fecha)) as descripcion,
             COUNT(*) as total_comandas,
             SUM(total) as ingresos_totales
             FROM comandas 
             GROUP BY YEAR(fecha)
             ORDER BY periodo DESC",
    default => "SELECT DATE(fecha) as periodo,
             CONCAT(DAYNAME(fecha), ', ', DATE_FORMAT(fecha, '%d de %M %Y')) as descripcion,
             COUNT(*) as total_comandas,
             SUM(total) as ingresos_totales
             FROM comandas 
             GROUP BY DATE(fecha)
             ORDER BY fecha DESC"
};

$pdf = new PDF();
$pdf->SetMargins(15, 10, 15);
$pdf->AddPage();
$pdf->AliasNbPages();
$pdf->SetFont('Arial', '', 10);

$result = mysqli_query($conn, $query);

if ($result) {
    $pdf->SetTextColor(0);
    while ($row = mysqli_fetch_assoc($result)) {
        $pdf->Cell(45, 10, utf8_decode($row['periodo']), 1, 0, 'C');
        $pdf->Cell(60, 10, utf8_decode($row['descripcion']), 1, 0, 'C');
        $pdf->Cell(35, 10, utf8_decode($row['total_comandas']), 1, 0, 'C');
        $pdf->Cell(40, 10, '$' . number_format($row['ingresos_totales'], 2), 1, 1, 'C');
    }
} else {
    $pdf->Cell(0, 10, 'Error en la consulta: ' . mysqli_error($conn), 1, 1, 'C');
}

$pdf->Output('I', 'IngresosTotales.pdf');
?>