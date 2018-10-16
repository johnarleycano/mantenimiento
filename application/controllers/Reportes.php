<?php
date_default_timezone_set('America/Bogota');

defined('BASEPATH') OR exit('El acceso directo a este archivo no está permitido');

/**
 * @author: 	John Arley Cano Salinas
 * Fecha: 		11 de enero de 2018
 * Programa:  	Mantenimiento | Módulo de reportes
 *            	Permite generar reportes 
 *            	en todos los formatos
 * Email: 		johnarleycano@hotmail.com
 */
Class Reportes extends CI_Controller{
	/**
	 * Función constructora de la clase. Se hereda el mismo constructor 
	 * de la clase para evitar sobreescribirlo y de esa manera 
     * conservar el funcionamiento de controlador.
	 */
	function __construct() {
        parent::__construct();

        // Carga de modelos
        $this->load->model(array('configuracion_model', 'reportes_model', 'basculas_model', 'mediciones_model'));

        // Carga de librerías
        require('system/libraries/Fpdf.php');
        require('system/libraries/Pdf_js.php');

        // Definición de la ruta de las fuentes
        define('FPDF_FONTPATH','system/fonts/');
    }

    /**
     * Reportes gráficos
     * 
     * @return [void]
     */
    function graficos()
    {
    	switch ($this->input->post("tipo")) {
            case 'resumen_mediciones':
                $this->load->view("reportes/graficos/resumen_mediciones");
            break;
    	}
    }

    /**
     * Mapas
     * 
     * @return [void]
     */
    function mapas()
    {
        switch ($this->uri->segment(3)) {
            case 'prueba':
                $this->data['titulo'] = 'Mapa';
                $this->data['via'] = $this->uri->segment(4);
                $this->data['contenido_principal'] = 'reportes/mapas/prueba';
                $this->load->view('core/template', $this->data);
            break;
        }
    }

    /**
     * Reportes en PDF
     * 
     * @return [void]
     */
    function pdf()
    {
        switch ($this->uri->segment(3)) {
            case 'medicion':
                // Se inserta el registro de logs enviando tipo de log y dato adicional si corresponde
                $this->logs_model->insertar(6, "Medición {$this->uri->segment(4)}");
                
                $this->load->view("reportes/pdf/medicion");
            break;

            case 'certificado_pesaje':
                // Se inserta el registro de logs enviando tipo de log y dato adicional si corresponde
                $this->logs_model->insertar(6, "Medición {$this->uri->segment(4)}");
                
                $this->load->view("reportes/pdf/certificado_pesaje");
            break;
        }
    }

    function wkb_to_json($wkb) {
        $geom = geoPHP::load($wkb,'wkb');
        return $geom->out('json');
    }

    function obtener(){
        # Build GeoJSON feature collection array
        $geojson = array(
           'type'      => 'FeatureCollection',
           'features'  => array()
        );

        foreach ($this->configuracion_model->obtener("vias_geometrias", $this->input->post("via")) as $registro) {
            $feature = array(
                 'type' => 'Feature',
                 'geometry' => json_decode($this->wkb_to_json($registro->wkb)),
                 'properties' => $registro
            );

            unset($registro->wkb);
            unset($registro->Shape);
            # Add feature arrays to feature collection array
            array_push($geojson['features'], $feature);

        }

echo json_encode($geojson, JSON_NUMERIC_CHECK);

    }
}
/* Fin del archivo Reportes.php */
/* Ubicación: ./application/controllers/Reportes.php */