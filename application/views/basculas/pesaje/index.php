<div class="uk-container uk-container-small">
	<h3 class="ui dividing header">
		<center>Expedición de certificado de cumplimiento</center>
	</h3>

	<form>
	    <div class="uk-margin">
	        <input class="uk-input uk-width-1-1 uk-form-large" type="text" id="input_placa" placeholder="Ingrese la placa" title="Placa" onkeyup="convertir_mayusculas(this);" autofocus>
	    </div>

	    <div class="uk-margin">
	    	<select class="uk-select uk-width-1-1 uk-form-large" id="select_tipo" title="tipo de vehículo">
	            <option value="">Elija un tipo de vehículo...</option>
	            <?php foreach ($this->configuracion_model->obtener("tipos_vehiculos") as $tipo) { ?>
	                <option value="<?php echo $tipo->Pk_Id ?>"><?php echo $tipo->Categoria; ?></option>
            	<?php } ?>
	    	</select>
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
	function generar_certificado()
	{
		cerrar_notificaciones()
		imprimir_notificacion("<div uk-spinner></div> Generando certificado...")
			
		campos_obligatorios = {
			"input_placa": $("#input_placa").val(),
			"select_tipo": $("#select_tipo").val(),
		}
		// imprimir(campos_obligatorios)
		
		// Si existen campos obligatorios sin diligenciar
		if(validar_campos_obligatorios(campos_obligatorios)){
			return false
		}

		datos = {
	    	"placa": $("#input_placa").val(),
	    	"Fk_Id_Tipo_Vehiculo": $("#select_tipo").val(),
	    	"Fecha": "<?php echo date("Y-m-d H:i:s"); ?>",
	    	"Fk_Id_Usuario": "<?php echo $this->session->userdata('Pk_Id_Usuario'); ?>",
	    }
	    // imprimir(datos)

	    id = ajax("<?php echo site_url('basculas/insertar'); ?>", {"tipo": "certificado_pesaje", "datos": datos}, 'HTML')
	    // imprimir(id)

	    if(id){
		    // Si realizó la inserción correctamente, genera el certificado
	        redireccionar(`<?php echo site_url('reportes/pdf/certificado_pesaje'); ?>/${id}`, `ventana`)
	    	
	    	cerrar_notificaciones();
			imprimir_notificacion(`El certificado se generó correctamente`, `success`)
	    }

		return false
	}

	$(document).ready(function(){
		$("form").on("submit", generar_certificado)
	})
</script>
