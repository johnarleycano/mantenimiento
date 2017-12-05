<?php
// Si se eligió una vía
if($id_via != 0){
	// Se consultan los datos de la vía
	$via = $this->configuracion_model->obtener("via", $id_via);
}
?>

<!-- Rango abscisado -->
<label class="uk-form-label" for="form-horizontal-select">Empezar en el kilómetro: <span id="abscisa_inicial"><?php echo (isset($via->Abscisa_Inicial)) ? $via->Abscisa_Inicial / 1000 : "" ; ?></span></label>
<div class="uk-form-controls">
	<input class="uk-range" type="range" id="rango_abscisado" value="<?php echo (isset($via->Abscisa_Inicial)) ? $via->Abscisa_Inicial : "" ; ?>" min="<?php echo (isset($via->Abscisa_Inicial)) ? $via->Abscisa_Inicial : "" ; ?>" max="<?php echo (isset($via->Abscisa_Final)) ? $via->Abscisa_Final : "" ; ?>" step="1000" title="Elija la abscisa donde iniciaría el recorrido" uk-tooltip="pos: bottom-left">
</div>

<!-- Abscisas inicial y final <-->
<div class="uk-form-controls">
	<div class="uk-align-left" id="rango"><?php echo (isset($via->Abscisa_Inicial) ? $this->configuracion_model->obtener("abscisado", $via->Abscisa_Inicial) : ""); ?></div>
	<div class="uk-align-right" id="rango"><?php echo (isset($via->Abscisa_Final) ? $this->configuracion_model->obtener("abscisado", $via->Abscisa_Final + 1) : ""); ?></div>
</div>
<div class="clear"></div>

<script type="text/javascript">
	$(document).ready(function(){


		$("#rango_abscisado").on("change", function(){
			$("#abscisa_inicial").text(parseFloat($(this).val())/1000);
		});
	});
</script>