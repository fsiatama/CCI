<?php

class HerramientasController {

    public function indexAction() {
        $is_template = false;
        return new View('herramientas', compact('is_template'));
    }

}
