 $( document ).ready(function() {
    $('.multiple-input').on('afterInit', function(){
        console.log('calls on after initialization event');
    }).on('beforeAddRow', function(e) {
        console.log('calls on before add row event');
    }).on('afterAddRow', function(e) {
        
        row = $('.multiple-input-list__item:last');

        
        select = row.find('select');
        select.removeAttr('disabled');
       

        console.log('calls on after add row event');
    }).on('beforeDeleteRow', function(e, row){
        
        console.log('calls on before remove row event.');

    }).on('afterDeleteRow', function(){
        console.log('calls on after remove row event');
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



});

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