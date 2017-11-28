<?php
defined('BASEPATH') OR exit('El acceso directo a este archivo no está permitido');

/**
 * @author:     John Arley Cano Salinas
 * Fecha:       23 de noviembre de 2017
 * Programa:    Mediciones | Módulo inicial - panel principal
 *              Permite visualizar el resumen o estado de las 
 *              mediciones y  gráficos o estadísticos
 * Email:       johnarleycano@hotmail.com
 */
class Panel extends CI_Controller {
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
}
/* Fin del archivo Panel.php */
/* Ubicación: ./application/controllers/Panel.php */