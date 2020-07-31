<?php 
	function requireToVar($file, $a)
	{
		ob_start();
		$parametro = $a;
		require($file);
		return ob_get_clean();
	}

	function exceptions_error_handler($severity, $message, $filename, $lineno) 
	{
	  if (error_reporting() == 0) {
	    return;
	  }
	  if (error_reporting() & $severity) {
	    throw new ErrorException($message, 0, $severity, $filename, $lineno);
	  }
	}

	function ValidarSession()
	{
	    if(!isset($_SESSION['idUsuario'])){
			session_destroy();
	    	
	    	if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
				echo json_encode('rechazado');
			}
			else{
				header('Location: ' . URL_PATH);
			}

			exit();
	    }
	}

	function CalcularEdad($fechaNacimiento)
	{
		$fecha = DateTime::createFromFormat('Y-m-d', $fechaNacimiento);

		$edad = $fecha->diff(new DateTime('now'))->y;
		
     	return $edad;
	}

	function ListarPermiso($db)
	{
		$permisos = array();

		try {
            $consulta = $db->prepare("SELECT perm.`permiso`
									FROM perfil_permiso perm
									JOIN perfil per ON perm.`id_perfil` = per.`id_perfil`
											AND per.`eliminado` = 0
									JOIN usuario usu ON per.`id_perfil` = usu.`id_perfil`
											AND usu.`eliminado` = 0
											AND usu.`id_usuario` = :idUsuario;");
            $consulta->bindParam(':idUsuario', $_SESSION['idUsuario']);

            if (!$consulta->execute()) {
                throw new Exception($consulta->errorInfo()[2]);
            }
            
            while($filas = $consulta->fetch()){
                $permisos[] = $filas;
            }
            
            return $permisos;
        } catch (Exception $e) {
            throw new Exception("Error en metodo : ".__FUNCTION__.' | '. $e->getMessage()."\n". $e->getTraceAsString());
        }
	}

	function ValidarAcceso($db, $accion)
	{
		$permisos = array();

		try {
            $consulta = $db->prepare("SELECT perm.`permiso`
									FROM perfil_permiso perm
									JOIN perfil per ON perm.`id_perfil` = per.`id_perfil`
											AND per.`eliminado` = 0
									JOIN usuario usu ON per.`id_perfil` = usu.`id_perfil`
											AND usu.`eliminado` = 0
											AND usu.`id_usuario` = :idUsuario
									WHERE perm.permiso = :accion;");
            $consulta->bindParam(':idUsuario', $_SESSION['idUsuario']);
            $consulta->bindParam(':accion', $accion);

            if (!$consulta->execute()) {
                throw new Exception($consulta->errorInfo()[2]);
            }
            
            while($filas = $consulta->fetch()){
                $permisos[] = $filas;
            }
            
            if (!(count($permisos) > 0)) {
            	echo 'No tiene los permisos necesarios para realizar esta acción.';
            	exit();
            }
        } catch (Exception $e) {
            throw new Exception("Error en metodo : ".__FUNCTION__.' | '. $e->getMessage()."\n". $e->getTraceAsString());
        }
	}

	function ValidarAccesoProyecto($db)
	{
		$idProyecto = 0;

		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		    $idProyecto = $_POST['idProyecto'];
		}
		else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
		    $idProyecto = $_GET['idProyecto'];
		}
		else{
			exit();
		}

		$permisos = array();
		try {
            $consulta = $db->prepare("SELECT id_usuario_proyecto
									FROM usuario_proyecto
									WHERE id_usuario = :idUsuario
									AND id_proyecto = :idProyecto;");
            $consulta->bindParam(':idUsuario', $_SESSION['idUsuario']);
            $consulta->bindParam(':idProyecto', $idProyecto);

            if (!$consulta->execute()) {
                throw new Exception($consulta->errorInfo()[2]);
            }
            
            while($filas = $consulta->fetch()){
                $permisos[] = $filas;
            }
            
            if (!(count($permisos) > 0)) {
            	echo 'No tiene permitido acceder a este proyecto.';
            	exit();
            }
        } catch (Exception $e) {
            throw new Exception("Error en metodo : ".__FUNCTION__.' | '. $e->getMessage()."\n". $e->getTraceAsString());
        }
	}

	class Resultado extends stdClass
	{
		public $estado;
		public $error;

		function __construct()
		{
			$estado = false;
			$error = '';
		}
	}
?>