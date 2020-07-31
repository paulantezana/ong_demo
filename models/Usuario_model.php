<?php
class Usuario_model
{
	private $db;
    private $usuarios;

	public function __construct($dbConexion){
        $this->db = $dbConexion;
        $this->usuarios = array();
    }

    public function BuscarUsuarioPorCorreo($correo){
    	$consulta = $this->db->prepare("SELECT usu.id_usuario, usu.nombre, usu.password, usu.eliminado, per.nombre as 'perfil' 
                                        FROM usuario usu
                                        join perfil per on usu.id_perfil = per.id_perfil
                                        WHERE usu.correo = :correo
                                        and usu.eliminado = 0;");
		$consulta->bindParam(':correo', $correo);
	    $consulta->execute();
        
        while($filas = $consulta->fetch()){
            $this->usuarios[] = $filas;
        }
        
        if (count($this->usuarios) > 0) {
            return $this->usuarios[0];
        }
        else{
            return false;
        }
    }

    public function Listar()
    {
        try {
            $this->usuarios = array();
            $consulta = $this->db->prepare("SELECT usu.id_usuario, usu.nombre, usu.correo, per.nombre as perfil, usu.fecha_creacion, count(pro.id_usuario_proyecto) as cantidadProyecto
                                            FROM usuario usu
                                            JOIN perfil per ON usu.id_perfil = per.id_perfil
                                            LEFT JOIN usuario_proyecto pro ON usu.id_usuario = pro.id_usuario
                                                                            AND pro.eliminado = 0
                                            WHERE usu.eliminado = 0 
                                            GROUP BY usu.id_usuario
                                            ORDER BY usu.fecha_creacion ASC;");

            if (!$consulta->execute()) {
                throw new Exception($consulta->errorInfo()[2]);
            }
            
            while($filas = $consulta->fetch()){
                $this->usuarios[] = $filas;
            }
            
            return $this->usuarios;
        } catch (Exception $e) {
            throw new Exception("Error en metodo : ".__FUNCTION__.' | '. $e->getMessage()."\n". $e->getTraceAsString());
        }
    }

    public function BuscarPorId($idUsuario)
    {
        try {
            $consulta = $this->db->prepare("SELECT *
                                            FROM usuario
                                            where id_usuario = :idUsuario
                                            and eliminado = 0;");
            $consulta->bindParam(':idUsuario', $idUsuario);

            if (!$consulta->execute()) {
                throw new Exception($consulta->errorInfo()[2]);
            }
            
            while($filas = $consulta->fetch()){
                $this->usuarios[] = $filas;
            }
            
            if (count($this->usuarios) > 0) {
                return $this->usuarios[0];
            }
            else{
                return false;
            }
        } catch (Exception $e) {
            throw new Exception("Error en metodo : ".__FUNCTION__.' | '. $e->getMessage()."\n". $e->getTraceAsString());
        }
    }

    public function Registrar($usuario)
    {
        try {
            $fecha = date('Y-m-d H:i:s');
 
            $consulta = $this->db->prepare("INSERT INTO usuario (nombre, correo, password, id_perfil, eliminado, id_usuario_creacion, fecha_creacion, id_ultimo_usuario, fecha_modificacion, direccion)
                                            VALUES (:nombre, :correo, :password, :idPerfil, 0, :idUsuarioCreacion, :fechaCreacion, :idUltimoUsuario, :fechaModificacion, :direccion);");
            $consulta->bindParam(':nombre', $usuario['nombre']);
            $consulta->bindParam(':correo', $usuario['correo']);
            $consulta->bindParam(':password', $usuario['password']);
            $consulta->bindParam(':idPerfil', $usuario['idPerfil']);
            $consulta->bindParam(':direccion', $usuario['direccion']);
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

    public function Modificar($usuario)
    {
        try {
            $fecha = date('Y-m-d H:i:s');
 
            $consulta = $this->db->prepare("UPDATE usuario SET nombre = :nombre, correo = :correo, password = :password, id_perfil = :idPerfil, direccion= :direccion, id_ultimo_usuario = :idUltimoUsuario , fecha_modificacion = :fechaModificacion WHERE id_usuario = :idUsuario;");
            $consulta->bindParam(':idUsuario', $usuario['idUsuario']);
            $consulta->bindParam(':nombre', $usuario['nombre']);
            $consulta->bindParam(':correo', $usuario['correo']);
            $consulta->bindParam(':password', $usuario['password']);
            $consulta->bindParam(':idPerfil', $usuario['idPerfil']);
            $consulta->bindParam(':direccion', $usuario['direccion']);
            $consulta->bindParam(':idUltimoUsuario', $_SESSION['idUsuario']);
            $consulta->bindParam(':fechaModificacion', $fecha);

            if (!$consulta->execute()) {
                throw new Exception($consulta->errorInfo()[2]);
            }
        } catch (Exception $e) {
            throw new Exception("Error en metodo : ".__FUNCTION__.' | '. $e->getMessage()."\n". $e->getTraceAsString());
        }
    }

    public function Eliminar($idUsuario)
    {
        try {
            $fecha = date('Y-m-d H:i:s');
 
            $consulta = $this->db->prepare("UPDATE usuario SET eliminado = 1, id_ultimo_usuario = :idUltimoUsuario , fecha_modificacion = :fechaModificacion WHERE id_usuario = :idUsuario;");
            $consulta->bindParam(':idUsuario', $idUsuario);
            $consulta->bindParam(':idUltimoUsuario', $_SESSION['idUsuario']);
            $consulta->bindParam(':fechaModificacion', $fecha);

            if (!$consulta->execute()) {
                throw new Exception($consulta->errorInfo()[2]);
            }
        } catch (Exception $e) {
            throw new Exception("Error en metodo : ".__FUNCTION__.' | '. $e->getMessage()."\n". $e->getTraceAsString());
        }
    }

    public function BuscarCorreoRegistrado($correo, $idUsuario)
    {
        $this->usuarios = array();
        try {
            $consulta = $this->db->prepare("SELECT *
                                            FROM usuario
                                            where id_usuario != :idUsuario
                                            and correo = :correo
                                            and eliminado = 0;");
            $consulta->bindParam(':idUsuario', $idUsuario);
            $consulta->bindParam(':correo', $correo);

            if (!$consulta->execute()) {
                throw new Exception($consulta->errorInfo()[2]);
            }
            
            while($filas = $consulta->fetch()){
                $this->usuarios[] = $filas;
            }
            
            if (count($this->usuarios) > 0) {
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