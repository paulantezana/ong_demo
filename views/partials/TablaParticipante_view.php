<div class="table-responsive">
  <table class="table table-hover table-sm" style="margin-bottom: 150px;" id="tablaParticipantesActivos">
    <thead>
      <?php if ($parametro[1] == 0): ?>
        <tr>
          <th scope="col">Rol</th>
          <th scope="col">DNI</th>
          <th scope="col">Genero</th>
          <th scope="col">Edad</th>
          <th scope="col">Nombre</th>
          <th scope="col">Ap. Paterno</th>
          <th scope="col">Ap. Materno</th>
          <th></th>
        </tr>
      <?php else: ?>
        <tr>
          <th scope="col">Rol</th>
          <th scope="col">DNI</th>
          <th scope="col">Jefe de Familia</th>
          <th></th>
        </tr>
      <?php endif ?>
      
    </thead>
    <tbody id="tablaParticipante">
      <?php if (count($parametro[0]) > 0): ?>
        <?php foreach ($parametro[0] as $key => $participante): ?>
          <tr>
            <td>
              <select id="rol_<?php echo $participante['id_accion_ejecucion_participante']; ?>" class="form-control form-control-sm">
                <option value="PARTICIPANTE" <?php echo  $participante["rol"] == "PARTICIPANTE"? 'selected' : ''; ?>>PARTICIPANTE</option>
                <option value="RESPONSABLE" <?php echo  $participante["rol"] == "RESPONSABLE"? 'selected' : ''; ?>>RESPONSABLE</option>
                <option value="BENEFICIARIO" <?php echo  $participante["rol"] == "BENEFICIARIO"? 'selected' : ''; ?>>BENEFICIARIO</option>
              </select>
            </td>
            <td name="dni"><?php echo $participante["dni"] ?></td>
            <?php if ($parametro[1] == 0): ?>
              <td name="genero"><?php echo $participante["genero"] == 1 ? 'MASCULINO' : 'FEMENINO'; ?></td>
              <td name="edad"><?php echo CalcularEdad($participante["fecha_nacimiento"]); ?></td>
              <td name="nombre"><?php echo $participante["nombre"] ?></td>
              <td name="paterno"><?php echo $participante["paterno"] ?></td>
              <td name="materno"><?php echo $participante["materno"] ?></td>
              <td>
                <button type="button" class="btn btn-sm btn-outline-info mr-2" onclick="ModalActualizarParticipanteRol(<?php echo $participante["id_accion_ejecucion_participante"]; ?>)" data-toggle="tooltip" data-placement="bottom" title="Modificar rol"><i class="far fa-save"></i></button>
                <button type="button" class="btn btn-sm btn-outline-warning mr-2" onclick="ModalPersonaModificar(this, <?php echo $participante["id_persona"]; ?>)" data-toggle="tooltip" data-placement="bottom" title="Modificar datos persona"><i class="far fa-edit"></i></button>
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="ModalParticipanteEliminar(this, <?php echo $participante["id_accion_ejecucion_participante"]; ?>)" data-toggle="tooltip" data-placement="bottom" title="Eliminar participante"><i class="fas fa-trash-alt"></i></button>
              </td>
            <?php else: ?>
              <td name="jefe"><?php echo $participante["jefe"] ?></td>
              <td>
                <button type="button" class="btn btn-sm btn-outline-info mr-2" onclick="ModalActualizarParticipanteRol(<?php echo $participante["id_accion_ejecucion_participante"]; ?>)" data-toggle="tooltip" data-placement="bottom" title="Modificar rol"><i class="far fa-save"></i></button>
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="ModalParticipanteEliminar(this, <?php echo $participante["id_accion_ejecucion_participante"]; ?>)" data-toggle="tooltip" data-placement="bottom" title="Eliminar participante"><i class="fas fa-trash-alt"></i></button>
              </td>
            <?php endif ?>  
            
          </tr>
        <?php endforeach ?>
      <?php else: ?>
        <tr>
          <td colspan="6"><h5>Aun no se registraron participantes.</h5></td>
        </tr>
      <?php endif ?>  
    </tbody>
    <tfoot style="display:none;">
    </tfoot>
  </table>
</div>