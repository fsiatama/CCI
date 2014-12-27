<?php

class HomeController {

	public function indexAction()
	{
		$is_template = false;
		return new View('home', compact('is_template'));
	}

	public function giaExportarAction()
	{
		$is_template = false;
		return new View('guia-exportar', compact('is_template'));
	}

	

}
