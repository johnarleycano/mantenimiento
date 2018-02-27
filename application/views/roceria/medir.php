<?php
$abscisa = $this->uri->segment(5);

// Se consulta la medición actual
$medicion = $this->roceria_model->obtener("medicion", $this->uri->segment(3));

// Se consulta la medición anterior
$medicion_anterior = $this->roceria_model->obtener("medicion_anterior", array("id_via" => $medicion->Fk_Id_Via, "id_medicion" => $medicion->Pk_Id));

// Se toma el id de la medición actual y anterior (Asigna el id de la medición anterior, si existe tal medición)
$id_medicion = $medicion->Pk_Id;
$id_medicion_anterior = ($medicion_anterior) ? $medicion_anterior->Pk_Id : 0;

// Se consulta los ítems a medir
$tipos_mediciones = $this->configuracion_model->obtener("tipos_mediciones");

// Se consulta los costados de la vía a medir
$costados = $this->configuracion_model->obtener("costados", $medicion->Fk_Id_Via);

// Se consulta los costados de la vía a medir
$calificaciones = $this->configuracion_model->obtener("calificaciones");
?>

<input type="hidden" id="id_medicion" value="<?php echo $id_medicion; ?>">
<input type="hidden" id="posicion" value="<?php echo $this->uri->segment(4); ?>">
<input type="hidden" id="abscisa" value="<?php echo $abscisa; ?>">
<input type="hidden" id="abscisa_inicial" value="<?php echo $medicion->Abscisa_Inicial; ?>">
<input type="hidden" id="abscisa_final" value="<?php echo $medicion->Abscisa_Final; ?>">
<input type="hidden" id="orden" value="<?php echo $this->uri->segment(6); ?>">

<!-- Contenedor de mediciones -->
<div id="mediciones">
	<h3 class="uk-heading-line uk-text-center">
		<span>Kilómetro <?php echo $abscisa/1000; ?></span>
	</h3>

	<!-- Medición -->
	<div id="medicion">
		<span>&nbsp;</span>
		<!-- Se recorren las calificaciones -->
		<?php foreach ($calificaciones as $calificacion) { ?>
			<div class="contenedor" style="background-color: rgb(<?php echo $calificacion->Color_R; ?>, <?php echo $calificacion->Color_G; ?>, <?php echo $calificacion->Color_B; ?>);"><img class="icon" src="<?php echo base_url(); ?>img/<?php echo $calificacion->Valor; ?>.png" title="<?php echo $calificacion->Descripcion; ?>" uk-tooltip="pos: bottom-center"></div>
		<?php } ?>
		<div class="contenedor cero"><p class="texto" title="Factor externo" uk-tooltip="pos: bottom-center"><b>FE</b></p></div>
	</div>
	<div class="separador"></div>

	<?php
	// Se recorren los costados
	foreach ($costados as $costado) {
		$nombre_costado = $costado->Nombre;

		// Se recorren los tipos de mediciones
		foreach ($tipos_mediciones as $tipo_medicion) {
			// Datos para consultar detalles de la medición
			$datos = array(
				"Abscisa" => $abscisa,
				"Fk_Id_Tipo_Medicion" => $tipo_medicion->Pk_Id,
				"Fk_Id_Costado" => $costado->Pk_Id,
			);

			// Se consulta el detalle de la medición actual
			$datos["Fk_Id_Medicion"] = $id_medicion;
			$detalle_medicion = $this->roceria_model->obtener("medicion_detalle", $datos);

			// Se consulta el detalle de la medición anterior
			$datos["Fk_Id_Medicion"] = $id_medicion_anterior;
			$detalle_medicion_anterior = $this->roceria_model->obtener("medicion_detalle", $datos);
			?>
			
			<div id="medicion">
				<?php
				if($costado->Nombre == $nombre_costado) {
					?>
					<div class="titulo_costado"><?php echo $nombre_costado; ?></div>
					<?php
				}
				$nombre_costado = "";
				?>

				<span class="uk-text-small">&nbsp;<?php echo "$tipo_medicion->Nombre"; ?></span>

				<?php
				// Se recorren las calificaciones
				foreach ($calificaciones as $calificacion) {
					$activo_fe = "disabled";
					$chequeado_fe = "";

					if (isset($detalle_medicion_anterior->Factor_Externo) && $detalle_medicion_anterior->Factor_Externo == 1) {
						$chequeado_fe = "checked";
					}

					// Si existe la calificación con anterioridad, se activa o desactiva el check y selecciona la casilla
					if (isset($detalle_medicion->Calificacion) && $detalle_medicion->Calificacion == $calificacion->Valor) {
						$chequeado = "checked";
						$opacidad = "";
					} else {
						$chequeado = "";
						$opacidad = "opacidad";
					}

					// Si factor externo está marcado, chequeará el input
					if (isset($detalle_medicion->Calificacion) && $detalle_medicion->Factor_Externo == 1) {
						$chequeado_fe = "checked";
						$activo_fe = "";
					}

					// Si factor externo está marcado, chequeará el input
					if (isset($detalle_medicion->Calificacion) && $detalle_medicion->Factor_Externo == 0) {
						$chequeado_fe = "";
					}

					$activo_fe = (isset($detalle_medicion->Calificacion)) ? "" : "disabled" ;

					// Si tiene medición anterior, se marca la clase para que se vea en la calificación
					$clase_medicion_anterior = (isset($detalle_medicion_anterior->Calificacion) && $detalle_medicion_anterior->Calificacion == $calificacion->Valor) ? "medicion_anterior" : "medicion_normal" ;
					?>
					
					<label>
						<div class="contenedor <?php echo $opacidad.' '.$clase_medicion_anterior; ?>" name="calificacion_<?php echo "{$tipo_medicion->Pk_Id}_{$costado->Pk_Id}_{$calificacion->Valor}" ?>" style="background-color: rgb(<?php echo $calificacion->Color_R; ?>, <?php echo $calificacion->Color_G; ?>, <?php echo $calificacion->Color_B; ?>);">
							<input
								class="uk-radio opacidad " 
								type="radio" 
								onClick="javascript:marcar(<?php echo $tipo_medicion->Pk_Id; ?>, <?php echo $costado->Pk_Id; ?>, <?php echo $calificacion->Valor; ?>)"
								id="calificacion_<?php echo "{$tipo_medicion->Pk_Id}_{$costado->Pk_Id}_{$calificacion->Valor}" ?>"
								name='calificacion_<?php echo "{$tipo_medicion->Pk_Id}_{$costado->Pk_Id}"; ?>' 
								data-tipo_medicion="<?php echo $tipo_medicion->Pk_Id; ?>"
								data-costado="<?php echo $costado->Pk_Id; ?>"
								data-calificacion="<?php echo $calificacion->Valor; ?>"
								<?php echo $chequeado; ?>
							>

							<!-- Input que almacena la marca de la calificación realizada, para tenerla en cuenta al momento de quitar la calificación -->
							<input type="hidden" id="<?php echo "{$tipo_medicion->Pk_Id}_{$costado->Pk_Id}_{$calificacion->Valor}"; ?>" value="<?php echo $chequeado; ?>">
						</div>
					</label>
				<?php } ?>

				<label>
					<!-- Factor externo -->
					<div class="contenedor">
						<input class="uk-radio" type="checkbox" id="factor_externo_<?php echo "{$tipo_medicion->Pk_Id}_{$costado->Pk_Id}" ?>" <?php echo "$chequeado_fe $activo_fe"; ?>>
					</div>
				</label>
			</div>
		<?php } ?>
		<div class="separador"></div>
	<?php } ?>
</div>

<script type="text/javascript">
	/**
	 * Continúa la medición
	 * 
	 * @param  {string} tipo [continúa hacia adelante o hacia atrás]
	 * 
	 * @return {void}
	 */
	function continuar(tipo) {
		cerrar_notificaciones();
		imprimir_notificacion("<div uk-spinner></div> Guardando medición...");

		const abscisa = parseFloat($("#abscisa").val())
		const posicion = (tipo == "adelante") ? parseFloat($("#posicion").val()) + 1 : parseFloat($("#posicion").val()) - 1
		let siguiente_abscisa
		let rango

		switch($("#orden").val()) {
		    case "1":
		        siguiente_abscisa = (tipo == "adelante") ? abscisa + 1000 : abscisa - 1000
		        rango = (siguiente_abscisa >= $("#abscisa_inicial").val() && siguiente_abscisa <= $("#abscisa_final").val()) ? true : false
	        break;
		
		    case "2":
		        siguiente_abscisa = (tipo == "adelante") ? abscisa - 1000 : abscisa + 1000
		        rango = (siguiente_abscisa >= $("#abscisa_inicial").val() && siguiente_abscisa <= $("#abscisa_final").val()) ? true : false
	        break;
		}

		if (!rango && tipo == "adelante") {
			detener()
			return false;
		}
		
		if (!rango && tipo == "atras") {
			redireccionar("<?php echo site_url('roceria/parametrizar'); ?>")
			return false;
		}

		guardar(tipo)
		redireccionar(`<?php echo site_url('roceria/medir'); ?>/${$("#id_medicion").val()}/${posicion}/${siguiente_abscisa}/${$("#orden").val()}`)
	}

	/**
	 * Envía los registros vía Ajax para ser guardados
	 * en base de datos
	 * 
	 * @param  [string] tipo [siguiente, anterior]
	 * 
	 * @return void
	 */
	function guardar(tipo = null)
	{
		let datos_medicion = [];
		let abscisa = $("#abscisa").val()
		let id_medicion = $("#id_medicion").val()
		
		// Se recorren los chequeados y se almacenan en el arreglo
        $("input[name^='calificacion_']:checked").each(function() {
        	let factor_externo

        	// Si está marcado como factor externo, se almacena para guardarse
        	( $("#factor_externo_" + $(this).attr("data-tipo_medicion") + "_" + $(this).attr("data-costado")).prop('checked')) ? factor_externo = 1 : factor_externo = 0

        	let medicion = {
        		"Abscisa": abscisa,
        		"Fk_Id_Medicion": id_medicion,
        		"Fk_Id_Tipo_Medicion": $(this).attr("data-tipo_medicion"),
        		"Fk_Id_Costado": $(this).attr("data-costado"),
        		"Calificacion": $(this).attr("data-calificacion"),
        		"Fecha": "<?php echo date("Y-m-d H:i:s"); ?>",
        		"Factor_Externo": factor_externo,
        	}
            datos_medicion.push(medicion);
        });
        // imprimir(datos_medicion);
        
        // Se eliminan los anteriores registros que pueda tener
        ajax("<?php echo site_url('roceria/eliminar'); ?>", {"tipo": "medicion_detalle", "datos": {"Abscisa": abscisa, "Fk_Id_Medicion": id_medicion}}, 'html');

        // Si tiene ítems marcados, se procede a guardar y retornar el id
        if (datos_medicion.length > 0) {
	        id = ajax("<?php echo site_url('roceria/insertar'); ?>", {"tipo": "medicion_detalle", "datos": datos_medicion}, 'html');
	        return id;
        }
	}

	/**
	 * Marca el ítem y lo resalta sobre los demás, entregando la
	 * calificación de 1 a 5
	 *
	 * @param  [string] tipo_medicion [Tipo de medición que se va a tomar (cuneta, rocería, etc)]
	 * @param  [string] costado       [Derecho, Izquierdo, Central]
	 * @param  [string] calificacion  [Desde 1 a 5]
	 * 
	 * @return [void]
	 */
	function marcar(tipo_medicion, costado, calificacion)
	{
		// Si la marca estaba en la misma posición, se ejecuta la desmarcación
		if ($("#"+tipo_medicion+"_"+costado+"_"+calificacion).val() == "checked") {
			desmarcar(tipo_medicion, costado, calificacion);

			return false;
		}

		// Se quitan las marcas de chequeado
		$("input[id^='"+tipo_medicion+"_"+costado+"']").val("");
		
		// Se marca como chequeado el valor escogido
		$("#" + tipo_medicion + "_" + costado + "_" + calificacion).val("checked");

		// Se activa el factor externo
		$("#factor_externo_" + tipo_medicion + "_" + costado).removeAttr("disabled")
		
		// Se desactivan todos los contenedores
		$("div[name^='calificacion_" + tipo_medicion + "_" + costado + "']").addClass('opacidad');

		// Se activa el contenedor seleccionado
		$("div[name='calificacion_" + tipo_medicion + "_" + costado + "_" + calificacion + "']").removeClass("opacidad");
	}

	/**
	 * Desmarca el ítem que se marcó, para que no quede ninguna 
	 * calificación marcada
	 *
	 * @param  [string] tipo_medicion [Tipo de medición que se va a tomar (cuneta, rocería, etc)]
	 * @param  [string] costado       [Derecho, Izquierdo, Central]
	 * @param  [string] calificacion  [Desde 1 a 5]
	 * 
	 * @return [void]
	 */
	function desmarcar(tipo_medicion, costado, calificacion)
	{
		// Se quita la marca de chequeado
		$("input[id^='"+tipo_medicion+"_"+costado+"_"+calificacion+"']").val("");
		
		// Se inactiva el factor externo
		$("#factor_externo_" + tipo_medicion + "_" + costado).prop({"disabled": true, "checked": false});

		// Se desactivan todos los contenedores
		$("div[name^='calificacion_" + tipo_medicion + "_" + costado + "']").addClass('opacidad');

		// Se quita el check
		$("input[name='calificacion_" + tipo_medicion + "_" + costado + "']").prop('checked', false); 
	}

	/**
	 * Carga la interfaz de resumen de la medición realizada
	 * 
	 * @return [void]
	 */
	function detener()
	{
		cerrar_notificaciones();
		imprimir_notificacion("<div uk-spinner></div> Guardando medición...");

		// Se guarda, si la hay
		guardar("detener");

		// Se redirecciona a la interfaz de resumen
		redireccionar(`${"<?php echo site_url('roceria/resumen_medicion'); ?>"}/${$("#id_medicion").val()}`);
	}
	
	$(document).ready(function(){
		var opciones = Array("anterior", "detener", "siguiente");

		// Si es la primera posición, quita el botón "anterior" y "detener medición"
		if ($("#posicion").val() == 1) opciones.splice(0, 1);

		// Botones del menú
		botones(opciones);
	});
</script>