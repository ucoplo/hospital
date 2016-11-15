//evento cambio destino de devolucion a Proveedor
$("#devolucion_proveedor-de_destino input").on('change', function() {
  seleccion = $("#devolucion_proveedor-de_destino input:checked").val(); 

  if (seleccion=="D") 
    $('.field-devolucion_proveedor-de_prove').hide();
  else
    $('.field-devolucion_proveedor-de_prove').show();
});

//evento cambio de deposito Devolucion
$("#devolucion_proveedor-de_deposito").on('change', function() {
    deposito = $(this).val();
    
    
    $('#devolucion_proveedor-renglones tr.multiple-input-list__item').each(function( index, elem ){
        select =  $( this ).find(".list-cell__DP_CODMON select");
        cargar_vencimientos(select);
    });
});

seleccion = $("#devolucion_proveedor-de_destino input:checked").val(); 

  if (seleccion=="D") 
    $('.field-devolucion_proveedor-de_prove').hide();
  else
    $('.field-devolucion_proveedor-de_prove').show();

//carga los vencimientos disponi
function cargar_vencimientos(elem){
	codmon = elem.val();
	deposito = $('#devolucion_proveedor-de_deposito').val();
    $.ajax({
        url: 'index.php?r=devolucion_proveedor/vencimientos_vigentes_select',
        dataType: 'json',
        method: 'POST',
        data: {codmon: codmon,deposito: deposito},
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
                if (codmon.length>0 && deposito.length>0){
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