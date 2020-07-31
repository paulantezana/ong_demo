<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#" onclick="AvanceProyecto(<?php echo $parametro[0]; ?>)"><?php echo $parametro[1]; ?></a></li>
    <li class="breadcrumb-item"><a href="#" onclick="AvanceObjetivo(<?php echo $parametro[2]; ?>)"><?php echo $parametro[3]; ?></a></li>
    <li class="breadcrumb-item"><a href="#" onclick="AvanceProducto(<?php echo $parametro[4]; ?>)"><?php echo $parametro[5]; ?></a></li>
    <li class="breadcrumb-item active" aria-current="page"><?php echo $parametro[7]; ?></li>
  </ol>
</nav>

<div>
  <label for="basic-url">Seleccione periodo de busqueda :</label>
  <div class="input-group mb-3">
    <input id="fechaIni" type="text" class="form-control" placeholder="Fecha Inicio" onkeydown="return false">
    <input id="fechaFin" type="text" class="form-control" placeholder="Fecha Fin" onkeydown="return false">
    <div class="input-group-append">
      <button type="button" class="btn btn-primary" onclick="AvanceActividad();"><i class="fas fa-search mr-2"> Buscar</i></button>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-12 col-md-6" id="divAvanceDetalle">
  
  </div>
  <div class="col-12 col-md-6" id="divAvanceEjecucion">
    
  </div>  
</div>


<script type="text/javascript">
  $(document).ready(function() {
    $('#fechaIni').datepicker({
    format: "yyyy-mm-dd",
    });

    $('#fechaFin').datepicker({
      format: "yyyy-mm-dd",
    });

  });
</script>