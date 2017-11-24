<?php
defined('BASEPATH') OR exit('El acceso directo a este archivo no está permitido');

class Configuracion extends CI_Controller {
	function index()
	{
        $this->data['titulo'] = 'Configuración';
        $this->data['contenido_principal'] = 'configuracion/index';
        $this->load->view('core/template', $this->data);
	}
}