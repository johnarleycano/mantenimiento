<?php
date_default_timezone_set('America/Bogota');

defined('BASEPATH') OR exit('El acceso directo a este archivo no está permitido');

/**
 * @author:     John Arley Cano Salinas
 * Fecha:       24 de noviembre de 2017
 * Programa:    Mediciones | Módulo de sesión
 *              Gestiona todo lo relacionado con el inicio
 *              y cierre de sesión del usuario
 * Email:       johnarleycano@hotmail.com
 */
class Sesion extends CI_Controller {
	/**
	 * Función constructora de la clase. Se hereda el mismo constructor 
	 * de la clase para evitar sobreescribirlo y de esa manera 
     * conservar el funcionamiento de controlador.
	 */
	function __construct() {
        parent::__construct();

        // Carga de modelos
        $this->load->model(array('configuracion_model', 'sesion_model'));
    }

	/**
     * Interfaz inicial de la sesión
     * 
     * @return [void]
     */
	function index()
	{
        // Se obtiene los datos de la aplicación principal
        $aplicacion = $this->configuracion_model->obtener("aplicacion", $this->config->item("id_aplicacion_sesion"));

        // Se lee el archivo con los datos de sesión activa
        $archivo = file_get_contents($aplicacion->Url."sesion.json");
        
        $datos_sesion = json_decode($archivo, true);
        
        $this->session->set_userdata($datos_sesion);
        
        // Se inserta el registro de logs enviando tipo de log y dato adicional si corresponde
        $this->logs_model->insertar(1);
        
        redirect("");
	}

	/**
	 * Cierra la sesión y redirecciona
	 * 
	 * @return [void]
	 */
	function cerrar()
	{
        $this->session->sess_destroy();
	        
        // Se inserta el registro de logs enviando tipo de log y dato adicional si corresponde
        $this->logs_model->insertar(2);
        
        $aplicacion = $this->configuracion_model->obtener("aplicacion", $this->config->item('id_aplicacion_sesion'));
        
        redirect("{$aplicacion->Url}index.php/sesion/iniciar/".$this->config->item('id_aplicacion'));
	}
}
/* Fin del archivo Sesion.php */
/* Ubicación: ./application/controllers/Sesion.php */