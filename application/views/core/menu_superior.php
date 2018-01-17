<nav id="menu_superior" class="uk-navbar uk-navbar-container uk-margin" uk-navbar>
    <!-- Ícono que activa el menú lateral -->
    <a class="uk-navbar-toggle" href="#" uk-toggle="target: #offcanvas-nav" title="Visualice el menú principal" uk-tooltip="pos: right">
        <span uk-navbar-toggle-icon></span>
    </a>
    
    <!-- Menú derecho -->
    <div class="uk-navbar-right uk-hidden">
        <ul class="uk-iconnav">
            <li><a onClick="javascript:algo()" id="icono_guardar" uk-icon="icon: plus; ratio: 2" title="Guardar" uk-tooltip="pos: bottom-left"></a></li>
            <li><a onClick="javascript:algo()" id="icono_editar" uk-icon="icon: file-edit; ratio: 2" title="Editar" uk-tooltip="pos: bottom-center"></a></li>
            <li><a onClick="javascript:algo()" id="icono_eliminar" uk-icon="icon: trash; ratio: 2" title="Eliminar" uk-tooltip="pos: bottom-left"></a></li>
            <li><a onClick="javascript:iniciar_medicion()" id="icono_iniciar" uk-icon="icon: play; ratio: 2" title="Iniciar medición" uk-tooltip="pos: bottom-left"></a></li>
            <li><a onClick="javascript:anterior()" id="icono_anterior" uk-icon="icon: chevron-left; ratio: 2" title="Anterior" uk-tooltip="pos: bottom-left"></a></li>
            <li><a onClick="javascript:detener()" id="icono_detener" uk-icon="icon: minus-circle; ratio: 2" title="Detener medición" uk-tooltip="pos: bottom-left"></a></li>
            <li><a onClick="javascript:siguiente()" id="icono_siguiente" uk-icon="icon: chevron-right; ratio: 2" title="Siguiente" uk-tooltip="pos: bottom-left"></a></li>
            <li><a onClick="javascript:generar_pdf()" id="icono_pdf" uk-icon="icon: copy; ratio: 2" title="Imprimir reporte en PDF" uk-tooltip="pos: bottom-center"></a></li>
            <li><a onClick="javascript:volver()" id="icono_volver" uk-icon="icon: reply; ratio: 2" title="Volver al panel" uk-tooltip="pos: bottom-center"></a></li>
        </ul>
    </div>
</nav>