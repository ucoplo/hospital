function codigo_unico(seleccion){
  codmon = seleccion.val();
  codmon_id = seleccion.attr('id');
  repite = false;
  $('.list-cell__DF_CODMON select').each(function( index, elem ){
      
      if ($(elem).attr('id')!=codmon_id && codmon==$(elem).val()){
          repite=true;
      }
  });

  return repite;

}

function cargar_vencimiento(elem){
    codmon = elem.val();
    deposito = $('#devolucion_salas_granel-de_deposito').val();
    remito = $('#devolucion_salas_granel-de_numremor').val();
    $.ajax({
        url: 'index.php?r=devolucion_salas_granel/vencimiento_codmon_remito',
        dataType: 'json',
        method: 'POST',
        data: {codmon: codmon,deposito: deposito,remito:remito},
        success: function (data, textStatus, jqXHR) {
            
            input_Vencimientos = elem.closest('td').next().next().find('input');

            input_Vencimientos.val(data['fecha']);        
            
        },
  
    });
}
