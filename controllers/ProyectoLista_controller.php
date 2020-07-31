<?php
	require_once("models/Proyecto_model.php");
	require_once("models/Objetivo_model.php");
	require_once("models/Producto_model.php");
	require_once("models/Actividad_model.php");
	require_once("models/ActividadDetalle_model.php");
	require_once("models/Accion_model.php");
	require_once("models/AccionEjecucion_model.php");

	class ProyectoLista
	{
		private $proyectoModel;
		private $objetivoModel;
		private $productoModel;
		private $actividadModel;
		private $actividadDetalleModel;
		private $accionModel;
		private $accionEjecucionModel;
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

			ValidarSession();
		}
		
		public function ListarProyecto()
		{
			try {
				$accesos = ListarPermiso($this->db);
				$parametro[0] = $this->proyectoModel->ListarPorUsuario();
				$contenido = requireToVar("views/ProyectoLista_view.php", $parametro);
				require_once("views/layouts/plantilla.php");
			}
			catch (Exception $e){
				print($e->getMessage()."\n\n".$e->getTraceAsString());
			}
		}

		public function ProyectoNuevo()
		{
			$accesos = ListarPermiso($this->db);
			$contenido = requireToVar("views/ProyectoNuevo_view.php", null);
			require_once("views/layouts/plantilla.php");
		}

		public function RegistrarProyecto()
		{
			$res = new Resultado();

			try {
				$proyecto = $_POST['proyecto'];

				$idProyecto = $this->proyectoModel->Registrar($proyecto);

				file_put_contents('img/foto_'.$idProyecto.'.png', base64_decode(explode(',', $proyecto['foto'])[1]));

				$res->estado = true;
			} catch (Exception $e) {
				$res->estado = false;
				$res->error = $e->getMessage();
			}
			
			echo json_encode($res);
		}
		
		public function BuscarProyectoActividad()
		{
			try {
				$codigo = $_GET['codigoActividad'];

				$actividad = $this->actividadModel->BuscarPorCodigo($codigo);

				$proyecto = $this->proyectoModel->BuscarPorId($actividad['id_proyecto']);

				$accesos = ListarPermiso($this->db);
				if ($proyecto == false) {
					$contenido = "No se encontro el proyecto o no tiene accesos al mismo.";
					require_once("views/layouts/plantilla.php");
				}
				else{
					$objetivos = $this->objetivoModel->ListarPorProyecto($actividad['id_proyecto']);

					foreach ($objetivos as $key => $objetivo) {
						$objetivos[$key]['productos'] = $this->productoModel->ListarPorObjetivo($objetivo['id_objetivo']);
					}
				
					$parametro[0] = $proyecto;
					$parametro[1] = $objetivos;
					$parametro[2] = $actividad['id_objetivo'];
					$parametro[3] = $actividad['id_producto'];
					$contenido = requireToVar("views/Proyecto_view.php", $parametro);
					require_once("views/layouts/plantilla.php");
				}
			}
			catch (Exception $e){
				print($e->getMessage()."\n\n".$e->getTraceAsString());
			}
		}
	}
?>