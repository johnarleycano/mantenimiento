<div class="uk-column-1-4@s" id="cont_filtros">
	<p>
	    <select class="uk-select" id="select_tipo_medicion" title="tipo de medición" autofocus>
	    	<?php foreach ($this->configuracion_model->obtener("tipos_mediciones") as $tipo) { ?>
	            <option value="<?php echo $tipo->Pk_Id ?>"><?php echo $tipo->Nombre; ?></option>
	    	<?php } ?>
	    </select>
	</p>

	<p>
	    <select class="uk-select" id="select_sector" title="sector">
	    	<option value="0">Todos los sectores</option>
	    	<?php foreach ($this->configuracion_model->obtener("sectores") as $sector) { ?>
	            <option value="<?php echo $sector->Pk_Id ?>"><?php echo $sector->Codigo; ?></option>
	    	<?php } ?>
	    </select>
	</p>

	<p>
	    <select class="uk-select" id="select_via" title="vía">
	    	<option value="0">Elija primero un sector...</option>
	    </select>
	</p>

	<p>
	    <select class="uk-select" id="select_calificacion" title="vía">
	    	<option value="0">Todas las calificaciones</option>
	    	<?php foreach ($this->configuracion_model->obtener("calificaciones") as $calificacion) { ?>
	            <option value="<?php echo $calificacion->Valor ?>"><?php echo $calificacion->Descripcion; ?></option>
	    	<?php } ?>
	    </select>
	</p>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		$("select").on("change", function(){
			resumen_mediciones()

			// Se guarda el filtro del usuario
			guardar_filtros(1, "<?php echo $this->session->userdata('Pk_Id_Usuario'); ?>")	
		})

		$("#select_sector").on("change", function(){
			// Se consultan las vías del sector
			datos = {
				url: "<?php echo site_url('configuracion/obtener'); ?>",
				tipo: "vias",
				id: $(this).val(),
				elemento_padre: $("#select_sector"),
				elemento_hijo: $("#select_via"),
				mensaje_padre: "Elija primero un sector",
				mensaje_hijo: "Todas las vías"
			}
			cargar_lista_desplegable(datos)
		})
	})
</script>