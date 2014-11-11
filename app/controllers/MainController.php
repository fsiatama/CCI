<?php

require PATH_APP.'min_agricultura/Repositories/SessionRepo.php';

class MainController {

	public function indexAction()
	{
		$sessionRepo = new SessionRepo;
		$result = false;
		if ($sessionRepo->validSession()) {
			$is_template = true;
			return new View('app', compact('is_template'));
		}
		else {
			$is_template = false;
			return new View('home', compact('is_template'));
		}

	}

}
