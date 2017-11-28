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
}
/* Fin del archivo Configuracion.php */
/* Ubicación: ./application/controllers/Configuracion.php */