<div class="table-responsive">
  <table class="table table-hover table-sm">
    <thead>
      <tr>
        <th scope="col">Producto</th>
        <th scope="col">Ponderación</th>
      </tr>
    </thead>
    <tbody id="tablaReporte">
      <?php foreach ($parametro[0] as $key => $producto) {
        echo  '<tr id="'.$producto["id_producto"].'">'.
                '<td>'.$producto["nombre"].'</td>'.
                '<td><input type="number" min="0.01" step="0.01" class="form-control" aria-describedby="emailHelp" value="'.$producto["ponderacion"].'" onchange="SumarPonderacion()" required></td>'.
              '</tr>';
      } ?>
    </tbody>
    <tfoot>
      <tr>
        <th>Total : </th>
        <th name="total"></th>
      </tr>
    </tfoot>
  </table>
</div>