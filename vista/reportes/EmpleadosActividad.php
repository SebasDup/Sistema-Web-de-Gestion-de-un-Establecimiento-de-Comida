
<?php
ob_clean();
require_once(__DIR__ . '/fpdf.php');
require_once(__DIR__ . '/../../modelo/Conexion.php');

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
        $logoPath = __DIR__ . '/images/logo.png';
        if(file_exists($logoPath)) {
            $this->Image($logoPath, $this->GetPageWidth() - 30, 10, 20);
        }
        $this->SetFont('Arial', 'B', 19);
        $this->Cell(0, 15, utf8_decode('Establecimiento de comida'), 0, 1, 'C', 0);
        $this->Ln(3);
        $this->SetTextColor(103);

        $this->SetTextColor(228, 100, 0);
        $this->Cell(0, 10, utf8_decode("Empleados con mayor actividad"), 0, 1, 'C', 0);
        $this->Ln(7);

        // Calculate table position
        $tableWidth = 15 + 80 + 40; // Width for ID, Full Name, and Services
        $pageWidth = $this->GetPageWidth();
        $this->centerPosition = ($pageWidth - $tableWidth) / 2;
        
        $this->SetX($this->centerPosition);
        $this->SetFillColor(228, 100, 0);
        $this->SetTextColor(255, 255, 255);
        $this->SetDrawColor(163, 163, 163);
        $this->SetFont('Arial', 'B', 10);

        // Table headers
        $this->Cell(15, 10, 'ID', 1, 0, 'C', 1);
        $this->Cell(80, 10, 'Nombre Completo', 1, 0, 'C', 1);
        $this->Cell(40, 10, 'Servicios Realizados', 1, 1, 'C', 1);
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

$pdf = new PDF();
$pdf->SetMargins(15, 10, 15);
$pdf->AddPage();
$pdf->AliasNbPages();

$pdf->SetFont('Arial', '', 10);
$pdf->SetDrawColor(163, 163, 163);

$query = "SELECT e.id, u.nombre, u.apellidoP, u.apellidoM, e.servicios_realizados
          FROM empleados e 
          INNER JOIN usuarios u ON e.usuario_id = u.id 
          ORDER BY e.servicios_realizados DESC";

$result = mysqli_query($conn, $query);

if ($result) {
    while ($datos_reporte = mysqli_fetch_assoc($result)) {
        $pdf->SetX($pdf->getCenterPosition());
        
        $nombre_completo = $datos_reporte['nombre'] . ' ' . 
                          $datos_reporte['apellidoP'] . ' ' . 
                          $datos_reporte['apellidoM'];
        
        $pdf->Cell(15, 10, utf8_decode($datos_reporte['id']), 1, 0, 'C', 0);
        $pdf->Cell(80, 10, utf8_decode($nombre_completo), 1, 0, 'C', 0);
        $pdf->Cell(40, 10, utf8_decode($datos_reporte['servicios_realizados']), 1, 1, 'C', 0);
    }
} else {
    $pdf->Cell(0, 10, 'Error en la consulta: ' . mysqli_error($conn), 1, 1, 'C', 0);
}

$pdf->Output('I', 'EmpleadosActividad.pdf');
?>