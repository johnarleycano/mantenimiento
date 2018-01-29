<footer style="
    background-color: #808080;
    bottom: 0;
    color: white;
    font-size: 0.7em;
    left: 0;
	position: fixed;
    text-align: center;
    width: 100%;
">
	Sistema de administración de permisos de usos de vía | Devimed S.A. | <i>Versión <?php echo version(); ?></i>
</footer>

<?php
function version()
{
	foreach(array_reverse(glob('.git/refs/tags/*')) as $archivo) {
    	$contents = file_get_contents($archivo);

	    return basename($archivo);
	    exit();
	}
}
?>