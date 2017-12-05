/**
 * Método para realizar consultas y carga
 * de información mediante Ajax
 * 
 * @param  [string]  url            Url del controlador y método que consultará
 * @param  [string]  datos          Datos que enviará. Normalmente en formato JSON
 * @param  [string]  tipo_respuesta La respuesta que retorna. Se da en Ajax o HTML
 * @param  [boolean] async          paso de datos síncrono o asíncrono
 * 
 * @return [array]                Respuesta con los datos
 * 
 */
function ajax(url, datos, tipo_respuesta, async = false){
    //Variable de exito
    var exito;

    // Esta es la petición ajax que llevará 
    // a la interfaz los datos pedidos
    $.ajax({
        url: url,
        data: datos,
        type: "POST",
        dataType: tipo_respuesta,
        async: async,
        success: function(respuesta){
            //Si la respuesta no es error
            if(respuesta){
                //Se almacena la respuesta como variable de éxito
                exito = respuesta;
            } else {
                //La variable de éxito será un mensaje de error
                // exito = 'error';
                exito = respuesta;
            } //If
        },//Success
        error: function(respuesta){
            //Variable de exito será mensaje de error de ajax
            exito = respuesta;
        }//Error
    });//Ajax

    //Se retorna la respuesta
    return exito;
}

/**
 * Oculta todos los íconos y habilita
 * los que estén parametrizados en cada
 * interfaz
 * 
 * @param  [array] parametros íconos a habilitar
 * 
 * @return [void]
 */
function botones(parametros)
{
    $("[id^='icono_']").hide();

    // Si trae parámetros
    if (parametros) {
        $("#menu_superior > div").removeClass("uk-hidden");
        
        for (i = 0; i < parametros.length; i++) { 
            $("#icono_" + parametros[i]).show();
        }
    }
}

function cargar_interfaz(contenedor, url, datos)
{
    // Configuración de los botones (de esta manera entran desactivados)
    // botones();

    // Carga de la interfaz
    $("#" + contenedor).load(url, datos);
}

/**
 * Se limpia la lista, se consultan los elementos
 * y se cargan en la lista nuevamente
 * 
 * @param  [array] datos    Datos a cargar y mostrar
 * 
 * @return [void]
 */
function cargar_lista_desplegable(datos){
    // Si no se elige ninguna opción, se limpia la lista
    if (datos.elemento_padre.val() == "") {
        limpiar_lista(datos.elemento_hijo, datos.mensaje_padre);

        return false;
    }
    
    // Se limpia la lista
    limpiar_lista(datos.elemento_hijo, datos.mensaje_hijo);

    // Consulta de las vías del sector seleccionado
    vias = ajax(datos.url, {"tipo": datos.tipo, "id": datos.id}, "JSON");

    // Se recorren las vías y se alimenta la lista desplegable
    $.each(vias, function(clave, via) {
        datos.elemento_hijo.append("<option value='" + via.Pk_Id + "'>" + via.Nombre + "</option>");
    });

    // Se pone el foco en la siguiente lista desplegable
    datos.elemento_hijo.focus();
}

/**
 * Cierra todas las notificaciones
 * en pantalla
 * 
 * @return [void]
 */
function cerrar_notificaciones()
{	
	UIkit.notification.closeAll();
}

/**
 * Imprime mensaje en consola
 * 
 * @param  [string] mensaje Mensaje a imprimir
 * 
 * @return [void]
 */
function imprimir(mensaje)
{
    console.log(mensaje);
}

/**
 * Imprime el mensaje de notificación en pantalla
 * 
 * @param  [string] tipo    primary, success, warning, danger
 * @param  [string] mensaje Mensaje de la notificación
 * 
 * @return [void]
 */
function imprimir_notificacion(mensaje, tipo = null)
{
	// datos para la notificación
	datos = {
	    message: mensaje,
	    pos: 'bottom-center',
	    timeout: 0
	}

	// Si trae un tipo (para formatear el mensaje)
	if (tipo) {
		datos.status = tipo;
	}

	// Si la notificación es una un mensaje de éxito
	if(tipo == "success"){
		datos.timeout = 5000;
	}

	// Se lanza la notificación
	UIkit.notification(datos);
}

/**
 * Limpia la lista desplegable y deja la opción por defecto
 * 
 * @param  [element]    elemento elemento del formulario (lista)
 * @param  [string]     mensaje  Mensaje de la opción por defecto
 * 
 * @return [void]
 */
function limpiar_lista(elemento, mensaje){
    elemento.html('').append("<option value=''>" + mensaje + "</option>");
}

/**
 * Redirige a la interfaz indicada
 * 
 * @param  [string] url Dirección a donde se dirige
 * 
 * @return [void]     
 */
function redireccionar(url){
    location.href = url;
}

/**
 * Recorre los campos y obligatorios buscando
 * que todos estén diligenciados
 * 
 * @param  [array] campos Arreglo de campos a validar
 * 
 * @return [array]        Campos que no se han diligenciado
 */
function validar_campos_obligatorios(campos)
{
    campos_vacios = new Array();

    // Se recorren los registros y  se almacenan en un arreglo
    // los nombres de los campos que están vacíos
    $.each(campos, function(clave, campo) {
    	if ($.trim(campo) == "") {
    		campos_vacios.push(clave);
    	}
    });

    // Si existen campos obligatorios sin diligenciar,
    // se recorre cada campo y se genera notificación en pantalla
    if(campos_vacios.length > 0){
        cerrar_notificaciones();

        for (var i = 0; i < campos_vacios.length; i++){
            imprimir($("#" + campos_vacios[i]).attr("id"))
			imprimir_notificacion("El valor de " + $("#" + campos_vacios[i]).attr("title")  + " no puede estar vacío", "warning");
		}
	}

    // Si hay campos vacíos se retorna el arreglo,
    // sino, false para continuar con el proceso del formulario
	if (campos_vacios.length > 0) {
		return true;
	}
}

/**
 * Se recorren los checks y se busca que
 * al menos esté uno marcado
 * 
 * @param  [string] elemento nombre del id de los checks
 * 
 * @return [boolean]          true = no hay checks marcados
 */
function validar_checks(elemento)
{
    var marcados = 0;

    // Se recorren los checks y se acumulan los marcados
    $("#" + elemento + ":checked").each(function(){
        marcados++;
    });

    if (marcados < 1) {
        return true;
    }
}