<?php
	require_once("models/Persona_model.php");
	require_once("models/Comunidad_model.php");

	class Persona
	{
		private $personaModel;
		private $comunidadModel;
		protected $db;

		public function __construct($dbConexion)
		{
			$this->db = $dbConexion;
			$this->personaModel = new Persona_model($this->db);
			$this->comunidadModel = new Comunidad_model($this->db);
			ValidarSession();
		}

		public function Persona()
		{
			try {
				$parametro[0] = $this->personaModel->Listar(0);
				$parametro[1] = $this->comunidadModel->Listar();
				$contenido = requireToVar("views/Persona_view.php", $parametro);
				$accesos = ListarPermiso($this->db);
				require_once("views/layouts/plantilla.php");
			}
			catch (Exception $e){
				print($e->getMessage()."\n\n".$e->getTraceAsString());
			}
		}

		public function ListarPersona()
		{
			$res = new Resultado();
			try {
				$idComunidad = $_GET['idComunidad'];

				$parametro[0] = $this->personaModel->Listar($idComunidad);
				$res->vista = requireToVar("views/partials/TablaPersona_view.php", $parametro);
				$res->estado = true;
			} catch (Exception $e) {
				$res->estado = false;
				$res->error = $e->getMessage()."\n\n".$e->getTraceAsString();
			}
			
			echo json_encode($res);
		}

		public function ListarPersonaSimple()
		{
			$res = new Resultado();

			try {
				$res->personas = $this->personaModel->Listar(0);
				$res->estado = true;
			} catch (Exception $e) {
				$res->estado = false;
				$res->error = $e->getMessage();
			}
			
			echo json_encode($res);
		}
		
		public function BuscarPersona()
		{
			$res = new Resultado();
			try {
				$idPersona = $_GET['idPersona'];

				$persona = $this->personaModel->BuscarPorId($idPersona);
				if ($persona == false) {
					throw new Exception("La persona no existe.");
				}

				$res->persona = $persona;
				$res->estado = true;
			} catch (Exception $e) {
				$res->estado = false;
				$res->error = $e->getMessage();
			}
			
			echo json_encode($res);
		}

		public function BuscarPeronaPorDni()
		{
			$res = new Resultado();
			try {
				$dni = $_GET['dni'];

				$persona = $this->personaModel->BuscarPorDni($dni);
				if ($persona == false) {
					$res->persona = new stdClass();
					$res->persona->id_persona = 0;
					$res->persona->nombre = "";
					$res->persona->paterno = "";
					$res->persona->materno = "";
					$res->persona->dni = $dni;
					$res->persona->genero = "";
					$res->persona->fecha_nacimiento = "";
					$res->persona->id_comunidad = "";
					try {
						$url = 'https://ww1.essalud.gob.pe/sisep/postulante/postulante/postulante_obtenerDatosPostulante.htm?strDni='.$dni;
						$ch = curl_init();
						curl_setopt ($ch, CURLOPT_URL, $url);
						curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 5);
						curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
						$contents = curl_exec($ch);
						if (curl_errno($ch)) {
						  throw new Exception(curl_errno($ch), 1);
						} else {
						  curl_close($ch);
						}   

		                $partes = json_decode($contents);
		                $res->persona->nombre = $partes->DatosPerson[0]->Nombres;
						$res->persona->paterno = $partes->DatosPerson[0]->ApellidoPaterno;
						$res->persona->materno = $partes->DatosPerson[0]->ApellidoMaterno;
					} catch (Exception $e) {
						error_log("ERROR al buscar DNI : ".$dni);
					}
				}
				else{
					$res->persona = $persona;
				}

				$res->estado = true;
			} catch (Exception $e) {
				$res->estado = false;
				$res->error = $e->getMessage();
			}
			
			echo json_encode($res);
		}

		public function RegistrarPersona()
		{
			$res = new Resultado();

			try {
				$persona = $_POST['persona'];

				if ($this->personaModel->BuscarDniRegistrado($persona['dni'], 0) == true) {
					throw new Exception("El número de DNI ya se encuentra registrado.");
				}
				
				$idPersona = $this->personaModel->Registrar($persona);

				$parametro[0] = $this->personaModel->Listar($persona['idComunidad']);
				$res->vista = requireToVar("views/partials/TablaPersona_view.php", $parametro);
				$res->personas = $parametro[0];
				$res->idPersona = $idPersona;
				$res->estado = true;
			} catch (Exception $e) {
				$res->estado = false;
				$res->error = $e->getMessage()."\n\n".$e->getTraceAsString();
			}
			
			echo json_encode($res);
		}

		public function ModificarPersona()
		{
			$res = new Resultado();

			try {
				$persona = $_POST['persona'];

				if ($this->personaModel->BuscarDniRegistrado($persona['dni'], $persona['idPersona']) == true) {
					throw new Exception("El correo ya se encuentra registrado.");
				}

				$this->personaModel->Modificar($persona);

				$parametro[0] = $this->personaModel->Listar($persona['idComunidad']);
				$res->vista = requireToVar("views/partials/TablaPersona_view.php", $parametro);
				$res->estado = true;
			} catch (Exception $e) {
				$res->estado = false;
				$res->error = $e->getMessage();
			}
			
			echo json_encode($res);
		}

		public function EliminarPersona()
		{
			$res = new Resultado();

			try {
				$idPersona = $_POST['idPersona'];
				
				$this->personaModel->Eliminar($idPersona);

				$parametro[0] = $this->personaModel->Listar($persona['idComunidad']);
				$res->vista = requireToVar("views/partials/TablaPersona_view.php", $parametro);
				$res->estado = true;
			} catch (Exception $e) {
				$res->estado = false;
				$res->error = $e->getMessage();
			}
			
			echo json_encode($res);
		}
	}
?>