<?php 

class Router
{
	public $uri;
	public $controller;
	public $method;
	public $old; 
	public function __construct()
	{
		$this->setUri();
		$this->setcontroller();
		$this->setmethod();
	}

	public function setUri()
	{
		$this->uri = explode('/', explode('?' ,$_SERVER['REQUEST_URI'])[0]);
	}

	public function setcontroller()
	{
		$this->controller = !empty($this->uri[2]) ? $this->uri[2] : 'Inicio';
	}

	public function setmethod()
	{
		if (!empty($this->uri[3])) 
		{
			$this->method = explode("?", $this->uri[3])[0];
		}
		else
		{
			$this->method = 'exec';
		}
	}
}

?>