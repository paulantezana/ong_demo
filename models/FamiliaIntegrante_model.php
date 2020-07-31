<?php
class FamiliaIntegrante_model
{
	private $db;
    private $integrantes;

	public function __construct($dbConexion)
    {
        $this->db = $dbConexion;
        $this->integrantes = array();
    }

    public function ListarPorFamilia($idFamilia)
    {
        try {
            $this->integrantes = array();
            $consulta = $this->db->prepare("SELECT f_int.rol, per.paterno, per.`materno`, per.`nombre`, per.`dni`, f_int.id_familia_integrante, f_int.`id_persona`
                                            FROM familia_integrante f_int
                                            JOIN persona per ON f_int.`id_persona` = per.`id_persona`
                                                     AND per.`eliminado` = 0
                                            WHERE f_int.id_familia = :idFamilia;");
            
            $consulta->bindParam(':idFamilia', $idFamilia);

            if (!$consulta->execute()) {
                throw new Exception($consulta->errorInfo()[2]);
            }
            
            while($filas = $consulta->fetch()){
                $this->integrantes[] = $filas;
            }
            
            return $this->integrantes;
        } catch (Exception $e) {
            throw new Exception("Error en metodo : ".__FUNCTION__.' | '. $e->getMessage()."\n". $e->getTraceAsString());
        }
    }

    public function ExisteIntegrante($idPersona)
    {
        try {
            $consulta = $this->db->prepare("SELECT *
                                            FROM  familia_integrante
                                            where id_persona = :idPersona
                                            and eliminado = 0;");
            $consulta->bindParam(':idPersona', $idPersona);

            if (!$consulta->execute()) {
                throw new Exception($consulta->errorInfo()[2]);
            }
            
            while($filas = $consulta->fetch()){
                $this->integrantes[] = $filas;
            }
            
            if (count($this->integrantes) > 0) {
                return true;
            }
            else{
                return false;
            }
        } catch (Exception $e) {
            throw new Exception("Error en metodo : ".__FUNCTION__.' | '. $e->getMessage()."\n". $e->getTraceAsString());
        }
    }

    public function Registrar($idFamilia, $integrante)
    {
        try {
            $fecha = date('Y-m-d H:i:s');
 
            $consulta = $this->db->prepare("INSERT INTO familia_integrante (id_familia, id_persona, rol, eliminado, id_usuario_creacion, fecha_creacion, id_ultimo_usuario, fecha_modificacion)
                                            VALUES (:idFamilia, :idPersona, :rol, 0, :idUsuarioCreacion, :fechaCreacion, :idUltimoUsuario, :fechaModificacion);");
            $consulta->bindParam(':idFamilia', $idFamilia);
            $consulta->bindParam(':idPersona', $integrante['idPersona']);
            $consulta->bindParam(':rol', $integrante['rol']);
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

    public function EliminarPorFamilia($idFamilia)
    {
        try {
            $fecha = date('Y-m-d H:i:s');
 
            $consulta = $this->db->prepare("DELETE FROM familia_integrante WHERE id_familia = :idFamilia;");
            $consulta->bindParam(':idFamilia', $idFamilia);

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