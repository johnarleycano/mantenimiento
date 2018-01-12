<div id="modal_detalles" class="modal" uk-modal>
	<div class="uk-modal-dialog">
    	<form class="uk-form-horizontal uk-margin-large">
	        <button class="uk-modal-close-default" type="button" uk-close></button>

	        <div class="uk-modal-header">
	            <h3 class="uk-modal-title">Puntos con calificación <?php echo $calificacion; ?> en la medición</h3>
	        </div>

	        <div class="uk-modal-body" uk-overflow-auto>
	        	<?php
	        	$medicion = $this->roceria_model->obtener("medicion", $id_medicion);
	        	$puntos_criticos = $this->panel_model->obtener("puntos_criticos_medicion", array("d.Fk_Id_Medicion" => $id_medicion, "d.Calificacion" => $calificacion));
	        	$num = 1;
	        	?>
				<ul class="uk-list uk-list-striped">
				    <li>
				    	<b><?php echo "$medicion->Sector | $medicion->Via"; ?></b><br>
						<?php echo $this->configuracion_model->obtener("formato_fecha", $medicion->Fecha_Inicial); ?>
				    </li>

				    <div class="uk-overflow-auto">
				    	<table class="uk-table uk-table-striped">
						    <thead>
						        <tr>
						            <th class="uk-text-center">#</th>
						            <th class="uk-text-center">Km</th>
						            <th class="uk-text-center">Medición</th>
						            <th class="uk-text-center">Costado</th>
						        </tr>
						    </thead>
						    <tbody>
						    	<?php foreach ($puntos_criticos as $punto) { ?>
							        <tr>
							        	<td class="uk-text-right"><?php echo $num++; ?></td>
							            <td class="uk-text-right"><?php echo ($punto->Abscisa / 1000); ?></td>
							            <td><?php echo $punto->Tipo_Medicion; ?></td>
							            <td><?php echo $punto->Costado; ?></td>
							        </tr>
								<?php } ?>
						    </tbody>
						</table>
					</div>
				</ul>
	        </div>

        	<div class="uk-modal-footer uk-text-right">
	            <button class="uk-button uk-button-default uk-modal-close" type="button">Cancelar</button>
	            <button class="uk-button uk-button-primary" type="submit">Guardar</button>
	        </div>
		</form>
    </div>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		$("#id_medicion").val("<?php echo $id_medicion; ?>");

		// Botones del menú
		botones(Array("pdf"));

		UIkit.modal("#modal_detalles").show();

		UIkit.util.on('#modal_detalles', 'hide', function () {
			botones();
		});

	});
</script>