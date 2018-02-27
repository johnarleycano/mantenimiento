<?php
// Se consutan las últimas mediciones
$mediciones = $this->panel_model->obtener("mediciones_urgentes", $calificacion);

// Si no hay mediciones, se muestra mensaje
if (count($mediciones) == 0) {
	echo "No hay registros por mostrar.";
	die();
}
?>
<dl class="uk-description-list uk-description-list-divider">
	<?php foreach ($mediciones as $medicion) { ?>
		<dt style="vertical-align: middle;">
			<span class="uk-badge"><?php echo "$medicion->Puntos" ?></span> <?php echo "$medicion->Sector | $medicion->Via"; ?>
		</dt>
				    	
		
		<a onCLick="javascript:ver_detalle(<?php echo $medicion->Fk_Id_Medicion; ?>, <?php echo $calificacion; ?>)" class="uk-link-reset">
		    <dd class="uk-text-small">
				<article class="uk-article">
				    <p class="uk-article-meta">
						<time datetime="<?php echo $medicion->Fecha; ?>"><?php echo $medicion->Fecha; ?></time>
				    </p>
				</article>
		    </dd>
		</a>
	<?php } ?>
</dl>

<script type="text/javascript">
	$("time").timeago();
</script>