<?php 
Class Configuracion_model extends CI_Model{
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
	 * @param  [int] 	$id   Id foráneo para filtrar los datos
	 * 
	 * @return [array]       Arreglo de datos
	 */
	function obtener($tipo, $id = null)
	{
		switch ($tipo) {
			case 'abscisado':
                // Se obtienen los kilómetros
                $kilometros = substr($id, 0, strlen($id) - 3);

                // Si no tiene kilómetros
                if($kilometros == ""){
                    // Se pone 0
                    $kilometros = 0;
                } // if 

                // Se retorna el valor formateado
                return "KM ".$kilometros."+".str_pad(substr($id, -3), 3, "0", STR_PAD_LEFT);
            break;

			case "sectores":
				return $this->db_configuracion
					->order_by("Nombre")
					->get("sectores")->result();
			break;

			case "via":
				return $this->db_configuracion
					->where("Pk_Id", $id)
					->get("vias")->row();
			break;

			case "vias":
				return $this->db_configuracion
					->where("Fk_Id_Sector", $id)
					->order_by("Nombre")
					->get("vias")->result();
			break;
		}
	}
}
/* Fin del archivo Configuracion_model.php */
/* Ubicación: ./application/models/Configuracion_model.php */