<?php

class GuiaBasicaExportarController {

    public function indexAction() {
        $is_template = false;
        return new View('guia_basica', compact('is_template'));
    }
    
}
