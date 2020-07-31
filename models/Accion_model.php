<?php
class Accion_model
{
	private $db;
    private $acciones;

	public function __construct($dbConexion)
    {
        $this->db = $dbConexion;
        $this->acciones = array();
    }

    public function ListarPorActividad($idActividad)
    {
        try {
            $this->acciones = array();
            $consulta = $this->db->prepare("SELECT acc.id_accion, acc.nombre, acc.descripcion, acc.cuantitativa, acc.meta_nominal, acc.meta_cuantitativa, acc.avance, acc.ponderacion, acc.requiere_dato_adicional, acc.participacion_familiar, acc.unidad_meta, acc.unidad_dato_adicional, COUNT(eje.`id_accion_ejecucion`) AS cantidad_ejecucion, IFNULL(SUM(eje.`meta`), 0) AS metaEjecucion
                                            FROM accion acc
                                            LEFT JOIN accion_ejecucion eje ON acc.`id_accion` = eje.`id_accion`
                                                                        AND eje.eliminado = 0 
                                            WHERE acc.eliminado = 0 
                                            AND acc.id_actividad = :idActividad
                                            GROUP BY acc.id_accion;");
            $consulta->bindParam(':idActividad', $idActividad);

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

    public function ListarConMedidaAdicionalPorProyecto($idProyecto)
    {
        try {
            $this->acciones = array();
            $consulta = $this->db->prepare("SELECT acc.id_accion, acc.nombre
                                            FROM accion acc
                                            JOIN actividad act ON acc.id_actividad = act.id_actividad
                                                            AND act.eliminado = 0 
                                            JOIN producto pro ON act.id_producto = pro.id_producto
                                                            AND pro.eliminado = 0 
                                            JOIN objetivo obj ON pro.id_objetivo = obj.id_objetivo
                                                            AND obj.eliminado = 0
                                                            AND obj.id_proyecto = :idProyecto
                                            WHERE acc.eliminado = 0 
                                            AND acc.requiere_dato_adicional = 1
                                            GROUP BY acc.id_accion;");
            $consulta->bindParam(':idProyecto', $idProyecto);

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

    public function ListarConAvanceAnual($idProyecto, $anio)
    {
        try {
            $this->acciones = array();
            $consulta = $this->db->prepare("SELECT act.nombre as actividad, acc.`nombre`,
                                                CASE WHEN acc.cuantitativa = 0 THEN '%' ELSE acc.unidad_meta END AS unidad,
                                                CASE WHEN acc.cuantitativa = 0 THEN '100' ELSE acc.meta_cuantitativa END AS metaProyecto,
                                                SUM(CASE WHEN MONTH(eje.`fecha_fin`) = 1 THEN eje.`meta` ELSE 0 END) AS metaEnero,
                                                SUM(CASE WHEN MONTH(eje.`fecha_ejecucion`) = 1 THEN eje.`avance` ELSE 0 END) AS avanceEnero,
                                                SUM(CASE WHEN MONTH(eje.`fecha_fin`) = 2 THEN eje.`meta` ELSE 0 END) AS metaFebrero,
                                                SUM(CASE WHEN MONTH(eje.`fecha_ejecucion`) = 2 THEN eje.`avance` ELSE 0 END) AS avanceFebrero,
                                                SUM(CASE WHEN MONTH(eje.`fecha_fin`) = 3 THEN eje.`meta` ELSE 0 END) AS metaMarzo,
                                                SUM(CASE WHEN MONTH(eje.`fecha_ejecucion`) = 3 THEN eje.`avance` ELSE 0 END) AS avanceMarzo,
                                                SUM(CASE WHEN MONTH(eje.`fecha_fin`) = 4 THEN eje.`meta` ELSE 0 END) AS metaAbril,
                                                SUM(CASE WHEN MONTH(eje.`fecha_ejecucion`) = 4 THEN eje.`avance` ELSE 0 END) AS avanceAbril,
                                                SUM(CASE WHEN MONTH(eje.`fecha_fin`) = 5 THEN eje.`meta` ELSE 0 END) AS metaMayo,
                                                SUM(CASE WHEN MONTH(eje.`fecha_ejecucion`) = 5 THEN eje.`avance` ELSE 0 END) AS avanceMayo,
                                                SUM(CASE WHEN MONTH(eje.`fecha_fin`) = 6 THEN eje.`meta` ELSE 0 END) AS metaJunio,
                                                SUM(CASE WHEN MONTH(eje.`fecha_ejecucion`) = 6 THEN eje.`avance` ELSE 0 END) AS avanceJunio,
                                                SUM(CASE WHEN MONTH(eje.`fecha_fin`) = 7 THEN eje.`meta` ELSE 0 END) AS metaJulio,
                                                SUM(CASE WHEN MONTH(eje.`fecha_ejecucion`) = 7 THEN eje.`avance` ELSE 0 END) AS avanceJulio,
                                                SUM(CASE WHEN MONTH(eje.`fecha_fin`) = 8 THEN eje.`meta` ELSE 0 END) AS metaAgosto,
                                                SUM(CASE WHEN MONTH(eje.`fecha_ejecucion`) = 8 THEN eje.`avance` ELSE 0 END) AS avanceAgosto,
                                                SUM(CASE WHEN MONTH(eje.`fecha_fin`) = 9 THEN eje.`meta` ELSE 0 END) AS metaSeptiembre,
                                                SUM(CASE WHEN MONTH(eje.`fecha_ejecucion`) = 9 THEN eje.`avance` ELSE 0 END) AS avanceSeptiembre,
                                                SUM(CASE WHEN MONTH(eje.`fecha_fin`) = 10 THEN eje.`meta` ELSE 0 END) AS metaOctubre,
                                                SUM(CASE WHEN MONTH(eje.`fecha_ejecucion`) = 10 THEN eje.`avance` ELSE 0 END) AS avanceOctubre,
                                                SUM(CASE WHEN MONTH(eje.`fecha_fin`) = 11 THEN eje.`meta` ELSE 0 END) AS metaNoviembre,
                                                SUM(CASE WHEN MONTH(eje.`fecha_ejecucion`) = 11 THEN eje.`avance` ELSE 0 END) AS avanceNoviembre,
                                                SUM(CASE WHEN MONTH(eje.`fecha_fin`) = 12 THEN eje.`meta` ELSE 0 END) AS metaDiciembre,
                                                SUM(CASE WHEN MONTH(eje.`fecha_ejecucion`) = 12 THEN eje.`avance` ELSE 0 END) AS avanceDiciembre
                                            FROM accion acc
                                            JOIN accion_ejecucion eje ON acc.`id_accion` = eje.`id_accion`
                                                        AND YEAR(CASE WHEN eje.`fecha_ejecucion` IS NOT NULL THEN eje.`fecha_ejecucion` ELSE eje.fecha_fin END)
                                                         = :anio
                                            JOIN actividad act ON acc.id_actividad = act.id_actividad
                                                            AND act.eliminado = 0
                                            JOIN producto pro ON act.id_producto = pro.id_producto
                                                            AND pro.eliminado = 0
                                            JOIN objetivo obj ON pro.id_objetivo = obj.id_objetivo
                                                            AND obj.eliminado = 0
                                                            AND obj.id_proyecto = :idProyecto
                                            GROUP BY acc.id_accion;");
            $consulta->bindParam(':idProyecto', $idProyecto);
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

    public function ReporteAvancePorActividad($idActividad, $fechaIni, $fechaFin)
    {
         try {
            $this->acciones = array();
            $consulta = $this->db->prepare("SELECT acc.`nombre`, acc.`cuantitativa`, acc.`unidad_meta`,  SUM(eje.`meta`) AS metaProyecto, SUM(eje.`avance`) AS metaEjecutada, acc.id_accion
                                        FROM accion acc
                                        JOIN accion_ejecucion eje ON acc.`id_accion` = eje.`id_accion`
                                                      AND eje.`eliminado` = 0
                                                      AND eje.`fecha_fin` BETWEEN :fechaIni AND :fechaFin
                                        WHERE acc.`id_actividad` = :idActividad
                                        AND acc.`eliminado` = 0
                                        GROUP BY acc.`id_accion`;");
            $consulta->bindParam(':idActividad', $idActividad);
            $consulta->bindParam(':fechaIni', $fechaIni);
            $consulta->bindParam(':fechaFin', $fechaFin);

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
    
    public function BuscarPorId($idActividad, $idAccion)
    {
        try {
            $consulta = $this->db->prepare("SELECT *
                                            FROM accion
                                            where id_actividad = :idActividad
                                            and id_accion = :idAccion
                                            and eliminado = 0;");
            $consulta->bindParam(':idActividad', $idActividad);
            $consulta->bindParam(':idAccion', $idAccion);

            if (!$consulta->execute()) {
                throw new Exception($consulta->errorInfo()[2]);
            }
            
            while($filas = $consulta->fetch()){
                $this->acciones[] = $filas;
            }
            
            if (count($this->acciones) > 0) {
                return $this->acciones[0];
            }
            else{
                return false;
            }
        } catch (Exception $e) {
            throw new Exception("Error en metodo : ".__FUNCTION__.' | '. $e->getMessage()."\n". $e->getTraceAsString());
        }
    }

    public function Registrar($idActividad, $accion)
    {
        try {
            $fecha = date('Y-m-d H:i:s');
 
            $consulta = $this->db->prepare("INSERT INTO accion (id_actividad, nombre, descripcion, cuantitativa, meta_nominal, meta_cuantitativa, avance, ponderacion, eliminado, id_usuario_creacion, fecha_creacion, id_ultimo_usuario, fecha_modificacion, requiere_dato_adicional, participacion_familiar, unidad_meta, unidad_dato_adicional, ubicacion, id_comunidad)
                                            VALUES (:idActividad, :nombre, :descripcion, :cuantitativa, :metaNominal, :metaCuantitativa, 0, 0, 0, :idUsuarioCreacion, :fechaCreacion, :idUltimoUsuario, :fechaModificacion, :requiereDatoAdicional, :participacionFamiliar, :unidadMeta, :unidadDatoAdicional, :ubicacion, :idComunidad);");
            $consulta->bindParam(':idActividad', $idActividad);
            $consulta->bindParam(':nombre', $accion['nombre']);
            $consulta->bindParam(':descripcion', $accion['descripcion']);
            $consulta->bindParam(':cuantitativa', $accion['cuantitativa']);
            $consulta->bindParam(':metaNominal', $accion['metaNominal']);
            $consulta->bindParam(':ubicacion', $accion['ubicacion']);
            $consulta->bindParam(':metaCuantitativa', $accion['metaCuantitativa']);
            $consulta->bindParam(':requiereDatoAdicional', $accion['requiereDatoAdicional']);
            $consulta->bindParam(':participacionFamiliar', $accion['participacionFamiliar']);
            $consulta->bindParam(':unidadMeta', $accion['unidadMeta']);
            $consulta->bindParam(':unidadDatoAdicional', $accion['unidadMedidaAdicional']);
            $consulta->bindParam(':idUsuarioCreacion', $_SESSION['idUsuario']);
            $consulta->bindParam(':fechaCreacion', $fecha);
            $consulta->bindParam(':idUltimoUsuario', $_SESSION['idUsuario']);
            $consulta->bindParam(':fechaModificacion', $fecha);
            $consulta->bindParam(':idComunidad', $accion['idComunidad']);

            if (!$consulta->execute()) {
                throw new Exception($consulta->errorInfo()[2]);
            }
            
            return $this->db->lastInsertId();
        } catch (Exception $e) {
            throw new Exception("Error en metodo : ".__FUNCTION__.' | '. $e->getMessage()."\n". $e->getTraceAsString());
        }
    }

    public function Modificar($accion)
    {
        try {
            $fecha = date('Y-m-d H:i:s');
 
            $consulta = $this->db->prepare("UPDATE accion SET nombre = :nombre, descripcion = :descripcion, cuantitativa = :cuantitativa, meta_nominal = :metaNominal, meta_cuantitativa = :metaCuantitativa, participacion_familiar = :participacionFamiliar, unidad_meta = :unidadMeta, unidad_dato_adicional = :unidadDatoAdicional, id_ultimo_usuario = :idUltimoUsuario , fecha_modificacion = :fechaModificacion, requiere_dato_adicional = :requiereDatoAdicional, ubicacion = :ubicacion, id_comunidad = :idComunidad WHERE id_accion = :idAccion;");
            $consulta->bindParam(':idAccion', $accion['idAccion']);
            $consulta->bindParam(':nombre', $accion['nombre']);
            $consulta->bindParam(':descripcion', $accion['descripcion']);
            $consulta->bindParam(':cuantitativa', $accion['cuantitativa']);
            $consulta->bindParam(':metaNominal', $accion['metaNominal']);
            $consulta->bindParam(':ubicacion', $accion['ubicacion']);
            $consulta->bindParam(':metaCuantitativa', $accion['metaCuantitativa']);
            $consulta->bindParam(':requiereDatoAdicional', $accion['requiereDatoAdicional']);
            $consulta->bindParam(':participacionFamiliar', $accion['participacionFamiliar']);
            $consulta->bindParam(':unidadMeta', $accion['unidadMeta']);
            $consulta->bindParam(':unidadDatoAdicional', $accion['unidadMedidaAdicional']);
            $consulta->bindParam(':idUltimoUsuario', $_SESSION['idUsuario']);
            $consulta->bindParam(':fechaModificacion', $fecha);
            $consulta->bindParam(':idComunidad', $accion['idComunidad']);

            if (!$consulta->execute()) {
                throw new Exception($consulta->errorInfo()[2]);
            }
        } catch (Exception $e) {
            throw new Exception("Error en metodo : ".__FUNCTION__.' | '. $e->getMessage()."\n". $e->getTraceAsString());
        }
    }

    public function Eliminar($idAccion)
    {
        try {
            $fecha = date('Y-m-d H:i:s');
 
            $consulta = $this->db->prepare("UPDATE accion SET eliminado = 1, id_ultimo_usuario = :idUltimoUsuario , fecha_modificacion = :fechaModificacion WHERE id_accion = :idAccion;");
            $consulta->bindParam(':idAccion', $idAccion);
            $consulta->bindParam(':idUltimoUsuario', $_SESSION['idUsuario']);
            $consulta->bindParam(':fechaModificacion', $fecha);

            if (!$consulta->execute()) {
                throw new Exception($consulta->errorInfo()[2]);
            }
        } catch (Exception $e) {
            throw new Exception("Error en metodo : ".__FUNCTION__.' | '. $e->getMessage()."\n". $e->getTraceAsString());
        }
    }

    public function AsignarPonderacion($idAccion, $ponderacion)
    {
        try {
            $fecha = date('Y-m-d H:i:s');
 
            $consulta = $this->db->prepare("UPDATE accion SET ponderacion = :ponderacion, id_ultimo_usuario = :idUltimoUsuario, fecha_modificacion = :fechaModificacion  WHERE id_accion = :idAccion");
            $consulta->bindParam(':idAccion', $idAccion);
            $consulta->bindParam(':ponderacion', $ponderacion);
            $consulta->bindParam(':idUltimoUsuario', $_SESSION['idUsuario']);
            $consulta->bindParam(':fechaModificacion', $fecha);

            if (!$consulta->execute()) {
                throw new Exception($consulta->errorInfo()[2]);
            }
            
            if(!($consulta->rowCount() > 0))
            {
                throw new Exception("No se pudo actualizar la ponderacion de uno o mas acciones.");
            }
        } catch (Exception $e) {
            throw new Exception("Error en metodo : ".__FUNCTION__.' | '. $e->getMessage()."\n". $e->getTraceAsString());
        }
    }

    public function ActualizarAvance($idAccion, $avance)
    {
        try {
            $fecha = date('Y-m-d H:i:s');
 
            $consulta = $this->db->prepare("UPDATE accion SET avance = :avance, id_ultimo_usuario = :idUltimoUsuario, fecha_modificacion = :fechaModificacion  WHERE id_accion = :idAccion");
            $consulta->bindParam(':idAccion', $idAccion);
            $consulta->bindParam(':avance', $avance);
            $consulta->bindParam(':idUltimoUsuario', $_SESSION['idUsuario']);
            $consulta->bindParam(':fechaModificacion', $fecha);

            if (!$consulta->execute()) {
                throw new Exception($consulta->errorInfo()[2]);
            }
            
            if(!($consulta->rowCount() > 0))
            {
                throw new Exception("No se pudo actualizar el avance de la acción.");
            }
        } catch (Exception $e) {
            throw new Exception("Error en metodo : ".__FUNCTION__.' | '. $e->getMessage()."\n". $e->getTraceAsString());
        }
    }

    public function ResumenAvance($idActividad)
    {
        try {
            $this->ejecuciones = array();
            $consulta = $this->db->prepare("SELECT SUM(CASE 
                                                            WHEN cuantitativa = 0 THEN avance * ponderacion / 100
                                                            ELSE (avance / meta_cuantitativa * 100) * ponderacion / 100
                                                        END) AS avance
                                            FROM accion
                                            WHERE id_actividad = :idActividad
                                            AND eliminado = 0;");
            $consulta->bindParam(':idActividad', $idActividad);

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

    public function PerticipacionFamiliarPorAccionEjecucion($idAccionEjecucion)
    {
        try {
            $consulta = $this->db->prepare("SELECT acc.participacion_familiar
                                            FROM accion acc
                                            JOIN accion_ejecucion eje on acc.id_accion = eje.id_accion
                                                                        AND eje.id_accion_ejecucion = :idAccionEjecucion
                                            WHERE acc.eliminado = 0;");
            $consulta->bindParam(':idAccionEjecucion', $idAccionEjecucion);

            if (!$consulta->execute()) {
                throw new Exception($consulta->errorInfo()[2]);
            }
            
            while($filas = $consulta->fetch()){
                $this->acciones[] = $filas;
            }
            
            if (count($this->acciones) > 0) {
                return $this->acciones[0]['participacion_familiar'];
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