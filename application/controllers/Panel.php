<?php
defined('BASEPATH') OR exit('El acceso directo a este archivo no está permitido');

class Panel extends CI_Controller {
	function index()
	{
		//Si no ha iniciado sesión o es un usuario diferente al 1
        if(!$this->session->userdata('Pk_Id_Usuario')){
            // Se cierra la sesion obligatoriamente
            redirect('sesion/cerrar');
        }//Fin if

        $this->data['titulo'] = 'Panel';
        $this->data['contenido_principal'] = 'panel/index';
        $this->load->view('core/template', $this->data);
	}
}