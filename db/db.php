<?php
class ConectarBD{

	private $conexion;

	public function __construct()
	{
		// $this->conexion = new PDO('mysql:host=localhost;dbname=cadep', 'root', '');
        $this->conexion = new PDO('mysql:host=localhost;dbname=corporac_ong_demo', 'ong_demo', '0Fhu86x@');
        $this->conexion->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $this->conexion->exec("SET CHARACTER SET UTF8");
	}

    public function GetConexion(){
        return $this->conexion;
    }
}
?>