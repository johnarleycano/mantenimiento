<div class="uk-offcanvas-content">
    <div id="offcanvas-nav" uk-offcanvas="overlay: false; mode: push; flip: false;">
        <div id="menu_lateral" class="uk-offcanvas-bar">
            <ul class="uk-nav uk-nav-default">
                <li class="uk-active"><a href="#">MEDIR</a></li>
                
                <li class="uk-parent">
                    <a href="#">REPORTES</a>
                    <ul class="uk-nav-sub">
                        <li><a href="#">Sub item</a></li>
                        <li><a href="#">Sub item</a></li>
                    </ul>
                </li>

                <li class="uk-nav-header">ROCERÍA Y CUNETAS</li>
                <li><a onCLick="javascript:medir_roceria()"><span class="uk-margin-small-right" uk-icon="icon: bolt"></span> Medir</a></li>
                <li><a href="#"><span class="uk-margin-small-right" uk-icon="icon: album"></span> Ver mediciones</a></li>

                <li class="uk-nav-header">SEÑALIZACIÓN VERTICAL</li>
                <li><a href="#"><span class="uk-margin-small-right" uk-icon="icon: bolt"></span> Medir</a></li>
                <li><a href="#"><span class="uk-margin-small-right" uk-icon="icon: album"></span> Ver mediciones</a></li>
                <br>
                
                <li class="uk-nav-divider"></li>
                <li><a href="#"><span class="uk-margin-small-right" uk-icon="icon: copy"></span> Reportes</a></li>
                <li><a href="<?php echo site_url('configuracion'); ?>"><span class="uk-margin-small-right" uk-icon="icon: cog"></span> Configuración</a></li>
                <li><a href="<?php echo site_url('sesion/cerrar'); ?>"><span class="uk-margin-small-right" uk-icon="icon: sign-out"></span> Salir</a></li>
            </ul>
        </div>
    </div>
</div>

<script type="text/javascript">
    
</script>