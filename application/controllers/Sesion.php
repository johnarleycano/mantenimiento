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
        $this->load->model(array('sesion_model'));
    }

	/**
     * Interfaz inicial de la sesión
     * 
     * @return [void]
     */
	function index()
	{
        $this->data['titulo'] = 'Identifcación';
        $this->data['contenido_principal'] = 'sesion/index';
        $this->load->view('core/template', $this->data);
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
        
        redirect('sesion');
	}

	/**
	 * Inicia la sesión y redirecciona
	 * al controlador inicial
	 * 
	 * @return [void]
	 */
	function iniciar()
	{
        //Se valida que la peticion venga mediante ajax y no mediante el navegador
        if($this->input->is_ajax_request()){
			// Se cargan los datos a la sesión
	        $this->session->set_userdata($this->input->post("datos_usuario"));

	        // Se inserta el registro de logs enviando tipo de log y dato adicional si corresponde
            $this->logs_model->insertar(1);
		} else {
            // Si la peticion fue hecha mediante navegador, se redirecciona a la pagina de inicio
            redirect('');
        }
	}

	/**
	 * Consulta que los datos de inicio de sesión
	 * existan en la base de datos, carga los permisos
	 * e inicia la sesión en el sistema
	 * 
	 * @return [void]
	 */
	function validar()
	{
        //Se valida que la peticion venga mediante ajax y no mediante el navegador
        if($this->input->is_ajax_request()){
        	// Datos recibidos por POST
            $usuario = $this->input->post('usuario');
            $clave = sha1($this->input->post('clave'));
            
            // Consulta del usuario en base de datos
            $datos_sesion = $this->sesion_model->validar($usuario, $clave);

            // Si el usuario existe
            if ($datos_sesion) {
	            // se consultan los accesos del usuario, de acuerdo al tipo de perfil
	            $accesos = $this->sesion_model->cargar_permisos($datos_sesion->Fk_Id_Tipo_Usuario, $datos_sesion->Pk_Id);

	            //Se arma un arreglo con los datos de sesion que va a mantener
	            $sesion = array(
	                "Pk_Id_Usuario" => $datos_sesion->Pk_Id,
	                "Login" => $datos_sesion->Login,
	                "Apellidos" => $datos_sesion->Apellidos,
	                "Nombres" => $datos_sesion->Nombres,
	                "Estado" => $datos_sesion->Estado,
	                "Fk_Id_Tipo_Usuario" => $datos_sesion->Fk_Id_Tipo_Usuario,
	                "Documento" => $datos_sesion->Documento,
	                "Email" => $datos_sesion->Email,
	                'Permisos' => $accesos
	            );
            	
            	// Envío de datos mediante JSON
        		print json_encode($sesion);
	            exit();
            }
            
        	// Envío de datos mediante JSON
        	print json_encode($datos_sesion);
		} else {
            // Si la peticion fue hecha mediante navegador, se redirecciona a la pagina de inicio
            redirect('');
        }
	}
}
/* Fin del archivo Sesion.php */
/* Ubicación: ./application/controllers/Sesion.php */