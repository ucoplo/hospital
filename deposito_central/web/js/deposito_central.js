yii.confirm = function (message, okCallback, cancelCallback) {
     //krajeeDialog.confirm(message,okCallback,cancelCallback);
     krajeeDialog.confirm(message, function (result) {
      if (result) { // ok button was pressed
          okCallback();
      } else { // confirmation was cancelled
          
      }
});
};

yii.alert = function (message, okCallback, cancelCallback) {
   krajeeDialog.alert(message,okCallback);
};

 $( document ).ready(function() {

  //BOTON IMPORTACION PROVEEDORES
	 $("#btn_importar_proveedores").click(function(event) {
    event.preventDefault();
 		 
    krajeeDialog.confirm("¿Realiza la importación de Proveedores de RAFAM?", function (result) {
        if (result) { // ok button was pressed
           $('#modal_importar_txt').modal('show');
           $('#wrp_mensaje_modal').html("Importando... <img src='images/spin.gif' alt=''>");
           $('.modal-header').html('Importación Proveedores RAFAM');
           $.ajax({
              url: 'index.php?r=importar_rafam/importar_proveedores',
              dataType: 'json',
              method: 'POST',
              data: {},
              success: function (data, textStatus, jqXHR) {
                $('#wrp_mensaje_modal').html(data+"<br><br><button type='button' class='btn btn-default' data-dismiss='modal'>Cerrar</button>");
              },
          }).fail(function(model, response) {
            
            $('#wrp_mensaje_modal').html("Error al importar<br><br><button type='button' class='btn btn-default' data-dismiss='modal'>Cerrar</button>");
          });
        } else { // confirmation was cancelled
            // execute your code for cancellation
        }
    });
  });
   
   //BOTON IMPORTACION ORDENES DE COMPRA
   $("#btn_importar_ordenes").click(function(event) {
    event.preventDefault();
     
    krajeeDialog.confirm("¿Realiza la importación de Ordenes de Compra de RAFAM?", function (result) {
        if (result) { // ok button was pressed
           $('#modal_importar_txt').modal('show');
           $('#wrp_mensaje_modal').html("Importando... <img src='images/spin.gif' alt=''>");
           $('.modal-header').html('Importación Ordenes de Compra RAFAM');
           $.ajax({
              url: 'index.php?r=importar_rafam/importar_ordenes',
              dataType: 'json',
              method: 'POST',
              data: {},
              success: function (data, textStatus, jqXHR) {
              	$('#wrp_mensaje_modal').html(data+"<br><br><button type='button' class='btn btn-default' data-dismiss='modal'>Cerrar</button>");
                // if (data.length==0)
                //   $('#wrp_mensaje_modal').html("Importación Exitosa.<br><br><button type='button' class='btn btn-default' data-dismiss='modal'>Cerrar</button>");
                // else
                //   //$('#wrp_mensaje_modal').html("Error al Actualizar:<br>"+JSON.stringify(data)+"<br><br><button type='button' class='btn btn-default' data-dismiss='modal'>Cerrar</button>");
                //   $('#wrp_mensaje_modal').html("Error al Importar:<br>Verifique que los campos obligatorios de medicamentos esten completos<br><button type='button' class='btn btn-default' data-dismiss='modal'>Cerrar</button>");
              },
          }).fail(function(model, response) {
            
            $('#wrp_mensaje_modal').html("Error al actualizar<br><br><button type='button' class='btn btn-default' data-dismiss='modal'>Cerrar</button>");
          });
        } else { // confirmation was cancelled
            // execute your code for cancellation
        }
    });
  });

});