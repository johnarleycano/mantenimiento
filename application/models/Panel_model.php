<?php 
Class Panel_model extends CI_Model{
	function __construct() {
        parent::__construct();

        /*
         * db_configuracion es la conexion a los datos de configuración de la aplicación, como lo son los sectores, vías,
         * tramos, entre otros.
         * Esta se llama porque en el archivo database.php la variable ['configuracion']['pconnect] esta marcada como false,
         * lo que quiere decir que no se conecta persistentemente sino cuando se le invoca, como en esta ocasión.
         */
        // $this->db_configuracion = $this->load->database('configuracion', TRUE);
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
            case 'mediciones_urgentes':
                $this->db
                    ->select(array(
                        's.Nombre Sector',
                        'v.Nombre Via',
                        'd.Fk_Id_Medicion',
                        'd.Fecha',
                        'Date_format(d.Fecha,"%h:%i %p") Hora',
                        'Count(d.Pk_Id) Puntos',
                        ))
                    ->from('mediciones_detalle d')
                    ->join('configuracion.costados c', 'd.Fk_Id_Costado = c.Pk_Id')
                    ->join('configuracion.vias v', 'c.Fk_Id_Via = v.Pk_Id')
                    ->join('configuracion.sectores s', 'v.Fk_Id_Sector = s.Pk_Id')
                    ->where('d.Calificacion', $id)
                    ->group_by('d.Fk_Id_Medicion')
                    ->order_by('d.Fecha')
                ;

                // return $this->db->get_compiled_select(); // string de la consulta
                return $this->db->get()->result();
            break;
            
            case "ultimas_mediciones":
                $this->db
                    ->select(array(
                        'd.Fecha',
                        'Date_format(d.Fecha,"%h:%i %p") Hora',
                        's.Nombre Sector',
                        'v.Nombre Via',
                        ))
                    ->from('mediciones_detalle d')
                    ->join('configuracion.costados c', 'd.Fk_Id_Costado = c.Pk_Id')
                    ->join('configuracion.vias v', 'c.Fk_Id_Via = v.Pk_Id')
                    ->join('configuracion.sectores s', 'v.Fk_Id_Sector = s.Pk_Id')
                    ->order_by('d.Fecha', 'DESC')
                ;

                if ($id == "hoy") {
                    $this->db
                        ->where("DATE_FORMAT(d.Fecha,'%Y-%m-%d') =", date("Y-m-d"))
                        ->group_by("c.Fk_Id_Via")
                    ;
                }

                if ($id == "semana") {
                    $this->db
                        ->where("DATE_FORMAT(d.Fecha,'%Y-%m-%d') >=", date('Y-m-d', strtotime('-7 day', strtotime(date("Y-m-d")))))
                        ->group_by("d.Fk_Id_Medicion")
                    ;
                }

                if ($id == "mes") {
                    $this->db
                        ->where("DATE_FORMAT(d.Fecha,'%Y-%m-%d') >=", date('Y-m-d', strtotime('-1 month', strtotime(date("Y-m-d")))))
                        ->group_by("d.Fk_Id_Medicion")
                    ;
                }
                
                // return $this->db->get_compiled_select(); // string de la consulta
                return $this->db->get()->result();
            break;
        }
    }
}
/* Fin del archivo Panel_model.php */
/* Ubicación: ./application/models/Panel_model.php */