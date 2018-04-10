<?php
// Se consutan las últimas mediciones
$mediciones = $this->panel_model->obtener("ultimas_mediciones", $fecha);

// Si no hay mediciones, se muestra mensaje
if (count($mediciones) == 0) {
	echo "No hay registros por mostrar.";
	die();
}
?>

		<ul  class="uk-list uk-list-striped">
	<?php foreach ($mediciones as $medicion) { ?>
		    <li>
		    	<span class="uk-text-bold"><?php echo "$medicion->Sector | $medicion->Via"; ?></span>
		    	<time datetime="<?php echo $medicion->Fecha; ?>" class="uk-text-muted uk-text-small"><?php echo $medicion->Fecha; ?></time><br>
		    	
		    	<a onCLick="javascript:ver_detalle(<?php echo $medicion->Fk_Id_Medicion; ?>)" uk-tooltip="pos: bottom-left" title="Ver detalles de la medición" style="text-decoration: none;">
		    		<i class="fas fa-search"></i>
		    	</a>

		    	<a onCLick="javascript:generar_pdf(<?php echo $medicion->Fk_Id_Medicion; ?>)" uk-tooltip="pos: bottom-left" title="Imprimir reporte en PDF" style="text-decoration: none;">
		    		<i class="far fa-file-pdf"></i>
		    	</a>
		    </li>
		
		<!-- <dt><?php // echo "$medicion->Sector | $medicion->Via"; ?></dt> -->
		<!-- <a onCLick="javascript:ver_detalle(<?php //echo $medicion->Fk_Id_Medicion; ?>)" style="text-decoration: none;"> -->
		<!-- <article class="uk-article">
		    <p class="uk-article-meta">
		    	<ul class="uk-iconnav">
				    <time class="timeago" datetime="<?php // echo $medicion->Fecha; ?>"><?php // echo $medicion->Fecha; ?></time>
				    <li><i class="fas fa-search"></i></li>
				    <li><i class="fas fa-search"></i></li>
				</ul>
		    </p>
		</article> -->
		<!-- </a> -->
	<?php } ?>
		</ul>

<script type="text/javascript">
	$("time").timeago();
</script>