<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#" onclick="AvanceProyecto(<?php echo $parametro[0]; ?>)"><?php echo $parametro[1]; ?></a></li>
    <li class="breadcrumb-item active" aria-current="page"><?php echo $parametro[3]; ?></li>
  </ol>
</nav>

<div class="table-responsive">
  <table class="table table-hover table-sm" id="tablaReporte">
    <thead>
      <tr>
        <th scope="col">Producto</th>
        <th scope="col">Avance</th>
        <th class="no-export" scope="col">Actividades</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($parametro[4] as $key => $producto): ?>
        <tr>
          <td><?php echo $producto["nombre"] ?></td>
          <td><?php echo $producto["avance"] ?>%</td>
          <td class="no-export">
            <button type="button" class="btn btn-link" onclick="AvanceProducto(<?php echo $producto['id_producto'] ?>)">Ver Actividades</i></button>
          </td>
        </tr>
      <?php endforeach ?>
    </tbody>
  </table>
</div>