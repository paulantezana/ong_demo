<?php
	require_once("models/Familia_model.php");
	require_once("models/FamiliaIntegrante_model.php");
	require_once("models/Persona_model.php");
	require_once("models/Comunidad_model.php");

	class Familia
	{
		private $personaModel;
		private $familiaModel;
		private $familiaIntegranteModel;
		private $comunidadModel;
		protected $db;

		public function __construct($dbConexion)
		{
			$this->db = $dbConexion;
			$this->personaModel = new Persona_model($this->db);
			$this->familiaModel = new Familia_model($this->db);
			$this->familiaIntegranteModel = new FamiliaIntegrante_model($this->db);
			$this->comunidadModel = new Comunidad_model($this->db);
			ValidarSession();
		}

		public function Familia()
		{
			try {
				$parametro[0] = $this->personaModel->Listar(0);
				$contenido = requireToVar("views/Familia_view.php", $parametro);
				$accesos = ListarPermiso($this->db);
				require_once("views/layouts/plantilla.php");
			}
			catch (Exception $e){
				print($e->getMessage()."\n\n".$e->getTraceAsString());
			}
		}

		public function ListarFamilia()
		{
			$res = new Resultado();
			try {
				$parametro[0] = $this->familiaModel->Listar();
				$res->vista = requireToVar("views/partials/TablaFamilia_view.php", $parametro);
				$res->estado = true;
			} catch (Exception $e) {
				$res->estado = false;
				$res->error = $e->getMessage()."\n\n".$e->getTraceAsString();
			}
			
			echo json_encode($res);
		}

		public function BuscarFamilia()
		{
			$res = new Resultado();
			try {
				$idFamilia = $_GET['idFamilia'];

				$familia = $this->familiaModel->BuscarPorId($idFamilia);
				if ($familia == false) {
					throw new Exception("La familia no existe.");
				}
				
				$res->integrantes = $this->familiaIntegranteModel->ListarPorFamilia($idFamilia);
				$res->estado = true;
			} catch (Exception $e) {
				$res->estado = false;
				$res->error = $e->getMessage();
			}
			
			echo json_encode($res);
		}

		public function RegistrarFamilia()
		{
			$res = new Resultado();
			$this->db->beginTransaction();
			try {
				$familia = $_POST['familia'];
		
				$idFamilia = $this->familiaModel->Registrar();

				$jefe = false;
				foreach ($familia['integrantes'] as $key => $integrante) {
					$integranteExistente = $this->familiaIntegranteModel->ExisteIntegrante($integrante['idPersona']);
					if ($integranteExistente == true) {
						throw new Exception("Una o mas personas ya estan asignadas a otra familia.");
					}

					if ($integrante['rol'] == 'JEFE') {
						if ($jefe == false) {
							$jefe = true;
						}
						else{
							throw new Exception("Solo se puede registrar un jefe de familia.");
						}
					}

					$this->familiaIntegranteModel->Registrar($idFamilia, $integrante);
				}

				if ($jefe == false) {
					throw new Exception("Debe seleccionar el jefe de familia.");
				}

				$parametro[0] = $this->familiaModel->Listar();
				$res->vista = requireToVar("views/partials/TablaFamilia_view.php", $parametro);
				$res->estado = true;
				$this->db->commit();
			} catch (Exception $e) {
				$this->db->rollback();
				$res->estado = false;
				$res->error = $e->getMessage()."\n\n".$e->getTraceAsString();
			}
			
			echo json_encode($res);
		}

		public function ModificarFamilia()
		{
			$res = new Resultado();
			$this->db->beginTransaction();
			try {
				$familiaNueva = $_POST['familia'];
		
				$familia = $this->familiaModel->BuscarPorId($familiaNueva['idFamilia']);
				if ($familia == false) {
					throw new Exception("La familia no existe.");
				}

				$this->familiaModel->Modificar($familiaNueva);

				$this->familiaIntegranteModel->EliminarPorFamilia($familiaNueva['idFamilia']);
         
				$jefe = false;
				foreach ($familiaNueva['integrantes'] as $key => $integrante) {
					$integranteExistente = $this->familiaIntegranteModel->ExisteIntegrante($integrante['idPersona']);
					if ($integranteExistente == true) {
						throw new Exception("Una o mas personas ya estan asignadas a otra familia.");
					}

					if ($integrante['rol'] == 'JEFE') {
						if ($jefe == false) {
							$jefe = true;
						}
						else{
							throw new Exception("Solo se puede registrar un jefe de familia.");
						}
					}

					$this->familiaIntegranteModel->Registrar($familiaNueva['idFamilia'], $integrante);
				}

				if ($jefe == false) {
					throw new Exception("Debe seleccionar el jefe de familia.");
				}

				$parametro[0] = $this->familiaModel->Listar();
				$res->vista = requireToVar("views/partials/TablaFamilia_view.php", $parametro);
				$res->estado = true;
				$this->db->commit();
			} catch (Exception $e) {
				$this->db->rollback();
				$res->estado = false;
				$res->error = $e->getMessage()."\n\n".$e->getTraceAsString();
			}
			
			echo json_encode($res);
		}

		public function EliminarFamilia()
		{
			$res = new Resultado();

			try {
				$idFamilia = $_POST['idFamilia'];
				
				$this->familiaModel->Eliminar($idFamilia);

				$parametro[0] = $this->familiaModel->Listar();
				$res->vista = requireToVar("views/partials/TablaFamilia_view.php", $parametro);
				$res->estado = true;
			} catch (Exception $e) {
				$res->estado = false;
				$res->error = $e->getMessage();
			}
			
			echo json_encode($res);
		}
	}
?>