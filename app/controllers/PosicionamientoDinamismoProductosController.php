<?php

class PosicionamientoDinamismoProductosController {

    public function indexAction() {
        $is_template = false;
        return new View('cuadrantes', compact('is_template'));
    }
}
