var idRergionActual = 0;
var idProvinciaActual = 0;
var idDistritoActual = 0;
var idComunidadActual = 0;

$(document).ready(function() {
  $('[name="menuProyecto"] a').addClass('active');

  $('#formComunidad').submit(false);
});

function SeleccionarRegion(){
  var idRegion = $('#listaRegion option:selected').val();

  if(idRegion == 0){
  	$('#modalComunidad_titulo').html('Registrar nueva región');
  	$('#modalComunidad_nombre').val('');
  	$('#modalComunidad_guardar').html('Registrar');
  	$('#modalComunidad').modal();
  }
  else if(idRegion > 0){
  	TablaProvincia(idRegion);
  }
}

function TablaProvincia(idRegion){
  $.blockUI();
  $('#divProvincia').html('');
  $('#divDistrito').html('');
  $('#divComunidad').html('');

  $.ajax({
    type: "GET",
    url: URL_PATH + "/Reporte/TablaProvincia",
    data: {
      idRegion : idRegion,
    },
    success: function(result){
      try{
        res = JSON.parse(result);
        if (res.estado == true) {
          idRergionActual = idRegion;

          $('#divProvincia').html(res.vista);
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

function ModalProvinciaNueva(){
  $('#modalComunidad_titulo').html('Registrar nueva provincia');
  $('#modalComunidad_nombre').val('');
  $('#modalComunidad_guardar').html('Registrar');
  $('#modalComunidad').modal();
}

function TablaDistrito(idProvincia){
  $.blockUI();
  $('#divDistrito').html('');
  $('#divComunidad').html('');
  $.ajax({
    type: "GET",
    url: URL_PATH + "/Reporte/TablaDistrito",
    data: {
      idProvincia : idProvincia,
    },
    success: function(result){
      try{
        res = JSON.parse(result);
        if (res.estado == true) {
          idProvinciaActual = idProvincia;
          
          $('#divDistrito').html(res.vista);
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

function ModalDistritoNuevo(){
  $('#modalComunidad_titulo').html('Registrar nuevo distrito');
  $('#modalComunidad_nombre').val('');
  $('#modalComunidad_guardar').html('Registrar');
  $('#modalComunidad').modal();
}

function TablaComunidad(idDistrito){
  $.blockUI();
  $('#divComunidad').html('');
  $.ajax({
    type: "GET",
    url: URL_PATH + "/Reporte/TablaComunidad",
    data: {
      idDistrito : idDistrito,
    },
    success: function(result){
      try{
        res = JSON.parse(result);
        if (res.estado == true) {
          idDistritoActual = idDistrito;
          
          $('#divComunidad').html(res.vista);
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

function ModalComunidadNuevo(){
  $('#modalComunidad_titulo').html('Registrar nueva comunidad');
  $('#modalComunidad_nombre').val('');
  $('#modalComunidad_guardar').html('Registrar');
  $('#modalComunidad').modal();
}

function RegistrarComunidad(){
  $.blockUI()
  var nombre = $('#modalComunidad_nombre').val();

  if ($('#modalComunidad_titulo').html() == 'Registrar nueva región') {
    $.ajax({
      type: "POST",
      url: URL_PATH + "/Reporte/RegistrarRegion",
      data: {
        nombre : nombre,
      },
      success: function(result){
        try{
          res = JSON.parse(result);
          if (res.estado == true) {
            location.href = URL_PATH + '/Reporte/Comunidad';
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
  else if ($('#modalComunidad_titulo').html() == 'Registrar nueva provincia') {
    $.ajax({
      type: "POST",
      url: URL_PATH + "/Reporte/RegistrarProvincia",
      data: {
        nombre : nombre,
        idRegion : idRergionActual
      },
      success: function(result){
        try{
          res = JSON.parse(result);
          if (res.estado == true) {
            $('#divProvincia').html('');
            $('#divDistrito').html('');
            $('#divComunidad').html('');
            $('#divProvincia').html(res.vista);
            $('#modalComunidad').modal('hide');
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
  else if ($('#modalComunidad_titulo').html() == 'Registrar nuevo distrito') {
    $.ajax({
      type: "POST",
      url: URL_PATH + "/Reporte/RegistrarDistrito",
      data: {
        nombre : nombre,
        idProvincia : idProvinciaActual
      },
      success: function(result){
        try{
          res = JSON.parse(result);
          if (res.estado == true) {
            $('#divDistrito').html('');
            $('#divComunidad').html('');
            $('#divDistrito').html(res.vista);
            $('#modalComunidad').modal('hide');
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
  else if ($('#modalComunidad_titulo').html() == 'Registrar nueva comunidad') {
    $.ajax({
      type: "POST",
      url: URL_PATH + "/Reporte/RegistrarComunidad",
      data: {
        nombre : nombre,
        idDistrito : idDistritoActual
      },
      success: function(result){
        try{
          res = JSON.parse(result);
          if (res.estado == true) {
            $('#divComunidad').html(res.vista);
            $('#modalComunidad').modal('hide');
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
  else if ($('#modalComunidad_titulo').html() == 'Modificar provincia') {
    $.ajax({
      type: "POST",
      url: URL_PATH + "/Reporte/ModificarProvincia",
      data: {
        nombre : nombre,
        idProvincia : idProvinciaActual,
        idRegion : idRergionActual
      },
      success: function(result){
        try{
          res = JSON.parse(result);
          if (res.estado == true) {
            $('#divProvincia').html(res.vista);
            $('#modalComunidad').modal('hide');
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
  else if ($('#modalComunidad_titulo').html() == 'Modificar distrito') {
    $.ajax({
      type: "POST",
      url: URL_PATH + "/Reporte/ModificarDistrito",
      data: {
        nombre : nombre,
        idDistrito : idDistritoActual,
        idProvincia : idProvinciaActual,
      },
      success: function(result){
        try{
          res = JSON.parse(result);
          if (res.estado == true) {
            $('#divDistrito').html(res.vista);
            $('#modalComunidad').modal('hide');
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
  else if ($('#modalComunidad_titulo').html() == 'Modificar comunidad') {
    $.ajax({
      type: "POST",
      url: URL_PATH + "/Reporte/ModificarComunidad",
      data: {
        nombre : nombre,
        idComunidad : idComunidadActual,
        idDistrito : idDistritoActual,
      },
      success: function(result){
        try{
          res = JSON.parse(result);
          if (res.estado == true) {
            $('#divComunidad').html(res.vista);
            $('#modalComunidad').modal('hide');
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

function ModalProvinciaModificar(idProvincia){
  $.blockUI();
  $.ajax({
    type: "GET",
    url: URL_PATH + "/Reporte/BuscarProvincia",
    data: {
      idProvincia : idProvincia,
    },
    success: function(result){
      try{
        res = JSON.parse(result);
        if (res.estado == true) {
          idProvinciaActual = idProvincia;
          $('#divDistrito').html('');
          $('#divComunidad').html('');

          $('#modalComunidad_titulo').html('Modificar provincia');
          $('#modalComunidad_nombre').val(res.nombre);
          $('#modalComunidad_guardar').html('Modificar');
          $('#modalComunidad').modal();
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

function ModalDistritoModificar(idDistrito){
  $.blockUI();
  $.ajax({
    type: "GET",
    url: URL_PATH + "/Reporte/BuscarDistrito",
    data: {
      idDistrito : idDistrito,
    },
    success: function(result){
      try{
        res = JSON.parse(result);
        if (res.estado == true) {
          idDistritoActual = idDistrito;
          $('#divComunidad').html('');

          $('#modalComunidad_titulo').html('Modificar distrito');
          $('#modalComunidad_nombre').val(res.nombre);
          $('#modalComunidad_guardar').html('Modificar');
          $('#modalComunidad').modal();
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

function ModalComunidadModificar(idComunidad){
  $.blockUI();
  $.ajax({
    type: "GET",
    url: URL_PATH + "/Reporte/BuscarComunidad",
    data: {
      idComunidad : idComunidad,
    },
    success: function(result){
      try{
        res = JSON.parse(result);
        if (res.estado == true) {
          idComunidadActual = idComunidad;
          $('#divComunidad').html('');

          $('#modalComunidad_titulo').html('Modificar comunidad');
          $('#modalComunidad_nombre').val(res.nombre);
          $('#modalComunidad_guardar').html('Modificar');
          $('#modalComunidad').modal();
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