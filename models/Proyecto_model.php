<?php
class Proyecto_model
{
	private $db;

    private $proyectos;

	public function __construct($dbConexion){
        $this->db = $dbConexion;
        $this->proyectos = array();
    }

    public function Listar(){
        try {
            $consulta = $this->db->prepare("SELECT id_proyecto, nombre, avance FROM proyecto where eliminado = 0;");

            if (!$consulta->execute()) {
                throw new Exception($consulta->errorInfo()[2]);
            }
            
            while($filas = $consulta->fetch()){
                $this->proyectos[] = $filas;
            }
            
            return $this->proyectos;
        } catch (Exception $e) {
            throw new Exception("Error en metodo : ".__FUNCTION__.' | '. $e->getMessage()."\n". $e->getTraceAsString());
        }
    }

    public function ListarPorUsuario(){
        try {
            $consulta = $this->db->prepare("SELECT pro.id_proyecto, pro.nombre, pro.descripcion, pro.avance 
                                            FROM proyecto pro
                                            JOIN usuario_proyecto acc on pro.id_proyecto = acc.id_proyecto
                                                                    and acc.id_usuario = :idUsuario
                                                                    and acc.eliminado = 0 
                                            where pro.eliminado = 0;");
            $consulta->bindParam(':idUsuario', $_SESSION['idUsuario']);

            if (!$consulta->execute()) {
                throw new Exception($consulta->errorInfo()[2]);
            }
            
            while($filas = $consulta->fetch()){
                $this->proyectos[] = $filas;
            }
            
            return $this->proyectos;
        } catch (Exception $e) {
            throw new Exception("Error en metodo : ".__FUNCTION__.' | '. $e->getMessage()."\n". $e->getTraceAsString());
        }
    }

    public function BuscarPorId($idProyecto){
        try {
            $consulta = $this->db->prepare("SELECT * FROM proyecto where id_proyecto = :idProyecto and eliminado = 0;");
            $consulta->bindParam(':idProyecto', $idProyecto);

            if (!$consulta->execute()) {
                throw new Exception($consulta->errorInfo()[2]);
            }
            
            while($filas = $consulta->fetch()){
                $this->proyectos[] = $filas;
            }
            
            if (count($this->proyectos) > 0) {
                return $this->proyectos[0];
            }
            else{
                return false;
            }
        } catch (Exception $e) {
            throw new Exception("Error en metodo : ".__FUNCTION__.' | '. $e->getMessage()."\n". $e->getTraceAsString());
        }
    }

    public function Registrar($proyecto){
        try {
            $fecha = date('Y-m-d H:i:s');
            $estado = 'PENDIENTE';
            $avance = 0;

            $consulta = $this->db->prepare("INSERT INTO proyecto (nombre, descripcion, fecha_inicio, fecha_fin, estado, avance, id_usuario_creacion, fecha_creacion, id_ultimo_usuario, fecha_modificacion ,eliminado)
                                            VALUES (:nombre, :descripcion, :fechaInicio, :fechaFin, :estado, :avance, :idUsuarioCreacion, :fechaCreacion, :idUltimoUsuario, :fechaModificacion, 0);");
            $consulta->bindParam(':nombre', $proyecto['nombre']);
            $consulta->bindParam(':descripcion', $proyecto['descripcion']);
            $consulta->bindParam(':fechaInicio', $proyecto['fechaInicio']);
            $consulta->bindParam(':fechaFin', $proyecto['fechaFin']);
            $consulta->bindParam(':estado', $estado);
            $consulta->bindParam(':avance', $avance);
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

    public function Eliminar($idProyecto){
        try {
            $fecha = date('Y-m-d H:i:s');
 
            $consulta = $this->db->prepare("UPDATE proyecto SET eliminado = 1, id_ultimo_usuario = :idUltimoUsuario , fecha_modificacion = :fechaModificacion WHERE id_proyecto  = :idProyecto;");
            $consulta->bindParam(':idProyecto', $idProyecto);
            $consulta->bindParam(':idUltimoUsuario', $_SESSION['idUsuario']);
            $consulta->bindParam(':fechaModificacion', $fecha);

            if (!$consulta->execute()) {
                throw new Exception($consulta->errorInfo()[2]);
            }
        } catch (Exception $e) {
            throw new Exception("Error en metodo : ".__FUNCTION__.' | '. $e->getMessage()."\n". $e->getTraceAsString());
        }
    }

    public function ActualizarAvance($idProyecto, $avance)
    {
        try {
            $fecha = date('Y-m-d H:i:s');
 
            $consulta = $this->db->prepare("UPDATE proyecto SET avance = :avance, id_ultimo_usuario = :idUltimoUsuario, fecha_modificacion = :fechaModificacion  WHERE id_proyecto = :idProyecto");
            $consulta->bindParam(':idProyecto', $idProyecto);
            $consulta->bindParam(':avance', $avance);
            $consulta->bindParam(':idUltimoUsuario', $_SESSION['idUsuario']);
            $consulta->bindParam(':fechaModificacion', $fecha);

            if (!$consulta->execute()) {
                throw new Exception($consulta->errorInfo()[2]);
            }
            
            if(!($consulta->rowCount() > 0))
            {
                throw new Exception("No se pudo actualizar el avance del proyecto.");
            }
        } catch (Exception $e) {
            throw new Exception("Error en metodo : ".__FUNCTION__.' | '. $e->getMessage()."\n". $e->getTraceAsString());
        }
    }
}
?>