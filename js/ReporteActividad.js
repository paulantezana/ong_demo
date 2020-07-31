$(document).ready(function() {
  $('#fechaIni').datepicker({
  format: "yyyy-mm-dd",
  });

  $('#fechaFin').datepicker({
    format: "yyyy-mm-dd",
  });
});

function BuscarReporte(idProyecto){
  $.blockUI();
  var idProyecto = $('#listaProyecto option:selected').val();
  var fechaIni = $('#fechaIni').val();
  var fechaFin = $('#fechaFin').val();
  
  if (!(idProyecto > 0)) {
    MostrarConfirmacion('Error de usuario', 'Seleccione un proyecto.', null, null);
    $.unblockUI();
  }
  else if (fechaIni == '') {
    MostrarConfirmacion('Error de usuario', 'Selecciona la fecha inicio.', null, null);
    $.unblockUI();
  }
  else if (fechaFin == '') {
    MostrarConfirmacion('Error de usuario', 'Selecciona la fecha fin.', null, null);
    $.unblockUI();
  }
  else{
    $.ajax({
      type: "GET",
      url: URL_PATH + "/Reporte/ReporteActividad",
      data: {
        idProyecto : idProyecto,
        fechaIni : fechaIni,
        fechaFin : fechaFin
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