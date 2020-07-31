<?php
class Perfil_model
{
	private $db;
    private $perfiles;

	public function __construct($dbConexion){
        $this->db = $dbConexion;
        $this->perfiles = array();
    }

    public function Listar()
    {
        try {
            $this->perfiles = array();
            $consulta = $this->db->prepare("SELECT *
                                            FROM perfil
                                            WHERE eliminado = 0;");

            if (!$consulta->execute()) {
                throw new Exception($consulta->errorInfo()[2]);
            }
            
            while($filas = $consulta->fetch()){
                $this->perfiles[] = $filas;
            }
            
            return $this->perfiles;
        } catch (Exception $e) {
            throw new Exception("Error en metodo : ".__FUNCTION__.' | '. $e->getMessage()."\n". $e->getTraceAsString());
        }
    }
}
?>