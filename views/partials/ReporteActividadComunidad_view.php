<div class="table-responsive">
  <table class="table table-hover table-sm" id="tablaReporte">
    <thead>
      <tr>
        <th scope="col">Comunidad</th>
        <th scope="col">Accion</th>
      </tr>
    </thead>
    <tbody >
      <?php foreach ($parametro[0] as $key => $comunidad): ?>
        <tr>
          <td><?php echo $comunidad["nombre"] ?></td>
          <td><?php echo $comunidad["accion"] ?></td>
        </tr>
      <?php endforeach ?>
    </tbody>
  </table>
</div>