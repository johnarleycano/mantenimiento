<?php
date_default_timezone_set('America/Bogota');

defined('BASEPATH') OR exit('El acceso directo a este archivo no está permitido');

/**
 * @author: 	John Arley Cano Salinas
 * Fecha: 		18 de mayo de 2018
 * Programa:  	Mantenimiento | Módulo de básculas
 *            	Gestión de la información relacionada a
 *            	las básculas de pesaje
 * Email: 		johnarleycano@hotmail.com
 */
class Basculas extends CI_Controller {
	/**
	 * Función constructora de la clase. Se hereda el mismo constructor 
	 * de la clase para evitar sobreescribirlo y de esa manera 
     * conservar el funcionamiento de controlador.
	 */
	function __construct() {
        parent::__construct();

        // Carga de modelos
        // $this->load->model(array('configuracion_model'));
    }

    /**
     * Interfaz de registro de pesaje
     * 
     * @return [void]
     */
	function pesaje()
	{
		// Si no ha iniciado sesión o es un usuario diferente al 1,
        // redirecciona al inicio de sesión
        if(!$this->session->userdata('Pk_Id_Usuario')){
            redirect('sesion/cerrar');
        }

        $this->data['titulo'] = 'Pesaje';
        $this->data['contenido_principal'] = 'basculas/pesaje/index';
        $this->load->view('core/template', $this->data);
	}

    /**
     * Permite la inserción de datos en la base de datos 
     * 
     * @return [void]
     */
    function insertar()
    {
        // Se valida que la peticion venga mediante ajax y no mediante el navegador
        if($this->input->is_ajax_request()){
            // Datos vía POST
            $datos = $this->input->post('datos');
            $tipo = $this->input->post('tipo');

            switch ($tipo) {
                case "certificado_pesaje":
                    // Se inserta el registro y log en base de datos
                    if ($this->basculas_model->insertar($tipo, $datos)) {
                        echo $id = $this->db->insert_id();

                        // Se inserta el registro de logs enviando tipo de log y dato adicional si corresponde
                        // $this->logs_model->insertar(3, "Medición $id");
                    }
                break;
            }
        }else{
            // Si la peticion fue hecha mediante navegador, se redirecciona a la pagina de inicio
            redirect('');
        }
    }
}
/* Fin del archivo Basculas.php */
/* Ubicación: ./application/controllers/Basculas.php */