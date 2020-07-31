function MostrarConfirmacion(titulo, mensaje, accion, campoAdicional){
    var modalCabecera = '<div class="modal fade" tabindex="-1" role="dialog" id="modalComun">'+
					  '<div class="modal-dialog" role="document">'+
					    '<form id="formComun" onsubmit="CerrarModalComun()">'+
					    '</form>'+
					  '</div>'+
					'</div>';

	var contenido = '<div class="modal-content">'+
				      '<div class="modal-header">'+
				        '<h5 class="modal-title" id="modalComunTitulo"></h5>'+
				        '<button type="button" class="close" data-dismiss="modal" aria-label="Close">'+
				          '<span aria-hidden="true">&times;</span>'+
				        '</button>'+
				      '</div>'+
				      '<div class="modal-body">'+
				        '<p id="modalComunMensaje"></p>';
		if (campoAdicional) {
			contenido += 	'<div class="form-group">'+
							  '<label for="usr">'+campoAdicional+' : </label>'+
							  '<input type="text" class="form-control" id="modalComunDatoAdicional" required>'+
							'</div>';
		}
		
		contenido +=  '</div>'+
				      '<div class="modal-footer">';
		if (accion) {
			contenido += 	'<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>';
		}
			contenido +=    '<button type="submit" class="btn btn-primary" id="modalComunAceptar" data-dismiss="modal">Continuar</button>'+
				      '</div>'+
				    '</div>';

	var modal = document.getElementById("modalComun");
	if (modal) {
		setTimeout(
			function(){ 
				ConfigurarModal(contenido, titulo, mensaje, accion);
			}, 
		300);
	}
	else {
		$(document).find("body").append(modalCabecera);
		modal = document.getElementById("modalComun");
		ConfigurarModal(contenido, titulo, mensaje, accion);
	}
}

function CerrarModalComun(){

}

function ConfigurarModal(contenido, titulo, mensaje, accion){
	$('#modalComun').find('form').html(contenido);

	$('#formComun').submit(false);

	if (titulo) {
		$('#modalComunTitulo').html(titulo);
	}
	else{
		$('#modalComunTitulo').html('Confirmación');
	}

	if (mensaje) {
		$('#modalComunMensaje').html(mensaje);
	}
	else{
		$('#modalComunMensaje').html('¿Esta seguro que desea continuar?');
	}

	$('#modalComunDatoAdicional').val('');
	$('#modalComunAceptar').attr('onclick', accion);
	$('#modalComun').modal('show');
}

var tableToExcel = (function() {
  var uri = 'data:application/vnd.ms-excel;base64,'
    , template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><meta http-equiv="content-type" content="application/vnd.ms-excel; charset=UTF-8"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>'
    // , template = 'data:application/vnd.ms-excel;base64,<html xmlns:x="urn:schemas-microsoft-com:office:excel"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>'
    , base64 = function(s) { return window.btoa(unescape(encodeURIComponent(s))) }
    , format = function(s, c) { return s.replace(/{(\w+)}/g, function(m, p) { return c[p]; }) }
  return function(table, name,nombreArchivo) {
    if (!table.nodeType) table = document.getElementById(table);

    var tablaOriginal = table.innerHTML;
    $(table).find('.no-export').remove();
    if ($(table).find('tbody tr').length > 0) {
      var ctx = {worksheet: name || 'Worksheet', table: table.innerHTML}
      // window.location.href = uri + base64(format(template, ctx));

      var blob = new Blob([s2ab(atob(base64(format(template, ctx))))], {
		    type: ''
		});

	  var link = document.createElement('a');//console.log(nombreArchivo);
		if (nombreArchivo===undefined) {
			link.download = 'ReporteCadep.xls';
		}else{
			link.download = nombreArchivo+'.xls';
		}
		
		link.href = URL.createObjectURL(blob);
		link.click();
    
       $(table).html(tablaOriginal);
    }
    else{
		MostrarConfirmacion('Error', 'No hay registros para exportar.', null, null);
    }
  }
})();

function FechaFormatoBD(fecha) {
	var y = fecha.getFullYear();
	var m = fecha.getMonth() + 1;
	var d = fecha.getDate();
	var mm = m < 10 ? '0' + m : m;
	var dd = d < 10 ? '0' + d : d;
	return '' + y + '-' + mm + '-' + dd;
}

function s2ab(s) {
  var buf = new ArrayBuffer(s.length);
  var view = new Uint8Array(buf);
  for (var i=0; i!=s.length; ++i) view[i] = s.charCodeAt(i) & 0xFF;
  return buf;
}
function calcularEdad(fecha) {
    var hoy = new Date();
    var cumpleanos = new Date(fecha);
    var edad = hoy.getFullYear() - cumpleanos.getFullYear();
    var m = hoy.getMonth() - cumpleanos.getMonth();

    if (m < 0 || (m === 0 && hoy.getDate() < cumpleanos.getDate())) {
        edad--;
    }

    return edad;
}