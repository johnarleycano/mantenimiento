<!-- <input type="hidden" id="ultima_medicion">
<input type="hidden" id="calificacion">
<input type="hidden" id="id_medicion"> -->

<div id="cont_panel">
	<!-- Gráfico de resumen de mediciones -->
	<div class="uk-column-1-1">
	    <p>
			<div class="ui padded segment uk-column-1-2@m" id="cont_filtros">
				<div class="ui green segment">
				    <select class="uk-select uk-form-small" id="select_tipo_medicion_resumen" title="tipo de medición">
				    	<?php foreach ($this->configuracion_model->obtener("tipos_mediciones") as $tipo) { ?>
				            <option value="<?php echo $tipo->Pk_Id ?>"><?php echo $tipo->Nombre; ?></option>
				    	<?php } ?>
				    </select>
			    </div>

			    <div class="ui green segment">
				    <select class="uk-select uk-form-small" id="select_calificacion" title="vía">
				    	<option value="0">Todas las calificaciones</option>
				    	<?php foreach ($this->configuracion_model->obtener("calificaciones") as $calificacion) { ?>
				            <option value="<?php echo $calificacion->Valor ?>"><?php echo $calificacion->Descripcion; ?></option>
				    	<?php } ?>
				    </select>
			    </div>
			</div>

			<div id="cont_grafico_resumen_mediciones" class="uk-margin-small"></div>
	    </p>
	</div>

	<!-- Mapa detalle de medición -->
	<div class="uk-column-1-1">
	    <p>
			<div class="ui padded segment uk-column-1-2@m" id="cont_filtros">
				<div class="ui green segment">
				    <select class="uk-select uk-form-small" id="select_tipo_medicion_mapa" title="tipo de medición">
				    	<?php foreach ($this->configuracion_model->obtener("tipos_mediciones") as $tipo) { ?>
				            <option value="<?php echo $tipo->Pk_Id ?>"><?php echo $tipo->Nombre; ?></option>
				    	<?php } ?>
				    </select>
			    </div>

			    <div class="ui green segment">
				    <select class="uk-select uk-form-small" id="select_medicion" title="medición"></select>
			    </div>
			</div>

			<div id="cont_mapa_mediciones" class="uk-margin-small">
				<p></p>
				<iframe src="<?php echo $this->config->item('mapa_url').'zoom=11'; ?>" width="100%" height="460"></iframe>
			</div>
	    </p>
	</div>

	<!-- Gráfico de mediciones -->
    <div class="uk-column-1-2@m">
    	<div id="cont_mediciones"></div>

    	<!-- <iframe src="<?php //echo $this->config->item('mapa_url').'zoom=11'; ?>" width="100%" height="460"></iframe> -->
    </div>
    
    <!-- <div class="uk-grid-match uk-child-width-1-2@m" uk-grid> -->
    	<!-- Puntos críticos -->
        <!-- <div>
        	<div class="uk-margin-medium-top">
        				    <ul class="uk-flex-center" uk-tab>
        				    	se recorren las calificaciones críticas
        				    	<?php // foreach ($this->configuracion_model->obtener("calificaciones_criticas") as $calificacion) { ?>
        				    		<li>
        					        	<a onCLick="javascript:mediciones_urgentes(<?php//  echo $calificacion->Valor; ?>);">
        					        		<span class="uk-label" style="background-color: rgb(<?php // echo $calificacion->Color_R; ?>, <?php // echo $calificacion->Color_G; ?>, <?php//  echo $calificacion->Color_B; ?>);" id="calificacion_<?php // echo $calificacion->Valor; ?>"><?php // echo $calificacion->Descripcion; ?></span>
        					        	</a>
        					        </li>
        				    	<?php // } ?>
        				    </ul>
                <div id="cont_mediciones_urgentes"></div>
            </div>
        </div> -->

        <!-- Últimas mediciones -->
       <!--  <div>
        	<div class="uk-margin-medium-top">
			    <ul class="uk-flex-center" uk-tab>
			        <li class="uk-active">
			        	<a onCLick="javascript:ultimas_mediciones('hoy');">Hoy</a>
			        </li>
			        <li>
		        		<a onCLick="javascript:ultimas_mediciones('semana');">La última semana</a>
			    	</li>
			        <li>
		        		<a onCLick="javascript:ultimas_mediciones('mes');">El último mes</a>
			    	</li>
			    </ul>
                <div id="cont_ultimas_mediciones"></div>
            </div>
        </div> -->
    <!-- </div> -->

    <div id="cont_modal"></div>
</div>

<script type="text/javascript">
	function actualizar_mediciones()
	{
		// Se consultan las mediciones
        datos = {
            url: "<?php echo site_url('mediciones/obtener'); ?>",
            tipo: "mediciones",
            id: $("#select_via_filtro").val(),
            elemento_padre: $("#select_via_filtro"),
            elemento_hijo: $("#select_medicion"),
            mensaje_padre: "Elija primero una vía",
            mensaje_hijo: "Elija una medición"
        }
        cargar_lista_desplegable(datos)
	}

	/**
	 * Genera el reporte en PDF en una pestaña adicional
	 * 
	 * @return [void]
	 */
	function generar_pdf(id_medicion)
	{
		redireccionar(`<?php echo site_url('reportes/pdf/medicion'); ?>/${id_medicion}`, "ventana");
	}

	function mapa_mediciones()
	{
		// Filtros principales
		let id_sector = ($("#select_sector_filtro").val()) ? $("#select_sector_filtro").val() : null
		let id_via = ($("#select_via_filtro").val()) ? $("#select_via_filtro").val() : null
		let id_costado = ($("#select_costado_filtro").val()) ? $("#select_costado_filtro").val() : null

		// Filtros específicos
		let id_tipo_medicion = $("#select_tipo_medicion_mapa").val();
		let id_medicion = ($("#select_medicion").val() != 0) ? $("#select_medicion").val() : null;

		// URLs
		let url_anterior = $("#cont_mapa_mediciones > iframe").attr("src")
        let url_nueva = url_anterior.replace('zoom=11', `zoom=11&via=${id_via}&medicion=${id_medicion}&tipo=${id_tipo_medicion}&costado=${id_costado}`)

        // Si tiene medición seleccionada
        if(id_medicion){
        	// Muestra el mapa
        	$("#cont_mapa_mediciones > iframe").attr("src", url_nueva)
    	} else {
    		// Mensaje
    		$("#cont_mapa_mediciones>p").text("Elija una medición para ver el mapa")
    	}
		// $("#cont_mapa_mediciones > iframe").attr("src" , function(i, val){return val})
	}

	/**
	 * Carga la interfaz con las zonas que necesitan atención
	 * debido a su baja calificación
	 * 
	 * @param  [string] calificacion 	[1,2]
	 * 
	 * @return [void]       	
	 */
	function mediciones_urgentes(calificacion)
	{
        // Se carga el valor a consultar en un input y se carga la interfaz en el panel
        $("#calificacion").val(calificacion);
		cargar_interfaz("cont_mediciones_urgentes", "<?php echo site_url('panel/cargar_interfaz'); ?>", {"tipo": "mediciones_urgentes", "calificacion": calificacion});
	}

	/**
	 * Carga la interfaz con el resumen de las mediciones
	 * por sectores
	 * 
	 * @return [void]       	
	 */
	function resumen_mediciones()
	{
		$("#cont_grafico_resumen_mediciones").load("<?php echo site_url('reportes/graficos'); ?>", {"tipo": "resumen_mediciones"})
	}

	/**
	 * Carga la interfaz con las últimas mediciones realizadas
	 * 
	 * @param  [string] fecha 	[hoy, semana, mes]
	 * 
	 * @return [void]       	
	 */
	function ultimas_mediciones(fecha)
	{
		// Se carga el valor a consultar en un input y se carga la interfaz en el panel
		$("#ultima_medicion").val(fecha);
		cargar_interfaz("cont_ultimas_mediciones", "<?php echo site_url('panel/cargar_interfaz'); ?>", {"tipo": "ultimas_mediciones", "fecha": fecha});
	}

	/**
	 * Ventana emergente que muestra detalles de una medición
	 * 
	 * @param  [int] id_medicion  [id de la medición]
	 * @param  [int] calificacion [calificación]
	 * 
	 * @return [void]
	 */
    function ver_detalle(id_medicion, calificacion)
    {
        cargar_interfaz("cont_modal", "<?php echo site_url('panel/cargar_interfaz'); ?>", {"tipo": "detalle_medicion", "id_medicion": id_medicion, "calificacion": calificacion});
    }

	$(document).ready(function(){
		// Filtros principales
		let id_sector = ($("#select_sector_filtro").val()) ? $("#select_sector_filtro").val() : 0
		let id_via = ($("#select_via_filtro").val()) ? $("#select_via_filtro").val() : 0
		let id_costado = ($("#select_costado_filtro").val()) ? $("#select_costado_filtro").val() : 0
		
		// Cuando se cambien los filtros generales
		$("select[id$=filtro]").on("change", function(){
			resumen_mediciones()
			actualizar_mediciones()
			mapa_mediciones()
		})

		$("#select_tipo_medicion_resumen, #select_calificacion").on("change", function(){
			resumen_mediciones()

			// Se guarda el filtro, indicando el módulo
            guardar_filtros(1, "<?php echo $this->session->userdata('Pk_Id_Usuario'); ?>")
		})

		$("#select_tipo_medicion_mapa, #select_medicion").on("change", function(){
			mapa_mediciones()

			// Se guarda el filtro, indicando el módulo
            guardar_filtros(1, "<?php echo $this->session->userdata('Pk_Id_Usuario'); ?>")
		})

		// Carga inicial de los datos
		resumen_mediciones()
		mapa_mediciones()
		actualizar_mediciones()

		// Activación del filtro superior
		filtro_superior()
		
		// setInterval(function(){
		// 	// Aquí se consultará si hay cambios en las tablas
			
		// }, 1000)
		
		// mediciones_urgentes(1)
		// ultimas_mediciones("hoy")

		// Botones del menú
		// botones()
	});
</script>