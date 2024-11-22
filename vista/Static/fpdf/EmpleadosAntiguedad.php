<?php
// Asegúrate de que no haya salida antes del PDF
ob_clean();

require('./fpdf.php');
include '../Static/connect/db.php';

class PDF extends FPDF
{
    private $centerPosition;

    public function setCenterPosition($value)
    {
        $this->centerPosition = $value;
    }

    public function getCenterPosition()
    {
        return $this->centerPosition;
    }

    function Header()
    {
        $this->Image('logo.png', $this->GetPageWidth() - 30, 10, 20);
        $this->SetFont('Arial', 'B', 19);
        $this->Cell(0, 15, utf8_decode('Establecimiento de comida'), 0, 1, 'C', 0);
        $this->Ln(3);
        $this->SetTextColor(103);

        $this->SetTextColor(228, 100, 0);
        $this->Cell(0, 10, utf8_decode("Empleado con mayor antigüedad"), 0, 1, 'C', 0);
        $this->Ln(7);

        // Calculate table position
        $tableWidth = 15 + 40 + 40 + 30 + 40 + 40 + 50; // Aumentado a 50 para antigüedad
        $pageWidth = $this->GetPageWidth();
        $this->centerPosition = ($pageWidth - $tableWidth) / 2;
        
        $this->SetX($this->centerPosition);
        $this->SetFillColor(228, 100, 0);
        $this->SetTextColor(255, 255, 255);
        $this->SetDrawColor(163, 163, 163);
        $this->SetFont('Arial', 'B', 10);

        // Table headers
        $this->Cell(15, 10, 'ID', 1, 0, 'C', 1);
        $this->Cell(40, 10, 'Nombre', 1, 0, 'C', 1);
        $this->Cell(40, 10, 'Puesto', 1, 0, 'C', 1);
        $this->Cell(30, 10, 'Salario', 1, 0, 'C', 1);
        $this->Cell(40, 10, utf8_decode('Zona Asignada'), 1, 0, 'C', 1);
        $this->Cell(40, 10, utf8_decode('Fecha Contratación'), 1, 0, 'C', 1);
        $this->Cell(50, 10, utf8_decode('Antigüedad'), 1, 1, 'C', 1);
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C');
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(355, 10, utf8_decode(date('d/m/Y')), 0, 0, 'C');
    }
}

$pdf = new PDF('L');
$pdf->SetMargins(15, 10, 15);
$pdf->AddPage();
$pdf->AliasNbPages();

$pdf->SetFont('Arial', '', 10);
$pdf->SetDrawColor(163, 163, 163);

// Corregir la consulta para incluir los cálculos de años, meses y días
$query = "SELECT e.*, u.nombre, u.apellido, 
         TIMESTAMPDIFF(YEAR, e.fecha_contratacion, CURDATE()) as años,
         TIMESTAMPDIFF(MONTH, e.fecha_contratacion, CURDATE()) % 12 as meses,
         TIMESTAMPDIFF(DAY, 
            DATE_ADD(e.fecha_contratacion, 
                INTERVAL TIMESTAMPDIFF(MONTH, e.fecha_contratacion, CURDATE()) MONTH), 
            CURDATE()) as dias
         FROM empleados e 
         INNER JOIN usuarios u ON e.usuario_id = u.id 
         ORDER BY e.fecha_contratacion ASC LIMIT 1";
$result = mysqli_query($conn, $query);

if ($result) {
    while ($datos_reporte = mysqli_fetch_assoc($result)) {
        $pdf->SetX($pdf->getCenterPosition());
        
        // Asegurarse de que los valores existan antes de usarlos
        $años = isset($datos_reporte['años']) ? $datos_reporte['años'] : 0;
        $meses = isset($datos_reporte['meses']) ? $datos_reporte['meses'] : 0;
        $dias = isset($datos_reporte['dias']) ? $datos_reporte['dias'] : 0;
        
        $antiguedad = sprintf("%d años, %d meses, %d días", $años, $meses, $dias);
        
        $pdf->Cell(15, 10, utf8_decode($datos_reporte['id']), 1, 0, 'C', 0);
        $pdf->Cell(40, 10, utf8_decode($datos_reporte['nombre'] . ' ' . $datos_reporte['apellido']), 1, 0, 'C', 0);
        $pdf->Cell(40, 10, utf8_decode($datos_reporte['puesto']), 1, 0, 'C', 0);
        $pdf->Cell(30, 10, utf8_decode($datos_reporte['salario']), 1, 0, 'C', 0);
        $pdf->Cell(40, 10, utf8_decode($datos_reporte['zona_asignada']), 1, 0, 'C', 0);
        $pdf->Cell(40, 10, utf8_decode($datos_reporte['fecha_contratacion']), 1, 0, 'C', 0);
        $pdf->Cell(50, 10, utf8_decode($antiguedad), 1, 1, 'C', 0);
    }
} else {
    $pdf->Cell(0, 10, 'Error en la consulta: ' . mysqli_error($conn), 1, 1, 'C', 0);
}

// Asegurarse de que no haya salida después de este punto
$pdf->Output('I', 'EmpleadoAntiguedad.pdf');
?>