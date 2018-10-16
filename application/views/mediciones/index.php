<div id="cont_lista"></div>
	
<script type="text/javascript">
    /**
     * Continúa la medición en donde terminó
     * 
     * @return [void]
     */
    function continuar_medicion(id_medicion)
    {
        redireccionar(`<?php echo site_url('mediciones/parametrizar'); ?>/${id_medicion}`, `ventana`)
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
        imprimir_notificacion("<div uk-spinner></div> Buscando mediciones...")

        const datos = {}
        const sector = ($("#select_sector_filtro").val() != 0) ? datos.Fk_Id_Sector = $("#select_sector_filtro").val() : null
        const via = ($("#select_via_filtro").val() != 0) ? datos.Fk_Id_Via = $("#select_via_filtro").val() : null

        cargar_interfaz("cont_lista", "<?php echo site_url('mediciones/cargar_interfaz'); ?>", {"tipo": "mediciones_lista", "datos": datos});
	}

	$(document).ready(function(){
		listar()

        $("select").on("change", listar)

        // Activación del filtro superior
        filtro_superior()
	})
</script>