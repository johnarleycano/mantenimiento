<?php
// // Clase PDF
// class PDF extends FPDF
// {
// 	// Cabecera de página
// 	function Header()
// 	{

// 	} // Cabecera

// 	// Pie de página
// 	function Footer()
// 	{

// 	} // Footer
// } // Clase PDF

class PDF_AutoPrint extends PDF_JavaScript
{
    function AutoPrint($printer='')
    {
        // // Open the print dialog
        // if($printer)
        // {
        //     $printer = str_replace('\\', '\\\\', $printer);
        //     $script = "var pp = getPrintParams();";
        //     $script .= "pp.interactive = pp.constants.interactionLevel.full;";
        //     $script .= "pp.printerName = '$printer'";
        //     $script .= "print(pp);";
        // }
        // else
        //     $script = 'print(true);';
        // $this->IncludeJS($script);
    }
}

// Creación del objeto de la clase heredada
$pdf = new PDF_AutoPrint("L", "mm", array(310.9606368, 396));

$pdf->AddPage();

$pdf->SetFont('Arial', '', 20);

$pdf->Text(90, 50, 'Print me!');

$pdf->AutoPrint();
$pdf->Output("I", utf8_decode("titulo.pdf"));
?>