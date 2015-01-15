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
        return new View('guia_basica_para_exportar', compact('is_template'));
    }

    public function informacionGeneralTLCAction() {
        $is_template = false;
        return new View('informacion_general_tlc', compact('is_template'));
    }

    public function requisitosIngresoColombiaAction() {
        $is_template = false;
        return new View('requisitos_ingreso_colombia', compact('is_template'));
    }

    public function protocolosExportacionAction() {
        $is_template = false;
        return new View('protocolos_exportacion', compact('is_template'));
    }

    public function requisitosSanitariosFitosanitariosAction() {
        $is_template = false;
        return new View('requisitos_sanitarios_fitosanitarios', compact('is_template'));
    }

    public function losgisticaTransporteAction() {
        $is_template = false;
        return new View('logistica_transporte', compact('is_template'));
    }

    public function comercioAgropecuarioColombiaAction() {
        $is_template = false;
        return new View('comercio_agropecuario_colombia', compact('is_template'));
    }

    public function comercioAgropecuarioMundialAction() {
        $is_template = false;
        return new View('comercio_agropecuario_mundial', compact('is_template'));
    }

    public function colombiaPorAcuerdoComercialAction() {
        $is_template = false;
        return new View('colombia_por_acuerdo_comercial', compact('is_template'));
    }

    public function posicionamientoProductosAction() {
        $is_template = false;
        return new View('posicionamientoProductos', compact('is_template'));
    }

}
