$(document).ready(function() {
  $('#radioNombre').prop('checked', true);
  CambiarSeleccion();
});

function BuscarParticipanteComunidad(){
  $.blockUI();
  $.ajax({
    type: "GET",
    url: URL_PATH + "/Reporte/BuscarParticipanteComunidad",
    data: {
      idProyecto : $('#listaProyecto option:selected').val(),
    },
    success: function(result){
      try{
        res = JSON.parse(result);
        if (res.estado == true) {
          $('#opcionesBusqueda').removeClass('d-none');

          $('.chosen-select').chosen('destroy');
          
          var listaNombre = '<option value="0" selected disabled>Selecione un nombre</option>'
          var listaDni = '<option value="0" selected disabled>Selecione un DNI</option>'
          for (var i = 0; i < res.personas.length; i++) {
            listaNombre += '<option value="'+res.personas[i].id_persona+'">' + res.personas[i].paterno + ' ' + res.personas[i].materno + ' ' + res.personas[i].nombre+ '</option>'
            listaDni += '<option value="'+res.personas[i].id_persona+'">' + res.personas[i].dni + '</option>'
          }

          $('#nombre').html(listaNombre);
          $('#dni').html(listaDni);

          var listaComunidad = '<option value="0" selected disabled>Selecione un nombre</option>'
          for (var i = 0; i < res.comunidades.length; i++) {
            listaComunidad += '<option value="'+res.comunidades[i].id_comunidad+'">' + res.comunidades[i].nombre + '</option>'
          }

          $('#comunidad').html(listaComunidad);

          $('#tipoParticipante').val('PERSONA');
          CambiarTipoParticipante();
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

function CambiarTipoParticipante(){
  var tipoParticipante = $('#tipoParticipante option:selected').val();
  if (tipoParticipante == "PERSONA" || tipoParticipante == "FAMILIA") {
    $('#radioDni').prop('disabled', false);
    $('#nombre').chosen('destroy');
    $('#nombre').show();
    $('#nombre').chosen();
    $('#comunidad').chosen('destroy');
    $('#comunidad').hide();
  }
  else if(tipoParticipante == "COMUNIDAD"){
    $('#radioNombre').prop('checked', true);
    $('#nombre').chosen('destroy');
    $('#nombre').hide();
    $('#comunidad').show();
    $('#comunidad').chosen();
    CambiarSeleccion();
    $('#radioDni').prop('disabled', true);
  }
}

function CambiarSeleccion(){
  if ($('#radioNombre').is(':checked')) {
    $('#nombre').attr('disabled', false);
    $('#nombre').trigger('chosen:updated');
    $('#dni').val(0);
    $('#dni').attr('disabled', true);
    $('#dni').trigger('chosen:updated');
  }
  else{
    $('#dni').chosen();
    $('#nombre').attr('disabled', true);
    $('#nombre').val(0);
    $('#nombre').trigger('chosen:updated');
    $('#dni').attr('disabled', false);
    $('#dni').trigger('chosen:updated');
  }
}

function BuscarReporte(){
  $.blockUI();
  var idProyecto = $('#listaProyecto option:selected').val();
  var tipoParticipante = $('#tipoParticipante option:selected').val();
  var nombre = $('#nombre').val();
  var dni = $('#dni').val();
  var comunidad = $('#comunidad').val();
  var datoBusqueda = "";
  if ($('#radioNombre').is(':checked')) {
    if (tipoParticipante == 'COMUNIDAD') {
      datoBusqueda = 'COMUNIDAD';
    }
    else{
      datoBusqueda = 'NOMBRE';
    }
  }
  else{
    datoBusqueda = 'DNI';
  }

  if (!(idProyecto > 0)) {
    MostrarConfirmacion('Error de usuario', 'Seleccione un proyecto.', null, null);
    $.unblockUI();
  }
  else if (datoBusqueda == 'NOMBRE' && !(nombre > 0)) {
    MostrarConfirmacion('Error de usuario', 'Seleccione un nombre.', null, null);
    $.unblockUI();
  }
  else if (datoBusqueda == 'DNI' && !(dni > 0)) {
    MostrarConfirmacion('Error de usuario', 'Seleccione un DNI.', null, null);
    $.unblockUI();
  }
  else if (datoBusqueda == 'COMUNIDAD' && !(comunidad > 0)) {
    MostrarConfirmacion('Error de usuario', 'Seleccione una comunidad.', null, null);
    $.unblockUI();
  }
  else{
    $.ajax({
      type: "GET",
      url: URL_PATH + "/Reporte/ReporteParticipacion",
      data: {
        idProyecto : idProyecto,
        tipoParticipante : tipoParticipante,
        nombre : nombre,
        dni : dni,
        comunidad : comunidad,
        datoBusqueda : datoBusqueda
      },
      success: function(result){
        try{
          res = JSON.parse(result);
          if (res.estado == true) {
            console.log(res);
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