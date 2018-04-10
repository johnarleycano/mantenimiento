<?php
$mediciones = $this->mediciones_model->obtener("resumen", $datos);

// Si no hay mediciones, se muestra mensaje
if (count($mediciones) == 0) { ?>
	<div class="uk-alert-primary" uk-alert>
	    <a class="uk-alert-close" uk-close></a>
	    <p>No se han encontrado mediciones con los filtros seleccionados.</p>
	</div>
<?php exit(); } ?>

<ul class="uk-list uk-list-striped">
    <?php foreach ($mediciones as $medicion) { ?>
    	<li>
    		<!-- Generar PDF -->
    		<a onCLick="javascript:generar_pdf(<?php echo $medicion->Pk_Id; ?>)" uk-tooltip="pos: bottom-left" title="Imprimir reporte en PDF" class="icono">
	    		<i class="far fa-file-pdf"></i>
	    	</a>

	    	<a onCLick="javascript:continuar_medicion(<?php echo $medicion->Pk_Id; ?>)" uk-tooltip="pos: bottom-left" title="Continuar medición" class="icono">
	    		<i class="far fa-paper-plane"></i>
	    	</a>

    		<span class="uk-text-left"><?php echo "$medicion->Sector | $medicion->Via"; ?></span>
    		<time datetime="<?php echo $medicion->Fecha_Inicial; ?>" class="uk-text-muted uk-text-small" style="float: right"><?php echo $medicion->Fecha_Inicial; ?></time>
    	</li>
	<?php } ?>
</ul>


<script type="text/javascript">
	$(document).ready(function(){
		cerrar_notificaciones();
		
		$("time").timeago();
	})
</script>