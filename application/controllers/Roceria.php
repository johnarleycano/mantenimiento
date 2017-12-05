<?php
defined('BASEPATH') OR exit('El acceso directo a este archivo no está permitido');

/**
 * @author:     John Arley Cano Salinas
 * Fecha:       28 de noviembre de 2017
 * Programa:    Mediciones | Módulo de rocería
 *              Medición, visualización y demás interacciones
 *              en la medición de la rocería
 * Email:       johnarleycano@hotmail.com
 */
class Roceria extends CI_Controller {
	/**
	 * Función constructora de la clase. Se hereda el mismo constructor 
	 * de la clase para evitar sobreescribirlo y de esa manera 
     * conservar el funcionamiento de controlador.
	 */
	function __construct() {
        parent::__construct();
        
        // Carga de modelos
        $this->load->model(array('configuracion_model'));
    }

    /**
     * Interfaz inicial de la rocería
     * 
     * @return [void]
     */
	function index()
	{
        $this->data['titulo'] = 'Rocería';
        $this->data['contenido_principal'] = 'roceria/index';
        $this->load->view('core/template', $this->data);
	}

	function parametrizar()
	{
        $this->data['titulo'] = 'Rocería - Parametrizar';
        $this->data['contenido_principal'] = 'roceria/parametrizar';
        $this->load->view('core/template', $this->data);
	}
}
/* Fin del archivo Roceria.php */
/* Ubicación: ./application/controllers/Roceria.php */
