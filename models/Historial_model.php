<?php
class Historial_model
{
	private $db;
    private $historiales;

	public function __construct($dbConexion){
        $this->db = $dbConexion;
        $this->historiales = array();
    }

    public function FiltrarPorProyecto($idProyecto, $fechaInicio, $fechaFin)
    {
        try {
            $this->historiales = array();
            $consulta = $this->db->prepare("SELECT his.fecha_creacion, usu.nombre, his.accion as accionEjecutada, acc.nombre as accion, act.nombre as actividad, pro.nombre as producto, obj.nombre as objetivo
                                            FROM historial his
                                            JOIN accion_ejecucion eje on his.id_accion_ejecucion = eje.id_accion_ejecucion
                                            JOIN accion acc on eje.id_accion = acc.id_accion
                                            join actividad act on acc.id_actividad = act.id_actividad
                                            join producto pro on act.id_producto = pro.id_producto
                                            join objetivo obj on pro.id_objetivo = obj.id_objetivo
                                                                and obj.id_proyecto = :idProyecto
                                            JOIN usuario usu on his.id_usuario = usu.id_usuario
                                            WHERE his.fecha_creacion BETWEEN :fechaInicio AND :fechaFin ;");
            $consulta->bindParam(':idProyecto', $idProyecto);
            $consulta->bindParam(':fechaInicio', $fechaInicio);
            $consulta->bindParam(':fechaFin', $fechaFin);

            if (!$consulta->execute()) {
                throw new Exception($consulta->errorInfo()[2]);
            }
            
            while($filas = $consulta->fetch()){
                $this->historiales[] = $filas;
            }
            
            return $this->historiales;
        } catch (Exception $e) {
            throw new Exception("Error en metodo : ".__FUNCTION__.' | '. $e->getMessage()."\n". $e->getTraceAsString());
        }
    }

    public function Registrar($accion, $idAccionEjecucion)
    {
        try {
            $fecha = date('Y-m-d H:i:s');
 
            $consulta = $this->db->prepare("INSERT INTO historial (id_usuario, id_accion_ejecucion, accion, fecha_creacion)
                                            VALUES (:idUsuario, :idAccionEjecucion, :accion, :fechaCreacion);");
            $consulta->bindParam(':accion', $accion);
            $consulta->bindParam(':idAccionEjecucion', $idAccionEjecucion);
            $consulta->bindParam(':idUsuario', $_SESSION['idUsuario']);
            $consulta->bindParam(':fechaCreacion', $fecha);

            if (!$consulta->execute()) {
                throw new Exception($consulta->errorInfo()[2]);
            }
            
            return $this->db->lastInsertId();
        } catch (Exception $e) {
            throw new Exception("Error en metodo : ".__FUNCTION__.' | '. $e->getMessage()."\n". $e->getTraceAsString());
        }
    }
}
?>