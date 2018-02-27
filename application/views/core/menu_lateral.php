<div class="uk-offcanvas-content">
    <div id="offcanvas-nav" uk-offcanvas="overlay: false; mode: push; flip: false;">
        <div id="menu_lateral" class="uk-offcanvas-bar">
            <ul class="uk-nav uk-nav-default">
                <li class="uk-active"><a href="<?php echo site_url(''); ?>">INICIO</a></li>
                
                <!-- <li class="uk-parent">
                    <a href="#">REPORTES</a>
                    <ul class="uk-nav-sub">
                        <li><a href="#">Sub item</a></li>
                        <li><a href="#">Sub item</a></li>
                    </ul>
                </li> -->

                <li class="uk-nav-header">ROCERÍA Y CUNETAS</li>
                <li><a onCLick="javascript:medir_roceria()"><i class="far fa-paper-plane fa-lg"></i>&nbsp;&nbsp;&nbsp;&nbsp;Medir</a></li>
                <li><a href="<?php echo site_url('roceria/ver'); ?>"><i class="fas fa-list-alt fa-lg"></i>&nbsp;&nbsp;&nbsp;&nbsp;Ver mediciones</a></li>
                
                <li class="uk-nav-divider"></li>
                <li><a href="<?php echo site_url('reportes'); ?>"><i class="far fa-clipboard fa-lg"></i>&nbsp;&nbsp;&nbsp;&nbsp;Reportes</a></li>
                <li><a href="<?php echo site_url('configuracion'); ?>"><i class="fas fa-cog fa-lg"></i>&nbsp;&nbsp;&nbsp;Configuración</a></li>
                
                <li class="uk-nav-divider"></li>
                <li><a href="<?php echo site_url('sesion/cerrar'); ?>"><i class="fas fa-sign-out-alt fa-lg"></i>&nbsp;&nbsp;&nbsp;Salir</a></li>
            </ul>
        </div>
    </div>
</div>