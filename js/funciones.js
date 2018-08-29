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

function RGB2Color(r,g,b)
{
  return `#${this.byte2Hex(r)}${this.byte2Hex(g)}${this.byte2Hex(b)}`;
}

function byte2Hex (n)
{
  var nybHexString = "0123456789ABCDEF";
  return String(nybHexString.substr((n >> 4) & 0x0F,1)) + nybHexString.substr(n & 0x0F,1);
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
    // Carga de la interfaz
    $(`#${contenedor}`).load(url, datos);
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
    registros = ajax(datos.url, {"tipo": datos.tipo, "id": datos.id}, "JSON");

    // Se recorren las vías y se alimenta la lista desplegable
    $.each(registros, function(clave, valor) {
        datos.elemento_hijo.append("<option value='" + valor.Pk_Id + "'>" + valor.Nombre + "</option>");
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
 * Convierte la cadena de texto del input en mayúsculas
 * 
 * @param  {el} elemento [Elemento a convertir]
 */
function convertir_mayusculas(elemento)
{
    $(elemento).val($(elemento).val().toUpperCase())
}


/**
 * Configura los filtros por defecto guardados previamente
 * 
 * @param  {int} id_modulo [Id del módulo de donde se tomarán los filtros]
 * 
 * @return {void}
 */
function filtros_por_defecto(id_modulo)
{
    // Consulta del filtro
    filtro = ajax(`${$("#url").val()}/configuracion/obtener`, {"tipo": "filtro", "id": id_modulo}, 'JSON')
    // imprimir(filtro, "tabla")

    // Si tiene vía
    if (filtro.Fk_Id_Via || filtro.Fk_Id_Via > 0) {
        // Se consultan las vías del sector
        datos = {
            url: `${$("#url").val()}/configuracion/obtener`,
            tipo: "vias",
            id: filtro.Fk_Id_Sector,
            elemento_padre: $("#select_sector"),
            elemento_hijo: $("#select_via"),
            mensaje_padre: "Elija primero un sector",
            mensaje_hijo: "Todas las vías"
        }
        cargar_lista_desplegable(datos)
        
        select_por_defecto("select_via", filtro.Fk_Id_Via)
    }
    
    // Valores por defecto
    select_por_defecto("select_tipo_medicion", filtro.Fk_Id_Tipo_Medicion)
    select_por_defecto("select_sector", filtro.Fk_Id_Sector)
    select_por_defecto("select_calificacion", filtro.Calificacion)
}

/**
 * Guarda los filtros del usuario en base de datos
 * 
 * @param  {int} id_modulo  [id del módulo]
 * @param  {int} id_usuario [id del usuario]
 * 
 * @return {void}
 */
function guardar_filtros(id_modulo, id_usuario)
{
    // Filtros generales
    let id_sector = ($("#select_sector_filtro").val() != 0) ? $("#select_sector_filtro").val() : null
    let id_via = ($("#select_via_filtro").val() != 0) ? $("#select_via_filtro").val() : null
    let id_costado = ($("#select_costado_filtro").val() != 0) ? $("#select_costado_filtro").val() : null

    // Filtros específicos
    let id_tipo_medicion_resumen = $("#select_tipo_medicion_resumen").val();
    let calificacion = ($("#select_calificacion").val() != 0) ? $("#select_calificacion").val() : null;
    let id_tipo_medicion_mapa = $("#select_tipo_medicion_mapa").val();
    let id_medicion = $("#select_medicion").val();
    

    let datos = {
        "Fk_Id_Usuario": id_usuario,
        "Fk_Id_Modulo": id_modulo,
    }

    // Se elimina el filtro del usuario para el módulo específico
    ajax(`${$("#url").val()}/configuracion/eliminar`, {"tipo": "filtro", "datos": datos}, 'HTML')
    
    // Agregamos el resto de datos que se van a insertar
    datos.Fk_Id_Sector = id_sector
    datos.Fk_Id_Via = id_via
    datos.Fk_Id_Costado = id_costado
    datos.Fk_Id_Tipo_Medicion_Resumen = id_tipo_medicion_resumen
    datos.Calificacion = calificacion
    datos.Fk_Id_Tipo_Medicion_Mapa = id_tipo_medicion_mapa
    datos.Fk_Id_Medicion = id_medicion
    
    cerrar_notificaciones()
    imprimir_notificacion("Filtros personalizados actualizados.", "success")

    // Se inserta el registro con el nuevo filtro
    ajax(`${$("#url").val()}/configuracion/insertar`, {"tipo": "filtro", "datos": datos}, 'HTML')
}

/**
 * Imprime mensaje en consola
 * 
 * @param  [string] mensaje Mensaje a imprimir
 * 
 * @return [void]
 */
function imprimir(mensaje, tipo = null)
{
    switch(tipo) {
        case "tabla":
            console.table(mensaje)
        break;

        case "tiempo_inicio":
            console.time(mensaje)
        break;

        case "tiempo_final":
            console.timeEnd(mensaje)
        break;

        case "grupo":
            console.group(mensaje)
        break;

        default:
            console.log(mensaje)
    }
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
function redireccionar(url, tipo = null){
    if (tipo == "ventana") {
        window.open(url);
        return false;
    }

    location.href = url;
}

/**
 * Pone un valor por defecto a un select
 * 
 * @param  {string} elemento Nombre del select
 * @param  {string} valor    Valor del option
 *
 * @return [void]
 */
function select_por_defecto(elemento, valor)
{
    $(`#${elemento} option[value='${valor}']`).attr("selected", true)
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
			imprimir_notificacion(`El valor de ${$(`#${campos_vacios[i]}`).attr(`title`)} no puede estar vacío`, "warning");
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