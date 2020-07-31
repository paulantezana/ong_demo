<div class="table-responsive">
  <table class="table table-hover table-sm" id="tablaReporteDetalle">
    <thead>
      <tr>
        <th scope="col">F. Inicio</th>
        <th scope="col">F. Fin</th>
        <th scope="col">Meta <br> Proyecto</th>
        <th scope="col">Meta <br> Ejecutada</th>
        <th scope="col">Avance</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($parametro[0] as $key => $ejecucion): ?>
        <tr>
          <td><?php echo $ejecucion["fecha_inicio"] ?></td>
          <td><?php echo $ejecucion["fecha_fin"] ?></td>
          <td><?php echo $ejecucion["meta"] ?></td>
          <td><?php echo $ejecucion["avance"] ?></td>
          <td><?php echo round(($ejecucion["avance"] * 100 / $ejecucion["meta"]), 2) ; ?>%</td>
        </tr>
      <?php endforeach ?>
    </tbody>
  </table>
</div>