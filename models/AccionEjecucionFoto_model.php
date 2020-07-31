<?php
class AccionEjecucionFoto_model
{
	private $db;
    private $fotos;

	public function __construct($dbConexion)
    {
        $this->db = $dbConexion;
        $this->fotos = array();
    }

    public function ListarPorEjecucion($idAccionEjecucion)
    {
        try {
            $this->fotos = array();

            $consulta = $this->db->prepare("SELECT id_accion_ejecucion_foto
                                            FROM accion_ejecucion_foto  
                                            where eliminado = 0 
                                            and id_accion_ejecucion = :idAccionEjecucion;");
            $consulta->bindParam(':idAccionEjecucion', $idAccionEjecucion);

            if (!$consulta->execute()) {
                throw new Exception($consulta->errorInfo()[2]);
            }
            
            while($filas = $consulta->fetch()){
                $this->fotos[] = $filas;
            }
            
            return $this->fotos;
        } catch (Exception $e) {
            throw new Exception("Error en metodo : ".__FUNCTION__.' | '. $e->getMessage()."\n". $e->getTraceAsString());
        }
    }

    public function Registrar($idAccionEjecucion)
    {
        try {
            $fecha = date('Y-m-d H:i:s');
 
            $consulta = $this->db->prepare("INSERT INTO accion_ejecucion_foto (id_accion_ejecucion, eliminado, id_usuario_creacion, fecha_creacion, id_ultimo_usuario, fecha_modificacion)
                                            VALUES (:idAccionEjecucion, 0, :idUsuarioCreacion, :fechaCreacion, :idUltimoUsuario, :fechaModificacion);");
            $consulta->bindParam(':idAccionEjecucion', $idAccionEjecucion);
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

    public function CantidadPorEjecucion($idAccionEjecucion)
    {
        try {
            $this->fotos = array();

            $consulta = $this->db->prepare("SELECT COUNT(`id_accion_ejecucion_foto`) as cantidad
                                            FROM accion_ejecucion_foto
                                            where eliminado = 0 
                                            and id_accion_ejecucion = :idAccionEjecucion;");
            $consulta->bindParam(':idAccionEjecucion', $idAccionEjecucion);

            if (!$consulta->execute()) {
                throw new Exception($consulta->errorInfo()[2]);
            }
            
            while($filas = $consulta->fetch()){
                $this->fotos[] = $filas;
            }
            
            return $this->fotos[0]['cantidad'];
        } catch (Exception $e) {
            throw new Exception("Error en metodo : ".__FUNCTION__.' | '. $e->getMessage()."\n". $e->getTraceAsString());
        }
    }

    public function Eliminar($idAccionEjecucionFoto)
    {
        try {
            $fecha = date('Y-m-d H:i:s');
 
            $consulta = $this->db->prepare("UPDATE accion_ejecucion_foto SET eliminado = 1, id_ultimo_usuario = :idUltimoUsuario , fecha_modificacion = :fechaModificacion WHERE id_accion_ejecucion_foto = :idAccionEjecucionFoto;");
            $consulta->bindParam(':idAccionEjecucionFoto', $idAccionEjecucionFoto);
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