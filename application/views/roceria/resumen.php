<?php
$id_medicion = $this->uri->segment(3);

// Se consulta la medición actual
$medicion = $this->roceria_model->obtener("medicion", $id_medicion);

// Se consulta los ítems a medir
$tipos_mediciones = $this->configuracion_model->obtener("tipos_mediciones");

// Se consulta los costados de la vía a medir
$costados = $this->configuracion_model->obtener("costados", $medicion->Fk_Id_Via);

// $detalle_medicion = $this->roceria_model->obtener("resumen_medicion", $id_medicion);
$abscisas = $this->roceria_model->obtener("abscisas_mediciones", array("id_medicion" => $id_medicion, "id_medicion_anterior" => null));
?>

<h3 class="uk-heading-line"><span><?php echo "$medicion->Sector | $medicion->Via"; ?></span></h3>

<div class="uk-overflow-auto">
    <table class="uk-table uk-table-hover uk-table-middle uk-table-divider">
        <thead>
            <tr>
                <th class="uk-table-shrink uk-text-middle" rowspan="2">Km</th>
				<?php foreach ($costados as $costado) { ?>
                	<th class="uk-text-center" colspan="<?php echo count($tipos_mediciones); ?>"><?php echo "COSTADO $costado->Nombre"; ?></th>
				<?php } ?>
            </tr>
            <tr>
				<?php foreach ($costados as $costado) { ?>
					<?php foreach ($tipos_mediciones as $tipo_medicion) { ?>
                		<th class="uk-text-center"><?php echo "$tipo_medicion->Nombre"; ?></th>
					<?php } ?>
				<?php } ?>
            </tr>
        </thead>
        <tbody>
        	<?php foreach ($abscisas as $abscisa) { ?>
	            <tr>
	                <td class="uk-text-right">
	                	<?php echo $abscisa->Valor / 1000; ?>
	                </td>
	                <?php foreach ($costados as $costado) { ?>
	                	<?php foreach ($tipos_mediciones as $tipo_medicion) { ?>
	                		<?php 
	                		$datos = array(
								"Abscisa" => $abscisa->Valor,
								"Fk_Id_Tipo_Medicion" => $tipo_medicion->Pk_Id,
								"Fk_Id_Costado" => $costado->Pk_Id,
								"Fk_Id_Medicion" => $medicion->Pk_Id,
							);
							?>
	                		<?php $detalle = $this->roceria_model->obtener("medicion_detalle", $datos); ?>
			                	<td class="uk-table-link uk-text-center">
				                    <a class="uk-link-reset" onCLick="javascript:modificarMedicion();">
				                    <?php $color = (isset($detalle->Calificacion)) ? "rgb({$detalle->Color_R}, {$detalle->Color_G}, {$detalle->Color_B})" : "" ; ?>
				                    	<span style="color: <?php echo $color; ?>;">
				                    		<?php echo (isset($detalle->Calificacion)) ? $detalle->Calificacion : "" ; ?>
			                    		</span>
			                    	</a>
				                </td>
						<?php } ?>
					<?php } ?>
	            </tr>
        	<?php } ?>
        </tbody>
    </table>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		cerrar_notificaciones();
		imprimir_notificacion("Medición detenida. Puede editar cualquier calificación de la medición", "success");

		// Botones del menú
		botones(Array("volver"))
	});
</script>