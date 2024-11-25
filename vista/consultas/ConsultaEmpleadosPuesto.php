
<?php
ob_clean();
require_once(__DIR__ . '/../reportes/fpdf.php');
require_once(__DIR__ . '/../../modelo/Conexion.php');

class PDF extends FPDF {
    function Header() {
        $logoPath = __DIR__ . '/../reportes/images/logo.png';
        if(file_exists($logoPath)) {
            $this->Image($logoPath, $this->GetPageWidth() - 30, 10, 20);
        }
        $this->SetFont('Arial', 'B', 19);
        $this->Cell(0, 15, utf8_decode('Establecimiento de comida'), 0, 1, 'C', 0);
        $this->Ln(3);

        $puesto = isset($_GET['puesto']) && !empty($_GET['puesto']) ? $_GET['puesto'] : 'Todos';
        $this->SetTextColor(228, 100, 0);
        $this->Cell(0, 10, utf8_decode("Consulta de Empleados - Puesto: " . $puesto), 0, 1, 'C', 0);
        $this->Ln(7);

        $this->SetFillColor(228, 100, 0);
        $this->SetTextColor(255, 255, 255);
        $this->SetDrawColor(163, 163, 163);
        $this->SetFont('Arial', 'B', 11);

        $this->Cell(20, 10, 'ID', 1, 0, 'C', 1);
        $this->Cell(80, 10, 'Nombre Completo', 1, 0, 'C', 1);
        $this->Cell(50, 10, 'Puesto', 1, 0, 'C', 1);
        $this->Cell(40, 10, 'Zona', 1, 1, 'C', 1);
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

$puesto = isset($_GET['puesto']) ? $_GET['puesto'] : '';

$pdf = new PDF();
$pdf->SetMargins(15, 10, 15);
$pdf->AddPage();
$pdf->AliasNbPages();
$pdf->SetFont('Arial', '', 10);

$whereClause = !empty($puesto) ? "WHERE e.puesto = '$puesto'" : "";

$query = "SELECT 
    e.id,
    CONCAT(u.nombre, ' ', u.apellidoP, ' ', u.apellidoM) as nombre_completo,
    e.puesto,
    e.zona_asignada
FROM empleados e
JOIN usuarios u ON e.usuario_id = u.id
$whereClause
ORDER BY e.puesto, nombre_completo";

$result = mysqli_query($conn, $query);

if ($result) {
    $pdf->SetTextColor(0);
    while ($row = mysqli_fetch_assoc($result)) {
        $pdf->Cell(20, 10, utf8_decode($row['id']), 1, 0, 'C');
        $pdf->Cell(80, 10, utf8_decode($row['nombre_completo']), 1, 0, 'C');
        $pdf->Cell(50, 10, utf8_decode($row['puesto']), 1, 0, 'C');
        $pdf->Cell(40, 10, utf8_decode($row['zona_asignada']), 1, 1, 'C');
    }
} else {
    $pdf->Cell(0, 10, 'Error en la consulta: ' . mysqli_error($conn), 1, 1, 'C');
}

$pdf->Output('I', 'ConsultaEmpleadosPuesto.pdf');
?>