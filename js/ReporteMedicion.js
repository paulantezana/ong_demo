function BuscarAccion(){
  $.blockUI();
  $.ajax({
    type: "GET",
    url: URL_PATH + "/Reporte/BuscarAccionConMedidaAdicional",
    data: {
      idProyecto : $('#listaProyecto option:selected').val(),
    },
    success: function(result){
      try{
        res = JSON.parse(result);
        if (res.estado == true) {
          $('#opcionesBusqueda').removeClass('d-none');

          $('#accion').chosen('destroy');
          
          var listaAccion = '<option value="0" selected disabled>Selecione una acción</option>'
          for (var i = 0; i < res.acciones.length; i++) {
            listaAccion += '<option value="'+res.acciones[i].id_accion+'">' + res.acciones[i].nombre+ '</option>'
          }

          $('#accion').html(listaAccion);
          $('#accion').chosen();
        }
        else {
          MostrarConfirmacion('Error', res.error, null, null);
        }
      }
      catch(err) {
        MostrarConfirmacion('Error de proceso', result + '|' + err.message, null, null);
      }
    },
    complete: function(){
      $.unblockUI();
    }
  });
}

function BuscarReporte(){
  $.blockUI();
  var idProyecto = $('#listaProyecto option:selected').val();
  var idAccion = $('#accion').val();

  if (!(idAccion > 0)) {
    MostrarConfirmacion('Error de usuario', 'Seleccione una acción.', null, null);
    $.unblockUI();
  }
  else{
    $.ajax({
      type: "GET",
      url: URL_PATH + "/Reporte/ReporteMedicion",
      data: {
        idProyecto : idProyecto,
        idAccion : idAccion,
      },
      success: function(result){
        try{
          res = JSON.parse(result);
          if (res.estado == true) {
            $('#divAvance').html(res.vista);
          }
          else {
            MostrarConfirmacion('Error', res.error, null, null);
          }
        }
        catch(err) {
          MostrarConfirmacion('Error de proceso', result + '|' + err.message, null, null);
        }
      },
      complete: function(){
        $.unblockUI();
      }
    });
  }
}