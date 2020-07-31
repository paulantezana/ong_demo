<div class="table-responsive">
  <table class="table table-hover table-sm" id="tablaReporte">
    <thead>
      <tr scope="col">
        <td colspan="3">
          <strong>Jefe Fam. : </strong><span><?= $parametro[0]['paterno']. ' ' . $parametro[0]['materno']. ' ' . $parametro[0]['nombre'] ?></span><br>
          <strong>DNI : </strong><span><?= $parametro[0]['dni']?></span>
        </td>
      </tr>
      <tr>
        <th scope="col">Rol</th>
        <th scope="col">Actividad</th>
        <th scope="col">Producto</th>
      </tr>
    </thead>
    <tbody >
      <?php foreach ($parametro[1] as $key => $actividad): ?>
        <tr>
          <td><?php echo $actividad["rol"] ?></td>
          <td><?php echo $actividad["nombre"] ?></td>
          <td><?php echo $actividad["producto"] ?></td>
        </tr>
      <?php endforeach ?>
    </tbody>
  </table>
</div>