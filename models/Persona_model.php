<?php
class Persona_model
{
	private $db;
    private $personas;

	public function __construct($dbConexion)
    {
        $this->db = $dbConexion;
        $this->personas = array();
    }

    public function Listar($idComunidad)
    {
        try {
            $this->personas = array();
            if ($idComunidad > 0) {
               $consulta = $this->db->prepare("SELECT per.id_persona, per.nombre, per.paterno, per.materno, per.tipo_documento, per.dni, per.genero, per.fecha_nacimiento, CONCAT(jef.paterno, ' ', jef.materno, ' ', jef.nombre ) as jefe, com.nombre as comunidad
                                            FROM persona per 
                                            LEFT JOIN familia_integrante f_int on per.id_persona = f_int.id_persona
                                            LEFT JOIN familia_integrante f_jef on f_int.id_familia = f_jef.id_familia
                                            LEFT join persona jef ON f_jef.id_persona = jef.id_persona
                                            JOIN comunidad com on per.id_comunidad = com.id_comunidad
                                            WHERE per.eliminado = 0
                                            AND per.id_comunidad = :idComunidad
                                            GROUP BY per.`id_persona`, CONCAT(jef.paterno, ' ', jef.materno, ' ', jef.nombre )
                                            ORDER BY per.paterno ASC;");
                $consulta->bindParam(':idComunidad', $idComunidad);
            }
            else{
                $consulta = $this->db->prepare("SELECT per.id_persona, per.nombre, per.paterno, per.materno, per.tipo_documento, per.dni, per.genero, per.fecha_nacimiento, CONCAT(jef.paterno, ' ', jef.materno, ' ', jef.nombre ) as jefe, com.nombre as comunidad
                                            FROM persona per 
                                            LEFT JOIN familia_integrante f_int on per.id_persona = f_int.id_persona
                                            LEFT JOIN familia_integrante f_jef on f_int.id_familia = f_jef.id_familia
                                            LEFT join persona jef ON f_jef.id_persona = jef.id_persona
                                            JOIN comunidad com on per.id_comunidad = com.id_comunidad
                                            WHERE per.eliminado = 0 
                                            GROUP BY per.`id_persona`, CONCAT(jef.paterno, ' ', jef.materno, ' ', jef.nombre )
                                            ORDER BY per.paterno ASC;");
            }

            if (!$consulta->execute()) {
                throw new Exception($consulta->errorInfo()[2]);
            }
            
            while($filas = $consulta->fetch()){
                $this->personas[] = $filas;
            }
            
            return $this->personas;
        } catch (Exception $e) {
            throw new Exception("Error en metodo : ".__FUNCTION__.' | '. $e->getMessage()."\n". $e->getTraceAsString());
        }
    }

    public function ListarPorProyecto($idProyecto)
    {
        try {
            $this->personas = array();
            $consulta = $this->db->prepare("SELECT per.id_persona, per.nombre, per.paterno, per.materno, per.dni
                                            FROM persona per 
                                            JOIN accion_ejecucion_participante par ON per.`id_persona` = par.`id_persona`
                                            JOIN accion_ejecucion eje ON par.`id_accion_ejecucion` = eje.`id_accion_ejecucion`
                                            JOIN accion acc ON eje.`id_accion` = acc.`id_accion`
                                            JOIN actividad act ON acc.`id_actividad` = act.`id_actividad`
                                            JOIN producto pro ON act.`id_producto` = pro.`id_producto`
                                            JOIN objetivo obj ON pro.`id_objetivo` = obj.`id_objetivo`
                                                AND obj.`id_proyecto` = :idProyecto
                                            WHERE per.eliminado = 0
                                            GROUP BY per.id_persona
                                            ORDER BY per.paterno ASC;");

            $consulta->bindParam(':idProyecto', $idProyecto);

            if (!$consulta->execute()) {
                throw new Exception($consulta->errorInfo()[2]);
            }
            
            while($filas = $consulta->fetch()){
                $this->personas[] = $filas;
            }
            
            return $this->personas;
        } catch (Exception $e) {
            throw new Exception("Error en metodo : ".__FUNCTION__.' | '. $e->getMessage()."\n". $e->getTraceAsString());
        }
    }
    
    public function BuscarPorId($idPersona)
    {
        try {
            $consulta = $this->db->prepare("SELECT *
                                            FROM persona
                                            where id_persona = :idPersona
                                            and eliminado = 0;");
            $consulta->bindParam(':idPersona', $idPersona);

            if (!$consulta->execute()) {
                throw new Exception($consulta->errorInfo()[2]);
            }
            
            while($filas = $consulta->fetch()){
                $this->personas[] = $filas;
            }
            
            if (count($this->personas) > 0) {
                return $this->personas[0];
            }
            else{
                return false;
            }
        } catch (Exception $e) {
            throw new Exception("Error en metodo : ".__FUNCTION__.' | '. $e->getMessage()."\n". $e->getTraceAsString());
        }
    }

    public function BuscarPorDni($dni)
    {
        try {
            $consulta = $this->db->prepare("SELECT *
                                            FROM persona
                                            where dni = :dni
                                            and eliminado = 0;");
            $consulta->bindParam(':dni', $dni);

            if (!$consulta->execute()) {
                throw new Exception($consulta->errorInfo()[2]);
            }
            
            while($filas = $consulta->fetch()){
                $this->personas[] = $filas;
            }
            
            if (count($this->personas) > 0) {
                return $this->personas[0];
            }
            else{
                return false;
            }
        } catch (Exception $e) {
            throw new Exception("Error en metodo : ".__FUNCTION__.' | '. $e->getMessage()."\n". $e->getTraceAsString());
        }
    }    

    public function Registrar($persona)
    {
        try {
            $fecha = date('Y-m-d H:i:s');
 
            $consulta = $this->db->prepare("INSERT INTO persona (nombre, paterno, materno, dni, genero, fecha_nacimiento, eliminado, id_usuario_creacion, fecha_creacion, id_ultimo_usuario, fecha_modificacion, id_comunidad, tipo_documento)
                                            VALUES (:nombre, :paterno, :materno, :dni, :genero, :fechaNacimiento, 0, :idUsuarioCreacion, :fechaCreacion, :idUltimoUsuario, :fechaModificacion, :idComunidad, :tipoDocumento);");
            $consulta->bindParam(':nombre', $persona['nombre']);
            $consulta->bindParam(':paterno', $persona['paterno']);
            $consulta->bindParam(':materno', $persona['materno']);
            $consulta->bindParam(':dni', $persona['dni']);
            $consulta->bindParam(':genero', $persona['genero']);
            $consulta->bindParam(':fechaNacimiento', $persona['fechaNacimiento']);
            $consulta->bindParam(':idComunidad', $persona['idComunidad']);
            $consulta->bindParam(':idUsuarioCreacion', $_SESSION['idUsuario']);
            $consulta->bindParam(':fechaCreacion', $fecha);
            $consulta->bindParam(':idUltimoUsuario', $_SESSION['idUsuario']);
            $consulta->bindParam(':fechaModificacion', $fecha);
			$consulta->bindParam(':tipoDocumento', $persona['tipoDocumento']);

            if (!$consulta->execute()) {
                throw new Exception($consulta->errorInfo()[2]);
            }
            
            return $this->db->lastInsertId();
        } catch (Exception $e) {
            throw new Exception("Error en metodo : ".__FUNCTION__.' | '. $e->getMessage()."\n". $e->getTraceAsString());
        }
    }

    public function Modificar($persona)
    {
        try {
            $fecha = date('Y-m-d H:i:s');
 
            $consulta = $this->db->prepare("UPDATE persona SET nombre = :nombre, paterno = :paterno, materno = :materno, dni = :dni, genero = :genero, fecha_nacimiento = :fechaNacimiento, id_ultimo_usuario = :idUltimoUsuario , fecha_modificacion = :fechaModificacion, id_comunidad = :idComunidad, tipo_documento=:tipoDocumento WHERE id_persona = :idPersona;");
            $consulta->bindParam(':idPersona', $persona['idPersona']);
            $consulta->bindParam(':nombre', $persona['nombre']);
            $consulta->bindParam(':paterno', $persona['paterno']);
            $consulta->bindParam(':materno', $persona['materno']);
            $consulta->bindParam(':dni', $persona['dni']);
            $consulta->bindParam(':genero', $persona['genero']);
            $consulta->bindParam(':fechaNacimiento', $persona['fechaNacimiento']);
            $consulta->bindParam(':idComunidad', $persona['idComunidad']);
            $consulta->bindParam(':idUltimoUsuario', $_SESSION['idUsuario']);
            $consulta->bindParam(':fechaModificacion', $fecha);
			$consulta->bindParam(':tipoDocumento', $persona['tipoDocumento']);

            if (!$consulta->execute()) {
                throw new Exception($consulta->errorInfo()[2]);
            }
        } catch (Exception $e) {
            throw new Exception("Error en metodo : ".__FUNCTION__.' | '. $e->getMessage()."\n". $e->getTraceAsString());
        }
    }

    public function Eliminar($idPersona)
    {
        try {
            $fecha = date('Y-m-d H:i:s');
 
            $consulta = $this->db->prepare("UPDATE persona SET eliminado = 1, id_ultimo_usuario = :idUltimoUsuario , fecha_modificacion = :fechaModificacion WHERE id_persona = :idPersona;");
            $consulta->bindParam(':idPersona', $idPersona);
            $consulta->bindParam(':idUltimoUsuario', $_SESSION['idUsuario']);
            $consulta->bindParam(':fechaModificacion', $fecha);

            if (!$consulta->execute()) {
                throw new Exception($consulta->errorInfo()[2]);
            }
        } catch (Exception $e) {
            throw new Exception("Error en metodo : ".__FUNCTION__.' | '. $e->getMessage()."\n". $e->getTraceAsString());
        }
    }

    public function BuscarDniRegistrado($dni, $idPersona)
    {
        $this->personas = array();
        try {
            $consulta = $this->db->prepare("SELECT *
                                            FROM persona
                                            where id_persona != :idPersona
                                            and dni = :dni
                                            and eliminado = 0;");
            $consulta->bindParam(':idPersona', $idPersona);
            $consulta->bindParam(':dni', $dni);

            if (!$consulta->execute()) {
                throw new Exception($consulta->errorInfo()[2]);
            }
            
            while($filas = $consulta->fetch()){
                $this->personas[] = $filas;
            }
            
            if (count($this->personas) > 0) {
                return true;
            }
            else{
                return false;
            }
        } catch (Exception $e) {
            throw new Exception("Error en metodo : ".__FUNCTION__.' | '. $e->getMessage()."\n". $e->getTraceAsString());
        }
    }
}
?>