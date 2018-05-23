<?php
// Clase Autoprint
class PDF_AutoPrint extends PDF_JavaScript
{
    function AutoPrint($printer='')
    {
        // Open the print dialog
        if($printer)
        {
            $printer = str_replace('\\', '\\\\', $printer);
            $script = "var pp = getPrintParams();";
            $script .= "pp.interactive = pp.constants.interactionLevel.full;";
            $script .= "pp.printerName = '$printer'";
            $script .= "print(pp);";
        } else {
            $script = 'print(true);';
        }
        $this->IncludeJS($script);
    }
}

// Consulta de los datos del certificado
$certificado = $this->basculas_model->obtener("certificado_pesaje", $this->uri->segment(4));

// Creación del objeto de la clase heredada
$pdf = new PDF_AutoPrint("L", "mm", array(310.9606368, 396));

// Configuraciones
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetTitle(utf8_decode("Certificado de pesaje"));
$pdf->SetAuthor('John Arley Cano - johnarleycano@hotmail.com');
$pdf->SetCreator('John Arley Cano - johnarleycano@hotmail.com');

$pdf->SetFont('Helvetica', 'B', 30);
$pdf->Cell(370,60, $pdf->Image("./img/logo.png",157,10, null, 50), 0, 1, 'C');
$pdf->Ln(5);
$pdf->Cell(370, 11, utf8_decode("ESTACIÓN DE PESAJE MANANTIALES"),0,1,'C', 0);
$pdf->Cell(370, 11, utf8_decode("KM 2+300 | AUTOPISTA MEDELLÍN - BOGOTÁ"),0,1,'C', 0);
$pdf->Ln(20);

$tamanio_Celda = 370 / 3;

$pdf->setX(50);
$pdf->SetFont('Helvetica', 'B', 30);
$pdf->Cell($tamanio_Celda, 11, utf8_decode("FECHA Y HORA"),0,0,'C', 0);
$pdf->setX(($tamanio_Celda) + 90);
$pdf->Cell($tamanio_Celda, 11, utf8_decode("PLACA"),0,1,'C', 0);

$pdf->setX(50);
$pdf->SetFont('Helvetica', '', 27);
$pdf->Cell($tamanio_Celda, 20, utf8_decode($this->configuracion_model->obtener("formato_fecha", $certificado->Fecha)." (".date("H:i a", strtotime($certificado->Fecha)).")"),1,0,'C', 0);
$pdf->setX(($tamanio_Celda) + 90);
$pdf->Cell($tamanio_Celda, 20, utf8_decode($certificado->Placa),1,1,'C', 0);
$pdf->Ln(20);

$pdf->setX(50);
$pdf->SetFont('Helvetica', 'B', 30);
$pdf->Cell($tamanio_Celda, 11, utf8_decode("TIPO DE VEHÍCULO"),0,0,'C', 0);
$pdf->setX(($tamanio_Celda) + 90);
$pdf->Cell($tamanio_Celda, 11, utf8_decode("PESO PERMITIDO"),0,1,'C', 0);

$pdf->setX(50);
$pdf->SetFont('Helvetica', '', 27);
$pdf->Cell($tamanio_Celda, 20, utf8_decode($certificado->Categoria),1,0,'C', 0);
$pdf->setX(($tamanio_Celda) + 90);
$pdf->Cell($tamanio_Celda, 20, utf8_decode(number_format($certificado->Peso_Maximo, 0, '', '.')." Kg "),1,1,'C', 0);
$pdf->Ln(10);

$pdf->setX(50);
$pdf->SetFont('Helvetica', 'B', 45);
$pdf->Cell(287, 25, utf8_decode("CUMPLE"),1,1,'C', 0);
$pdf->Ln(15);

$pdf->setX(50);
$pdf->SetFont('Helvetica', '', 13);
$pdf->Cell(265, 25, utf8_decode("* Según Resolución 4100 del 28 de diciembre de 2004"),0,1,'L', 0);

$pdf->Image("./img/logo_supertansporte.png",350,225, null, 50);

$pdf->AutoPrint();
$pdf->Output("I", utf8_decode("Certificado de pesaje.pdf"));
?>