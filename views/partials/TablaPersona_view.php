<div class="table-responsive">
  <table class="table table-hover table-sm" id="tablaReportePersonas">
    <thead>
      <tr>
        <th scope="col">Ap. Paterno</th>
        <th scope="col">Ap. Materno</th>
        <th scope="col">Nombre(s)</th>
        <th scope="col">Tipo Doc.</th>
        <th scope="col">DNI</th>
        <th scope="col">Genero</th>
        <th scope="col">Edad</th>
        <th scope="col">Comunidad</th>
        <th scope="col" style="width: 50px"></th>
      </tr>
    </thead>
    <tbody id="tablaReporte">
      <?php foreach ($parametro[0] as $key => $persona): ?>
        <tr>
          <td><?php echo $persona["paterno"] ?></td>
          <td><?php echo $persona["materno"] ?></td>
          <td><?php echo $persona["nombre"] ?></td>
          <td><?php echo $persona["tipo_documento"] ?></td>
          <td><?php echo $persona["dni"] ?></td>
          <td><?php echo $persona["genero"] == 1 ? 'MASCULINO' : 'FEMENINO'; ?></td>
          <td><?php echo CalcularEdad($persona["fecha_nacimiento"]); ?></td>
          <td><?php echo $persona["comunidad"] ?></td>
          <td>
            <button type="button" class="btn btn-light btn-sm" onclick="ModalPersonaModificar(<?php echo $persona['id_persona'] ?>)" data-toggle="tooltip" data-placement="bottom" title="Modificar persona"><i class="far fa-edit"></i></button>
          </td>
        </tr>
      <?php endforeach ?>
    </tbody>
  </table>
</div>