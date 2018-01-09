<dl class="uk-description-list uk-description-list-divider">
	<?php foreach ($this->panel_model->obtener("ultimas_mediciones", $fecha) as $medicion) { ?>
		<dt><?php echo "$medicion->Sector | $medicion->Via"; ?></dt>
	    <dd class="uk-text-small">
			<article class="uk-article">
			    <p class="uk-article-meta">
			    	<?php echo $this->configuracion_model->obtener("formato_fecha", $medicion->Fecha)." | ".$medicion->Hora; ?>
			    </p>
			</article>
	    </dd>
	<?php } ?>
</dl>

<script type="text/javascript">
	$(document).ready(function(){
		
	});
</script>