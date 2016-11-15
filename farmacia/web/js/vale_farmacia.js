$( document ).ready(function() {  
  
  $('.money').mask('000000000.00', {reverse: true});

  //Creacion de nuevo remito en tabla VALI_REM
  $("#nuevo_remito").on("click", function() {
    
    numero_actual =$('#numero_remito-vr_nrorem').val();
    if (numero_actual!=null)
       $('#numero_remito-vr_nrorem').val(Number(numero_actual)+1);
    else
        $('#numero_remito-vr_nrorem').val(1);

    $('#numero_remito-vr_hordes').prop("readonly", false);
    $('#numero_remito-vr_hordes').val('');
    $('#numero_remito-vr_horhas').prop("readonly", false);
    $('#numero_remito-vr_horhas').val('');
    $('#numero_remito-vr_fecdes').val('');
    $('#numero_remito-vr_fecdes-disp').val('');
    $('#numero_remito-vr_fechas').val('');
    $('#numero_remito-vr_fechas-disp').val('');

    $('.numero-remito-create').children('h2').html('Nuevo Remito');
    $("#guardar_remito").html('Comenzar Remito');
    $("#guardar_remito").removeClass('hide');
    $(".oculto").removeClass('hide');
    $(".visible").addClass('hide');
    $('#guardar_remito').prop("disabled", false); 
    $("#es_remito_nuevo").val('1');
    $("#remito_procesado").hide();


    $(this).hide();
    krajeeDialog.alert("Ingrese Las fechas y horas de vigencia del nuevo Remito");

    return false;
  });

    if ( $('#consumo_medicamentos_pacientes-cm_hiscli').val()!==''){
       $('#vales_enfermeria').hide();
    }else{
      $('.field-consumo_medicamentos_pacientes-renglones').hide();
      $('.field-consumo_medicamentos_pacientes-cm_hiscli').hide();
      $('#wrp_paciente').hide();
      $('#wrp_medico').hide();
      $('#datos_internacion').hide();
      $('.field-consumo_medicamentos_pacientes-cm_medico').hide();
      $('#wrp_guardar').hide();
      $('#datos_etiquetas').hide();
    }
 
})
// se carga el vencimiento por renglon utilizando funcion en Ambulatorios
function cargar_vencimiento(elem){
    codmon = elem.val();
    deposito = '02';
    $.ajax({
        url: 'index.php?r=ambulatorios_ventanilla/vencimiento_vigente_codmon',
        dataType: 'json',
        method: 'POST',
        data: {codmon: codmon,deposito: deposito},
        success: function (data, textStatus, jqXHR) {
            
            input_Vencimientos = elem.closest('td').next().next().find('input');

            input_Vencimientos.val(data['fecha']);        
            
        },
  
    });
}

function obtenerRenglones(nrovale,medico,hiscli,paciente_nombre,medico_nombre,sala,hab,cama,ingreso,idinterna){
  $.ajax({
      url: 'index.php?r=consumo_medicamentos_pacientes/vale_enfermeria_renglones',
      dataType: 'json',
      method: 'POST',
      data: {valeenf: nrovale},
      success: function (data, textStatus, jqXHR) {
           //se completa el medico del vale de farmacia con el de enfermería
           $('#consumo_medicamentos_pacientes-cm_medico').val(medico);//.trigger("change");
           $('#consumo_medicamentos_pacientes-cm_hiscli').val(hiscli);
           $('#consumo_medicamentos_pacientes-cm_idinterna').val(idinterna);
          
           $('#paciente_nombre').val(paciente_nombre);
           $('#medico_nombre').val(medico_nombre);
           $('#consumo_medicamentos_pacientes-vale_enfermeria').val(nrovale);

           if (sala!=null){
             //Datos Internacion
             $('#consumo_medicamentos_pacientes-sala').val(sala);
             $('#consumo_medicamentos_pacientes-habitacion').val(hab);
             $('#consumo_medicamentos_pacientes-cama').val(cama);
             $('#consumo_medicamentos_pacientes-ingreso').val(ingreso.substring(8,10)  + "-" + ingreso.substring(5,7) + "-" + ingreso.substring(0,4));
           }
           

           //se agregan los renglones del pedido al vale
           medicamentos = data['renglones'];
           cant_renglones =  $('#consumo_medicamentos_pacientes-renglones').find('tr.multiple-input-list__item').length;
           
           for (var i = medicamentos.length - 1; i >= 0; i--) {
              
              if (cant_renglones>1){
                  $('#consumo_medicamentos_pacientes-renglones').find('.js-input-plus').click();
                  
              }else{
                  cant_renglones =2;
              }
              fila = $('#consumo_medicamentos_pacientes-renglones').find('tr').last();
              fila.children('.list-cell__VA_CODMON').find('select').val(medicamentos[i].codmon).trigger("change");
              fila.children('.list-cell__descripcion').find('input').val(medicamentos[i].descripcion);
              fila.children('.list-cell__VA_CANTID').find('input').val(medicamentos[i].cantidad);
              
              cargar_vencimiento(fila.children('.list-cell__VA_CODMON').find('select'));

             
              
           }

           if (medicamentos.length>0){
              $('#vales_enfermeria').hide();
              $('.field-consumo_medicamentos_pacientes-renglones').show();
              $('.field-consumo_medicamentos_pacientes-cm_hiscli').show();
              $('.field-consumo_medicamentos_pacientes-cm_medico').show();
              $('#wrp_paciente').show();
              $('#wrp_medico').show();
              $('#wrp_guardar').show();
              $('#datos_internacion').show();

              mostrar_etiquetas(data['etiquetas']);
             
            }else{
              krajeeDialog.alert("Sin renglones a importar");
            }

      },

  });

}

function mostrar_etiquetas(etiquetas){

  for (var i = etiquetas.length - 1; i >= 0; i--) {
    etiqueta = etiquetas[i].ET_BMP.replace("../..", "");
   
    imagen = "<img id='img"+i+"' src='"+etiqueta+"'/>";
    input = "<input type='hidden' name='Consumo_medicamentos_pacientes[etiquetas]["+i+"]' value='"+etiqueta+"'>";
 
    $('#lista_etiquetas').append(imagen);
    $('#lista_etiquetas').append(input);
  }

  if (etiquetas.length>0){
    $('#datos_etiquetas').show();
  }
}
//Carga los renglones del vale de Farmacia a partir del Vale de Enfermería
function cargarRenglonesVale(nrovale,medico,hiscli,fecha,hora,paciente_nombre,medico_nombre,sala,hab,cama,ingreso,idinterna){
       
        
       var fecha_hora_vale = new Date(fecha+" "+hora);
       var fecha_desde = new Date($('#numero_remito-vr_fecdes').val()+" "+$('#numero_remito-vr_hordes').val());
       var fecha_hasta = new Date($('#numero_remito-vr_fechas').val()+" "+$('#numero_remito-vr_horhas').val());
       
       var rango_horario_valido = (fecha_desde <= fecha_hora_vale);
       var ingresa_renglones = true;

       //Compruebo si el vale seleccionado esta dentro del rango horario del Remito
       if ((fecha_desde > fecha_hora_vale)||(fecha_hora_vale>fecha_hasta)){
        krajeeDialog.confirm("El vale esta fuera del rango Horario del Remito, ¿Desea continuar?", function (result) {
            if (result) { // ok button was pressed
                ingresa_renglones = true;
            } else { // confirmation was cancelled
                ingresa_renglones = false;
            }

            if (ingresa_renglones){
              obtenerRenglones(nrovale,medico,hiscli,paciente_nombre,medico_nombre,sala,hab,cama,ingreso,idinterna)

            }
        });
       }else{
        obtenerRenglones(nrovale,medico,hiscli,paciente_nombre,medico_nombre,sala,hab,cama,ingreso,idinterna)
       }
       
    }

function codigo_unico(seleccion){
  codmon = seleccion.val();
  codmon_id = seleccion.attr('id');
  repite = false;
  $('.list-cell__VA_CODMON select').each(function( index, elem ){
      
      if ($(elem).attr('id')!=codmon_id && codmon==$(elem).val()){
          repite=true;
      }
  });

  return repite;

}

$( "#btn_entregar" ).click(function( event ) {
   //event.preventDefault();
   id=$('#nro_remito').val();
   condpac = $('#cond_pac').val();
   $.ajax({
        url: 'index.php?r=consumo_medicamentos_pacientes/procesar',
        dataType: 'json',
        method: 'GET',
        data: {id: id,condpac:condpac},
    
  
    }).done(function() {
      location.reload();
      
      
  })
  .fail(function(jqXHR, textStatus ) {
     alert( "Request failed: " + textStatus );
    
  });
   

  });
