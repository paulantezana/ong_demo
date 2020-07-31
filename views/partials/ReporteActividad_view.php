<div class="table-responsive">
  <table class="table table-hover table-sm" id="tablaReporte">
    <thead>
      <tr>
        <th scope="col">Fecha</th>
        <th scope="col">Usuario</th>
        <th scope="col">Acción</th>
        <th scope="col">Acción afectada</th>
        <th scope="col">Objetivo</th>
      </tr>
    </thead>
    <tbody >
      <?php foreach ($parametro[0] as $key => $actividad): ?>
        <tr>
          <td><?php echo $actividad["fecha_creacion"] ?></td>
          <td><?php echo $actividad["nombre"] ?></td>
          <td><?php echo $actividad["accionEjecutada"] ?></td>
          <td><?php echo $actividad["accion"] ?></td>
          <td><?php echo $actividad["objetivo"] ?></td>
        </tr>
      <?php endforeach ?>
    </tbody>
  </table>
</div>