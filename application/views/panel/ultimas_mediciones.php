<?php
// Se consutan las últimas mediciones
$mediciones = $this->panel_model->obtener("ultimas_mediciones", $fecha);

// Si no hay mediciones, se muestra mensaje
if (count($mediciones) == 0) {
	echo "No hay registros por mostrar.";
	die();
}
?>

<dl class="uk-description-list uk-description-list-divider">
	<?php foreach ($mediciones as $medicion) { ?>
		<a onCLick="javascript:ver_detalle(<?php echo $medicion->Fk_Id_Medicion; ?>)" style="text-decoration: none;">
			<dt><?php echo "$medicion->Sector | $medicion->Via"; ?></dt>
		    <dd class="uk-text-small">
				<article class="uk-article">
				    <p class="uk-article-meta">
				    	<?php echo $this->configuracion_model->obtener("formato_fecha", $medicion->Fecha)." | ".$medicion->Hora; ?>
				    </p>
				</article>
		    </dd>
		</a>
	<?php } ?>
</dl>