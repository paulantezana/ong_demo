<div class="table-responsive">
  <table class="table table-hover table-sm" id="tablaReporte">
    <thead>
      <tr>
        <th scope="col">Nombre</th>
        <th scope="col">Familia</th>
        <th scope="col">Rol</th>
        <th scope="col">Accion</th>
      </tr>
    </thead>
    <tbody >
      <?php foreach ($parametro[0] as $key => $participante): ?>
        <tr>
          <td><?php echo $participante["paterno"] . ' ' . $participante["materno"] . ' ' . $participante["nombre"]?></td>
          <td><?php echo $participante["jefe"] ?></td>
          <td><?php echo $participante["rol"] ?></td>
          <td><?php echo $participante["accion"] ?></td>
        </tr>
      <?php endforeach ?>
    </tbody>
  </table>
</div>