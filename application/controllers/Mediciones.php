<?php
date_default_timezone_set('America/Bogota');

defined('BASEPATH') OR exit('El acceso directo a este archivo no está permitido');

/**
 * @author:     John Arley Cano Salinas
 * Fecha:       27 de febrero de 2018
 * Programa:    Mantenimiento | Módulo de mediciones general
 *              Gestión de datos de las mediciones a nivel general
 * Email:       johnarleycano@hotmail.com
 */
class Mediciones extends CI_Controller {
	/**
	 * Función constructora de la clase. Se hereda el mismo constructor 
	 * de la clase para evitar sobreescribirlo y de esa manera 
     * conservar el funcionamiento de controlador.
	 */
	function __construct() {
        parent::__construct();
        
        // Carga de modelos
        $this->load->model(array('configuracion_model', 'mediciones_model'));
    }

    /**
     * Visualización de mediciones
     * 
     * @return [void]
     */
    function ver()
    {
        // Si no ha iniciado sesión o es un usuario diferente al 1,
        // redirecciona al inicio de sesión
        if(!$this->session->userdata('Pk_Id_Usuario')){
            redirect('sesion/cerrar');
        }

        $this->data['titulo'] = 'Listado';
        $this->data['contenido_principal'] = 'mediciones/index';
        $this->load->view('core/template', $this->data);
    }

    /**
     * Carga de interfaces vía Ajax
     * 
     * @return [void]
     */
    function cargar_interfaz()
    {
        //Se valida que la peticion venga mediante ajax y no mediante el navegador
        if($this->input->is_ajax_request()){
            $tipo = $this->input->post("tipo");

            switch ($tipo) {
                case "mediciones_lista":
                    $this->data["datos"] = $this->input->post("datos");
                    $this->load->view("mediciones/listar", $this->data);
                break;
            }
        } else {
            // Si la peticion fue hecha mediante navegador, se redirecciona a la pagina de inicio
            redirect('');
        }
    }

    /**
     * Obtiene registros de base de datos
     * y los retorna a las vistas
     * 
     * @return [vois]
     */
    function obtener()
    {
        //Se valida que la peticion venga mediante ajax y no mediante el navegador
        if($this->input->is_ajax_request()){
            $tipo = $this->input->post("tipo");
            $id = $this->input->post("id");

            switch ($tipo) {
                case "abscisas_limite":
                    print json_encode($this->mediciones_model->obtener($tipo, $id));
                break;

                case "medicion":
                    print json_encode($this->mediciones_model->obtener($tipo, $id));
                break;

                case "mediciones":
                    print json_encode($this->mediciones_model->obtener($tipo, $id));
                break;

                case "ultima_medicion":
                    print json_encode($this->mediciones_model->obtener($tipo, $id));
                break;
            }
        } else {
            // Si la peticion fue hecha mediante navegador, se redirecciona a la pagina de inicio
            redirect('');
        }
    }

}
/* Fin del archivo Mediciones.php */
/* Ubicación: ./application/controllers/Mediciones.php */