<?php

class ComercioAgropecuarioColombiaController {

    public function indexAction() {
        $is_template    = false;
        $updateInfoImpo = Helpers::getUpdateInfo('aduanas', 'impo');
        $updateInfoExpo = Helpers::getUpdateInfo('aduanas', 'expo');


		$lines     = Helpers::getRequire(PATH_APP.'lib/indicador.config.php');
		$activator = Helpers::arrayGet($lines, 'activator');
		$params    = array_merge(compact('is_template', 'updateInfoImpo', 'updateInfoExpo', 'activator'));
		
        return new View('comercio_colombia', $params);
    }
}
