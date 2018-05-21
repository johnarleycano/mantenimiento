<?php 
Class Basculas_model extends CI_Model{
	/**
	 * Permite la inserción de datos en la base de datos 
	 * 
	 * @param  [string] $tipo  Tipo de inserción
	 * @param  [array] 	$datos Datos que se van a insertar
	 * 
	 * @return [boolean]        true, false
	 */
	function insertar($tipo, $datos)
	{
		switch ($tipo) {
			case "certificado_pesaje":
				// return $this->db->insert('mediciones', $datos);
			break;
		}
	}
}
/* Fin del archivo Basculas_model.php */
/* Ubicación: ./application/models/Basculas_model.php */