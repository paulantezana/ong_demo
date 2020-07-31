<div class="table-responsive">
  <table class="table table-hover table-sm">
    <thead>
      <tr>
        <th scope="col">Proyecto</th>
        <th scope="col">Acceso</th>
      </tr>
    </thead>
    <tbody id="tablaReporte">
      <?php foreach ($parametro[0] as $key => $proyecto): ?>
        <tr>
          <td><?php echo $proyecto["nombre"] ?></td>
          <td class="text-center">
            <input style="height: 20px; width: 20px;" type="checkbox" id="acceso_<?php echo $proyecto['id_proyecto']; ?>">
          </td>
        </tr>
      <?php endforeach ?>
    </tbody>
  </table>
</div>