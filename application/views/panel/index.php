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
</div>







<!-- <div class="uk-section uk-section-muted"> -->
<!-- <div class="uk-section">
    <div class="uk-container">
        <h2 class="uk-heading-line uk-text-center"><span>Últimas mediciones</span></h2>
        <div class="uk-grid-match uk-child-width-1-3@m" uk-grid>
            <div>
            	<h4 class="uk-heading-bullet"><span>Hoy</span></h4>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor.</p>
            </div>
            <div>
            	<h4 class="uk-heading-bullet"><span>Esta semana</span></h4>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor.</p>
            </div>
            <div>
            	<h4 class="uk-heading-bullet"><span>Este mes</span></h4>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor.</p>
            </div>
        </div>
    </div>
</div>

<div class="uk-section">
    <div class="uk-container">
        <h2 class="uk-heading-line uk-text-center"><span>Puntos de atención urgente</span></h2>
        <div class="uk-grid-match uk-child-width-1-2@m" uk-grid>
            <div>
            	<h4 class="uk-heading-bullet"><span>Hoy</span></h4>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor.</p>
            </div>
            <div>
            	<h4 class="uk-heading-bullet"><span>Esta semana</span></h4>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor.</p>
            </div>
        </div>
    </div>
</div>

<div class="uk-grid-match uk-grid-small uk-text-center">
    <div class="uk-width-1-1@m">
        <h3 class="uk-heading-line"><span>Puntos de atención urgente</span></h3>
        <div class="uk-grid">
    		<div class="uk-width-1-2@m">
		        <div>
			        <p>1</p>


		    	</div>
		    </div>
		    <div class="uk-width-1-2@m">
		        <div>
			        <p>2</p>


		    	</div>
		    </div>
        </div>
			



    </div>
    <div class="uk-width-1-3@m">
	        <h5 class="uk-heading-bullet"><span>Últimas mediciones | Hoy</span></h5>


    </div>
    <div class="uk-width-1-3@m">
	        <h5 class="uk-heading-bullet"><span>Últimas mediciones | Esta semana</span></h5>

	        
    </div>
    <div class="uk-width-1-3@m">
	        <h5 class="uk-heading-bullet"><span>Últimas mediciones | Este mes</span></h5>


    </div>

    <div class="uk-width-1-4@m">
        <div>1-4@m</div>
    </div>
    <div class="uk-width-1-4@m">
        <div>1-4@m</div>
    </div>
    <div class="uk-width-1-5@m uk-hidden@l">
        <div>1-5@m<br>hidden@l</div>
    </div>
    <div class="uk-width-1-5@m uk-width-1-3@l">
        <div>1-5@m<br>1-3@l</div>
    </div>
    <div class="uk-width-3-5@m uk-width-2-3@l">
        <div>3-5@m<br>2-3@l</div>
    </div>
</div>

<div class="uk-grid-match uk-grid-small uk-text-center" uk-grid>
    <div class="uk-width-auto@m uk-visible@l">
        <div>auto@m<br>visible@l</div>
    </div>
    <div class="uk-width-1-3@m">
        <div>1-3@m</div>
    </div>
    <div class="uk-width-expand@m">
        <div>expand@m</div>
    </div>
</div> -->

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