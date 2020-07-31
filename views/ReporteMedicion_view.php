<div class="MainContainer">
  <div class="input-group mb-3">
    <div class="input-group-prepend">
      <label class="input-group-text" for="listaProyecto">Proyecto:</label>
    </div>
    <select class="custom-select" id="listaProyecto" onchange="BuscarAccion()">
      <option selected disabled value="0">Seleccione un proyecto</option>
      <?php foreach ($parametro[0] as $key => $proyecto): ?>
        <option value="<?php echo $proyecto['id_proyecto']; ?>"><?php echo $proyecto['nombre']; ?></option>
      <?php endforeach ?>
    </select>
    <div class="input-group-append">
      <button type="button" class="btn btn-primary" onclick="tableToExcel('tablaReporte', 'Reporte')"><i class="far fa-file-excel"></i></button>
    </div>
  </div>
  <div id="opcionesBusqueda" class="d-none row mb-3">
    <div class="col-12 col-md-6 col-lg-3">
      <div class="input-group input-group-sm">
        <div class="input-group-prepend">
          <label class="input-group-text" for="accion">Acción :</label>
        </div>
        <select id="accion" class="chosen-select">
        </select>
      </div>
    </div>
    <div class="col-12 col-md-6 col-lg-3">
      <button type="button" class="btn btn-primary" onclick="BuscarReporte();"><i class="fas fa-search"> Buscar</i></button>
    </div>
  </div>
    
  <div id="divAvance">
  </div>
</div>

<script type="text/javascript" src="<?= URL_PATH ?>/js/ReporteMedicion.js"></script>