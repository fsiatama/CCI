<?php

class HomeController {

	public function indexAction()
	{
		$is_template = false;
		return new View('home', compact('is_template'));
	}

	public function loginAction()
	{
		$is_template = false;
		return new View('login', compact('is_template'));
	}

}
