<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#" onclick="AvanceProyecto(<?php echo $parametro[0]; ?>)"><?php echo $parametro[1]; ?></a></li>
    <li class="breadcrumb-item"><a href="#" onclick="AvanceObjetivo(<?php echo $parametro[2]; ?>)"><?php echo $parametro[3]; ?></a></li>
    <li class="breadcrumb-item active" aria-current="page"><?php echo $parametro[5]; ?></li>
  </ol>
</nav>

<div class="table-responsive">
  <table class="table table-hover table-sm" id="tablaReporte">
    <thead>
      <tr>
        <th scope="col">Código</th>
        <th scope="col">Actividad</th>
        <th scope="col">Avance</th>
        <th scope="col" class="no-export">Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($parametro[6] as $key => $actividad): ?>
        <tr>
          <td><?php echo $actividad["codigo"] ?></td>
          <td><?php echo $actividad["nombre"] ?></td>
          <td><?php echo $actividad["avance"] ?>%</td>
          <td class="no-export">
            <button type="button" class="btn btn-link" onclick="ReporteActividad(<?php echo $actividad['id_actividad'] ?>)">Ver Acciones</i></button>
          </td>
        </tr>
      <?php endforeach ?>
    </tbody>
  </table>
</div>