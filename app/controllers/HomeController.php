<?php

class HomeController {

	public function indexAction()
	{
		return new View('home', ['titulo' => 'Clase 2','prueba' => 333]);
	}

}
