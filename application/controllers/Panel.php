<?php
defined('BASEPATH') OR exit('El acceso directo a este archivo no está permitido');

class Panel extends CI_Controller {
	function index()
	{
        $this->data['titulo'] = 'Panel';
        $this->data['contenido_principal'] = 'panel/index';
        $this->load->view('core/template', $this->data);
	}
}
