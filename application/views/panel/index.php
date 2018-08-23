<input type="hidden" id="ultima_medicion">
<input type="hidden" id="calificacion">
<input type="hidden" id="id_medicion">

<div class="uk-section" id="cont_panel">
    <div class="uk-container">
    	<!-- Filtros del panel -->
		<div class="uk-grid-match uk-child-width-1-1@m" uk-grid>
			<div id="cont_filtros_panel"></div>
		</div>
		
		<!-- Gráfico de mediciones -->
        <div class="uk-grid-match uk-child-width-1-1@m" uk-grid>
        	<div id="cont_mediciones"></div>
        </div>

        <!-- Mapa de calor -->
        <div class="uk-grid-match uk-child-width-1-1@m" id="cont_mapa" uk-grid>
        	<center><h3>Última medición</h3></center>
        	<iframe src="<?php echo $this->config->item('mapa_url').'zoom=11'; ?>" height="460"></iframe>
        </div>
        
        <div class="uk-grid-match uk-child-width-1-2@m" uk-grid>
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
        </div>
    </div>

    <div id="cont_modal"></div>
</div>

<script type="text/javascript">
	/**
	 * Genera el reporte en PDF en una pestaña adicional
	 * 
	 * @return [void]
	 */
	function generar_pdf(id_medicion)
	{
		redireccionar(`<?php echo site_url('reportes/pdf/medicion'); ?>/${id_medicion}`, "ventana");
	}

	function mapa_mediciones(id_via, id_tipo_medicion)
	{
		// Se consulta la última medición de la vía
		ultima_medicion = ajax("<?php echo site_url('mediciones/obtener'); ?>", {"tipo": "ultima_medicion", "id": id_via}, 'JSON')
		
		var oldSrc = $("#cont_mapa iframe").attr("src")
        var newSrc = oldSrc.replace("zoom=11", `zoom=11&medicion=${ultima_medicion.Pk_Id}&tipo=${id_tipo_medicion}`)
          
        $("#cont_mapa iframe").attr("src", newSrc)
          
		// $("iframe").attr("src" , function(i, val){return val})
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
		cargar_interfaz("cont_mediciones", "<?php echo site_url('panel/cargar_interfaz'); ?>", {"tipo": "resumen_mediciones"})
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
		setInterval(function(){
			// mediciones_urgentes($("#calificacion").val());
			// ultimas_mediciones($("#ultima_medicion").val());
		}, 1000);
		
        cargar_interfaz("cont_filtros_panel", "<?php echo site_url('configuracion/cargar_interfaz'); ?>", {"tipo": "filtros"});

		resumen_mediciones()
		// mapa_mediciones()
		// mediciones_urgentes(1)
		// ultimas_mediciones("hoy")

		// Botones del menú
		botones();
	});
</script>