yii.confirm = function (message, okCallback, cancelCallback) {
     //krajeeDialog.confirm(message,okCallback,cancelCallback);
     krajeeDialog.confirm(message, function (result) {
      if (result) { // ok button was pressed
          okCallback();
      } else { // confirmation was cancelled
          
      }
});
};

 $( document ).ready(function() {

   //BOTON IMPORTACION TXT KAIROS
	 $("#btn_importar_txt").click(function(event) {
    event.preventDefault();
 		 
    krajeeDialog.confirm("¿Realiza la importación de Datos Kairos?", function (result) {
        if (result) { // ok button was pressed
           $('#modal_importar_txt').modal('show');
           $('#wrp_mensaje_modal').html("Importando... <img src='images/spin.gif' alt=''>");
           $('.modal-header').html('Importación txt Kairos');
           $.ajax({
              url: 'index.php?r=importar_kairos/importar_txt',
              dataType: 'json',
              method: 'POST',
              data: {},
              success: function (data, textStatus, jqXHR) {
                $('#wrp_mensaje_modal').html(data+"<br><button type='button' class='btn btn-default' data-dismiss='modal'>Cerrar</button>");
              },
          });
        } else { // confirmation was cancelled
            // execute your code for cancellation
        }
    });
  });
   
   //BOTON ACTUALIZACION PRECIOS KAIROS
   $("#btn_actualizar_precios").click(function(event) {
    event.preventDefault();
     
    krajeeDialog.confirm("¿Realiza la actualización de precios de medicamentos?", function (result) {
        if (result) { // ok button was pressed
           $('#modal_importar_txt').modal('show');
           $('#wrp_mensaje_modal').html("Actualizando... <img src='images/spin.gif' alt=''>");
           $('.modal-header').html('Actualizar Precios desde Kairos');
           $.ajax({
              url: 'index.php?r=importar_kairos/actualizar_precios',
              dataType: 'json',
              method: 'POST',
              data: {},
              success: function (data, textStatus, jqXHR) {
                if (data.length==0)
                  $('#wrp_mensaje_modal').html("Actualización Exitosa.<br><br><button type='button' class='btn btn-default' data-dismiss='modal'>Cerrar</button>");
                else
                  //$('#wrp_mensaje_modal').html("Error al Actualizar:<br>"+JSON.stringify(data)+"<br><br><button type='button' class='btn btn-default' data-dismiss='modal'>Cerrar</button>");
                  $('#wrp_mensaje_modal').html("Error al Actualizar:<br>Verifique que los campos obligatorios de medicamentos esten completos<br><button type='button' class='btn btn-default' data-dismiss='modal'>Cerrar</button>");
              },
          }).fail(function(model, response) {
            
            $('#wrp_mensaje_modal').html("Error al actualizar<br><button type='button' class='btn btn-default' data-dismiss='modal'>Cerrar</button>");
          });
        } else { // confirmation was cancelled
            // execute your code for cancellation
        }
    });
  });

});