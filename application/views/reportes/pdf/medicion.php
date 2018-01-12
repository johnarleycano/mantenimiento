<?php
// Se consulta la medición actual y se toman valores globales para la cabecera
$medicion = $this->roceria_model->obtener("medicion", $this->uri->segment(4));
$GLOBALS['medicion'] = $medicion;
$GLOBALS['fecha'] = $this->configuracion_model->obtener("formato_fecha", $medicion->Fecha_Inicial);

// Se consulta la medición anterior
$medicion_anterior = $this->roceria_model->obtener("medicion_anterior", $medicion->Fk_Id_Via);
$GLOBALS['fecha_anterior'] = ($medicion_anterior) ? $this->configuracion_model->obtener("formato_fecha", $medicion_anterior->Fecha_Inicial) : "No existe" ;


// Asigna el id de la medición anterior, si existe tal medición
$id_medicion_anterior = ($medicion_anterior) ? $medicion_anterior->Pk_Id : 0;

// Se consulta los ítems a medir
$tipos_mediciones = $this->configuracion_model->obtener("tipos_mediciones");
$GLOBALS['tipos_mediciones'] = $tipos_mediciones;

// Se consulta los costados de la vía a medir
$costados = $this->configuracion_model->obtener("costados", $medicion->Fk_Id_Via);
$GLOBALS['costados'] = $costados;

// Se consulta los costados de la vía a medir
$calificaciones = $this->configuracion_model->obtener("calificaciones");
$GLOBALS['calificaciones'] = $calificaciones;

// Fuente
$helvetica = 'Helvetica';
$GLOBALS['helvetica'] = $helvetica;

$GLOBALS['ancho_logo'] = 30;
$GLOBALS['ancho_hoja'] = 195;
$GLOBALS['ancho_km'] = 10;

// Clase PDF
class PDF extends FPDF
{
	// Cabecera de página
	function Header()
	{
		// Logo y título principal
	    $this->SetFont($GLOBALS['helvetica'], "B", 13);
	    $this->Cell($GLOBALS['ancho_logo'],16, $this->Image("./img/logo.png",15,10, null, 15), 0, 0, 'L');
	    $this->Cell($GLOBALS['ancho_hoja'] - $GLOBALS['ancho_logo'],10, utf8_decode("MEDICIONES DE ROCERÍA Y CUNETAS"), 0, 1, "C");

	    // Datos de la medición
	    $this->SetX(40);
	    $this->SetFont($GLOBALS['helvetica'], "", 10);
	    $this->Cell(20,6, utf8_decode($GLOBALS['medicion']->Sector),0,0,'C');
	    $this->Cell(5,6, utf8_decode('|'),0,0,'C');
	    $this->Cell(60,6, utf8_decode($GLOBALS['medicion']->Via),0,0,'C');
	    $this->SetFont($GLOBALS['helvetica'], "", 9);
	    $this->Cell(5,6, utf8_decode('|'),0,0,'C');
	    $this->Cell(25,6, utf8_decode($GLOBALS['fecha']),0,0,'C');
	    $this->Cell(5,6, utf8_decode('|'),0,0,'C');
	    $this->Cell(45,6, utf8_decode("Anterior: {$GLOBALS['fecha_anterior']}"),0,1,'C');
	    $this->Ln(5);

	    // Cálculo de tamaño de las celdas para las convenciones de colores
	    $total_celda_colores = 4 * count($GLOBALS['calificaciones']);
	    $total_celda_descripciones = (205 - $total_celda_colores);
	    $valor_celda_descripcion = $total_celda_descripciones / count($GLOBALS['calificaciones']);
	    
	    // Recorrido e impresión de las calificaciones con sus respectivos colores
	    $this->SetFont($GLOBALS['helvetica'], "", 8);
	    foreach ($GLOBALS['calificaciones'] as $calificacion) {
		    $this->SetFillColor($calificacion->Color_R, $calificacion->Color_G, $calificacion->Color_B);
		    $this->Cell(4, 4, null,0,0,'C', 1);

		    $this->Cell(($valor_celda_descripcion), 4, utf8_decode($calificacion->Descripcion),0,0,'L');
	    }
	    $this->Ln(10);

	    // Cabecera de las listas
	    $this->Cell($GLOBALS['ancho_km'], 15, utf8_decode("Km"),1,0,'C', 0);

	    // Ejes X
	    $x_tipo_medicion = $this->getX();
	    $x_costado = $this->getX();

	    $ancho_tipo_medicion = ($GLOBALS['ancho_hoja'] - $GLOBALS['ancho_km']) / count($GLOBALS['tipos_mediciones']);

	    // Se recorren los tipos de mediciones
		foreach ($GLOBALS['tipos_mediciones'] as $tipo_medicion) {
			$this->setXY($x_tipo_medicion, 41);
			$this->Cell($ancho_tipo_medicion, 5, utf8_decode($tipo_medicion->Nombre),1,0,'C', 0);

			$ancho_costado = $ancho_tipo_medicion / count($GLOBALS['costados']);
			$GLOBALS['ancho_costado'] = $ancho_costado;

			// Se recorren los costados
			foreach ($GLOBALS['costados'] as $costado) {
				$this->SetXY($x_costado, 46);
	    		$this->Cell($ancho_costado, 5, utf8_decode($costado->Nombre),1,0,'C', 0);

	    		$ancho_medicion = $ancho_costado / 2;
				$GLOBALS['ancho_medicion'] = $ancho_medicion;
				
				$this->SetXY($x_costado, 51);
	    		$this->Cell($ancho_medicion, 5, utf8_decode("Anterior"),1,0,'C', 0);
	    		$this->Cell($ancho_medicion, 5, utf8_decode("Actual"),1,0,'C', 0);

				// Eje X
				$x_costado += $ancho_costado;
			}

			// Eje X
			$x_tipo_medicion += $ancho_tipo_medicion;
		}

		$this->Ln();
	} // Cabecera

	// Pie de página
	function Footer()
	{
	    // Posición: a 1,5 cm del final
	    $this->SetY(-15);
	    // Arial italic 8
	    $this->SetFont('Helvetica','I',8);
	    // Número de página
	    $this->Cell(0,10,utf8_decode('Sistema de Mediciones | Devimed S.A. - Página '.$this->PageNo().' de {nb}'),0,0,'C');
	} // Footer
} // Clase PDF

// Creación del objeto de la clase heredada
$pdf = new PDF("P", "mm", "Letter");

//Alias para el numero de paginas(numeracion)
$pdf->AliasNbPages();

//Anadir pagina
$pdf->AddPage();

$titulo = "Medición de rocería y cunetas - {$GLOBALS['fecha']}";

// Parámetros adicionales
$pdf->SetTitle(utf8_decode($titulo));
$pdf->SetAuthor('John Arley Cano - johnarleycano@hotmail.com');
$pdf->SetCreator('John Arley Cano - johnarleycano@hotmail.com');

/**
 * Contenido de las mediciones
 */

foreach ($this->roceria_model->obtener("abscisas_medicion",$medicion->Pk_Id) as $abscisa) {
	$pdf->Cell($GLOBALS['ancho_km'], 5, ($abscisa->Valor / 1000),1,0,'R', 0);

	// Se recorren los tipos de mediciones
	foreach ($tipos_mediciones as $tipo_medicion) {
		// Se recorren los costados
		foreach ($costados as $costado) {
			// Datos para consultar detalles de la medición
			$datos = array(
				"Abscisa" => $abscisa->Valor,
				"Fk_Id_Tipo_Medicion" => $tipo_medicion->Pk_Id,
				"Fk_Id_Costado" => $costado->Pk_Id,
			);

			// Se consulta el detalle de la medición anterior
			$datos["Fk_Id_Medicion"] = $id_medicion_anterior;
			$detalle_medicion_anterior = $this->roceria_model->obtener("medicion_detalle", $datos);

			if (isset($detalle_medicion_anterior->Calificacion)) {
				$calificacion_actual = $detalle_medicion_anterior->Calificacion;
		    	$pdf->SetFillColor($detalle_medicion_anterior->Color_R, $detalle_medicion_anterior->Color_G, $detalle_medicion_anterior->Color_B);
		    	$relleno = 1;
		    	$borde = 0;
			} else {
				$calificacion_actual = "";
		    	$relleno = 0;
		    	$borde = 1;
			}

			// Medición anterior
			$pdf->Cell($GLOBALS['ancho_medicion'], 5, null,$borde,0,'C', $relleno);

			// Se consulta el detalle de la medición actual
			$datos["Fk_Id_Medicion"] = $medicion->Pk_Id;
			$detalle_medicion_actual = $this->roceria_model->obtener("medicion_detalle", $datos);

			if (isset($detalle_medicion_actual->Calificacion)) {
				$calificacion_actual = $detalle_medicion_actual->Calificacion;
		    	$pdf->SetFillColor($detalle_medicion_actual->Color_R, $detalle_medicion_actual->Color_G, $detalle_medicion_actual->Color_B);
		    	$relleno = 1;
		    	$borde = 0;
			} else {
				$calificacion_actual = "";
		    	$relleno = 0;
		    	$borde = 1;
			}

			// Medición actual
			$pdf->Cell($GLOBALS['ancho_medicion'], 5, $calificacion_actual,$borde,0,'C', $relleno);




			

			

			
			
			

			// // Se consulta el detalle de la medición anterior
			// $datos["Fk_Id_Medicion"] = $id_medicion_anterior;
			// $detalle_medicion_anterior = $this->roceria_model->obtener("medicion_detalle", $datos);
			
		}
	}

	$pdf->Ln();
}













































$pdf->Output("I", utf8_decode("$titulo.pdf"));
?>