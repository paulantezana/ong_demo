<div class="MainContainer">
  <div class="row mb-3">
    <div class="col-12 col-md-6 col-lg-5">
      <div class="input-group">
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
    </div>
    <div class="col-12 col-md-6 col-lg-5">
      <div class="input-group">
        <div class="input-group-prepend">
          <label class="input-group-text" for="anio">Año :</label>
        </div>
        <input id="anio" type="text" class="form-control" placeholder="Año de busqueda" onkeydown="return false">
      </div>
    </div>
    <div class="col-12 col-md-6 col-lg-2">
      <button type="button" class="btn btn-primary" onclick="BuscarReporte();"><i class="fas fa-search"> Buscar</i></button>
    </div>
  </div>
    
  <div id="divAvance">
  </div>
</div>

<script type="text/javascript" src="<?= URL_PATH ?>/js/ReporteAvanceAnualActividad.js"></script>