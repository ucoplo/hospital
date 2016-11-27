//evento cambio de deposito Devolucion
$("#perdidas-dp_deposito").on('change', function() {
    deposito = $(this).val();
    
    
    $('#perdidas-renglones tr.multiple-input-list__item').each(function( index, elem ){
        select =  $( this ).find(".list-cell__DR_CODART select");
        cargar_vencimientos(select);
    });
});

//carga los vencimientos disponi
function cargar_vencimientos(elem){
	codart = elem.val();
    
	deposito = $('#perdidas-dp_deposito').val();
    $.ajax({
        url: 'index.php?r=perdidas/vencimientos_vigentes_select',
        dataType: 'json',
        method: 'POST',
        data: {codart: codart,deposito: deposito},
        success: function (data, textStatus, jqXHR) {
        	select_Vencimientos = elem.closest('td').next().next().find('select');
            descripcion = elem.closest('td').next().find('input');
            
            if (data!=''){
                select_Vencimientos.html(data);        
                select_Vencimientos.prop("selectedIndex", 0);
                descripcion.parent().find('.help-block-error').remove();
            }else{
                if (codart!=null && codart.length>0 && deposito.length>0){
                    select_Vencimientos.html(data);
                    select_Vencimientos.prop("selectedIndex", -1);
                    descripcion.parent().find('.help-block-error').remove();
                    $( '<div class="help-descripcion help-block-error" style="color:#a94442;">No posee Lotes.</div>' ).insertAfter(descripcion);
                }else{
                    select_Vencimientos.html(data);
                    select_Vencimientos.prop("selectedIndex", -1);
                }
            }
        },
  
    });
}

function codigo_unico(seleccion){
  codart = seleccion.val();
  codart_id = seleccion.attr('id');
  unico = true;
  $('.list-cell__DR_CODART select').each(function( index, elem ){
      
      if ($(elem).attr('id')!=codart_id && codart==$(elem).val()){
          unico=false;
      }
  });

  return unico;

}