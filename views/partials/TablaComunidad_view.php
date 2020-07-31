<div class="card">
  <h5 class="card-header CrudHeader">
    Comunidades
    <button type="button" class="btn btn-sm btn-primary float-right" onclick="ModalComunidadNuevo()" data-toggle="tooltip" data-placement="bottom" title="Crear comunidad"><i class="fas fa-plus-circle"></i> Crear</button>
  </h5>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-hover table-sm">
        <thead>
          <tr>
            <th scope="col">Nombre</th>
            <th scope="col"></th>
          </tr>
        </thead>
        <tbody id="tablaReporte">
          <?php foreach ($parametro[0] as $key => $comunidad): ?>
            <tr>
              <td><?php echo $comunidad["nombre"] ?></td>
              <td class="text-center">
                <button type="button" class="btn btn-sm btn-light" onclick="ModalComunidadModificar(<?php echo $comunidad["id_comunidad"] ?>)" data-toggle="tooltip" data-placement="bottom" title="Modificar comunidad"><i class="fas fa-edit"></i></button>
              </td>
            </tr>
          <?php endforeach ?>
        </tbody>
      </table>
    </div>
  </div>
</div>