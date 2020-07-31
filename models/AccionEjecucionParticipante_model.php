<?php
class AccionEjecucionParticipante_model
{
	private $db;
    private $participantes;

	public function __construct($dbConexion)
    {
        $this->db = $dbConexion;
        $this->participantes = array();
    }

    public function ListarPorEjecucion($idAccionEjecucion)
    {
        try {
            $this->participantes = array();

            $consulta = $this->db->prepare("SELECT par.id_accion_ejecucion_participante, par.rol, par.id_persona, per.nombre, per.paterno, per.materno, per.genero, per.fecha_nacimiento, per.dni, CONCAT(jef.paterno, ' ', jef.materno, ' ', jef.nombre ) as jefe
                                            FROM accion_ejecucion_participante par  
                                            JOIN persona per ON par.id_persona = per.id_persona
                                            LEFT JOIN familia_integrante f_int ON per.id_persona = f_int.id_persona
                                            LEFT JOIN familia_integrante fam_jef ON f_int.id_familia = fam_jef.id_familia
                                                                            AND fam_jef.rol = 'JEFE'
                                            LEFT JOIN persona jef On fam_jef.id_persona = jef.id_persona
                                            where par.eliminado = 0 
                                            and par.id_accion_ejecucion = :idAccionEjecucion;");
            $consulta->bindParam(':idAccionEjecucion', $idAccionEjecucion);

            if (!$consulta->execute()) {
                throw new Exception($consulta->errorInfo()[2]);
            }
            
            while($filas = $consulta->fetch()){
                $this->participantes[] = $filas;
            }
            
            return $this->participantes;
        } catch (Exception $e) {
            throw new Exception("Error en metodo : ".__FUNCTION__.' | '. $e->getMessage()."\n". $e->getTraceAsString());
        }
    }

    public function ListarPorActividad($idActividad)
    {
        try {
            $this->participantes = array();

            $consulta = $this->db->prepare("SELECT par.id_accion_ejecucion_participante, par.rol, par.id_persona, per.nombre, per.paterno, per.materno, per.dni, CONCAT(jef.paterno, ' ', jef.materno, ' ', jef.nombre ) as jefe, acc.participacion_familiar as familiar, acc.nombre as accion
                                            FROM accion_ejecucion_participante par 
                                            JOIN accion_ejecucion eje ON par.id_accion_ejecucion = eje.id_accion_ejecucion
                                                                    AND eje.eliminado = 0
                                            JOIN accion acc ON eje.id_accion = acc.id_accion
                                                            AND acc.eliminado = 0
                                                            AND acc.id_actividad = :idActividad
                                            JOIN persona per ON par.id_persona = per.id_persona
                                            LEFT JOIN familia_integrante f_int ON per.id_persona = f_int.id_persona
                                            LEFT JOIN familia_integrante fam_jef ON f_int.id_familia = fam_jef.id_familia
                                                                            AND fam_jef.rol = 'JEFE'
                                            LEFT JOIN persona jef On fam_jef.id_persona = jef.id_persona
                                            where par.eliminado = 0
                                            ORDER BY per.paterno;");
            $consulta->bindParam(':idActividad', $idActividad);

            if (!$consulta->execute()) {
                throw new Exception($consulta->errorInfo()[2]);
            }
            
            while($filas = $consulta->fetch()){
                $this->participantes[] = $filas;
            }
            
            return $this->participantes;
        } catch (Exception $e) {
            throw new Exception("Error en metodo : ".__FUNCTION__.' | '. $e->getMessage()."\n". $e->getTraceAsString());
        }
    }

    public function Registrar($idAccionEjecucion, $participante)
    {
        try {
            $fecha = date('Y-m-d H:i:s');
 
            $consulta = $this->db->prepare("INSERT INTO accion_ejecucion_participante (id_accion_ejecucion, id_persona, rol, eliminado, id_usuario_creacion, fecha_creacion, id_ultimo_usuario, fecha_modificacion)
                                            VALUES (:idAccionEjecucion, :idPersona, :rol, 0, :idUsuarioCreacion, :fechaCreacion, :idUltimoUsuario, :fechaModificacion);");
            $consulta->bindParam(':idAccionEjecucion', $idAccionEjecucion);
            $consulta->bindParam(':idPersona', $participante['idPersona']);
            $consulta->bindParam(':rol', $participante['rol']);
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

    public function ActualizarRol($participante)
    {
        try {
            $fecha = date('Y-m-d H:i:s');
 
            $consulta = $this->db->prepare("UPDATE accion_ejecucion_participante SET rol = :rol, id_ultimo_usuario = :idUltimoUsuario , fecha_modificacion = :fechaModificacion WHERE id_accion_ejecucion_participante = :idAccionEjecucionParticipante;");
            $consulta->bindParam(':rol', $participante['rol']);
            $consulta->bindParam(':idAccionEjecucionParticipante', $participante['idAccionEjecucionParticipante']);
            $consulta->bindParam(':idUltimoUsuario', $_SESSION['idUsuario']);
            $consulta->bindParam(':fechaModificacion', $fecha);

            if (!$consulta->execute()) {
                throw new Exception($consulta->errorInfo()[2]);
            }
        } catch (Exception $e) {
            throw new Exception("Error en metodo : ".__FUNCTION__.' | '. $e->getMessage()."\n". $e->getTraceAsString());
        }
    }

    public function Eliminar($idAccionEjecucionParticipante)
    {
        try {
            $fecha = date('Y-m-d H:i:s');
 
            $consulta = $this->db->prepare("UPDATE accion_ejecucion_participante SET eliminado = 1, id_ultimo_usuario = :idUltimoUsuario , fecha_modificacion = :fechaModificacion WHERE id_accion_ejecucion_participante = :idAccionEjecucionParticipante;");
            $consulta->bindParam(':idAccionEjecucionParticipante', $idAccionEjecucionParticipante);
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