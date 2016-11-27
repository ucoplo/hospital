 $( document ).ready(function() {
  
});

function codigo_unico(seleccion){
  codart = seleccion.val();
  codart_id = seleccion.attr('id');
  unico = true;
  $('.list-cell__AR_CODART select').each(function( index, elem ){
      
      if ($(elem).attr('id')!=codart_id && codart==$(elem).val()){
          unico=false;
      }
  });

  return unico;

}