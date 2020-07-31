<?php
class Actividad_model
{
	private $db;
    private $actividades;

	public function __construct($dbConexion)
    {
        $this->db = $dbConexion;
        $this->actividades = array();
    }

    public function ListarPorProducto($idProducto)
    {
        try {
            $this->actividades = array();
            $consulta = $this->db->prepare("SELECT act.id_actividad, act.codigo, act.nombre, act.descripcion, act.avance, act.ponderacion, det.estado, det.observacion 
                                            FROM actividad act
                                            JOIN actividad_detalle det on act.id_actividad = det.id_actividad
                                                                        and det.ultimo = 1
                                                                        and det.eliminado = 0
                                            where act.eliminado = 0 
                                            and act.id_producto = :idProducto
                                            ORDER BY act.id_actividad;");
            $consulta->bindParam(':idProducto', $idProducto);

            if (!$consulta->execute()) {
                throw new Exception($consulta->errorInfo()[2]);
            }
            
            while($filas = $consulta->fetch()){
                $this->actividades[] = $filas;
            }
            
            return $this->actividades;
        } catch (Exception $e) {
            throw new Exception("Error en metodo : ".__FUNCTION__.' | '. $e->getMessage()."\n". $e->getTraceAsString());
        }
    }

    public function ListarPorParticipante($idProyecto, $idPersona)
    {
        try {
            $this->actividades = array();
            $consulta = $this->db->prepare("SELECT act.id_actividad, act.codigo, act.nombre, par.rol, pro.nombre as producto
                                            FROM actividad act
                                            JOIN producto pro ON act.id_producto = pro.id_producto
                                                            and pro.eliminado = 0
                                            JOIN objetivo obj ON pro.id_objetivo = obj.id_objetivo
                                                              AND obj.id_proyecto = :idProyecto
                                                            and obj.eliminado = 0
                                            JOIN accion acc ON act.id_actividad = acc.id_actividad
                                                            and acc.eliminado = 0
                                            JOIN accion_ejecucion eje ON acc.id_accion = eje.id_accion
                                                                    and eje.eliminado = 0
                                            JOIN accion_ejecucion_participante par ON eje.id_accion_ejecucion = par.id_accion_ejecucion
                                                                                AND par.id_persona = :idPersona
                                                                                AND par.eliminado = 0
                                            where act.eliminado = 0 
                                            ORDER BY act.id_actividad;");
            $consulta->bindParam(':idProyecto', $idProyecto);
            $consulta->bindParam(':idPersona', $idPersona);

            if (!$consulta->execute()) {
                throw new Exception($consulta->errorInfo()[2]);
            }
            
            while($filas = $consulta->fetch()){
                $this->actividades[] = $filas;
            }
            
            return $this->actividades;
        } catch (Exception $e) {
            throw new Exception("Error en metodo : ".__FUNCTION__.' | '. $e->getMessage()."\n". $e->getTraceAsString());
        }
    }

    public function ListarPorComunidad($idProyecto, $idComunidad)
    {
        try {
            $this->actividades = array();
            $consulta = $this->db->prepare("SELECT act.id_actividad, act.codigo, act.nombre, pro.nombre as producto
                                            FROM actividad act
                                            JOIN producto pro ON act.id_producto = pro.id_producto
                                                            and pro.eliminado = 0
                                            JOIN objetivo obj ON pro.id_objetivo = obj.id_objetivo
                                                              AND obj.id_proyecto = :idProyecto
                                                            and obj.eliminado = 0
                                            JOIN accion acc ON act.id_actividad = acc.id_actividad
                                                            and acc.eliminado = 0
                                                            AND acc.id_comunidad = :idComunidad
                                            where act.eliminado = 0 
                                            GROUP BY act.id_actividad,  act.codigo, act.nombre, pro.nombre
                                            ORDER BY act.id_actividad;");
            $consulta->bindParam(':idComunidad', $idComunidad);
            $consulta->bindParam(':idProyecto', $idProyecto);

            if (!$consulta->execute()) {
                throw new Exception($consulta->errorInfo()[2]);
            }
            
            while($filas = $consulta->fetch()){
                $this->actividades[] = $filas;
            }
            
            return $this->actividades;
        } catch (Exception $e) {
            throw new Exception("Error en metodo : ".__FUNCTION__.' | '. $e->getMessage()."\n". $e->getTraceAsString());
        }
    }

    public function ListarPorProyecto($idProyecto)
    {
        try {
            $this->actividades = array();
            $consulta = $this->db->prepare("SELECT act.id_actividad, act.codigo, act.nombre, pro.nombre as producto
                                            FROM actividad act
                                            JOIN producto pro ON act.id_producto = pro.id_producto
                                                            and pro.eliminado = 0
                                            JOIN objetivo obj ON pro.id_objetivo = obj.id_objetivo
                                                              AND obj.id_proyecto = :idProyecto
                                                            and obj.eliminado = 0
                                            where act.eliminado = 0 
                                            GROUP BY act.id_actividad,  act.codigo, act.nombre, pro.nombre
                                            ORDER BY act.id_actividad;");
            $consulta->bindParam(':idProyecto', $idProyecto);

            if (!$consulta->execute()) {
                throw new Exception($consulta->errorInfo()[2]);
            }
            
            while($filas = $consulta->fetch()){
                $this->actividades[] = $filas;
            }
            
            return $this->actividades;
        } catch (Exception $e) {
            throw new Exception("Error en metodo : ".__FUNCTION__.' | '. $e->getMessage()."\n". $e->getTraceAsString());
        }
    }

    public function ListarAutorizadoPorProducto($idProducto)
    {
        try {
            $this->actividades = array();
            $consulta = $this->db->prepare("SELECT act.id_actividad, act.codigo, act.nombre, act.descripcion, act.avance, act.ponderacion, det.estado, det.observacion 
                                            FROM actividad act
                                            JOIN actividad_detalle det on act.id_actividad = det.id_actividad
                                                                        and det.ultimo = 1
                                                                        and det.eliminado = 0
                                                                        and det.estado = 'AUTORIZADO'
                                            where act.eliminado = 0 
                                            and act.id_producto = :idProducto
                                            ORDER BY act.id_actividad;");
            $consulta->bindParam(':idProducto', $idProducto);

            if (!$consulta->execute()) {
                throw new Exception($consulta->errorInfo()[2]);
            }
            
            while($filas = $consulta->fetch()){
                $this->actividades[] = $filas;
            }
            
            return $this->actividades;
        } catch (Exception $e) {
            throw new Exception("Error en metodo : ".__FUNCTION__.' | '. $e->getMessage()."\n". $e->getTraceAsString());
        }
    }

    public function ListarConAvanceAnual($anio)
    {
        try {
            $this->acciones = array();
            $consulta = $this->db->prepare("SELECT act.`nombre`,
                                                CASE WHEN acc.cuantitativa = 0 THEN '%' ELSE acc.unidad_meta END AS unidad,
                                                SUM(CASE WHEN MONTH(eje.`fecha_ejecucion`) = 1 THEN eje.`meta` ELSE 0 END) AS metaEnero,
                                                SUM(CASE WHEN MONTH(eje.`fecha_ejecucion`) = 1 THEN eje.`avance` ELSE 0 END) AS avanceEnero,
                                                SUM(CASE WHEN MONTH(eje.`fecha_ejecucion`) = 2 THEN eje.`meta` ELSE 0 END) AS metaFebrero,
                                                SUM(CASE WHEN MONTH(eje.`fecha_ejecucion`) = 2 THEN eje.`avance` ELSE 0 END) AS avanceFebrero,
                                                SUM(CASE WHEN MONTH(eje.`fecha_ejecucion`) = 3 THEN eje.`meta` ELSE 0 END) AS metaMarzo,
                                                SUM(CASE WHEN MONTH(eje.`fecha_ejecucion`) = 3 THEN eje.`avance` ELSE 0 END) AS avanceMarzo,
                                                SUM(CASE WHEN MONTH(eje.`fecha_ejecucion`) = 4 THEN eje.`meta` ELSE 0 END) AS metaAbril,
                                                SUM(CASE WHEN MONTH(eje.`fecha_ejecucion`) = 4 THEN eje.`avance` ELSE 0 END) AS avanceAbril,
                                                SUM(CASE WHEN MONTH(eje.`fecha_ejecucion`) = 5 THEN eje.`meta` ELSE 0 END) AS metaMayo,
                                                SUM(CASE WHEN MONTH(eje.`fecha_ejecucion`) = 5 THEN eje.`avance` ELSE 0 END) AS avanceMayo,
                                                SUM(CASE WHEN MONTH(eje.`fecha_ejecucion`) = 6 THEN eje.`meta` ELSE 0 END) AS metaJunio,
                                                SUM(CASE WHEN MONTH(eje.`fecha_ejecucion`) = 6 THEN eje.`avance` ELSE 0 END) AS avanceJunio,
                                                SUM(CASE WHEN MONTH(eje.`fecha_ejecucion`) = 7 THEN eje.`meta` ELSE 0 END) AS metaJulio,
                                                SUM(CASE WHEN MONTH(eje.`fecha_ejecucion`) = 7 THEN eje.`avance` ELSE 0 END) AS avanceJulio,
                                                SUM(CASE WHEN MONTH(eje.`fecha_ejecucion`) = 8 THEN eje.`meta` ELSE 0 END) AS metaAgosto,
                                                SUM(CASE WHEN MONTH(eje.`fecha_ejecucion`) = 8 THEN eje.`avance` ELSE 0 END) AS avanceAgosto,
                                                SUM(CASE WHEN MONTH(eje.`fecha_ejecucion`) = 9 THEN eje.`meta` ELSE 0 END) AS metaSeptiembre,
                                                SUM(CASE WHEN MONTH(eje.`fecha_ejecucion`) = 9 THEN eje.`avance` ELSE 0 END) AS avanceSeptiembre,
                                                SUM(CASE WHEN MONTH(eje.`fecha_ejecucion`) = 10 THEN eje.`meta` ELSE 0 END) AS metaOctubre,
                                                SUM(CASE WHEN MONTH(eje.`fecha_ejecucion`) = 10 THEN eje.`avance` ELSE 0 END) AS avanceOctubre,
                                                SUM(CASE WHEN MONTH(eje.`fecha_ejecucion`) = 11 THEN eje.`meta` ELSE 0 END) AS metaNoviembre,
                                                SUM(CASE WHEN MONTH(eje.`fecha_ejecucion`) = 11 THEN eje.`avance` ELSE 0 END) AS avanceNoviembre,
                                                SUM(CASE WHEN MONTH(eje.`fecha_ejecucion`) = 12 THEN eje.`meta` ELSE 0 END) AS metaDiciembre,
                                                SUM(CASE WHEN MONTH(eje.`fecha_ejecucion`) = 12 THEN eje.`avance` ELSE 0 END) AS avanceDiciembre
                                            FROM accion acc
                                            JOIN accion_ejecucion eje ON acc.`id_accion` = eje.`id_accion`
                                                        AND YEAR(eje.`fecha_ejecucion`) = :anio
                                            WHERE acc.id_actividad = :idActividad
                                            GROUP BY acc.id_accion;");
            $consulta->bindParam(':idActividad', $idActividad);
            $consulta->bindParam(':anio', $anio);

            if (!$consulta->execute()) {
                throw new Exception($consulta->errorInfo()[2]);
            }
            
            while($filas = $consulta->fetch()){
                $this->acciones[] = $filas;
            }
            
            return $this->acciones;
        } catch (Exception $e) {
            throw new Exception("Error en metodo : ".__FUNCTION__.' | '. $e->getMessage()."\n". $e->getTraceAsString());
        }
    }

    public function BuscarPorId($idProducto, $idActividad)
    {
        try {
            $consulta = $this->db->prepare("SELECT act.*, det.estado
                                            FROM actividad act
                                            JOIN actividad_detalle det on act.id_actividad = det.id_actividad
                                                                        and det.ultimo = 1
                                                                        and det.eliminado = 0
                                            where act.id_producto = :idProducto 
                                            and act.id_actividad = :idActividad 
                                            and act.eliminado = 0;");
            $consulta->bindParam(':idProducto', $idProducto);
            $consulta->bindParam(':idActividad', $idActividad);

            if (!$consulta->execute()) {
                throw new Exception($consulta->errorInfo()[2]);
            }
            
            while($filas = $consulta->fetch()){
                $this->actividades[] = $filas;
            }
            
            if (count($this->actividades) > 0) {
                return $this->actividades[0];
            }
            else{
                return false;
            }
        } catch (Exception $e) {
            throw new Exception("Error en metodo : ".__FUNCTION__.' | '. $e->getMessage()."\n". $e->getTraceAsString());
        }
    }

    public function BuscarPorCodigo($codigo)
    {
        try {
            $consulta = $this->db->prepare("SELECT pro.id_proyecto, obj.`id_objetivo`, prod.`id_producto`, act.id_actividad
                                            FROM actividad act
                                            JOIN producto prod ON act.`id_producto` = prod.`id_producto`
                                                                AND prod.eliminado = 0
                                            JOIN objetivo obj ON prod.`id_objetivo` = obj.`id_objetivo`
                                                                AND obj.eliminado = 0
                                            JOIN proyecto pro ON obj.`id_proyecto` = pro.`id_proyecto`
                                                                AND pro.eliminado = 0
                                            JOIN usuario_proyecto  acc ON pro.id_proyecto = acc.id_proyecto
                                                                        AND acc.id_usuario = :idUsuario
                                                                        AND acc.eliminado = 0
                                            WHERE act.`codigo` = :codigo
                                            AND act.eliminado = 0;");
            $consulta->bindParam(':codigo', $codigo);
            $consulta->bindParam(':idUsuario', $_SESSION['idUsuario']);

            if (!$consulta->execute()) {
                throw new Exception($consulta->errorInfo()[2]);
            }
            
            while($filas = $consulta->fetch()){
                $this->actividades[] = $filas;
            }
            
            if (count($this->actividades) > 0) {
                return $this->actividades[0];
            }
            else{
                return false;
            }
        } catch (Exception $e) {
            throw new Exception("Error en metodo : ".__FUNCTION__.' | '. $e->getMessage()."\n". $e->getTraceAsString());
        }
    }

    public function Registrar($idProducto, $actividad)
    {
        try {
            $fecha = date('Y-m-d H:i:s');
 
            $consulta = $this->db->prepare("INSERT INTO actividad (id_producto, codigo, nombre, descripcion, avance, ponderacion, eliminado, id_usuario_creacion, fecha_creacion, id_ultimo_usuario, fecha_modificacion)
                                            VALUES (:idProducto, :codigo, :nombre, :descripcion, 0, 0, 0, :idUsuarioCreacion, :fechaCreacion, :idUltimoUsuario, :fechaModificacion);");
            $consulta->bindParam(':idProducto', $idProducto);
            $consulta->bindParam(':nombre', $actividad['nombre']);
            $consulta->bindParam(':codigo', $actividad['codigo']);
            $consulta->bindParam(':descripcion', $actividad['descripcion']);
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

    public function Modificar($actividad)
    {
        try {
            $fecha = date('Y-m-d H:i:s');
 
            $consulta = $this->db->prepare("UPDATE actividad SET codigo = :codigo, nombre = :nombre, descripcion = :descripcion, id_ultimo_usuario = :idUltimoUsuario , fecha_modificacion = :fechaModificacion WHERE id_actividad = :idActividad;");
            $consulta->bindParam(':idActividad', $actividad['idActividad']);
            $consulta->bindParam(':codigo', $actividad['codigo']);
            $consulta->bindParam(':nombre', $actividad['nombre']);
            $consulta->bindParam(':descripcion', $actividad['descripcion']);
            $consulta->bindParam(':idUltimoUsuario', $_SESSION['idUsuario']);
            $consulta->bindParam(':fechaModificacion', $fecha);

            if (!$consulta->execute()) {
                throw new Exception($consulta->errorInfo()[2]);
            }
        } catch (Exception $e) {
            throw new Exception("Error en metodo : ".__FUNCTION__.' | '. $e->getMessage()."\n". $e->getTraceAsString());
        }
    }

    public function Eliminar($idActividad)
    {
        try {
            $fecha = date('Y-m-d H:i:s');
 
            $consulta = $this->db->prepare("UPDATE actividad SET eliminado = 1, id_ultimo_usuario = :idUltimoUsuario , fecha_modificacion = :fechaModificacion WHERE id_actividad = :idActividad;");
            $consulta->bindParam(':idActividad', $idActividad);
            $consulta->bindParam(':idUltimoUsuario', $_SESSION['idUsuario']);
            $consulta->bindParam(':fechaModificacion', $fecha);

            if (!$consulta->execute()) {
                throw new Exception($consulta->errorInfo()[2]);
            }
        } catch (Exception $e) {
            throw new Exception("Error en metodo : ".__FUNCTION__.' | '. $e->getMessage()."\n". $e->getTraceAsString());
        }
    }

    public function AsignarPonderacion($idActividad, $ponderacion)
    {
        try {
            $fecha = date('Y-m-d H:i:s');
 
            $consulta = $this->db->prepare("UPDATE actividad SET ponderacion = :ponderacion, id_ultimo_usuario = :idUltimoUsuario, fecha_modificacion = :fechaModificacion  WHERE id_actividad = :idActividad;");
            $consulta->bindParam(':idActividad', $idActividad);
            $consulta->bindParam(':ponderacion', $ponderacion);
            $consulta->bindParam(':idUltimoUsuario', $_SESSION['idUsuario']);
            $consulta->bindParam(':fechaModificacion', $fecha);

            if (!$consulta->execute()) {
                throw new Exception($consulta->errorInfo()[2]);
            }
            
            if(!($consulta->rowCount() > 0))
            {
                throw new Exception("No se pudo actualizar la ponderacion de uno o mas actividades.");
            }
        } catch (Exception $e) {
            throw new Exception("Error en metodo : ".__FUNCTION__.' | '. $e->getMessage()."\n". $e->getTraceAsString());
        }
    }

    public function ActualizarAvance($idActividad, $avance)
    {
        try {
            $fecha = date('Y-m-d H:i:s');
 
            $consulta = $this->db->prepare("UPDATE actividad SET avance = :avance, id_ultimo_usuario = :idUltimoUsuario, fecha_modificacion = :fechaModificacion  WHERE id_actividad = :idActividad");
            $consulta->bindParam(':idActividad', $idActividad);
            $consulta->bindParam(':avance', $avance);
            $consulta->bindParam(':idUltimoUsuario', $_SESSION['idUsuario']);
            $consulta->bindParam(':fechaModificacion', $fecha);

            if (!$consulta->execute()) {
                throw new Exception($consulta->errorInfo()[2]);
            }
            
            if(!($consulta->rowCount() > 0))
            {
                throw new Exception("No se pudo actualizar el avance de la actividad.");
            }
        } catch (Exception $e) {
            throw new Exception("Error en metodo : ".__FUNCTION__.' | '. $e->getMessage()."\n". $e->getTraceAsString());
        }
    }

    public function ResumenAvance($idProducto)
    {
        try {
            $this->ejecuciones = array();
            $consulta = $this->db->prepare("SELECT SUM(avance * ponderacion / 100) AS avance
                                            FROM actividad
                                            WHERE id_producto = :idProducto
                                            AND eliminado = 0;");
            $consulta->bindParam(':idProducto', $idProducto);

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
    
    public function ValidarCodigo($codigo, $idActividad)
    {
        try {
            $this->actividades = array();
            $consulta = $this->db->prepare("SELECT act.id_actividad
                                            FROM actividad act
                                            JOIN producto pro on act.id_producto = pro.id_producto
                                                                AND pro.eliminado = 0
                                            JOIN objetivo obj on pro.id_objetivo = obj.id_objetivo
                                                                AND obj.eliminado = 0
                                            JOIN proyecto proy on obj.id_proyecto = proy.id_proyecto
                                                                AND proy.eliminado = 0 
                                            WHERE act.codigo = :codigo
                                            and act.id_actividad != :idActividad 
                                            and act.eliminado = 0;");
            $consulta->bindParam(':codigo', $codigo);
            $consulta->bindParam(':idActividad', $idActividad);

            if (!$consulta->execute()) {
                throw new Exception($consulta->errorInfo()[2]);
            }
            
            while($filas = $consulta->fetch()){
                $this->actividades[] = $filas;
            }
            
            if (count($this->actividades) > 0) {
                throw new Exception("El codigo de acceso rapido ya existe.");
            }
        } catch (Exception $e) {
            throw new Exception("Error en metodo : ".__FUNCTION__.' | '. $e->getMessage()."\n". $e->getTraceAsString());
        }
    }
}
?>