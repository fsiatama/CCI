<?php

class InformacionGeneralController {

    public function indexAction() {
        $is_template = false;
        return new View('informacion_general', compact('is_template'));
    }
}
