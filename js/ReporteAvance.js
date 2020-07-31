var idProyectoActual = 0;
var idObjetivoActual = 0;
var idProductoActual = 0;
var idActividadActual = 0;
var fechaIniActual = "";
var fechaFinActual = "";

function SeleccionarProyecto() {
  $.blockUI();

  var idProyecto = $('#listaProyecto option:selected').val();

  AvanceProyecto(idProyecto);
}

function AvanceProyecto(idProyecto){
  $.ajax({
    type: "GET",
    url: URL_PATH + "/Reporte/AvanceProyecto",
    data: {
      idProyecto : idProyecto
    },
    success: function(result){
      try{
        res = JSON.parse(result);
        if (res.estado == true) {
          idProyectoActual = idProyecto;

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

function AvanceObjetivo(idObjetivo) {
  $.blockUI();
  $.ajax({
    type: "GET",
    url: URL_PATH + "/Reporte/AvanceObjetivo",
    data: {
      idProyecto : idProyectoActual,
      idObjetivo : idObjetivo
    },
    success: function(result){
      try{
        res = JSON.parse(result);
        if (res.estado == true) {
          idObjetivoActual = idObjetivo;

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

function AvanceProducto(idProducto) {
  $.blockUI();
  $.ajax({
    type: "GET",
    url: URL_PATH + "/Reporte/AvanceProducto",
    data: {
      idProyecto : idProyectoActual,
      idObjetivo : idObjetivoActual,
      idProducto : idProducto
    },
    success: function(result){
      try{
        res = JSON.parse(result);
        if (res.estado == true) {
          idProductoActual = idProducto;

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

function ReporteActividad(idActividad) {
  $.blockUI();
  $.ajax({
    type: "GET",
    url: URL_PATH + "/Reporte/VistaAvanceActividad",
    data: {
      idProyecto : idProyectoActual,
      idObjetivo : idObjetivoActual,
      idProducto : idProductoActual,
      idActividad : idActividad
    },
    success: function(result){
      try{
        res = JSON.parse(result);
        if (res.estado == true) {
          idActividadActual = idActividad;
          
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

function AvanceActividad() {
  $.blockUI();
  var fechaIni = $('#fechaIni').val();
  var fechaFin = $('#fechaFin').val();
  if (fechaIni == '') {
    MostrarConfirmacion('Error', 'Selecciona la fecha inicio.', null, null);
  }
  else if (fechaFin == '') {
    MostrarConfirmacion('Error', 'Selecciona la fecha fin.', null, null);
  }
  else{
    $.ajax({
      type: "GET",
      url: URL_PATH + "/Reporte/AvanceActividad",
      data: {
        idProyecto : idProyectoActual,
        idObjetivo : idObjetivoActual,
        idProducto : idProductoActual,
        idActividad : idActividadActual,
        fechaIni : fechaIni,
        fechaFin : fechaFin
      },
      success: function(result){
        try{
          res = JSON.parse(result);
          if (res.estado == true) {
            fechaIniActual = fechaIni;
            fechaFinActual = fechaFin;

            $('#divAvanceDetalle').html(res.vista);
          }
          else {
            MostrarConfirmacion('Error', res.error, null, null);
          }
        }
        catch(err) {
          MostrarConfirmacion('Error de proceso', err.message, null, null);
        }
      },
      complete: function(){
        $.unblockUI();
      }
    });


  }
}

function AvanceAccion(idAccion) {
  $.blockUI();

  $.ajax({
    type: "GET",
    url: URL_PATH + "/Reporte/AvanceAccion",
    data: {
      idProyecto : idProyectoActual,
      idObjetivo : idObjetivoActual,
      idProducto : idProductoActual,
      idActividad : idActividadActual,
      fechaIni : fechaIniActual,
      fechaFin : fechaFinActual,
      idAccion : idAccion
    },
    success: function(result){
      try{
        res = JSON.parse(result);
        if (res.estado == true) {
          $('#divAvanceEjecucion').html(res.vista);
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