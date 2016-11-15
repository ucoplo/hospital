function codigo_unico(seleccion){
  codmon = seleccion.val();
  codmon_id = seleccion.attr('id');
  repite = false;
  $('.list-cell__PF_CODMON select').each(function( index, elem ){

      if ($(elem).attr('id')!=codmon_id && codmon==$(elem).val()){
          repite=true;
      }
  });

  return repite;

}

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

$( "#btn_entregar" ).click(function( event ) {
   //event.preventDefault();
   id=$('#nro_remito').val();
   $.ajax({
        url: 'index.php?r=consumo_medicamentos_granel/procesar',
        dataType: 'json',
        method: 'GET',
        data: {id: id},


    }).done(function() {
      location.reload();


  })
  .fail(function() {
    alert( "error al procesar" );
  });


  });
