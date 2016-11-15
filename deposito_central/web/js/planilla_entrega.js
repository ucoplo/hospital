function codigo_unico(seleccion){
  codart = seleccion.val();
  codart_id = seleccion.attr('id');
  repite = false;
  $('.list-cell__PR_CODART select').each(function( index, elem ){

      if ($(elem).attr('id')!=codart_id && codart==$(elem).val()){
          repite=true;
      }
  });

  return repite;

}

// se carga el vencimiento por renglon utilizando funcion en Ambulatorios
function cargar_vencimiento(elem){
    codart = elem.val();
    deposito = $('#planilla_entrega-pe_deposito').val();
    $.ajax({
        url: 'index.php?r=planilla_entrega/vencimiento_vigente_codart',
        dataType: 'json',
        method: 'POST',
        data: {codart: codart,deposito: deposito},
        success: function (data, textStatus, jqXHR) {

            input_Vencimientos = elem.closest('td').next().next().find('input');

            input_Vencimientos.val(data['fecha']);

        },

    });
}

//evento cambio de deposito 
$("#planilla_entrega-pe_deposito").on('change', function() {
    deposito = $(this).val();
    
    $('#planilla_entrega-renglones tr.multiple-input-list__item').each(function( index, elem ){
        select =  $( this ).find(".list-cell__PR_CODART select");
        cargar_vencimiento(select);
    });
});

$( "#btn_entregar" ).click(function( event ) {
    id=
   //event.preventDefault();
   id=$('#nro_remito').val(); 
   $.ajax({
        url: 'index.php?r=planilla_entrega/procesar',
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
