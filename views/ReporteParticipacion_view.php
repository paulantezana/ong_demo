<div class="MainContainer">
  <div class="input-group mb-3">
    <div class="input-group-prepend">
      <label class="input-group-text" for="listaProyecto">Proyecto:</label>
    </div>
    <select class="custom-select" id="listaProyecto" onchange="BuscarParticipanteComunidad()">
      <option selected disabled value="0">Seleccione un proyecto</option>
      <?php foreach ($parametro[0] as $key => $proyecto): ?>
        <option value="<?php echo $proyecto['id_proyecto']; ?>"><?php echo $proyecto['nombre']; ?></option>
      <?php endforeach ?>
    </select>
    <div class="input-group-append">
      <button type="button" class="btn btn-primary" onclick="tableToExcel('tablaReporte', 'Reporte')"><i class="far fa-file-excel"></i></button>
    </div>
  </div>

  <div id="opcionesBusqueda" class="d-none row mb-2">
    <div class="col-12 col-md-6 col-lg-3">
      <div class="input-group input-group-sm">
        <div class="input-group-prepend">
          <label class="input-group-text" for="tipoParticipante">Tipo participante :</label>
        </div>
        <select class="custom-select" id="tipoParticipante" onchange="CambiarTipoParticipante()">
          <option selected value="PERSONA">Personas</option>
          <option value="FAMILIA">Familias</option>
          <option value="COMUNIDAD">Comunidades</option>
        </select>
      </div>
    </div>
    <div class="col-12 col-md-6 col-lg-3">
      <div class="input-group">
        <div class="input-group-prepend">
          <div class="input-group-text">
            <input type="radio" id="radioNombre" name="customRadio" onchange="CambiarSeleccion()">
          </div>
        </div>
        <select id="nombre" class="chosen-select">
        </select>
        <select id="comunidad" class="chosen-select">
        </select>
      </div>
    </div>
    <div class="col-12 col-md-6 col-lg-3">
      <div class="input-group">
        <div class="input-group-prepend">
          <div class="input-group-text">
            <input type="radio" id="radioDni" name="customRadio" onchange="CambiarSeleccion()">
          </div>
        </div>
        <select id="dni" class="chosen-select">
        </select>
      </div>
    </div>
    <div class="col-12 col-md-6 col-lg-3">
      <button type="button" class="btn btn-primary" onclick="BuscarReporte();"><i class="fas fa-search mr-2"> Buscar</i></button>
    </div>
  </div>
    
  <div id="divAvance">
  </div>
</div>

<script type="text/javascript" src="<?= URL_PATH ?>/js/ReporteParticipacion.js"></script>