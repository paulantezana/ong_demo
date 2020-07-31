<?php
	require_once("models/Usuario_model.php");
	require_once("models/Perfil_model.php");
	require_once("models/UsuarioProyecto_model.php");
	require_once("models/Proyecto_model.php");

	class Usuario
	{
		private $usuarioModel;
		private $perfilModel;
		private $usuarioProyectoModel;
		private $proyectoModel;
		protected $db;

		public function __construct($dbConexion)
		{
			$this->db = $dbConexion;
			$this->usuarioModel = new Usuario_model($this->db);
			$this->perfilModel = new Perfil_model($this->db);
			$this->usuarioProyectoModel = new UsuarioProyecto_model($this->db);
			$this->proyectoModel = new Proyecto_model($this->db);

			ValidarSession();
		}

		public function Usuario()
		{
			try {
				$parametro[0] = $this->perfilModel->Listar();
				$contenido = requireToVar("views/Usuario_view.php", $parametro);
				$accesos = ListarPermiso($this->db);
				require_once("views/layouts/plantilla.php");
			}
			catch (Exception $e){
				print($e->getMessage()."\n\n".$e->getTraceAsString());
			}
		}

		public function ListarUsuario()
		{
			$res = new Resultado();
			try {
				$parametro[0] = $this->usuarioModel->Listar();
				$res->vista = requireToVar("views/partials/TablaUsuario_view.php", $parametro);
				$res->estado = true;
			} catch (Exception $e) {
				$res->estado = false;
				$res->error = $e->getMessage()."\n\n".$e->getTraceAsString();
			}
			
			echo json_encode($res);
		}

		public function BuscarUsuario()
		{
			$res = new Resultado();
			try {
				$idUsuario = $_GET['idUsuario'];

				$usuario = $this->usuarioModel->BuscarPorId($idUsuario);
				if ($usuario == false) {
					throw new Exception("El usuario no existe.");
				}

				$res->usuario = $usuario;
				$res->estado = true;
			} catch (Exception $e) {
				$res->estado = false;
				$res->error = $e->getMessage();
			}
			
			echo json_encode($res);
		}

		public function RegistrarUsuario()
		{
			$res = new Resultado();

			try {
				$usuario = $_POST['usuario'];
				$usuario['password'] = password_hash($usuario['password'], PASSWORD_DEFAULT);

				if ($this->usuarioModel->BuscarCorreoRegistrado($usuario['correo'], 0) == true) {
					throw new Exception("El correo ya se encuentra registrado.");
				}
				
				$this->usuarioModel->Registrar($usuario);

				$parametro[0] = $this->usuarioModel->Listar();
				$res->vista = requireToVar("views/partials/TablaUsuario_view.php", $parametro);
				$res->estado = true;
			} catch (Exception $e) {
				$res->estado = false;
				$res->error = $e->getMessage()."\n\n".$e->getTraceAsString();
			}
			
			echo json_encode($res);
		}

		public function ModificarUsuario()
		{
			$res = new Resultado();

			try {
				$usuario = $_POST['usuario'];
				$usuario['password'] = password_hash($usuario['password'], PASSWORD_DEFAULT);

				if ($this->usuarioModel->BuscarCorreoRegistrado($usuario['correo'], $usuario['idUsuario']) == true) {
					throw new Exception("El correo ya se encuentra registrado.");
				}

				$this->usuarioModel->Modificar($usuario);

				$parametro[0] = $this->usuarioModel->Listar();
				$res->vista = requireToVar("views/partials/TablaUsuario_view.php", $parametro);
				$res->estado = true;
			} catch (Exception $e) {
				$res->estado = false;
				$res->error = $e->getMessage();
			}
			
			echo json_encode($res);
		}

		public function EliminarUsuario()
		{
			$res = new Resultado();

			try {
				$idUsuario = $_POST['idUsuario'];
				
				$this->usuarioModel->Eliminar($idUsuario);

				$parametro[0] = $this->usuarioModel->Listar();
				$res->vista = requireToVar("views/partials/TablaUsuario_view.php", $parametro);
				$res->estado = true;
			} catch (Exception $e) {
				$res->estado = false;
				$res->error = $e->getMessage();
			}
			
			echo json_encode($res);
		}

		public function ListarProyectoAsignado(){
			$res = new Resultado();
			try {
				$idUsuario = $_GET['idUsuario'];
				$parametro[0] = $this->proyectoModel->Listar();
				$res->vista = requireToVar("views/partials/TablaProyectoAcceso_view.php", $parametro);
				$res->proyectos = $this->usuarioProyectoModel->ListarPorUsuario($idUsuario);
				$res->estado = true;
			} catch (Exception $e) {
				$res->estado = false;
				$res->error = $e->getMessage()."\n\n".$e->getTraceAsString();
			}
			
			echo json_encode($res);
		}

		public function AsignarProyecto()
		{
			$res = new Resultado();
			try {
				$idUsuario = $_POST['idUsuario'];
				$cantidad = $_POST['cantidad'];
				if ($cantidad > 0) {
					$proyectos = $_POST['proyectos'];
				}
				else{
					$proyectos = array();
				}
				
				$usuario = $this->usuarioModel->BuscarPorId($idUsuario);
				if ($usuario == false) {
					throw new Exception("El usuario no existe.");
				}

				$this->usuarioProyectoModel->EliminarPorUsuario($idUsuario);
				foreach ($proyectos as $key => $idProyecto) {
					$this->usuarioProyectoModel->Registrar($idUsuario, $idProyecto);
				}
				
				$parametro[0] = $this->usuarioModel->Listar();
				$res->vista = requireToVar("views/partials/TablaUsuario_view.php", $parametro);
				$res->estado = true;
			} catch (Exception $e) {
				$res->estado = false;
				$res->error = $e->getMessage();
			}
			
			echo json_encode($res);
		}
	}
?>