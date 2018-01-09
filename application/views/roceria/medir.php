<?php
// Se consulta la medición
$medicion = $this->roceria_model->obtener("medicion", $id_medicion);
// print_r($medicion);

// Se consulta los ítems a medir
$tipos_mediciones = $this->configuracion_model->obtener("tipos_mediciones");
// print_r($tipos_mediciones);

// Se consulta los costados de la vía a medir
$costados = $this->configuracion_model->obtener("costados", $medicion->Fk_Id_Via);
// print_r($costados);

// Se consulta los costados de la vía a medir
$calificaciones = $this->configuracion_model->obtener("calificaciones");
// print_r($costados);
?>

<input type="hidden" id="posicion" value="<?php echo $posicion; ?>">
<input type="hidden" id="id_medicion" value="<?php echo $id_medicion; ?>">
<input type="hidden" id="abscisa" value="<?php echo $abscisa; ?>">
<input type="hidden" id="abscisa_inicial" value="<?php echo $abscisa_inicial; ?>">
<input type="hidden" id="abscisa_final" value="<?php echo $abscisa_final; ?>">

<div id="mediciones">
	<h3 class="uk-heading-bullet">Kilómetro <?php echo ($abscisa/1000) ?></h3>
	<div id="medicion">
		<h5 class="uk-heading-divider">&nbsp;</h5>

		<div class="contenedor cinco"><img class="icon" src="<?php echo base_url(); ?>img/5.png"></div>
		<div class="contenedor cuatro"><img class="icon" src="<?php echo base_url(); ?>img/4.png"></div>
		<div class="contenedor tres"><img class="icon" src="<?php echo base_url(); ?>img/3.png"></div>
		<div class="contenedor dos"><img class="icon" src="<?php echo base_url(); ?>img/2.png"></div>
		<div class="contenedor uno"><img class="icon" src="<?php echo base_url(); ?>img/1.png"></div>
		<div class="contenedor cero"><p class="texto"><b>FE</b></p></div>
	</div>
	<div class="separador"></div>

	<?php foreach ($tipos_mediciones as $tipo_medicion) { ?>
		<?php foreach ($costados as $costado) { ?>
			<!-- Se consulta el detalle de la medición -->
			<?php $detalle_medicion = $this->roceria_model->obtener("medicion_detalle", array("Fk_Id_Medicion" => $id_medicion, "Abscisa" => $abscisa, "Fk_Id_Tipo_Medicion" => $tipo_medicion->Pk_Id, "Fk_Id_Costado" => $costado->Pk_Id)); ?>

			<div id="medicion">
				<h5 class="uk-heading-divider"><?php echo $costado->Codigo; ?></h5>
				
				<?php foreach ($calificaciones as $calificacion) { ?>
					<?php
					// Si existe la calificación con anterioridad, se activa o desactiva el check y selecciona la casilla
					if (isset($detalle_medicion->Calificacion) && $detalle_medicion->Calificacion == $calificacion->Valor) {
						$chequeado = "checked";
						$opacidad = "";
					} else {
						$chequeado = "";
						$opacidad = "opacidad";
					} ?>

					<label>
						<div class="contenedor <?php echo $opacidad.' '.$calificacion->Clase; ?>" name="calificacion_<?php echo "{$tipo_medicion->Pk_Id}_{$costado->Pk_Id}_{$calificacion->Valor}" ?>">
							<input
								class="uk-radio opacidad" 
								type="radio" 
								name='calificacion_<?php echo "{$tipo_medicion->Pk_Id}_{$costado->Pk_Id}" ?>' 
								onClick="javascript:marcar(<?php echo $tipo_medicion->Pk_Id; ?>, <?php echo $costado->Pk_Id; ?>, <?php echo $calificacion->Valor; ?>)" 
								data-tipo_medicion="<?php echo $tipo_medicion->Pk_Id; ?>"
								data-costado="<?php echo $costado->Pk_Id; ?>"
								data-calificacion="<?php echo $calificacion->Valor; ?>"
								<?php echo $chequeado; ?>
							>
						</div>
					</label>
				<?php } ?>
			</div>
		<?php } ?>
		<div class="separador"></div>
	<?php } ?>

	<progress id="js-progressbar" class="uk-progress" value="<?php echo $abscisa; ?>" max="<?php echo $abscisa_final; ?>"></progress>
</div>

<script type="text/javascript">
	// Cargará el detalle de la medición en esa posición
	function anterior()
	{
		// Si es paso 1, se devuelve a la interfaz de parametrización
		if ($("#posicion").val() == "1") {
			redireccionar("<?php echo site_url('roceria/parametrizar'); ?>");
			return false;
		}

		guardar("anterior")

		// Se carga la interfaz de medición
        url = "<?php echo site_url('roceria/medir') ?>" + "/" + $("#id_medicion").val() + "/" + (parseFloat($("#posicion").val()) - 1) + "/" + (parseFloat($("#abscisa").val()) - 1000) + "/" + $("#abscisa_final").val();
		redireccionar(url);
	}

	function guardar(tipo)
	{
		var datos_medicion = []
		
		// Se recorren los chequeados y se almacenan en el arreglo
        $("input[name^='calificacion_']:checked").each(function() {
        	var medicion = {
        		"Abscisa": $("#abscisa").val(),
        		"Fk_Id_Medicion": $("#id_medicion").val(),
        		"Fk_Id_Tipo_Medicion": $(this).attr("data-tipo_medicion"),
        		"Fk_Id_Costado": $(this).attr("data-costado"),
        		"Calificacion": $(this).attr("data-calificacion"),
        		"Fecha": "<?php echo date("Y-m-d h:i:s"); ?>",
        	}
            datos_medicion.push(medicion);
        });
        // imprimir(datos_medicion);
        
        // Se eliminan los anteriores registros que pueda tener
        ajax("<?php echo site_url('roceria/eliminar'); ?>", {"tipo": "medicion_detalle", "datos": {"Abscisa": $("#abscisa").val(), "Fk_Id_Medicion": $("#id_medicion").val()}}, 'html');

      	// Si es medición siguiente y no se marcó ningún ítem, mostrará un mensaje para que marque al menos uno
        if (datos_medicion.length == 0 && tipo == "siguiente") {
        	cerrar_notificaciones();
			imprimir_notificacion("No ha tomado ninguna medida. Marque al menos un ítem.", "danger");

			return false;
        }

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
	 * @param  [string] costado       [Derecho, Izquierdo, central]
	 * @param  [string] calificacion  [Desde 0 a 5]
	 * 
	 * @return [void]
	 */
	function marcar(tipo_medicion, costado, calificacion)
	{
		$("div[name^='calificacion_" + tipo_medicion + "_" + costado + "']").addClass('opacidad');
		$("div[name='calificacion_" + tipo_medicion + "_" + costado + "_" + calificacion + "']").removeClass("opacidad");
	}

	/**
	 * Continúa la medición en el siguiente kilómetro
	 * 
	 * @return [void]
	 */
	function siguiente()
	{
		cerrar_notificaciones();
		imprimir_notificacion("<div uk-spinner></div> Guardando medición...");

		// Si se guarda exitosamente
		if (guardar("siguiente")) {
			// Si la abscisa siguiente es mayor a la abscisa final,
			// entonces mostrará los resultados finales
			if ((parseFloat($("#abscisa").val()) + 1000) > $("#abscisa_final").val()) {
	        	terminar();

				return false;
			}

			// Se carga la interfaz de medición
	        url = "<?php echo site_url('roceria/medir') ?>" + "/" + $("#id_medicion").val() + "/" + (parseFloat($("#posicion").val()) + 1) + "/" + (parseFloat($("#abscisa").val()) + 1000) + "/" + $("#abscisa_final").val();
			redireccionar(url);
		}
	}

	/**
	 * Carga la interfaz de resumen de la medición realizada
	 * 
	 * @return [void]
	 */
	function terminar()
	{
    	url = "<?php echo site_url('roceria/terminar') ?>" + "/" + $("#id_medicion").val();
		redireccionar(url);
	}
	
	$(document).ready(function(){
		var opciones = Array("anterior", "siguiente", "terminar");

		// Si es la primera posición, quita el botón "anterior"
		if ($("#posicion").val() == "1") {
			opciones.splice(0, 1);
		}

		// Botones del menú
		botones(opciones);
	});
</script>