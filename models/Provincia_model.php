<?php
class Provincia_model
{
	private $db;
    private $provincias;

	public function __construct($dbConexion)
    {
        $this->db = $dbConexion;
        $this->provincias = array();
    }

    public function ListarPorRegion($idRegion)
    {
        try {
            $this->provincias = array();
            $consulta = $this->db->prepare("SELECT id_provincia, nombre
                                            FROM provincia
                                            WHERE eliminado = 0 
                                            AND id_region = :idRegion
                                            ORDER BY nombre ASC;");
            $consulta->bindParam(':idRegion', $idRegion);

            if (!$consulta->execute()) {
                throw new Exception($consulta->errorInfo()[2]);
            }
            
            while($filas = $consulta->fetch()){
                $this->provincias[] = $filas;
            }
            
            return $this->provincias;
        } catch (Exception $e) {
            throw new Exception("Error en metodo : ".__FUNCTION__.' | '. $e->getMessage()."\n". $e->getTraceAsString());
        }
    }

    public function BuscarPorId($idProvincia){
        try {
            $consulta = $this->db->prepare("SELECT * FROM provincia where id_provincia = :idProvincia and eliminado = 0;");
            $consulta->bindParam(':idProvincia', $idProvincia);

            if (!$consulta->execute()) {
                throw new Exception($consulta->errorInfo()[2]);
            }
            
            while($filas = $consulta->fetch()){
                $this->provincias[] = $filas;
            }
            
            if (count($this->provincias) > 0) {
                return $this->provincias[0];
            }
            else{
                return false;
            }
        } catch (Exception $e) {
            throw new Exception("Error en metodo : ".__FUNCTION__.' | '. $e->getMessage()."\n". $e->getTraceAsString());
        }
    }

    public function Registrar($nombre, $idRegion)
    {
        try {
            $fecha = date('Y-m-d H:i:s');
 
            $consulta = $this->db->prepare("INSERT INTO provincia (nombre, id_region, eliminado, id_usuario_creacion, fecha_creacion, id_ultimo_usuario, fecha_modificacion)
                                            VALUES (:nombre, :idRegion, 0, :idUsuarioCreacion, :fechaCreacion, :idUltimoUsuario, :fechaModificacion);");
            $consulta->bindParam(':nombre', $nombre);
            $consulta->bindParam(':idRegion', $idRegion);
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

    public function Modificar($nombre, $idProvincia)
    {
        try {
            $fecha = date('Y-m-d H:i:s');
 
            $consulta = $this->db->prepare("UPDATE provincia SET nombre = :nombre, id_ultimo_usuario = :idUltimoUsuario , fecha_modificacion = :fechaModificacion WHERE id_provincia = :idProvincia;");
            $consulta->bindParam(':idProvincia', $idProvincia);
            $consulta->bindParam(':nombre', $nombre);
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