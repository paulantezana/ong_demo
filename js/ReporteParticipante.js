function BuscarActividad(){
  $.blockUI();
  $.ajax({
    type: "GET",
    url: URL_PATH + "/Reporte/BuscarActividadPorProyecto",
    data: {
      idProyecto : $('#listaProyecto option:selected').val(),
    },
    success: function(result){
      try{
        res = JSON.parse(result);
        if (res.estado == true) {
          $('#opcionesBusqueda').removeClass('d-none');

          $('#actividad').chosen('destroy');
          
          var listaActividad = '<option value="0" selected disabled>Selecione una actividad</option>'
          for (var i = 0; i < res.actividades.length; i++) {
            listaActividad += '<option value="'+res.actividades[i].id_actividad+'">' + res.actividades[i].nombre+ '</option>'
          }

          $('#actividad').html(listaActividad);
          $('#actividad').chosen();
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
  var tipoParticipante = $('#tipoParticipante option:selected').val();
  var idActividad = $('#actividad').val();

  if (!(idActividad > 0)) {
    MostrarConfirmacion('Error de usuario', 'Seleccione una actividad.', null, null);
    $.unblockUI();
  }
  else{
    $.ajax({
      type: "GET",
      url: URL_PATH + "/Reporte/ReporteParticipanteComunidad",
      data: {
        idProyecto : idProyecto,
        idActividad : idActividad,
        tipoParticipante : tipoParticipante,
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