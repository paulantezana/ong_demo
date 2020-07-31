<div class="card">
  <h5 class="card-header CrudHeader">
    Distritos
    <button type="button" class="btn btn-sm btn-primary float-right" onclick="ModalDistritoNuevo()" data-toggle="tooltip" data-placement="bottom" title="Crear distrito"><i class="fas fa-plus-circle mr-2"></i> Crear</button>
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
          <?php foreach ($parametro[0] as $key => $distrito): ?>
            <tr>
              <td><?php echo $distrito["nombre"] ?></td>
              <td class="text-center">
                <button type="button" class="btn btn-sm btn-light" onclick="ModalDistritoModificar(<?php echo $distrito["id_distrito"] ?>)" data-toggle="tooltip" data-placement="bottom" title="Modificar distrito"><i class="fas fa-edit"></i></button>
                <button type="button" class="btn btn-sm btn-success" onclick="TablaComunidad(<?php echo $distrito["id_distrito"] ?>)" data-toggle="tooltip" data-placement="bottom" title="Ver comunidades"><i class="fas fa-search"></i></button>
              </td>
            </tr>
          <?php endforeach ?>
        </tbody>
      </table>
    </div>
  </div>
</div>