<div class="MainContainer">
  <div class="TopToolbar">
    <div class="TopToolbar-left">
        <i class=" fas fa-list-ul mr-2"></i><strong>Familias Registradas</strong>
    </div>
    <div class="TopToolbar-right">
        <button type="button" class="btn btn-primary" onclick="ModalFamiliaNueva();" data-toggle="tooltip" data-placement="bottom" title="Registrar nueva familia"><i class="fas fa-plus-circle mr-2"></i> Registrar</button>    
    </div>
  </div>
  <div id="divReporte">
  </div>
</div>

<script type="text/javascript">
  var personas = JSON.parse(`<?php echo json_encode($parametro[0]); ?>`);
</script>

<script type="text/javascript" src="<?= URL_PATH ?>/js/Familia.js"></script>

<div class="modal fade" id="modalFamilia" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <form class="was-validated" id="formFamilia" onsubmit="RegistrarFamilia();">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalFamilia_titulo"></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="table-responsive">
            <table class="table table-hover table-sm">
              <thead>
                <tr>
                  <th scope="col">Rol</th>
                  <th scope="col">DNI</th>
                  <th scope="col">Ap. Paterno</th>
                  <th scope="col">Ap. Materno</th>
                  <th scope="col">Nombre(s)</th>
                  <th></th>
                </tr>
              </thead>
              <tbody id="tablaIntegrante">
                
              </tbody>
              <tfoot>
                <tr>
                  <th colspan="6"><button type="button" class="btn btn-outline-info float-right" onclick="AgregarIntegrante();">Agregar Integrante</button></th>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary" id="modalFamilia_guardar">Registrar</button>
        </div>
      </div>
    </form>
  </div>
</div>