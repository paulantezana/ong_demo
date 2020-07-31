<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#"><?php echo $parametro[0]; ?></a></li>
  </ol>
</nav>

<div class="table-responsive">
  <table class="table table-hover table-sm" id="tablaReporte">
    <thead>
      <tr>
        <th scope="col">Objetivo</th>
        <th scope="col">Avance</th>
        <th scope="col" class="no-export">Productos</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($parametro[1] as $key => $objetivo): ?>
        <tr>
          <td><?php echo $objetivo["nombre"] ?></td>
          <td><?php echo $objetivo["avance"] ?>%</td>
          <td class="no-export">
            <button type="button" class="btn btn-link" onclick="AvanceObjetivo(<?php echo $objetivo['id_objetivo'] ?>)">Ver Productos</i></button>
          </td>
        </tr>
      <?php endforeach ?>
    </tbody>
  </table>
</div>