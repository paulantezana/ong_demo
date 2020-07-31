<div class="MainContainer">
  <div class="input-group mb-3">
    <div class="input-group-prepend">
      <label class="input-group-text" for="listaRegion">Regióon:</label>
    </div>
    <select class="custom-select" id="listaRegion" onchange="SeleccionarRegion()">
      <option selected disabled value="">Seleccione una región</option>
      <option value="0">Nueva</option>
      <?php foreach ($parametro[0] as $key => $region): ?>
        <option value="<?php echo $region['id_region']; ?>"><?php echo $region['nombre']; ?></option>
      <?php endforeach ?>
    </select>
  </div>
  <div class="row">
    <div class="col-12 col-md-4" id="divProvincia"></div>
    <div class="col-12 col-md-4" id="divDistrito"></div>
    <div class="col-12 col-md-4" id="divComunidad"></div>
  </div>
</div>

<script type="text/javascript" src="<?= URL_PATH ?>/js/Comunidad.js"></script>

<div class="modal fade" id="modalComunidad" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form class="was-validated" id="formComunidad" onsubmit="RegistrarComunidad();">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalComunidad_titulo"></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="modalComunidad_nombre" class="col-form-label">Nombre:</label>
            <input type="text" class="form-control" id="modalComunidad_nombre" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary" id="modalComunidad_guardar">Registrar</button>
        </div>
      </div>
    </form>
  </div>
</div>