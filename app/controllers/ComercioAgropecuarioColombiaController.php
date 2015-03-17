<?php

class ComercioAgropecuarioColombiaController {

    public function indexAction() {
        $is_template    = false;
        $updateInfoImpo = Helpers::getUpdateInfo('aduanas', 'impo');
        $updateInfoExpo = Helpers::getUpdateInfo('aduanas', 'expo');

        $params = array_merge(compact('is_template', 'updateInfoImpo', 'updateInfoExpo'));
        return new View('comercio_colombia', $params);
    }
}
