<input type="hidden" id="ultima_medicion">
<input type="hidden" id="calificacion">

<div class="uk-section">
    <div class="uk-container">
        <div class="uk-grid-match uk-child-width-1-2@m" uk-grid>
            <div>
            	<div class="uk-margin-medium-top">
				    <ul class="uk-flex-center" uk-tab>
				        <li class="uk-active">
				        	<a onCLick="javascript:mediciones_urgentes(1);"><span class="uk-label uno"><?php echo $this->configuracion_model->obtener("nombre_calificacion", 1) ?></span></a>
				        </li>
				        <li>
				        	<a onCLick="javascript:mediciones_urgentes(2);"><span class="uk-label dos"><?php echo $this->configuracion_model->obtener("nombre_calificacion", 2) ?></span></a>
				        </li>
				    </ul>
                    <div id="cont_mediciones_urgentes"></div>
                </div>
            </div>
            <div>
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
            </div>
        </div>
    </div>

    <div id="cont_modal"></div>
</div>

<script type="text/javascript">
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

    function ver_detalle(id_medicion, calificacion)
    {
        imprimir(id_medicion)
        cargar_interfaz("cont_modal", "<?php echo site_url('panel/cargar_interfaz'); ?>", {"tipo": "detalle_medicion", "id_medicion": id_medicion, "calificacion": calificacion});
    }

	$(document).ready(function(){
		setInterval(function(){
			mediciones_urgentes($("#calificacion").val());
			ultimas_mediciones($("#ultima_medicion").val());
		}, 1000);

		mediciones_urgentes(1);
		ultimas_mediciones("hoy");

		// Botones del menú
		botones();
	});
</script>