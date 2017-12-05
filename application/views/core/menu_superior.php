<nav id="menu_superior" class="uk-navbar uk-navbar-container uk-margin" uk-navbar>
    <!-- Ícono que activa el menú lateral -->
    <a class="uk-navbar-toggle" href="#" uk-toggle="target: #offcanvas-nav" title="Visualice el menú principal" uk-tooltip="pos: right">
        <span uk-navbar-toggle-icon></span>
    </a>
    
    <!-- Menú derecho -->
    <div class="uk-navbar-right uk-hidden">
        <ul class="uk-iconnav">
            <li><a href="#" id="icono_guardar" uk-icon="icon: plus" title="Guardar" uk-tooltip="pos: bottom-left"></a></li>
            <li><a href="#" id="icono_editar" uk-icon="icon: file-edit" title="Editar" uk-tooltip="pos: bottom-center"></a></li>
            <li><a href="#" id="icono_copiar" uk-icon="icon: copy" title="Copiar" uk-tooltip="pos: bottom-center"></a></li>
            <li><a href="#" id="icono_eliminar" uk-icon="icon: trash" title="Eliminar" uk-tooltip="pos: bottom-left"></a></li>
            <li><a href="#" id="icono_iniciar" uk-icon="icon: play" title="Iniciar medición" uk-tooltip="pos: bottom-left" onClick="javascript:iniciar_medicion()"></a></li>
            <li><a href="#" id="icono_anterior" uk-icon="icon: chevron-left" title="Anterior" uk-tooltip="pos: bottom-left" onClick="javascript:anterior    ()"></a></li>
            <li><a href="#" id="icono_terminar" uk-icon="icon: close" title="Terminar" uk-tooltip="pos: bottom-left" onClick="javascript:terminar()"></a></li>
            <li><a href="#" id="icono_siguiente" uk-icon="icon: chevron-right" title="Siguiente" uk-tooltip="pos: bottom-left" onClick="javascript:siguiente()"></a></li>
        </ul>
    </div>
</nav>