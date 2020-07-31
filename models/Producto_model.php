<?php
class Producto_model
{
	private $db;
    private $productos;

	public function __construct($dbConexion){
        $this->db = $dbConexion;
        $this->productos = array();
    }

    public function ListarPorObjetivo($idObjetivo){
        try {
            $this->productos = array();
            $consulta = $this->db->prepare("SELECT id_producto, nombre, descripcion, avance, ponderacion FROM producto where eliminado = 0 and id_objetivo = :idObjetivo;");
            $consulta->bindParam(':idObjetivo', $idObjetivo);

            if (!$consulta->execute()) {
                throw new Exception($consulta->errorInfo()[2]);
            }
            
            while($filas = $consulta->fetch()){
                $this->productos[] = $filas;
            }
            
            return $this->productos;
        } catch (Exception $e) {
            throw new Exception("Error en metodo : ".__FUNCTION__.' | '. $e->getMessage()."\n". $e->getTraceAsString());
        }
    }

    public function BuscarPorId($idObjetivo, $idProducto){
        try {
            $consulta = $this->db->prepare("SELECT * FROM producto where id_producto = :idProducto and id_objetivo = :idObjetivo and eliminado = 0;");
            $consulta->bindParam(':idProducto', $idProducto);
            $consulta->bindParam(':idObjetivo', $idObjetivo);

            if (!$consulta->execute()) {
                throw new Exception($consulta->errorInfo()[2]);
            }
            
            while($filas = $consulta->fetch()){
                $this->productos[] = $filas;
            }
            
            if (count($this->productos) > 0) {
                return $this->productos[0];
            }
            else{
                return false;
            }
        } catch (Exception $e) {
            throw new Exception("Error en metodo : ".__FUNCTION__.' | '. $e->getMessage()."\n". $e->getTraceAsString());
        }
    }

    public function Registrar($idObjetivo, $producto){
        try {
            $fecha = date('Y-m-d H:i:s');
 
            $consulta = $this->db->prepare("INSERT INTO producto (id_objetivo, nombre, descripcion, avance, ponderacion, eliminado, id_usuario_creacion, fecha_creacion, id_ultimo_usuario, fecha_modificacion)
                                            VALUES (:idObjetivo, :nombre, :descripcion, 0, 0, 0, :idUsuarioCreacion, :fechaCreacion, :idUltimoUsuario, :fechaModificacion);");
            $consulta->bindParam(':idObjetivo', $idObjetivo);
            $consulta->bindParam(':nombre', $producto['nombre']);
            $consulta->bindParam(':descripcion', $producto['descripcion']);
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

    public function Modificar($producto)
    {
        try {
            $fecha = date('Y-m-d H:i:s');
 
            $consulta = $this->db->prepare("UPDATE producto SET nombre = :nombre, descripcion = :descripcion, id_ultimo_usuario = :idUltimoUsuario , fecha_modificacion = :fechaModificacion WHERE id_producto = :idProducto;");
            $consulta->bindParam(':idProducto', $producto['idProducto']);
            $consulta->bindParam(':nombre', $producto['nombre']);
            $consulta->bindParam(':descripcion', $producto['descripcion']);
            $consulta->bindParam(':idUltimoUsuario', $_SESSION['idUsuario']);
            $consulta->bindParam(':fechaModificacion', $fecha);

            if (!$consulta->execute()) {
                throw new Exception($consulta->errorInfo()[2]);
            }
        } catch (Exception $e) {
            throw new Exception("Error en metodo : ".__FUNCTION__.' | '. $e->getMessage()."\n". $e->getTraceAsString());
        }
    }

    public function Eliminar($idProducto)
    {
        try {
            $fecha = date('Y-m-d H:i:s');
 
            $consulta = $this->db->prepare("UPDATE producto SET eliminado = 1, id_ultimo_usuario = :idUltimoUsuario , fecha_modificacion = :fechaModificacion WHERE id_producto = :idProducto;");
            $consulta->bindParam(':idProducto', $idProducto);
            $consulta->bindParam(':idUltimoUsuario', $_SESSION['idUsuario']);
            $consulta->bindParam(':fechaModificacion', $fecha);

            if (!$consulta->execute()) {
                throw new Exception($consulta->errorInfo()[2]);
            }
        } catch (Exception $e) {
            throw new Exception("Error en metodo : ".__FUNCTION__.' | '. $e->getMessage()."\n". $e->getTraceAsString());
        }
    }

    public function AsignarPonderacion($idProducto, $ponderacion)
    {
        try {
            $fecha = date('Y-m-d H:i:s');
 
            $consulta = $this->db->prepare("UPDATE producto SET ponderacion = :ponderacion, id_ultimo_usuario = :idUltimoUsuario, fecha_modificacion = :fechaModificacion  WHERE id_producto = :idProducto");
            $consulta->bindParam(':idProducto', $idProducto);
            $consulta->bindParam(':ponderacion', $ponderacion);
            $consulta->bindParam(':idUltimoUsuario', $_SESSION['idUsuario']);
            $consulta->bindParam(':fechaModificacion', $fecha);

            if (!$consulta->execute()) {
                throw new Exception($consulta->errorInfo()[2]);
            }
            
            if(!($consulta->rowCount() > 0))
            {
                throw new Exception("No se pudo actualizar la ponderacion de uno o mas productos.");
            }
        } catch (Exception $e) {
            throw new Exception("Error en metodo : ".__FUNCTION__.' | '. $e->getMessage()."\n". $e->getTraceAsString());
        }
    }

    public function ActualizarAvance($idProducto, $avance)
    {
        try {
            $fecha = date('Y-m-d H:i:s');
 
            $consulta = $this->db->prepare("UPDATE producto SET avance = :avance, id_ultimo_usuario = :idUltimoUsuario, fecha_modificacion = :fechaModificacion  WHERE id_producto = :idProducto");
            $consulta->bindParam(':idProducto', $idProducto);
            $consulta->bindParam(':avance', $avance);
            $consulta->bindParam(':idUltimoUsuario', $_SESSION['idUsuario']);
            $consulta->bindParam(':fechaModificacion', $fecha);

            if (!$consulta->execute()) {
                throw new Exception($consulta->errorInfo()[2]);
            }
            
            if(!($consulta->rowCount() > 0))
            {
                throw new Exception("No se pudo actualizar el avance del producto.");
            }
        } catch (Exception $e) {
            throw new Exception("Error en metodo : ".__FUNCTION__.' | '. $e->getMessage()."\n". $e->getTraceAsString());
        }
    }

    public function ResumenAvance($idObjetivo)
    {
        try {
            $this->ejecuciones = array();
            $consulta = $this->db->prepare("SELECT SUM(avance * ponderacion / 100) AS avance
                                            FROM producto
                                            WHERE id_objetivo = :idObjetivo
                                            AND eliminado = 0;");
            $consulta->bindParam(':idObjetivo', $idObjetivo);

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