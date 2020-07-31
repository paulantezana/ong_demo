<div class="MainContainer">
	<form class="mt-3 was-validated" id="formProyecto" onsubmit="RegistrarProyecto()">
		<div class="row justify-content-center">
		<input id="titulo" placeholder="Titulo del proyecto" type="text" class="form-control col-12 col-md-8 titulo-proyecto" required>
		</div>
		<div class="form-group">
		<label for="inputAddress2">Descripción</label>
		<textarea id="descripcion" placeholder="Descipción del proyecto" type="text" name="" class="form-control" required></textarea>
		</div>
		<div class="row">
		<div class="col-12 col-md-5 text-center">
			<div class="custom-file">
			<input type="file" class="custom-file-input" id="foto" required>
			<label class="custom-file-label" for="foto">Selecionar imagen de portada...</label>
			</div>
			<img src="<?= URL_PATH ?>/img/sin_foto.jpg" class="img-fluid" id="fotoSelecionada">
		</div>
		<div class="col-12 col-md-3">
			<div class="form-group">
			<label for="fechaInicio"><i class="far fa-calendar-alt"></i> Fecha Inicio</label>
			<input type="date" class="form-control datepicker" id="fechaInicio" placeholder="Seleccione fecha inicio" onkeydown="return false" required>
			</div>
			<div class="form-group">
			<label for="fechaFin"><i class="far fa-calendar-alt"></i> Fecha Fin</label>
			<input type="date" class="form-control datepicker" id="fechaFin" placeholder="Seleccione fecha fin" onkeydown="return false" required>
			</div>
		</div>
		</div>
		<div class="row justify-content-center">
		<div class="col-12 col-md-6 text-center">
			<button type="submit" class="btn btn-primary w-100 mt-3"><i class="fas fa-tools mr-2"></i> Guardar Proyecto Nuevo</button>
		</div>
		</div>
	</form>
</div>

<script type="text/javascript" src="<?= URL_PATH ?>/js/ProyectoNuevo.js"></script>