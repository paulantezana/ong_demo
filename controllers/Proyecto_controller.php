<?php
	require_once("models/Proyecto_model.php");
	require_once("models/Objetivo_model.php");
	require_once("models/Producto_model.php");
	require_once("models/Actividad_model.php");
	require_once("models/ActividadDetalle_model.php");
	require_once("models/Accion_model.php");
	require_once("models/AccionEjecucion_model.php");
	require_once("models/Persona_model.php");
	require_once("models/AccionEjecucionParticipante_model.php");
	require_once("models/AccionEjecucionFoto_model.php");
	require_once("models/Historial_model.php");
	require_once("models/Comunidad_model.php");

	class Proyecto
	{
		private $proyectoModel;
		private $objetivoModel;
		private $productoModel;
		private $actividadModel;
		private $actividadDetalleModel;
		private $accionModel;
		private $accionEjecucionModel;
		private $personaModel;
		private $accionEjecucionParticipanteModel;
		private $accionEjecucionFotoModel;
		private $historialModel;
		private $comunidadModel;
		protected $db;

		public function __construct($dbConexion)
		{
			ValidarSession();

			$this->db = $dbConexion;
			$this->proyectoModel = new Proyecto_model($this->db);
			$this->objetivoModel = new Objetivo_model($this->db);
			$this->productoModel = new Producto_model($this->db);
			$this->actividadModel = new Actividad_model($this->db);
			$this->actividadDetalleModel = new ActividadDetalle_model($this->db);
			$this->accionModel = new Accion_model($this->db);
			$this->accionEjecucionModel = new AccionEjecucion_model($this->db);
			$this->personaModel = new Persona_model($this->db);
			$this->accionEjecucionParticipanteModel = new AccionEjecucionParticipante_model($this->db);
			$this->accionEjecucionFotoModel = new AccionEjecucionFoto_model($this->db);
			$this->historialModel = new Historial_model($this->db);
			$this->comunidadModel = new Comunidad_model($this->db);

			ValidarAccesoProyecto($this->db);
		}

		public function BuscarProyecto()
		{
			try {
				ValidarAcceso($this->db, "menuVerProyecto");
				$idProyecto = $_GET['idProyecto'];

				$parametro[0] = $this->proyectoModel->BuscarPorId($idProyecto);
				$accesos = ListarPermiso($this->db);
				
				if ($parametro[0] == false) {
					$contenido = "No se encontro el proyecto.";
					require_once("views/layouts/plantilla.php");
				}
				else{
					$objetivos = $this->objetivoModel->ListarPorProyecto($idProyecto);

					foreach ($objetivos as $key => $objetivo) {
						$objetivos[$key]['productos'] = $this->productoModel->ListarPorObjetivo($objetivo['id_objetivo']);
					}
				
					$parametro[1] = $objetivos;
					$parametro[2] = 0;
					$parametro[3] = 0;
					$parametro[4] = $this->comunidadModel->Listar();
					$contenido = requireToVar("views/Proyecto_view.php", $parametro);
					require_once("views/layouts/plantilla.php");
				}
			}
			catch (Exception $e){
				print($e->getMessage());
			}
		}

		public function EliminarProyecto()
		{
			$res = new Resultado();

			try {
				ValidarAcceso($this->db, "opcionProyectoEliminar");
				$idProyecto = $_POST['idProyecto'];

				$this->proyectoModel->Eliminar($idProyecto);

				$res->estado = true;
			} catch (Exception $e) {
				$res->estado = false;
				$res->error = $e->getMessage();
			}
			
			echo json_encode($res);
		}

		public function RegistrarObjetivo()
		{
			$res = new Resultado();

			try {
				ValidarAcceso($this->db, "opcionObjetivoNuevo");
				$idProyecto = $_POST['idProyecto'];
				$objetivo = $_POST['objetivo'];

				$this->objetivoModel->Registrar($objetivo, $idProyecto);

				$objetivos = $this->objetivoModel->ListarPorProyecto($idProyecto);

				foreach ($objetivos as $key => $objetivo) {
					$objetivos[$key]['productos'] = $this->productoModel->ListarPorObjetivo($objetivo['id_objetivo']);
				}
			
				$res->objetivos = $objetivos;

				$res->estado = true;
			} catch (Exception $e) {
				$res->estado = false;
				$res->error = $e->getMessage();
			}
			
			echo json_encode($res);
		}

		public function BuscarObjetivo()
		{
			$res = new Resultado();

			try {
				ValidarAcceso($this->db, "opcionObjetivoModificar");
				$idProyecto = $_GET['idProyecto'];
				$idObjetivo = $_GET['idObjetivo'];

				$objetivo = $this->objetivoModel->BuscarPorId($idProyecto, $idObjetivo);
				if ($objetivo == false) {
					throw new Exception("El objetivo no existe o no esta disponible para usted.");
				}

				$res->objetivo = $objetivo;
				$res->estado = true;
			} catch (Exception $e) {
				$res->estado = false;
				$res->error = $e->getMessage();
			}
			
			echo json_encode($res);
		}

		public function EliminarObjetivo()
		{
			$res = new Resultado();

			try {
				ValidarAcceso($this->db, "opcionObjetivoEliminar");
				$idProyecto = $_POST['idProyecto'];
				$idObjetivo = $_POST['idObjetivo'];

				$objetivo = $this->objetivoModel->BuscarPorId($idProyecto, $idObjetivo);
				if ($objetivo == false) {
					throw new Exception("El objetivo no existe o no esta disponible para usted.");
				}

				$this->objetivoModel->Eliminar($idObjetivo);

				$objetivos = $this->objetivoModel->ListarPorProyecto($idProyecto);

				foreach ($objetivos as $key => $objetivo) {
					$objetivos[$key]['productos'] = $this->productoModel->ListarPorObjetivo($objetivo['id_objetivo']);
				}

				$res->objetivos = $objetivos;
				$res->estado = true;
			} catch (Exception $e) {
				$res->estado = false;
				$res->error = $e->getMessage();
			}
			
			echo json_encode($res);
		}

		public function ModificarObjetivo()
		{
			$res = new Resultado();

			try {
				ValidarAcceso($this->db, "opcionObjetivoModificar");
				$idProyecto = $_POST['idProyecto'];
				$objetivoNuevo = $_POST['objetivo'];

				$objetivo = $this->objetivoModel->BuscarPorId($idProyecto, $objetivoNuevo['idObjetivo']);
				if ($objetivo == false) {
					throw new Exception("El objetivo no existe o no esta disponible para usted.");
				}

				$this->objetivoModel->Modificar($objetivoNuevo);

				$objetivos = $this->objetivoModel->ListarPorProyecto($idProyecto);

				foreach ($objetivos as $key => $objetivo) {
					$objetivos[$key]['productos'] = $this->productoModel->ListarPorObjetivo($objetivo['id_objetivo']);
				}

				$res->objetivos = $objetivos;
				$res->estado = true;
			} catch (Exception $e) {
				$res->estado = false;
				$res->error = $e->getMessage();
			}
			
			echo json_encode($res);
		}

		public function ListarObjetivo()
		{
			$res = new Resultado();

			try {
				ValidarAcceso($this->db, "opcionObjetivoPonderar");
				$idProyecto = $_GET['idProyecto'];

				$objetivos = $this->objetivoModel->ListarPorProyecto($idProyecto);

				if (count($objetivos) > 0) {
					$parametro[0] = $objetivos;
					$res->vista = requireToVar("views/partials/TablaObjetivo_view.php", $parametro);
					$res->estado = true;
				}
				else{
					throw new Exception("El proyecto no cuenta con objetivos registrados.");
				}
			} catch (Exception $e) {
				$res->estado = false;
				$res->error = $e->getMessage();
			}
			
			echo json_encode($res);
		}

		public function ModificarPonderacionObjetivo()
		{
			$res = new Resultado();

			$this->db->beginTransaction();
			try {
				ValidarAcceso($this->db, "opcionObjetivoPonderar");
				$idProyecto = $_POST['idProyecto'];
				$listaObjetivos = $_POST['objetivos'];

				$total = 0;
				foreach ($listaObjetivos as $key => $nuevoObjetivo) {
					$objetivo = $this->objetivoModel->BuscarPorId($idProyecto, $nuevoObjetivo['idObjetivo']);
			
					if ($objetivo == false) {
						throw new Exception("Uno o mas objetivos no existen o no estan disponibles para usted.");
					}

					$this->objetivoModel->AsignarPonderacion($nuevoObjetivo['idObjetivo'], $nuevoObjetivo['ponderacion']);
					$total += $nuevoObjetivo['ponderacion'];
				}

				if ($total != 100) {
					throw new Exception("La sumatoria debe ser igual a 100.");
				}

				$avanceProyecto = $this->objetivoModel->ResumenAvance($idProyecto);
				$this->proyectoModel->ActualizarAvance($idProyecto, round($avanceProyecto['avance'],2));

				$res->avanceProyecto = round($avanceProyecto['avance'],2);

				$objetivos = $this->objetivoModel->ListarPorProyecto($idProyecto);

				foreach ($objetivos as $key => $objetivo) {
					$objetivos[$key]['productos'] = $this->productoModel->ListarPorObjetivo($objetivo['id_objetivo']);
				}

				$res->objetivos = $objetivos;
				$res->estado = true;

				$this->db->commit();
			} catch (Exception $e) {
				$this->db->rollBack();
				$res->estado = false;
				$res->error = $e->getMessage();
			}
			
			echo json_encode($res);
		}

		public function RegistrarProducto()
		{
			$res = new Resultado();

			try {
				ValidarAcceso($this->db, "opcionProductoNuevo");
				$idProyecto = $_POST['idProyecto'];
				$idObjetivo = $_POST['idObjetivo'];
				$producto = $_POST['producto'];

				$objetivo = $this->objetivoModel->BuscarPorId($idProyecto, $idObjetivo);
				if ($objetivo == false) {
					throw new Exception("El proyecto no existe o no esta disponible para usted.");
				}

				$this->productoModel->Registrar($idObjetivo, $producto);

				$productos = $this->productoModel->ListarPorObjetivo($idObjetivo);
			
				$res->productos = $productos;

				$res->estado = true;
			} catch (Exception $e) {
				$res->estado = false;
				$res->error = $e->getMessage();
			}
			
			echo json_encode($res);
		}

		public function BuscarProducto()
		{
			$res = new Resultado();

			try {
				ValidarAcceso($this->db, "opcionProductoModificar");
				$idProducto = $_GET['idProducto'];
				$idProyecto = $_GET['idProyecto'];
				$idObjetivo = $_GET['idObjetivo'];

				$producto = $this->ValidarAccesoProducto($idProyecto, $idObjetivo, $idProducto);

				$actividades = $this->actividadModel->ListarPorProducto($idProducto);

				foreach ($actividades as $key => $actividad) {
					$actividades[$key]['acciones'] = $this->accionModel->ListarPorActividad($actividad['id_actividad']);
				}

				$res->producto = $producto;
				$res->actividades = $actividades;
				$res->estado = true;
			} catch (Exception $e) {
				$res->estado = false;
				$res->error = $e->getMessage();
			}
			
			echo json_encode($res);
		}

		public function ListarProducto()
		{
			$res = new Resultado();

			try {
				ValidarAcceso($this->db, "opcionProductoPonderar");
				$idProyecto = $_GET['idProyecto'];
				$idObjetivo = $_GET['idObjetivo'];

				$objetivo = $this->objetivoModel->BuscarPorId($idProyecto, $idObjetivo);
				if ($objetivo == false) {
					throw new Exception("El proyecto no existe o no esta disponible para usted.");
				}

				$productos = $this->productoModel->ListarPorObjetivo($idObjetivo);

				if (count($productos) > 0) {
					$parametro[0] = $productos;
					$res->vista = requireToVar("views/partials/TablaProducto_view.php", $parametro);
					$res->estado = true;
				}
				else{
					throw new Exception("El objetivo no cuenta con productos registrados.");
				}
			} catch (Exception $e) {
				$res->estado = false;
				$res->error = $e->getMessage();
			}
			
			echo json_encode($res);
		}

		public function EliminarProducto()
		{
			$res = new Resultado();

			try {
				ValidarAcceso($this->db, "opcionProductoEliminar");
				$idProyecto = $_POST['idProyecto'];
				$idObjetivo = $_POST['idObjetivo'];
				$idProducto = $_POST['idProducto'];

				$this->ValidarAccesoProducto($idProyecto, $idObjetivo, $idProducto);

				$this->productoModel->Eliminar($idProducto);

				$productos = $this->productoModel->ListarPorObjetivo($idObjetivo);
			
				$res->productos = $productos;
				$res->estado = true;
			} catch (Exception $e) {
				$res->estado = false;
				$res->error = $e->getMessage();
			}
			
			echo json_encode($res);
		}

		public function ModificarProducto()
		{
			$res = new Resultado();

			try {
				ValidarAcceso($this->db, "opcionProductoModificar");
				$idProyecto = $_POST['idProyecto'];
				$idObjetivo = $_POST['idObjetivo'];
				$productoNuevo = $_POST['producto'];

				$this->ValidarAccesoProducto($idProyecto, $idObjetivo, $productoNuevo['idProducto']);

				$this->productoModel->Modificar($productoNuevo);

				$res->productos = $this->productoModel->ListarPorObjetivo($idObjetivo);
				$res->estado = true;
			} catch (Exception $e) {
				$res->estado = false;
				$res->error = $e->getMessage();
			}
			
			echo json_encode($res);
		}

		public function ModificarPonderacionProducto()
		{
			$res = new Resultado();

			$this->db->beginTransaction();

			try {
				ValidarAcceso($this->db, "opcionProductoPonderar");
				$idProyecto = $_POST['idProyecto'];
				$idObjetivo = $_POST['idObjetivo'];
				$ListarProducto = $_POST['productos'];

				$objetivo = $this->objetivoModel->BuscarPorId($idProyecto, $idObjetivo);
				if ($objetivo == false) {
					throw new Exception("El proyecto no existe o no esta disponible para usted.");
				}

				$total = 0;

				foreach ($ListarProducto as $key => $nuevoProducto) {
					$producto = $this->productoModel->BuscarPorId($idObjetivo, $nuevoProducto['idProducto']);
			
					if ($producto == false) {
						throw new Exception("Uno o mas productos no existen o no estan disponibles para usted.");
					}

					$this->productoModel->AsignarPonderacion($nuevoProducto['idProducto'], $nuevoProducto['ponderacion']);
					$total += $nuevoProducto['ponderacion'];
				}

				if ($total != 100) {
					throw new Exception("La sumatoria debe ser igual a 100.");
				}

				$productos = $this->productoModel->ListarPorObjetivo($idObjetivo);

				$avanceObjetivo = $this->productoModel->ResumenAvance($idObjetivo);
				$this->objetivoModel->ActualizarAvance($idObjetivo, round($avanceObjetivo['avance'],2));

				$avanceProyecto = $this->objetivoModel->ResumenAvance($idProyecto);
				$this->proyectoModel->ActualizarAvance($idProyecto, round($avanceProyecto['avance'],2));

				$res->avanceObjetivo = round($avanceObjetivo['avance'],2);
				$res->avanceProyecto = round($avanceProyecto['avance'],2);

				$res->productos = $productos;
				$res->estado = true;

				$this->db->commit();
			} catch (Exception $e) {
				$this->db->rollBack();
				$res->estado = false;
				$res->error = $e->getMessage();
			}
			
			echo json_encode($res);
		}

		public function BuscarActividad()
		{
			$res = new Resultado();

			try {
				ValidarAcceso($this->db, "opcionActividadModificar");
				$idProyecto = $_GET['idProyecto'];
				$idObjetivo = $_GET['idObjetivo'];
				$idProducto = $_GET['idProducto'];
				$idActividad = $_GET['idActividad'];

				$actividad = $this->ValidarAccesoActividad($idProyecto, $idObjetivo, $idProducto, $idActividad);

				if (!($actividad['estado'] == 'NUEVO' || $actividad['estado'] == 'RECHAZADO')) {
					throw new Exception("Estado de actividad incorrecto, la actividad no se puede modificar.");
				}

				$res->actividad = $actividad;
				$res->estado = true;
			} catch (Exception $e) {
				$res->estado = false;
				$res->error = $e->getMessage();
			}
			
			echo json_encode($res);
		}

		public function ListarActividad()
		{
			$res = new Resultado();

			try {
				ValidarAcceso($this->db, "opcionActividadPonderar");
				$idProyecto = $_GET['idProyecto'];
				$idObjetivo = $_GET['idObjetivo'];
				$idProducto = $_GET['idProducto'];

				$this->ValidarAccesoProducto($idProyecto, $idObjetivo, $idProducto);

				$actividades = $this->actividadModel->ListarAutorizadoPorProducto($idProducto);

				if (count($actividades) > 0) {
					$parametro[0] = $actividades;
					$res->vista = requireToVar("views/partials/TablaActividad_view.php", $parametro);
					$res->estado = true;
				}
				else{
					throw new Exception("El producto no cuenta con actividades registradas.");
				}
			} catch (Exception $e) {
				$res->estado = false;
				$res->error = $e->getMessage();
			}
			
			echo json_encode($res);
		}

		public function RegistrarActividad()
		{
			$res = new Resultado();
			$this->db->beginTransaction();

			try {
				ValidarAcceso($this->db, "opcionActividadNuevo");
				$idProyecto = $_POST['idProyecto'];
				$idObjetivo = $_POST['idObjetivo'];
				$idProducto = $_POST['idProducto'];
				$actividadNueva = $_POST['actividad'];

				$this->ValidarAccesoProducto($idProyecto, $idObjetivo, $idProducto);

				$this->actividadModel->ValidarCodigo($actividadNueva['codigo'], 0);
				$idActividad = $this->actividadModel->Registrar($idProducto, $actividadNueva);
				$this->actividadDetalleModel->Registrar($idActividad, 'NUEVO', '');
				$this->db->commit();

				$actividades = $this->actividadModel->ListarPorProducto($idProducto);

				foreach ($actividades as $key => $actividad) {
					$actividades[$key]['acciones'] = $this->accionModel->ListarPorActividad($actividad['id_actividad']);
				}
			
				$res->actividades = $actividades;
				$res->estado = true;
			} catch (Exception $e) {
				$this->db->rollBack();
				$res->estado = false;
				$res->error = $e->getMessage();
			}
			
			echo json_encode($res);
		}

		public function SolicitarAutorizacion()
		{
			$res = new Resultado();

			try {
				ValidarAcceso($this->db, "opcionActividadSolicitar");
				$idProyecto = $_POST['idProyecto'];
				$idObjetivo = $_POST['idObjetivo'];
				$idProducto = $_POST['idProducto'];
				$idActividad = $_POST['idActividad'];

				$actividad = $this->ValidarAccesoActividad($idProyecto, $idObjetivo, $idProducto, $idActividad);

				$acciones = $this->accionModel->ListarPorActividad($idActividad);

				$total = 0;

				foreach ($acciones as $key => $accion) {
					if ($accion['ponderacion'] <= 0) {
						throw new Exception("Todas las acciones deben tener una ponderacion mayor a cero.");
					}

					$total += $accion['ponderacion'];

					if ($accion['cantidad_ejecucion'] > 0) {
						if (($accion['cuantitativa'] == 1 && $accion['meta_cuantitativa'] > $accion['metaEjecucion'])
							|| ($accion['cuantitativa'] == 0 && 100 > $accion['metaEjecucion'])) {
							throw new Exception("Una o mas acciones requieren programar su ejecución al 100%.");
						}
					}
					else{
						throw new Exception("Una o mas acciones no tienen ejecuciones programadas.");
					}
				}

				if ($total != 100) {
					throw new Exception("La sumatoria de la ponderación de las acciones debe ser igual a 100.");
				}

				$this->actividadDetalleModel->Registrar($idActividad, 'SOLICITADO', '');

				$actividades = $this->actividadModel->ListarPorProducto($idProducto);

				foreach ($actividades as $key => $actividad) {
					$actividades[$key]['acciones'] = $this->accionModel->ListarPorActividad($actividad['id_actividad']);
				}
			
				$res->actividades = $actividades;
				$res->estado = true;
			} catch (Exception $e) {
				$res->estado = false;
				$res->error = $e->getMessage();
			}
			
			echo json_encode($res);
		}

		public function RechazarActividad()
		{
			$res = new Resultado();

			try {
				ValidarAcceso($this->db, "opcionActividadRechazar");
				$idProyecto = $_POST['idProyecto'];
				$idObjetivo = $_POST['idObjetivo'];
				$idProducto = $_POST['idProducto'];
				$idActividad = $_POST['idActividad'];
				$observacion = $_POST['observacion'];

				$actividad = $this->ValidarAccesoActividad($idProyecto, $idObjetivo, $idProducto, $idActividad);

				if (!($actividad['estado'] == 'SOLICITADO' || $actividad['estado'] == 'AUTORIZADO')) {
					throw new Exception("Estado de actividad incorrecto, la actividad no se puede rechazar.");
				}

				$this->actividadDetalleModel->Registrar($idActividad, 'RECHAZADO', $observacion);

				$actividades = $this->actividadModel->ListarPorProducto($idProducto);

				foreach ($actividades as $key => $actividad) {
					$actividades[$key]['acciones'] = $this->accionModel->ListarPorActividad($actividad['id_actividad']);
				}
			
				$res->actividades = $actividades;
				$res->estado = true;
			} catch (Exception $e) {
				$res->estado = false;
				$res->error = $e->getMessage();
			}
			
			echo json_encode($res);
		}

		public function AutorizarActividad()
		{
			$res = new Resultado();

			try {
				ValidarAcceso($this->db, "opcionActividadAutorizar");
				$idProyecto = $_POST['idProyecto'];
				$idObjetivo = $_POST['idObjetivo'];
				$idProducto = $_POST['idProducto'];
				$idActividad = $_POST['idActividad'];

				$actividad = $this->ValidarAccesoActividad($idProyecto, $idObjetivo, $idProducto, $idActividad);

				if (!($actividad['estado'] == 'SOLICITADO')) {
					throw new Exception("Estado de actividad incorrecto, la actividad no se puede modificar.");
				}

				$acciones = $this->accionModel->ListarPorActividad($idActividad);

				$total = 0;

				foreach ($acciones as $key => $accion) {
					if ($accion['ponderacion'] <= 0) {
						throw new Exception("Todas las acciones deben tener una ponderacion mayor a cero.");
					}

					$total += $accion['ponderacion'];

					if ($accion['cantidad_ejecucion'] > 0) {
						if (($accion['cuantitativa'] == 1 && $accion['meta_cuantitativa'] > $accion['metaEjecucion'])
							|| ($accion['cuantitativa'] == 0 && 100 > $accion['metaEjecucion'])) {
							throw new Exception("Una o mas acciones requieren programar su ejecución al 100%.");
						}
					}
					else{
						throw new Exception("Una o mas acciones no tienen ejecuciones programadas.");
					}
				}

				if ($total != 100) {
					throw new Exception("La sumatoria de la ponderación de las acciones debe ser igual a 100.");
				}

				$this->actividadDetalleModel->Registrar($idActividad, 'AUTORIZADO', '');

				$actividades = $this->actividadModel->ListarPorProducto($idProducto);

				foreach ($actividades as $key => $actividad) {
					$actividades[$key]['acciones'] = $this->accionModel->ListarPorActividad($actividad['id_actividad']);
				}
			
				$res->actividades = $actividades;
				$res->estado = true;
			} catch (Exception $e) {
				$res->estado = false;
				$res->error = $e->getMessage();
			}
			
			echo json_encode($res);
		}

		public function EliminarActividad()
		{
			$res = new Resultado();

			try {
				ValidarAcceso($this->db, "opcionActividadEliminar");
				$idProyecto = $_POST['idProyecto'];
				$idObjetivo = $_POST['idObjetivo'];
				$idProducto = $_POST['idProducto'];
				$idActividad = $_POST['idActividad'];

				$actividad = $this->ValidarAccesoActividad($idProyecto, $idObjetivo, $idProducto, $idActividad);

				if (!($actividad['estado'] == 'NUEVO' || $actividad['estado'] == 'RECHAZADO')) {
					throw new Exception("Estado de actividad incorrecto, la actividad no se puede eliminar.");
				}

				$this->actividadModel->Eliminar($idActividad);
				$this->actividadDetalleModel->Registrar($idActividad, 'ELIMINADO', '');

				$actividades = $this->actividadModel->ListarPorProducto($idProducto);

				foreach ($actividades as $key => $actividad) {
					$actividades[$key]['acciones'] = $this->accionModel->ListarPorActividad($actividad['id_actividad']);
				}
			
				$res->actividades = $actividades;
				$res->estado = true;
			} catch (Exception $e) {
				$res->estado = false;
				$res->error = $e->getMessage();
			}
			
			echo json_encode($res);
		}

		public function ModificarActividad()
		{
			$res = new Resultado();

			try {
				ValidarAcceso($this->db, "opcionActividadModificar");
				$idProyecto = $_POST['idProyecto'];
				$idObjetivo = $_POST['idObjetivo'];
				$idProducto = $_POST['idProducto'];
				$actividadNueva = $_POST['actividad'];

				$actividad = $this->ValidarAccesoActividad($idProyecto, $idObjetivo, $idProducto, $actividadNueva['idActividad']);

				if (!($actividad['estado'] == 'NUEVO' || $actividad['estado'] == 'RECHAZADO')) {
					throw new Exception("Estado de actividad incorrecto, la actividad no se puede modificar.");
				}

				$this->actividadModel->ValidarCodigo($actividadNueva['codigo'], $actividadNueva['idActividad']);

				$this->actividadModel->Modificar($actividadNueva);
				// $this->actividadDetalleModel->Registrar($idActividad, 'ELIMINADO', '');

				$actividades = $this->actividadModel->ListarPorProducto($idProducto);

				foreach ($actividades as $key => $actividad) {
					$actividades[$key]['acciones'] = $this->accionModel->ListarPorActividad($actividad['id_actividad']);
				}
			
				$res->actividades = $actividades;
				$res->estado = true;
			} catch (Exception $e) {
				$res->estado = false;
				$res->error = $e->getMessage();
			}
			
			echo json_encode($res);
		}

		public function ModificarPonderacionActividad()
		{
			$res = new Resultado();

			$this->db->beginTransaction();

			try {
				ValidarAcceso($this->db, "opcionActividadPonderar");
				$idProyecto = $_POST['idProyecto'];
				$idObjetivo = $_POST['idObjetivo'];
				$idProducto = $_POST['idProducto'];
				$listaActividad = $_POST['actividades'];

				$this->ValidarAccesoProducto($idProyecto, $idObjetivo, $idProducto);

				$total = 0;

				foreach ($listaActividad as $key => $nuevaActividad) {
					$actividad = $this->actividadModel->BuscarPorId($idProducto, $nuevaActividad['idActividad']);
			
					if ($actividad == false) {
						throw new Exception("Una o mas actividades no existen o no estan disponibles para usted.");
					}

					$this->actividadModel->AsignarPonderacion($nuevaActividad['idActividad'], $nuevaActividad['ponderacion']);
					$total += $nuevaActividad['ponderacion'];
				}

				if ($total != 100) {
					throw new Exception("La sumatoria debe ser igual a 100.");
				}

				$actividades = $this->actividadModel->ListarPorProducto($idProducto);

				foreach ($actividades as $key => $actividad) {
					$actividades[$key]['acciones'] = $this->accionModel->ListarPorActividad($actividad['id_actividad']);
				}
				
				$avanceProducto = $this->actividadModel->ResumenAvance($idProducto);
				$this->productoModel->ActualizarAvance($idProducto, round($avanceProducto['avance'],2));

				$avanceObjetivo = $this->productoModel->ResumenAvance($idObjetivo);
				$this->objetivoModel->ActualizarAvance($idObjetivo, round($avanceObjetivo['avance'],2));

				$avanceProyecto = $this->objetivoModel->ResumenAvance($idProyecto);
				$this->proyectoModel->ActualizarAvance($idProyecto, round($avanceProyecto['avance'],2));

				$res->avanceProducto = round($avanceProducto['avance'],2);
				$res->avanceObjetivo = round($avanceObjetivo['avance'],2);
				$res->avanceProyecto = round($avanceProyecto['avance'],2);

				$res->actividades = $actividades;
				$res->estado = true;

				$this->db->commit();
			} catch (Exception $e) {
				$this->db->rollBack();
				$res->estado = false;
				$res->error = $e->getMessage();
			}
			
			echo json_encode($res);
		}

		public function RegistrarAccion()
		{
			$res = new Resultado();

			try {
				ValidarAcceso($this->db, "opcionAccionNuevo");
				$idProyecto = $_POST['idProyecto'];
				$idObjetivo = $_POST['idObjetivo'];
				$idProducto = $_POST['idProducto'];
				$idActividad = $_POST['idActividad'];
				$accionNueva = $_POST['accion'];

				$actividad = $this->ValidarAccesoActividad($idProyecto, $idObjetivo, $idProducto, $idActividad);

				if (!($actividad['estado'] == 'NUEVO' || $actividad['estado'] == 'RECHAZADO')) {
					throw new Exception("Estado de actividad incorrecto, la actividad no se puede modificar.");
				}
				
				$this->accionModel->Registrar($idActividad, $accionNueva);

				$acciones = $this->accionModel->ListarPorActividad($idActividad);
				
				$res->estadoActividad = $actividad['estado'];
				$res->acciones = $acciones;
				$res->estado = true;
			} catch (Exception $e) {
				$res->estado = false;
				$res->error = $e->getMessage();
			}
			
			echo json_encode($res);
		}

		public function ListarAccion()
		{
			$res = new Resultado();

			try {
				ValidarAcceso($this->db, "opcionAccionPonderar");
				$idProyecto = $_GET['idProyecto'];
				$idObjetivo = $_GET['idObjetivo'];
				$idProducto = $_GET['idProducto'];
				$idActividad = $_GET['idActividad'];

				$this->ValidarAccesoActividad($idProyecto, $idObjetivo, $idProducto, $idActividad);

				$acciones = $this->accionModel->ListarPorActividad($idActividad);

				if (count($acciones) > 0) {
					$parametro[0] = $acciones;
					$res->vista = requireToVar("views/partials/TablaAccion_view.php", $parametro);
					$res->estado = true;
				}
				else{
					throw new Exception("La actividad no cuenta con acciones registradas.");
				}
			} catch (Exception $e) {
				$res->estado = false;
				$res->error = $e->getMessage();
			}
			
			echo json_encode($res);
		}

		public function BuscarAccion()
		{
			$res = new Resultado();

			try {
				ValidarAcceso($this->db, "opcionAccionModificar");
				$idProyecto = $_GET['idProyecto'];
				$idObjetivo = $_GET['idObjetivo'];
				$idProducto = $_GET['idProducto'];
				$idActividad = $_GET['idActividad'];
				$idAccion = $_GET['idAccion'];

				$resValidacion = $this->ValidarAccesoAccion($idProyecto, $idObjetivo, $idProducto, $idActividad, $idAccion, true);

				$res->accion = $resValidacion->accion;
				$res->estado = true;
			} catch (Exception $e) {
				$res->estado = false;
				$res->error = $e->getMessage();
			}
			
			echo json_encode($res);
		}

		public function ModificarAccion()
		{
			$res = new Resultado();

			try {
				ValidarAcceso($this->db, "opcionAccionModificar");
				$idProyecto = $_POST['idProyecto'];
				$idObjetivo = $_POST['idObjetivo'];
				$idProducto = $_POST['idProducto'];
				$idActividad = $_POST['idActividad'];
				$accionNueva = $_POST['accion'];

				$resValidacion = $this->ValidarAccesoAccion($idProyecto, $idObjetivo, $idProducto, $idActividad, $accionNueva['idAccion'], true);

				$this->accionModel->Modificar($accionNueva);

				$acciones = $this->accionModel->ListarPorActividad($idActividad);
				
				$res->estadoActividad = $resValidacion->estadoActividad;
				$res->acciones = $acciones;
				$res->estado = true;
			} catch (Exception $e) {
				$res->estado = false;
				$res->error = $e->getMessage();
			}
			
			echo json_encode($res);
		}

		public function EliminarAccion()
		{
			$res = new Resultado();

			try {
				ValidarAcceso($this->db, "opcionAccionEliminar");
				$idProyecto = $_POST['idProyecto'];
				$idObjetivo = $_POST['idObjetivo'];
				$idProducto = $_POST['idProducto'];
				$idActividad = $_POST['idActividad'];
				$idAccion = $_POST['idAccion'];

				$resValidacion = $this->ValidarAccesoAccion($idProyecto, $idObjetivo, $idProducto, $idActividad, $idAccion, true);

				$this->accionModel->Eliminar($idAccion);

				$acciones = $this->accionModel->ListarPorActividad($idActividad);
				
				$res->estadoActividad = $resValidacion->estadoActividad;
				$res->acciones = $acciones;
				$res->estado = true;
			} catch (Exception $e) {
				$res->estado = false;
				$res->error = $e->getMessage();
			}
			
			echo json_encode($res);
		}

		public function ModificarPonderacionAccion()
		{
			$res = new Resultado();

			$this->db->beginTransaction();

			try {
				ValidarAcceso($this->db, "opcionAccionPonderar");
				$idProyecto = $_POST['idProyecto'];
				$idObjetivo = $_POST['idObjetivo'];
				$idProducto = $_POST['idProducto'];
				$idActividad = $_POST['idActividad'];
				$listaAccion = $_POST['acciones'];

				$actividad = $this->ValidarAccesoActividad($idProyecto, $idObjetivo, $idProducto, $idActividad);

				if (!($actividad['estado'] == 'NUEVO' || $actividad['estado'] == 'RECHAZADO')) {
					throw new Exception("Estado de actividad incorrecto, la actividad no se puede modificar.");
				}

				$total = 0;

				foreach ($listaAccion as $key => $nuevaActividad) {
					$accion = $this->accionModel->BuscarPorId($idActividad, $nuevaActividad['idAccion']);
			
					if ($accion == false) {
						throw new Exception("Una o mas acciones no existen o no estan disponibles para usted.");
					}

					$this->accionModel->AsignarPonderacion($nuevaActividad['idAccion'], $nuevaActividad['ponderacion']);
					$total += $nuevaActividad['ponderacion'];
				}

				if ($total != 100) {
					throw new Exception("La sumatoria debe ser igual a 100.");
				}

				$acciones = $this->accionModel->ListarPorActividad($idActividad);
				$res->estadoActividad = $actividad['estado'];
				$res->acciones = $acciones;
				$res->estado = true;

				$this->db->commit();
			} catch (Exception $e) {
				$this->db->rollBack();
				$res->estado = false;
				$res->error = $e->getMessage();
			}
			
			echo json_encode($res);
		}

		public function RegistrarEjecucionProgramada()
		{
			$res = new Resultado();

			$this->db->beginTransaction();
			try {
				ValidarAcceso($this->db, "opcionEjecucionNuevo");
				$idProyecto = $_POST['idProyecto'];
				$idObjetivo = $_POST['idObjetivo'];
				$idProducto = $_POST['idProducto'];
				$idActividad = $_POST['idActividad'];
				$idAccion = $_POST['idAccion'];
				$ejecucion = $_POST['ejecucion'];

				$resValidacion = $this->ValidarAccesoAccion($idProyecto, $idObjetivo, $idProducto, $idActividad, $idAccion, true);

				$accionTotalizada = $this->accionEjecucionModel->ResumenMeta($idAccion, 0);

				if ($accionTotalizada['cuantitativa'] == 1) {
					$metaPendiente = floatval($accionTotalizada['meta_cuantitativa']) - floatval($accionTotalizada['meta']);
				}
				else{
					$metaPendiente = 100 - floatval($accionTotalizada['meta']);
				}

				if ($metaPendiente < $ejecucion['meta']) {
					throw new Exception("Meta incorrecta, la meta pendiente de programación es : ". $metaPendiente);
				}

				$this->accionEjecucionModel->Registrar($idAccion, $ejecucion);

				$parametro[0] = $this->accionEjecucionModel->ListarPorAccion($idAccion);
				$parametro[1] = $resValidacion->accion['requiere_dato_adicional'];
				$parametro[2] = $resValidacion->estadoActividad;
				$parametro[3] = $resValidacion->accion['cuantitativa'];

				$res->nombreAccion = $resValidacion->accion['nombre'];
				$res->vista = requireToVar("views/partials/TablaEjecucion_view.php", $parametro);
				$res->estado = true;
				$res->estadoActividad = $resValidacion->estadoActividad;
				$res->acciones = $this->accionModel->ListarPorActividad($idActividad);

				$this->db->commit();
			} catch (Exception $e) {
				$this->db->rollBack();
				$res->estado = false;
				$res->error = $e->getMessage();
			}
			
			echo json_encode($res);
		}

		public function ModificarEjecucionProgramada()
		{
			$res = new Resultado();

			$this->db->beginTransaction();
			try {
				ValidarAcceso($this->db, "opcionEjecucionModificar");
				$idProyecto = $_POST['idProyecto'];
				$idObjetivo = $_POST['idObjetivo'];
				$idProducto = $_POST['idProducto'];
				$idActividad = $_POST['idActividad'];
				$idAccion = $_POST['idAccion'];
				$ejecucion = $_POST['ejecucion'];

				$resValidacion = $this->ValidarAccesoEjecucion($idProyecto, $idObjetivo, $idProducto, $idActividad, $idAccion, true, $ejecucion['idAccionEjecucion']);

				$accionTotalizada = $this->accionEjecucionModel->ResumenMeta($idAccion, $ejecucion['idAccionEjecucion']);

				if ($accionTotalizada['cuantitativa'] == 1) {
					$metaPendiente = floatval($accionTotalizada['meta_cuantitativa']) - floatval($accionTotalizada['meta']);
				}
				else{
					$metaPendiente = 100 - floatval($accionTotalizada['meta']);
				}

				if ($metaPendiente < $ejecucion['meta']) {
					throw new Exception("Meta incorrecta, la meta pendiente de programación es : ". $metaPendiente);
				}

				$this->accionEjecucionModel->Modificar($ejecucion);
				
				$parametro[0] = $this->accionEjecucionModel->ListarPorAccion($idAccion);
				$parametro[1] = $resValidacion->accion['requiere_dato_adicional'];
				$parametro[2] = $resValidacion->estadoActividad;
				$parametro[3] = $resValidacion->accion['cuantitativa'];

				$res->vista = requireToVar("views/partials/TablaEjecucion_view.php", $parametro);
				$res->estado = true;
				$res->estado = true;
				$this->db->commit();
			} catch (Exception $e) {
				$this->db->rollBack();
				$res->estado = false;
				$res->error = $e->getMessage();
			}
			
			echo json_encode($res);
		}
		
		public function EliminarEjecucionProgramada()
		{
			$res = new Resultado();

			$this->db->beginTransaction();
			try {
				ValidarAcceso($this->db, "opcionEjecucionEliminar");
				$idProyecto = $_POST['idProyecto'];
				$idObjetivo = $_POST['idObjetivo'];
				$idProducto = $_POST['idProducto'];
				$idActividad = $_POST['idActividad'];
				$idAccion = $_POST['idAccion'];
				$idAccionEjecucion = $_POST['idAccionEjecucion'];

				$resValidacion = $this->ValidarAccesoEjecucion($idProyecto, $idObjetivo, $idProducto, $idActividad, $idAccion, true, $idAccionEjecucion);

				$this->accionEjecucionModel->Eliminar($idAccionEjecucion);
				
				$parametro[0] = $this->accionEjecucionModel->ListarPorAccion($idAccion);
				$parametro[1] = $resValidacion->accion['requiere_dato_adicional'];
				$parametro[2] = $resValidacion->accion['cuantitativa'];
				$parametro[3] = $resValidacion->accion['cuantitativa'];

				$res->vista = requireToVar("views/partials/TablaEjecucion_view.php", $parametro);
				$res->estadoActividad = $resValidacion->estadoActividad;
				$res->acciones = $this->accionModel->ListarPorActividad($idActividad);
				$res->estado = true;

				$this->db->commit();
			} catch (Exception $e) {
				$this->db->rollBack();
				$res->estado = false;
				$res->error = $e->getMessage();
			}
			
			echo json_encode($res);
		}

		public function RegistrarEjecucionAvance()
		{
			$res = new Resultado();

			$this->db->beginTransaction();
			try {
				ValidarAcceso($this->db, "opcionEjecucionAvance");
				$idProyecto = $_POST['idProyecto'];
				$idObjetivo = $_POST['idObjetivo'];
				$idProducto = $_POST['idProducto'];
				$idActividad = $_POST['idActividad'];
				$idAccion = $_POST['idAccion'];
				$ejecucion = $_POST['ejecucion'];

				$resValidacion = $this->ValidarAccesoEjecucion($idProyecto, $idObjetivo, $idProducto, $idActividad, $idAccion, false, $ejecucion['idAccionEjecucion']);
				if ($resValidacion->accion['requiere_dato_adicional'] == 0) {
					$ejecucion['medidaAdicional'] = 0;
				}

				$this->accionEjecucionModel->RegistrarEjecucion($ejecucion);

				$accionTotalizada = $this->accionEjecucionModel->ResumenAvance($idAccion);
				$this->accionModel->ActualizarAvance($idAccion, $accionTotalizada['avance']);

				$avanceActividad = $this->accionModel->ResumenAvance($idActividad);
				$this->actividadModel->ActualizarAvance($idActividad, round($avanceActividad['avance'],2));

				$avanceProducto = $this->actividadModel->ResumenAvance($idProducto);
				$this->productoModel->ActualizarAvance($idProducto, round($avanceProducto['avance'],2));

				$avanceObjetivo = $this->productoModel->ResumenAvance($idObjetivo);
				$this->objetivoModel->ActualizarAvance($idObjetivo, round($avanceObjetivo['avance'],2));

				$avanceProyecto = $this->objetivoModel->ResumenAvance($idProyecto);
				$this->proyectoModel->ActualizarAvance($idProyecto, round($avanceProyecto['avance'],2));

				if ($accionTotalizada['cuantitativa'] == 0) {
					$res->avanceAccion = floatval($accionTotalizada['avance']);
				}
				else{
					$res->avanceAccion = floatval($accionTotalizada['avance']) / $accionTotalizada['meta_cuantitativa'] * 100;
				}

				$this->historialModel->Registrar('Registrar avance', $ejecucion['idAccionEjecucion']);

				$res->ejecutado = round(floatval($accionTotalizada['avance']), 2);
				$res->avanceAccion = round($res->avanceAccion, 2);
				$res->avanceActividad = round($avanceActividad['avance'],2);
				$res->avanceProducto = round($avanceProducto['avance'],2);
				$res->avanceObjetivo = round($avanceObjetivo['avance'],2);
				$res->avanceProyecto = round($avanceProyecto['avance'],2);

				$parametro[0] = $this->accionEjecucionModel->ListarPorAccion($idAccion);
				$parametro[1] = $resValidacion->accion['requiere_dato_adicional'];
				$parametro[2] = $resValidacion->estadoActividad;
				$parametro[3] = $resValidacion->accion['cuantitativa'];
				$res->vista = requireToVar("views/partials/TablaEjecucion_view.php", $parametro);

				$res->estado = true;
				$this->db->commit();
			} catch (Exception $e) {
				$this->db->rollBack();
				$res->estado = false;
				$res->error = $e->getMessage();
			}
			
			echo json_encode($res);
		}

		public function ListarAccionEjecucion()
		{
			$res = new Resultado();

			try {
				$idProyecto = $_GET['idProyecto'];
				$idObjetivo = $_GET['idObjetivo'];
				$idProducto = $_GET['idProducto'];
				$idActividad = $_GET['idActividad'];
				$idAccion = $_GET['idAccion'];

				$resValidacion = $this->ValidarAccesoAccionSinEstado($idProyecto, $idObjetivo, $idProducto, $idActividad, $idAccion);

				$ejecuciones = $this->accionEjecucionModel->ListarPorAccion($idAccion);

				$parametro[0] = $ejecuciones;
				$parametro[1] = $resValidacion->accion['requiere_dato_adicional'];
				$parametro[2] = $resValidacion->estadoActividad;
				$parametro[3] = $resValidacion->accion['cuantitativa'];

				$res->nombreAccion = $resValidacion->accion['nombre'];
				$res->vista = requireToVar("views/partials/TablaEjecucion_view.php", $parametro);
				$res->estado = true;
			} catch (Exception $e) {
				$res->estado = false;
				$res->error = $e->getMessage();
			}
			
			echo json_encode($res);
		}

		public function BuscarAccionEjecucion()
		{
			$res = new Resultado();

			try {
				$idProyecto = $_GET['idProyecto'];
				$idObjetivo = $_GET['idObjetivo'];
				$idProducto = $_GET['idProducto'];
				$idActividad = $_GET['idActividad'];
				$idAccion = $_GET['idAccion'];
				$idAccionEjecucion = $_GET['idAccionEjecucion'];

				$resValidacion = $this->ValidarAccesoEjecucionSinEstado($idProyecto, $idObjetivo, $idProducto, $idActividad, $idAccion, $idAccionEjecucion);

				$res->ejecucion = $resValidacion->ejecucion;
				$res->participacionFamiliar = $resValidacion->accion['participacion_familiar'];
				$res->unidadMeta = $resValidacion->accion['unidad_meta'];
				$res->unidadDatoAdicional = $resValidacion->accion['unidad_dato_adicional'];
				$res->estado = true;
			} catch (Exception $e) {
				$res->estado = false;
				$res->error = $e->getMessage();
			}
			
			echo json_encode($res);
		}

		public function ListarParticipante()
		{
			$res = new Resultado();

			try {
				$idProyecto = $_GET['idProyecto'];
				$idObjetivo = $_GET['idObjetivo'];
				$idProducto = $_GET['idProducto'];
				$idActividad = $_GET['idActividad'];
				$idAccion = $_GET['idAccion'];
				$idAccionEjecucion = $_GET['idAccionEjecucion'];

				$this->ValidarAccesoEjecucion($idProyecto, $idObjetivo, $idProducto, $idActividad, $idAccion, false, $idAccionEjecucion);

				$participacionFamiliar = $this->accionModel->PerticipacionFamiliarPorAccionEjecucion($idAccionEjecucion);

				if ($participacionFamiliar === false) {
					throw new Exception("Error al acceder a esta accion, es posible que no se encuentre disponible.");
				}

				$parametro[0] = $this->accionEjecucionParticipanteModel->ListarPorEjecucion($idAccionEjecucion);
				$parametro[1] = $participacionFamiliar;

				$res->vista = requireToVar("views/partials/TablaParticipante_view.php", $parametro);
				$res->participacionFamiliar = $participacionFamiliar;
				$res->estado = true;
			} catch (Exception $e) {
				$res->estado = false;
				$res->error = $e->getMessage();
			}
			
			echo json_encode($res);
		}

		public function RegistrarParticipante()
		{
			$res = new Resultado();

			$this->db->beginTransaction();
			try {
				ValidarAcceso($this->db, "opcionEjecucionParticipante");
				$idProyecto = $_POST['idProyecto'];
				$idObjetivo = $_POST['idObjetivo'];
				$idProducto = $_POST['idProducto'];
				$idActividad = $_POST['idActividad'];
				$idAccion = $_POST['idAccion'];
				$idAccionEjecucion = $_POST['idAccionEjecucion'];
				$participante = $_POST['participante'];

				$resValidacion = $this->ValidarAccesoEjecucion($idProyecto, $idObjetivo, $idProducto, $idActividad, $idAccion, false, $idAccionEjecucion);

				$idParticipante = $this->accionEjecucionParticipanteModel->Registrar($idAccionEjecucion, $participante);

				$this->historialModel->Registrar('Registrar participante', $idAccionEjecucion);

				$res->idParticipante = $idParticipante;
				$res->estado = true;
				$this->db->commit();
			} catch (Exception $e) {
				$this->db->rollBack();
				$res->estado = false;
				$res->error = $e->getMessage();
			}
			
			echo json_encode($res);
		}

		public function ModficicarParticianteRol()
		{
			$res = new Resultado();

			$this->db->beginTransaction();
			try {
				ValidarAcceso($this->db, "opcionEjecucionParticipante");
				$idProyecto = $_POST['idProyecto'];
				$idObjetivo = $_POST['idObjetivo'];
				$idProducto = $_POST['idProducto'];
				$idActividad = $_POST['idActividad'];
				$idAccion = $_POST['idAccion'];
				$idAccionEjecucion = $_POST['idAccionEjecucion'];
				$participante = $_POST['participante'];

				$resValidacion = $this->ValidarAccesoEjecucion($idProyecto, $idObjetivo, $idProducto, $idActividad, $idAccion, false, $idAccionEjecucion);

				$this->accionEjecucionParticipanteModel->ActualizarRol($participante);

				$this->historialModel->Registrar('Eliminar participante', $idAccionEjecucion);

				$res->estado = true;
				$this->db->commit();
			} catch (Exception $e) {
				$this->db->rollBack();
				$res->estado = false;
				$res->error = $e->getMessage();
			}
			
			echo json_encode($res);
		}

		public function EliminarParticipante()
		{
			$res = new Resultado();

			$this->db->beginTransaction();
			try {
				ValidarAcceso($this->db, "opcionEjecucionParticipante");
				$idProyecto = $_POST['idProyecto'];
				$idObjetivo = $_POST['idObjetivo'];
				$idProducto = $_POST['idProducto'];
				$idActividad = $_POST['idActividad'];
				$idAccion = $_POST['idAccion'];
				$idAccionEjecucion = $_POST['idAccionEjecucion'];
				$idAccionEjecucionParticipante = $_POST['idAccionEjecucionParticipante'];

				$resValidacion = $this->ValidarAccesoEjecucion($idProyecto, $idObjetivo, $idProducto, $idActividad, $idAccion, false, $idAccionEjecucion);

				$this->accionEjecucionParticipanteModel->Eliminar($idAccionEjecucionParticipante);

				$this->historialModel->Registrar('Eliminar participante', $idAccionEjecucion);

				$res->estado = true;
				$this->db->commit();
			} catch (Exception $e) {
				$this->db->rollBack();
				$res->estado = false;
				$res->error = $e->getMessage();
			}
			
			echo json_encode($res);
		}

		public function ListarFotoEjecucion()
		{
			$res = new Resultado();

			$this->db->beginTransaction();
			try {
				ValidarAcceso($this->db, "opcionEjecucionFoto");
				$idProyecto = $_GET['idProyecto'];
				$idObjetivo = $_GET['idObjetivo'];
				$idProducto = $_GET['idProducto'];
				$idActividad = $_GET['idActividad'];
				$idAccion = $_GET['idAccion'];
				$idAccionEjecucion = $_GET['idAccionEjecucion'];

				$resValidacion = $this->ValidarAccesoEjecucion($idProyecto, $idObjetivo, $idProducto, $idActividad, $idAccion, false, $idAccionEjecucion);

				$fotos = $this->accionEjecucionFotoModel->ListarPorEjecucion($idAccionEjecucion);

				$res->fotos = $fotos;
				$res->estado = true;
				$this->db->commit();
			} catch (Exception $e) {
				$this->db->rollBack();
				$res->estado = false;
				$res->error = $e->getMessage();
			}
			
			echo json_encode($res);
		}

		public function RegistrarFoto()
		{
			$res = new Resultado();

			$this->db->beginTransaction();
			try {
				ValidarAcceso($this->db, "opcionEjecucionFoto");
				$idProyecto = $_POST['idProyecto'];
				$idObjetivo = $_POST['idObjetivo'];
				$idProducto = $_POST['idProducto'];
				$idActividad = $_POST['idActividad'];
				$idAccion = $_POST['idAccion'];
				$idAccionEjecucion = $_POST['idAccionEjecucion'];
				$foto = $_POST['foto'];

				$resValidacion = $this->ValidarAccesoEjecucion($idProyecto, $idObjetivo, $idProducto, $idActividad, $idAccion, false, $idAccionEjecucion);

				$cantidad = $this->accionEjecucionFotoModel->CantidadPorEjecucion($idAccionEjecucion);
				if ($cantidad >= 3) {
					throw new Exception("Solo se pueden registrar 3 fotos por ejecución.");
				}

				$idFoto = $this->accionEjecucionFotoModel->Registrar($idAccionEjecucion);

				if (!is_dir('img/proyecto_' . $idProyecto)) {
				  // dir doesn't exist, make it
				  mkdir('img/proyecto_' . $idProyecto);
				}

				file_put_contents('img/proyecto_' . $idProyecto .'/'.$idAccionEjecucion.'_'.$idFoto.'.png', base64_decode(explode(',', $foto)[1]));

				$this->historialModel->Registrar('Registrar foto', $idAccionEjecucion);

				$res->idFoto = $idFoto;
				$res->estado = true;
				$this->db->commit();
			} catch (Exception $e) {
				$this->db->rollBack();
				$res->estado = false;
				$res->error = $e->getMessage();
			}
			
			echo json_encode($res);
		}

		public function EliminarFoto()
		{
			$res = new Resultado();

			$this->db->beginTransaction();
			try {
				ValidarAcceso($this->db, "opcionEjecucionFoto");
				$idProyecto = $_POST['idProyecto'];
				$idObjetivo = $_POST['idObjetivo'];
				$idProducto = $_POST['idProducto'];
				$idActividad = $_POST['idActividad'];
				$idAccion = $_POST['idAccion'];
				$idAccionEjecucion = $_POST['idAccionEjecucion'];
				$foto = $_POST['foto'];
				
				$resValidacion = $this->ValidarAccesoEjecucion($idProyecto, $idObjetivo, $idProducto, $idActividad, $idAccion, false, $idAccionEjecucion);

				$this->accionEjecucionFotoModel->Eliminar(explode('_', $foto)[1]);

				$this->historialModel->Registrar('Eliminar foto', $idAccionEjecucion);

				$res->estado = true;
				$this->db->commit();
			} catch (Exception $e) {
				$this->db->rollBack();
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

		private function ValidarAccesoAccionSinEstado($idProyecto, $idObjetivo, $idProducto, $idActividad, $idAccion)
		{
			$res = new stdClass();
			$actividad = $this->ValidarAccesoActividad($idProyecto, $idObjetivo, $idProducto, $idActividad);

			$accion = $this->accionModel->BuscarPorId($idActividad, $idAccion);
			
			if ($accion == false) {
				throw new Exception("La acción no existe o no esta disponible para usted.");
			}

			$res->estadoActividad = $actividad['estado'];
			$res->accion = $accion;

			return $res;
		}

		private function ValidarAccesoEjecucion($idProyecto, $idObjetivo, $idProducto, $idActividad, $idAccion, $edicionAccion, $idAccionEjecucion)
		{
			$res = $this->ValidarAccesoAccion($idProyecto, $idObjetivo, $idProducto, $idActividad, $idAccion, $edicionAccion);

			$ejecucion = $this->accionEjecucionModel->BuscarPorId($idAccion, $idAccionEjecucion);
			
			if ($ejecucion == false) {
				throw new Exception("La ejecucion programada no existe o no esta disponible para usted.");
			}

			$res->ejecucion = $ejecucion;
			return $res;
		}

		private function ValidarAccesoEjecucionSinEstado($idProyecto, $idObjetivo, $idProducto, $idActividad, $idAccion, $idAccionEjecucion)
		{
			$res = $this->ValidarAccesoAccionSinEstado($idProyecto, $idObjetivo, $idProducto, $idActividad, $idAccion);

			$ejecucion = $this->accionEjecucionModel->BuscarPorId($idAccion, $idAccionEjecucion);
			
			if ($ejecucion == false) {
				throw new Exception("La ejecucion programada no existe o no esta disponible para usted.");
			}

			$res->ejecucion = $ejecucion;
			return $res;
		}
	}
?>