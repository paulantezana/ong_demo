var idObjetivoActivo = 0;
var idProductoActivo = 0;
var idActividadActiva = 0;
var idAccionActiva = 0;
var idAccionEjecucionActiva = 0;
var idPersonaActiva = 0;
var requiereDatoAdicional = 0;
var filaParticipanteActiva;
var listaPersona = [];
var participacionFamiliar = 0;

$(document).ready(function() {
  $('[name="menuProyecto"] a').addClass('active');

  $('#formObjetivo').submit(false);
  $('#formAccion').submit(false);
  $('#formPonderacion').submit(false);
  $('#formAccionEjecucion').submit(false);
  $('#formPersona').submit(false);

  DibujarObjetivos();

  if (idObjetivoBusqueda > 0) {
    ModalProducto(idObjetivoBusqueda, idProductoBusqueda);
  }

  document.getElementById('foto').addEventListener('change', function() {
    if (ValidarArchivo(this.files)) {
      for (var i = 0; i < this.files.length; i++) {
        var reader = new FileReader();

        $('label[for="foto"]').html(this.files[i].name);

        reader.onload = function(e) {
          GuardarFoto(e.target.result);
        }

        reader.readAsDataURL(this.files[i]);
      }   
    }
    else{
      MostrarConfirmacion("Error", "El archivo tiene formato o tamaño incorrecto, solo se aceptan archivos con extension .jpg y .png. y un tamaño maximo de 5000Kb.", null, null);
    }
  }, false);
});

$(function(){
  // Remove svg.radial-progress .complete inline styling
  $('svg.radial-progress').each(function( index, value ) { 
      $(this).find($('circle.complete')).removeAttr( 'style' );
  });

  // Activate progress animation on scroll
  $(window).scroll(function(){
      AnimarProgreso();
  }).trigger('scroll');
});

$(document).on('show.bs.modal', '.modal', function () {
  var zIndex = 1040 + (10 * $('.modal:visible').length);
  $(this).css('z-index', zIndex);
  setTimeout(function() {
      $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
  }, 0);
});

function ValidarArchivo(archivos){
  var tipoArchivo = ["image/png", "image/jpeg"];

  for (var i = 0; i < archivos.length; i++) {
    if (tipoArchivo.indexOf(archivos[i].type) < 0) {
      return false;
        }

        var fileSize = archivos[i].size;
      var siezekiloByte = parseInt(fileSize / 1024);
      if (siezekiloByte >  5000) {
          return false;
      }
  }
  
  return true;
}

function ModalProyectoEliminar(){
  MostrarConfirmacion('Advertencia','Si elimina el proyecto se perderan los objetivos, productos, actividades y acciones.<br>¿Esta seguro que desea eliminar el proyecto?', 'EliminarProyecto()', null);
}

function EliminarProyecto(){
  $.blockUI();
  $.ajax({
    type: "POST",
    url: URL_PATH + "/Proyecto/EliminarProyecto",
    data: {
      idProyecto : idProyectoActual,
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

function ModalObjetivoNuevo(){
  $('#modalObjetivo_titulo').html('Nuevo Objetivo');
  $('#modalObjetivo_grupoCodigo').removeClass('d-block');
  $('#modalObjetivo_grupoCodigo').addClass('d-none');
  $('#modalObjetivo_nombre').val('');
  $('#modalObjetivo_descripcion').val('');
  $('#modalObjetivo_guardar').html('Registrar');
  $('#modalObjetivo').modal();
}

function DibujarObjetivos(){
  $('#carouselObjetivos').html('');
  $('#carouselIndicadores').html('');
  var contenido = "";
  var indicadores = "";

  $('#carouselObjetivos').html(contenido);

  $('#mensajePonderacionObjetivos').css('display', 'none');
  if (objetivos.length > 0) {
    for (var i = 0; i < objetivos.length; i++) {
      
      if (objetivos[i]['ponderacion'] == 0) {
        $('#mensajePonderacionObjetivos').css('display', '-webkit-inline-box');
      }

      if (i ==  0){
        contenido = '<div class="carousel-item active">';
        indicadores += '<li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>';
      }
      else{
        contenido = '<div class="carousel-item">';
        indicadores += '<li data-target="#carouselExampleIndicators" data-slide-to="'+i+'"></li>';
      }
      contenido += '<div class="ObjetiveSlide">'+
                      '<div class="row mb-2">'+
                        '<div class="col-12 col-md-9">'+
                          '<h4 class="ObjetiveSlide-title">'+
                            objetivos[i]['nombre']+
                          '</h4>'+
                          '<div>'+
                            '<button name="opcionObjetivoEliminar" type="button" class="btn btn-sm btn-light float-left mr-2 d-none" onclick="ModalObjetivoEliminar('+objetivos[i]['id_objetivo']+')" data-toggle="tooltip" data-placement="bottom" title="Eliminar Objetivo"><i class="fas fa-trash-alt"></i></button>'+
                            '<button name="opcionObjetivoModificar" type="button" class="btn btn-sm btn-light float-left mr-2 d-none" onclick="ModalObjetivoModificar('+objetivos[i]['id_objetivo']+')" data-toggle="tooltip" data-placement="bottom" title="Modificar Objetivo"><i class="far fa-edit"></i></button>'+
                          '</div>'+
                          '<p class="card-text">'+objetivos[i]['descripcion']+'</p>'+
                        '</div>'+
                        '<div class="col-12 col-md-3">'+
                          '<div class="contenedor-avance">'+
                            '<svg class="radial-progress" data-percentage="'+objetivos[i]['avance']+'" viewBox="0 0 80 80" id="avanceObjetivo_'+objetivos[i]['id_objetivo']+'">'+
                              '<circle class="incomplete" cx="40" cy="40" r="35"></circle>'+
                              '<circle class="complete" cx="40" cy="40" r="35" style="stroke-dashoffset: 219.584067;"></circle>'+
                              '<text class="percentage" x="50%" y="57%" transform="matrix(0, 1, -1, 0, 80, 0)">'+objetivos[i]['avance']+'%</text>'+
                            '</svg>'+
                          '</div>'+
                        '</div>'+
                      '</div>'+
                      '<div class="row">'+
                        '<div class="col-12">'+
                          '<div class="card">'+
                            '<div class="card-header bg-white d-flex align-items-center justify-content-between">'+
                              '<strong class="mb-0">Productos</strong>'+
                              '<div>'+
                                '<button name="opcionProductoPonderar" type="button" class="btn btn-sm btn-primary float-right d-none" onclick="ModalPonderarProducto('+objetivos[i]['id_objetivo']+')" data-toggle="tooltip" data-placement="bottom" title="Ponderar productos"><i class="fas fa-list-ol mr-2"></i> Ponderar</button>'+
                                '<button name="opcionProductoNuevo" type="button" class="btn btn-sm btn-success float-right mr-2 d-none" onclick="ModalProductoNuevo('+objetivos[i]['id_objetivo']+')" data-toggle="tooltip" data-placement="bottom" title="Crear producto"><i class="far fa-file mr-2"></i> Crear</button>'+
                              '</div>'+
                            '</div>'+
                            '<ul class="list-group list-group-flush mb-3" id="listaProducto_'+objetivos[i].id_objetivo+'">';
              contenido +=  '</ul>'+
                          '</div>'+
                        '</div>'+
                      '</div>'+
                    '</div>'+
                  '</div>';
    
      $('#carouselObjetivos').append(contenido);

      DibujarProductos(objetivos[i].id_objetivo, objetivos[i].productos);
    }
  }
  else{
    contenido +=  '<div class="carousel-item active">'+
                    '<div class="ObjetiveSlide">'+
                      '<h4 class="ObjetiveSlide-title">El proyecto no cuenta con objetivos registrados.</h4>'+
                    '</div>'+
                  '</div>';
  }

  ActualizarAcceso();
  $('#carouselIndicadores').html(indicadores);
}

function ModalPonderarObjetivo(){
  $.blockUI();
  $.ajax({
    type: "GET",
    url: URL_PATH + "/Proyecto/ListarObjetivo",
    data: {
      idProyecto : idProyectoActual,
    },
    success: function(result){
      try{
        res = JSON.parse(result);
        if (res.estado == true) {
          $('#tiutloModalPonderacion').html('Ponderar objetivos');
          $('#listaPonderacion').html(res.vista);
          SumarPonderacion();
          $('#modalPonderacion').modal();
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

function DibujarProductos(idObjetivo, productos){
  var contenido = "";
  if (productos.length > 0) {
    for (var j = 0; j < productos.length; j++) {
      var mensajeEstado = "";
      if (productos[j]['ponderacion'] <= 0) {
        mensajeEstado = '<span class=\'text-danger\'> <strong>ALERTA :</strong> Debe registrar su ponderación.</span>';
      }

      contenido +=  '<li class="list-group-item" style="line-height:1;">'+
                      '<button name="opcionProductoEliminar" type="button" class="btn btn-light btn-sm mr-2 d-none" data-toggle="tooltip" data-placement="bottom" title="Eliminar Producto" onclick="ModalEliminarProducto('+idObjetivo+','+productos[j].id_producto+')"><i class="fas fa-trash-alt"></i></button>'+
                      '<button name="opcionProductoModificar" type="button" class="btn btn-light btn-sm mr-2 d-none" data-toggle="tooltip" data-placement="bottom" title="Modficar Producto" onclick="ModalModificarProducto('+idObjetivo+','+productos[j].id_producto+')"><i class="far fa-edit"></i></button>'+
                      '<a href="#" onclick="ModalProducto('+idObjetivo+','+productos[j].id_producto+')" style="display: -webkit-inline-box; line-height: 1;vertical-align: middle;"><strong>'+productos[j]['nombre']+'</strong><br><small>Ponderación : '+productos[j]['ponderacion']+mensajeEstado+'</small></a>'+
                    '</li>';
    }
  }
  else{
    contenido += '<li class="list-group-item">No se tienen productos enlasados.</li>';
  }

  $('#listaProducto_'+idObjetivo).html(contenido);
  ActualizarAcceso();
}

function RegistrarObjetivo(){
  $.blockUI();
  var objetivo = new Object();
  objetivo.nombre = $('#modalObjetivo_nombre').val();
  objetivo.descripcion = $('#modalObjetivo_descripcion').val();

  if($('#modalObjetivo_titulo').html() == 'Nuevo Objetivo'){
    $.ajax({
      type: "POST",
      url: URL_PATH + "/Proyecto/RegistrarObjetivo",
        data: {
          objetivo : objetivo,
          idProyecto : idProyectoActual,
        },
        success: function(result){
          try{
            res = JSON.parse(result);
            if (res.estado == true) {
              objetivos = res.objetivos;
              DibujarObjetivos();
              $('#modalObjetivo').modal('hide');
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
  else if($('#modalObjetivo_titulo').html() == 'Modificar Objetivo'){
    objetivo.idObjetivo = idObjetivoActivo;
    $.ajax({
      type: "POST",
      url: URL_PATH + "/Proyecto/ModificarObjetivo",
        data: {
          objetivo : objetivo,
          idProyecto : idProyectoActual,
        },
        success: function(result){
          try{
            res = JSON.parse(result);
            if (res.estado == true) {

              objetivos = res.objetivos;
              DibujarObjetivos();
              $('#modalObjetivo').modal('hide');
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
  else if($('#modalObjetivo_titulo').html() == 'Nuevo Producto'){
    $.ajax({
      type: "POST",
      url: URL_PATH + "/Proyecto/RegistrarProducto",
        data: {
          producto : objetivo,
          idProyecto : idProyectoActual,
          idObjetivo : idObjetivoActivo
        },
        success: function(result){
          try{
            res = JSON.parse(result);
            if (res.estado == true) {
              var productos = res.productos;
              DibujarProductos(idObjetivoActivo, productos);
              $('#modalObjetivo').modal('hide');
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
  else if($('#modalObjetivo_titulo').html() == 'Modificar Producto'){
    objetivo.idProducto = idProductoActivo;
    $.ajax({
      type: "POST",
      url: URL_PATH + "/Proyecto/ModificarProducto",
        data: {
          producto : objetivo,
          idProyecto : idProyectoActual,
          idObjetivo : idObjetivoActivo
        },
        success: function(result){
          try{
            res = JSON.parse(result);
            if (res.estado == true) {
              DibujarProductos(idObjetivoActivo, res.productos);
              $('#modalObjetivo').modal('hide');
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
  else if($('#modalObjetivo_titulo').html() == 'Nueva Actividad'){
    objetivo.codigo = $('#modalObjetivo_codigo').val();
    $.ajax({
      type: "POST",
      url: URL_PATH + "/Proyecto/RegistrarActividad",
        data: {
          actividad : objetivo,
          idProyecto : idProyectoActual,
          idObjetivo : idObjetivoActivo,
          idProducto : idProductoActivo
        },
        success: function(result){
          try{
            res = JSON.parse(result);
            if (res.estado == true) {
              DibujarActividades(res.actividades);
              $('#modalObjetivo').modal('hide');
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
  else if($('#modalObjetivo_titulo').html() == 'Modificar Actividad'){
    objetivo.idActividad = idActividadActiva;
    objetivo.codigo = $('#modalObjetivo_codigo').val();
    $.ajax({
      type: "POST",
      url: URL_PATH + "/Proyecto/ModificarActividad",
        data: {
          actividad : objetivo,
          idProyecto : idProyectoActual,
          idObjetivo : idObjetivoActivo,
          idProducto : idProductoActivo
        },
        success: function(result){
          try{
            res = JSON.parse(result);
            if (res.estado == true) {
              DibujarActividades(res.actividades);
              $('#modalObjetivo').modal('hide');
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

function ModalObjetivoModificar(idObjetivo){
  $.blockUI();
  idObjetivoActivo = idObjetivo;

  $.ajax({
    type: "GET",
    url: URL_PATH + "/Proyecto/BuscarObjetivo",
    data: {
      idProyecto : idProyectoActual,
      idObjetivo : idObjetivoActivo,
    },
    success: function(result){
      try{
        res = JSON.parse(result);
        if (res.estado == true) {
          $('#modalObjetivo_titulo').html('Modificar Objetivo');
          $('#modalObjetivo_grupoCodigo').removeClass('d-block');
          $('#modalObjetivo_grupoCodigo').addClass('d-none');
          $('#modalObjetivo_nombre').val(res.objetivo.nombre);
          $('#modalObjetivo_descripcion').val(res.objetivo.descripcion);
          $('#modalObjetivo_guardar').html('Modificar');
          $('#modalObjetivo').modal();
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

function ModalObjetivoEliminar(idObjetivo){
  MostrarConfirmacion(null,'¿Esta seguro que desea eliminar este objetivo?', 'EliminarObjetivo('+idObjetivo+')', null);
}

function EliminarObjetivo(idObjetivo){
  $.blockUI();
  $.ajax({
    type: "POST",
    url: URL_PATH + "/Proyecto/EliminarObjetivo",
    data: {
      idProyecto : idProyectoActual,
      idObjetivo : idObjetivo,
    },
    success: function(result){
      try{
        res = JSON.parse(result);
        if (res.estado == true) {
          objetivos = res.objetivos;
          DibujarObjetivos();
          $('#modalObjetivo').modal('hide');
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

function ModalProductoNuevo(idObjetivo){
  idObjetivoActivo = idObjetivo;
  $('#modalObjetivo_titulo').html('Nuevo Producto');
  $('#modalObjetivo_grupoCodigo').removeClass('d-block');
  $('#modalObjetivo_grupoCodigo').addClass('d-none');
  $('#modalObjetivo_nombre').val('');
  $('#modalObjetivo_descripcion').val('');
  $('#modalObjetivo_guardar').html('Registrar');
  $('#modalObjetivo').modal();
}

function ModalEliminarProducto(idObjetivo, idProducto){
  MostrarConfirmacion(null,'¿Esta seguro que desea eliminar este producto, el producto no podra recuperarse?', 'EliminarProducto('+idObjetivo+','+idProducto+')', null);
}

function EliminarProducto(idObjetivo, idProducto){
  $.blockUI();
  idObjetivoActivo = idObjetivo;
  $.ajax({
    type: "POST",
    url: URL_PATH + "/Proyecto/EliminarProducto",
      data: {
        idProyecto : idProyectoActual,
        idObjetivo : idObjetivoActivo,
        idProducto : idProducto,
      },
      success: function(result){
        try{
          res = JSON.parse(result);
          if (res.estado == true) {
            DibujarProductos(idObjetivoActivo, res.productos);
          }
          else {
        $.unblockUI();
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

function ModalModificarProducto(idObjetivo, idProducto){
  $.blockUI();

  idObjetivoActivo = idObjetivo;
  idProductoActivo = idProducto
  $.ajax({
    type: "GET",
    url: URL_PATH + "/Proyecto/BuscarProducto",
    data: {
      idProyecto : idProyectoActual,
      idObjetivo : idObjetivoActivo,
      idProducto : idProductoActivo,
    },
    success: function(result){
      try{
        res = JSON.parse(result);
        if (res.estado == true) {
          $('#modalObjetivo_titulo').html('Modificar Producto');
          $('#modalObjetivo_grupoCodigo').removeClass('d-block');
          $('#modalObjetivo_grupoCodigo').addClass('d-none');
          $('#modalObjetivo_nombre').val(res.producto.nombre);
          $('#modalObjetivo_descripcion').val(res.producto.descripcion);
          $('#modalObjetivo_guardar').html('Modificar');
          $('#modalObjetivo').modal();
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

function ModalProducto(idObjetivo, idProducto){
  $.ajax({
    type: "GET",
    url: URL_PATH + "/Proyecto/BuscarProducto",
      data: {
        idProducto : idProducto,
        idProyecto : idProyectoActual,
        idObjetivo : idObjetivo
      },
      success: function(result){
        try{
          res = JSON.parse(result);
          if (res.estado == true) {
            idObjetivoActivo = idObjetivo;
            idProductoActivo = idProducto;

            $("#botonNuevaActividad").unbind( "click" );
            $("#botonNuevaActividad").click(function(){ModalActividadNueva(idProducto);});

            $('#modalProductoDetallado_titulo').html(res.producto.nombre);
            $('#modalProductoDetallado_descripcion').html(res.producto.descripcion);

            $('#avanceProducto').attr('data-percentage', res.producto.avance);
            $('#avanceProducto text').html(res.producto.avance+'%')
            AnimarProgreso();

            DibujarActividades(res.actividades);
            $('#modalProductoDetallado').modal();
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

function DibujarActividades(actividades){
  var contenido = "";
  $('#accordionActividad').html(contenido);

  if (actividades.length > 0) {
    for (var i = 0; i < actividades.length; i++) {
      var mensajeEstado = "";
      if (actividades[i].estado == 'RECHAZADO') {
        actividades[i].descripcion += '<span class=\'text-danger\'><br><strong>Observación</strong> :'+actividades[i].observacion+'</span>';
      }
      else if(actividades[i].estado == 'AUTORIZADO') {
        mensajeEstado = ' Ponderación : ' + actividades[i].ponderacion;
        if (actividades[i].ponderacion <= 0) {
          mensajeEstado += '<span class=\'text-danger\'> <strong>ALERTA :</strong> Debe registrar su ponderación.</span>';
        }
      }
      contenido =  '<div class="card">'+
                      '<div class="card-header actividad-'+actividades[i].estado+'" id="cabeceraAccion_'+actividades[i].id_actividad+'">'+
                        '<div class="row">'+
                          '<div class="col-12 col-md-9" data-toggle="popover" title="Descripción" data-content="'+actividades[i].descripcion+'" data-trigger="hover" data-placement="bottom" data-html="true">'+
                            '<button type="button" class="btn btn-outline-secondary btn-sm btn-acordion collapsed mr-2" data-toggle="collapse" data-target="#collapse_'+actividades[i].id_actividad+'" aria-expanded="false" aria-controls="collapse_1"><i class="fas"></i></button>';
                            if (actividades[i].estado == 'NUEVO' || actividades[i].estado == 'RECHAZADO') {
              contenido +=    '<button name="opcionActividadEliminar" type="button" class="btn btn-outline-danger btn-sm mr-2 d-none" data-toggle="tooltip" data-placement="bottom" title="Eliminar Actividad" onclick="ModalEliminarActividad('+actividades[i].id_actividad+')"><i class="fas fa-trash-alt"></i></button>'+
                              '<button name="opcionActividadModificar" type="button" class="btn btn-outline-warning btn-sm mr-2 d-none" data-toggle="tooltip" data-placement="bottom" title="Modficar Actividad" onclick="ModalModificarActividad('+actividades[i].id_actividad+')"><i class="far fa-edit"></i></button>'+
                              '<button name="opcionAccionPonderar" type="button" class="btn btn-outline-info btn-sm mr-2 d-none" data-toggle="tooltip" data-placement="bottom" title="Asignar Ponderación" onclick="ModalPonderarAccion('+actividades[i].id_actividad+')"><i class="fas fa-list-ol"></i></button>'+
                              '<button name="opcionActividadSolicitar" type="button" class="btn btn-outline-primary btn-sm mr-2 d-none" data-toggle="tooltip" data-placement="bottom" title="Solicitar Autorización" onclick="ModalSolicitarAutorizacion('+actividades[i].id_actividad+')"><i class="fas fa-share-square"></i></button>';
                            }
                            else if (actividades[i].estado == 'SOLICITADO') {
              contenido +=    '<button name="opcionActividadRechazar" type="button" class="btn btn-outline-danger btn-sm mr-2 d-none" data-toggle="tooltip" data-placement="bottom" title="Rechazar" onclick="ModalRechazarActividad('+actividades[i].id_actividad+')" ><i class="fas fa-times-circle"></i></button>'+
                              '<button name="opcionActividadAutorizar" type="button" class="btn btn-outline-success btn-sm mr-2 d-none" data-toggle="tooltip" data-placement="bottom" title="Autorizar" onclick="ModalAutorizarActividad('+actividades[i].id_actividad+')"><i class="fas fa-check-double"></i></button>';
                            }
                            else if (actividades[i].estado == 'AUTORIZADO') {
              contenido +=    '<button name="opcionActividadHabilitarEdicion" type="button" class="btn btn-outline-danger btn-sm mr-2 d-none" data-toggle="tooltip" data-placement="bottom" title="Habilitar Edición" onclick="ModalRechazarActividad('+actividades[i].id_actividad+')" ><i class="fas fa-user-edit"></i></button>';
                            }
              contenido +=  '<strong style="display: -webkit-inline-box; line-height: 1;vertical-align: middle;">'+actividades[i].codigo+' - '+actividades[i].nombre+'<br><small class="text-red">Estado : '+actividades[i].estado+mensajeEstado+'</small></strong>'+
                          '</div>'+
                          '<div class="col-12 col-md-3">'+
                            '<div class="progress">'+
                              '<div id="progreso_actividad_'+actividades[i].id_actividad+'" class="progress-bar progress-bar-striped bg-success" role="progressbar" style="width: '+actividades[i].avance+'%" aria-valuenow="'+actividades[i].avance+'" aria-valuemin="0" aria-valuemax="100">'+actividades[i].avance+'%</div>'+
                            '</div>'+
                          '</div>'+
                        '</div>'+
                      '</div>'+
                      '<div id="collapse_'+actividades[i].id_actividad+'" class="collapse" aria-labelledby="cabeceraAccion_'+actividades[i].id_actividad+'" data-parent="#accordionActividad">'+
                        '<div class="card-body">'+
                          '<table class="table table-sm table-hover mb-0">'+
                              '<thead>'+
                                '<tr>'+
                                  '<th></th>'+
                                  '<th scope="col">Acción</th>'+
                                  '<th scope="col">Ponderación</th>'+
                                  '<th scope="col">Meta (descripción)</th>'+
                                  '<th scope="col">Meta<br>Propuesta</th>'+
                                  '<th scope="col">Meta<br>Alcanzada</th>'+
                                  '<th scope="col">Avance %</th>'+
                                '</tr>'+
                              '</thead>'+
                              '<tbody id="tablaAccion_'+actividades[i].id_actividad+'">';
            contenido +=      '</tbody>'+
                              '<tfoot>'+
                                '<tr>'+
                                  '<td colspan="7">'+
                                    '<button name="opcionAccionNuevo" type="button" class="btn btn-sm btn-outline-success float-right d-none" onclick="ModalAccionNueva('+actividades[i].id_actividad+')"><i class="fas fa-plus-circle"></i>  Agregar Acción</button>'+
                                  '</td>'+
                                '</tr>'+
                              '</tfoot>'+
                            '</table>'+ 
                          '</div>'+
                      '</div>'+
                    '</div>';

      $('#accordionActividad').append(contenido);

      DibujarAcciones(actividades[i].id_actividad, actividades[i].estado, actividades[i].acciones);
    }
  }
  else{
    contenido += '<div class="card"><div class="card-header" id="cabeceraAccion_1">Aun no se han registrado actividades.</div></div>';
  }
  
  ActualizarAcceso();

  $('[data-toggle="popover"]').popover();
}

function DibujarAcciones(idActividad, estadoActividad, acciones){
  var porcentajeAvance = 0;
  var contenido = "";

  if (acciones.length > 0) {
    for (var j = 0; j < acciones.length; j++) {
      var colorBotonCronograma = "info";
      contenido +=      '<tr>'+
                          '<td>';
                          if (estadoActividad == 'NUEVO' || estadoActividad == 'RECHAZADO') {
                            if (acciones[j].cantidad_ejecucion <= 0) {
                              colorBotonCronograma = "danger";
                            }
                            else if(acciones[j].cuantitativa == 1 && acciones[j].meta_cuantitativa > acciones[j].metaEjecucion)
                            {
                              colorBotonCronograma = "danger";
                            }
                            else if(acciones[j].cuantitativa == 0 && 100 > acciones[j].metaEjecucion)
                            {
                              colorBotonCronograma = "danger";
                            }

      contenido +=          '<button name="opcionAccionEliminar" type="button" class="btn btn-outline-danger btn-sm mr-2 d-none" onclick="ModalAccionEliminar('+idActividad+','+acciones[j].id_accion+')" data-toggle="tooltip" data-placement="bottom" title="Eliminar acción"><i class="fas fa-trash-alt"></i></button>'+
                            '<button name="opcionAccionModificar" type="button" class="btn btn-outline-warning btn-sm mr-2 d-none" onclick="ModalAccionModificar('+idActividad+','+acciones[j].id_accion+')" data-toggle="tooltip" data-placement="bottom" title="Modificar acción"><i class="far fa-edit"></i></button>'+
                            '<button type="button" class="btn btn-outline-'+colorBotonCronograma+' btn-sm" onclick="ModalListarEjecucion('+idActividad+','+acciones[j].id_accion+','+acciones[j].requiere_dato_adicional+',\''+estadoActividad+'\',\''+acciones[j].unidad_meta+'\',\''+acciones[j].unidad_dato_adicional+'\','+acciones[j].cuantitativa+')" data-toggle="tooltip" data-placement="bottom" title="Ver cronograma de ejecuciones"><i class="far fa-list-alt"></i></button>';
                          }
                          else if (estadoActividad == 'AUTORIZADO'){
      contenido +=          '<button type="button" class="btn btn-outline-info btn-sm mr-2" onclick="ModalListarEjecucion('+idActividad+','+acciones[j].id_accion+','+acciones[j].requiere_dato_adicional+',\''+estadoActividad+'\',\''+acciones[j].unidad_meta+'\',\''+acciones[j].unidad_dato_adicional+'\','+acciones[j].cuantitativa+')" data-toggle="tooltip" data-placement="bottom" title="Ver ejecuciones registradas"><i class="far fa-list-alt"></i></button>';
                          }
      contenido +=        '</td>'+
                          '<td data-toggle="popover" title="Descripción" data-content="'+acciones[j].descripcion+'" data-trigger="hover" data-placement="bottom">'+acciones[j].nombre+'</td>'+
                          '<td>'+acciones[j].ponderacion+'</td>'+
                          '<td>'+acciones[j].meta_nominal+'</td>'+
                          '<td>';
                            if (acciones[j].cuantitativa == 1) {
            contenido +=      acciones[j].meta_cuantitativa;
                              porcentajeAvance = parseFloat((acciones[j].avance / acciones[j].meta_cuantitativa) * 100);
                            }
                            else{
            contenido +=      '%';
                              porcentajeAvance = parseFloat(acciones[j].avance);
                            }
      contenido +=        '</td>'+
                          '<td id="meta_ejecutada_'+acciones[j].id_accion+'">'+acciones[j].avance+'</td>'+
                          '<td>'+
                            '<div class="progress">'+
                              '<div id="progreso_accion_'+acciones[j].id_accion+'" class="progress-bar progress-bar-striped bg-primary" role="progressbar" style="width: '+porcentajeAvance.toFixed(2)+'%" aria-valuenow="'+porcentajeAvance.toFixed(2)+'" aria-valuemin="0" aria-valuemax="100">'+porcentajeAvance.toFixed(2)+'%</div>'+
                            '</div>'+
                          '</td>'+
                        '</tr>';
    }
  }
  else{
    contenido +=  '<tr><td colspan="7">Aun no se han registrado acciones para esta actividad.</td></tr>';              
  }
  $('#tablaAccion_'+idActividad).html(contenido);
  $('[data-toggle="popover"]').popover();1

  ActualizarAcceso();
}

function ModalActividadNueva(idProducto){
  idProductoActivo = idProducto;
  $('#modalObjetivo_titulo').html('Nueva Actividad');
  $('#modalObjetivo_grupoCodigo').removeClass('d-none');
  $('#modalObjetivo_grupoCodigo').addClass('d-block');
  $('#modalObjetivo_nombre').val('');
  $('#modalObjetivo_descripcion').val('');
  $('#modalObjetivo_guardar').html('Registrar');
  $('#modalObjetivo').modal();
}

function ModalAccionNueva(idActividad){
  idActividadActiva = idActividad;

  $('#modalAccion_titulo').html('Nueva acción');
  $('#modalAccion_nombre').val('');
  $('#modalAccion_descripcion').val('');
  $('#modalAccion_meta').val('');
  $('#esCuantitativa').prop('checked' , false);
  $('#modalAccion_metaCuantitativa').val('');
  $('#modalAccion_unidadMeta').val('');
  $('#modalAccion_unidadMedidaAdicional').val('');
  $('#requiereDatoAdicional').prop('checked' , false);
  $('#participacionFamiliar').prop('checked' , false);
  $('#modalAccion_guardar').html('Registrar');
  $('#modalAccion').modal();

  setTimeout(
    function() {
      $('#modalAccion_comunidad').chosen();
      $('#modalAccion_comunidad').val(0);
      $('#modalAccion_comunidad').trigger('chosen:updated');
    }, 
  200);
  
  HabilitarCantidad();
  HabilitarUnidadMedidaAdicional();
}

function HabilitarCantidad(){
  if ($('#esCuantitativa').prop('checked')) {
    $('#modalAccion_metaCuantitativa').prop('readonly', false);
    $('#modalAccion_unidadMeta').prop('readonly', false);
  }else
  {
    $('#modalAccion_metaCuantitativa').prop('readonly', true);
    $('#modalAccion_unidadMeta').prop('readonly', true);
  }
}

function HabilitarUnidadMedidaAdicional(){
  if ($('#requiereDatoAdicional').prop('checked')) {
    $('#modalAccion_unidadMedidaAdicional').prop('readonly', false);
  }else  {
    $('#modalAccion_unidadMedidaAdicional').prop('readonly', true);
  }
}

function RegistrarAccion(){
  $.blockUI();

  var accion = new Object();
  accion.nombre = $('#modalAccion_nombre').val();
  accion.descripcion = $('#modalAccion_descripcion').val();
  accion.metaNominal = $('#modalAccion_meta').val();
  accion.cuantitativa = $('#esCuantitativa').prop('checked') ? 1 : 0;
  accion.requiereDatoAdicional = $('#requiereDatoAdicional').prop('checked') ? 1 : 0;
  accion.participacionFamiliar = $('#participacionFamiliar').prop('checked') ? 1 : 0;
  accion.ubicacion = $('#modalAccion_ubicacion').val();
  accion.idComunidad = $('#modalAccion_comunidad option:selected').val();
  
  if (accion.cuantitativa) {
    accion.metaCuantitativa = $('#modalAccion_metaCuantitativa').val();
    accion.unidadMeta = $('#modalAccion_unidadMeta').val();
  }
  else{
    accion.metaCuantitativa = 0;
    accion.unidadMeta = '';
  }

  if (accion.requiereDatoAdicional) {
    accion.unidadMedidaAdicional = $('#modalAccion_unidadMedidaAdicional').val();
  }
  else{
    accion.unidadMedidaAdicional = '';
  }
  if (!(accion.idComunidad > 0) || accion.idComunidad == 99999) {
    MostrarConfirmacion('Error de usuario', 'Seleccione una comunidad', null, null);
    $.unblockUI();
  }
  else{
    if($('#modalAccion_titulo').html() == 'Nueva acción'){
      $.ajax({
        type: "POST",
        url: URL_PATH + "/Proyecto/RegistrarAccion",
        data: {
          accion : accion,
          idProyecto : idProyectoActual,
          idObjetivo : idObjetivoActivo,
          idProducto : idProductoActivo,
          idActividad : idActividadActiva
        },
        success: function(result){
          try{
            res = JSON.parse(result);
            if (res.estado == true) {
              DibujarAcciones(idActividadActiva, res.estadoActividad, res.acciones);
              $('#modalAccion').modal('hide');
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
    else if($('#modalAccion_titulo').html() == 'Modificar acción'){
      accion.idAccion = idAccionActiva;
      $.ajax({
        type: "POST",
        url: URL_PATH + "/Proyecto/ModificarAccion",
        data: {
          accion : accion,
          idProyecto : idProyectoActual,
          idObjetivo : idObjetivoActivo,
          idProducto : idProductoActivo,
          idActividad : idActividadActiva
        },
        success: function(result){
          try{
            res = JSON.parse(result);
            if (res.estado == true) {
              DibujarAcciones(idActividadActiva, res.estadoActividad, res.acciones);
              $('#modalAccion').modal('hide');
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

function ModalAccionModificar(idActividad, idAccion){
  $.blockUI();
  idActividadActiva = idActividad;

  $.ajax({
    type: "GET",
    url: URL_PATH + "/Proyecto/BuscarAccion",
    data: {
      idAccion : idAccion,
      idProyecto : idProyectoActual,
      idObjetivo : idObjetivoActivo,
      idProducto : idProductoActivo,
      idActividad : idActividadActiva
    },
    success: function(result){
      try{
        res = JSON.parse(result);
        if (res.estado == true) {
          idAccionActiva = idAccion;

          $('#modalAccion_titulo').html('Modificar acción');
          $('#modalAccion_nombre').val(res.accion.nombre);
          $('#modalAccion_descripcion').val(res.accion.descripcion);
          $('#modalAccion_meta').val(res.accion.meta_nominal);
          $('#modalAccion_ubicacion').val(res.accion.ubicacion);
          $('#esCuantitativa').prop('checked' , Boolean(parseInt(res.accion.cuantitativa)));
          HabilitarCantidad();
          $('#requiereDatoAdicional').prop('checked' , Boolean(parseInt(res.accion.requiere_dato_adicional)));
          HabilitarUnidadMedidaAdicional();
          $('#modalAccion_metaCuantitativa').val(res.accion.meta_cuantitativa);
          $('#modalAccion_unidadMeta').val(res.accion.unidad_meta);
          $('#modalAccion_unidadMedidaAdicional').val(res.accion.unidad_dato_adicional);
          $('#participacionFamiliar').prop('checked' , Boolean(parseInt(res.accion.participacion_familiar)));
          $('#modalAccion_guardar').html('Modificar');
          $('#modalAccion').modal();

          setTimeout(
            function() {
              $('#modalAccion_comunidad').val(res.accion.id_comunidad).trigger("chosen:updated");
              $('#modalAccion_comunidad').chosen();
            }, 
          200);
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

function ModalAccionEliminar(idActividad, idAccion){
  MostrarConfirmacion(null,'¿Esta seguro que desea eliminar esta acción?', 'EliminarAccion('+idActividad+','+idAccion+')', null);
}

function EliminarAccion(idActividad, idAccion){
  $.blockUI();
  $.ajax({
    type: "POST",
    url: URL_PATH + "/Proyecto/EliminarAccion",
    data: {
      idAccion : idAccion,
      idProyecto : idProyectoActual,
      idObjetivo : idObjetivoActivo,
      idProducto : idProductoActivo,
      idActividad : idActividad
    },
    success: function(result){
      try{
        res = JSON.parse(result);
        if (res.estado == true) {
          DibujarAcciones(idActividad, res.estadoActividad ,res.acciones);
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

function ModalPonderarAccion(idActividad){
  idActividadActiva = idActividad;
  $.blockUI();
  $.ajax({
    type: "GET",
    url: URL_PATH + "/Proyecto/ListarAccion",
    data: {
      idProyecto : idProyectoActual,
      idObjetivo : idObjetivoActivo,
      idProducto : idProductoActivo,
      idActividad : idActividadActiva
    },
    success: function(result){
      try{
        res = JSON.parse(result);
        if (res.estado == true) {
          $('#tiutloModalPonderacion').html('Ponderar acciones');
          $('#listaPonderacion').html(res.vista);
          SumarPonderacion();
          $('#modalPonderacion').modal();
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

function SumarPonderacion(){
  var total = 0;

  $('#listaPonderacion #tablaReporte tr input').each(function(){
    total += parseFloat($(this).val());
  });

  $('#listaPonderacion [name="total"]').html(total.toFixed(2));

  return total.toFixed(2);
}

function RegistrarPonderacion(){
  $.blockUI();

  var total = SumarPonderacion();

  if (parseFloat(total) != 100) {
    MostrarConfirmacion('Error', 'El total debe ser igual a 100, que equivale al 100% de la ejecución.', null, null);
    $.unblockUI();
  }
  else{
    if ($('#tiutloModalPonderacion').html() == "Ponderar acciones") {
      var listaAccion = [];
      $('#listaPonderacion #tablaReporte tr').each(function(){
        var accion = new Object();
        accion.idAccion = this.id;
        accion.ponderacion = $(this).find('input').val();
        listaAccion.push(accion);
      });

      $.ajax({
        type: "POST",
        url: URL_PATH + "/Proyecto/ModificarPonderacionAccion",
        data: {
          idProyecto : idProyectoActual,
          idObjetivo : idObjetivoActivo,
          idProducto : idProductoActivo,
          idActividad : idActividadActiva,
          acciones : listaAccion
        },
        success: function(result){
          try{
            res = JSON.parse(result);
            if (res.estado == true) {
              $('#modalPonderacion').modal('hide');
              MostrarConfirmacion('Correcto', 'Registrado correctamente.', null, null);
              DibujarAcciones(idActividadActiva, res.estadoActividad ,res.acciones);
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
    else if ($('#tiutloModalPonderacion').html() == "Ponderar actividades") {
      var listaActividad = [];
      $('#listaPonderacion #tablaReporte tr').each(function(){
        var actividad = new Object();
        actividad.idActividad = this.id;
        actividad.ponderacion = $(this).find('input').val();
        listaActividad.push(actividad);
      });

      $.ajax({
        type: "POST",
        url: URL_PATH + "/Proyecto/ModificarPonderacionActividad",
        data: {
          idProyecto : idProyectoActual,
          idObjetivo : idObjetivoActivo,
          idProducto : idProductoActivo,
          actividades : listaActividad
        },
        success: function(result){
          try{
            res = JSON.parse(result);
            if (res.estado == true) {
              $('#modalPonderacion').modal('hide');
              MostrarConfirmacion('Correcto', 'Registrado correctamente.', null, null);
              DibujarActividades(res.actividades);

              $('#avanceProducto').attr('data-percentage', res.avanceProducto);
              $('#avanceProducto text').html(res.avanceProducto+'%');
              
              $('#avanceObjetivo_'+idObjetivoActivo).attr('data-percentage', res.avanceObjetivo);
              $('#avanceObjetivo_'+idObjetivoActivo+' text').html(res.avanceObjetivo+'%');

              $('#avanceProyecto').attr('data-percentage', res.avanceProyecto);
              $('#avanceProyecto text').html(res.avanceProyecto+'%');

              AnimarProgreso();
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
    else if ($('#tiutloModalPonderacion').html() == "Ponderar productos") {
      var listaProducto = [];
      $('#listaPonderacion #tablaReporte tr').each(function(){
        var producto = new Object();
        producto.idProducto = this.id;
        producto.ponderacion = $(this).find('input').val();
        listaProducto.push(producto);
      });

      $.ajax({
        type: "POST",
        url: URL_PATH + "/Proyecto/ModificarPonderacionProducto",
        data: {
          idProyecto : idProyectoActual,
          idObjetivo : idObjetivoActivo,
          productos : listaProducto
        },
        success: function(result){
          try{
            res = JSON.parse(result);
            if (res.estado == true) {
              $('#modalPonderacion').modal('hide');
              MostrarConfirmacion('Correcto', 'Registrado correctamente.', null, null);
              DibujarProductos(idObjetivoActivo, res.productos);

              $('#avanceObjetivo_'+idObjetivoActivo).attr('data-percentage', res.avanceObjetivo);
              $('#avanceObjetivo_'+idObjetivoActivo+' text').html(res.avanceObjetivo+'%');

              $('#avanceProyecto').attr('data-percentage', res.avanceProyecto);
              $('#avanceProyecto text').html(res.avanceProyecto+'%');

              AnimarProgreso();
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
    else if ($('#tiutloModalPonderacion').html() == "Ponderar objetivos") {
      var listaObjetivo = [];
      $('#listaPonderacion #tablaReporte tr').each(function(){
        var objetivo = new Object();
        objetivo.idObjetivo = this.id;
        objetivo.ponderacion = $(this).find('input').val();
        listaObjetivo.push(objetivo);
      });

      $.ajax({
        type: "POST",
        url: URL_PATH + "/Proyecto/ModificarPonderacionObjetivo",
        data: {
          idProyecto : idProyectoActual,
          objetivos : listaObjetivo
        },
        success: function(result){
          try{
            res = JSON.parse(result);
            if (res.estado == true) {
              $('#modalPonderacion').modal('hide');
              MostrarConfirmacion('Correcto', 'Registrado correctamente.', null, null);
              objetivos = res.objetivos;
              DibujarObjetivos();

              $('#avanceProyecto').attr('data-percentage', res.avanceProyecto);
              $('#avanceProyecto text').html(res.avanceProyecto+'%');

              AnimarProgreso();
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

function ModalSolicitarAutorizacion(idActividad){
  MostrarConfirmacion(null,'¿Esta seguro que desea solicitar la autorización de esta actividad, la actividad no podra ser modificada posteriormente?', 'SolicitarAutorizacion('+idActividad+')', null);
}

function SolicitarAutorizacion(idActividad){
  $.blockUI();
  $.ajax({
    type: "POST",
    url: URL_PATH + "/Proyecto/SolicitarAutorizacion",
      data: {
        idProyecto : idProyectoActual,
        idObjetivo : idObjetivoActivo,
        idProducto : idProductoActivo,
        idActividad : idActividad
      },
      success: function(result){
        try{
          res = JSON.parse(result);
          if (res.estado == true) {
            DibujarActividades(res.actividades);
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

function ModalRechazarActividad(idActividad){
  MostrarConfirmacion(null,'¿Esta seguro que desea rechazar esta actividad?', 'RechazarActividad('+idActividad+')', 'Observación');
}

function RechazarActividad(idActividad){
  $.blockUI();
  $.ajax({
    type: "POST",
    url: URL_PATH + "/Proyecto/RechazarActividad",
      data: {
        idProyecto : idProyectoActual,
        idObjetivo : idObjetivoActivo,
        idProducto : idProductoActivo,
        idActividad : idActividad,
        observacion : $('#modalComunDatoAdicional').val()
      },
      success: function(result){
        try{
          res = JSON.parse(result);
          if (res.estado == true) {
            DibujarActividades(res.actividades);
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

function ModalAutorizarActividad(idActividad){
  MostrarConfirmacion(null,'¿Esta seguro que desea autorizar esta actividad, la actividad ya no podra sufrir cambios una vez autorizadad?', 'AutorizarActividad('+idActividad+')', null);
}

function AutorizarActividad(idActividad){
  $.blockUI();
  $.ajax({
    type: "POST",
    url: URL_PATH + "/Proyecto/AutorizarActividad",
      data: {
        idProyecto : idProyectoActual,
        idObjetivo : idObjetivoActivo,
        idProducto : idProductoActivo,
        idActividad : idActividad
      },
      success: function(result){
        try{
          res = JSON.parse(result);
          if (res.estado == true) {
            DibujarActividades(res.actividades);
          }
          else {
        $.unblockUI();
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

function ModalEliminarActividad(idActividad){
  MostrarConfirmacion(null,'¿Esta seguro que desea eliminar esta actividad, la actividad ya no podra recuperarse?', 'EliminarActividad('+idActividad+')', null);
}

function EliminarActividad(idActividad){
  $.blockUI();
  $.ajax({
    type: "POST",
    url: URL_PATH + "/Proyecto/EliminarActividad",
      data: {
        idProyecto : idProyectoActual,
        idObjetivo : idObjetivoActivo,
        idProducto : idProductoActivo,
        idActividad : idActividad
      },
      success: function(result){
        try{
          res = JSON.parse(result);
          if (res.estado == true) {
            DibujarActividades(res.actividades);
          }
          else {
        $.unblockUI();
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

function ModalModificarActividad(idActividad){
  $.blockUI();

  $.ajax({
    type: "GET",
    url: URL_PATH + "/Proyecto/BuscarActividad",
    data: {
      idProyecto : idProyectoActual,
      idObjetivo : idObjetivoActivo,
      idProducto : idProductoActivo,
      idActividad : idActividad
    },
    success: function(result){
      try{
        res = JSON.parse(result);
        if (res.estado == true) {
          idActividadActiva = idActividad;

          $('#modalObjetivo_titulo').html('Modificar Actividad');
          $('#modalObjetivo_grupoCodigo').removeClass('d-none');
          $('#modalObjetivo_grupoCodigo').addClass('d-block');
          $('#modalObjetivo_codigo').val(res.actividad.codigo);
          $('#modalObjetivo_nombre').val(res.actividad.nombre);
          $('#modalObjetivo_descripcion').val(res.actividad.descripcion);
          $('#modalObjetivo_guardar').html('Modificar');
          $('#modalObjetivo').modal();
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

function ModalPonderarActividad(){
  $.blockUI();
  $.ajax({
    type: "GET",
    url: URL_PATH + "/Proyecto/ListarActividad",
    data: {
      idProyecto : idProyectoActual,
      idObjetivo : idObjetivoActivo,
      idProducto : idProductoActivo
    },
    success: function(result){
      try{
        res = JSON.parse(result);
        if (res.estado == true) {
          $('#tiutloModalPonderacion').html('Ponderar actividades');
          $('#listaPonderacion').html(res.vista);
          SumarPonderacion();
          $('#modalPonderacion').modal();
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

function ModalPonderarProducto(idObjetivo){
  idObjetivoActivo = idObjetivo;
  $.blockUI();
  $.ajax({
    type: "GET",
    url: URL_PATH + "/Proyecto/ListarProducto",
    data: {
      idProyecto : idProyectoActual,
      idObjetivo : idObjetivoActivo,
    },
    success: function(result){
      try{
        res = JSON.parse(result);
        if (res.estado == true) {
          $('#tiutloModalPonderacion').html('Ponderar productos');
          $('#listaPonderacion').html(res.vista);
          SumarPonderacion();
          $('#modalPonderacion').modal();
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

function ModalAccionEjecucion(unidadMeta, unidadDatoAdicional, cuantitativa){
  $('#tituloModalAcionEjecucion').html('Programar Ejecución');
  $('#fechaInicio').val('');
  $('#fechaFin').val('');
  $('#fechaEjecucion').val('');
  $('#meta').val('');
  if (cuantitativa == 1) {
    $('#unidadMeta').val(unidadMeta);
    $('#unidadAvance').val(unidadMeta);
  }
  else{
    $('#unidadMeta').val('%');
    $('#unidadAvance').val('%');
  }

  $('#avance').val('');
  $('#observacion').val('');
  $('#datoAdicional').val('');
  $('#unidadDatoAdicional').val(unidadDatoAdicional);
  
  $('#fechaInicio').prop('readonly', false);
  $('#fechaFin').prop('readonly', false);
  $('#meta').prop('readonly', false);

  $('#fechaEjecucion').prop('readonly', true);
  $('#avance').prop('readonly', true);
  $('#observacion').prop('readonly', true);
  $('#datoAdicional').prop('readonly', true);
  $('#unidadDatoAdicional').prop('readonly', true);

  $('#modalAccionEjecucion').modal();
}

function ProgramarAccionEjecucion(){
  $.blockUI();

  var ejecucion = new Object();
  ejecucion.fechaInicio = $('#fechaInicio').val();
  ejecucion.fechaFin = $('#fechaFin').val();
  ejecucion.meta = $('#meta').val();
  ejecucion.avance = $('#avance').val();
  ejecucion.observacion = $('#observacion').val();
  ejecucion.medidaAdicional = $('#datoAdicional').val();

  if ($('#tituloModalAcionEjecucion').html() == 'Programar Ejecución') {
    $.ajax({
      type: "POST",
      url: URL_PATH + "/Proyecto/RegistrarEjecucionProgramada",
      data: {
        ejecucion : ejecucion,
        idProyecto : idProyectoActual,
        idObjetivo : idObjetivoActivo,
        idProducto : idProductoActivo,
        idActividad : idActividadActiva,
        idAccion : idAccionActiva
      },
      success: function(result){
        try{
          res = JSON.parse(result);
          if (res.estado == true) {
            $('#listaEjecucion').html(res.vista);
            DibujarAcciones(idActividadActiva, res.estadoActividad, res.acciones);
            $('#modalAccionEjecucion').modal('hide');
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
  else if ($('#tituloModalAcionEjecucion').html() == 'Modificar ejecución programada') {
    ejecucion.idAccionEjecucion = idAccionEjecucionActiva;
    $.ajax({
      type: "POST",
      url: URL_PATH + "/Proyecto/ModificarEjecucionProgramada",
      data: {
        ejecucion : ejecucion,
        idProyecto : idProyectoActual,
        idObjetivo : idObjetivoActivo,
        idProducto : idProductoActivo,
        idActividad : idActividadActiva,
        idAccion : idAccionActiva
      },
      success: function(result){
        try{
          res = JSON.parse(result);
          if (res.estado == true) {
            $('#listaEjecucion').html(res.vista);
            ActualizarAcceso();
            $('#modalAccionEjecucion').modal('hide');
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
  else if ($('#tituloModalAcionEjecucion').html() == 'Registrar Ejecución') {
    ejecucion.idAccionEjecucion = idAccionEjecucionActiva;
    ejecucion.fechaEjecucion = $('#fechaEjecucion').val();
    if (ejecucion.fechaEjecucion.length > 0) {
      $.ajax({
        type: "POST",
        url: URL_PATH + "/Proyecto/RegistrarEjecucionAvance",
        data: {
          ejecucion : ejecucion,
          idProyecto : idProyectoActual,
          idObjetivo : idObjetivoActivo,
          idProducto : idProductoActivo,
          idActividad : idActividadActiva,
          idAccion : idAccionActiva
        },
        success: function(result){
          try{
            res = JSON.parse(result);
            if (res.estado == true) {
              $('#listaEjecucion').html(res.vista);
              $('#meta_ejecutada_'+idAccionActiva).html(res.ejecutado);
              $('#progreso_accion_'+idAccionActiva).css('width', res.avanceAccion+'%');
              $('#progreso_accion_'+idAccionActiva).html(res.avanceAccion+'%');

              $('#progreso_actividad_'+idActividadActiva).css('width', res.avanceActividad+'%');
              $('#progreso_actividad_'+idActividadActiva).html(res.avanceActividad+'%');

              $('#avanceProducto').attr('data-percentage', res.avanceProducto);
              $('#avanceProducto text').html(res.avanceProducto+'%');
              
              $('#avanceObjetivo_'+idObjetivoActivo).attr('data-percentage', res.avanceObjetivo);
              $('#avanceObjetivo_'+idObjetivoActivo+' text').html(res.avanceObjetivo+'%');

              $('#avanceProyecto').attr('data-percentage', res.avanceProyecto);
              $('#avanceProyecto text').html(res.avanceProyecto+'%');

              AnimarProgreso();
              ActualizarAcceso();

              $('#modalAccionEjecucion').modal('hide');
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
      MostrarConfirmacion('Error de usuario', "Ingrese fecha de ejecucion", null, null);
      $.unblockUI();
    }
  }
}

function ModalAccionEjecucionModificar(idAccionEjecucion){
  $.blockUI();
  $.ajax({
    type: "GET",
    url: URL_PATH + "/Proyecto/BuscarAccionEjecucion",
    data: {
      idProyecto : idProyectoActual,
      idObjetivo : idObjetivoActivo,
      idProducto : idProductoActivo,
      idActividad : idActividadActiva,
      idAccion : idAccionActiva,
      idAccionEjecucion : idAccionEjecucion
    },
    success: function(result){
      try{
        res = JSON.parse(result);
        if (res.estado == true) {
          idAccionEjecucionActiva = idAccionEjecucion;

          $('#tituloModalAcionEjecucion').html('Modificar ejecución programada');
          $('#fechaInicio').val(res.ejecucion.fecha_inicio);
          $('#fechaFin').val(res.ejecucion.fecha_fin);
          $('#fechaEjecucion').val(res.ejecucion.fecha_ejecucion);
          $('#meta').val(res.ejecucion.meta);
          $('#avance').val(res.ejecucion.avance);
          $('#observacion').val(res.ejecucion.observacion);
          $('#datoAdicional').val(res.ejecucion.medida_adicional);

          $('#fechaInicio').prop('readonly', false);
          $('#fechaFin').prop('readonly', false);
          $('#meta').prop('readonly', false);
        
          $('#avance').prop('readonly', true);
          $('#observacion').prop('readonly', true);
          $('#datoAdicional').prop('readonly', true);

          $('#modalAccionEjecucion').modal();
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

function ModalAccionEjecucionEliminar(idAccionEjecucion){ 
  MostrarConfirmacion(null,'¿Esta seguro que desea eliminar esta programación?', 'EliminarAccionEjecucion('+idAccionEjecucion+')', null);
}

function EliminarAccionEjecucion(idAccionEjecucion){
  $.blockUI();
  $.ajax({
    type: "POST",
    url: URL_PATH + "/Proyecto/EliminarEjecucionProgramada",
    data: {
      idProyecto : idProyectoActual,
      idObjetivo : idObjetivoActivo,
      idProducto : idProductoActivo,
      idActividad : idActividadActiva,
      idAccion : idAccionActiva,
      idAccionEjecucion : idAccionEjecucion
    },
    success: function(result){
      try{
        res = JSON.parse(result);
        if (res.estado == true) {
          $('#listaEjecucion').html(res.vista);
          DibujarAcciones(idActividadActiva, res.estadoActividad, res.acciones);
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

function ModalListarEjecucion(idActividad, idAccion, datoAdicional, estadoActividad, unidadMeta, unidadDatoAdicional, cuantitativa){
  requiereDatoAdicional = datoAdicional;
  idActividadActiva = idActividad;
  idAccionActiva = idAccion;
  $.blockUI();
  $.ajax({
    type: "GET",
    url: URL_PATH + "/Proyecto/ListarAccionEjecucion",
    data: {
      idProyecto : idProyectoActual,
      idObjetivo : idObjetivoActivo,
      idProducto : idProductoActivo,
      idActividad : idActividadActiva,
      idAccion : idAccionActiva
    },
    success: function(result){
      try{
        res = JSON.parse(result);
        if (res.estado == true) {
          $('#tituloModalListaEjecucion').html(res.nombreAccion);
          $('#listaEjecucion').html(res.vista);
          
          if (estadoActividad == 'AUTORIZADO') {
            $('#modalListaEjecucion .modal-footer').css('display', 'none');
          }
          else{
            $('#modalListaEjecucion .modal-footer').css('display', 'flex');
            $('#modalListaEjecucion .btn-outline-success').unbind( "click" );
            $("#modalListaEjecucion .btn-outline-success").click(function(){ModalAccionEjecucion(unidadMeta, unidadDatoAdicional, cuantitativa);});
          }

          ActualizarAcceso();

          $('#modalListaEjecucion').modal();
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

function ModalAccionEjecucionAvance(idAccionEjecucion) {
  $.blockUI();

  $.ajax({
    type: "GET",
    url: URL_PATH + "/Proyecto/BuscarAccionEjecucion",
    data: {
      idProyecto : idProyectoActual,
      idObjetivo : idObjetivoActivo,
      idProducto : idProductoActivo,
      idActividad : idActividadActiva,
      idAccion : idAccionActiva,
      idAccionEjecucion : idAccionEjecucion
    },
    success: function(result){
      try{
        res = JSON.parse(result);
        if (res.estado == true) {
          console.log(res);
          idAccionEjecucionActiva = idAccionEjecucion;
          $('#tituloModalAcionEjecucion').html('Registrar Ejecución');
          $('#fechaInicio').val(res.ejecucion.fecha_inicio);
          $('#fechaFin').val(res.ejecucion.fecha_fin);
          $('#fechaEjecucion').val(res.ejecucion.fecha_ejecucion);
          $('#meta').val(res.ejecucion.meta);
          $('#unidadMeta').val(res.unidadMeta);
          $('#unidadAvance').val(res.unidadMeta);
          $('#avance').val(res.ejecucion.avance);
          $('#observacion').val(res.ejecucion.observacion);
          $('#datoAdicional').val(res.ejecucion.medida_adicional);
          $('#unidadDatoAdicional').val(res.unidadDatoAdicional);

          $('#fechaInicio').prop('readonly', true);
          $('#fechaFin').prop('readonly', true);
          $('#meta').prop('readonly', true);
        
          $('#fechaEjecucion').prop('readonly', false);
          $('#avance').prop('readonly', false);
          $('#observacion').prop('readonly', false);
          
          if (requiereDatoAdicional == 1) {
            $('#datoAdicional').prop('readonly', false);
          }
          else{
            $('#datoAdicional').prop('readonly', true);
          }

          $('#modalAccionEjecucion').modal();
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

function AnimarProgreso(){
  setTimeout(function() {
    $('svg.radial-progress').each(function( index, value ) { 
      // If svg.radial-progress is approximately 25% vertically into the window when scrolling from the top or the bottom
      if ( 
        $(window).scrollTop() > $(this).offset().top - ($(window).height() * 0.75) &&
        $(window).scrollTop() < $(this).offset().top + $(this).height() - ($(window).height() * 0.25)
      ) {
        // Get percentage of progress
        percent = $(this).attr('data-percentage');
        if (percent > 80) {
          $(this).find($('circle.complete')).css('stroke', 'green');
        }
        else if (percent > 50){
          $(this).find($('circle.complete')).css('stroke', 'yellow');
        }
        else {
          $(this).find($('circle.complete')).css('stroke', 'red');
        }
        radius = $(this).find($('circle.complete')).attr('r');
        // Get circumference (2πr)
        circumference = 2 * Math.PI * radius;
        // Get stroke-dashoffset value based on the percentage of the circumference
        strokeDashOffset = circumference - ((percent * circumference) / 100);
        $(this).find($('circle.complete')).css('stroke-dashoffset', circumference);
        // Transition progress for 1.25 seconds
        $(this).find($('circle.complete')).animate({'stroke-dashoffset': strokeDashOffset}, 1250);
      }
    });
  }, 500);
}

function ModalParticipante(idAccionEjecucion){
  $.blockUI();

  $.ajax({
    type: "GET",
    url: URL_PATH + "/Proyecto/ListarParticipante",
    data: {
      idProyecto : idProyectoActual,
      idObjetivo : idObjetivoActivo,
      idProducto : idProductoActivo,
      idActividad : idActividadActiva,
      idAccion : idAccionActiva,
      idAccionEjecucion : idAccionEjecucion
    },
    success: function(result){
      try{
        res = JSON.parse(result);
        if (res.estado == true) {
          idAccionEjecucionActiva = idAccionEjecucion;
          participacionFamiliar = res.participacionFamiliar;
          $('#listaParticipante').html(res.vista);

          $('#modalListaParticipante .btn-outline-success').unbind( "click" );
          $("#modalListaParticipante .btn-outline-success").click(function(){DibujarNuevoParticipante();});

          $('#modalListaParticipante').modal();

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

function ExportarExcelDatos(){
  $('#tablaParticipantesActivos tfoot').html('');
  //let accion=$('#accordionActividad .card .show').val();
  let titulo='Producto:'+$('#modalProductoDetallado_titulo').text()+' -> Accion: '+$('#tituloModalListaEjecucion').text();
  $('#tablaParticipantesActivos tfoot').append('<tr>'+
    '<td colspan=6>'+titulo+'</td>'+
  '</tr>');
  
  tableToExcel('tablaParticipantesActivos', 'Reporte','ReporteParticipantesCadep');
}

function DibujarNuevoParticipante(){
  $.blockUI();
  $.ajax({
    type: "GET",
    url: URL_PATH + "/Persona/ListarPersonaSimple",
    data: {
    },
    success: function(result){
      try{
        res = JSON.parse(result);
        if (res.estado == true) {
          listaPersona = res.personas;
          var filaParticipante = '<tr>'+
                                    '<td name="rol">'+
                                      '<select class="form-control form-control-sm">'+
                                        '<option value="PARTICIPANTE" selected>PARTICIPANTE</option>'+
                                        '<option value="RESPONSABLE">RESPONSABLE</option>'+
                                        '<option value="BENEFICIARIO">BENEFICIARIO</option>'+
                                      '</select>'+
                                    '</td>'+
                                    '<td name="listaPersona">'+
                                      '<select class="chosen-select" onchange="SeleccionarPersona(this);">'+
                                        '<option value="" disabled selected>Seleccionar DNI</option>'+
                                        '<option value="0">Nuevo</option>';
                                        for (var i = 0; i < res.personas.length; i++) {
                                          filaParticipante += '<option value="'+res.personas[i].id_persona+'">'+res.personas[i].dni+'</option>';
                                        }
              filaParticipante  +=    '</select>'+
                                    '</td>';
                                    if (participacionFamiliar == 0) {
              filaParticipante  +=    '<td name="genero"></td>'+
                                      '<td name="edad"></td>'+
                                      '<td name="nombre"></td>'+
                                      '<td name="paterno"></td>'+
                                      '<td name="materno"></td>';
                                    }
                                    else{
              filaParticipante  +=    '<td name="jefe"></td>';
                                    }
              filaParticipante  +=  '<td name="accion">'+
                                      '<button type="button" class="btn btn-sm btn-outline-success mr-2" onclick="RegistrarParticipante(this)" data-toggle="tooltip" data-placement="bottom" title="Registrar participante"><i class="fas fa-save"></i></button>'+
                                      '<button type="button" class="btn btn-sm btn-outline-danger mr-2" onclick="EliminarFila(this)" data-toggle="tooltip" data-placement="bottom" title="Eliminar participante"><i class="fas fa-minus-circle"></i></button>'+
                                    '</td>'+
                                  '</tr>';

          if ($('#tablaParticipante').find('h5').length){
            $('#tablaParticipante').html(filaParticipante);
          }
          else{
            $('#tablaParticipante').append(filaParticipante);
          }

          $('.chosen-select').chosen();
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

function EliminarFila(btn){
  $(btn).parent().parent().remove();
}

function SeleccionarPersona(select){
  filaParticipanteActiva = $(select).parent().parent();
  var seleccionado = $(select).find('option:selected').val();
  if (seleccionado == 0) {
    ModalPersonaNueva();
  }
  else if (seleccionado > 0) {
    var personaSeleccionada = SeleccionarPersonaPorId(seleccionado);
    if (participacionFamiliar == 0) {console.log(personaSeleccionada);
      $(filaParticipanteActiva).find('[name="genero"]').html(personaSeleccionada.genero==1 ? 'MASCULINO' : 'FEMENINO' );
      $(filaParticipanteActiva).find('[name="edad"]').html(calcularEdad(personaSeleccionada.fecha_nacimiento));
      $(filaParticipanteActiva).find('[name="nombre"]').html(personaSeleccionada.nombre);
      $(filaParticipanteActiva).find('[name="paterno"]').html(personaSeleccionada.paterno);
      $(filaParticipanteActiva).find('[name="materno"]').html(personaSeleccionada.materno);
      $(filaParticipanteActiva).find('[name="accion"]').html('<button type="button" class="btn btn-sm btn-outline-success mr-2" onclick="RegistrarParticipante(this)" data-toggle="tooltip" data-placement="bottom" title="Registrar participante"><i class="fas fa-save"></i></button>'+
                                                          '<button type="button" class="btn btn-sm btn-outline-danger" onclick="EliminarFila(this)" data-toggle="tooltip" data-placement="bottom" title="Eliminar participante"><i class="fas fa-minus-circle"></i></button>'+
                                                          '<button type="button" class="btn btn-sm btn-outline-warning float-left mr-2" onclick="ModalPersonaModificar(this,'+seleccionado+')" data-toggle="tooltip" data-placement="bottom" title="Modificar Persona"><i class="far fa-edit"></i></button>');
    }
    else{
      $(filaParticipanteActiva).find('[name="jefe"]').html(personaSeleccionada.jefe);
      $(filaParticipanteActiva).find('[name="accion"]').html('<button type="button" class="btn btn-sm btn-outline-success mr-2" onclick="RegistrarParticipante(this)" data-toggle="tooltip" data-placement="bottom" title="Registrar participante"><i class="fas fa-save"></i></button>'+
                                                          '<button type="button" class="btn btn-sm btn-outline-danger" onclick="EliminarFila(this)" data-toggle="tooltip" data-placement="bottom" title="Eliminar participante"><i class="fas fa-minus-circle"></i></button>');
    }
  }
}

function RegistrarParticipante(btn){
  var fila = $(btn).parent().parent();
  $.blockUI();
  var participante = new Object();
  participante.idPersona = $(fila).find('[name="listaPersona"] select').val();
  participante.rol = $(fila).find('[name="rol"] select').val();

  if (participante.idPersona > 0) {
    $.ajax({
      type: "POST",
      url: URL_PATH + "/Proyecto/RegistrarParticipante",
        data: {
          idProyecto : idProyectoActual,
          idObjetivo : idObjetivoActivo,
          idProducto : idProductoActivo,
          idActividad : idActividadActiva,
          idAccion : idAccionActiva,
          idAccionEjecucion : idAccionEjecucionActiva,
          participante : participante,
        },
        success: function(result){
          try{
            res = JSON.parse(result);
            if (res.estado == true) {
              $(fila).find('[name="rol"]').html(participante.rol);
              $(fila).find('[name="listaPersona"]').html($(fila).find('[name="listaPersona"] select option:selected').text());
              $(fila).find('[name="accion"]').html('<button type="button" class="btn btn-sm btn-outline-danger" onclick="ModalParticipanteEliminar(this, '+res.idParticipante+')" data-toggle="tooltip" data-placement="bottom" title="Eliminar participante"><i class="fas fa-trash-alt"></i></button>'+
                                                    '<button type="button" class="btn btn-sm btn-outline-warning float-left mr-2" onclick="ModalPersonaModificar(this,'+res.idParticipante+')" data-toggle="tooltip" data-placement="bottom" title="Modificar Persona"><i class="far fa-edit"></i></button>');              
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
  else{
    MostrarConfirmacion('Error de usuario', 'Debe selecionar una persona mediante su DNI.', null, null);
    $.unblockUI();
  }
}

function ModalActualizarParticipanteRol(idParticipante){ 
  MostrarConfirmacion(null,'¿Esta seguro que desea modificar el rol de este participante?', 'ModficicarParticianteRol('+idParticipante+')', null);
}

function ModficicarParticianteRol(idParticipante){
  $.blockUI();
  var participante = new Object();
  participante.rol = $('#rol_'+idParticipante).find('option:selected').val();
  participante.idAccionEjecucionParticipante = idParticipante;

  $.ajax({
    type: "POST",
    url: URL_PATH + "/Proyecto/ModficicarParticianteRol",
      data: {
        idProyecto : idProyectoActual,
        idObjetivo : idObjetivoActivo,
        idProducto : idProductoActivo,
        idActividad : idActividadActiva,
        idAccion : idAccionActiva,
        idAccionEjecucion : idAccionEjecucionActiva,
        participante : participante,
      },
      success: function(result){
        try{
          res = JSON.parse(result);
          if (res.estado == true) {
            
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

function ModalParticipanteEliminar(btn, idParticipante){ 
  filaParticipanteActiva = $(btn).parent().parent();
  MostrarConfirmacion(null,'¿Esta seguro que desea eliminar este participante?', 'EliminarParticipante('+idParticipante+')', null);
}

function EliminarParticipante(idParticipante){
  $.blockUI();
  $.ajax({
    type: "POST",
    url: URL_PATH + "/Proyecto/EliminarParticipante",
      data: {
        idProyecto : idProyectoActual,
        idObjetivo : idObjetivoActivo,
        idProducto : idProductoActivo,
        idActividad : idActividadActiva,
        idAccion : idAccionActiva,
        idAccionEjecucion : idAccionEjecucionActiva,
        idAccionEjecucionParticipante : idParticipante,
      },
      success: function(result){
        try{
          res = JSON.parse(result);
          if (res.estado == true) {
            $(filaParticipanteActiva).remove();
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

function ModalPersonaNueva(){
  $('#modalPersona_titulo').html('Nueva Persona');
  $('#modalPersona_dni').val('');
  $('#modalPersona_nombre').val('');
  $('#modalPersona_paterno').val('');
  $('#modalPersona_materno').val('');
  $('#modalPersona_genero').val(0);
  $("#modalPersona_idComunidad option[value='99999']").prop("selected", true);
  $('#modalPersona_fechaNacimiento').val('');
  $('#modalPersona_guardar').html('Registrar');
  $('#modalPersona').modal();
}

function ModalPersonaModificar(btn, idPersona){
  $.blockUI();

  filaParticipanteActiva = $(btn).parent().parent();
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
          $('#modalPersona_dni').val(res.persona.dni);
          $('#modalPersona_nombre').val(res.persona.nombre);
          $('#modalPersona_paterno').val(res.persona.paterno);
          $('#modalPersona_materno').val(res.persona.materno);
          $('#modalPersona_genero').val(res.persona.genero);
          $('#modalPersona_fechaNacimiento').val(res.persona.fecha_nacimiento);
		  $("#modalPersona_idComunidad option[value='"+res.persona.id_comunidad+"']").prop("selected", true);
          $('#modalPersona_guardar').html('Modificar');
          $('#modalPersona').modal();
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

function RegistrarPersona(){
  $.blockUI();
  var persona = new Object();
  persona.dni = $('#modalPersona_dni').val();
  persona.tipoDocumento = $('#modalPersona_tipo option:selected').val();
  persona.nombre = $('#modalPersona_nombre').val();
  persona.paterno = $('#modalPersona_paterno').val();
  persona.materno = $('#modalPersona_materno').val();
  persona.genero = $('#modalPersona_genero option:selected').val();
  persona.fechaNacimiento = $('#modalPersona_fechaNacimiento').val();
  persona.idComunidad = $('#modalPersona_idComunidad option:selected').val();
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
              listaPersona = res.personas;
              DibujarListaPersona(res.idPersona);
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
              listaPersona = res.personas;
              $(filaParticipanteActiva).find('[name="nombre"]').html(persona.nombre);
              $(filaParticipanteActiva).find('[name="paterno"]').html(persona.paterno);
              $(filaParticipanteActiva).find('[name="materno"]').html(persona.materno);
              $(filaParticipanteActiva).find('[name="dni"]').html(persona.dni);
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

function DibujarListaPersona(idPersona){
  var personaSelect =  '<option value="" disabled>Seleccionar DNI</option>'+
                      '<option value="0">Nuevo</option>';
                      for (var i = 0; i < listaPersona.length; i++) {
                        personaSelect += '<option value="'+listaPersona[i].id_persona+'">'+listaPersona[i].dni+'</option>';
                      }
                      
  $(filaParticipanteActiva).find('[name="listaPersona"] select').html(personaSelect);
  $(filaParticipanteActiva).find('[name="listaPersona"] select').val(idPersona);
  $(filaParticipanteActiva).find('[name="listaPersona"] select').trigger("chosen:updated");
  SeleccionarPersona($(filaParticipanteActiva).find('[name="listaPersona"] select'));
}

function SeleccionarPersonaPorId(idPersona){
  for (var i = 0; i < listaPersona.length; i++) {
    if (listaPersona[i].id_persona == idPersona) {
      return listaPersona[i];
    }
  }
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
                $('#modalPersona_dni').val(res.persona.dni);
                $('#modalPersona_nombre').val(res.persona.nombre);
                $('#modalPersona_paterno').val(res.persona.paterno);
                $('#modalPersona_materno').val(res.persona.materno);
                $('#modalPersona_genero').val(res.persona.genero);
                $('#modalPersona_fechaNacimiento').val(res.persona.fecha_nacimiento);
              }
              else{
                $('#modalPersona_dni').val(res.persona.dni);
                $('#modalPersona_nombre').val(res.persona.nombre);
                $('#modalPersona_paterno').val(res.persona.paterno);
                $('#modalPersona_materno').val(res.persona.materno);
                $('#modalPersona_genero').val(0);
                $('#modalPersona_fechaNacimiento').val('');
              }
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

function ModalFoto(idAccionEjecucion){
  $.blockUI();
  $.ajax({
    type: "GET",
    url: URL_PATH + "/Proyecto/ListarFotoEjecucion",
    data: {
      idProyecto : idProyectoActual,
      idObjetivo : idObjetivoActivo,
      idProducto : idProductoActivo,
      idActividad : idActividadActiva,
      idAccion : idAccionActiva,
      idAccionEjecucion : idAccionEjecucion
    },
    success: function(result){
      try{
        res = JSON.parse(result);
        if (res.estado == true) {
          idAccionEjecucionActiva = idAccionEjecucion;

          $('label[for="foto"]').html('Seleccione foto de referencia');
          $('#listaFoto').html('');
          for (var i = 0; i < res.fotos.length; i++) {
            DibujarFoto(idAccionEjecucionActiva+'_'+res.fotos[i].id_accion_ejecucion_foto);
          }

          $('#modalFoto').modal();
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

function GuardarFoto(foto){
  $.blockUI();
  $.ajax({
    type: "POST",
    url: URL_PATH + "/Proyecto/RegistrarFoto",
    data: {
      idProyecto : idProyectoActual,
      idObjetivo : idObjetivoActivo,
      idProducto : idProductoActivo,
      idActividad : idActividadActiva,
      idAccion : idAccionActiva,
      idAccionEjecucion : idAccionEjecucionActiva,
      foto : foto
    },
    success: function(result){
      try{
        res = JSON.parse(result);
        if (res.estado == true) {
          DibujarFoto(idAccionEjecucionActiva+'_'+res.idFoto);
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

function DibujarFoto(nombre){
  $('#listaFoto').append('<div id="'+nombre+'" class="col-12 col-md-6 col-lg-4 col-xl-3">'+
                  '<img class="img-fluid foto-habitacion" onclick="ModalFotoEliminar(\''+nombre+'\')" src=URL_PATH + "/img/proyecto_'+idProyectoActual+'/'+nombre+'.png">'+
                '</div>');
}

function ModalFotoEliminar(nombre){ 
  MostrarConfirmacion(null,'¿Esta seguro que desea eliminar esta foto?', 'EliminarFoto(\''+nombre+'\')', null);
}

function EliminarFoto(nombre){
  $.blockUI();
  $.ajax({
    type: "POST",
    url: URL_PATH + "/Proyecto/EliminarFoto",
      data: {
        idProyecto : idProyectoActual,
        idObjetivo : idObjetivoActivo,
        idProducto : idProductoActivo,
        idActividad : idActividadActiva,
        idAccion : idAccionActiva,
        idAccionEjecucion : idAccionEjecucionActiva,
        foto : nombre,
      },
      success: function(result){
        try{
          res = JSON.parse(result);
          if (res.estado == true) {
            $('#'+nombre).remove();
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