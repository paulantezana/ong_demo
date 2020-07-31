<div class="table-responsive">
  <table class="table table-hover table-sm">
    <thead>
      <tr>
        <th scope="col">Nombre</th>
        <th scope="col">Correo</th>
        <th scope="col">Perfil</th>
        <th scope="col">Alta</th>
        <th scope="col" style="width: 140px;"></th>
      </tr>
    </thead>
    <tbody id="tablaReporte">
      <?php foreach ($parametro[0] as $key => $usuario): ?>
        <tr>
          <td><?php echo $usuario["nombre"] ?></td>
          <td><?php echo $usuario["correo"] ?></td>
          <td><?php echo $usuario["perfil"] ?></td>
          <td><?php echo $usuario["fecha_creacion"] ?></td>
          <td>
            <button type="button" class="btn btn-light btn-sm mr-2" onclick="ModalUsuarioEliminar(<?php echo $usuario['id_usuario'] ?>)" data-toggle="tooltip" data-placement="bottom" title="Eliminar acción"><i class="fas fa-trash-alt"></i></button>
            <button type="button" class="btn btn-light btn-sm mr-2" onclick="ModalUsuarioModificar(<?php echo $usuario['id_usuario'] ?>)" data-toggle="tooltip" data-placement="bottom" title="Modificar acción"><i class="far fa-edit"></i></button>
            <button type="button" class="btn btn-<?php echo $usuario['cantidadProyecto'] > 0 ? 'success' : 'danger'; ?> btn-sm" onclick="ModalUsuarioProyecto(<?php echo $usuario['id_usuario'] ?>)" data-toggle="tooltip" data-placement="bottom" title="Asignar proyectos"><i class="fas fa-list-alt"></i></button>
          </td>
        </tr>
      <?php endforeach ?>
    </tbody>
  </table>
</div>