<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="X-UA-Compatible" content="IE=edge">

<!-- Título que viene desde el controlador de cada interfaz -->
<title><?php echo $titulo; ?> | Mediciones</title>

<!-- Estilos -->
<link rel="stylesheet" href="<?php echo base_url(); ?>css/mediciones.css" />
<link rel="stylesheet" href="<?php echo base_url(); ?>css/estilos.css" />

<?php if(ENVIRONMENT === 'development') { ?>
	<!-- Estilos -->
	<link rel="stylesheet" href="<?php echo base_url(); ?>css/uikit.css" />

	<!-- Scripts -->
	<script src="<?php echo base_url(); ?>js/uikit.js"></script> <!-- Scripts para UI Kit -->
	<script src="<?php echo base_url(); ?>js/uikit-icons.js"></script> <!-- Scripts para UI Kit -->
<?php } ?>

<?php if(ENVIRONMENT === 'production') { ?>
	<!-- Estilos -->
	<link rel="stylesheet" href="<?php echo base_url(); ?>css/uikit.min.css" />

	<!-- Scripts -->
	<script src="<?php echo base_url(); ?>js/uikit.min.js"></script> <!-- Scripts para UI Kit -->
	<script src="<?php echo base_url(); ?>js/uikit-icons.min.js"></script> <!-- Scripts para UI Kit -->
<?php } ?>

<!-- Scripts -->
<script src="<?php echo base_url(); ?>js/jquery-3.2.1.min.js"></script> <!-- jQuery -->
<script src="<?php echo base_url(); ?>js/jquery.timeago.js"></script>
<script src="<?php echo base_url(); ?>js/jquery.timeago.es.js"></script>
<script src="<?php echo base_url(); ?>js/funciones.js"></script> <!-- Funciones generales -->