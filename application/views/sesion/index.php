<div class="uk-section uk-section-default">
    <div class="uk-container">
        <h3>Sistema de Mediciones para cunetas, rocería y señalización vertical</h3>
        <div class="uk-grid-match uk-child-width-1-3@m" uk-grid>
            <div>
                <p><img src="<?php echo base_url().'img/logo.png'; ?>"></p>
            </div>
            <div>
                <p>En este sistema se podrá recoger la información de las mediciones periódicas tomadas en campo a lo largo de toda la vía concesionada de Devimed S.A.</p>
            </div>
            <div>
                <form>
				    <fieldset class="uk-fieldset">
				        <div class="uk-margin">
				            <input class="uk-input uk-form-large" id="login" title="nombre de usuario" type="text" placeholder="Nombre de usuario" autofocus>
				        </div>
				        <div class="uk-margin">
				            <input class="uk-input uk-form-large" id="clave" title="contraseña" type="password" placeholder="Contraseña">
				        </div>
				        <p uk-margin>
    						<input type="submit" class="uk-button uk-button-secondary uk-button-large uk-width-1-1" value="INICIAR SESIÓN">
    					</p>
				        <!-- <div class="uk-margin">
				            <input class="uk-range" type="range" value="2" min="0" max="10" step="0.5">
				        </div> -->
				    </fieldset>
				</form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		// Botón iniciar sesión
		$("form").on("submit", function(){
			cerrar_notificaciones();
			imprimir_notificacion("<div uk-spinner></div> Iniciando sesión...");

			campos_obligatorios = {
				"login": $("#login").val(),
				"clave": $("#clave").val()
			}
			// console.log(campos_obligatorios);

			// Si existen campos obligatorios sin diligenciar
			if(validar_campos_obligatorios(campos_obligatorios)){
				return false;
			}

			// Se consulta los datos del usuario
            usuario = ajax("<?php echo site_url('sesion/validar'); ?>", {"usuario": $("#login").val(), "clave": $("#clave").val()}, 'JSON');
            // console.log(usuario);

            // Si no existe el usuario con esas credenciales
            if (!usuario) {
				cerrar_notificaciones();
				imprimir_notificacion("<span uk-icon='icon: bolt'></span> El usuario y contraseña que ha digitado no existen en la base de datos. Por favor verifique e intente nuevamente, o comuníquese con el área de sistemas.", "danger");

            	return false;
            }

            // Si el usuario está desactivado
			if (usuario.Estado != 1) {
				cerrar_notificaciones();
				imprimir_notificacion("<span uk-icon='icon: bolt'></span> El acceso para " + usuario.Nombres + " " + usuario.Apellidos + " se encuentra desactivado y no puede iniciar sesión. Para mayor información, comuníquese con el área de sistemas.", "danger");

            	return false;
			}

			// Si no tiene permiso de acceder a la aplicación
			if (!usuario.Fk_Id_Tipo_Usuario) {
				cerrar_notificaciones();
				imprimir_notificacion("<span uk-icon='icon: bolt'></span> " + usuario.Nombres + " " + usuario.Apellidos + " no tiene acceso autorizado a la aplicación y la sesión no puede ser iniciada. Para mayor información, comuníquese con el área de sistemas", "danger");

            	return false;
			}

			// Se inicia la sesión
			ajax("<?php echo site_url('sesion/iniciar'); ?>", {"datos_usuario": usuario}, 'html');

			redireccionar("<?php echo site_url(''); ?>");

			return false;
		});
	});

</script>