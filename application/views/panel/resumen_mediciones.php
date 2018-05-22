<script type="text/javascript">
	$(document).ready(function(){

		// Recolección de valores
		let id_sector = ($("#select_sector").val() != 0) ? $("#select_sector").val() : null;
		let id_via = ($("#select_via").val() != 0) ? $("#select_via").val() : null;
		let calificacion = ($("#select_calificacion").val() != 0) ? $("#select_calificacion").val() : null;

		// Se consultan los valores de calificaciones (de 1 a 5)
		calificaciones = ajax("<?php echo site_url('panel/obtener'); ?>", {"tipo": "calificaciones", "id": calificacion}, 'JSON')
		// imprimir(calificacion)

		// Arreglo con los datos de las calificaciones
		datos_calificaciones = []
		
		// Recorrido de las calificaciones para establecer cada línea
		$.each(calificaciones, function(key, calificacion){
			filtros = {
				"calificacion": calificacion.Valor,
				"sector": id_sector,
				"via": id_via,
			}

			mediciones_por_calificacion = ajax("<?php echo site_url('panel/obtener'); ?>", {"tipo": "valores_por_calificacion", "id": filtros}, 'JSON')

			valor2 = []
			titulos_mediciones = []

			$.each(mediciones_por_calificacion, function(key, detalle){
				titulos_mediciones.push(detalle.Titulo)
				valor2.push(parseFloat(detalle.Total))
			})

			datos_calificaciones.push({
				name: calificacion.Descripcion,
				color: RGB2Color(calificacion.Color_R, calificacion.Color_G, calificacion.Color_B),
				// Listado de cada una de las mediciones
				data: valor2
			})
        })

		Highcharts.chart('cont_mediciones', {
		    chart: {
		        type: 'line'
		    },
		    title: {
		        text: 'Resumen de mediciones'
		    },
		    subtitle: {
		        text: "<?php echo $this->configuracion_model->obtener("formato_fecha", date('Y-m-d')); ?>"
		    },
		    xAxis: {
		    	// Mediciones (nombradas por fechas)
		        categories: titulos_mediciones
		    },
		    yAxis: {
		        title: {
		            text: 'Total'
		        }
		    },
		    plotOptions: {
		        line: {
		            dataLabels: {
		                enabled: false
		            },
		            enableMouseTracking: true
		        }
		    },
		    series: datos_calificaciones
		})
		
		$(".highcharts-credits").html("")
	
	})
</script>