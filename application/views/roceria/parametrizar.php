<div id="cont_roceria" class="uk-container uk-container-small">
	<form class="uk-form-horizontal uk-margin-large">
		<!-- Sector -->
	    <div class="uk-margin">
        	<label class="uk-form-label" for="form-horizontal-select">Sector</label>
	        <div class="uk-form-controls">
	            <select class="uk-select" id="select_sector" title="sector" autofocus>
	            	<option value="">Elija...</option>
	            	<?php foreach ($this->configuracion_model->obtener("sectores") as $sector) { ?>
		                <option value="<?php echo $sector->Pk_Id ?>"><?php echo $sector->Nombre; ?></option>
	            	<?php } ?>
	            </select>
	        </div>
	    </div>
		
		<!-- Vía -->
	    <div class="uk-margin">
        	<label class="uk-form-label" for="form-horizontal-select">Vía</label>
	        <div class="uk-form-controls">
	            <select class="uk-select" id="select_via" title="vía">
	            	<option value="">Elija primero un sector...</option>
	            </select>
	        </div>
	    </div>

	    <!-- Vía -->
	    <div class="uk-margin">
        	<label class="uk-form-label" for="form-horizontal-select">Calzadas a medir</label>
	        <div class="uk-form-controls">
	        	<div id="cont_calzadas">Elija primero una vía</div>
	        </div>
	    </div>
		
		<!-- Barra para el abscisado -->
    	<div id="cont_abscisado" class="uk-margin"></div>
    </form>
</div>

<script type="text/javascript">
	/**
	 * Inicia la medición con los datos obtenidos
	 * del formulario
	 * 
	 * @return [void]
	 */
	function iniciar_medicion(){
		cerrar_notificaciones();
		imprimir_notificacion("<div uk-spinner></div> Iniciando medición...");

		campos_obligatorios = {
			"select_sector": $("#select_sector").val(),
			"select_via": $("#select_via").val()
		}
		// imprimir(campos_obligatorios);

		// Si existen campos obligatorios sin diligenciar
		if(validar_campos_obligatorios(campos_obligatorios)){
			return false;
		}

	    datos = {
	    	"Abscisa_Inicial": $("#abscisa_inicial").val(),
	    	"Abscisa_Final": $("#abscisa_final").val(),
	    	"Fk_Id_Via": $("#select_via").val(),
	    	"Fk_Id_Via": $("#select_via").val(),
	    	"Fecha_Inicial": "<?php echo date("Y-m-d h:i:s"); ?>",
	    	"Fk_Id_Usuario": "<?php echo $this->session->userdata('Pk_Id_Usuario'); ?>"
	    }
	    // imprimir(datos);

        id_medicion_temporal = ajax("<?php echo site_url('roceria/insertar'); ?>", {"tipo": "medicion_temporal", "datos": datos}, 'HTML');
        // imprimir(id_medicion_temporal);
        
        // Url con el inicio de la medición
        url = "<?php echo site_url('roceria/medir') ?>" + "/" + id_medicion_temporal + "/1" + "/" + $("#abscisa_inicial").val() + "/" + $("#abscisa_final").val();

		// Se carga la interfaz de medición
		redireccionar(url);

		return false;
	}

	$(document).ready(function(){
		// Botones del menú
		botones(Array("iniciar"));

		// Se carga el rango de abscisado por defecto
		cargar_interfaz("cont_abscisado", "<?php echo site_url('roceria/cargar_interfaz'); ?>", {"tipo": "rango_abscisado", "id_via": "0"});

		// Cuando se elija el sector, se cargan las vías de
		// ese sector
		$("#select_sector").on("change", function(){
			datos = {
				url: "<?php echo site_url('configuracion/obtener'); ?>",
				tipo: "vias",
				id: $(this).val(),
				elemento_padre: $("#select_sector"),
				elemento_hijo: $("#select_via"),
				mensaje_padre: "Elija primero un sector",
				mensaje_hijo: "Elija una vía"
			}
			cargar_lista_desplegable(datos);
		});

		// Al seleccionar una vía, carga una interfaz con el rango del abscisado
		$("#select_via").on("change", function(){
			// Se cargan los abscisados
			cargar_interfaz("cont_abscisado", "<?php echo site_url('roceria/cargar_interfaz'); ?>", {"tipo": "rango_abscisado", "id_via": $(this).val()});
		});
	});
</script>