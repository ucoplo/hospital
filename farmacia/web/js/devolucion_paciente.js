function codigo_unico(seleccion){
  codmon = seleccion.val();
  codmon_id = seleccion.attr('id');
  repite = false;
  $('.list-cell__DV_CODMON select').each(function( index, elem ){
      
      if ($(elem).attr('id')!=codmon_id && codmon==$(elem).val()){
          repite=true;
      }
  });

  return repite;

}

function cargar_vencimiento(elem){
    codmon = elem.val();
    deposito = $('#devolucion_salas_paciente-de_deposito').val();
    vale = $('#devolucion_salas_paciente-de_numvalor').val();
    $.ajax({
        url: 'index.php?r=devolucion_salas_paciente/vencimiento_codmon_vale',
        dataType: 'json',
        method: 'POST',
        data: {codmon: codmon,deposito: deposito,vale:vale},
        success: function (data, textStatus, jqXHR) {
            
            input_Vencimientos = elem.closest('td').next().next().find('input');

            input_Vencimientos.val(data['fecha']);        
            
        },
  
    });
}
