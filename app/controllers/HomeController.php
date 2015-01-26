<?php

class HomeController {

    public function indexAction() {
        $is_template = false;
        return new View('home', compact('is_template'));
    }

    public function loginAction() {
        $is_template = false;
        return new View('login', compact('is_template'));
    }
    
    // 2.2 COMPONENTES DEL MODULO 2: ACCESO AL PÚBLICO EN GENERAL

    public function guiaBasicaParaExportarAction() {
        $is_template = false;
        return new View('guia_basica', compact('is_template'));
    }

    public function informacionGeneralAction() {
        $is_template = false;
        return new View('informacion_general', compact('is_template'));
    }

    public function informacionExtraAction() {
        $is_template = false;
        return new View('informacion_extra', compact('is_template'));
    }
}
