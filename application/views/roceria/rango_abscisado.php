<?php
// Valores iniciales
$abscisa_inicial = "";
$abscisa_final = "";
$kilometro_inicial = "";

// Si se eligió una vía
if($id_via != 0){
	// Se consultan los datos de la vía
	$via = $this->configuracion_model->obtener("via", $id_via);

	// Asignación de valores
	$abscisa_inicial = $via->Abscisa_Inicial;
	$abscisa_final = $via->Abscisa_Final + 1;
	$kilometro_inicial = $abscisa_inicial / 1000;
}
?>

<!-- Rango abscisado -->
<label class="uk-form-label" for="form-horizontal-select">
	Empezar en el kilómetro: <span id="kilometro_inicial"><?php echo $kilometro_inicial; ?></span>
</label>

<div class="uk-form-controls">
	<input class="uk-range" type="range" id="rango_abscisado" value="<?php echo $abscisa_inicial; ?>" min="<?php echo $abscisa_inicial; ?>" max="<?php echo $abscisa_final-1; ?>" step="1000" title="Elija la abscisa donde iniciará el recorrido" uk-tooltip="pos: bottom-left">
</div>

<!-- Label de abscisas inicial y final -->
<div class="uk-form-controls">
	<div class="uk-align-left" id="rango">
		<?php echo (isset($via->Abscisa_Inicial) ? $this->configuracion_model->obtener("abscisado", $abscisa_inicial) : ""); ?>		
	</div>
	<div class="uk-align-right" id="rango">
		<?php echo (isset($via->Abscisa_Final) ? $this->configuracion_model->obtener("abscisado", $abscisa_final) : ""); ?>
	</div>
</div>
<div class="clear"></div>

<!-- Rango de abscisas para la medición -->
<input type="hidden" id="abscisa_inicial" value="<?php echo $abscisa_inicial; ?>">
<input type="hidden" id="abscisa_final" value="<?php echo $abscisa_final; ?>">

<script type="text/javascript">
	$(document).ready(function(){
		// Cuando cambie el rango, se actualiza el valor del abscisado y kilómetro inicial
		$("#rango_abscisado").on("change", function(){
			$("#kilometro_inicial").text(parseFloat($(this).val())/1000);
			$("#abscisa_inicial").val(parseFloat($(this).val()));
		});
	});
</script>