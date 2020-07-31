<?php
class Region_model
{
	private $db;
    private $regiones;

	public function __construct($dbConexion)
    {
        $this->db = $dbConexion;
        $this->regiones = array();
    }

    public function Listar()
    {
        try {
            $this->regiones = array();
            $consulta = $this->db->prepare("SELECT id_region, nombre
                                            FROM region
                                            WHERE eliminado = 0 
                                            ORDER BY nombre ASC;");

            if (!$consulta->execute()) {
                throw new Exception($consulta->errorInfo()[2]);
            }
            
            while($filas = $consulta->fetch()){
                $this->regiones[] = $filas;
            }
            
            return $this->regiones;
        } catch (Exception $e) {
            throw new Exception("Error en metodo : ".__FUNCTION__.' | '. $e->getMessage()."\n". $e->getTraceAsString());
        }
    }

    public function Registrar($nombre)
    {
        try {
            $fecha = date('Y-m-d H:i:s');
 
            $consulta = $this->db->prepare("INSERT INTO region (nombre, eliminado, id_usuario_creacion, fecha_creacion, id_ultimo_usuario, fecha_modificacion)
                                            VALUES (:nombre, 0, :idUsuarioCreacion, :fechaCreacion, :idUltimoUsuario, :fechaModificacion);");
            $consulta->bindParam(':nombre', $nombre);
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

    public function Modificar($nombre, $idRegion)
    {
        try {
            $fecha = date('Y-m-d H:i:s');
 
            $consulta = $this->db->prepare("UPDATE region SET nombre = :nombre, id_ultimo_usuario = :idUltimoUsuario , fecha_modificacion = :fechaModificacion WHERE id_region = :idRegion;");
            $consulta->bindParam(':idRegion', $idRegion);
            $consulta->bindParam(':nombre', $nombre);
            $consulta->bindParam(':fechaNacimiento', $persona['fechaNacimiento']);
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