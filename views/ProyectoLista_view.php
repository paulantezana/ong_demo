<div class="MainContainer">
  <div>
    <h3 class="mt-3 text-center">Proyectos registrados</h3>
  </div>
  <div>
    <form action="<?= URL_PATH ?>/ProyectoLista/BuscarProyectoActividad" method="get">
      <div class="input-group mb-4">
        <div class="input-group-prepend">
          <span class="input-group-text" id="inputGroup-sizing-sm">BUSCAR</span>
        </div>
        <input name="codigoActividad" type="text" class="form-control <?php if(isset($parametro[1])){echo 'is-invalid';} ?>" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" placeholder="Ingrese codigo de actividad" required>
        <div class="input-group-append">
          <button class="btn btn-primary" type="submit" id="button-addon2"><i class="fas fa-search"></i></button>
        </div>
        <div class="invalid-feedback">
          <?php if(isset($parametro[1])){echo $parametro[1];} ?>
        </div>
      </div>
    </form>
  </div>
  <div class="row">
    <?php 
      foreach ($parametro[0] as $key => $proyecto) {
        echo '<div class="col-12 col-md-4 col-lg-3">
                <div class="Proyect">
                  <div class="Proyect-img">
                    <img src="' . URL_PATH . '/img/foto_'.$proyecto['id_proyecto'].'.png">
                  </div>
                  <div class="Proyect-progress">
                    <svg class="radial-progress" data-percentage="'.$proyecto['avance'].'" viewBox="0 0 80 80">
                      <circle class="incomplete" cx="40" cy="40" r="35"></circle>
                      <circle class="complete" cx="40" cy="40" r="35" style="stroke-dashoffset: 39.58406743523136;"></circle>
                      <text class="percentage" x="50%" y="57%" transform="matrix(0, 1, -1, 0, 80, 0)">'.$proyecto['avance'].'%</text>
                    </svg>
                  </div>
                  <div class="Proyect-content">
                    <h5 class="Proyect-title">'.$proyecto['nombre'].'</h5>
                    <a class="btn btn-primary btn-sm" href="'.URL_PATH.'/Proyecto/BuscarProyecto?idProyecto='.$proyecto['id_proyecto'].'">Ver detalle</a>
                  </div>
                </div>
              </div>';
      } 
    ?>
  </div>

</div>
<script type="text/javascript" src="<?= URL_PATH ?>/js/ProyectoLista.js"></script>