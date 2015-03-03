<?php

class ContingentesDesgravacionesController {

    public function indexAction() {
        $is_template = false;
        return new View('contingentes_desgravaciones', compact('is_template'));
    }
}
