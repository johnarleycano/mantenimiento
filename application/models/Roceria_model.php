<?php 
Class Roceria_model extends CI_Model{
	function eliminar($tipo, $id){
		// Según el tipo
		switch ($tipo) {
			case 'medicion_detalle':
				if($this->db->delete('mediciones_detalle', $id)){
					return true;
				}
			break;
		}
	}

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
			case "medicion":
				return $this->db->insert('mediciones', $datos);
			break;
		}

		switch ($tipo) {
			case "medicion_detalle":
				return $this->db->insert_batch('mediciones_detalle', $datos);
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
			case 'medicion':
				// return $this->db
				// 	->where("Pk_Id", $id)
				// 	->get("mediciones")->row();

				$this->db
		        	->select(array(
			            'm.Fecha_Inicial',
			            'v.Nombre Via',
			            's.Nombre Sector',
			            'm.Fk_Id_Via',
			            ))
		            ->from('mediciones m')
		            ->join('configuracion.vias v', 'm.Fk_Id_Via = v.Pk_Id')
		            ->join('configuracion.sectores s', 'v.Fk_Id_Sector = s.Pk_Id')
		            ->where('m.Pk_Id', $id)
	            ;

		        // return $this->db->get_compiled_select(); // string de la consulta
		        return $this->db->get()->row();
			break;

			case 'medicion_detalle':
				return $this->db
					->where($id)
					->get("mediciones_detalle")->row();
			break;

            case 'medicion_anterior':
                $this->db
                    ->select(array(
                        'm.Pk_Id',
                        ))
                    ->from('mediciones m')
                    ->where('m.Fk_Id_Via', $id)
                    ->order_by('m.Fecha_Inicial', 'DESC')
                    ->limit(1, 1)
                ;

		        // return $this->db->get_compiled_select(); // string de la consulta
		        return $this->db->get()->row();
            break;
		}
	}
}
/* Fin del archivo Roceria_model.php */
/* Ubicación: ./application/models/Roceria_model.php */