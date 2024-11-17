
<?php
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
        $this->Cell(0, 10, utf8_decode("Mesas Asignadas a Empleados"), 0, 1, 'C', 0);
        $this->Ln(7);

        // Calculate table position
        $tableWidth = 15 + 40 + 20 + 40 + 40;
        $pageWidth = $this->GetPageWidth();
        $this->centerPosition = ($pageWidth - $tableWidth) / 2;
        
        $this->SetX($this->centerPosition);
        $this->SetFillColor(228, 100, 0);
        $this->SetTextColor(255, 255, 255);
        $this->SetDrawColor(163, 163, 163);
        $this->SetFont('Arial', 'B', 10);

        // Headers
        $this->Cell(15, 10, 'ID', 1, 0, 'C', 1);
        $this->Cell(40, 10, 'Empleado', 1, 0, 'C', 1);
        $this->Cell(20, 10, utf8_decode('Mesa #'), 1, 0, 'C', 1);
        $this->Cell(40, 10, 'Zona', 1, 0, 'C', 1);
        $this->Cell(40, 10, 'Estado', 1, 1, 'C', 1);
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

// Query para obtener mesas asignadas con información del empleado
$query = "SELECT m.id, m.numero, m.estado, z.nombre as zona_nombre, 
          CONCAT(u.nombre, ' ', u.apellido) as nombre_empleado 
          FROM mesas m 
          INNER JOIN zonas z ON m.zona_id = z.id 
          LEFT JOIN empleados e ON e.zona_asignada = z.nombre 
          LEFT JOIN usuarios u ON e.usuario_id = u.id 
          WHERE e.id IS NOT NULL 
          ORDER BY m.zona_id, m.numero";

$result = mysqli_query($conn, $query);

if ($result) {
    while ($datos_reporte = mysqli_fetch_assoc($result)) {
        $pdf->SetX($pdf->getCenterPosition());
        $pdf->Cell(15, 10, utf8_decode($datos_reporte['id']), 1, 0, 'C', 0);
        $pdf->Cell(40, 10, utf8_decode($datos_reporte['nombre_empleado']), 1, 0, 'C', 0);
        $pdf->Cell(20, 10, utf8_decode($datos_reporte['numero']), 1, 0, 'C', 0);
        $pdf->Cell(40, 10, utf8_decode($datos_reporte['zona_nombre']), 1, 0, 'C', 0);
        $pdf->Cell(40, 10, utf8_decode($datos_reporte['estado']), 1, 1, 'C', 0);
    }
} else {
    $pdf->Cell(0, 10, 'Error en la consulta: ' . mysqli_error($conn), 1, 1, 'C', 0);
}

$pdf->Output('I', 'MesasAsignadas.pdf');
?>