 $( document ).ready(function() {
    $('.multiple-input').on('afterInit', function(){
        console.log('calls on after initialization event');
    }).on('beforeAddRow', function(e) {
        console.log('calls on before add row event');
    }).on('afterAddRow', function(e) {
        
        row = $('.multiple-input-list__item:last');
        
        select = row.find('select');
        select.removeAttr('disabled');

        $( '.list-cell__PE_CANTPED input' ).change(function() {
           $('#pedido_adquisicion-pe_costo').val(calcular_costo());
        });
             

        console.log('calls on after add row event');
    }).on('beforeDeleteRow', function(e, row){
        
        console.log('calls on before remove row event.');

    }).on('afterDeleteRow', function(){
        $('#pedido_adquisicion-pe_costo').val(calcular_costo());
    });

    // $("#pedido_adquisicion-pe_artdes").select2({
    //   ajax: {
    //     data: function (params) {
    //       var query = {
    //         search: params.term,
    //         page: params.page,
            
    //       }

    //       // Query paramters will be ?search=[term]&page=[page]
    //       return query;
    //     }
       
    //   }
    // });

    $( '.list-cell__PE_CANTPED input' ).change(function() {
       $('#pedido_adquisicion-pe_costo').val(calcular_costo());
    });


});

function calcular_costo(){
  costo = 0;
  $('.multiple-input-list__item').each(function( index, elem ){
      
      costo = costo + ($(elem).find('.list-cell__precio input').val()*$(elem).find('.list-cell__PE_CANTPED input').val());
          
  });

  return costo;

}

function codigo_unico(seleccion){
  codart = seleccion.val();
  codart_id = seleccion.attr('id');
  unico = true;
  $('.list-cell__BUSCAR_ARTICULO select').each(function( index, elem ){
      
      if ($(elem).attr('id')!=codart_id && codart==$(elem).val()){
          unico=false;
      }
  });

  return unico;

}