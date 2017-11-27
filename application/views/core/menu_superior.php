<nav id="menu_superior" class="uk-navbar uk-navbar-container uk-margin" uk-navbar>
    <!-- Ícono que activa el menú lateral -->
    <a class="uk-navbar-toggle" href="#" uk-toggle="target: #offcanvas-nav" title="Visualice el menú principal" uk-tooltip="pos: right">
        <span uk-navbar-toggle-icon></span>
    </a>
    
    <!-- Menú derecho -->
    <div class="uk-navbar-right">
        <ul class="uk-iconnav">
            <li><a href="#" uk-icon="icon: plus" title="Guardar" uk-tooltip="pos: bottom-left"></a></li>
            <li><a href="#" uk-icon="icon: file-edit" title="Editar" uk-tooltip="pos: bottom-center"></a></li>
            <li><a href="#" uk-icon="icon: copy" title="Copiar" uk-tooltip="pos: bottom-center"></a></li>
            <li><a href="#" uk-icon="icon: trash" title="Eliminar" uk-tooltip="pos: bottom-left"></a></li>
            <li><a href="javascript:cerrar_sesion()" uk-icon="icon: sign-out" title="Cerrar sesión" uk-tooltip="pos: bottom-left"></a></li>
        </ul>
    </div>
</nav>

<script type="text/javascript">
    function cerrar_sesion()
    {
        redireccionar("<?php echo site_url('sesion/cerrar'); ?>");
    }
</script>