<div class="table-responsive">
  <table class="table table-hover table-sm" id="tablaReporte">
    <thead>
      <tr>
        <th scope="col">Acción</th>
        <th scope="col">Unidad</th>
        <th scope="col">Meta <br> Proyecto</th>
        <th scope="col">Meta <br> Ejecutada</th>
        <th scope="col">Avance</th>
        <th scope="col" class="no-export">Ejecuciones</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($parametro[0] as $key => $accion): ?>
        <tr>
          <td><?php echo $accion["nombre"] ?></td>
          <td><?php echo $accion["cuantitativa"] == 1 ? $accion["unidad_meta"] : '%'; ?></td>
          <td><?php echo $accion["metaProyecto"] ?></td>
          <td><?php echo $accion["metaEjecutada"] ?></td>
          <td><?php echo round(($accion["metaEjecutada"] * 100 / $accion["metaProyecto"]), 2) ; ?>%</td>
          <td class="no-export">
            <button type="button" class="btn btn-link" onclick="AvanceAccion(<?php echo $accion['id_accion'] ?>)">Ver Detalle</i></button>
          </td>
        </tr>
      <?php endforeach ?>
    </tbody>
  </table>
</div>