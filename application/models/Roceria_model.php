<?php 
Class Roceria_model extends CI_Model{
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
			case "medicion_temporal":
				return $this->db->insert('temp_medicion', $datos);
			break;
		}

		switch ($tipo) {
			case "medicion_detalle_temporal":
				return $this->db->insert_batch('temp_medicion_detalle', $datos);
			break;
		}
	}

	/**
	 * Obtiene registros de base de datos
	 * y los retorna a las vistas
	 * 
	 * @param  [string] $tipo Tipo de consulta que va a hacer
	 * @param  [int] 	$id   Id foráneo para filtrar los datos
	 * 
	 * @return [array]       Arreglo de datos
	 */
	function obtener($tipo, $id = null)
	{
		switch ($tipo) {
			case 'medicion_temporal':
				return $this->db
					->where("Pk_Id", $id)
					->get("temp_medicion")->row();
			break;
		}
	}
}
/* Fin del archivo Roceria_model.php */
/* Ubicación: ./application/models/Roceria_model.php */