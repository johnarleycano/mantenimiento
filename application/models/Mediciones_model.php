<?php 
Class Mediciones_model extends CI_Model{
	function __construct() {
        parent::__construct();

        /*
         * db_configuracion es la conexion a los datos de configuración de la aplicación, como lo son los sectores, vías,
         * tramos, entre otros.
         * Esta se llama porque en el archivo database.php la variable ['configuracion']['pconnect] esta marcada como false,
         * lo que quiere decir que no se conecta persistentemente sino cuando se le invoca, como en esta ocasión.
         */
        $this->db_configuracion = $this->load->database('configuracion', TRUE);
    }

    /**
     * Obtiene registros de base de datos
     * y los retorna a las vistas
     * 
     * @param  [string] $tipo Tipo de consulta que va a hacer
     * @param  [int]    $id   Id foráneo para filtrar los datos
     * 
     * @return [array]       Arreglo de datos
     */
    function obtener($tipo, $id = null)
    {
        switch ($tipo) {
            case 'abscisas_limite':
                return $this->db
                    ->select('MAX( d.Abscisa ) Mayor')
                    ->select('MIN( d.Abscisa ) Menor')
                    ->where("Fk_Id_Medicion", $id)
                    ->get("mediciones_detalle d")->row();
            break;

            case 'medicion':
                $this->db
                    ->select(array(
                        'm.*',
                        'v.Fk_Id_Sector',
                    ))
                    ->where("m.Pk_Id", $id)
                    ->from("mediciones m")
                    ->join('configuracion.vias v', 'm.Fk_Id_Via = v.Pk_Id')
                ;
                
                // return $this->db->get_compiled_select(); // string de la consulta
                return $this->db->get()->row();
            break;
            
            case 'resumen':
                if ($id) $this->db->where($id);
                $this->db
                    ->select(array(
                        'm.*',
                        's.Codigo Sector',
                        'v.Nombre Via',
                        ))
                    ->from('mediciones m')
                    ->join('configuracion.vias v', 'm.Fk_Id_Via = v.Pk_Id')
                    ->join('configuracion.sectores s', 'v.Fk_Id_Sector = s.Pk_Id')
                ;
                
                // return $this->db->get_compiled_select(); // string de la consulta
                return $this->db->get()->result();
            break;
        }
    }
}
/* Fin del archivo Configuracion_model.php */
/* Ubicación: ./application/models/Configuracion_model.php */