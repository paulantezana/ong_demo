<?php
class Comunidad_model
{
	private $db;
    private $comunidades;

	public function __construct($dbConexion)
    {
        $this->db = $dbConexion;
        $this->comunidades = array();
    }

    public function Listar()
    {
        try {
            $this->comunidades = array();
            $consulta = $this->db->prepare("SELECT *
                                            FROM comunidad
                                            WHERE eliminado = 0;");

            if (!$consulta->execute()) {
                throw new Exception($consulta->errorInfo()[2]);
            }
            
            while($filas = $consulta->fetch()){
                $this->comunidades[] = $filas;
            }
            
            return $this->comunidades;
        } catch (Exception $e) {
            throw new Exception("Error en metodo : ".__FUNCTION__.' | '. $e->getMessage()."\n". $e->getTraceAsString());
        }
    }

    public function ListarPorActividad($idActividad)
    {
        try {
            $this->comunidades = array();
            $consulta = $this->db->prepare("SELECT com.nombre, acc.nombre as accion
                                            FROM comunidad com
                                            JOIN accion acc ON com.id_comunidad = acc.id_comunidad
                                                            AND acc.eliminado = 0
                                                            and acc.id_actividad = :idActividad
                                            WHERE com.eliminado = 0
                                            ORDER BY com.nombre;");
            $consulta->bindParam(':idActividad', $idActividad);

            if (!$consulta->execute()) {
                throw new Exception($consulta->errorInfo()[2]);
            }
            
            while($filas = $consulta->fetch()){
                $this->comunidades[] = $filas;
            }
            
            return $this->comunidades;
        } catch (Exception $e) {
            throw new Exception("Error en metodo : ".__FUNCTION__.' | '. $e->getMessage()."\n". $e->getTraceAsString());
        }
    }

    public function ListarPorDistrito($idDistrito)
    {
        try {
            $this->comunidades = array();
            $consulta = $this->db->prepare("SELECT id_comunidad, nombre
                                            FROM comunidad
                                            WHERE eliminado = 0 
                                            AND id_distrito = :idDistrito
                                            ORDER BY nombre ASC;");
            $consulta->bindParam(':idDistrito', $idDistrito);

            if (!$consulta->execute()) {
                throw new Exception($consulta->errorInfo()[2]);
            }
            
            while($filas = $consulta->fetch()){
                $this->comunidades[] = $filas;
            }
            
            return $this->comunidades;
        } catch (Exception $e) {
            throw new Exception("Error en metodo : ".__FUNCTION__.' | '. $e->getMessage()."\n". $e->getTraceAsString());
        }
    }

    public function ListarPorProyecto($idProyecto)
    {
        try {
            $this->personas = array();
            $consulta = $this->db->prepare("SELECT com.id_comunidad, com.nombre
                                            FROM comunidad com 
                                            JOIN accion acc ON com.`id_comunidad` = acc.`id_comunidad`
                                            JOIN actividad act ON acc.`id_actividad` = act.`id_actividad`
                                            JOIN producto pro ON act.`id_producto` = pro.`id_producto`
                                            JOIN objetivo obj ON pro.`id_objetivo` = obj.`id_objetivo`
                                                AND obj.`id_proyecto` = :idProyecto
                                            WHERE com.eliminado = 0
                                            GROUP BY com.id_comunidad
                                            ORDER BY com.nombre ASC;");

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

    public function BuscarPorId($idComunidad)
    {
        try {
            $consulta = $this->db->prepare("SELECT * FROM comunidad where id_comunidad = :idComunidad and eliminado = 0;");
            $consulta->bindParam(':idComunidad', $idComunidad);

            if (!$consulta->execute()) {
                throw new Exception($consulta->errorInfo()[2]);
            }
            
            while($filas = $consulta->fetch()){
                $this->comunidades[] = $filas;
            }
            
            if (count($this->comunidades) > 0) {
                return $this->comunidades[0];
            }
            else{
                return false;
            }
        } catch (Exception $e) {
            throw new Exception("Error en metodo : ".__FUNCTION__.' | '. $e->getMessage()."\n". $e->getTraceAsString());
        }
    }

    public function Registrar($nombre, $idDistrito)
    {
        try {
            $fecha = date('Y-m-d H:i:s');
 
            $consulta = $this->db->prepare("INSERT INTO comunidad (nombre, id_distrito, eliminado, id_usuario_creacion, fecha_creacion, id_ultimo_usuario, fecha_modificacion)
                                            VALUES (:nombre, :idDistrito, 0, :idUsuarioCreacion, :fechaCreacion, :idUltimoUsuario, :fechaModificacion);");
            $consulta->bindParam(':nombre', $nombre);
            $consulta->bindParam(':idDistrito', $idDistrito);
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

    public function Modificar($nombre, $idComunidad)
    {
        try {
            $fecha = date('Y-m-d H:i:s');
 
            $consulta = $this->db->prepare("UPDATE comunidad SET nombre = :nombre, id_ultimo_usuario = :idUltimoUsuario , fecha_modificacion = :fechaModificacion WHERE id_comunidad = :idComunidad;");
            $consulta->bindParam(':idComunidad', $idComunidad);
            $consulta->bindParam(':nombre', $nombre);
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
