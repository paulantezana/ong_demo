<?php
	require_once("models/Proyecto_model.php");
	require_once("models/Objetivo_model.php");
	require_once("models/Producto_model.php");
	require_once("models/Actividad_model.php");
	require_once("models/ActividadDetalle_model.php");
	require_once("models/Accion_model.php");
	require_once("models/AccionEjecucion_model.php");
	require_once("models/Historial_model.php");
	require_once("models/Region_model.php");
	require_once("models/Provincia_model.php");
	require_once("models/Distrito_model.php");
	require_once("models/Comunidad_model.php");
	require_once("models/Persona_model.php");
	require_once("models/Familia_model.php");
	require_once("models/AccionEjecucionParticipante_model.php");

	class Reporte
	{
		private $proyectoModel;
		private $objetivoModel;
		private $productoModel;
		private $actividadModel;
		private $actividadDetalleModel;
		private $accionModel;
		private $accionEjecucionModel;
		private $historialModel;
		private $regionModel;
		private $provinciaModel;
		private $distritoModel;
		private $comunidadModel;
		private $personaModel;
		private $familiaModel;
		private $accionEjecucionParticipanteModel;
		protected $db;

		public function __construct($dbConexion)
		{
			$this->db = $dbConexion;
			$this->proyectoModel = new Proyecto_model($this->db);
			$this->objetivoModel = new Objetivo_model($this->db);
			$this->productoModel = new Producto_model($this->db);
			$this->actividadModel = new Actividad_model($this->db);
			$this->actividadDetalleModel = new ActividadDetalle_model($this->db);
			$this->accionModel = new Accion_model($this->db);
			$this->accionEjecucionModel = new AccionEjecucion_model($this->db);
			$this->historialModel = new Historial_model($this->db);

			$this->regionModel = new Region_model($this->db);
			$this->provinciaModel = new Provincia_model($this->db);
			$this->distritoModel = new Distrito_model($this->db);
			$this->comunidadModel = new Comunidad_model($this->db);
			$this->personaModel = new Persona_model($this->db);
			$this->familiaModel = new Familia_model($this->db);
			$this->accionEjecucionParticipanteModel = new AccionEjecucionParticipante_model($this->db);
			ValidarSession();
		}
		
		public function Avance()
		{
			try {
				$parametro[0] = $this->proyectoModel->ListarPorUsuario();
				$contenido = requireToVar("views/ReporteAvance_view.php", $parametro);
				$accesos = ListarPermiso($this->db);
				require_once("views/layouts/plantilla.php");
			}
			catch (Exception $e){
				print($e->getMessage()."\n\n".$e->getTraceAsString());
			}
		}

		public function AvanceProyecto()
		{
			ValidarAccesoProyecto($this->db);
			$res = new Resultado();

			try {
				$idProyecto = $_GET['idProyecto'];
				
				$proyecto = $this->proyectoModel->BuscarPorId($idProyecto);

				if (strlen($proyecto['nombre']) > 50) {
					$parametro[0] = substr($proyecto['nombre'], 0, 47) . "...";
				}
				else{
					$parametro[0] = $proyecto['nombre'];
				}

				$parametro[1] = $this->objetivoModel->ListarPorProyecto($idProyecto);
				$res->vista = requireToVar("views/partials/ReporteAvanceObjetivo_view.php", $parametro);
				$res->estado = true;
			} catch (Exception $e) {
				$res->estado = false;
				$res->error = $e->getMessage();
			}
			
			echo json_encode($res);
		}

		public function AvanceObjetivo()
		{
			ValidarAccesoProyecto($this->db);
			$res = new Resultado();

			try {
				$idProyecto = $_GET['idProyecto'];
				$idObjetivo = $_GET['idObjetivo'];


				$objetivo = $this->objetivoModel->BuscarPorId($idProyecto, $idObjetivo);
				if ($objetivo == false) {
					throw new Exception("El proyecto no existe o no esta disponible para usted.");
				}

				$proyecto = $this->proyectoModel->BuscarPorId($idProyecto);
				$parametro[0] = $proyecto['id_proyecto'];
				if (strlen($proyecto['nombre']) > 50) {
					$parametro[1] = substr($proyecto['nombre'], 0, 47) . "...";
				}
				else{
					$parametro[1] = $proyecto['nombre'];
				}

				$parametro[2] = $objetivo['id_objetivo'];
				if (strlen($objetivo['nombre']) > 50) {
					$parametro[3] = substr($objetivo['nombre'], 0, 47) . "...";
				}
				else{
					$parametro[3] = $objetivo['nombre'];
				}

				$parametro[4] = $this->productoModel->ListarPorObjetivo($idObjetivo);
				$res->vista = requireToVar("views/partials/ReporteAvanceProducto_view.php", $parametro);
				$res->estado = true;
			} catch (Exception $e) {
				$res->estado = false;
				$res->error = $e->getMessage();
			}
			
			echo json_encode($res);
		}

		public function AvanceProducto()
		{
			ValidarAccesoProyecto($this->db);
			$res = new Resultado();

			try {
				$idProyecto = $_GET['idProyecto'];
				$idObjetivo = $_GET['idObjetivo'];
				$idProducto = $_GET['idProducto'];

				$producto = $this->ValidarAccesoProducto($idProyecto, $idObjetivo, $idProducto);
				if ($producto == false) {
					throw new Exception("El producto no existe o no esta disponible para usted.");
				}

				$proyecto = $this->proyectoModel->BuscarPorId($idProyecto);
				$objetivo = $this->objetivoModel->BuscarPorId($idProyecto, $idObjetivo);

				$parametro[0] = $proyecto['id_proyecto'];
				if (strlen($proyecto['nombre']) > 50) {
					$parametro[1] = substr($proyecto['nombre'], 0, 47) . "...";
				}
				else{
					$parametro[1] = $proyecto['nombre'];
				}

				$parametro[2] = $objetivo['id_objetivo'];
				if (strlen($objetivo['nombre']) > 50) {
					$parametro[3] = substr($objetivo['nombre'], 0, 47) . "...";
				}
				else{
					$parametro[3] = $objetivo['nombre'];
				}

				$parametro[4] = $producto['id_producto'];
				if (strlen($producto['nombre']) > 50) {
					$parametro[5] = substr($producto['nombre'], 0, 47) . "...";
				}
				else{
					$parametro[5] = $producto['nombre'];
				}

				$parametro[6] = $this->actividadModel->ListarAutorizadoPorProducto($idProducto);
				$res->vista = requireToVar("views/partials/ReporteAvanceActividad_view.php", $parametro);
				$res->estado = true;
			} catch (Exception $e) {
				$res->estado = false;
				$res->error = $e->getMessage();
			}
			
			echo json_encode($res);
		}

		public function VistaAvanceActividad()
		{
			ValidarAccesoProyecto($this->db);
			$res = new Resultado();

			try {
				$idProyecto = $_GET['idProyecto'];
				$idObjetivo = $_GET['idObjetivo'];
				$idProducto = $_GET['idProducto'];
				$idActividad = $_GET['idActividad'];

				$actividad = $this->ValidarAccesoActividad($idProyecto, $idObjetivo, $idProducto, $idActividad);

				$proyecto = $this->proyectoModel->BuscarPorId($idProyecto);
				$objetivo = $this->objetivoModel->BuscarPorId($idProyecto, $idObjetivo);
				$producto = $this->productoModel->BuscarPorId($idObjetivo, $idProducto);
				
				$parametro[0] = $proyecto['id_proyecto'];
				if (strlen($proyecto['nombre']) > 50) {
					$parametro[1] = substr($proyecto['nombre'], 0, 47) . "...";
				}
				else{
					$parametro[1] = $proyecto['nombre'];
				}

				$parametro[2] = $objetivo['id_objetivo'];
				if (strlen($objetivo['nombre']) > 50) {
					$parametro[3] = substr($objetivo['nombre'], 0, 47) . "...";
				}
				else{
					$parametro[3] = $objetivo['nombre'];
				}

				$parametro[4] = $producto['id_producto'];
				if (strlen($producto['nombre']) > 50) {
					$parametro[5] = substr($producto['nombre'], 0, 47) . "...";
				}
				else{
					$parametro[5] = $producto['nombre'];
				}

				$parametro[6] = $actividad['id_actividad'];
				if (strlen($actividad['nombre']) > 50) {
					$parametro[7] = substr($actividad['nombre'], 0, 47) . "...";
				}
				else{
					$parametro[7] = $actividad['nombre'];
				}

				$res->vista = requireToVar("views/partials/ReporteAvanceAccion_view.php", $parametro);
				$res->estado = true;
			} catch (Exception $e) {
				$res->estado = false;
				$res->error = $e->getMessage();
			}
			
			echo json_encode($res);
		}

		public function AvanceActividad()
		{
			ValidarAccesoProyecto($this->db);
			$res = new Resultado();

			try {
				$idProyecto = $_GET['idProyecto'];
				$idObjetivo = $_GET['idObjetivo'];
				$idProducto = $_GET['idProducto'];
				$idActividad = $_GET['idActividad'];
				$fechaIni = $_GET['fechaIni'];
				$fechaFin = $_GET['fechaFin'];

				$actividad = $this->ValidarAccesoActividad($idProyecto, $idObjetivo, $idProducto, $idActividad);

				$parametro[0] = $this->accionModel->ReporteAvancePorActividad($idActividad, $fechaIni, $fechaFin);
				$res->vista = requireToVar("views/partials/ReporteAvanceAccionDetalle_view.php", $parametro);
				$res->estado = true;
			} catch (Exception $e) {
				$res->estado = false;
				$res->error = $e->getMessage();
			}
			
			echo json_encode($res);
		}

		public function AvanceAccion()
		{
			ValidarAccesoProyecto($this->db);
			$res = new Resultado();

			try {
				$idProyecto = $_GET['idProyecto'];
				$idObjetivo = $_GET['idObjetivo'];
				$idProducto = $_GET['idProducto'];
				$idActividad = $_GET['idActividad'];
				$fechaIni = $_GET['fechaIni'];
				$fechaFin = $_GET['fechaFin'];
				$idAccion = $_GET['idAccion'];

				$this->ValidarAccesoAccion($idProyecto, $idObjetivo, $idProducto, $idActividad, $idAccion, false);

				$parametro[0] = $this->accionEjecucionModel->FiltrarPorAccion($idAccion, $fechaIni, $fechaFin);
				$res->vista = requireToVar("views/partials/ReporteAvanceAccionEjecucion_view.php", $parametro);
				$res->estado = true;
			} catch (Exception $e) {
				$res->estado = false;
				$res->error = $e->getMessage();
			}
			
			echo json_encode($res);
		}

		public function VistaReporteActividad()
		{
			try {
				$parametro[0] = $this->proyectoModel->ListarPorUsuario();
				$contenido = requireToVar("views/ReporteActividad_view.php", $parametro);
				$accesos = ListarPermiso($this->db);
				require_once("views/layouts/plantilla.php");
			}
			catch (Exception $e){
				print($e->getMessage()."\n\n".$e->getTraceAsString());
			}
		}

		public function ReporteActividad()
		{
			ValidarAccesoProyecto($this->db);
			$res = new Resultado();

			try {
				$idProyecto = $_GET['idProyecto'];
				$fechaIni = $_GET['fechaIni'];
				$fechaFin = $_GET['fechaFin'];
				
				$parametro[0] = $this->historialModel->FiltrarPorProyecto($idProyecto, $fechaIni, $fechaFin);
				$res->vista = requireToVar("views/partials/ReporteActividad_view.php", $parametro);
				$res->estado = true;
			} catch (Exception $e) {
				$res->estado = false;
				$res->error = $e->getMessage();
			}
			
			echo json_encode($res);
		}

		public function Comunidad()
		{
			try {
				$parametro[0] = $this->regionModel->Listar();
				$contenido = requireToVar("views/Comunidad_view.php", $parametro);
				$accesos = ListarPermiso($this->db);
				require_once("views/layouts/plantilla.php");
			}
			catch (Exception $e){
				print($e->getMessage()."\n\n".$e->getTraceAsString());
			}
		}

		public function TablaProvincia()
		{
			$res = new Resultado();

			try {
				$idRegion = $_GET['idRegion'];
				
				$parametro[0] = $this->provinciaModel->ListarPorRegion($idRegion);
				$res->vista = requireToVar("views/partials/TablaProvincia_view.php", $parametro);
				$res->estado = true;
			} catch (Exception $e) {
				$res->estado = false;
				$res->error = $e->getMessage();
			}
			
			echo json_encode($res);
		}

		public function TablaDistrito()
		{
			$res = new Resultado();

			try {
				$idProvincia = $_GET['idProvincia'];
				
				$parametro[0] = $this->distritoModel->ListarPorProvincia($idProvincia);
				$res->vista = requireToVar("views/partials/TablaDistrito_view.php", $parametro);
				$res->estado = true;
			} catch (Exception $e) {
				$res->estado = false;
				$res->error = $e->getMessage();
			}
			
			echo json_encode($res);
		}

		public function TablaComunidad()
		{
			$res = new Resultado();

			try {
				$idDistrito = $_GET['idDistrito'];
				
				$parametro[0] = $this->comunidadModel->ListarPorDistrito($idDistrito);
				$res->vista = requireToVar("views/partials/TablaComunidad_view.php", $parametro);
				$res->estado = true;
			} catch (Exception $e) {
				$res->estado = false;
				$res->error = $e->getMessage();
			}
			
			echo json_encode($res);
		}

		public function RegistrarRegion()
		{
			$res = new Resultado();

			try {
				$nombre = $_POST['nombre'];
				
				$this->regionModel->Registrar($nombre);

				$res->estado = true;
			} catch (Exception $e) {
				$res->estado = false;
				$res->error = $e->getMessage();
			}
			
			echo json_encode($res);
		}

		public function RegistrarProvincia()
		{
			$res = new Resultado();

			try {
				$nombre = $_POST['nombre'];
				$idRegion = $_POST['idRegion'];

				$this->provinciaModel->Registrar($nombre, $idRegion);

				$parametro[0] = $this->provinciaModel->ListarPorRegion($idRegion);
				$res->vista = requireToVar("views/partials/TablaProvincia_view.php", $parametro);
				$res->estado = true;
			} catch (Exception $e) {
				$res->estado = false;
				$res->error = $e->getMessage();
			}
			
			echo json_encode($res);
		}

		public function RegistrarDistrito()
		{
			$res = new Resultado();

			try {
				$nombre = $_POST['nombre'];
				$idProvincia = $_POST['idProvincia'];

				$this->distritoModel->Registrar($nombre, $idProvincia);

				$parametro[0] = $this->distritoModel->ListarPorProvincia($idProvincia);
				$res->vista = requireToVar("views/partials/TablaDistrito_view.php", $parametro);
				$res->estado = true;
			} catch (Exception $e) {
				$res->estado = false;
				$res->error = $e->getMessage();
			}
			
			echo json_encode($res);
		}

		public function RegistrarComunidad()
		{
			$res = new Resultado();

			try {
				$nombre = $_POST['nombre'];
				$idDistrito = $_POST['idDistrito'];

				$this->comunidadModel->Registrar($nombre, $idDistrito);

				$parametro[0] = $this->comunidadModel->ListarPorDistrito($idDistrito);
				$res->vista = requireToVar("views/partials/TablaComunidad_view.php", $parametro);
				$res->estado = true;
			} catch (Exception $e) {
				$res->estado = false;
				$res->error = $e->getMessage();
			}
			
			echo json_encode($res);
		}

		public function BuscarProvincia()
		{
			$res = new Resultado();

			try {
				$idProvincia = $_GET['idProvincia'];

				$provincia = $this->provinciaModel->BuscarPorId($idProvincia);
				if ($provincia == false) {
					throw new Exception("La provincia no existe.");
				}

				$res->nombre = $provincia['nombre'];
				$res->estado = true;
			} catch (Exception $e) {
				$res->estado = false;
				$res->error = $e->getMessage();
			}
			
			echo json_encode($res);
		}

		public function ModificarProvincia()
		{
			$res = new Resultado();

			try {
				$nombre = $_POST['nombre'];
				$idProvincia = $_POST['idProvincia'];
				$idRegion = $_POST['idRegion'];

				$this->provinciaModel->Modificar($nombre, $idProvincia);

				$parametro[0] = $this->provinciaModel->ListarPorRegion($idRegion);
				$res->vista = requireToVar("views/partials/TablaProvincia_view.php", $parametro);
				$res->estado = true;
			} catch (Exception $e) {
				$res->estado = false;
				$res->error = $e->getMessage();
			}
			
			echo json_encode($res);
		}

		public function BuscarDistrito()
		{
			$res = new Resultado();

			try {
				$idDistrito = $_GET['idDistrito'];

				$distrito = $this->distritoModel->BuscarPorId($idDistrito);
				if ($distrito == false) {
					throw new Exception("El distrito no existe.");
				}

				$res->nombre = $distrito['nombre'];
				$res->estado = true;
			} catch (Exception $e) {
				$res->estado = false;
				$res->error = $e->getMessage();
			}
			
			echo json_encode($res);
		}

		public function ModificarDistrito()
		{
			$res = new Resultado();

			try {
				$nombre = $_POST['nombre'];
				$idDistrito = $_POST['idDistrito'];
				$idProvincia = $_POST['idProvincia'];

				$this->distritoModel->Modificar($nombre, $idDistrito);

				$parametro[0] = $this->distritoModel->ListarPorProvincia($idProvincia);
				$res->vista = requireToVar("views/partials/TablaDistrito_view.php", $parametro);
				$res->estado = true;
			} catch (Exception $e) {
				$res->estado = false;
				$res->error = $e->getMessage();
			}
			
			echo json_encode($res);
		}

		public function BuscarComunidad()
		{
			$res = new Resultado();

			try {
				$idComunidad = $_GET['idComunidad'];

				$comunidad = $this->comunidadModel->BuscarPorId($idComunidad);
				if ($comunidad == false) {
					throw new Exception("La comunidad no existe.");
				}

				$res->nombre = $comunidad['nombre'];
				$res->estado = true;
			} catch (Exception $e) {
				$res->estado = false;
				$res->error = $e->getMessage();
			}
			
			echo json_encode($res);
		}

		public function ModificarComunidad()
		{
			$res = new Resultado();

			try {
				$nombre = $_POST['nombre'];
				$idComunidad = $_POST['idComunidad'];
				$idDistrito = $_POST['idDistrito'];

				$this->comunidadModel->Modificar($nombre, $idComunidad);

				$parametro[0] = $this->comunidadModel->ListarPorDistrito($idDistrito);
				$res->vista = requireToVar("views/partials/TablaComunidad_view.php", $parametro);
				$res->estado = true;
			} catch (Exception $e) {
				$res->estado = false;
				$res->error = $e->getMessage();
			}
			
			echo json_encode($res);
		}

		public function Participacion()
		{
			try {
				$parametro[0] = $this->proyectoModel->ListarPorUsuario();
				$contenido = requireToVar("views/ReporteParticipacion_view.php", $parametro);
				$accesos = ListarPermiso($this->db);
				require_once("views/layouts/plantilla.php");
			}
			catch (Exception $e){
				print($e->getMessage()."\n\n".$e->getTraceAsString());
			}
		}

		public function BuscarParticipanteComunidad()
		{
			ValidarAccesoProyecto($this->db);
			$res = new Resultado();

			try {
				$idProyecto = $_GET['idProyecto'];

				$res->personas = $this->personaModel->ListarPorProyecto($idProyecto);
				$res->comunidades = $this->comunidadModel->ListarPorProyecto($idProyecto);
				
				$res->estado = true;
			} catch (Exception $e) {
				$res->estado = false;
				$res->error = $e->getMessage();
			}
			
			echo json_encode($res);
		}

		public function ReporteParticipacion()
		{
			ValidarAccesoProyecto($this->db);
			$res = new Resultado();

			try {
				$idProyecto = $_GET['idProyecto'];
				$tipoParticipante = $_GET['tipoParticipante'];
				$datoBusqueda = $_GET['datoBusqueda'];

				if ($tipoParticipante == 'PERSONA') {
					if ($datoBusqueda == "DNI") {
						$idPersona = $_GET['dni'];
					}
					else{
						$idPersona = $_GET['nombre'];
					}

					$parametro[0] = $this->personaModel->BuscarPorId($idPersona);
					$parametro[1] = $this->actividadModel->ListarPorParticipante($idProyecto, $idPersona);
					$res->vista = requireToVar("views/partials/ReporteParticipacionPersona_view.php", $parametro);
				}
				else if ($tipoParticipante == 'FAMILIA') {
					if ($datoBusqueda == "DNI") {
						$idPersona = $_GET['dni'];
					}
					else{
						$idPersona = $_GET['nombre'];
					}

					$parametro[0] = $this->familiaModel->BuscarPorPersona($idPersona);
					if ($parametro[0] == false) {
						throw new Exception('La persona seleccionada no pertenece a ninguna familia.');
					}

					$parametro[1] = $this->actividadModel->ListarPorParticipante($idProyecto, $idPersona);
					$res->vista = requireToVar("views/partials/ReporteParticipacionFamilia_view.php", $parametro);
				}
				else if($tipoParticipante == 'COMUNIDAD'){
					if ($datoBusqueda == "COMUNIDAD") {
						$idComunidad = $_GET['comunidad'];
						$parametro[0] = $this->comunidadModel->BuscarPorId($idComunidad);
						$parametro[1] = $this->actividadModel->ListarPorComunidad($idProyecto, $idComunidad);
						$res->idComunidad = $idComunidad;
						$res->vista = requireToVar("views/partials/ReporteComunidadActividad_view.php", $parametro);
					}
				}

				$res->estado = true;
			} catch (Exception $e) {
				$res->estado = false;
				$res->error = $e->getMessage();
			}
			
			echo json_encode($res);
		}

		public function ParticipanteComunidad()
		{
			try {
				$parametro[0] = $this->proyectoModel->ListarPorUsuario();
				$contenido = requireToVar("views/ReporteParticipante_view.php", $parametro);
				$accesos = ListarPermiso($this->db);
				require_once("views/layouts/plantilla.php");
			}
			catch (Exception $e){
				print($e->getMessage()."\n\n".$e->getTraceAsString());
			}
		}

		public function BuscarActividadPorProyecto()
		{
			ValidarAccesoProyecto($this->db);
			$res = new Resultado();

			try {
				$idProyecto = $_GET['idProyecto'];

				$res->actividades = $this->actividadModel->ListarPorProyecto($idProyecto);
				
				$res->estado = true;
			} catch (Exception $e) {
				$res->estado = false;
				$res->error = $e->getMessage();
			}
			
			echo json_encode($res);
		}

		public function ReporteParticipanteComunidad()
		{
			ValidarAccesoProyecto($this->db);
			$res = new Resultado();

			try {
				$tipoParticipante = $_GET['tipoParticipante'];
				$idActividad = $_GET['idActividad'];

				if ($tipoParticipante == 'PARTICIPANTE') {
					$parametro[0] = $this->accionEjecucionParticipanteModel->ListarPorActividad($idActividad);
					$res->vista = requireToVar("views/partials/ReporteActividadParticipante_view.php", $parametro);
				}
				else if($tipoParticipante == 'COMUNIDAD'){
					$parametro[0] = $this->comunidadModel->ListarPorActividad($idActividad);
					$res->vista = requireToVar("views/partials/ReporteActividadComunidad_view.php", $parametro);
				}

				$res->estado = true;
			} catch (Exception $e) {
				$res->estado = false;
				$res->error = $e->getMessage();
			}
			
			echo json_encode($res);
		}

		public function Medicion()
		{
			try {
				$parametro[0] = $this->proyectoModel->ListarPorUsuario();
				$contenido = requireToVar("views/ReporteMedicion_view.php", $parametro);
				$accesos = ListarPermiso($this->db);
				require_once("views/layouts/plantilla.php");
			}
			catch (Exception $e){
				print($e->getMessage()."\n\n".$e->getTraceAsString());
			}
		}

		public function BuscarAccionConMedidaAdicional()
		{
			ValidarAccesoProyecto($this->db);
			$res = new Resultado();

			try {
				$idProyecto = $_GET['idProyecto'];

				$res->acciones = $this->accionModel->ListarConMedidaAdicionalPorProyecto($idProyecto);
				
				$res->estado = true;
			} catch (Exception $e) {
				$res->estado = false;
				$res->error = $e->getMessage();
			}
			
			echo json_encode($res);
		}

		public function ReporteMedicion()
		{
			ValidarAccesoProyecto($this->db);
			$res = new Resultado();

			try {
				$idAccion = $_GET['idAccion'];

				$parametro[0] = $this->accionEjecucionModel->ListarMedidaAdicionalPorAccion($idAccion);
				$res->vista = requireToVar("views/partials/ReporteMedicion_view.php", $parametro);

				$res->estado = true;
			} catch (Exception $e) {
				$res->estado = false;
				$res->error = $e->getMessage();
			}
			
			echo json_encode($res);
		}

		public function AvanceAnualActividad()
		{
			try {
				$parametro[0] = $this->proyectoModel->ListarPorUsuario();
				$contenido = requireToVar("views/ReporteAvanceAnualActividad_view.php", $parametro);
				$accesos = ListarPermiso($this->db);
				require_once("views/layouts/plantilla.php");
			}
			catch (Exception $e){
				print($e->getMessage()."\n\n".$e->getTraceAsString());
			}
		}

		public function ReporteAvanceAnualActividad()
		{
			ValidarAccesoProyecto($this->db);
			$res = new Resultado();

			try {
				$idProyecto = $_GET['idProyecto'];
				$anio = $_GET['anio'];

				$parametro[0] = $this->accionModel->ListarConAvanceAnual($idProyecto, $anio);
				$res->vista = requireToVar("views/partials/ReporteAccionConAvanceAnual_view.php", $parametro);

				$res->estado = true;
			} catch (Exception $e) {
				$res->estado = false;
				$res->error = $e->getMessage();
			}
			
			echo json_encode($res);
		}

		private function ValidarAccesoProducto($idProyecto, $idObjetivo, $idProducto)
		{
			$objetivo = $this->objetivoModel->BuscarPorId($idProyecto, $idObjetivo);
			if ($objetivo == false) {
				throw new Exception("El proyecto no existe o no esta disponible para usted.");
			}

			$producto = $this->productoModel->BuscarPorId($idObjetivo, $idProducto);
			if ($producto == false) {
				throw new Exception("El producto no existe o no esta disponible para usted. ");
			}

			return $producto;
		}

		private function ValidarAccesoActividad($idProyecto, $idObjetivo, $idProducto, $idActividad)
		{
			$this->ValidarAccesoProducto($idProyecto, $idObjetivo, $idProducto);

			$actividad = $this->actividadModel->BuscarPorId($idProducto, $idActividad);

			if ($actividad == false) {
				throw new Exception("La actividad no existe o no esta disponible para usted.");
			}

			return $actividad;
		}

		private function ValidarAccesoAccion($idProyecto, $idObjetivo, $idProducto, $idActividad, $idAccion, $edicion)
		{
			$res = new stdClass();
			$actividad = $this->ValidarAccesoActividad($idProyecto, $idObjetivo, $idProducto, $idActividad);

			if ($edicion == true) {
				if (!($actividad['estado'] == 'NUEVO' || $actividad['estado'] == 'RECHAZADO')) {
					throw new Exception("Estado de actividad incorrecto, la actividad no se puede modificar.");
				}
			}
			else{
				if (!($actividad['estado'] == 'AUTORIZADO')) {
					throw new Exception("Estado de actividad incorrecto, la actividad no esta autorizada.");
				}
			}

			$accion = $this->accionModel->BuscarPorId($idActividad, $idAccion);
			
			if ($accion == false) {
				throw new Exception("La acción no existe o no esta disponible para usted.");
			}

			$res->estadoActividad = $actividad['estado'];
			$res->accion = $accion;

			return $res;
		}
	}
?>