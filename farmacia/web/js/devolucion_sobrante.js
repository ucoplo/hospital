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

