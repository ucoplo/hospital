
//evento cambio de deposito Devolucion
$("#devolucion_proveedor-dd_deposito").on('change', function() {
    deposito = $(this).val();
    
    
    $('#devolucion_proveedor-renglones tr.multiple-input-list__item').each(function( index, elem ){
        select =  $( this ).find(".list-cell__DP_CODART select");
        cargar_vencimientos(select);
    });
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

//carga los vencimientos disponi
function cargar_vencimientos(elem){
	codart = elem.val();
	deposito = $('#devolucion_proveedor-dd_deposito').val();
    $.ajax({
        url: 'index.php?r=devolucion_proveedor/vencimientos_vigentes_select',
        dataType: 'json',
        method: 'POST',
        data: {codart: codart,deposito: deposito},
        success: function (data, textStatus, jqXHR) {
      //   	select_Vencimientos = elem.closest('td').next().next().find('select');
      //       fecha_update = select_Vencimientos.closest('td').next().find('input').val();

    		// select_Vencimientos.html(data);        
      //       select_Vencimientos.prop("selectedIndex", -1);

            select_Vencimientos = elem.closest('td').next().next().find('select');
            descripcion = elem.closest('td').next().find('input');
            
            if (data!=''){
                select_Vencimientos.html(data);        
                select_Vencimientos.prop("selectedIndex", 0);
                descripcion.parent().find('.help-block-error').remove();
            }else{
                if (codart.length>0 && deposito.length>0){
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