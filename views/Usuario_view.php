<div class="MainContainer">
    <div class="TopToolbar">
        <div class="TopToolbar-left">
            <i class=" fas fa-list-ul mr-2"></i><strong>Usuarios Registrados</strong>
        </div>
        <div class="TopToolbar-right">
           <button type="button" class="btn btn-primary" onclick="ModalUsuarioNuevo();" data-toggle="tooltip" data-placement="bottom" title="Registrar nuevo usuario"><i class="fas fa-plus-circle mr-2"></i> Nuevo</button>
        </div>
    </div>
    
    <div id="divReporte"></div>
</div>

<script type="text/javascript" src="<?= URL_PATH ?>/js/Usuario.js"></script>

<div class="modal fade" id="modalUsuario" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form class="was-validated" id="formUsuario" onsubmit="RegistrarUsuario();">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalUsuario_titulo"></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="nombre" class="col-form-label">Nombre:</label>
            <input type="text" class="form-control" id="nombre" required>
          </div>
          <div class="form-group">
            <label for="correo" class="col-form-label">Correo:</label>
            <input type="email" class="form-control" id="correo" required>
          </div>
          <div class="form-group">
            <label for="password" class="col-form-label">Password:</label>
            <input type="password" class="form-control" id="password" required>
          </div>
          <div class="form-group">
            <label for="modalPersona_dni" class="col-form-label">Perfil:</label>
            <select class="form-control" id="perfil">
              <?php foreach ($parametro[0] as $key => $perfil): ?>
                <option value="<?php echo $perfil['id_perfil'] ?>"><?php echo $perfil['nombre'] ?></option>
              <?php endforeach ?>
            </select>
          </div>
          <div class="form-group">
            <label for="direccion" class="col-form-label">Dirección:</label>
            <input type="direccion" class="form-control" id="direccion" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary" id="modalUsuario_guardar">Registrar</button>
        </div>
      </div>
    </form>
  </div>
</div>

<div class="modal fade" id="modalUsuarioProyecto" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Asignación de proyectos</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="tablaProyecto">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" onclick="RegistrarAcceso()">Guardar Cambios</button>
      </div>
    </div>
  </div>
</div>