<?php 
Class Logs_model extends CI_Model{
	/**
	 * Prepara los datos en un arreglo e inserta
	 * el log en base de datos
	 * 
	 * @param  [string] $tipo        Tipo de log
	 * @param  [string] $observacion Observación adicional
	 * 
	 * @return [void]
	 */
	function insertar($tipo, $observacion = null){
        $datos = array(
            'Fk_Id_Tipo_Log' => $tipo,
            'Fk_Id_Usuario' => $this->session->userdata('Pk_Id_Usuario'),
            'Observacion' => $observacion
        );

        $this->db->insert('logs', $datos);
    }



}
/* Fin del archivo logs_model.php */
/* Ubicación: ./application/models/logs_model.php */