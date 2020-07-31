<div class="table-responsive">
  <table class="table table-hover table-sm" id="tablaReporte">
    <thead>
      <tr>
        <th scope="col">Fecha Ejecución</th>
        <th scope="col">Medida</th>
      </tr>
    </thead>
    <tbody >
      <?php foreach ($parametro[0] as $key => $accionEjecucion): ?>
        <tr>
          <td><?php echo $accionEjecucion["fecha_ejecucion"] ?></td>
          <td><?php echo $accionEjecucion["medida_adicional"]. ' ('. $accionEjecucion["unidad_dato_adicional"]. ')' ?></td>
        </tr>
      <?php endforeach ?>
    </tbody>
  </table>
</div>