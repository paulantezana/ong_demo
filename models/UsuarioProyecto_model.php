<?php
class UsuarioProyecto_model
{
	private $db;
    private $usuariosProyectos;

	public function __construct($dbConexion)
    {
        $this->db = $dbConexion;
        $this->usuariosProyectos = array();
    }

    public function ListarPorUsuario($idUsuario){
    	$consulta = $this->db->prepare("SELECT id_proyecto
                                        FROM usuario_proyecto
                                        WHERE id_usuario = :idUsuario
                                        AND eliminado = 0;");
		$consulta->bindParam(':idUsuario', $idUsuario);
	    $consulta->execute();
        
        while($filas = $consulta->fetch()){
            $this->usuariosProyectos[] = $filas;
        }
        
        return $this->usuariosProyectos;
    }

    public function Registrar($idUsuario, $idProyecto)
    {
        try {
            $fecha = date('Y-m-d H:i:s');
 
            $consulta = $this->db->prepare("INSERT INTO usuario_proyecto (id_usuario, id_proyecto, eliminado, id_usuario_creacion, fecha_creacion, id_ultimo_usuario, fecha_modificacion)
                                            VALUES (:idUsuario, :idProyecto, 0, :idUsuarioCreacion, :fechaCreacion, :idUltimoUsuario, :fechaModificacion);");
            $consulta->bindParam(':idUsuario', $idUsuario);
            $consulta->bindParam(':idProyecto', $idProyecto);
            $consulta->bindParam(':idUsuarioCreacion', $_SESSION['idUsuario']);
            $consulta->bindParam(':fechaCreacion', $fecha);
            $consulta->bindParam(':idUltimoUsuario', $_SESSION['idUsuario']);
            $consulta->bindParam(':fechaModificacion', $fecha);

            if (!$consulta->execute()) {
                throw new Exception($consulta->errorInfo()[2]);
            }
            
            return $this->db->lastInsertId();
        } catch (Exception $e) {
            throw new Exception("Error en metodo : ".__FUNCTION__.' | '. $e->getMessage()."\n". $e->getTraceAsString());
        }
    }

    public function EliminarPorUsuario($idUsuario)
    {
        try {
            $fecha = date('Y-m-d H:i:s');
 
            $consulta = $this->db->prepare("UPDATE usuario_proyecto SET eliminado = 1, id_ultimo_usuario = :idUltimoUsuario , fecha_modificacion = :fechaModificacion WHERE id_usuario = :idUsuario;");
            $consulta->bindParam(':idUsuario', $idUsuario);
            $consulta->bindParam(':idUltimoUsuario', $_SESSION['idUsuario']);
            $consulta->bindParam(':fechaModificacion', $fecha);

            if (!$consulta->execute()) {
                throw new Exception($consulta->errorInfo()[2]);
            }
        } catch (Exception $e) {
            throw new Exception("Error en metodo : ".__FUNCTION__.' | '. $e->getMessage()."\n". $e->getTraceAsString());
        }
    }
}
?>
