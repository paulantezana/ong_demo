var fotoSeleccionada = "";

$(document).ready(function() {
  $('[name="menuProyecto"] a').addClass('active');

  $('#formProyecto').submit(false);

  $('#fechaInicio').datepicker({
    format: "yyyy-mm-dd",
  });

  $('#fechaFin').datepicker({
    format: "yyyy-mm-dd",
  });

  document.getElementById('foto').addEventListener('change', function() {
    if (ValidarArchivo(this.files)) {
        for (var i = 0; i < this.files.length; i++) {
        	var reader = new FileReader();

      		$('label[for="foto"]').html(this.files[i].name);

          reader.onload = function(e) {
              //GuardarFoto(e.target.result);
              fotoSeleccionada = e.target.result;
              $('#fotoSelecionada').attr('src', fotoSeleccionada);
          }

    			reader.readAsDataURL(this.files[i]);
    		}		
    }
    else{
        MostrarConfirmacion("Error", "El archivo tiene formato o tamaño incorrecto, solo se aceptan archivos con extension .jpg y .png. y un tamaño maximo de 2000Kb.", null, null);
    }
  }, false);
});

function ValidarArchivo(archivos){
  var tipoArchivo = ["image/png", "image/jpeg"];

  for (var i = 0; i < archivos.length; i++) {
    if (tipoArchivo.indexOf(archivos[i].type) < 0) {
      return false;
        }

        var fileSize = archivos[i].size;
      var siezekiloByte = parseInt(fileSize / 1024);
      if (siezekiloByte >  2000) {
          return false;
      }
  }
  
  return true;
}

function RegistrarProyecto(idPago){
  $.blockUI();
  var proyecto = {};

  proyecto.nombre = $('#titulo').val();
  proyecto.descripcion = $('#descripcion').val();
  proyecto.fechaInicio = FechaFormatoBD($("#fechaInicio").datepicker("getDates")[0]);
  proyecto.fechaFin = FechaFormatoBD($("#fechaFin").datepicker("getDates")[0]);
  proyecto.foto = fotoSeleccionada;
  
  $.ajax({
    type: "POST",
    url: URL_PATH + "/ProyectoLista/RegistrarProyecto",
    data: {
      proyecto : proyecto,
    },
    success: function(result){
      try{
        res = JSON.parse(result);
        if (res.estado == true) {
          location.href = URL_PATH + '/ProyectoLista/ListarProyecto';
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