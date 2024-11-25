<?php

require('./fpdf.php');
include '../Static/connect/db.php';

// Eliminar estas líneas ya que $pdf aún no está definido
// $shift = 5;
// $pdf->SetX( ($pdf->GetPageWidth() - 15 - 18 - 25 - 20 - 40 - 40) / 2 + $pdf->getLeftMarginPublic() );

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

    public function getLeftMarginPublic()
    {
        return $this->lMargin;
    }

    // Cabecera de página
    function Header()
    {
        //include '../../recursos/Recurso_conexion_bd.php';//llamamos a la conexion BD

        //$consulta_info = $conexion->query(" select *from hotel ");//traemos datos de la empresa desde BD
        //$dato_info = $consulta_info->fetch_object();
        $this->Image('logo.png', $this->GetPageWidth() - 30, 10, 20); // Adjust X position based on page width
        $this->SetFont('Arial', 'B', 19); //tipo fuente, negrita(B-I-U-BIU), tamañoTexto
        $this->Cell(0, 15, utf8_decode('Establecimiento de comida'), 0, 1, 'C', 0); // Centered title
        $this->Ln(3); // Salto de línea
        $this->SetTextColor(103); //color

        

        /* UBICACION */
        //$this->Cell(110);  // mover a la derecha
        //$this->SetFont('Arial', 'B', 10);
        //$this->Cell(96, 10, utf8_decode("Ubicación : "), 0, 0, '', 0);
        //$this->Ln(5);

        /* TELEFONO */
        //$this->Cell(110);  // mover a la derecha
        //$this->SetFont('Arial', 'B', 10);
        //$this->Cell(59, 10, utf8_decode("Teléfono : "), 0, 0, '', 0);
        //$this->Ln(5);

        /* COREEO */
        //$this->Cell(110);  // mover a la derecha
        //$this->SetFont('Arial', 'B', 10);
        //$this->Cell(85, 10, utf8_decode("Correo : "), 0, 0, '', 0);
        //$this->Ln(5);

        /* TELEFONO */
        //$this->Cell(110);  // mover a la derecha
        //$this->SetFont('Arial', 'B', 10);
        //$this->Cell(85, 10, utf8_decode("Sucursal : "), 0, 0, '', 0);
        //$this->Ln(10);

        /* TITULO DE LA TABLA */
        //color
        $this->SetTextColor(228, 100, 0);
        $this->Cell(0, 10, utf8_decode("Consulta de mesas disponibles "), 0, 1, 'C', 0); // Centered table title
        $this->Ln(7); 

        /* CAMPOS DE LA TABLA */
        // Calculate total table width and center position only once
        $tableWidth = 15 + 18 + 25 + 20 + 40 + 40;
        $pageWidth = $this->GetPageWidth();
        $leftMargin = $this->lMargin;
        $this->centerPosition = ($pageWidth - $tableWidth) / 2;
        
        // Use the calculated center position
        $this->SetX($this->centerPosition);

        // Set table header styles
        $this->SetFillColor(228, 100, 0); // Fondo
        $this->SetTextColor(255, 255, 255); // Texto
        $this->SetDrawColor(163, 163, 163); // Borde
        $this->SetFont('Arial', 'B', 10); // Fuente

        // Define table headers with updated width for "Última Actualización"
        $this->Cell(15, 10, utf8_decode('Id'), 1, 0, 'C', 1);
        $this->Cell(18, 10, utf8_decode('Número'), 1, 0, 'C', 1);
        $this->Cell(25, 10, utf8_decode('Capacidad'), 1, 0, 'C', 1);
        $this->Cell(20, 10, utf8_decode('Estado'), 1, 0, 'C', 1);
        $this->Cell(40, 10, utf8_decode('Zona'), 1, 0, 'C', 1);
        $this->Cell(40, 10, utf8_decode('Última Actualización'), 1, 1, 'C', 1); // Increased to 40
    }

    // Pie de página
    function Footer()
    {
        $this->SetY(-15); // Posición: a 1,5 cm del final
        $this->SetFont('Arial', 'I', 8); //tipo fuente, negrita(B-I-U-BIU), tamañoTexto
        $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C'); //pie de pagina(numero de pagina)

        $this->SetY(-15); // Posición: a 1,5 cm del final
        $this->SetFont('Arial', 'I', 8); //tipo fuente, cursiva, tamañoTexto
        $hoy = date('d/m/Y');
        $this->Cell(355, 10, utf8_decode($hoy), 0, 0, 'C'); // pie de pagina(fecha de pagina)
    }
}

//include '../../recursos/Recurso_conexion_bd.php';
//require '../../funciones/CortarCadena.php';
/* CONSULTA INFORMACION DEL HOSPEDAJE */
//$consulta_info = $conexion->query(" select *from hotel ");
//$dato_info = $consulta_info->fetch_object();

$pdf = new PDF('L'); // Set orientation to Landscape when creating the PDF
$pdf->SetMargins(15, 10, 15); // Set wider margins
$pdf->AddPage();
$pdf->AliasNbPages();

// ...existing code...

$i = 0;
$pdf->SetFont('Arial', '', 10); // Reduced font size
$pdf->SetDrawColor(163, 163, 163);

/* CONSULTA MESAS DISPONIBLES */
$query = "SELECT * FROM mesas WHERE id NOT IN (SELECT mesa_id FROM reservaciones_mesas) AND estado = 'disponible'";
$result = mysqli_query($conn, $query);

if ($result) {
    while ($datos_reporte = mysqli_fetch_assoc($result)) {
        $i++;

        // Use the same center position as headers
        $pdf->SetX($pdf->getCenterPosition());

        // Center each cell's content and adjust "Última Actualización" width
        $pdf->Cell(15, 10, utf8_decode($i), 1, 0, 'C', 0);
        $pdf->Cell(18, 10, utf8_decode($datos_reporte['numero']), 1, 0, 'C', 0);
        $pdf->Cell(25, 10, utf8_decode($datos_reporte['capacidad']), 1, 0, 'C', 0);
        $pdf->Cell(20, 10, utf8_decode($datos_reporte['estado']), 1, 0, 'C', 0);
        $pdf->Cell(40, 10, utf8_decode($datos_reporte['zona_id']), 1, 0, 'C', 0);
        $pdf->Cell(40, 10, utf8_decode($datos_reporte['ultima_actualizacion']), 1, 1, 'C', 0); // Increased to 40
    }
} else {
    // Handle query error
    $pdf->Cell(0, 10, 'Error en la consulta: ' . mysqli_error($conn), 1, 1, 'C', 0);
}

$pdf->Output('Prueba.pdf', 'I');

?>