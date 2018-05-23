<?php
date_default_timezone_set('America/Bogota');

defined('BASEPATH') OR exit('El acceso directo a este archivo no está permitido');

/**
 * @author: 	John Arley Cano Salinas
 * Fecha: 		21 de noviembre de 2017
 * Programa:  	Mantenimiento | Módulo de configuración
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
                case "filtros":
                    $this->load->view("configuracion/filtros");
                break;
            }
        } else {
            // Si la peticion fue hecha mediante navegador, se redirecciona a la pagina de inicio
            redirect('');
        }
    }

    /**
     * Elimina registros en base de datos
     * 
     * @return [boolean] true, false
     */
    function eliminar(){
        //Se valida que la peticion venga mediante ajax y no mediante el navegador
        if($this->input->is_ajax_request()){
            // Datos por POST
            $tipo = $this->input->post("tipo");

            // Suiche
            switch ($tipo) {
                case 'filtro':
                    echo $this->configuracion_model->eliminar($tipo, $this->input->post("datos"));
                break;
            }
        }else{
            //Si la peticion fue hecha mediante navegador, se redirecciona a la pagina de inicio
            redirect('');
        }
    }

    /**
     * Permite la inserción de datos en la base de datos 
     * 
     * @return [void]
     */
    function insertar()
    {
        //Se valida que la peticion venga mediante ajax y no mediante el navegador
        if($this->input->is_ajax_request()){
            // Datos vía POST
            $datos = $this->input->post('datos');
            $tipo = $this->input->post('tipo');

            switch ($tipo) {
                case "filtro":
                    // Se inserta el registro y log en base de datos
                    echo $this->configuracion_model->insertar($tipo, $datos);
                break;
            }
        }else{
            //Si la peticion fue hecha mediante navegador, se redirecciona a la pagina de inicio
            redirect('');
        } // if
    }

	function obtener()
	{
        //Se valida que la peticion venga mediante ajax y no mediante el navegador
        if($this->input->is_ajax_request()){
        	$tipo = $this->input->post("tipo");
        	$id = $this->input->post("id");

        	switch ($tipo) {
                case "filtro":
                    print json_encode($this->configuracion_model->obtener($tipo, $id));
                break;

                case "via":
                    print json_encode($this->configuracion_model->obtener($tipo, $id));
                break;

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