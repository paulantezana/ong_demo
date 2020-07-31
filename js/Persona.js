var idPersonaActiva = 0;

$(document).ready(function() {
  $('[name="menuParticipante"] a').addClass('active');

  // $('#modalPersona_fechaNacimiento').datepicker({
  //   format: "yyyy-mm-dd",
  // });

  $('#listaComunidad').chosen();
  $('#formPersona').submit(false);
});

function ListarPersona(){
  $.blockUI();
  var idComunidad = $('#listaComunidad option:selected').val();
  if (idComunidad >= 0) {
    $.ajax({
      type: "GET",
      url: URL_PATH + "/Persona/ListarPersona",
      data: {
        idComunidad : idComunidad
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
  else{
    MostrarConfirmacion('Error de usuario', "Seleccione una comunidad.", null, null);
  }
}

function ModalPersonaNueva(){
  $('#modalPersona_titulo').html('Nueva Persona');
  $('#modalPersona_dni').val('');
  $('#modalPersona_nombre').val('');
  $('#modalPersona_paterno').val('');
  $('#modalPersona_materno').val('');
  $('#modalPersona_genero').val(0);
  $('#modalPersona_fechaNacimiento').val('');
  $('#modalPersona_guardar').html('Registrar');
  $('#modalPersona').modal();
  
  setTimeout(function() {
    $('#comunidad').chosen('destroy');
    $('#comunidad').chosen();
  }, 300);
}

function RegistrarPersona(){
  $.blockUI();
  var persona = new Object();
  persona.tipoDocumento = $('#modalPersona_tipo option:selected').val();
  persona.dni = $('#modalPersona_dni').val();
  persona.nombre = $('#modalPersona_nombre').val();
  persona.paterno = $('#modalPersona_paterno').val();
  persona.materno = $('#modalPersona_materno').val();
  persona.genero = $('#modalPersona_genero option:selected').val();
  persona.fechaNacimiento = $('#modalPersona_fechaNacimiento').val();
  persona.idComunidad = $('#comunidad option:selected').val();

  if (persona.idComunidad > 0) {
    if($('#modalPersona_titulo').html() == 'Nueva Persona'){
      $.ajax({
        type: "POST",
        url: URL_PATH + "/Persona/RegistrarPersona",
        data: {
          persona : persona,
        },
        success: function(result){
          try{
            res = JSON.parse(result);
            if (res.estado == true) {
              $('#divReporte').html(res.vista);
              $('#modalPersona').modal('hide');
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
    else if($('#modalPersona_titulo').html() == 'Modificar Persona'){
      persona.idPersona = idPersonaActiva;
      $.ajax({
        type: "POST",
        url: URL_PATH + "/Persona/ModificarPersona",
          data: {
            persona : persona,
          },
          success: function(result){
            try{
              res = JSON.parse(result);
              if (res.estado == true) {
                $('#divReporte').html(res.vista);
                $('#modalPersona').modal('hide');
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
  else{
    MostrarConfirmacion('Error de usuario', "Seleccione una comunidad.", null, null);
   $.unblockUI();
  }
}

function ModalPersonaModificar(idPersona){
  $.blockUI();
  $.ajax({
    type: "GET",
    url: URL_PATH + "/Persona/BuscarPersona",
      data: {
        idPersona : idPersona,
      },
      success: function(result){
        try{
          res = JSON.parse(result);
          if (res.estado == true) {
            idPersonaActiva = idPersona;
            $('#modalPersona_titulo').html('Modificar Persona');
            $('#modalPersona_tipo').val(res.persona.tipo_documento);
            $('#modalPersona_dni').val(res.persona.dni);
            $('#modalPersona_nombre').val(res.persona.nombre);
            $('#modalPersona_paterno').val(res.persona.paterno);
            $('#modalPersona_materno').val(res.persona.materno);
            $('#modalPersona_genero').val(res.persona.genero);
            $('#modalPersona_fechaNacimiento').val(res.persona.fecha_nacimiento);
            $('#modalPersona_guardar').html('Modificar');

            $('#modalPersona').modal();
            setTimeout(function() {
              $('#comunidad').chosen();
              $('#comunidad').val(res.persona.id_comunidad);
              $('#comunidad').trigger('chosen:updated');
            }, 300);
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

function BuscarPersonaPorDNI(){
  var dni = $('#modalPersona_dni').val();
  if (dni.length == 8) {
    $.blockUI();
    $.ajax({
      type: "GET",
      url: URL_PATH + "/Persona/BuscarPeronaPorDni",
        data: {
          dni : dni,
        },
        success: function(result){
          try{
            res = JSON.parse(result);
            if (res.estado == true) {
              if (res.persona.id_persona > 0) {
                $('#modalPersona_genero').val(res.persona.genero);
                $('#modalPersona_fechaNacimiento').val(res.persona.fecha_nacimiento);
                $('#comunidad').val(res.persona.idComunidad);
                $('#comunidad').trigger('chosen:updated');
              }
              else{
                $('#modalPersona_genero').val(0);
                $('#modalPersona_fechaNacimiento').val('');
              }

              $('#modalPersona_dni').val(res.persona.dni);
              $('#modalPersona_nombre').val(res.persona.nombre);
              $('#modalPersona_paterno').val(res.persona.paterno);
              $('#modalPersona_materno').val(res.persona.materno);
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

function CambiarTextoInput(){
  let tipoDocumento=$('#modalPersona_tipo option:selected').text();
  $('#labelTipoDocumento').text(tipoDocumento+':');
  //console.log(tipoDocumento);
}
function ExportarExcel(){
  let datos=$('#tablaReporte tr').length; 
  console.log(datos);
  if (datos>0) {
    tableToExcel('tablaReportePersonas', 'Reporte');
  }
}