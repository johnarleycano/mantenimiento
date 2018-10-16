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
                case 'medicion_detalle':
                    echo $this->mediciones_model->eliminar($tipo, $this->input->post("datos"));
                break;
            }
        }else{
            //Si la peticion fue hecha mediante navegador, se redirecciona a la pagina de inicio
            redirect('');
        }
    }

    function roceria()
    {
        // Si no ha iniciado sesión
        // redirecciona al inicio de sesión
        if(!$this->session->userdata('Pk_Id_Usuario')){
            redirect('sesion/cerrar');
        }

        $this->data['titulo'] = 'Rocería - Iniciar';
        $this->data['contenido_principal'] = 'mediciones/roceria';
        $this->load->view('core/template', $this->data);
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
                case "medicion":
                    // Se inserta el registro y log en base de datos
                    if ($this->mediciones_model->insertar($tipo, $datos)) {
                        echo $id = $this->db->insert_id();

                        // Se inserta el registro de logs enviando tipo de log y dato adicional si corresponde
                        $this->logs_model->insertar(3, "Medición $id");
                    }
                break;

                case "medicion_detalle":
                    // Se inserta el registro y log en base de datos
                    if ($this->mediciones_model->insertar($tipo, $datos)) {
                        echo $id = $this->db->insert_id();

                        // Se inserta el registro de logs enviando tipo de log y dato adicional si corresponde
                        // $this->logs_model->insertar(4, "Medición {$datos["0"]["Fk_Id_Medicion"]}, Abscisa {$datos["0"]["Abscisa"]}");
                    }
                break;

                case "medicion_continuar_log":
                    // Se inserta el registro de logs enviando tipo de log y dato adicional si corresponde
                    $this->logs_model->insertar(7, "Medición {$this->input->post('id')}");
                break;
            }
        }else{
            //Si la peticion fue hecha mediante navegador, se redirecciona a la pagina de inicio
            redirect('');
        } // if
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

    /**
     * Permite la parametrización de la
     * medición de rocería y cunetas 
     * 
     * @return [void]
     */
    function parametrizar()
    {
        // Si no ha iniciado sesión o es un usuario diferente al 1,
        // redirecciona al inicio de sesión
        if(!$this->session->userdata('Pk_Id_Usuario')){
            redirect('sesion/cerrar');
        }
        
        $this->data['titulo'] = 'Rocería - Parametrizar';
        $this->data['contenido_principal'] = 'mediciones/parametrizar';
        $this->load->view('core/template', $this->data);
    }

    /**
     * Muestra los resultados de la medición
     * 
     * @return [void]
     */
    function resumen()
    {
        // Si no ha iniciado sesión o es un usuario diferente al 1,
        // redirecciona al inicio de sesión
        if(!$this->session->userdata('Pk_Id_Usuario')){
            redirect('sesion/cerrar');
        }

        // Se inserta el registro de logs enviando tipo de log y dato adicional si corresponde
        $this->logs_model->insertar(5, "Medición ".$this->uri->segment(3));

        $this->data['titulo'] = 'Medición - Resumen';
        $this->data['contenido_principal'] = 'mediciones/resumen';
        $this->load->view('core/template', $this->data);
    }
}
/* Fin del archivo Mediciones.php */
/* Ubicación: ./application/controllers/Mediciones.php */