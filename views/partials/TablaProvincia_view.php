<div class="card">
  <h5 class="card-header CrudHeader">
    Provincias
    <button type="button" class="btn btn-sm btn-primary float-right" onclick="ModalProvinciaNueva()" data-toggle="tooltip" data-placement="bottom" title="Crear provincia"><i class="fas fa-plus-circle mr-2"></i> Crear</button>
  </h5>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-hover table-sm">
        <thead>
          <tr>
            <th scope="col">Nombre</th>
            <th scope="col" style="width: 90px"></th>
          </tr>
        </thead>
        <tbody id="tablaReporte">
          <?php foreach ($parametro[0] as $key => $provincia): ?>
            <tr>
              <td><?php echo $provincia["nombre"] ?></td>
              <td class="text-center">
                <button type="button" class="btn btn-sm btn-light" onclick="ModalProvinciaModificar(<?php echo $provincia["id_provincia"] ?>)" data-toggle="tooltip" data-placement="bottom" title="Modificar provincia"><i class="fas fa-edit"></i></button>
                <button type="button" class="btn btn-sm btn-success" onclick="TablaDistrito(<?php echo $provincia["id_provincia"] ?>)" data-toggle="tooltip" data-placement="bottom" title="Ver distritos"><i class="fas fa-search"></i></button>
              </td>
            </tr>
          <?php endforeach ?>
        </tbody>
      </table>
    </div>
  </div>
</div>