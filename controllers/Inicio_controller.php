<?php
	require_once("models/Usuario_model.php");

	class Inicio
	{
		private $usuarioModel;
		protected $db;

		public function __construct($dbConexion)
		{
			$this->db = $dbConexion;
			$this->usuarioModel = new Usuario_model($this->db);
		}

		public function exec()
		{
			require_once("views/Login.php");
		}

		public function Login()
		{
			if(!$_POST || !$_POST['correo'] || !$_POST['password']){
				header('Location: ' . URL_PATH . '/');
				exit();
		    }

		    try {
				$usuario = $this->usuarioModel->BuscarUsuarioPorCorreo($_POST['correo']);
				// $password = password_hash("Administrador", PASSWORD_DEFAULT);
				//$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

				if ($usuario != false && password_verify($_POST['password'], $usuario['password']))
				{
					$_SESSION['idUsuario'] = $usuario['id_usuario'];
					// setcookie('perfil', $usuario['perfil'], null , '/HananPacha/',null , false); 
					// setcookie('usuario', $usuario['nombre'], null , '/HananPacha/',null , false); 
					$_SESSION['perfil'] = $usuario['perfil'];
					$_SESSION['usuario'] = $usuario['nombre'];
					
					$proyectoController = new ProyectoLista($this->db);
					
					if ($usuario['eliminado'] == 0) {
						$proyectoController->ListarProyecto();
					}
					else{
						$error = "Acceso denegado!";
						require_once("views/Login.php");
					}
				}
				else
				{
					$error = "Usuario y/o contraseña incorrectos!";
					require_once("views/Login.php");
				}
			}
			catch (Exception $e){
				print($e->getMessage(). "\n" . $e->getTraceAsString());
			}
		}

		public function Acceso()
		{
			if(!isset($_SESSION['idUsuario'])){
				header('Location: ' . URL_PATH . '/');
				exit();
		    }

		    try {
				$usuario = $this->usuarioModel->BuscarUsuarioPorCorreo($_POST['correo']);
				// $password = password_hash("Administrador", PASSWORD_DEFAULT);
				//$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

			}
			catch (Exception $e){
				print($e->getMessage(). "\n" . $e->getTraceAsString());
			}
		}

		public function Salir()
		{
			session_destroy();
			header('Location: ' . URL_PATH . '/');
		}
	}
?>