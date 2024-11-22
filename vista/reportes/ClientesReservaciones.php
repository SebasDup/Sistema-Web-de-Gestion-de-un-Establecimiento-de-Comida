<?php
ob_clean();
require_once(__DIR__ . '/fpdf.php');
require_once(__DIR__ . '/../../modelo/Conexion.php');

// Validate date parameters
$fecha_inicio = isset($_GET['fecha_inicio']) && !empty($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : null;
$fecha_fin = isset($_GET['fecha_fin']) && !empty($_GET['fecha_fin']) ? $_GET['fecha_fin'] : null;

// Validate dates are present and in correct format
if (!$fecha_inicio || !$fecha_fin || 
    !preg_match("/^\d{4}-\d{2}-\d{2}$/", $fecha_inicio) || 
    !preg_match("/^\d{4}-\d{2}-\d{2}$/", $fecha_fin)) {
    die("<script>
        alert('Por favor, seleccione un rango de fechas válido en formato YYYY-MM-DD');
        window.history.back();
    </script>");
}

class PDF extends FPDF
{
    private $centerPosition;
    private $fecha_inicio;
    private $fecha_fin;

    public function setCenterPosition($value)
    {
        $this->centerPosition = $value;
    }

    public function setDateRange($inicio, $fin)
    {
        $this->fecha_inicio = $inicio;
        $this->fecha_fin = $fin;
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

        $this->SetTextColor(228, 100, 0);
        $this->Cell(0, 10, utf8_decode("Reporte de Reservaciones"), 0, 1, 'C', 0);
        
        // Show date range if set
        if ($this->fecha_inicio && $this->fecha_fin) {
            $this->SetFont('Arial', '', 12);
            $this->Cell(0, 10, utf8_decode("Período: " . date('d/m/Y', strtotime($this->fecha_inicio)) . 
                " - " . date('d/m/Y', strtotime($this->fecha_fin))), 0, 1, 'C', 0);
        }
        $this->Ln(7);

        // Headers
        $this->SetFillColor(228, 100, 0);
        $this->SetTextColor(255, 255, 255);
        $this->SetDrawColor(163, 163, 163);
        $this->SetFont('Arial', 'B', 8);

        $this->Cell(15, 10, 'Mesa ID', 1, 0, 'C', 1);
        $this->Cell(20, 10, utf8_decode('Núm. Mesa'), 1, 0, 'C', 1);
        $this->Cell(15, 10, 'Zona ID', 1, 0, 'C', 1);
        $this->Cell(20, 10, 'Zona', 1, 0, 'C', 1);
        $this->Cell(20, 10, 'Cliente ID', 1, 0, 'C', 1);
        $this->Cell(60, 10, 'Nombre Cliente', 1, 0, 'C', 1);
        $this->Cell(35, 10, 'Fecha', 1, 0, 'C', 1);
        $this->Cell(20, 10, 'Personas', 1, 0, 'C', 1);
        $this->Cell(25, 10, 'Estado', 1, 0, 'C', 1);
        $this->Cell(45, 10, 'Comentarios', 1, 1, 'C', 1);
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C');
        $this->SetY(-15);
        $this->SetX(-60);
        $this->Cell(50, 10, utf8_decode(date('d/m/Y H:i:s')), 0, 0, 'R');
    }
}

// Create PDF
$pdf = new PDF('L', 'mm', 'A4');
$pdf->setDateRange($fecha_inicio, $fecha_fin);
$pdf->SetMargins(15, 10, 15);
$pdf->AddPage();
$pdf->AliasNbPages();
$pdf->SetFont('Arial', '', 8);

// Query for reservations with all required information
$query = "SELECT 
    m.id as mesa_id,
    m.numero as numero_mesa,
    z.id as zona_id,
    z.nombre as nombre_zona,
    u.id as cliente_id,
    CONCAT(u.nombre, ' ', u.apellidoP, ' ', u.apellidoM) as nombre_completo,
    r.fecha,
    r.personas,
    r.estado,
    r.comentarios
FROM reservaciones r
INNER JOIN usuarios u ON r.cliente_id = u.id
INNER JOIN reservaciones_mesas rm ON r.id = rm.reservacion_id
INNER JOIN mesas m ON rm.mesa_id = m.id
INNER JOIN zonas z ON m.zona_id = z.id
WHERE r.fecha BETWEEN ? AND ?
ORDER BY r.fecha DESC";

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "ss", $fecha_inicio, $fecha_fin);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($result) {
    $pdf->SetTextColor(0);
    
    while ($row = mysqli_fetch_assoc($result)) {
        $pdf->Cell(15, 10, utf8_decode($row['mesa_id']), 1, 0, 'C');
        $pdf->Cell(20, 10, utf8_decode($row['numero_mesa']), 1, 0, 'C');
        $pdf->Cell(15, 10, utf8_decode($row['zona_id']), 1, 0, 'C');
        $pdf->Cell(20, 10, utf8_decode($row['nombre_zona']), 1, 0, 'C');
        $pdf->Cell(20, 10, utf8_decode($row['cliente_id']), 1, 0, 'C');
        $pdf->Cell(60, 10, utf8_decode($row['nombre_completo']), 1, 0, 'C');
        $pdf->Cell(35, 10, date('d/m/Y H:i', strtotime($row['fecha'])), 1, 0, 'C');
        $pdf->Cell(20, 10, utf8_decode($row['personas']), 1, 0, 'C');
        $pdf->Cell(25, 10, utf8_decode($row['estado']), 1, 0, 'C');
        $pdf->Cell(45, 10, utf8_decode($row['comentarios']), 1, 1, 'C');
    }
} else {
    $pdf->Cell(0, 10, 'Error en la consulta: ' . mysqli_error($conn), 1, 1, 'C');
}

$pdf->Output('I', 'ReservacionesClientes.pdf');
?>