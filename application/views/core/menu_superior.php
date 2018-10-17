<nav id="menu_superior" class="uk-navbar uk-navbar-container uk-margin" uk-navbar>
    <!-- Ícono que activa el menú lateral -->
    <a class="uk-navbar-toggle" href="#" uk-toggle="target: #offcanvas-nav" title="Visualice el menú principal" uk-tooltip="pos: right">
        <span uk-navbar-toggle-icon></span>
    </a>

    <!-- Filtro superior -->
    <div class="uk-navbar-center uk-hidden" id="filtro_superior">
        <!-- Sector -->
        <select class="uk-select uk-form-width-medium uk-form-small" id="select_sector_filtro">
            <option value="0">Todos los sectores</option>
            <?php foreach ($this->configuracion_model->obtener("sectores") as $sector) { ?>
                <option value="<?php echo $sector->Pk_Id; ?>"><?php echo "$sector->Codigo"; ?></option>
            <?php } ?>
        </select>

        <!-- Vía -->
        <select class="uk-select uk-form-width-medium uk-margin-small-left uk-form-small" id="select_via_filtro">
            <option value="0">Elija primero un sector...</option>
        </select>
    </div>
    
    <!-- Menú derecho -->
    <div class="uk-navbar-right">
        <ul class="uk-iconnav">
            <!-- Guardar -->
            <li id="icono_guardar" class="uk-hidden">
                <a onClick="#" uk-icon="icon: check; ratio: 1.5" title="Guardar" uk-tooltip="pos: bottom-left"></a>
            </li>
            
            <!-- Editar -->
            <li id="icono_editar" class="uk-hidden">
                <a onClick="#" uk-icon="icon: file-edit; ratio: 1.5" title="Editar" uk-tooltip="pos: bottom-center"></a>
            </li>
            
            <!-- Eliminar -->
            <li id="icono_eliminar" class="uk-hidden">
                <a onClick="#" uk-icon="icon: trash; ratio: 1.5" title="Eliminar" uk-tooltip="pos: bottom-left"></a>
            </li>
            
            <!-- Iniciar medición -->
            <li id="icono_iniciar" class="uk-hidden">
                <a onClick="javascript:iniciar_medicion()" uk-icon="icon: play; ratio: 1.5" title="Iniciar medición" uk-tooltip="pos: bottom-left"></a>
            </li>
            
            <!-- Atrás medición -->
            <li id="icono_anterior" class="uk-hidden">
                <a onClick="javascript:continuar('atras')" uk-icon="icon: chevron-left; ratio: 1.5" title="Anterior" uk-tooltip="pos: bottom-left"></a>
            </li>
            
            <!-- Detener medición -->
            <li id="icono_detener" class="uk-hidden">
                <a onClick="javascript:detener()" uk-icon="icon: minus-circle; ratio: 1.5" title="Detener medición" uk-tooltip="pos: bottom-left"></a>
            </li>
            
            <!-- Adelante -->
            <li id="icono_siguiente" class="uk-hidden">
                <a onClick="javascript:continuar('adelante')" uk-icon="icon: chevron-right; ratio: 1.5" title="Siguiente" uk-tooltip="pos: bottom-left"></a>
            </li>
            
            <!-- PDF -->
            <li id="icono_pdf" class="uk-hidden">
                <a onClick="javascript:generar_pdf()" uk-icon="icon: copy; ratio: 1.5" title="Imprimir reporte en PDF" uk-tooltip="pos: bottom-center"></a>
            </li>

            <!-- Volver -->
            <li id="icono_volver" class="uk-hidden">
                <a onClick="javascript:volver()" uk-icon="icon: reply; ratio: 1.5" title="Volver al panel" uk-tooltip="pos: bottom-center"></a>
            </li>
        </ul>
    </div>
</nav>

<script type="text/javascript">
    $(document).ready(function(){
        // Consulta de los datos del filtro
        let filtro = ajax("<?php echo site_url('configuracion/obtener'); ?>", {"tipo": "filtro", "id": "<?php echo $this->session->userdata('Pk_Id_Usuario'); ?>"}, 'JSON')

        // Si tiene un filtro previo guardado
        if(filtro){
            // Sector por defecto
            select_por_defecto("select_sector_filtro", filtro.Fk_Id_Sector)

            // Se consultan las vías del sector
            datos = {
                url: "<?php echo site_url('configuracion/obtener'); ?>",
                tipo: "vias",
                id: filtro.Fk_Id_Sector,
                elemento_padre: $("#select_sector_filtro"),
                elemento_hijo: $("#select_via_filtro"),
                mensaje_padre: "Elija primero un sector",
                mensaje_hijo: "Todas las vías"
            }
            cargar_lista_desplegable(datos)

            // Vía por defecto
            select_por_defecto("select_via_filtro", filtro.Fk_Id_Via)

            // Se consultan los costados de la vía
            datos = {
                url: "<?php echo site_url('configuracion/obtener'); ?>",
                tipo: "costados",
                id: filtro.Fk_Id_Via,
                elemento_padre: $("#select_via_filtro"),
                elemento_hijo: $("#select_costado_filtro"),
                mensaje_padre: "Elija primero una vía",
                mensaje_hijo: "Todos los costados"
            }
            cargar_lista_desplegable(datos)
            
            // Costado por defecto
            select_por_defecto("select_costado_filtro", filtro.Fk_Id_Costado)

            // Otros filtros por defecto
            select_por_defecto("select_tipo_medicion_resumen", filtro.Fk_Id_Tipo_Medicion_Resumen)
            select_por_defecto("select_calificacion", filtro.Calificacion)
            select_por_defecto("select_tipo_medicion_mapa", filtro.Fk_Id_Tipo_Medicion_Mapa)
            select_por_defecto("select_medicion", filtro.Fk_Id_Medicion)
        }

        // Al seleccionar un sector
        $("#select_sector_filtro").on("change", function(){
            // Se consultan las vías del sector
            datos = {
                url: "<?php echo site_url('configuracion/obtener'); ?>",
                tipo: "vias",
                id: $(this).val(),
                elemento_padre: $("#select_sector_filtro"),
                elemento_hijo: $("#select_via_filtro"),
                mensaje_padre: "Elija primero un sector",
                mensaje_hijo: "Todas las vías"
            }
            cargar_lista_desplegable(datos)

            limpiar_lista($("#select_costado_filtro"), "Elija primero una vía...")
        })

        // Al seleccionar una vía
        $("#select_via_filtro").on("change", function(){
            // Se consultan los costados de la vía
            datos = {
                url: "<?php echo site_url('configuracion/obtener'); ?>",
                tipo: "costados",
                id: $(this).val(),
                elemento_padre: $("#select_via_filtro"),
                elemento_hijo: $("#select_costado_filtro"),
                mensaje_padre: "Elija primero una vía",
                mensaje_hijo: "Todos los costados"
            }
            cargar_lista_desplegable(datos)
        })

        $("#select_sector_filtro, #select_via_filtro, #select_costado_filtro").on("change", function(){
            // Se guarda el filtro, indicando el módulo
            guardar_filtros(1, "<?php echo $this->session->userdata('Pk_Id_Usuario'); ?>")
        })
    })
</script>