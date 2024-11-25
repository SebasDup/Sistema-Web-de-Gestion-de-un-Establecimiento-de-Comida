<?php
ob_clean();
require_once(__DIR__ . '/fpdf.php');
require_once(__DIR__ . '/../../modelo/Conexion.php');

class PDF extends FPDF
{
    function Header()
    {
        $logoPath = __DIR__ . '/images/logo.png';
        if(file_exists($logoPath)) {
            $this->Image($logoPath, $this->GetPageWidth() - 30, 10, 20);
        }
        $this->SetFont('Arial', 'B', 19);
        $this->Cell(0, 15, utf8_decode('Establecimiento de comida'), 0, 1, 'C', 0);
        $this->Ln(3);

        $categoria = isset($_GET['categoria']) && !empty($_GET['categoria']) ? $_GET['categoria'] : 'Todas';
        $this->SetTextColor(228, 100, 0);
        $this->Cell(0, 10, utf8_decode("Reporte de Ingresos - Categoría: " . $categoria), 0, 1, 'C', 0);
        $this->Ln(7);

        $this->SetFillColor(228, 100, 0);
        $this->SetTextColor(255, 255, 255);
        $this->SetDrawColor(163, 163, 163);
        $this->SetFont('Arial', 'B', 11);

        $this->Cell(20, 10, 'ID', 1, 0, 'C', 1);
        $this->Cell(45, 10, 'Platillo', 1, 0, 'C', 1);
        $this->Cell(30, 10, 'Categoria', 1, 0, 'C', 1);
        $this->Cell(55, 10, 'Fecha', 1, 0, 'C', 1);
        $this->Cell(20, 10, 'Cant.', 1, 0, 'C', 1);
        $this->Cell(20, 10, 'Total', 1, 1, 'C', 1);
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

$pdf = new PDF();
$pdf->SetMargins(15, 10, 15);
$pdf->AddPage();
$pdf->AliasNbPages();
$pdf->SetFont('Arial', '', 10);

$categoria = isset($_GET['categoria']) ? $_GET['categoria'] : '';
$periodo = isset($_GET['periodo']) ? $_GET['periodo'] : '';

mysqli_query($conn, "SET lc_time_names = 'es_ES'");

$query = "SELECT 
    m.id,
    m.nombre,
    m.categoria,";

if ($periodo == 'dia') {
    $query .= "CONCAT(DAYNAME(c.fecha), ', ', DATE_FORMAT(c.fecha, '%d de %M %Y')) as descripcion,";
} else if ($periodo == 'mes') {
    $query .= "DATE_FORMAT(c.fecha, '%M %Y') as descripcion,";
} else if ($periodo == 'anio') {
    $query .= "CONCAT('Año ', YEAR(c.fecha)) as descripcion,";
}

$query .= "SUM(ic.cantidad) as cantidad_total,
    SUM(ic.cantidad * ic.precio_unitario) as total_ingresos
FROM menu m
LEFT JOIN items_comanda ic ON m.id = ic.menu_id
LEFT JOIN comandas c ON ic.comanda_id = c.id";

$whereConditions = [];
if (!empty($categoria)) {
    $whereConditions[] = "m.categoria = '$categoria'";
}
if ($periodo) {
    $whereConditions[] = "c.fecha IS NOT NULL";
}

if (!empty($whereConditions)) {
    $query .= " WHERE " . implode(" AND ", $whereConditions);
}

$query .= " GROUP BY m.id, m.nombre, m.categoria";
if ($periodo) {
    $query .= ", fecha";
}
$query .= " ORDER BY ";
if ($periodo) {
    $query .= "fecha DESC, ";
}
$query .= "total_ingresos DESC";

$result = mysqli_query($conn, $query);

if ($result) {
    $pdf->SetTextColor(0);
    $total_general = 0;
    
    while ($row = mysqli_fetch_assoc($result)) {
        $pdf->Cell(20, 10, utf8_decode($row['id']), 1, 0, 'C');
        $pdf->Cell(45, 10, utf8_decode($row['nombre']), 1, 0, 'C');
        $pdf->Cell(30, 10, utf8_decode($row['categoria']), 1, 0, 'C');
        $pdf->Cell(55, 10, isset($row['descripcion']) ? utf8_decode($row['descripcion']) : 'Todo el historial', 1, 0, 'C');
        $pdf->Cell(20, 10, utf8_decode($row['cantidad_total']), 1, 0, 'C');
        $pdf->Cell(20, 10, '$' . number_format($row['total_ingresos'], 2), 1, 1, 'C');
        $total_general += $row['total_ingresos'];
    }
    
    $pdf->SetFillColor(228, 100, 0);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->Cell(170, 10, 'Total General:', 1, 0, 'R', 1);
    $pdf->Cell(20, 10, '$' . number_format($total_general, 2), 1, 1, 'C', 1);
} else {
    $pdf->Cell(0, 10, 'Error en la consulta: ' . mysqli_error($conn), 1, 1, 'C');
}

$pdf->Output('I', 'IngresosPorCategoria.pdf');
?>