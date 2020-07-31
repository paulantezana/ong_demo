<div class="MainContainer">
  <div class="input-group mb-3">
    <div class="input-group-prepend">
      <label class="input-group-text" for="listaProyecto">Proyecto:</label>
    </div>
    <select class="custom-select" id="listaProyecto">
      <option selected disabled value="0">Seleccione un proyecto</option>
      <?php foreach ($parametro[0] as $key => $proyecto): ?>
        <option value="<?php echo $proyecto['id_proyecto']; ?>"><?php echo $proyecto['nombre']; ?></option>
      <?php endforeach ?>
    </select>
    <div class="input-group-append">
      <button type="button" class="btn btn-primary" onclick="tableToExcel('tablaReporte', 'Reporte')"><i class="far fa-file-excel"></i></button>
    </div>
  </div>

  <div>
    <label for="basic-url">Seleccione periodo de busqueda :</label>
    <div class="input-group mb-3">
      <input id="fechaIni" type="text" class="form-control" placeholder="Fecha Inicio" onkeydown="return false">
      <input id="fechaFin" type="text" class="form-control" placeholder="Fecha Fin" onkeydown="return false">
      <div class="input-group-append">
        <button type="button" class="btn btn-primary" onclick="BuscarReporte();"><i class="fas fa-search mr-2">Buscar</i></button>
      </div>
    </div>
  </div>
  <div id="divAvance">
  </div>
</div>

<script type="text/javascript" src="<?= URL_PATH ?>/js/ReporteActividad.js"></script>