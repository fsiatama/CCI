<?php

class MainController {

	public function indexAction()
	{
		return new View('app', array('is_template' => true));
	}

}
