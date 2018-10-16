<?php
// Se consulta la medición actual y se toman valores globales para la cabecera
$medicion = $this->mediciones_model->obtener("medicion", $this->uri->segment(4));

// Se consulta la medición anterior
$medicion_anterior = $this->mediciones_model->obtener("medicion_anterior", array("id_via" => $medicion->Fk_Id_Via, "id_medicion" => $medicion->Pk_Id));

// Se toma el id de la medición actual y anterior (Asigna el id de la medición anterior, si existe tal medición)
$id_medicion = $medicion->Pk_Id;
$id_medicion_anterior = ($medicion_anterior) ? $medicion_anterior->Pk_Id : 0;

// Se consulta los ítems a medir
$tipos_mediciones = $this->configuracion_model->obtener("tipos_mediciones");

// Se consulta los costados de la vía a medir
$costados = $this->configuracion_model->obtener("costados", $medicion->Fk_Id_Via);

// Se consulta los costados de la vía a medir
$calificaciones = $this->configuracion_model->obtener("calificaciones");

// Fuente
$helvetica = 'Helvetica';

// Variables globales
$GLOBALS['ancho_logo'] = 30;
$GLOBALS['ancho_hoja'] = 195;
$GLOBALS['ancho_km'] = 7;
$GLOBALS['ancho_fecha_medicion'] = 18;
$GLOBALS['ancho_fe'] = 5;
$GLOBALS['medicion'] = $medicion;
$GLOBALS['fecha_titulo'] = $this->configuracion_model->obtener("formato_fecha", $medicion->Fecha_Inicial);
$GLOBALS['fecha'] = $this->configuracion_model->obtener("formato_fecha", $medicion->Fecha_Inicial, "corto");
$GLOBALS['fecha_generacion'] = $this->configuracion_model->obtener("formato_fecha", date("Y-m-d"));
$GLOBALS['fecha_anterior'] = ($medicion_anterior) ? $this->configuracion_model->obtener("formato_fecha", $medicion_anterior->Fecha_Inicial, "corto") : "N/A";
$GLOBALS['tipos_mediciones'] = $tipos_mediciones;
$GLOBALS['costados'] = $costados;
$GLOBALS['calificaciones'] = $calificaciones;
$GLOBALS['helvetica'] = $helvetica;

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
	    $this->SetFont($GLOBALS['helvetica'], "", 9);
	    $this->Cell(15,6, utf8_decode($GLOBALS['medicion']->Sector),0,0,'C');
	    $this->Cell(5,6, utf8_decode('|'),0,0,'C');
	    $this->Cell(50,6, utf8_decode($GLOBALS['medicion']->Via),0,0,'C');
	    $this->SetFont($GLOBALS['helvetica'], "", 9);
	    $this->Cell(5,6, utf8_decode('|'),0,0,'C');
	    $this->Cell(30,6, utf8_decode($GLOBALS['fecha_titulo']),0,0,'C');
	    $this->Cell(5,6, utf8_decode('|'),0,0,'C');
	    $this->Cell(50,6, utf8_decode("Usuario: {$GLOBALS['medicion']->Usuario}"),0,1,'C');
	    $this->Ln(5);

        // Cálculo de tamaño de las celdas para las convenciones de colores
	    $total_celda_colores = 4 * count($GLOBALS['calificaciones']);
	    $total_celda_descripciones = (210 - $total_celda_colores);
	    $valor_celda_descripcion = $total_celda_descripciones / count($GLOBALS['calificaciones']);

       	// Recorrido e impresión de las calificaciones con sus respectivos colores
	    $this->SetFont($GLOBALS['helvetica'], "", 8);
	    foreach ($GLOBALS['calificaciones'] as $calificacion) {
		    $this->SetFillColor($calificacion->Color_R, $calificacion->Color_G, $calificacion->Color_B);
		    $this->Cell(4, 4, null,0,0,'C', 1);

		    $this->Cell(($valor_celda_descripcion), 4, utf8_decode($calificacion->Descripcion),0,0,'L');
	    }
	    $this->Ln(10);

	    /**
	     * Cabecera de las listas
	     */
    	$this->Cell($GLOBALS['ancho_km'], 15, utf8_decode("Km"),1,0,'C', 0);
    	$this->Cell($GLOBALS['ancho_fecha_medicion'], 15, utf8_decode("Fecha"),1,0,'C', 0);

	    // Se establecen los ejes X
	    $x_costado = $this->getX();
	    $x_tipo_medicion = $this->getX();
	    $x_fecha = $this->getX();

	    // 1. Se calcula el ancho
	    $ancho_costado = ($GLOBALS['ancho_hoja'] - $GLOBALS['ancho_km'] - $GLOBALS['ancho_fecha_medicion']) / count($GLOBALS['costados']);

	    // 2. Se recorren los registros existentes
		foreach ($GLOBALS['costados'] as $costado) {
			// 3. Se define las coordenadas
			$this->setXY($x_costado, 41);

			// 4. Se imprime la información de la celda
			$this->Cell($ancho_costado, 5, utf8_decode($costado->Nombre),1,0,'C', 0);

	    	// 1. Se calcula el ancho
			$ancho_tipo_medicion = $ancho_costado / count($GLOBALS['tipos_mediciones']);

			// 2. Se recorren los registros existentes
			foreach ($GLOBALS['tipos_mediciones'] as $tipo_medicion) {
				// 3. Se define las coordenadas
				$this->setXY($x_tipo_medicion, 46);

				// 4. Se imprime la información de la celda
				$this->Cell($ancho_tipo_medicion, 5, utf8_decode($tipo_medicion->Nombre),1,0,'C', 0);

				// 1. Se calcula el ancho
				$ancho_fecha = $ancho_tipo_medicion / 2;
				$GLOBALS['ancho_fecha'] = $ancho_fecha;

				$fechas = Array($GLOBALS['fecha_anterior'], $GLOBALS['fecha']);

				// 2. Se recorren los registros existentes
				for ($i=0; $i <= 1; $i++) {
					// 3. Se define las coordenadas
					$this->SetXY($x_fecha, 51);

					// 4. Se imprime la información de la celda
    				$this->Cell($ancho_fecha, 5, utf8_decode($fechas[$i]),1,0,'C', 0);

    				// 5. Se incrementa el eje X para que continúe en el mismo eje
					$x_fecha += $ancho_fecha;
				}

				// 5. Se incrementa el eje X para que continúe en el mismo eje
				$x_tipo_medicion += $ancho_tipo_medicion;
			}

			// 5. Se incrementa el eje X para que continúe en el mismo eje
			$x_costado += $ancho_costado;
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
	    $this->Cell(0,10,utf8_decode('Sistema de Mediciones | Devimed S.A. | Generado: '.$GLOBALS['fecha_generacion'].' ('.date('H:i a').')'.' - Página '.$this->PageNo().' de {nb}'),0,0,'C');
	} // Footer
} // Clase PDF

// Creación del objeto de la clase heredada
$pdf = new PDF("P", "mm", "Letter");

//Alias para el numero de paginas(numeracion)
$pdf->AliasNbPages();

//Anadir pagina
$pdf->AddPage();

// Parámetros adicionales
$titulo = "Medición de rocería y cunetas - {$GLOBALS['fecha']}";
$pdf->SetTitle(utf8_decode($titulo));
$pdf->SetAuthor('John Arley Cano - johnarleycano@hotmail.com');
$pdf->SetCreator('John Arley Cano - johnarleycano@hotmail.com');

/**
 * Contenido de las mediciones
 */
// Se crean los registros de las abscisas
foreach ($this->mediciones_model->obtener("abscisas_mediciones", array("id_medicion" => $id_medicion, "id_medicion_anterior" => $id_medicion_anterior)) as $abscisa) {
	$pdf->Cell($GLOBALS['ancho_km'], 5, ($abscisa->Valor / 1000),1,0,'R', 0);
	$pdf->Cell($GLOBALS['ancho_fecha_medicion'], 5, $this->configuracion_model->obtener("formato_fecha", $abscisa->Fecha, "corto"),1,0,'L', 0);

	// Se recorren los costados de la medición anterior
	foreach ($GLOBALS['costados'] as $costado) {
		/**
		 * Medición anterior
		 */
		// Se recorren los tipos de mediciones
		foreach ($tipos_mediciones as $tipo_medicion) {
			/**
			 * Medición anterior
			 */
			// Datos para consultar detalles de la medición
			$datos = array(
				"Abscisa" => $abscisa->Valor,
				"Fk_Id_Tipo_Medicion" => $tipo_medicion->Pk_Id,
				"Fk_Id_Costado" => $costado->Pk_Id,
				"Fk_Id_Medicion" => $id_medicion_anterior,
			);

			// Se consulta el detalle de la medición anterior
			$detalle_medicion_anterior = $this->mediciones_model->obtener("medicion_detalle", $datos);

			if (isset($detalle_medicion_anterior->Calificacion)) {
				$calificacion_anterior = $detalle_medicion_anterior->Calificacion;
		    	$pdf->SetFillColor($detalle_medicion_anterior->Color_R, $detalle_medicion_anterior->Color_G, $detalle_medicion_anterior->Color_B);
		    	$relleno = 1;
		    	$factor_externo_anterior = ($detalle_medicion_anterior->Factor_Externo) ? "FE" : "";

		    	
			} else {
				$calificacion_anterior = "";
		    	$relleno = 0;
		    	$factor_externo_anterior = "";
			}

			$pdf->Cell($GLOBALS['ancho_fecha'] - $GLOBALS['ancho_fe'], 5, null, 1,0,'C', $relleno);
			$pdf->Cell($GLOBALS['ancho_fe'], 5, "", 1,0,'C', $relleno);
			// $pdf->Cell($GLOBALS['ancho_fecha'], 5, $calificacion_anterior, 1,0,'C', $relleno);

			/**
			 * Medición actual
			 */
			// Datos para consultar detalles de la medición
			$datos = array(
				"Abscisa" => $abscisa->Valor,
				"Fk_Id_Tipo_Medicion" => $tipo_medicion->Pk_Id,
				"Fk_Id_Costado" => $costado->Pk_Id,
				"Fk_Id_Medicion" => $medicion->Pk_Id,
			);

			// Se consulta el detalle de la medición actual
			$detalle_medicion_actual = $this->mediciones_model->obtener("medicion_detalle", $datos);

			if (isset($detalle_medicion_actual->Calificacion)) {
				$calificacion_actual = $detalle_medicion_actual->Calificacion;
		    	$pdf->SetFillColor($detalle_medicion_actual->Color_R, $detalle_medicion_actual->Color_G, $detalle_medicion_actual->Color_B);
		    	$relleno = 1;
		    	$factor_externo = ($detalle_medicion_actual->Factor_Externo) ? "FE" : "";
			} else {
				$calificacion_actual = "";
		    	$relleno = 0;
		    	$factor_externo = "";
			}

			$pdf->Cell($GLOBALS['ancho_fecha'] - $GLOBALS['ancho_fe'], 5, null,1,0,'C', $relleno);
			$pdf->Cell($GLOBALS['ancho_fe'], 5, $factor_externo, 1,0,'C', $relleno);
			// $pdf->Cell($GLOBALS['ancho_fecha'], 5, $calificacion_actual,1,0,'C', $relleno);
		}
	}

	$pdf->Ln();
}

$pdf->Output("I", utf8_decode("$titulo.pdf"));
?>