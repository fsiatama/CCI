<?php

class MainController {

	public function indexAction()
	{
		$is_template = true;
		return new View('app', compact('is_template'));
	}

}
