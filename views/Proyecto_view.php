<div class="MainContainer">
    <div class="row">
      <div class="col-md-3">
        <div class="Proyect">
          <div class="Proyect-img">
            <img src="<?= URL_PATH ?>/img/foto_<?php echo $parametro[0]['id_proyecto'] ?>.png" class="img-fluid mb-3" id="fotoSelecionada">
          </div>
          <div class="Proyect-progress">
            <svg class="radial-progress" data-percentage="<?php echo $parametro[0]['avance']; ?>" viewBox="0 0 80 80" id="avanceProyecto">
              <circle class="incomplete" cx="40" cy="40" r="35"></circle>
              <circle class="complete" cx="40" cy="40" r="35" style="stroke-dashoffset: 219.584067;"></circle>
              <text class="percentage" x="50%" y="57%" transform="matrix(0, 1, -1, 0, 80, 0)"><?php echo $parametro[0]['avance']; ?>%</text>
            </svg>
          </div>
          <div class="Proyect-content">
            <h3 class="Proyect-title"><?php echo $parametro[0]['nombre']; ?></h3>
            <p class="Proyect-description"><?php echo $parametro[0]['descripcion']; ?></p>
            <p class="mb-1"><strong><i class="far fa-calendar-alt"></i> FECHA INICIO :</strong> <?php echo $parametro[0]['fecha_inicio']; ?></p>
            <p class="mb-1"><strong><i class="far fa-calendar-alt"></i> FECHA FIN :</strong> <?php echo $parametro[0]['fecha_fin']; ?></p>
            <!-- <p><strong>ESTADO :</strong> <?php echo $parametro[0]['estado']; ?></p> -->
          </div>
          <div class="Proyect-footer">
            <button type="button" class="btn btn-sm btn-outline-danger d-none" onclick="ModalProyectoEliminar();" data-toggle="tooltip" data-placement="bottom" title="Eliminar Proyecto" name="opcionProyectoEliminar"><i class="fas fa-trash-alt"></i> Eliminar Proyecto</button>
          </div>
        </div>
      </div>
      <div class="col-md-9">
        <div class="ProDeObjetive">
          <div class="ProDeObjetive-header">
            <div>
              <div><strong><i class="fas fa-list-ul mr-2"></i>OBJETIVOS</strong></div>
              <small id="mensajePonderacionObjetivos" class="text-danger" style="display: none;"><strong>ALERTA :</strong> Debe actualizar la ponderación.</small>
            </div>
            <div>
              <button type="button" class="btn btn-primary float-right d-none" onclick="ModalPonderarObjetivo()" name="opcionObjetivoPonderar"><i class="fas fa-list-ol mr-2"></i> Ponderar</button>
              <button type="button" class="btn btn-success float-right mr-2 d-none" onclick="ModalObjetivoNuevo()" name="opcionObjetivoNuevo"><i class="fas fa-plus-circle mr-2"></i> Nuevo</button>
            </div>
          </div>
          <div class="ProDeObjetive-body">
            <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel" data-interval="false">
              <ol class="carousel-indicators" id="carouselIndicadores"></ol>
              <div class="carousel-inner" id="carouselObjetivos"></div>
              <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                <i class="fas fa-angle-left"></i>
                <span class="sr-only">Previous</span>
              </a>
              <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                <i class="fas fa-angle-right"></i>
                <span class="sr-only">Next</span>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>

<script type="text/javascript">
  var idProyectoActual = JSON.parse('<?php echo json_encode($parametro[0]['id_proyecto']); ?>');
  var objetivos = JSON.parse('<?php echo json_encode($parametro[1]); ?>');
  var idObjetivoBusqueda = JSON.parse('<?php echo json_encode($parametro[2]); ?>');
  var idProductoBusqueda = JSON.parse('<?php echo json_encode($parametro[3]); ?>');
</script>
<script type="text/javascript" src="<?= URL_PATH ?>/js/Proyecto.js"></script>

<div class="modal fade" id="modalObjetivo" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form class="was-validated" id="formObjetivo" onsubmit="RegistrarObjetivo();">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalObjetivo_titulo"></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group d-none" id="modalObjetivo_grupoCodigo">
            <label for="modalObjetivo_codigo" class="col-form-label">Código:</label>
            <input type="text" class="form-control" id="modalObjetivo_codigo" placeholder="Ingrese el codigo de acceso rapido">
          </div>
          <div class="form-group">
            <label for="modalObjetivo_nombre" class="col-form-label">Nombre:</label>
            <input type="text" class="form-control" id="modalObjetivo_nombre" required>
          </div>
          <div class="form-group">
            <label for="modalObjetivo_descripcion" class="col-form-label">Descripción:</label>
            <textarea class="form-control" id="modalObjetivo_descripcion" required></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary" id="modalObjetivo_guardar">Registrar</button>
        </div>
      </div>
    </form>
  </div>
</div>

<div class="modal fade" id="modalProductoDetallado" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalProductoDetallado_titulo">
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-12 col-md-9 col-lg-10">
            <p class="text-justify" id="modalProductoDetallado_descripcion"></p>
          </div>
          <div class="col-12 col-md-3 col-lg-2">
            <div class="contenedor-avance">
              <svg class="radial-progress" data-percentage="" viewBox="0 0 80 80" id="avanceProducto">
                <circle class="incomplete" cx="40" cy="40" r="35"></circle>
                <circle class="complete" cx="40" cy="40" r="35" style="stroke-dashoffset: 219.584067;"></circle>
                <text class="percentage" x="50%" y="57%" transform="matrix(0, 1, -1, 0, 80, 0)">0%</text>
              </svg>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-12">
            <div class="card mt-3">
              <div class="card-header d-flex align-items-center justify-content-between" style="background: var(--skColorDarkSmooth); color: var(--skColorDarkInverse);">
                <strong class="mb-0">ACTIVIDADES</strong>
                <div>
                  <button name="opcionActividadPonderar" type="button" class="btn btn-sm btn-warning float-right d-none" id="botonPonderarActividad" onclick="ModalPonderarActividad()"><i class="fas fa-list-ol"></i> Ponderar</button>
                  <button name="opcionActividadNuevo" type="button" class="btn btn-sm btn-success float-right mr-2 d-none" id="botonNuevaActividad"><i class="far fa-file"></i> Nueva</button>
                </div>
              </div>
              <div class="accordion" id="accordionActividad">

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalAccion" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form class="was-validated" id="formAccion" onsubmit="RegistrarAccion();">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalAccion_titulo">Nueva acción</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="modalAccion_nombre" class="col-form-label">Nombre:</label>
            <input type="text" class="form-control" id="modalAccion_nombre" required>
          </div>
          <div class="form-group">
            <label for="modalAccion_descripcion" class="col-form-label">Descripción:</label>
            <textarea class="form-control" id="modalAccion_descripcion" required></textarea>
          </div>
          <div class="form-group">
            <label for="modalAccion_ubicacion" class="col-form-label">Ubicación:</label>
            <input type="text" class="form-control" id="modalAccion_ubicacion">
          </div>
          <div class="form-group">
            <label for="modalAccion_comunidad" class="col-form-label">Comunidad:</label>
            <select id="modalAccion_comunidad" class="chosen-select" required>
              <option value="0" selected disabled>Seleccione comunidad</option>
              <?php foreach ($parametro[4] as $comunidad) : ?>
                <option value="<?= $comunidad['id_comunidad'] ?>"><?= $comunidad['nombre']; ?></option>
              <?php endforeach ?>
            </select>
          </div>
          <div class="form-group">
            <label for="modalAccion_meta" class="col-form-label">Meta:</label>
            <input type="text" class="form-control" id="modalAccion_meta" required>
          </div>
          <div class="form-group">
            <div class="custom-control custom-checkbox">
              <input type="checkbox" class="custom-control-input" id="esCuantitativa" onchange="HabilitarCantidad()">
              <label class="custom-control-label" for="esCuantitativa">La meta es cuantitativa.</label>
            </div>
            <div class="input-group">
              <input type="number" step="0.01" min="0.01" class="form-control" id="modalAccion_metaCuantitativa" placeholder="Cantidad" required readonly>
              <input type="text" class="form-control" id="modalAccion_unidadMeta" placeholder="Unidad Ej: Metros, Litros/s" required readonly>
            </div>
          </div>
          <div class="form-group">
            <div class="custom-control custom-checkbox">
              <input type="checkbox" class="custom-control-input" id="requiereDatoAdicional" onchange="HabilitarUnidadMedidaAdicional()">
              <label class="custom-control-label" for="requiereDatoAdicional">Requiere dato de medida adicional, este dato es usado para realizar graficas o reportes en base a este dato.</label>
            </div>
            <input type="text" class="form-control" id="modalAccion_unidadMedidaAdicional" placeholder="Unidad Ej: Metros, Litros/s" required readonly>
          </div>
          <div class="form-group">
            <div class="custom-control custom-checkbox">
              <input type="checkbox" class="custom-control-input" id="participacionFamiliar">
              <label class="custom-control-label" for="participacionFamiliar">Participación por familias.</label>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary" id="modalAccion_guardar">Registrar</button>
        </div>
      </div>
    </form>
  </div>
</div>

<div class="modal fade" id="modalPonderacion" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form class="was-validated" id="formPonderacion" onsubmit="RegistrarPonderacion();">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="tiutloModalPonderacion"></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" id="listaPonderacion">

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Registrar</button>
        </div>
      </div>
    </form>
  </div>
</div>

<div class="modal fade" id="modalAccionEjecucion" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form class="was-validated" id="formAccionEjecucion" onsubmit="ProgramarAccionEjecucion();">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="tituloModalAcionEjecucion"></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" id="listaPonderacion">
          <div class="form-group">
            <label for="fechaInicio"><i class="far fa-calendar-alt"></i> Fecha Inicio</label>
            <input type="date" class="form-control datepicker" id="fechaInicio" placeholder="Seleccione fecha inicio" required>
          </div>
          <div class="form-group">
            <label for="fechaFin"><i class="far fa-calendar-alt"></i> Fecha Fin</label>
            <input type="date" class="form-control datepicker" id="fechaFin" placeholder="Seleccione fecha fin" required>
          </div>
          <div class="form-group">
            <label for="meta"><i class="fas fa-chart-line"></i> Meta</label>
            <div class="input-group">
              <input type="number" min="0.01" step="0.01" class="form-control" id="meta" placeholder="Ingrese meta propuesta" required>
              <input type="text" class="form-control" id="unidadMeta" required readonly>
            </div>
          </div>
          <div class="form-group">
            <label for="fechaEjecucion"><i class="far fa-calendar-alt"></i> Fecha Ejecución</label>
            <input type="date" class="form-control datepicker" id="fechaEjecucion" placeholder="Seleccione fecha Ejecución">
          </div>
          <div class="form-group">
            <label for="avance"><i class="fas fa-chart-bar"></i> Avance</label>
            <div class="input-group">
              <input type="number" min="0.01" step="0.01" class="form-control" id="avance" placeholder="Ingrese avance logrado" required>
              <input type="text" class="form-control" id="unidadAvance" required readonly>
            </div>
          </div>
          <div class="form-group">
            <label for="observacion"><i class="far fa-comment"></i> Observación</label>
            <textarea type="text" class="form-control" id="observacion" placeholder="Ingrese una observación en caso de ser necesario."></textarea>
          </div>
          <div class="form-group">
            <label for="datoAdicional"><i class="fas fa-chart-line"></i> Dato adicional de medida</label>
            <div class="input-group">
              <input type="number" min="0.01" step="0.01" class="form-control" id="datoAdicional" placeholder="Ingrese medida" required readonly="false">
              <input type="text" class="form-control" id="unidadDatoAdicional" required readonly>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Registrar Ejecución</button>
        </div>
      </div>
    </form>
  </div>
</div>

<div class="modal fade" id="modalListaEjecucion" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="tituloModalListaEjecucion"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="listaEjecucion">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cerrar ventana</button>
        <button name="opcionEjecucionNuevo" type="button" class="btn btn-outline-success d-none"><i class="fas fa-plus-circle"></i> Agregar Nuevo</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalListaParticipante" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Participantes</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="listaParticipante">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn" onclick="ExportarExcelDatos()"><i class="far fa-file-excel"></i></button>
        <button type="button" class="btn btn-light" data-dismiss="modal">Cerrar ventana</button>
        <button type="button" class="btn btn-primary"><i class="fas fa-plus-circle"></i> Agregar Nuevo</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalPersona" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form class="was-validated" id="formPersona" onsubmit="RegistrarPersona();">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalPersona_titulo">Nueva Persona</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-12">
              <div class="form-group">
                <label for="modalPersona_idComunidad" class="col-form-label">Comunidad:</label>
                <select id="modalPersona_idComunidad" class="form-control">
                  <option value="0" selected>Seleccione comunidad</option>
                  <?php foreach ($parametro[4] as $comunidad) : ?>
                    <option value="<?= $comunidad['id_comunidad'] ?>"><?= $comunidad['nombre']; ?></option>
                  <?php endforeach ?>
                </select>
              </div>
            </div>
            <div class="col-4">
              <div class="form-group">
                <label for="modalPersona_tipo" class="col-form-label">TIPO:</label>
                <select id="modalPersona_tipo" class="form-control" onchange="CambiarTextoInput()">
                  <option>DNI</option>
                  <option>CODIGO</option>
                </select>
              </div>
            </div>
            <div class="col-8">
              <div class="form-group">
                <label for="modalPersona_dni" class="col-form-label">DNI:</label>
                <input type="text" class="form-control" id="modalPersona_dni" required onfocusout="BuscarPersonaPorDNI()">
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <label for="modalPersona_nombre" class="col-form-label">Nombre(s):</label>
                <input type="text" class="form-control" id="modalPersona_nombre" required>
              </div>
            </div>
            <div class="col-12 col-md-6">
              <div class="form-group">
                <label for="modalPersona_paterno" class="col-form-label">Ap. Paterno:</label>
                <input type="text" class="form-control" id="modalPersona_paterno" required>
              </div>
            </div>
            <div class="col-12 col-md-6">
              <div class="form-group">
                <label for="modalPersona_materno" class="col-form-label">Ap. Materno:</label>
                <input type="text" class="form-control" id="modalPersona_materno" required>
              </div>
            </div>
            <div class="col-12 col-md-6">
              <div class="form-group">
                <label for="modalPersona_fechaNacimiento" class="col-form-label"><i class="far fa-calendar-alt"></i> Fec. Nac.</label>
                <input type="date" class="form-control datepicker" id="modalPersona_fechaNacimiento" required>
              </div>
            </div>
            <div class="col-12 col-md-6">
              <div class="form-group">
                <label for="modalPersona_genero" class="col-form-label">Genero:</label>
                <select class="form-control" id="modalPersona_genero">
                  <option value="0">FEMENINO</option>
                  <option value="1">MASCULINO</option>
                </select>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary" id="modalPersona_guardar">Registrar</button>
        </div>
      </div>
    </form>
  </div>
</div>

<div class="modal fade" id="modalFoto" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Fotos</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="custom-file mb-3">
          <input type="file" class="custom-file-input" id="foto" aria-describedby="inputGroupFileAddon01">
          <label class="custom-file-label" for="foto">Seleccione foto de referencia</label>
        </div>
        <div class="row" id="listaFoto">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">Aceptar</button>
      </div>
    </div>
  </div>
</div>