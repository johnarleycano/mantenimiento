<?php
// Se consulta la medición
$medicion_temporal = $this->roceria_model->obtener("medicion_temporal", $id_medicion_temporal);
// print_r($medicion_temporal);

// Se consulta los ítems a medir
$tipos_mediciones = $this->configuracion_model->obtener("tipos_mediciones");
// print_r($tipos_mediciones);

// Se consulta los costados de la vía a medir
$costados = $this->configuracion_model->obtener("costados", $medicion_temporal->Fk_Id_Via);
// print_r($costados);

// Se consulta los costados de la vía a medir
$calificaciones = $this->configuracion_model->obtener("calificaciones");
// print_r($costados);

?>
<input type="text" id="posicion" value="<?php echo $posicion; ?>">
<input type="text" id="id_medicion_temporal" value="<?php echo $id_medicion_temporal; ?>">
<input type="text" id="abscisa" value="<?php echo $abscisa; ?>">
<input type="text" id="abscisa_final" value="<?php echo $abscisa_final; ?>">

<div id="mediciones">
	<h3 class="uk-heading-bullet">Kilómetro <?php echo ($abscisa/1000) ?></h3>
	<div id="medicion">
		<h5 class="uk-heading-divider">&nbsp;</h5>

		<div class="contenedor" id="cinco">
			<img class="icon" src="<?php echo base_url(); ?>img/5.png">
		</div>

		<div class="contenedor" id="cuatro">
			<img class="icon" src="<?php echo base_url(); ?>img/4.png">
		</div>

		<div class="contenedor" id="tres">
			<img class="icon" src="<?php echo base_url(); ?>img/3.png">
		</div>

		<div class="contenedor" id="dos">
			<img class="icon" src="<?php echo base_url(); ?>img/2.png">
		</div>

		<div class="contenedor" id="uno">
			<img class="icon" src="<?php echo base_url(); ?>img/1.png">
		</div>

		<div class="contenedor" id="cero">
			<p class="texto"><b>FE</b></p>
		</div>
	</div>
	<div class="separador"></div>

	<?php foreach ($tipos_mediciones as $tipo_medicion) { ?>
		<?php foreach ($costados as $costado) { ?>
			<div id="medicion">
				<h5 class="uk-heading-divider"><?php echo $costado->Codigo; ?></h5>

				<?php foreach ($calificaciones as $calificacion) { ?>
					<label>
						<div class="contenedor opacidad" id="<?php echo $calificacion->Clase; ?>" name="calificacion_<?php echo "{$tipo_medicion->Pk_Id}_{$costado->Pk_Id}_{$calificacion->Valor}" ?>">

							<input
								class="uk-radio opacidad" 
								type="radio" 
								name='calificacion_<?php echo "{$tipo_medicion->Pk_Id}_{$costado->Pk_Id}" ?>' 
								onClick="javascript:marcar(<?php echo $tipo_medicion->Pk_Id; ?>, <?php echo $costado->Pk_Id; ?>, <?php echo $calificacion->Valor; ?>)" 
								data-tipo_medicion="<?php echo $tipo_medicion->Pk_Id; ?>"
								data-costado="<?php echo $costado->Pk_Id; ?>"
								data-calificacion="<?php echo $calificacion->Valor; ?>"
							>
						</div>
					</label>
				<?php } ?>
			</div>
		<?php } ?>
		<div class="separador"></div>
	<?php } ?>
</div>

<script type="text/javascript">
	function marcar(tipo_medicion, costado, calificacion)
	{
		$("div[name^='calificacion_" + tipo_medicion + "_" + costado + "']").addClass('opacidad');
		$("div[name='calificacion_" + tipo_medicion + "_" + costado + "_" + calificacion + "']").removeClass("opacidad");
	}


	// Cargará el detalle de la medición en esa posición
	function anterior()
	{
		

	    var posicion = parseFloat($("#posicion").val()) - 1;
	    
	    // Url con el inicio de la medición
        url = "<?php echo site_url('roceria/medir') ?>" + "/" + $("#id_medicion_temporal").val() + "/" + posicion;

		// Se carga la interfaz de medición
		redireccionar(url);

		
	}

	function siguiente()
	{
		cerrar_notificaciones();
		imprimir_notificacion("<div uk-spinner></div> Guardando medición...");

		var id_medicion_temporal = $("#id_medicion_temporal").val();
		var posicion = parseFloat($("#posicion").val()) + 1;
	    var abscisa = parseFloat($("#abscisa").val()) + 1000;
	    var abscisa_final = $("#abscisa_final").val();

		// Se elimina la medición si anteriormente se hizo
		
		var datos_medicion = []
		
		// Se recorren los chequeados y se almacenan en el arreglo
        $("input[name^='calificacion_']:checked").each(function() {
        	var medicion = {
        		"Abscisa": $("#abscisa").val(),
        		"Fk_Id_Temp_Medicion": id_medicion_temporal,
        		"Fk_Id_Tipo_Medicion": $(this).attr("data-tipo_medicion"),
        		"Fk_Id_Costado": $(this).attr("data-costado"),
        		"Fk_Id_Calificacion": $(this).attr("data-calificacion"),
        	}
            datos_medicion.push(medicion);
        });
        // imprimir(unidades_medida);
        
		guardar = ajax("<?php echo site_url('roceria/insertar'); ?>", {"tipo": "medicion_detalle_temporal", "datos": datos_medicion}, 'html');
		// imprimir(guardar);
		
		// Si la abscisa siguiente es mayor a la abscisa final,
		// entonces mostrará los resultados finales
		if (abscisa > abscisa_final) {
			// Url con el fin de la medición
        	url = "<?php echo site_url('roceria/terminar') ?>" + "/" + id_medicion_temporal;

			// Se carga la interfaz de medición
			redireccionar(url);

			return false;
		}
		
		
	   
	    
		// Url con el inicio de la medición
        url = "<?php echo site_url('roceria/medir') ?>" + "/" + id_medicion_temporal + "/" + posicion + "/" + abscisa + "/" + abscisa_final;

		// Se carga la interfaz de medición
		redireccionar(url);
	}

	function terminar()
	{
		// Url con el fin de la medición
    	url = "<?php echo site_url('roceria/terminar') ?>" + "/" + $("#id_medicion_temporal").val();

		// Se carga la interfaz de medición
		redireccionar(url);
	}
	
	$(document).ready(function(){
		// Botones del menú
		botones(Array("anterior", "siguiente", "terminar"));
	});
</script>