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
        $this->Cell(0, 10, utf8_decode("Historial de Servicio por Mesa"), 0, 1, 'C', 0);
        $this->Ln(7);

        $this->SetFillColor(228, 100, 0);
        $this->SetTextColor(255, 255, 255);
        $this->SetDrawColor(163, 163, 163);
        $this->SetFont('Arial', 'B', 11);

        $this->Cell(50, 10, 'Empleado', 1, 0, 'C', 1);
        $this->Cell(30, 10, 'Zona', 1, 0, 'C', 1);
        $this->Cell(20, 10, 'Mesa', 1, 0, 'C', 1);
        $this->Cell(40, 10, 'Fecha Cambio', 1, 0, 'C', 1);
        $this->Cell(40, 10, 'Clientes Atendidos', 1, 1, 'C', 1);
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

$pdf = new PDF();
$pdf->SetMargins(15, 10, 15);
$pdf->AddPage();
$pdf->AliasNbPages();
$pdf->SetFont('Arial', '', 10);

$query = "
    SELECT 
        CONCAT(u.nombre, ' ', u.apellidoP, ' ', u.apellidoM) as empleado,
        hz.zona_asignada,
        GROUP_CONCAT(DISTINCT m.numero) as mesas,
        hz.fecha_asignacion as fecha_cambio,
        GROUP_CONCAT(DISTINCT 
            CONCAT(uc.nombre, ' ', uc.apellidoP)
            ORDER BY uc.nombre
            SEPARATOR ', '
        ) as clientes_atendidos
    FROM empleados e
    INNER JOIN usuarios u ON e.usuario_id = u.id
    INNER JOIN historial_zonas hz ON e.id = hz.empleado_id
    LEFT JOIN mesas m ON m.zona_id = (SELECT id FROM zonas WHERE nombre = hz.zona_asignada)
    LEFT JOIN comandas c ON c.mesa_id = m.id
        AND DATE(c.fecha) >= hz.fecha_asignacion 
        AND DATE(c.fecha) <= COALESCE(
            (SELECT MIN(fecha_asignacion) 
             FROM historial_zonas hz2 
             WHERE hz2.empleado_id = hz.empleado_id 
             AND hz2.fecha_asignacion > hz.fecha_asignacion),
            CURRENT_DATE
        )
    LEFT JOIN usuarios uc ON c.cliente_id = uc.id
    GROUP BY e.id, hz.zona_asignada, hz.fecha_asignacion
    ORDER BY hz.fecha_asignacion DESC, empleado";

$result = mysqli_query($conn, $query);

if ($result) {
    $pdf->SetTextColor(0);
    while ($row = mysqli_fetch_assoc($result)) {
        $pdf->Cell(50, 10, utf8_decode($row['empleado']), 1, 0, 'C');
        $pdf->Cell(30, 10, utf8_decode($row['zona_asignada']), 1, 0, 'C');
        $pdf->Cell(20, 10, utf8_decode($row['mesas'] ?? 'N/A'), 1, 0, 'C');
        $pdf->Cell(40, 10, date('d/m/Y', strtotime($row['fecha_cambio'])), 1, 0, 'C');
        $pdf->Cell(40, 10, utf8_decode($row['clientes_atendidos'] ?? 'N/A'), 1, 1, 'C');
    }
} else {
    $pdf->Cell(0, 10, 'Error en la consulta: ' . mysqli_error($conn), 1, 1, 'C');
}

$pdf->Output('I', 'HistorialServicioMesas.pdf');
?>