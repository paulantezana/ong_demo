<?php
class Familia_model
{
	private $db;
    private $familias;

	public function __construct($dbConexion)
    {
        $this->db = $dbConexion;
        $this->familias = array();
    }

    public function Listar()
    {
        try {
            $this->familias = array();
            $consulta = $this->db->prepare("SELECT fam.id_familia, com.`nombre` AS comunidad, per.`paterno`, per.`materno`, per.`nombre`
                                            FROM familia fam
                                            JOIN familia_integrante f_int ON fam.`id_familia` = f_int.`id_familia`
                                                            AND f_int.rol = 'JEFE'
                                            JOIN persona per ON f_int.`id_persona` = per.`id_persona`
                                            JOIN comunidad com ON per.`id_comunidad` = com.`id_comunidad`
                                            WHERE fam.eliminado = 0
                                            ORDER BY com.`nombre`, per.`paterno` ASC;");

            if (!$consulta->execute()) {
                throw new Exception($consulta->errorInfo()[2]);
            }
            
            while($filas = $consulta->fetch()){
                $this->familias[] = $filas;
            }
            
            return $this->familias;
        } catch (Exception $e) {
            throw new Exception("Error en metodo : ".__FUNCTION__.' | '. $e->getMessage()."\n". $e->getTraceAsString());
        }
    }

    public function BuscarPorId($idFamilia)
    {
        try {
            $consulta = $this->db->prepare("SELECT *
                                            FROM familia
                                            where id_familia = :idFamilia
                                            and eliminado = 0;");
            $consulta->bindParam(':idFamilia', $idFamilia);

            if (!$consulta->execute()) {
                throw new Exception($consulta->errorInfo()[2]);
            }
            
            while($filas = $consulta->fetch()){
                $this->familias[] = $filas;
            }
            
            if (count($this->familias) > 0) {
                return $this->familias[0];
            }
            else{
                return false;
            }
        } catch (Exception $e) {
            throw new Exception("Error en metodo : ".__FUNCTION__.' | '. $e->getMessage()."\n". $e->getTraceAsString());
        }
    }

    public function BuscarPorPersona($idPersona)
    {
        try {
            $consulta = $this->db->prepare("SELECT per.nombre, per.paterno, per.materno, per.dni
                                            FROM familia_integrante fam
                                            JOIN familia_integrante fam_jefe on fam.id_familia = fam_jefe.id_familia   
                                                                            and fam_jefe.rol = 'JEFE'
                                            JOIN persona per ON fam_jefe.id_persona = per.id_persona
                                            where fam.id_persona = :idPersona
                                            and fam.eliminado = 0;");
            $consulta->bindParam(':idFamilia', $idFamilia);

            if (!$consulta->execute()) {
                throw new Exception($consulta->errorInfo()[2]);
            }
            
            while($filas = $consulta->fetch()){
                $this->familias[] = $filas;
            }
            
            if (count($this->familias) > 0) {
                return $this->familias[0];
            }
            else{
                return false;
            }
        } catch (Exception $e) {
            throw new Exception("Error en metodo : ".__FUNCTION__.' | '. $e->getMessage()."\n". $e->getTraceAsString());
        }
    }

    public function Registrar()
    {
        try {
            $fecha = date('Y-m-d H:i:s');
 
            $consulta = $this->db->prepare("INSERT INTO familia (eliminado, id_usuario_creacion, fecha_creacion, id_ultimo_usuario, fecha_modificacion)
                                            VALUES (0, :idUsuarioCreacion, :fechaCreacion, :idUltimoUsuario, :fechaModificacion);");
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

    public function Modificar($familia)
    {
        try {
            $fecha = date('Y-m-d H:i:s');
 
            $consulta = $this->db->prepare("UPDATE familia SET id_comunidad = :idComunidad, id_ultimo_usuario = :idUltimoUsuario , fecha_modificacion = :fechaModificacion WHERE id_familia = :idFamilia;");
            $consulta->bindParam(':idFamilia', $familia['idFamilia']);
            $consulta->bindParam(':idComunidad', $familia['idComunidad']);
            $consulta->bindParam(':idUltimoUsuario', $_SESSION['idUsuario']);
            $consulta->bindParam(':fechaModificacion', $fecha);

            if (!$consulta->execute()) {
                throw new Exception($consulta->errorInfo()[2]);
            }
        } catch (Exception $e) {
            throw new Exception("Error en metodo : ".__FUNCTION__.' | '. $e->getMessage()."\n". $e->getTraceAsString());
        }
    }

    public function Eliminar($idFamilia)
    {
        try {
            $fecha = date('Y-m-d H:i:s');
 
            $consulta = $this->db->prepare("UPDATE familia SET eliminado = 1, id_ultimo_usuario = :idUltimoUsuario , fecha_modificacion = :fechaModificacion WHERE id_familia = :idFamilia;");
            $consulta->bindParam(':idFamilia', $idFamilia);
            $consulta->bindParam(':idUltimoUsuario', $_SESSION['idUsuario']);
            $consulta->bindParam(':fechaModificacion', $fecha);

            if (!$consulta->execute()) {
                throw new Exception($consulta->errorInfo()[2]);
            }

            $consulta = $this->db->prepare("UPDATE familia_integrante SET eliminado = 1, id_ultimo_usuario = :idUltimoUsuario , fecha_modificacion = :fechaModificacion WHERE id_familia = :idFamilia;");
            $consulta->bindParam(':idFamilia', $idFamilia);
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