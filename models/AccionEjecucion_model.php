<?php
class AccionEjecucion_model
{
	private $db;
    private $ejecuciones;

	public function __construct($dbConexion)
    {
        $this->db = $dbConexion;
        $this->ejecuciones = array();
    }

    public function ListarPorAccion($idAccion)
    {
        try {
            $this->ejecuciones = array();
            $consulta = $this->db->prepare("SELECT eje.id_accion_ejecucion, eje.fecha_inicio, eje.fecha_fin, eje.meta, eje.avance, eje.observacion, acc.cuantitativa, eje.medida_adicional, COUNT(par.id_accion_ejecucion_participante) as cantidadParticipante, COUNT(fot.id_accion_ejecucion_foto) as cantidadFoto
                                            FROM accion_ejecucion eje
                                            join accion acc on eje.id_accion = acc.id_accion
                                            LEFT JOIN accion_ejecucion_participante par on eje.id_accion_ejecucion = par.id_accion_ejecucion
                                                                                        and par.eliminado = 0
                                            LEFT JOIN accion_ejecucion_foto fot ON eje.id_accion_ejecucion = fot.id_accion_ejecucion
                                                                                    and fot.eliminado = 0
                                            where eje.eliminado = 0 
                                            and eje.id_accion = :idAccion
                                            GROUP BY eje.id_accion_ejecucion;");
            $consulta->bindParam(':idAccion', $idAccion);

            if (!$consulta->execute()) {
                throw new Exception($consulta->errorInfo()[2]);
            }
            
            while($filas = $consulta->fetch()){
                $this->ejecuciones[] = $filas;
            }
            
            return $this->ejecuciones;
        } catch (Exception $e) {
            throw new Exception("Error en metodo : ".__FUNCTION__.' | '. $e->getMessage()."\n". $e->getTraceAsString());
        }
    }

    public function ListarMedidaAdicionalPorAccion($idAccion)
    {
        try {
            $this->ejecuciones = array();
            $consulta = $this->db->prepare("SELECT eje.id_accion_ejecucion, eje.fecha_ejecucion, eje.medida_adicional, acc.unidad_dato_adicional
                                            FROM accion_ejecucion eje
                                            JOIN accion acc ON eje.id_accion = acc.id_accion
                                            where eje.eliminado = 0 
                                            and eje.id_accion = :idAccion
                                            GROUP BY eje.id_accion_ejecucion;");
            $consulta->bindParam(':idAccion', $idAccion);

            if (!$consulta->execute()) {
                throw new Exception($consulta->errorInfo()[2]);
            }
            
            while($filas = $consulta->fetch()){
                $this->ejecuciones[] = $filas;
            }
            
            return $this->ejecuciones;
        } catch (Exception $e) {
            throw new Exception("Error en metodo : ".__FUNCTION__.' | '. $e->getMessage()."\n". $e->getTraceAsString());
        }
    }

    public function FiltrarPorAccion($idAccion, $fechaInicio, $fechaFin)
    {
        try {
            $this->ejecuciones = array();
            $consulta = $this->db->prepare("SELECT eje.id_accion_ejecucion, eje.fecha_inicio, eje.fecha_fin, eje.meta, eje.avance, eje.observacion
                                            FROM accion_ejecucion eje
                                            where eje.eliminado = 0 
                                            and eje.id_accion = :idAccion
                                            and eje.`fecha_fin` BETWEEN :fechaInicio AND :fechaFin;
                                            GROUP BY eje.id_accion_ejecucion;");
            $consulta->bindParam(':idAccion', $idAccion);
            $consulta->bindParam(':fechaInicio', $fechaInicio);
            $consulta->bindParam(':fechaFin', $fechaFin);

            if (!$consulta->execute()) {
                throw new Exception($consulta->errorInfo()[2]);
            }
            
            while($filas = $consulta->fetch()){
                $this->ejecuciones[] = $filas;
            }
            
            return $this->ejecuciones;
        } catch (Exception $e) {
            throw new Exception("Error en metodo : ".__FUNCTION__.' | '. $e->getMessage()."\n". $e->getTraceAsString());
        }
    }

    public function BuscarPorId($idAccion, $idAccionEjecucion)
    {
        try {
            $consulta = $this->db->prepare("SELECT *
                                            FROM accion_ejecucion
                                            where id_accion_ejecucion = :idAccionEjecucion
                                            and id_accion = :idAccion
                                            and eliminado = 0;");
            $consulta->bindParam(':idAccionEjecucion', $idAccionEjecucion);
            $consulta->bindParam(':idAccion', $idAccion);

            if (!$consulta->execute()) {
                throw new Exception($consulta->errorInfo()[2]);
            }
            
            while($filas = $consulta->fetch()){
                $this->ejecuciones[] = $filas;
            }
            
            if (count($this->ejecuciones) > 0) {
                return $this->ejecuciones[0];
            }
            else{
                return false;
            }
        } catch (Exception $e) {
            throw new Exception("Error en metodo : ".__FUNCTION__.' | '. $e->getMessage()."\n". $e->getTraceAsString());
        }
    }

    public function Registrar($idAccion, $ejecucion)
    {
        try {
            $fecha = date('Y-m-d H:i:s');
 
            $consulta = $this->db->prepare("INSERT INTO accion_ejecucion (id_accion, fecha_inicio, fecha_fin, meta, avance, observacion, medida_adicional, eliminado, id_usuario_creacion, fecha_creacion, id_ultimo_usuario, fecha_modificacion)
                                            VALUES (:idAccion, :fechaInicio, :fechaFin, :meta, 0, '', 0, 0,  :idUsuarioCreacion, :fechaCreacion, :idUltimoUsuario, :fechaModificacion);");
            $consulta->bindParam(':idAccion', $idAccion);
            $consulta->bindParam(':fechaInicio', $ejecucion['fechaInicio']);
            $consulta->bindParam(':fechaFin', $ejecucion['fechaFin']);
            $consulta->bindParam(':meta', $ejecucion['meta']);
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

    public function Modificar($ejecucion)
    {
        try {
            $fecha = date('Y-m-d H:i:s');
 
            $consulta = $this->db->prepare("UPDATE accion_ejecucion SET fecha_inicio = :fechaInicio, fecha_fin = :fechaFin, meta = :meta, id_ultimo_usuario = :idUltimoUsuario, fecha_modificacion = :fechaModificacion WHERE id_accion_ejecucion = :idAccionEjecucion");
            $consulta->bindParam(':idAccionEjecucion', $ejecucion['idAccionEjecucion']);
            $consulta->bindParam(':fechaInicio', $ejecucion['fechaInicio']);
            $consulta->bindParam(':fechaFin', $ejecucion['fechaFin']);
            $consulta->bindParam(':meta', $ejecucion['meta']);
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

    public function Eliminar($idAccionEjecucion)
    {
        try {
            $fecha = date('Y-m-d H:i:s');
 
            $consulta = $this->db->prepare("UPDATE accion_ejecucion SET eliminado = 1, id_ultimo_usuario = :idUltimoUsuario, fecha_modificacion = :fechaModificacion WHERE id_accion_ejecucion = :idAccionEjecucion");
            $consulta->bindParam(':idAccionEjecucion', $idAccionEjecucion);
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

    public function RegistrarEjecucion($ejecucion)
    {
        try {
            $fecha = date('Y-m-d H:i:s');
 
            $consulta = $this->db->prepare("UPDATE accion_ejecucion SET avance = :avance, observacion = :observacion, medida_adicional = :medidaAdicional, id_ultimo_usuario = :idUltimoUsuario, fecha_modificacion = :fechaModificacion, fecha_ejecucion = :fechaEjecucion WHERE id_accion_ejecucion = :idAccionEjecucion");
            $consulta->bindParam(':idAccionEjecucion', $ejecucion['idAccionEjecucion']);
            $consulta->bindParam(':avance', $ejecucion['avance']);
            $consulta->bindParam(':observacion', $ejecucion['observacion']);
            $consulta->bindParam(':medidaAdicional', $ejecucion['medidaAdicional']);
            $consulta->bindParam(':idUltimoUsuario', $_SESSION['idUsuario']);
            $consulta->bindParam(':fechaModificacion', $fecha);
            $consulta->bindParam(':fechaEjecucion', $ejecucion['fechaEjecucion']);

            if (!$consulta->execute()) {
                throw new Exception($consulta->errorInfo()[2]);
            }
            
            return $this->db->lastInsertId();
        } catch (Exception $e) {
            throw new Exception("Error en metodo : ".__FUNCTION__.' | '. $e->getMessage()."\n". $e->getTraceAsString());
        }
    }

    public function ResumenAvance($idAccion)
    {
        try {
            $this->ejecuciones = array();
            $consulta = $this->db->prepare("SELECT IFNULL(SUM(eje.avance),0) AS avance, acc.cuantitativa, acc.`meta_cuantitativa`
                                            FROM accion acc
                                            LEFT JOIN accion_ejecucion eje ON eje.id_accion = acc.id_accion
                                                                            AND eje.`eliminado` = 0
                                            WHERE acc.eliminado = 0 
                                            AND acc.id_accion = :idAccion
                                            GROUP BY acc.`id_accion`;");
            $consulta->bindParam(':idAccion', $idAccion);

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

    public function ResumenMeta($idAccion, $idAccionEjecucion)
    {
        try {
            $this->ejecuciones = array();
            $consulta = $this->db->prepare("SELECT IFNULL(SUM(eje.meta),0) AS meta, acc.cuantitativa, acc.`meta_cuantitativa`
                                            FROM accion acc
                                            LEFT JOIN accion_ejecucion eje ON eje.id_accion = acc.id_accion
                                                                            AND eje.`eliminado` = 0
                                                                            AND eje.id_accion_ejecucion != :idAccionEjecucion
                                            WHERE acc.eliminado = 0 
                                            AND acc.id_accion = :idAccion
                                            GROUP BY acc.`id_accion`;");
            $consulta->bindParam(':idAccion', $idAccion);
            $consulta->bindParam(':idAccionEjecucion', $idAccionEjecucion);

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