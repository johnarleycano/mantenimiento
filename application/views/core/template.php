<!DOCTYPE html>
<html>
	<head>
		<!-- Cabecera con todos los estilos y scripts -->
        <?php $this->load->view('core/header'); ?>
	</head>
	<body>
        <?php
        // Si ha iniciado sesión
        if ($this->session->userdata("Pk_Id_Usuario")) {
            // Menús de la aplicación
            $this->load->view('core/menu_superior');
            $this->load->view('core/menu_lateral');
        }
        ?>

    	<!-- Contenedor principal -->
        <div id="contenedor_principal" class="uk-container uk-container-medium">
            <!--Se carga el contenido principal -->
            <?php $this->load->view($contenido_principal); ?>
        </div>

       <!-- Pié de página -->
        <?php $this->load->view('core/footer'); ?>

        <!-- Input que entrega url base para archivos en JS -->
        <input type="hidden" id="url" value="<?php echo site_url(''); ?>">
	</body>
</html>

<script type="text/javascript">
    /**
     * Carga de interfaz de medición de rocería
     * 
     * @return [void]
     */
    function parametrizar_medicion()
    {
        redireccionar("<?php echo site_url('mediciones/parametrizar'); ?>");
    }

    function volver()
    {
        redireccionar("<?php echo site_url(''); ?>");
    }
</script>