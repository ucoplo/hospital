var inserto_receta=false;
var inserto_programa=false;

//evento cambio de deposito Vale Ventanilla
$("#ambulatorios_ventanilla-am_deposito").on('change', function() {
    deposito = $(this).val();
    
    
    $('#ambulatorios_ventanilla-renglones tr.multiple-input-list__item').each(function( index, elem ){
        select =  $( this ).find(".list-cell__AM_CODMON select");
        cargar_vencimiento(select);
    });



});

function codigo_unico(seleccion){
    codmon = seleccion.val();
    codmon_id = seleccion.attr('id');
    repite = false;
    $('.list-cell__AM_CODMON select').each(function( index, elem ){
        
        if ($(elem).attr('id')!=codmon_id && codmon==$(elem).val()){
            repite=true;
        }
    });

    return repite;

}
function cargar_vencimiento(elem){
    codmon = elem.val();
    deposito = $('#ambulatorios_ventanilla-am_deposito').val();
    $.ajax({
        url: 'index.php?r=ambulatorios_ventanilla/vencimiento_vigente_codmon',
        dataType: 'json',
        method: 'POST',
        data: {codmon: codmon,deposito: deposito},
        success: function (data, textStatus, jqXHR) {
            
            input_Vencimientos = elem.closest('td').next().next().find('input');

            descripcion_error = elem.closest('td').next().find('div.help-block');
            descripcion_error.attr("style","color:#a94442;");
            if (data['fecha'].length>0){
               
               input_Vencimientos.val(data['fecha']); 
                descripcion_error.text('');
            }else{
                if (codmon.length>0 && deposito.length>0){
                    input_Vencimientos.val(data['fecha']); 
                    descripcion_error.text("No posee lotes");
                }else{
                    input_Vencimientos.val(data['fecha']); 

                }
            }     
            
        },
  
    });
}


//Modal Buscar Paciente-----------------------------------------------------------------------------------------------------


    $(document).on('click', '.showModalButton', function(){
    
         $('#modal_form_ventanilla').modal('show');
          $('#modalContent').load($(this).attr('value'));
       
    });


function cerrarModal() {
    $("#modal_form_ventanilla").modal("hide");
}

function cambiarPagina(cant) {
    var pagCampo = $("#paginaPacientes");
    var pagActual = pagCampo.val();
    pagCampo.val(parseInt(pagActual) + cant); //TODO: transformar en entero antes de hacer la suma
    buscarPaciente(false);
}

function buscarPaciente(resetearPagina = true){
    if (resetearPagina)
        $("#paginaPacientes").val(0);
    
    var parametros = $("#formBuscarPaciente").serialize();
    $.ajax({
            data:  parametros,
            url:   'index.php?r=paciente/index',
            type:  'POST',
            beforeSend: function () {
                    $('#modalContent').html("Procesando, espere por favor...");
            },
            success:  function (response) {
                    $('#modalContent').html(response);
            },
            error: function (response) {
                    $('#modalContent').html('');
            },
            dataType:'html'
    });
}

function monthDiff(dt1, dt2) {
    var ret = {months:0, years:0};

    if (dt1 === null || dt2 === null)
        return ret;

    var year1 = dt1.getFullYear();
    var year2 = dt2.getFullYear();
    var month1 = dt1.getMonth();
    var month2 = dt2.getMonth();

    ret['years'] = year2 - year1;
    ret['months'] = month2 - month1;

    if (ret['months'] < 0)
    {
        ret['months'] += 12;
        ret['years'] -= 1;
    }

    return ret;
}

function cargarDatosPaciente(apenom,nombre,apellido,sexo,tipdoc,numdoc,fecnac,hiscli,direc,telef,codos,osdesc,nroafi,entder,
                              nivel,locnac,locnacdescripcion,venniv,observ,desc_nacion,desc_provincia,desc_partido,desc_localidad){
    d1 = new Date(fecnac);
    d2 = new Date();

    edadPaciente = monthDiff(d1, d2);
    if (nombre==''){
        $('#paciente-pa_apellido').val(apenom);
        $('#paciente-pa_nombre').val(apenom);
    }else{
        $('#paciente-pa_apellido').val(apellido);
        $('#paciente-pa_nombre').val(nombre);
    }

    if (venniv=='0000-00-00')
        venniv = '';
    else{
        venniv = venniv.substring(8,10)  + "-" + venniv.substring(5,7) + "-" + venniv.substring(0,4);
    }

    $('#campoDatosPaciente').val(hiscli+' - '+apenom);
    $('#paciente-pa_apenom').val(apenom);
    $('#paciente-pa_tipdoc').val(tipdoc);
    $('#paciente-pa_numdoc').val(numdoc);
    $('#ambulatorios_ventanilla-am_hiscli').val(hiscli);
    $('#ambulatorios_ventanilla-am_entider').val(entder).trigger("change");
    
    
    $('#paciente-pa_sexo').val(sexo);
    $('#paciente-pa_edad').val(edadPaciente.years);

    $('#paciente-pa_locnac').val(locnac);
    $('#paciente-pa_locnac_descripcion').val(locnacdescripcion);
    
    $('#paciente-pa_codos').val(codos);
    $('#paciente-pa_codos_descricpion').val(osdesc);
    $('#paciente-pa_nivel').val(nivel);
    $('#paciente-pa_venniv').val(venniv);
    $('#paciente-pa_observ').val(observ);

   $('.ambulatorios-ventanilla-masdatos').find('tr:nth-child(1)').children('td').html(desc_nacion);
   $('.ambulatorios-ventanilla-masdatos').find('tr:nth-child(2)').children('td').html(desc_provincia);
   $('.ambulatorios-ventanilla-masdatos').find('tr:nth-child(3)').children('td').html(desc_partido);
   $('.ambulatorios-ventanilla-masdatos').find('tr:nth-child(4)').children('td').html(desc_localidad);
   $('.ambulatorios-ventanilla-masdatos').find('tr:nth-child(5)').children('td').html(direc);
   $('.ambulatorios-ventanilla-masdatos').find('tr:nth-child(6)').children('td').html(telef);
    
    //cargarValoresDeArchivos(hiscli);

    // Asignar los datos del paciente en la consulta del botón Modificar
    
    
    val = $("#btn_historial_retiros").attr("value");//, "/hospi/farmacia/web/index.php?r=ambulatorios_ventanilla%2Fhistorialretiros&AM_HISCLI=" + hiscli);
    n = val.indexOf("historialretiros");
    val = val.substring(0,n)+"historialretiros&AM_HISCLI=" + hiscli;
    $("#btn_historial_retiros").attr("value",val);

    val = $("#btn_recetas").attr("value");//, "/hospi/farmacia/web/index.php?r=ambulatorios_ventanilla%2Fhistorialretiros&AM_HISCLI=" + hiscli);
    n = val.indexOf("recetaspaciente");
    val = val.substring(0,n)+"recetaspaciente&AM_HISCLI=" + hiscli;
    $("#btn_recetas").attr("value",val);

  
    
    cerrarModal();
}

//Vaciar contenido de ventana modal cuando se cierra
$('#modal_form_ventanilla').on('hidden.bs.modal', function (e) {
  $(this).find('#modalContent').html('');
})

function cargarRenglonesVale(nroreceta,hiscli,medico){
    if ((!inserto_receta) && (!inserto_programa)){
    $.ajax({
        url: 'index.php?r=ambulatorios_ventanilla/recetas_renglones',
        dataType: 'json',
        method: 'POST',
        data: {nroreceta: nroreceta,hiscli: hiscli},
        success: function (data, textStatus, jqXHR) {
             //se completa el medico del vale con la receta
             $('#ambulatorios_ventanilla-am_medico').val(medico).trigger("change");
            
             //se agtregan los renglones de la receta al vale
             cant_renglones =  $('#ambulatorios_ventanilla-renglones').find('tr.multiple-input-list__item').length;
             for (var i = data.length - 1; i >= 0; i--) {
               
                $('#ambulatorios_ventanilla-renglones').find('.js-input-plus').click();
   
                fila = $('#ambulatorios_ventanilla-renglones').find('tr').last();
                fila.children('.list-cell__AM_CODMON').find('select').val(data[i].codmon).trigger("change");
                fila.children('.list-cell__descripcion').find('input').val(data[i].descripcion);

                cargar_vencimiento(fila.children('.list-cell__AM_CODMON').find('select'));
                cargar_cantidad_acumulada(fila.children('.list-cell__AM_CODMON').find('select'));
             };
        },
  
    });
    inserto_receta = true;
    }else{
        if (inserto_receta)
            krajeeDialog.alert("Ya se insertaron medicamentos de una receta.");
        else
            krajeeDialog.alert("Ya se insertaron medicamentos de un Programa");
    }
     cerrarModal();
}

function cargar_cantidad_acumulada(elem){
    codmon = elem.val();
    hiscli = $("#ambulatorios_ventanilla-am_hiscli").val();
    $.ajax({
        url: 'index.php?r=ambulatorios_ventanilla/cantidad_acumulada',
        dataType: 'json',
        method: 'POST',
        data: {codmon: codmon, hiscli: hiscli},
        success: function (data, textStatus, jqXHR) {
            input_acumulado = elem.closest('tr').find('td.list-cell__cant_acumulada input');
            input_acumulado.val(data);
            
        },
  
    });
}


  

  $('.list-cell__AM_CANTPED input').focusout(function() {
    codmon = $(this).closest('tr').find('.list-cell__AM_CODMON select').val();
    deposito = $('#ambulatorios_ventanilla-am_deposito').val();
    cant_pedida = $(this).val();
    elem_pedido = $(this);
    
    
    $.ajax({
        url: 'index.php?r=ambulatorios_ventanilla/verificar_stock',
        dataType: 'json',
        method: 'POST',
        data: {codmon: codmon, deposito: deposito, cant_pedida: cant_pedida},
        success: function (data, textStatus, jqXHR) {
            input_retirado = elem_pedido.closest('td').next().find('input');
            
            input_retirado.val(data['cant_a_retirar']);
        },
    });
    
  });

$('.multiple-input').on('afterInit', function(){
    console.log('calls on after initialization event');
}).on('beforeAddRow', function(e) {
    console.log('calls on before add row event');
}).on('afterAddRow', function(e) {
     $('.list-cell__AM_CANTPED input').focusout(function() {
    codmon = $(this).closest('tr').find('.list-cell__AM_CODMON select').val();
    deposito = $('#ambulatorios_ventanilla-am_deposito').val();
    cant_pedida = $(this).val();
    elem_pedido = $(this);
    
    
    $.ajax({
        url: 'index.php?r=ambulatorios_ventanilla/verificar_stock',
        dataType: 'json',
        method: 'POST',
        data: {codmon: codmon, deposito: deposito, cant_pedida: cant_pedida},
        success: function (data, textStatus, jqXHR) {
            input_retirado = elem_pedido.closest('td').next().find('input');
            
            input_retirado.val(data['cant_a_retirar']);
        },
    });
    
  });
    console.log('calls on after add row event');
}).on('beforeDeleteRow', function(e, row){
    // row - HTML container of the current row for removal. 
    // For TableRenderer it is tr.multiple-input-list__item
    console.log('calls on before remove row event.');
    return confirm('¿Esta seguro de eliminar el renglón?')
}).on('afterDeleteRow', function(){
    console.log('calls on after remove row event');
});

function ValidarPaciente(){
    hc = $('#ambulatorios_ventanilla-am_hiscli').val();
    if (hc == null || hc.length == 0 || /^\s*$/.test(hc))
        return false
    else
        return true;
                               
}
 $(document).on('click', '#btn_siguiente_vale', function(){
    if (ValidarPaciente()){
        $('#datosPaciente').hide();
        $('#datosVale').show();
        $('#campoPaciente').show();
    }
    else
        krajeeDialog.alert("Debe seleccionar un Paciente");
 });

function cargarRenglonesPrograma(programa){
    if (!inserto_receta){
        renglones = $('#ambulatorios_ventanilla-renglones').find('tr.multiple-input-list__item');
               $.each( renglones, function( key, value ) {
                  value.remove();
                });
        $.ajax({
            url: 'index.php?r=ambulatorios_ventanilla/programa_renglones',
            dataType: 'json',
            method: 'POST',
            data: {programa: programa},
            success: function (data, textStatus, jqXHR) {
                             
                 //se agtregan los renglones del programa
                 cant_renglones =  $('#ambulatorios_ventanilla-renglones').find('tr.multiple-input-list__item').length;
                 for (var i = data.length - 1; i >= 0; i--) {
                    
                   
                        $('#ambulatorios_ventanilla-renglones').find('.js-input-plus').click();
                        
                   
                    
                    fila = $('#ambulatorios_ventanilla-renglones').find('tr').last();
                    fila.children('.list-cell__AM_CODMON').find('select').val(data[i].codmon).trigger("change");
                    fila.children('.list-cell__descripcion').find('input').val(data[i].descripcion);
                    fila.children('.list-cell__AM_CANTPED').find('input').val(data[i].cantidad);
                    fila.children('.list-cell__AM_CANTENT').find('input').val(data[i].cantidad);
                    
                    cargar_vencimiento(fila.children('.list-cell__AM_CODMON').find('select'));
                    cargar_cantidad_acumulada(fila.children('.list-cell__AM_CODMON').find('select'));
                    
                 };
            },
  
        });
        inserto_programa = true;
    }else{
        krajeeDialog.alert("No se insertan renglones ya que fue insertada una receta.");
    }
 
}

function cargar_datos_paciente(e,datum) {
        // buscar paciente y mostrar información en la interfaz
        //$('.showModalButton').addClass('hide');
        $.ajax({
            url: 'index.php?r=paciente/fetch_paciente',
            dataType: 'JSON',
            method: 'POST',
            data: {
                PA_HISCLI: datum.cod
            },
            success: function (paciente) {
                // $('#ambulatorios_ventanilla-am_hiscli').val(paciente.PA_HISCLI);
                // $('#ambulatorios_ventanilla-am_entider').val(paciente.PA_ENTDE).trigger("change");

                $('#search-paciente').val('');
                
                datos = paciente.paciente;
                cargarDatosPaciente(datos.PA_APENOM, datos.PA_NOMBRE, datos.PA_APELLIDO,paciente.sexo,datos.PA_TIPDOC,
                                    datos.PA_NUMDOC,datos.PA_FECNAC,datos.PA_HISCLI,
                                    datos.PA_DIREC ,datos.PA_TELEF,datos.PA_CODOS,
                                    paciente.OSDescripcion,datos.PA_NROAFI,datos.PA_ENTDE,
                                    datos.PA_NIVEL,datos.PA_LOCNAC,paciente.localidadNacimientoDescripcion,
                                    datos.PA_VENNIV,datos.PA_OBSERV,
                                    paciente.NA_DETALLE,paciente.PR_DETALLE,paciente.PT_DETALLE,
                                    paciente.LO_DETALLE);


            },
        });




    }

$( document ).ready(function() {
    $('.date').mask('00/00/0000');
  $('.time').mask('00:00:00');
  $('.date_time').mask('00/00/0000 00:00:00');
  $('.cep').mask('00000-000');
  $('.phone').mask('0000-0000');
  $('.phone_with_ddd').mask('(00) 0000-0000');
  $('.phone_us').mask('(000) 000-0000');
  $('.mixed').mask('AAA 000-S0S');
  $('.cpf').mask('000.000.000-00', {reverse: true});
  $('.cnpj').mask('00.000.000/0000-00', {reverse: true});
  $('.money').mask('000000000.00', {reverse: true});
  $('.money2').mask("#.##0,00", {reverse: true});


  // $("#btnguardar").on("click", function() {
  //    krajeeDialog.confirm("¿Confirma el Vale de Ventanilla?", function (result) {
  //       if (result) {
  //           return true;
  //       } else {
  //           return false;
  //       }
  //   });
  // });
  $('#ambulatorios_ventanilla-am_prog').change(function () {
        programa = $(this).val();
        if (programa == null || programa.length == 0 || /^\s*$/.test(programa)){
        }else{
           
           cargarRenglonesPrograma(programa);
       }
  });
});
