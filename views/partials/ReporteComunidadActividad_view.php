<div class="table-responsive">
  <table class="table table-hover table-sm" id="tablaReporte">
    <thead>
      <tr scope="col">
        <td colspan="3">
          <strong>Comunidad : </strong><span><?= $parametro[0]['nombre'] ?></span><br>
        </td>
      </tr>
      <tr>
        <th scope="col">Actividad</th>
        <th scope="col">producto</th>
      </tr>
    </thead>
    <tbody >
      <?php foreach ($parametro[1] as $key => $actividad): ?>
        <tr>
          <td><?php echo $actividad["nombre"] ?></td>
          <td><?php echo $actividad["producto"] ?></td>
        </tr>
      <?php endforeach ?>
    </tbody>
  </table>
</div>