var idUsuarioActivo = 0;

$(document).ready(function() {
  $('[name="menuUsuario"] a').addClass('active');

  $('#formUsuario').submit(false);

  ListarUsuario();
});

function ListarUsuario(){
  $.blockUI();
  $.ajax({
    type: "GET",
    url: URL_PATH + "/Usuario/ListarUsuario",
    data: {
    },
    success: function(result){
      try{
        res = JSON.parse(result);
        if (res.estado == true) {
          $('#divReporte').html(res.vista);
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

function ModalUsuarioNuevo(){
  $('#modalUsuario_titulo').html('Nuevo Usuario');
  $('#nombre').val('');
  $('#correo').val('');
  $('#password').val('');
  $('#modalUsuario').modal();
}

function RegistrarUsuario(){
  $.blockUI();
  var usuario = new Object();
  usuario.nombre = $('#nombre').val();
  usuario.correo = $('#correo').val();
  usuario.password = $('#password').val();
  usuario.idPerfil = $('#perfil option:selected').val();
  usuario.direccion = $('#direccion').val();

  if($('#modalUsuario_titulo').html() == 'Nuevo Usuario'){
    $.ajax({
      type: "POST",
      url: URL_PATH + "/Usuario/RegistrarUsuario",
      data: {
        usuario : usuario,
      },
      success: function(result){
        try{
          res = JSON.parse(result);
          if (res.estado == true) {
            $('#divReporte').html(res.vista);
            $('#modalUsuario').modal('hide');
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
  else if($('#modalUsuario_titulo').html() == 'Modificar Usuario'){
    usuario.idUsuario = idUsuarioActivo;
    $.ajax({
      type: "POST",
      url: URL_PATH + "/Usuario/ModificarUsuario",
        data: {
          usuario : usuario,
        },
        success: function(result){
          try{
            res = JSON.parse(result);
            if (res.estado == true) {
              $('#divReporte').html(res.vista);
              $('#modalUsuario').modal('hide');
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

function ModalUsuarioModificar(idUsuario){
  $.blockUI();
  $.ajax({
    type: "GET",
    url: URL_PATH + "/Usuario/BuscarUsuario",
      data: {
        idUsuario : idUsuario,
      },
      success: function(result){
        try{
          res = JSON.parse(result);
          if (res.estado == true) {
            idUsuarioActivo = idUsuario;
            $('#modalUsuario_titulo').html('Modificar Usuario');
            $('#nombre').val(res.usuario.nombre);
            $('#correo').val(res.usuario.correo);
            $('#password').val('');
            $('#direccion').val(res.usuario.direccion);
            $('#modalUsuario').modal();
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

function ModalUsuarioEliminar(idUsuario){
  MostrarConfirmacion(null,'¿Esta seguro que desea eliminar este usuario?', 'EliminarUsuario('+idUsuario+')', null);
}

function EliminarUsuario(idUsuario){
  $.blockUI();
  $.ajax({
    type: "POST",
    url: URL_PATH + "/Usuario/EliminarUsuario",
    data: {
      idUsuario : idUsuario,
    },
    success: function(result){
      try{
        res = JSON.parse(result);
        if (res.estado == true) {
          $('#divReporte').html(res.vista);
          $('#modalUsuario').modal('hide');
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

function ModalUsuarioProyecto(idUsuario){
  $.blockUI();
  $.ajax({
    type: "GET",
    url: URL_PATH + "/Usuario/ListarProyectoAsignado",
      data: {
        idUsuario : idUsuario,
      },
      success: function(result){
        try{
          res = JSON.parse(result);
          if (res.estado == true) {
            idUsuarioActivo = idUsuario;
            $('#tablaProyecto').html(res.vista);
            for (var i = 0; i < res.proyectos.length; i++) {
              $('#acceso_'+ res.proyectos[i].id_proyecto).prop('checked' , true);
            }

            $('#modalUsuarioProyecto').modal();
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

function RegistrarAcceso(){
  $.blockUI();
  var proyectos = new Array();

  $('input:checked').each(function(){
    var id = $(this).attr('id');
    var idProyecto = id.split('_')[1];
    proyectos.push(idProyecto);
  });

  $.ajax({
    type: "POST",
    url: URL_PATH + "/Usuario/AsignarProyecto",
    data: {
      idUsuario : idUsuarioActivo,
      proyectos : proyectos,
      cantidad : proyectos.length
    },
    success: function(result){
      try{
        res = JSON.parse(result);
        if (res.estado == true) {
          $('#divReporte').html(res.vista);
          $('#modalUsuarioProyecto').modal('hide');
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