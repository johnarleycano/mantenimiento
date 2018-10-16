<!-- Inputs para almacenar los límites de kilómetros -->
<input type="hidden" id="kilometro_inicial">
<input type="hidden" id="kilometro_final">

<div id="cont_roceria" class="uk-container uk-container-small">
	<h3 class="ui dividing header">
		<center><?php echo ($this->uri->segment(3)) ? "Reanudar la medición" : "Parametrizar el inicio de una nueva medición" ; ?></center>
	</h3>
	
	<form class="uk-form-horizontal uk-margin-small">
		<!-- Sector -->
	    <div class="uk-margin">
        	<label class="uk-form-label" for="select_sector">Sector</label>
	        <div class="uk-form-controls">
	            <select class="uk-select" id="select_sector" title="sector" autofocus>
	            	<option value="">Elija...</option>
	            	<?php foreach ($this->configuracion_model->obtener("sectores") as $sector) { ?>
		                <option value="<?php echo $sector->Pk_Id ?>"><?php echo $sector->Codigo; ?></option>
	            	<?php } ?>
	            </select>
	        </div>
	    </div>
		
		<!-- Vía -->
	    <div class="uk-margin">
        	<label class="uk-form-label" for="select_via">Vía</label>
	        <div class="uk-form-controls">
	            <select class="uk-select" id="select_via" title="vía">
	            	<option value="">Elija primero un sector...</option>
	            </select>
	        </div>
	    </div>
		
		<!-- Orden -->
	    <div class="uk-margin">
        	<label class="uk-form-label" for="select_orden">Orden para medir</label>
	        <div class="uk-form-controls">
	        	<select class="uk-select" id="select_orden" title="vía">
	            	<option value="1">Ascendente</option>
	            	<option value="2">Descendente</option>
	            </select>
	        </div>
	    </div>
		
		<!-- Abscisa de inicio -->
	    <div class="uk-margin">
        	<label class="uk-form-label" for="input_kilometro_inicio">Kilómetro de inicio</label>
	        <div class="uk-form-controls">
	            <input class="uk-input" type="number" id="input_kilometro_inicio" title="kilómetro de inicio" placeholder="Elja primero una vía" disabled>
	        </div>
	    </div>
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
			"select_via": $("#select_via").val(),
			"input_kilometro_inicio": $("#input_kilometro_inicio").val(),
		}
		// imprimir(campos_obligatorios);

		// Si existen campos obligatorios sin diligenciar
		if(validar_campos_obligatorios(campos_obligatorios)){
			return false;
		}

		// Si el kilómetro seleccionado es mayor al kilómetro final o menor al kilómetro inicial
		if ($("#input_kilometro_inicio").val() < $("#kilometro_inicial").val() || parseFloat($("#input_kilometro_inicio").val()) > $("#kilometro_final").val()) {
			cerrar_notificaciones();
			imprimir_notificacion(`El kilómetro de inicio debe estar entre ${$("#kilometro_inicial").val()} y ${$("#kilometro_final").val()}`, `danger`);

			return false;
		}

	    datos = {
	    	"Abscisa_Inicial": $("#kilometro_inicial").val()*1000,
	    	"Abscisa_Final": $("#kilometro_final").val()*1000,
	    	"Fk_Id_Via": $("#select_via").val(),
	    	"Fecha_Inicial": "<?php echo date("Y-m-d H:i:s"); ?>",
	    	"Fk_Id_Usuario": "<?php echo $this->session->userdata('Pk_Id_Usuario'); ?>",
	    	"Orden": $("#select_orden").val()
	    }
	    // imprimir(datos)
	    
	    // Si es una medición existente
	    if("<?php echo $this->uri->segment(3); ?>") {
	    	// Medición existente
	    	var id_medicion = "<?php echo $this->uri->segment(3); ?>"

	    	// Inserción de log
	    	ajax("<?php echo site_url('mediciones/insertar'); ?>", {"tipo": "medicion_continuar_log", "id": id_medicion}, 'HTML')
	    } else {
	    	// Nueva medición
        	var id_medicion = ajax("<?php echo site_url('mediciones/insertar'); ?>", {"tipo": "medicion", "datos": datos}, 'HTML')
	    }

		// Se carga la interfaz de medición
		redireccionar(`<?php echo site_url('mediciones/roceria'); ?>/${id_medicion}/1/${$("#input_kilometro_inicio").val()*1000}/${$("#select_orden").val()}`);
		return false
	}

	$(document).ready(function(){
		// Ocultar el filtro superior
		$("#filtro_superior").hide()

		// Botones del menú
		botones(Array("iniciar"))

		$("form").on("submit", function(){
			iniciar_medicion()

			return false
		});

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
			// Se desactiva el input y se quita el valor
			$("#input_kilometro_inicio").val("").attr("disabled", true)

			// Si seleccióno una vía, se obtienen los datos
			if($("#select_via").val() != ""){
				via = ajax("<?php echo site_url('configuracion/obtener'); ?>", {"tipo": "via", "id": $(this).val()}, "JSON")

				// Se almacenan en inputs los valores de kilómetros inicial y final
				$("#kilometro_inicial").val(via.Kilometro_Inicial)
				$("#kilometro_final").val(via.Kilometro_Final)

				// Se activa el input y se pone valor mínimo
				$("#input_kilometro_inicio").val(via.Kilometro_Inicial).removeAttr("disabled")
				$("#select_orden").focus()
			}
		});

		// Al seleccionar el orden, cambia los valores límite del kilómetro de inicio
		$("#select_orden").on("change", function(){
			($(this).val() == 1) ? $("#input_kilometro_inicio").val($("#kilometro_inicial").val()) : $("#input_kilometro_inicio").val($("#kilometro_final").val())

			$("#input_kilometro_inicio").focus()
		});
	});
</script>

<!-- Si trae id de medición -->
<?php if($this->uri->segment(3)){ ?>
	<script type="text/javascript">
		let id_medicion = "<?php echo $this->uri->segment(3); ?>"

		// Se consulta la abscisa donde terminó la medición
        medicion = ajax("<?php echo site_url('mediciones/obtener'); ?>", {"tipo": "medicion", "id": id_medicion}, 'JSON')

		select_por_defecto("select_sector", medicion.Fk_Id_Sector)
		
		// Se consultan las vías del sector
		datos = {
			url: "<?php echo site_url('configuracion/obtener'); ?>",
			tipo: "vias",
			id: medicion.Fk_Id_Sector,
			elemento_padre: $("#select_sector"),
			elemento_hijo: $("#select_via"),
			mensaje_padre: "Elija primero un sector",
			mensaje_hijo: "Elija una vía"
		}
		cargar_lista_desplegable(datos)

		via = ajax("<?php echo site_url('configuracion/obtener'); ?>", {"tipo": "via", "id": medicion.Fk_Id_Via}, "JSON")
		
		$("#kilometro_inicial").val(via.Kilometro_Inicial)
		$("#kilometro_final").val(via.Kilometro_Final)

		// Selects por defecto
		select_por_defecto("select_via", medicion.Fk_Id_Via)
		select_por_defecto("select_orden", medicion.Orden)

		// Deshabilitar campos
		$("#select_sector, #select_via").attr("disabled", true)

		// Se obtiene las abscisas límite de la última medición
        medicion_abscisa = ajax("<?php echo site_url('mediciones/obtener'); ?>", {"tipo": "abscisas_limite", "id": id_medicion}, 'JSON')
        // imprimir(medicion_abscisa, "tabla")

		// Si es ascendente
        kilometro = (medicion.Orden == 1) ? parseFloat(medicion_abscisa.Mayor) / 1000 : parseFloat(medicion_abscisa.Menor) / 1000
		// imprimir(kilometro)

		$("#input_kilometro_inicio").val(kilometro).removeAttr("disabled")
	</script>
<?php } ?>