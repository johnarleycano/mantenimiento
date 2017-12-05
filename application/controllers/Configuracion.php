<?php
defined('BASEPATH') OR exit('El acceso directo a este archivo no está permitido');

/**
 * @author: 	John Arley Cano Salinas
 * Fecha: 		21 de noviembre de 2017
 * Programa:  	Mediciones | Módulo de configuración
 *            	Permite configurar y parametrizar todas las opciones
 *             	de la aplicación
 * Email: 		johnarleycano@hotmail.com
 */
class Configuracion extends CI_Controller {
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
     * Interfaz inicial de la configuración
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

        $this->data['titulo'] = 'Configuración';
        $this->data['contenido_principal'] = 'configuracion/index';
        $this->load->view('core/template', $this->data);
	}

    function cargar_interfaz()
    {
        //Se valida que la peticion venga mediante ajax y no mediante el navegador
        if($this->input->is_ajax_request()){
            $tipo = $this->input->post("tipo");

            switch ($tipo) {
                case "medicion_roceria":
                    $this->data["abscisa_inicial"] = $this->input->post("abscisa_inicial");
                    $this->data["abscisa_final"] = $this->input->post("abscisa_final");
                    $this->load->view("roceria/medir", $this->data);
                break;

                case "rango_abscisado":
                    $this->data["id_via"] = $this->input->post("id_via");
                    $this->load->view("roceria/rango_abscisado", $this->data);
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
                case "vias":
					print json_encode($this->configuracion_model->obtener($tipo, $id));
				break;
			}
		} else {
            // Si la peticion fue hecha mediante navegador, se redirecciona a la pagina de inicio
            redirect('');
        }
	}
}
/* Fin del archivo Configuracion.php */
/* Ubicación: ./application/controllers/Configuracion.php */