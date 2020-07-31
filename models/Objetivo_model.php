<?php
class Objetivo_model
{
	private $db;
    private $objetivos;

	public function __construct($dbConexion){
        $this->db = $dbConexion;
        $this->objetivos = array();
    }

    public function ListarPorProyecto($idProyecto){
        try {
            $this->objetivos = array();

            $consulta = $this->db->prepare("SELECT id_objetivo, nombre, descripcion, avance, ponderacion FROM objetivo where eliminado = 0 and id_proyecto = :idProyecto;");
            $consulta->bindParam(':idProyecto', $idProyecto);

            if (!$consulta->execute()) {
                throw new Exception($consulta->errorInfo()[2]);
            }
            
            while($filas = $consulta->fetch()){
                $this->objetivos[] = $filas;
            }
            
            return $this->objetivos;
        } catch (Exception $e) {
            throw new Exception("Error en metodo : ".__FUNCTION__.' | '. $e->getMessage()."\n". $e->getTraceAsString());
        }
    }

    public function BuscarPorId($idProyecto, $idObjetivo){
        try {
            $consulta = $this->db->prepare("SELECT * FROM objetivo where id_objetivo = :idObjetivo and eliminado = 0 and id_proyecto = :idProyecto;");
            $consulta->bindParam(':idObjetivo', $idObjetivo);
            $consulta->bindParam(':idProyecto', $idProyecto);

            if (!$consulta->execute()) {
                throw new Exception($consulta->errorInfo()[2]);
            }
            
            while($filas = $consulta->fetch()){
                $this->objetivos[] = $filas;
            }
            
            if (count($this->objetivos) > 0) {
                return $this->objetivos[0];
            }
            else{
                return false;
            }
        } catch (Exception $e) {
            throw new Exception("Error en metodo : ".__FUNCTION__.' | '. $e->getMessage()."\n". $e->getTraceAsString());
        }
    }

    public function Registrar($objetivo, $idProyecto){
        try {
            $fecha = date('Y-m-d H:i:s');
 
            $consulta = $this->db->prepare("INSERT INTO objetivo (id_proyecto, nombre, descripcion, avance, ponderacion, eliminado, id_usuario_creacion, fecha_creacion, id_ultimo_usuario, fecha_modificacion)
                                            VALUES (:idProyecto, :nombre, :descripcion, 0, 0, 0, :idUsuarioCreacion, :fechaCreacion, :idUltimoUsuario, :fechaModificacion);");
            $consulta->bindParam(':idProyecto', $idProyecto);
            $consulta->bindParam(':nombre', $objetivo['nombre']);
            $consulta->bindParam(':descripcion', $objetivo['descripcion']);
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

    public function Modificar($objetivo)
    {
        try {
            $fecha = date('Y-m-d H:i:s');
 
            $consulta = $this->db->prepare("UPDATE objetivo SET nombre = :nombre, descripcion = :descripcion, id_ultimo_usuario = :idUltimoUsuario , fecha_modificacion = :fechaModificacion WHERE id_objetivo = :idObjetivo;");
            $consulta->bindParam(':idObjetivo', $objetivo['idObjetivo']);
            $consulta->bindParam(':nombre', $objetivo['nombre']);
            $consulta->bindParam(':descripcion', $objetivo['descripcion']);
            $consulta->bindParam(':idUltimoUsuario', $_SESSION['idUsuario']);
            $consulta->bindParam(':fechaModificacion', $fecha);

            if (!$consulta->execute()) {
                throw new Exception($consulta->errorInfo()[2]);
            }
        } catch (Exception $e) {
            throw new Exception("Error en metodo : ".__FUNCTION__.' | '. $e->getMessage()."\n". $e->getTraceAsString());
        }
    }

    public function Eliminar($idObjetivo)
    {
        try {
            $fecha = date('Y-m-d H:i:s');
 
            $consulta = $this->db->prepare("UPDATE objetivo SET eliminado = 1, id_ultimo_usuario = :idUltimoUsuario , fecha_modificacion = :fechaModificacion WHERE id_objetivo = :idObjetivo;");
            $consulta->bindParam(':idObjetivo', $idObjetivo);
            $consulta->bindParam(':idUltimoUsuario', $_SESSION['idUsuario']);
            $consulta->bindParam(':fechaModificacion', $fecha);

            if (!$consulta->execute()) {
                throw new Exception($consulta->errorInfo()[2]);
            }
        } catch (Exception $e) {
            throw new Exception("Error en metodo : ".__FUNCTION__.' | '. $e->getMessage()."\n". $e->getTraceAsString());
        }
    }

    public function AsignarPonderacion($idObjetivo, $ponderacion)
    {
        try {
            $fecha = date('Y-m-d H:i:s');
 
            $consulta = $this->db->prepare("UPDATE objetivo SET ponderacion = :ponderacion, id_ultimo_usuario = :idUltimoUsuario, fecha_modificacion = :fechaModificacion  WHERE id_objetivo = :idObjetivo;");
            $consulta->bindParam(':idObjetivo', $idObjetivo);
            $consulta->bindParam(':ponderacion', $ponderacion);
            $consulta->bindParam(':idUltimoUsuario', $_SESSION['idUsuario']);
            $consulta->bindParam(':fechaModificacion', $fecha);

            if (!$consulta->execute()) {
                throw new Exception($consulta->errorInfo()[2]);
            }
            
            if(!($consulta->rowCount() > 0))
            {
                throw new Exception("No se pudo actualizar la ponderacion de uno o mas objetivos.");
            }
        } catch (Exception $e) {
            throw new Exception("Error en metodo : ".__FUNCTION__.' | '. $e->getMessage()."\n". $e->getTraceAsString());
        }
    }

    public function ActualizarAvance($idObjetivo, $avance)
    {
        try {
            $fecha = date('Y-m-d H:i:s');
 
            $consulta = $this->db->prepare("UPDATE objetivo SET avance = :avance, id_ultimo_usuario = :idUltimoUsuario, fecha_modificacion = :fechaModificacion  WHERE id_objetivo = :idObjetivo");
            $consulta->bindParam(':idObjetivo', $idObjetivo);
            $consulta->bindParam(':avance', $avance);
            $consulta->bindParam(':idUltimoUsuario', $_SESSION['idUsuario']);
            $consulta->bindParam(':fechaModificacion', $fecha);

            if (!$consulta->execute()) {
                throw new Exception($consulta->errorInfo()[2]);
            }
            
            if(!($consulta->rowCount() > 0))
            {
                throw new Exception("No se pudo actualizar el avance del objetivo.");
            }
        } catch (Exception $e) {
            throw new Exception("Error en metodo : ".__FUNCTION__.' | '. $e->getMessage()."\n". $e->getTraceAsString());
        }
    }

    public function ResumenAvance($idProyecto)
    {
        try {
            $this->ejecuciones = array();
            $consulta = $this->db->prepare("SELECT SUM(avance * ponderacion / 100) AS avance
                                            FROM objetivo
                                            WHERE id_proyecto = :idProyecto
                                            AND eliminado = 0;");
            $consulta->bindParam(':idProyecto', $idProyecto);

            if (!$consulta->execute()) {
                throw new Exception($consulta->errorInfo()[2]);
            }
            
            while($filas = $consulta->fetch()){
                $this->ejecuciones[] = $filas;
            }
            
            return $this->ejecuciones[0];
        } catch (Exception $e) {
            throw new Exception("Error en metodo : ".__FUNCTION__.' | '. $e->getMessage()."\n". $e->getTraceAsString());
        }
    }
}
?>