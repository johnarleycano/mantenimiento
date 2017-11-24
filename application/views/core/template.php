<!DOCTYPE html>
<html>
	<head>
		<!-- Cabecera con todos los estilos y scripts -->
        <?php $this->load->view('core/header'); ?>
	</head>
	<body>
        <?php
        // Menús de la aplicación
        $this->load->view('core/menu_superior');
        $this->load->view('core/menu_lateral');
        ?>

    	<!-- Contenedor principal -->
        <div id="contenedor_principal" class="uk-container uk-container-large">
            <!--Se carga el contenido principal -->
            <?php $this->load->view($contenido_principal); ?>
    	</div>

	   <!-- Pié de página -->
        <?php $this->load->view('core/footer'); ?>
	</body>
</html>