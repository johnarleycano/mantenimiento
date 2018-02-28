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
        // Se consulta la abscisa donde terminó la medición
        medicion = ajax("<?php echo site_url('mediciones/obtener'); ?>", {"tipo": "medicion", "id": id_medicion}, 'JSON')

        // Se obtiene las abscisas límite de la última medición
        medicion_abscisa = ajax("<?php echo site_url('mediciones/obtener'); ?>", {"tipo": "abscisas_limite", "id": id_medicion}, 'JSON')

        // Si el orden es ascendente, la abscisa será la mayor, sino, será la menor
        abscisa = (medicion.Orden == 1) ? medicion_abscisa.Mayor + 1000 : medicion_abscisa.Menor - 1000

        // roceria/medir/id_medicion/posicion/abscisa/orden
        redireccionar(`<?php echo site_url('roceria/medir'); ?>/${id_medicion}/1/${abscisa}/${medicion.Orden}`)
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
        
        imprimir(datos, "tabla")

		cargar_interfaz("cont_lista", "<?php echo site_url('mediciones/cargar_interfaz'); ?>", {"tipo": "mediciones_lista", "datos": datos});
	}

	$(document).ready(function(){
		listar()

        $("select").on("change", listar)
	})
</script>