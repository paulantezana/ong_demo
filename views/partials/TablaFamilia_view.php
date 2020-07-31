<div class="table-responsive">
  <table class="table table-hover table-sm">
    <thead>
      <tr>
        <th scope="col">Comunidad</th>
        <th scope="col">Jefe de familia</th>
        <th scope="col" style="width: 50px"></th>
      </tr>
    </thead>
    <tbody id="tablaReporte">
      <?php foreach ($parametro[0] as $key => $familia): ?>
        <tr>
          <td><?php echo $familia["comunidad"] ?></td>
          <td><?php echo $familia["paterno"]. ' ' . $familia["materno"] . ' ' . $familia["nombre"];?></td>
          <td>
            <button type="button" class="btn btn-light btn-sm" onclick="ModalFamiliaModificar(<?php echo $familia['id_familia'] ?>)" data-toggle="tooltip" data-placement="bottom" title="Modificar familia"><i class="far fa-edit"></i></button>
          </td>
        </tr>
      <?php endforeach ?>
    </tbody>
  </table>
</div>