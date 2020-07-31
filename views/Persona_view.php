<div class="MainContainer">
  <div class="TopToolbar">
    <div class="TopToolbar-left">
        <i class=" fas fa-list-ul mr-2"></i><strong>Personas Registradas</strong>
    </div>
    <div class="TopToolbar-right">
        <button type="button" class="btn btn-primary" onclick="ModalPersonaNueva();" data-toggle="tooltip" data-placement="bottom" title="Registrar nueva persona"><i class="fas fa-plus-circle mr-2"></i> Registrar</button>    
    </div>
  </div>

  <div class="row">
    <div class="col-12">
      <div class="input-group input-group-sm mb-3">
        <div class="input-group-prepend">
          <span class="input-group-text">Comunidad :</span>
        </div>
        <select class="chosen-select" id="listaComunidad">
          <option value="" disabled selected>Seleccione comunidad</option>
          <option value="0">Todo</option>
          <?php foreach ($parametro[1] as $key => $comunidad): ?>
            <option value="<?php echo $comunidad['id_comunidad'] ?>"><?php echo $comunidad['nombre'] ?></option>
          <?php endforeach ?>
        </select>
        <div class="input-group-append">
          <button type="button" class="btn btn-outline-success" onclick="ListarPersona();"><i class="fas fa-search"></i></button>
        </div>
        <div class="input-group-append">
          <button type="button" class="btn btn-outline-success" onclick="ExportarExcel();"><i class="far fa-file-excel"></i></button>
        </div>
      </div>
    </div>
  </div>

  <div id="divReporte">
  </div>
</div>

<script type="text/javascript" src="<?= URL_PATH ?>/js/Persona.js"></script>

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
              <div class="input-group input-group-sm mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text">Comunidad :</span>
                </div>
                <select class="chosen-select" id="comunidad">
                  <option value="0" disabled selected>Seleccione comunidad</option>
                  <?php foreach ($parametro[1] as $key => $comunidad): ?>
                    <option value="<?php echo $comunidad['id_comunidad'] ?>"><?php echo $comunidad['nombre'] ?></option>
                  <?php endforeach ?>
                </select>
              </div>
            </div>
            <div class="col-4">
              <div class="form-group">
                <label for="modalPersona_tipo" class="col-form-label">TIPO:</label>
                <select id="modalPersona_tipo" class="form-control" onchange="CambiarTextoInput()">
                    <option >DNI</option>
                    <option >CODIGO</option>
                </select>
              </div>
            </div>
            <div class="col-8">
              <div class="form-group">
                <label for="modalPersona_dni" class="col-form-label" id="labelTipoDocumento">DNI:</label>
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