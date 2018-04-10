<?php
date_default_timezone_set('America/Bogota');

defined('BASEPATH') OR exit('El acceso directo a este archivo no está permitido');

/**
 * @author: 	John Arley Cano Salinas
 * Fecha: 		11 de enero de 2018
 * Programa:  	Mantenimiento | Módulo de reportes
 *            	Permite generar reportes 
 *            	en todos los formatos
 * Email: 		johnarleycano@hotmail.com
 */
Class Reportes extends CI_Controller{
	/**
	 * Función constructora de la clase. Se hereda el mismo constructor 
	 * de la clase para evitar sobreescribirlo y de esa manera 
     * conservar el funcionamiento de controlador.
	 */
	function __construct() {
        parent::__construct();

        // Carga de modelos
        $this->load->model(array('configuracion_model', 'reportes_model', 'roceria_model'));

        // Carga de librerías
        require('system/libraries/Fpdf.php');

        // Definición de la ruta de las fuentes
        define('FPDF_FONTPATH','system/fonts/');
    }

    /**
     * Reportes en PDF
     * 
     * @return [void]
     */
    function pdf()
    {
    	switch ($this->uri->segment(3)) {
    		case 'medicion':
                // Se inserta el registro de logs enviando tipo de log y dato adicional si corresponde
                $this->logs_model->insertar(6, "Medición {$this->uri->segment(4)}");
                
                $this->load->view("reportes/pdf/medicion");
			break;
    	}
    }
}
/* Fin del archivo Reportes.php */
/* Ubicación: ./application/controllers/Reportes.php */