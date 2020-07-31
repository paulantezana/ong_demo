var idFamiliaActiva = 0;

$(document).ready(function() {
  $('[name="menuParticipante"] a').addClass('active');

  $('#formFamilia').submit(false);

  ListarFamilia();
});

function ListarFamilia(){
  $.blockUI();
  $.ajax({
    type: "GET",
    url: URL_PATH + "/Familia/ListarFamilia",
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

function ModalFamiliaNueva(){
  $('#modalFamilia_titulo').html('Nueva Familia');
  $('#tablaIntegrante').html(FilaNuevoIntegrante());
  $('#modalFamilia_guardar').html('Registrar');
  $('#modalFamilia').modal();

  setTimeout(function() {$('.chosen-select').chosen();}, 300);
}

function FilaNuevoIntegrante(){
  var fila =  '<tr>'+
                '<td>'+
                  '<select class="form-control form-control-sm" name="rol">'+
                    '<option value="JEFE">JEFE DE FAMILIA</option>'+
                    '<option value="CONYUGUE">CONYUGUE</option>'+
                    '<option value="HIJO">HIJO</option>'+
                  '</select>'+
                '</td>'+
                '<td>'+
                  '<select class="chosen-select" name="persona" onchange="SeleccionarPersona(this)">'+
                    '<option value="0" disabled selected>Seleccionar DNI</option>';
                for (var i = 0; i < personas.length; i++) {
              fila += '<option value="'+personas[i].id_persona+'">'+personas[i].dni+'</option>';
                }
          fila += '</select>'+
                '</td>'+
                '<td name="paterno"></td>'+
                '<td name="materno"></td>'+
                '<td name="nombre"></td>'+
                '<td>'+
                  '<button type="button" class="btn btn-sm btn-outline-danger" onclick="ModalIntegranteEliminar(this)" data-toggle="tooltip" data-placement="bottom" title="Eliminar integrante de la familia."><i class="fas fa-trash-alt"></i></button>'+
                '</td>'+
              '</tr>';

  return fila;
}

function AgregarIntegrante(){
  $('#tablaIntegrante').append(FilaNuevoIntegrante());
  $('.chosen-select').chosen();
}

function ModalIntegranteEliminar(btn){
  $(btn).parent().parent().remove();
}

function SeleccionarPersona(select){
  for (var i = 0; i < personas.length; i++) {
    if (personas[i].id_persona == $(select).find('option:selected').val()){
      var tr = $(select).parent().parent();

      $(tr).find('[name="paterno"]').html(personas[i].paterno);
      $(tr).find('[name="materno"]').html(personas[i].materno);
      $(tr).find('[name="nombre"]').html(personas[i].nombre);
      return;
    }
  }
}

function RegistrarFamilia(){
  $.blockUI();
  var familia = new Object();
  familia.integrantes = [];

  var jefeSeleccionado = false;
  var validacion = true;
  $('#tablaIntegrante tr').each(function(){
    var integrante = new Object();
    integrante.idPersona = $(this).find('[name="persona"] option:selected').val();
    integrante.rol = $(this).find('[name="rol"] option:selected').val();

    if (integrante.idPersona <= 0) {
      MostrarConfirmacion('Error de usuario', 'Tiene que seleccionar todos los integrantes mediante su DNI.', null, null);
      validacion = false;
      $.unblockUI();
    }

    if (integrante.rol == 'JEFE') {
      if (!jefeSeleccionado) {
        jefeSeleccionado = true;
      }
      else{
        MostrarConfirmacion('Error de usuario', 'Solo puede haber un jefe de familia.', null, null);
        validacion = false;
        $.unblockUI();
      }
    }

    familia.integrantes.push(integrante);
  });
  if (validacion == true) {
    if (jefeSeleccionado == false){
      MostrarConfirmacion('Error de usuario', 'Debe seleccionar un jefe de familia.', null, null);
      $.unblockUI();
    }
    else {
      if ($('#modalFamilia_titulo').html() == 'Nueva Familia') {
        $.ajax({
          type: "POST",
          url: URL_PATH + "/Familia/RegistrarFamilia",
          data: {
            familia : familia,
          },
          success: function(result){
            try{
              res = JSON.parse(result);
              if (res.estado == true) {
                $('#divReporte').html(res.vista);
                $('#modalFamilia').modal('hide');
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
      else if ($('#modalFamilia_titulo').html() == 'Modificar Familia') {
        familia.idFamilia = idFamiliaActiva;
        console.log(familia);
        $.ajax({
          type: "POST",
          url: URL_PATH + "/Familia/ModificarFamilia",
          data: {
            familia : familia,
          },
          success: function(result){
            try{
              res = JSON.parse(result);
              if (res.estado == true) {
                $('#divReporte').html(res.vista);
                $('#modalFamilia').modal('hide');
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
  }
}

function ModalFamiliaModificar(idFamilia){
  $.blockUI();
  $.ajax({
    type: "GET",
    url: URL_PATH + "/Familia/BuscarFamilia",
      data: {
        idFamilia : idFamilia,
      },
      success: function(result){
        try{
          res = JSON.parse(result);
          if (res.estado == true) {
            idFamiliaActiva = idFamilia;
            $('#modalFamilia_titulo').html('Modificar Familia');
            $('#tablaIntegrante').html('');
            
            for (var i = 0; i < res.integrantes.length; i++) {
              var fila =  '<tr>'+
                            '<td>'+
                              '<select id="rol_'+res.integrantes[i].id_familia_integrante+'" class="form-control form-control-sm" name="rol">'+
                                '<option value="JEFE">JEFE DE FAMILIA</option>'+
                                '<option value="CONYUGUE">CONYUGUE</option>'+
                                '<option value="HIJO">HIJO</option>'+
                              '</select>'+
                            '</td>'+
                            '<td>'+
                              '<select id="persona_'+res.integrantes[i].id_familia_integrante+'" class="chosen-select" name="persona" onchange="SeleccionarPersona(this)">';
                                for (var j = 0; j < personas.length; j++) {
                                  fila += '<option value="'+personas[j].id_persona+'">'+personas[j].dni+'</option>';
                                }
                   fila +=    '</select>'+
                            '</td>'+
                            '<td name="paterno">'+res.integrantes[i].paterno+'</td>'+
                            '<td name="materno">'+res.integrantes[i].materno+'</td>'+
                            '<td name="nombre">'+res.integrantes[i].nombre+'</td>'+
                            '<td>'+
                              '<button type="button" class="btn btn-sm btn-outline-danger" onclick="ModalIntegranteEliminar(this)" data-toggle="tooltip" data-placement="bottom" title="Eliminar integrante de la familia."><i class="fas fa-trash-alt"></i></button>'+
                            '</td>'+
                            '</tr>';
              $('#tablaIntegrante').append(fila);

              $('#rol_'+res.integrantes[i].id_familia_integrante).val(res.integrantes[i].rol);
              $('#persona_'+res.integrantes[i].id_familia_integrante).val(res.integrantes[i].id_persona);
            }
            $('#modalFamilia_guardar').html('Modificar');
            $('#modalFamilia').modal();

            setTimeout(function() {              
              $('#tablaIntegrante .chosen-select').chosen();
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