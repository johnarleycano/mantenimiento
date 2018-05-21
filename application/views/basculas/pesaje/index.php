<div class="uk-container uk-container-small">
	<h3 class="ui dividing header">
		<center>Expedición de certificado de cumplimiento</center>
	</h3>

	<form>
	    <div class="uk-margin">
	        <input class="uk-input uk-width-1-1 uk-form-large" type="text" id="input_placa" placeholder="Ingrese la placa" title="Placa" onkeyup="convertir_mayusculas(this);" autofocus>
	    </div>

	    <div class="uk-margin">
	        <input class="uk-button uk-button-primary uk-button-large uk-width-1-1" type="submit" value="Generar certificado"></input>
	    </div>
	</form>
</div>

<script type="text/javascript">
	/**
	 * Permite generar el PDF del certificado
	 * 
	 * @return {void}
	 */
	function generar_certificado(){
		cerrar_notificaciones()
		imprimir_notificacion("<div uk-spinner></div> Generando certificado...")
			
		campos_obligatorios = {
			"input_placa": $("#input_placa").val(),
		}
		// imprimir(campos_obligatorios)
		// imprimir($("#input_placa").attr("title"))
		
		// Si existen campos obligatorios sin diligenciar
		if(validar_campos_obligatorios(campos_obligatorios)){
			return false
		}

		datos = {
	    	"Placa": $("#input_placa").val(),
	    	"Fecha": "<?php echo date("Y-m-d H:i:s"); ?>",
	    	"Fk_Id_Usuario": "<?php echo $this->session->userdata('Pk_Id_Usuario'); ?>",
	    }
	    imprimir(datos)

	    id = ajax("<?php echo site_url('roceria/insertar'); ?>", {"tipo": "certificado_pesaje", "datos": datos}, 'HTML')
	    imprimir(id)

	    // if(id){
		    // Si realizó la inserción correctamente, genera el certificado
	        redireccionar(`<?php echo site_url('reportes/pdf/certificado_pesaje'); ?>/${id}`, "ventana");
	    	
	    	cerrar_notificaciones();
			imprimir_notificacion(`El certificado se generó correctamente`, `success`);
	    // }

	    


		return false
	}

	$(document).ready(function(){
		$("form").on("submit", generar_certificado)
	})
</script>
