<div class="uk-column-1-4@s">
    <p>
        <select class="uk-select" id="select_sector" title="sector" autofocus>
        	<option value="">Todos los sectores</option>
        	<?php foreach ($this->configuracion_model->obtener("sectores") as $sector) { ?>
                <option value="<?php echo $sector->Pk_Id ?>"><?php echo $sector->Codigo; ?></option>
        	<?php } ?>
        </select>
    </p>
    
    <p>
        <select class="uk-select" id="select_via" title="vía">
            <option value="">Todas las vías</option>
            <?php foreach ($this->configuracion_model->obtener("vias") as $via) { ?>
                <option value="<?php echo $via->Pk_Id ?>"><?php echo $via->Nombre; ?></option>
            <?php } ?>
        </select>
    </p>
    
    <p>
        <?php $rango = $this->configuracion_model->obtener("rango_abscisado"); ?>
        <select class="uk-select" id="select_km_inicial">
            <?php for ($i = $rango->Minimo; $i < $rango->Maximo; $i++) { ?>
                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
            <?php } ?>
        </select>
    </p>
    
    <p>
        <select class="uk-select" id="select_km_final">
            <?php for ($i = $rango->Minimo; $i < $rango->Maximo; $i++) { ?>
                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
            <?php } ?>
        </select>
    </p>
</div>

<div id="cont_lista"></div>
	
<script type="text/javascript">
    /**
     * Continúa la medición en donde terminó
     * 
     * @return [void]
     */
    function continuar_medicion(id_medicion)
    {
        redireccionar(`<?php echo site_url('roceria/parametrizar'); ?>/${id_medicion}`, `ventana`)
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
    /**
     * Listado de registros
     * 
     * @return {void}
     */
	function listar()
	{
        cerrar_notificaciones();
        imprimir_notificacion("<div uk-spinner></div> Buscando mediciones...");

        console.clear()

        const datos = {}
        const sector = $("#select_sector").val()
        const via = $("#select_via").val()
        const costado = $("#select_costado").val()
        const km_inicial = $("#input_kilometro_inicial").val()
        const km_final = $("#input_kilometro_final").val()

        if (sector) datos.Fk_Id_Sector = sector
        if (via) datos.Fk_Id_Via = via
		
        cargar_interfaz("cont_lista", "<?php echo site_url('mediciones/cargar_interfaz'); ?>", {"tipo": "mediciones_lista", "datos": datos});
	}

	$(document).ready(function(){
		listar()

        $("select").on("change", listar)
	})
</script>