$(document).ready(function() {
  $('#anio').datepicker({
    format: "yyyy",
    viewMode: "years", 
    minViewMode: "years",
    autoclose: true
  });
});

function BuscarReporte(){
  $.blockUI();
  var idProyecto = $('#listaProyecto option:selected').val();
  var anio = $('#anio').val();

  if (!(idProyecto > 0)) {
    MostrarConfirmacion('Error de usuario', 'Seleccione una actividad.', null, null);
    $.unblockUI();
  }
  else if(!(anio > 0)){
    MostrarConfirmacion('Error de usuario', 'Seleccione un año.', null, null);
    $.unblockUI();
  }
  else{
    $.ajax({
      type: "GET",
      url: URL_PATH + "/Reporte/ReporteAvanceAnualActividad",
      data: {
        idProyecto : idProyecto,
        anio : anio,
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