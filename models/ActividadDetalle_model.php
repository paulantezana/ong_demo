<?php
class ActividadDetalle_model
{
	private $db;
    private $actividades;

	public function __construct($dbConexion){
        $this->db = $dbConexion;
        $this->actividades = array();
    }

    public function Registrar($idActividad, $estado, $observacion){
        try {
            $fecha = date('Y-m-d H:i:s');
 
            $consulta = $this->db->prepare("UPDATE actividad_detalle set ultimo = 0, id_ultimo_usuario = :idUltimoUsuario, fecha_modificacion = :fechaModificacion WHERE id_actividad = :idActividad and ultimo = 1;");
            $consulta->bindParam(':idActividad', $idActividad);
            $consulta->bindParam(':idUltimoUsuario', $_SESSION['idUsuario']);
            $consulta->bindParam(':fechaModificacion', $fecha);

            if (!$consulta->execute()) {
                throw new Exception($consulta->errorInfo()[2]);
            }

            $consulta = $this->db->prepare("INSERT INTO actividad_detalle (id_actividad, estado, observacion, ultimo, eliminado, id_usuario_creacion, fecha_creacion, id_ultimo_usuario, fecha_modificacion)
                                            VALUES (:idActividad, :estado, :observacion, 1, 0, :idUsuarioCreacion, :fechaCreacion, :idUltimoUsuario, :fechaModificacion);");
            $consulta->bindParam(':idActividad', $idActividad);
            $consulta->bindParam(':estado', $estado);
            $consulta->bindParam(':observacion', $observacion);
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
}
?>