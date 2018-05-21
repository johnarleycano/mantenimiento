<footer style="
    background: white;
    bottom: 0;
    color: gray;
    font-size: 0.8em;
    left: 0;
    padding-top: 5px;
    padding-bottom: 5px;
    position: fixed;
    text-align: center;
    width: 100%;
">
	Sistema de mantenimiento | <a href="http://devimed.com.co/" target="_blank">Devimed S.A.</a> | <i>Versión <b><?php echo version(); ?>
</footer>

<?php
function version()
{
    foreach(array_reverse(glob('.git/refs/tags/*')) as $archivo) {
        $contents = file_get_contents($archivo);
        return basename($archivo);
    }
}
?>