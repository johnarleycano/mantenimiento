<?php
date_default_timezone_set('America/Bogota');

defined('BASEPATH') OR exit('El acceso directo a este archivo no está permitido');

/**
 * @author:     John Arley Cano Salinas
 * Fecha:       23 de noviembre de 2017
 * Programa:    Mantenimiento | Módulo inicial - panel principal
 *              Permite visualizar el resumen o estado de las 
 *              mediciones y  gráficos o estadísticos
 * Email:       johnarleycano@hotmail.com
 */
class Panel extends CI_Controller {
    /**
     * Función constructora de la clase. Se hereda el mismo constructor 
     * de la clase para evitar sobreescribirlo y de esa manera 
     * conservar el funcionamiento de controlador.
     */
    function __construct() {
        parent::__construct();

        // Carga de modelos
        $this->load->model(array('configuracion_model', 'panel_model', 'roceria_model'));
    }
    
    /**
     * Interfaz inicial del panel
     * 
     * @return [void]
     */
	function index()
	{
		// Si no ha iniciado sesión o es un usuario diferente al 1,
        // redirecciona al inicio de sesión
        if(!$this->session->userdata('Pk_Id_Usuario')){
            redirect('sesion/cerrar');
        }

        $this->data['titulo'] = 'Panel';
        $this->data['contenido_principal'] = 'panel/index';
        $this->load->view('core/template', $this->data);
	}

    /**
     * Carga de interfaz vía Ajax
     * 
     * @return [void]
     */
    function cargar_interfaz()
    {
        //Se valida que la peticion venga mediante ajax y no mediante el navegador
        if($this->input->is_ajax_request()){
            $tipo = $this->input->post("tipo");

            switch ($tipo) {
                case "detalle_medicion":
                    $this->data["calificacion"] = $this->input->post("calificacion");
                    $this->data["id_medicion"] = $this->input->post("id_medicion");
                    $this->load->view("panel/detalle_medicion", $this->data);
                break;

                case "mediciones_urgentes":
                    $this->data["calificacion"] = $this->input->post("calificacion");
                    $this->load->view("panel/mediciones_urgentes", $this->data);
                break;

                case "resumen_mediciones":
                    $this->load->view("panel/resumen_mediciones");
                break;

                case "ultimas_mediciones":
                    $this->data["fecha"] = $this->input->post("fecha");
                    $this->load->view("panel/ultimas_mediciones", $this->data);
                break;
            }
        } else {
            // Si la peticion fue hecha mediante navegador, se redirecciona a la pagina de inicio
            redirect('');
        }
    }

    function obtener()
    {
        //Se valida que la peticion venga mediante ajax y no mediante el navegador
        if($this->input->is_ajax_request()){
            $tipo = $this->input->post("tipo");
            $id = $this->input->post("id");

            switch ($tipo) {
                case "calificaciones":
                    print json_encode($this->panel_model->obtener($tipo, $id));
                break;
                
                case "valores_por_calificacion":
                    print json_encode($this->panel_model->obtener($tipo, $id));
                break;
            }
        } else {
            // Si la peticion fue hecha mediante navegador, se redirecciona a la pagina de inicio
            redirect('');
        }
    }
}
/* Fin del archivo Panel.php */
/* Ubicación: ./application/controllers/Panel.php */