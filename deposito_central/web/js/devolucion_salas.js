function codigo_unico(seleccion){
  codmon = seleccion.val();
  codmon_id = seleccion.attr('id');
  repite = false;
  $('.list-cell__PR_CODART select').each(function( index, elem ){
      
      if ($(elem).attr('id')!=codmon_id && codmon==$(elem).val()){
          repite=true;
      }
  });

  return repite;

}

function cargar_vencimiento(elem){
    codart = elem.val();
    deposito = $('#devolucion_salas-de_deposito').val();
    remito = $('#devolucion_salas-de_numremor').val();
    $.ajax({
        url: 'index.php?r=devolucion_salas/vencimiento_codart_remito',
        dataType: 'json',
        method: 'POST',
        data: {codart: codart,deposito: deposito,remito:remito},
        success: function (data, textStatus, jqXHR) {
            
            input_Vencimientos = elem.closest('td').next().next().find('input');

            input_Vencimientos.val(data['fecha']);        
            
        },
  
    });
}
