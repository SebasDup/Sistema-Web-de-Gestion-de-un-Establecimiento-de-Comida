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

        $this->SetTextColor(228, 100, 0);
        $this->Cell(0, 10, utf8_decode("Consulta de Reservaciones por Estado"), 0, 1, 'C', 0);
        $this->Ln(7);

        $this->SetFillColor(228, 100, 0);
        $this->SetTextColor(255, 255, 255);
        $this->SetDrawColor(163, 163, 163);
        $this->SetFont('Arial', 'B', 11);

        $this->Cell(20, 10, 'ID', 1, 0, 'C', 1);
        $this->Cell(60, 10, 'Cliente', 1, 0, 'C', 1);
        $this->Cell(40, 10, 'Fecha', 1, 0, 'C', 1);
        $this->Cell(30, 10, 'Personas', 1, 0, 'C', 1);
        $this->Cell(40, 10, 'Estado', 1, 1, 'C', 1);
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

$estado = isset($_GET['estado']) ? $_GET['estado'] : 'pendiente';

$pdf = new PDF();
$pdf->SetMargins(15, 10, 15);
$pdf->AddPage();
$pdf->AliasNbPages();
$pdf->SetFont('Arial', '', 10);

$query = "SELECT r.id, CONCAT(u.nombre, ' ', u.apellidoP, ' ', u.apellidoM) as cliente, 
          DATE_FORMAT(r.fecha, '%d/%m/%Y %H:%i') as fecha, 
          r.personas, r.estado
          FROM reservaciones r
          LEFT JOIN usuarios u ON r.cliente_id = u.id
          WHERE r.estado = '$estado'
          ORDER BY r.fecha DESC";

$result = mysqli_query($conn, $query);

if ($result) {
    $pdf->SetTextColor(0);
    while ($row = mysqli_fetch_assoc($result)) {
        $pdf->Cell(20, 10, utf8_decode($row['id']), 1, 0, 'C');
        $pdf->Cell(60, 10, utf8_decode($row['cliente']), 1, 0, 'L');
        $pdf->Cell(40, 10, utf8_decode($row['fecha']), 1, 0, 'C');
        $pdf->Cell(30, 10, utf8_decode($row['personas']), 1, 0, 'C');
        $pdf->Cell(40, 10, utf8_decode($row['estado']), 1, 1, 'C');
    }
} else {
    $pdf->Cell(0, 10, 'Error en la consulta: ' . mysqli_error($conn), 1, 1, 'C');
}

$pdf->Output('I', 'ConsultaReservacionesEstado.pdf');
?>