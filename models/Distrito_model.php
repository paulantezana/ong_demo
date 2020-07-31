<?php
class Distrito_model
{
	private $db;
    private $distritos;

	public function __construct($dbConexion)
    {
        $this->db = $dbConexion;
        $this->distritos = array();
    }

    public function ListarPorProvincia($idProvincia)
    {
        try {
            $this->distritos = array();
            $consulta = $this->db->prepare("SELECT id_distrito, nombre
                                            FROM distrito
                                            WHERE eliminado = 0 
                                            AND id_provincia = :idProvincia
                                            ORDER BY nombre ASC;");

            $consulta->bindParam(':idProvincia', $idProvincia);
            
            if (!$consulta->execute()) {
                throw new Exception($consulta->errorInfo()[2]);
            }
            
            while($filas = $consulta->fetch()){
                $this->distritos[] = $filas;
            }
            
            return $this->distritos;
        } catch (Exception $e) {
            throw new Exception("Error en metodo : ".__FUNCTION__.' | '. $e->getMessage()."\n". $e->getTraceAsString());
        }
    }

    public function BuscarPorId($idDistrito){
        try {
            $consulta = $this->db->prepare("SELECT * FROM distrito where id_distrito = :idDistrito and eliminado = 0;");
            $consulta->bindParam(':idDistrito', $idDistrito);

            if (!$consulta->execute()) {
                throw new Exception($consulta->errorInfo()[2]);
            }
            
            while($filas = $consulta->fetch()){
                $this->distritos[] = $filas;
            }
            
            if (count($this->distritos) > 0) {
                return $this->distritos[0];
            }
            else{
                return false;
            }
        } catch (Exception $e) {
            throw new Exception("Error en metodo : ".__FUNCTION__.' | '. $e->getMessage()."\n". $e->getTraceAsString());
        }
    }

    public function Registrar($nombre, $idProvincia)
    {
        try {
            $fecha = date('Y-m-d H:i:s');
 
            $consulta = $this->db->prepare("INSERT INTO distrito (nombre, id_provincia, eliminado, id_usuario_creacion, fecha_creacion, id_ultimo_usuario, fecha_modificacion)
                                            VALUES (:nombre, :idProvincia, 0, :idUsuarioCreacion, :fechaCreacion, :idUltimoUsuario, :fechaModificacion);");
            $consulta->bindParam(':nombre', $nombre);
            $consulta->bindParam(':idProvincia', $idProvincia);
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

    public function Modificar($nombre, $idDistrito)
    {
        try {
            $fecha = date('Y-m-d H:i:s');
 
            $consulta = $this->db->prepare("UPDATE distrito SET nombre = :nombre, id_ultimo_usuario = :idUltimoUsuario , fecha_modificacion = :fechaModificacion WHERE id_distrito = :idDistrito;");
            $consulta->bindParam(':idDistrito', $idDistrito);
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