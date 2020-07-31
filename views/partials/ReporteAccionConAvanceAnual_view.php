<div class="table-responsive">
  <table class="table table-hover table-sm table-bordered" id="tablaReporte">
    <thead>
      <tr>
        <th scope="col" rowspan="2">Actividad</th>
        <th scope="col" rowspan="2">Acción</th>
        <th scope="col" rowspan="2">Unidad</th>
        <th scope="col" rowspan="2">Meta<br>Proyecto</th>
        <th scope="col" colspan="3">Enero</th>
        <th scope="col" colspan="3">Febrero</th>
        <th scope="col" colspan="3">Marzo</th>
        <th scope="col" colspan="3">Abril</th>
        <th scope="col" colspan="3">Mayo</th>
        <th scope="col" colspan="3">Junio</th>
        <th scope="col" colspan="3">Julio</th>
        <th scope="col" colspan="3">Agosto</th>
        <th scope="col" colspan="3">Septiembre</th>
        <th scope="col" colspan="3">Octubre</th>
        <th scope="col" colspan="3">Noviembre</th>
        <th scope="col" colspan="3">Diciembre</th>
        <th scope="col" colspan="3">Total Anual</th>
      </tr>
      <tr>
        <th scope="col">Meta</th>
        <th scope="col">Ejec.</th>
        <th scope="col">Avance</th>
        <th scope="col">Meta</th>
        <th scope="col">Ejec.</th>
        <th scope="col">Avance</th>
        <th scope="col">Meta</th>
        <th scope="col">Ejec.</th>
        <th scope="col">Avance</th>
        <th scope="col">Meta</th>
        <th scope="col">Ejec.</th>
        <th scope="col">Avance</th>
        <th scope="col">Meta</th>
        <th scope="col">Ejec.</th>
        <th scope="col">Avance</th>
        <th scope="col">Meta</th>
        <th scope="col">Ejec.</th>
        <th scope="col">Avance</th>
        <th scope="col">Meta</th>
        <th scope="col">Ejec.</th>
        <th scope="col">Avance</th>
        <th scope="col">Meta</th>
        <th scope="col">Ejec.</th>
        <th scope="col">Avance</th>
        <th scope="col">Meta</th>
        <th scope="col">Ejec.</th>
        <th scope="col">Avance</th>
        <th scope="col">Meta</th>
        <th scope="col">Ejec.</th>
        <th scope="col">Avance</th>
        <th scope="col">Meta</th>
        <th scope="col">Ejec.</th>
        <th scope="col">Avance</th>
        <th scope="col">Meta</th>
        <th scope="col">Ejec.</th>
        <th scope="col">Avance</th>
        <th scope="col">Meta</th>
        <th scope="col">Ejec.</th>
        <th scope="col">Avance</th>
      </tr>
    </thead>
    <tbody >
      <?php foreach ($parametro[0] as $key => $accion): ?>
        <tr>
          <td><?php echo $accion["actividad"] ?></td>
          <td><?php echo $accion["nombre"] ?></td>
          <td><?php echo $accion["unidad"] ?></td>
          <td><?php echo $accion["metaProyecto"] ?></td>
          <td><?php echo $accion["metaEnero"] ?></td>
          <td><?php echo $accion["avanceEnero"] ?></td>
          <td><?php echo $accion["metaEnero"] > 0 ? number_format(($accion["avanceEnero"] * 100 / $accion["metaEnero"]), 2) : 0 ?></td>
          <td><?php echo $accion["metaFebrero"] ?></td>
          <td><?php echo $accion["avanceFebrero"] ?></td>
          <td><?php echo $accion["metaFebrero"] > 0 ? number_format(($accion["avanceFebrero"] * 100 / $accion["metaFebrero"]), 2) : 0 ?></td>
          <td><?php echo $accion["metaMarzo"] ?></td>
          <td><?php echo $accion["avanceMarzo"] ?></td>
          <td><?php echo $accion["metaMarzo"] > 0 ? number_format(($accion["avanceMarzo"] * 100 / $accion["metaMarzo"]), 2) : 0 ?></td>
          <td><?php echo $accion["metaAbril"] ?></td>
          <td><?php echo $accion["avanceAbril"] ?></td>
          <td><?php echo $accion["metaAbril"] > 0 ? number_format(($accion["avanceAbril"] * 100 / $accion["metaAbril"]), 2) : 0 ?></td>
          <td><?php echo $accion["metaMayo"] ?></td>
          <td><?php echo $accion["avanceMayo"] ?></td>
          <td><?php echo $accion["metaMayo"] > 0 ? number_format(($accion["avanceMayo"] * 100 / $accion["metaMayo"]), 2) : 0 ?></td>
          <td><?php echo $accion["metaJunio"] ?></td>
          <td><?php echo $accion["avanceJunio"] ?></td>
          <td><?php echo $accion["metaJunio"] > 0 ? number_format(($accion["avanceJunio"] * 100 / $accion["metaJunio"]), 2) : 0 ?></td>
          <td><?php echo $accion["metaJulio"] ?></td>
          <td><?php echo $accion["avanceJulio"] ?></td>
          <td><?php echo $accion["metaJulio"] > 0 ? number_format(($accion["avanceJulio"] * 100 / $accion["metaJulio"]), 2) : 0 ?></td>
          <td><?php echo $accion["metaAgosto"] ?></td>
          <td><?php echo $accion["avanceAgosto"] ?></td>
          <td><?php echo $accion["metaAgosto"] > 0 ? number_format(($accion["avanceAgosto"] * 100 / $accion["metaAgosto"]), 2) : 0 ?></td>
          <td><?php echo $accion["metaSeptiembre"] ?></td>
          <td><?php echo $accion["avanceSeptiembre"] ?></td>
          <td><?php echo $accion["metaSeptiembre"] > 0 ? number_format(($accion["avanceSeptiembre"] * 100 / $accion["metaSeptiembre"]), 2) : 0 ?></td>
          <td><?php echo $accion["metaOctubre"] ?></td>
          <td><?php echo $accion["avanceOctubre"] ?></td>
          <td><?php echo $accion["metaOctubre"] > 0 ? number_format(($accion["avanceOctubre"] * 100 / $accion["metaOctubre"]), 2) : 0 ?></td>
          <td><?php echo $accion["metaNoviembre"] ?></td>
          <td><?php echo $accion["avanceNoviembre"] ?></td>
          <td><?php echo $accion["metaNoviembre"] > 0 ? number_format(($accion["avanceNoviembre"] * 100 / $accion["metaNoviembre"]), 2) : 0 ?></td>
          <td><?php echo $accion["metaDiciembre"] ?></td>
          <td><?php echo $accion["avanceDiciembre"] ?></td>
          <td><?php echo $accion["metaDiciembre"] > 0 ? number_format(($accion["avanceDiciembre"] * 100 / $accion["metaDiciembre"]), 2) : 0 ?></td>
          <td><?php echo $accion["metaEnero"] + $accion["metaFebrero"] + $accion["metaMarzo"] + $accion["metaAbril"] + $accion["metaMayo"] + $accion["metaJunio"] + $accion["metaJulio"] + $accion["metaAgosto"] + $accion["metaSeptiembre"] + $accion["metaOctubre"] + $accion["metaNoviembre"] + $accion["metaDiciembre"]?></td>
          <td><?php echo $accion["avanceEnero"] + $accion["avanceFebrero"] + $accion["avanceMarzo"] + $accion["avanceAbril"] + $accion["avanceMayo"] + $accion["avanceJunio"] + $accion["avanceJulio"] + $accion["avanceAgosto"] + $accion["avanceSeptiembre"] + $accion["avanceOctubre"] + $accion["avanceNoviembre"] + $accion["avanceDiciembre"]?></td>
          <td><?php echo ($accion["metaEnero"] + $accion["metaFebrero"] + $accion["metaMarzo"] + $accion["metaAbril"] + $accion["metaMayo"] + $accion["metaJunio"] + $accion["metaJulio"] + $accion["metaAgosto"] + $accion["metaSeptiembre"] + $accion["metaOctubre"] + $accion["metaNoviembre"] + $accion["metaDiciembre"]) > 0 ? number_format((($accion["avanceEnero"] + $accion["avanceFebrero"] + $accion["avanceMarzo"] + $accion["avanceAbril"] + $accion["avanceMayo"] + $accion["avanceJunio"] + $accion["avanceJulio"] + $accion["avanceAgosto"] + $accion["avanceSeptiembre"] + $accion["avanceOctubre"] + $accion["avanceNoviembre"] + $accion["avanceDiciembre"]) * 100 / ($accion["metaEnero"] + $accion["metaFebrero"] + $accion["metaMarzo"] + $accion["metaAbril"] + $accion["metaMayo"] + $accion["metaJunio"] + $accion["metaJulio"] + $accion["metaAgosto"] + $accion["metaSeptiembre"] + $accion["metaOctubre"] + $accion["metaNoviembre"] + $accion["metaDiciembre"])), 2) : 0 ?></td>
        </tr>
      <?php endforeach ?>
    </tbody>
  </table>
</div>