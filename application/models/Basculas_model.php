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
				return $this->db->insert('certificados_basculas', $datos);
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
	function obtener($tipo, $id = null, $adicional = null)
	{
		switch ($tipo) {
			case 'certificado_pesaje':
				$this->db
		        	->select(array(
			            'c.Placa',
			            'c.Fecha',
			            'tv.Categoria',
			            'tv.Peso_Maximo',
			            ))
		            ->from('certificados_basculas c')
		            ->join('tipos_vehiculos tv', 'c.Fk_Id_Tipo_Vehiculo = tv.Pk_Id')
		            ->where('c.Pk_Id', $id)
	            ;
		        
		        // return $this->db->get_compiled_select(); // string de la consulta
		        return $this->db->get()->row();
			break;
		}
	}
}
/* Fin del archivo Basculas_model.php */
/* Ubicación: ./application/models/Basculas_model.php */