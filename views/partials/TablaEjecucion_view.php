<div class="table-responsive">
  <table class="table table-hover table-sm">
    <thead>
      <tr>
        <th></th>
        <th scope="col">F. Inicio</th>
        <th scope="col">F. Fin</th>
        <th scope="col">Meta</th>
        <th scope="col">Avance <?php if ($parametro[3] == 0) {echo ' %';} ?></th>
        <?php if ($parametro[1] == 1): ?>
          <th scope="col">Medición</th>
        <?php endif ?>
        <th scope="col">Observacion</th>
      </tr>
    </thead>
    <tbody id="tablaReporte">
      <?php if (count($parametro[0]) > 0): ?>
        <?php foreach ($parametro[0] as $key => $ejecucion): ?>
          <tr>
            <td>
              <?php if ($parametro[2] == 'AUTORIZADO'): ?>
                <?php if ($ejecucion['avance'] <= 0): ?>
                  <button name="opcionEjecucionAvance" type="button" class="btn btn-outline-success btn-sm d-none" onclick="ModalAccionEjecucionAvance(<?php echo $ejecucion['id_accion_ejecucion'] ?>)" data-toggle="tooltip" data-placement="bottom" title="Registrar avance"><i class="fas fa-tools"></i></button>
                <?php else: ?>
                  <button name="opcionEjecucionAvance_Admin" type="button" class="btn btn-outline-success btn-sm d-none" onclick="ModalAccionEjecucionAvance(<?php echo $ejecucion['id_accion_ejecucion'] ?>)" data-toggle="tooltip" data-placement="bottom" title="Registrar avance"><i class="fas fa-tools"></i></button>
                <?php endif ?>
                <?php if ($ejecucion['cantidadParticipante'] <= 0): ?>
                  <button name="opcionEjecucionParticipante" type="button" class="btn btn-outline-warning btn-sm d-none" onclick="ModalParticipante(<?php echo $ejecucion['id_accion_ejecucion'] ?>)" data-toggle="tooltip" data-placement="bottom" title="Registrar participantes"><i class="fas fa-users"></i></button>
                <?php else: ?>
                  <button name="opcionEjecucionParticipante" type="button" class="btn btn-outline-info btn-sm d-none" onclick="ModalParticipante(<?php echo $ejecucion['id_accion_ejecucion'] ?>)" data-toggle="tooltip" data-placement="bottom" title="Registrar participantes"><i class="fas fa-users"></i></button>
                <?php endif ?>
                <?php if ($ejecucion['cantidadFoto'] <= 0): ?>
                  <button name="opcionEjecucionFoto" type="button" class="btn btn-outline-warning btn-sm d-none" onclick="ModalFoto(<?php echo $ejecucion['id_accion_ejecucion'] ?>)" data-toggle="tooltip" data-placement="bottom" title="Ver/Registrar fotos de la actividad"><i class="far fa-images"></i></button>
                <?php else: ?>
                  <button name="opcionEjecucionFoto" type="button" class="btn btn-outline-info btn-sm d-none" onclick="ModalFoto(<?php echo $ejecucion['id_accion_ejecucion'] ?>)" data-toggle="tooltip" data-placement="bottom" title="Ver/Registrar fotos de la actividad"><i class="far fa-images"></i></button>
                <?php endif ?>
              <?php else: ?>
                <button name="opcionEjecucionEliminar" type="button" class="btn btn-outline-danger btn-sm mr-2 d-none" onclick="ModalAccionEjecucionEliminar(<?php echo $ejecucion['id_accion_ejecucion'] ?>)" data-toggle="tooltip" data-placement="bottom" title="Eliminar acción"><i class="fas fa-trash-alt"></i></button>
                <button name="opcionEjecucionModificar" type="button" class="btn btn-outline-warning btn-sm mr-2 d-none" onclick="ModalAccionEjecucionModificar(<?php echo $ejecucion['id_accion_ejecucion'] ?>)" data-toggle="tooltip" data-placement="bottom" title="Modificar acción"><i class="far fa-edit"></i></button>
              <?php endif ?>
            </td>
            <td><?php echo $ejecucion["fecha_inicio"] ?></td>
            <td><?php echo $ejecucion["fecha_fin"] ?></td>
            <td><?php echo $ejecucion["meta"] ?></td>
            <td><?php echo $ejecucion["avance"] ?></td>
            <?php if ($parametro[1] == 1): ?>
              <td><?php echo $ejecucion["medida_adicional"] ?></td>
            <?php endif ?>
            <td><?php echo $ejecucion["observacion"] ?></td>
          </tr>
        <?php endforeach ?>
      <?php else: ?>
        <tr>
          <td colspan="<?php echo (6 + $parametro[1]) ?>"><h5>Aun no se programaron ejecuciones.</h5></td>
        </tr>
      <?php endif ?>
      
    </tbody>
  </table>
</div>