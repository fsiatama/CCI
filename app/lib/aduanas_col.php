<?php
$camposSisduanImp[] = array('campo'=>"decl.num_declara", 'nombre'=>_TABNUMDECLARA, 'alias'=>'num_declara', 'key'=>'', 'tipo'=>'s', 'sort'=>1, 'groupby'=>0, 'fulltext'=>0, 'enacumulado'=>0);
$camposSisduanImp[] = array('campo'=>"DATE_FORMAT(decl.fecha, '%Y-%m-%d')", 'nombre'=>_TABFECHAYMD, 'alias'=>'periodo', 'key'=>'decl.periodo', 'tipo'=>'s', 'sort'=>1, 'groupby'=>1, 'fulltext'=>0, 'enacumulado'=>0);
$camposSisduanImp[] = array('campo'=>"DATE_FORMAT(decl.fecha, '%Y-%m')", 'nombre'=>_TABFECHA, 'alias'=>'periodoymd', 'key'=>'decl.periodo', 'tipo'=>'s', 'sort'=>1, 'groupby'=>1, 'fulltext'=>0, 'enacumulado'=>1);
$camposSisduanImp[] = array('campo'=>"decl.id_empresa", 'nombre'=>_TABNIT, 'alias'=>'id_empresa', 'key'=>'', 'tipo'=>'s', 'sort'=>1, 'groupby'=>1, 'fulltext'=>0, 'enacumulado'=>1);
$camposSisduanImp[] = array('campo'=>"empresa.digito_cheq", 'nombre'=>_TABDIGITOCHEQUEO, 'alias'=>'digito_cheq', 'key'=>'', 'tipo'=>'s', 'sort'=>0, 'groupby'=>0, 'fulltext'=>0, 'enacumulado'=>1);
$camposSisduanImp[] = array('campo'=>"empresa.empresa", 'nombre'=>_IMPORTADOR, 'alias'=>'empresa', 'key'=>'decl.id_empresa', 'tipo'=>'s', 'sort'=>1, 'groupby'=>1, 'fulltext'=>0, 'enacumulado'=>1);
$camposSisduanImp[] = array('campo'=>"empresa.representante", 'nombre'=>_TABREPRESENTANTE, 'alias'=>'representante', 'key'=>'', 'tipo'=>'s', 'sort'=>0, 'groupby'=>0, 'fulltext'=>0, 'enacumulado'=>1);
$camposSisduanImp[] = array('campo'=>"empresa.ciudad", 'nombre'=>_TABCIUDADDOMICILIO, 'alias'=>'ciudad', 'key'=>'empresa.id_ciudad', 'tipo'=>'s', 'sort'=>0, 'groupby'=>0, 'fulltext'=>0, 'enacumulado'=>1);
$camposSisduanImp[] = array('campo'=>"empresa.departamentos", 'nombre'=>_TABDEPTODOMICILIO, 'alias'=>'emp_departamentos', 'key'=>'empresa.id_departamentos', 'tipo'=>'s', 'sort'=>0, 'groupby'=>0, 'fulltext'=>0, 'enacumulado'=>1);
$camposSisduanImp[] = array('campo'=>"empresa.telefono", 'nombre'=>_TABTELEFONO, 'alias'=>'telefono', 'key'=>'', 'tipo'=>'s', 'sort'=>0, 'groupby'=>0, 'fulltext'=>0, 'enacumulado'=>1);
$camposSisduanImp[] = array('campo'=>"empresa.fax", 'nombre'=>_TABFAX, 'alias'=>'fax', 'key'=>'', 'tipo'=>'s', 'sort'=>0, 'groupby'=>0, 'fulltext'=>0, 'enacumulado'=>1);
$camposSisduanImp[] = array('campo'=>"empresa.direccion", 'nombre'=>_TABDIRECCION, 'alias'=>'direccion', 'key'=>'', 'tipo'=>'s', 'sort'=>0, 'groupby'=>0, 'fulltext'=>0, 'enacumulado'=>1);
$camposSisduanImp[] = array('campo'=>"empresa.email", 'nombre'=>_TABEMAIL, 'alias'=>'email', 'key'=>'', 'tipo'=>'s', 'sort'=>0, 'groupby'=>0, 'fulltext'=>0, 'enacumulado'=>1);
$camposSisduanImp[] = array('campo'=>"empresa.clase", 'nombre'=>_TABCLASE, 'alias'=>'clase', 'key'=>'', 'tipo'=>'s', 'sort'=>0, 'groupby'=>0, 'fulltext'=>0, 'enacumulado'=>1);
$camposSisduanImp[] = array('campo'=>"empresa.uap", 'nombre'=>_TABUAP, 'alias'=>'uap', 'key'=>'', 'tipo'=>'s', 'sort'=>0, 'groupby'=>0, 'fulltext'=>0, 'enacumulado'=>0);
$camposSisduanImp[] = array('campo'=>"empresa.altex", 'nombre'=>_TABALTEX, 'alias'=>'altex', 'key'=>'', 'tipo'=>'s', 'sort'=>0, 'groupby'=>0, 'fulltext'=>0, 'enacumulado'=>0);
$camposSisduanImp[] = array('campo'=>"proveedor.proveedor", 'nombre'=>_TABPROVEEDOR, 'alias'=>'proveedor', 'key'=>'decl.id_proveedor', 'tipo'=>'s', 'sort'=>1, 'groupby'=>1, 'fulltext'=>0, 'enacumulado'=>1);
$camposSisduanImp[] = array('campo'=>"ciudad_prove.ciudad_prove", 'nombre'=>_TABCIUDADPROVEEDOR, 'alias'=>'ciudad_prove', 'key'=>'decl.id_ciudad_prove', 'tipo'=>'s', 'sort'=>1, 'groupby'=>1, 'fulltext'=>0, 'enacumulado'=>1);
$camposSisduanImp[] = array('campo'=>"proveedor.paises", 'nombre'=>_TABPAISPROVEEDOR, 'alias'=>'paisproveedor', 'key'=>'proveedor.id_paises', 'tipo'=>'s', 'sort'=>1, 'groupby'=>1, 'fulltext'=>0, 'enacumulado'=>1);

$camposSisduanImp[] = array('campo'=>"decl.direccion_proveedor", 'nombre'=>_TABDDIRPRO, 'alias'=>'direccion_proveedor', 'key'=>'', 'tipo'=>'s', 'sort'=>0, 'groupby'=>0, 'fulltext'=>0, 'enacumulado'=>0);
$camposSisduanImp[] = array('campo'=>"decl.email_proveedor", 'nombre'=>_TABEMAILPROVEEDOR, 'alias'=>'email_proveedor', 'key'=>'', 'tipo'=>'s', 'sort'=>0, 'groupby'=>0, 'fulltext'=>0, 'enacumulado'=>0);

$camposSisduanImp[] = array('campo'=>"paisorigen.paises", 'nombre'=>_TABPAISORIGEN, 'alias'=>'paisorigen', 'key'=>'decl.id_paisorigen', 'tipo'=>'s', 'sort'=>1, 'groupby'=>1, 'fulltext'=>0, 'enacumulado'=>1);
$camposSisduanImp[] = array('campo'=>"paiscompra.paises", 'nombre'=>_TABPAISCOMPRA, 'alias'=>'paiscompra', 'key'=>'decl.id_paiscompra', 'tipo'=>'s', 'sort'=>1, 'groupby'=>1, 'fulltext'=>0, 'enacumulado'=>1);
$camposSisduanImp[] = array('campo'=>"paisprocedencia.paises", 'nombre'=>_TABTPAISPROCEDENCIA, 'alias'=>'paisprocedencia', 'key'=>'decl.id_paisprocedencia', 'tipo'=>'s', 'sort'=>1, 'groupby'=>1, 'fulltext'=>0, 'enacumulado'=>1);
$camposSisduanImp[] = array('campo'=>"decl.id_posicion", 'nombre'=>_TABPOSICION, 'alias'=>'id_posicion', 'key'=>'', 'tipo'=>'s', 'sort'=>1, 'groupby'=>1, 'fulltext'=>0, 'enacumulado'=>1);
$camposSisduanImp[] = array('campo'=>"posicion.posicion", 'nombre'=>_TABDESCRIPCIONARANCEL, 'alias'=>'posicion', 'key'=>'decl.id_posicion', 'tipo'=>'s', 'sort'=>1, 'groupby'=>1, 'fulltext'=>1, 'enacumulado'=>1);
$camposSisduanImp[] = array('campo'=>"decl.id_subpartida", 'nombre'=>_COD_ARMONIZADO, 'alias'=>'id_subpartida', 'key'=>'', 'tipo'=>'s', 'sort'=>1, 'groupby'=>1, 'fulltext'=>0, 'enacumulado'=>1);
$camposSisduanImp[] = array('campo'=>"aran.descripcion_ing", 'nombre'=>_TABDESARANCELING, 'alias'=>'descripcion_ing', 'key'=>'decl.id_subpartida', 'tipo'=>'s', 'sort'=>1, 'groupby'=>1, 'fulltext'=>1, 'enacumulado'=>1);
$camposSisduanImp[] = array('campo'=>"decl.descripcion_prod", 'nombre'=>_TABDESCRIPCIONPROD, 'alias'=>'descripcion_prod', 'key'=>'', 'tipo'=>'s', 'sort'=>0, 'groupby'=>0, 'fulltext'=>1, 'enacumulado'=>0);
$camposSisduanImp[] = array('campo'=>"decl.cantidad", 'nombre'=>_TABCANTIDAD, 'alias'=>'cantidad', 'key'=>'', 'tipo'=>'n', 'sort'=>1, 'groupby'=>0, 'fulltext'=>0, 'enacumulado'=>1);
$camposSisduanImp[] = array('campo'=>"unidad_impo.unidad_impo", 'nombre'=>_TABUNIDADCOMERCIAL, 'alias'=>'unidad_impo', 'key'=>'decl.id_unidad_impo', 'tipo'=>'s', 'sort'=>1, 'groupby'=>1, 'fulltext'=>0, 'enacumulado'=>1);
$camposSisduanImp[] = array('campo'=>"decl.valorfob", 'nombre'=>_TABVALORFOB, 'alias'=>'valorfob', 'key'=>'', 'tipo'=>'n', 'sort'=>1, 'groupby'=>0, 'fulltext'=>0, 'enacumulado'=>1);
$camposSisduanImp[] = array('campo'=>"decl.valorfob_unitario", 'nombre'=>_TABVALORUNIMP, 'alias'=>'valorfob_unitario', 'key'=>'', 'tipo'=>'n', 'sort'=>1, 'groupby'=>0, 'fulltext'=>0, 'enacumulado'=>0);
$camposSisduanImp[] = array('campo'=>"decl.valorcif", 'nombre'=>_TABVALORCIF, 'alias'=>'valorcif', 'key'=>'', 'tipo'=>'n', 'sort'=>1, 'groupby'=>0, 'fulltext'=>0, 'enacumulado'=>1);
$camposSisduanImp[] = array('campo'=>"decl.peso_neto", 'nombre'=>_TABPESONETO, 'alias'=>'peso_neto', 'key'=>'', 'tipo'=>'n', 'sort'=>1, 'groupby'=>0, 'fulltext'=>0, 'enacumulado'=>1);
$camposSisduanImp[] = array('campo'=>"decl.peso_bruto", 'nombre'=>_TABPESOBRUTO, 'alias'=>'peso_bruto', 'key'=>'', 'tipo'=>'n', 'sort'=>1, 'groupby'=>0, 'fulltext'=>0, 'enacumulado'=>1);
$camposSisduanImp[] = array('campo'=>"decl.num_bultos", 'nombre'=>_TABNUMBULTOS, 'alias'=>'num_bultos', 'key'=>'', 'tipo'=>'n', 'sort'=>1, 'groupby'=>0, 'fulltext'=>0, 'enacumulado'=>0);
$camposSisduanImp[] = array('campo'=>"via.via", 'nombre'=>_TABVIA, 'alias'=>'via', 'key'=>'decl.id_via', 'tipo'=>'s', 'sort'=>1, 'groupby'=>1, 'fulltext'=>0, 'enacumulado'=>1);
$camposSisduanImp[] = array('campo'=>"aduanas.aduanas", 'nombre'=>_TABADUANA, 'alias'=>'aduana', 'key'=>'decl.id_aduanas', 'tipo'=>'s', 'sort'=>1, 'groupby'=>1, 'fulltext'=>0, 'enacumulado'=>1);
$camposSisduanImp[] = array('campo'=>"aduanasing.aduanas", 'nombre'=>_TABCIUDADING, 'alias'=>'aduanasing', 'key'=>'decl.id_aduanas_ing', 'tipo'=>'s', 'sort'=>1, 'groupby'=>1, 'fulltext'=>0, 'enacumulado'=>1);
$camposSisduanImp[] = array('campo'=>"departamentos.departamentos", 'nombre'=>_TABDEPTODESTINO, 'alias'=>'departamentos', 'key'=>'decl.id_deptorigen', 'tipo'=>'s', 'sort'=>1, 'groupby'=>1, 'fulltext'=>0, 'enacumulado'=>0);
$camposSisduanImp[] = array('campo'=>"decl.fecha_manifiesto", 'nombre'=>_TABFECHAMANIFIESTO, 'alias'=>'fecha_manifiesto', 'key'=>'', 'tipo'=>'s', 'sort'=>1, 'groupby'=>0, 'fulltext'=>0, 'enacumulado'=>0);
$camposSisduanImp[] = array('campo'=>"decl.num_manifiesto", 'nombre'=>_TABNUMMANIFIESTO, 'alias'=>'num_manifiesto', 'key'=>'', 'tipo'=>'s', 'sort'=>0, 'groupby'=>0, 'fulltext'=>0, 'enacumulado'=>0);
$camposSisduanImp[] = array('campo'=>"decl.num_doc_trans", 'nombre'=>_TABNUMDOCTRANS, 'alias'=>'num_doc_trans', 'key'=>'', 'tipo'=>'s', 'sort'=>0, 'groupby'=>0, 'fulltext'=>0, 'enacumulado'=>0);
$camposSisduanImp[] = array('campo'=>"decl.fecha_factura", 'nombre'=>_FECHAFACTURA, 'alias'=>'fecha_factura', 'key'=>'', 'tipo'=>'s', 'sort'=>1, 'groupby'=>0, 'fulltext'=>0, 'enacumulado'=>0);
$camposSisduanImp[] = array('campo'=>"decl.factura", 'nombre'=>_FACTURA, 'alias'=>'factura', 'key'=>'', 'tipo'=>'s', 'sort'=>0, 'groupby'=>0, 'fulltext'=>0, 'enacumulado'=>0);
$camposSisduanImp[] = array('campo'=>"transportador.transportador", 'nombre'=>_TABTRANSPORTADOR, 'alias'=>'transportador', 'key'=>'decl.id_transportador', 'tipo'=>'s', 'sort'=>1, 'groupby'=>1, 'fulltext'=>0, 'enacumulado'=>1);
$camposSisduanImp[] = array('campo'=>"paisbandera.paises", 'nombre'=>_TABBANDERA, 'alias'=>'bandera', 'key'=>'decl.id_bandera', 'tipo'=>'s', 'sort'=>1, 'groupby'=>1, 'fulltext'=>0, 'enacumulado'=>0);
$camposSisduanImp[] = array('campo'=>"decl.flete", 'nombre'=>_TABFLETE, 'alias'=>'flete', 'key'=>'', 'tipo'=>'n', 'sort'=>1, 'groupby'=>0, 'fulltext'=>0, 'enacumulado'=>0);
$camposSisduanImp[] = array('campo'=>"decl.seguro", 'nombre'=>_TABSEGUROPAN, 'alias'=>'seguro', 'key'=>'', 'tipo'=>'n', 'sort'=>1, 'groupby'=>0, 'fulltext'=>0, 'enacumulado'=>0);
$camposSisduanImp[] = array('campo'=>"decl.flete_seguro_otros", 'nombre'=>_TABFLETEYSEGUROTROS, 'alias'=>'flete_seguro_otros', 'key'=>'', 'tipo'=>'n', 'sort'=>1, 'groupby'=>0, 'fulltext'=>0, 'enacumulado'=>0);
$camposSisduanImp[] = array('campo'=>"sia.sia", 'nombre'=>_TABSIA, 'alias'=>'sia', 'key'=>'decl.id_sia', 'tipo'=>'s', 'sort'=>1, 'groupby'=>1, 'fulltext'=>0, 'enacumulado'=>1);
$camposSisduanImp[] = array('campo'=>"deposito.deposito", 'nombre'=>_TABDEPOSITO, 'alias'=>'deposito', 'key'=>'decl.id_deposito', 'tipo'=>'s', 'sort'=>1, 'groupby'=>1, 'fulltext'=>0, 'enacumulado'=>1);
$camposSisduanImp[] = array('campo'=>"bancos.bancos", 'nombre'=>_TABBANCOS, 'alias'=>'bancos', 'key'=>'decl.id_bancos', 'tipo'=>'s', 'sort'=>1, 'groupby'=>1, 'fulltext'=>0, 'enacumulado'=>0);
$camposSisduanImp[] = array('campo'=>"sucursal.sucursal", 'nombre'=>_TABSUCURSAL, 'alias'=>'sucursal', 'key'=>'decl.id_sucursal', 'tipo'=>'s', 'sort'=>1, 'groupby'=>1, 'fulltext'=>0, 'enacumulado'=>0);
$camposSisduanImp[] = array('campo'=>"decl.tasa", 'nombre'=>_TABTASA, 'alias'=>'tasa', 'key'=>'', 'tipo'=>'n', 'sort'=>1, 'groupby'=>0, 'fulltext'=>0, 'enacumulado'=>0);
$camposSisduanImp[] = array('campo'=>"forma_pago_imp.forma_pago_imp", 'nombre'=>_TABFORMAPAGO, 'alias'=>'forma_pago_imp', 'key'=>'decl.id_forma_pago_imp', 'tipo'=>'s', 'sort'=>1, 'groupby'=>1, 'fulltext'=>0, 'enacumulado'=>0);
$camposSisduanImp[] = array('campo'=>"decl.porcen_ara", 'nombre'=>_TABPORCENARA, 'alias'=>'porcen_ara', 'key'=>'', 'tipo'=>'n', 'sort'=>1, 'groupby'=>0, 'fulltext'=>0, 'enacumulado'=>0);
$camposSisduanImp[] = array('campo'=>"decl.base_ara", 'nombre'=>_TABBASEARA, 'alias'=>'base_ara', 'key'=>'', 'tipo'=>'n', 'sort'=>1, 'groupby'=>0, 'fulltext'=>0, 'enacumulado'=>0);
$camposSisduanImp[] = array('campo'=>"decl.subtot_ara", 'nombre'=>_TABSUBTOTARA, 'alias'=>'subtot_ara', 'key'=>'', 'tipo'=>'n', 'sort'=>1, 'groupby'=>0, 'fulltext'=>0, 'enacumulado'=>0);
$camposSisduanImp[] = array('campo'=>"decl.ara_pagado", 'nombre'=>_TABARAPAGADO, 'alias'=>'ara_pagado', 'key'=>'', 'tipo'=>'n', 'sort'=>1, 'groupby'=>0, 'fulltext'=>0, 'enacumulado'=>0);
$camposSisduanImp[] = array('campo'=>"decl.valor_t_ar", 'nombre'=>_TABVALORTAR, 'alias'=>'valor_t_ar', 'key'=>'', 'tipo'=>'n', 'sort'=>1, 'groupby'=>0, 'fulltext'=>0, 'enacumulado'=>0);
$camposSisduanImp[] = array('campo'=>"decl.porcen_iva", 'nombre'=>_TABPORCENTIVA, 'alias'=>'porcen_iva', 'key'=>'', 'tipo'=>'n', 'sort'=>1, 'groupby'=>0, 'fulltext'=>0, 'enacumulado'=>0);
$camposSisduanImp[] = array('campo'=>"decl.base_iva", 'nombre'=>_TABBASEIVA, 'alias'=>'base_iva', 'key'=>'', 'tipo'=>'n', 'sort'=>1, 'groupby'=>0, 'fulltext'=>0, 'enacumulado'=>0);
$camposSisduanImp[] = array('campo'=>"decl.sustot_iva", 'nombre'=>_TABSUBTOTIVA, 'alias'=>'sustot_iva', 'key'=>'', 'tipo'=>'n', 'sort'=>1, 'groupby'=>0, 'fulltext'=>0, 'enacumulado'=>0);
$camposSisduanImp[] = array('campo'=>"decl.valor_t_iva", 'nombre'=>_TABVALORTIVA, 'alias'=>'valor_t_iva', 'key'=>'', 'tipo'=>'n', 'sort'=>1, 'groupby'=>0, 'fulltext'=>0, 'enacumulado'=>0);
$camposSisduanImp[] = array('campo'=>"decl.iva_pagado", 'nombre'=>_TABIVAPAGADO, 'alias'=>'iva_pagado', 'key'=>'', 'tipo'=>'n', 'sort'=>1, 'groupby'=>0, 'fulltext'=>0, 'enacumulado'=>0);
$camposSisduanImp[] = array('campo'=>"decl.valor_paga", 'nombre'=>_TABVALORPAGA, 'alias'=>'valor_paga', 'key'=>'', 'tipo'=>'n', 'sort'=>1, 'groupby'=>0, 'fulltext'=>0, 'enacumulado'=>0);
$camposSisduanImp[] = array('campo'=>"decl.val_aduana", 'nombre'=>_TABVALADUANA, 'alias'=>'val_aduana', 'key'=>'', 'tipo'=>'n', 'sort'=>1, 'groupby'=>0, 'fulltext'=>0, 'enacumulado'=>0);
$camposSisduanImp[] = array('campo'=>"decl.tot_pagado", 'nombre'=>_TABTOTPAGADO, 'alias'=>'tot_pagado', 'key'=>'', 'tipo'=>'n', 'sort'=>1, 'groupby'=>0, 'fulltext'=>0, 'enacumulado'=>0);
$camposSisduanImp[] = array('campo'=>"decl.num_registro", 'nombre'=>_TABNUMREGISTRO, 'alias'=>'num_registro', 'key'=>'', 'tipo'=>'s', 'sort'=>0, 'groupby'=>0, 'fulltext'=>0, 'enacumulado'=>0);
$camposSisduanImp[] = array('campo'=>"decl.ofic_aprobacion", 'nombre'=>_TABOFICAPROBACION, 'alias'=>'ofic_aprobacion', 'key'=>'', 'tipo'=>'s', 'sort'=>0, 'groupby'=>0, 'fulltext'=>0, 'enacumulado'=>0);
$camposSisduanImp[] = array('campo'=>"decl.ano_aprobacion", 'nombre'=>_TABANOAPROBACION, 'alias'=>'ano_aprobacion', 'key'=>'', 'tipo'=>'s', 'sort'=>0, 'groupby'=>0, 'fulltext'=>0, 'enacumulado'=>0);
$camposSisduanImp[] = array('campo'=>"modalidad.modalidad", 'nombre'=>_TABMODALIDAD, 'alias'=>'modalidad', 'key'=>'decl.id_modalidad', 'tipo'=>'s', 'sort'=>0, 'groupby'=>0, 'fulltext'=>0, 'enacumulado'=>0);
$camposSisduanImp[] = array('campo'=>"regimen_impo.regimen_impo", 'nombre'=>_TABREGIMENIMPO, 'alias'=>'regimen_impo', 'key'=>'decl.id_regimen_impo', 'tipo'=>'s', 'sort'=>1, 'groupby'=>1, 'fulltext'=>0, 'enacumulado'=>0);
$camposSisduanImp[] = array('campo'=>"decl.id_ciiu", 'nombre'=>_TABCIIU, 'alias'=>'id_ciiu', 'key'=>'', 'tipo'=>'s', 'sort'=>1, 'groupby'=>1, 'fulltext'=>0, 'enacumulado'=>1);
$camposSisduanImp[] = array('campo'=>"ciiu.ciiu", 'nombre'=>_TABDESCCIIU, 'alias'=>'ciiu', 'key'=>'decl.id_ciiu', 'tipo'=>'s', 'sort'=>1, 'groupby'=>1, 'fulltext'=>0, 'enacumulado'=>1);
$camposSisduanImp[] = array('campo'=>"decl.id_cuode", 'nombre'=>_TABCUODE, 'alias'=>'id_cuode', 'key'=>'', 'tipo'=>'s', 'sort'=>1, 'groupby'=>1, 'fulltext'=>0, 'enacumulado'=>0);
$camposSisduanImp[] = array('campo'=>"cuode.cuode", 'nombre'=>_TABDESCCUODE, 'alias'=>'cuode', 'key'=>'decl.id_cuode', 'tipo'=>'s', 'sort'=>1, 'groupby'=>1, 'fulltext'=>0, 'enacumulado'=>0);
//$camposSisduanImp[] = array('campo'=>"regimen.regimen", 'nombre'=>_REGIMEN, 'alias'=>'regimen', 'key'=>'decl.id_regimen', 'tipo'=>'s', 'sort'=>1, 'groupby'=>1, 'fulltext'=>0, 'enacumulado'=>0);
//$camposSisduanImp[] = array('campo'=>"decl.lineas", 'nombre'=>_TABLINEAS, 'alias'=>'lineas', 'key'=>'', 'tipo'=>'n', 'sort'=>1, 'groupby'=>1, 'fulltext'=>0, 'enacumulado'=>1);


$camposSisduanExp[] = array('campo'=>"decl.num_declara", 'nombre'=>_TABNUMDECLARA, 'alias'=>'num_declara', 'key'=>'', 'tipo'=>'s', 'sort'=>1, 'groupby'=>0, 'fulltext'=>0, 'enacumulado'=>0);
$camposSisduanExp[] = array('campo'=>"DATE_FORMAT(decl.fecha, '%Y-%m')", 'nombre'=>_TABFECHA, 'alias'=>'periodoymd', 'key'=>'decl.periodo', 'tipo'=>'s', 'sort'=>1, 'groupby'=>1, 'fulltext'=>0, 'enacumulado'=>1);
$camposSisduanExp[] = array('campo'=>"DATE_FORMAT(decl.fecha, '%Y-%m-%d')", 'nombre'=>_TABFECHAYMD, 'alias'=>'periodo', 'key'=>'decl.periodo', 'tipo'=>'s', 'sort'=>1, 'groupby'=>1, 'fulltext'=>0, 'enacumulado'=>0);
$camposSisduanExp[] = array('campo'=>"decl.id_empresa", 'nombre'=>_TABNIT, 'alias'=>'id_empresa', 'key'=>'', 'tipo'=>'s', 'sort'=>1, 'groupby'=>1, 'fulltext'=>0, 'enacumulado'=>1);
$camposSisduanExp[] = array('campo'=>"empresa.digito_cheq", 'nombre'=>_TABDIGITOCHEQUEO, 'alias'=>'digito_cheq', 'key'=>'', 'tipo'=>'s', 'sort'=>0, 'groupby'=>0, 'fulltext'=>0, 'enacumulado'=>1);
$camposSisduanExp[] = array('campo'=>"empresa.empresa", 'nombre'=>_EXPORTADOR, 'alias'=>'empresa', 'key'=>'decl.id_empresa', 'tipo'=>'s', 'sort'=>1, 'groupby'=>1, 'fulltext'=>0, 'enacumulado'=>1);
$camposSisduanExp[] = array('campo'=>"empresa.representante", 'nombre'=>_TABREPRESENTANTE, 'alias'=>'representante', 'key'=>'', 'tipo'=>'s', 'sort'=>0, 'groupby'=>0, 'fulltext'=>0, 'enacumulado'=>1);
$camposSisduanExp[] = array('campo'=>"empresa.ciudad", 'nombre'=>_TABCIUDADDOMICILIO, 'alias'=>'ciudad', 'key'=>'', 'tipo'=>'s', 'sort'=>0, 'groupby'=>0, 'fulltext'=>0, 'enacumulado'=>1);
$camposSisduanExp[] = array('campo'=>"empresa.departamentos", 'nombre'=>_TABDEPTODOMICILIO, 'alias'=>'emp_departamentos', 'key'=>'', 'tipo'=>'s', 'sort'=>0, 'groupby'=>0, 'fulltext'=>0, 'enacumulado'=>1);
$camposSisduanExp[] = array('campo'=>"empresa.telefono", 'nombre'=>_TABTELEFONO, 'alias'=>'telefono', 'key'=>'', 'tipo'=>'s', 'sort'=>0, 'groupby'=>0, 'fulltext'=>0, 'enacumulado'=>1);
$camposSisduanExp[] = array('campo'=>"empresa.fax", 'nombre'=>_TABFAX, 'alias'=>'fax', 'key'=>'', 'tipo'=>'s', 'sort'=>0, 'groupby'=>0, 'fulltext'=>0, 'enacumulado'=>1);
$camposSisduanExp[] = array('campo'=>"empresa.direccion", 'nombre'=>_TABDIRECCION, 'alias'=>'direccion', 'key'=>'', 'tipo'=>'s', 'sort'=>0, 'groupby'=>0, 'fulltext'=>0, 'enacumulado'=>1);
$camposSisduanExp[] = array('campo'=>"empresa.email", 'nombre'=>_TABEMAIL, 'alias'=>'email', 'key'=>'', 'tipo'=>'s', 'sort'=>0, 'groupby'=>0, 'fulltext'=>0, 'enacumulado'=>1);
$camposSisduanExp[] = array('campo'=>"empresa.clase", 'nombre'=>_TABCLASE, 'alias'=>'clase', 'key'=>'', 'tipo'=>'s', 'sort'=>0, 'groupby'=>0, 'fulltext'=>0, 'enacumulado'=>1);
$camposSisduanExp[] = array('campo'=>"clas_expo.clas_expo", 'nombre'=>_TABCLASEXPO, 'alias'=>'clas_expo', 'key'=>'decl.id_clas_expo', 'tipo'=>'s', 'sort'=>0, 'groupby'=>0, 'fulltext'=>0, 'enacumulado'=>0);
$camposSisduanExp[] = array('campo'=>"empresa.uap", 'nombre'=>_TABUAP, 'alias'=>'uap', 'key'=>'', 'tipo'=>'s', 'sort'=>0, 'groupby'=>0, 'fulltext'=>0, 'enacumulado'=>0);
$camposSisduanExp[] = array('campo'=>"empresa.altex", 'nombre'=>_TABALTEX, 'alias'=>'altex', 'key'=>'', 'tipo'=>'s', 'sort'=>0, 'groupby'=>0, 'fulltext'=>0, 'enacumulado'=>0);
$camposSisduanExp[] = array('campo'=>"importador.importador", 'nombre'=>_TABRAZONIMPO, 'alias'=>'importador', 'key'=>'decl.id_importador', 'tipo'=>'s', 'sort'=>1, 'groupby'=>1, 'fulltext'=>0, 'enacumulado'=>1);
$camposSisduanExp[] = array('campo'=>"paisdestino.paises", 'nombre'=>_TABPAISDESTINO, 'alias'=>'paisdestino', 'key'=>'decl.id_paisdestino', 'tipo'=>'s', 'sort'=>0, 'groupby'=>0, 'fulltext'=>0, 'enacumulado'=>1);
$camposSisduanExp[] = array('campo'=>"ciudad_prove.ciudad_prove", 'nombre'=>_TABCIUDADIMPO, 'alias'=>'ciudad_prove', 'key'=>'decl.id_ciudad_prove', 'tipo'=>'s', 'sort'=>1, 'groupby'=>1, 'fulltext'=>0, 'enacumulado'=>1);
$camposSisduanExp[] = array('campo'=>"decl.direccion_importador", 'nombre'=>_TABDIRIMPO, 'alias'=>'direccion_importador', 'key'=>'', 'tipo'=>'s', 'sort'=>0, 'groupby'=>0, 'fulltext'=>0, 'enacumulado'=>0);
$camposSisduanExp[] = array('campo'=>"decl.id_posicion", 'nombre'=>_TABPOSICION, 'alias'=>'id_posicion', 'key'=>'', 'tipo'=>'s', 'sort'=>1, 'groupby'=>1, 'fulltext'=>0, 'enacumulado'=>1);
$camposSisduanExp[] = array('campo'=>"posicion.posicion", 'nombre'=>_TABDESCRIPCIONARANCEL, 'alias'=>'posicion', 'key'=>'decl.id_posicion', 'tipo'=>'s', 'sort'=>1, 'groupby'=>1, 'fulltext'=>1, 'enacumulado'=>1);
$camposSisduanExp[] = array('campo'=>"decl.id_subpartida", 'nombre'=>_COD_ARMONIZADO, 'alias'=>'id_subpartida', 'key'=>'', 'tipo'=>'s', 'sort'=>1, 'groupby'=>1, 'fulltext'=>0, 'enacumulado'=>1);
$camposSisduanExp[] = array('campo'=>"aran.descripcion_ing", 'nombre'=>_TABDESARANCELING, 'alias'=>'descripcion_ing', 'key'=>'decl.id_subpartida', 'tipo'=>'s', 'sort'=>1, 'groupby'=>1, 'fulltext'=>1, 'enacumulado'=>1);
$camposSisduanExp[] = array('campo'=>"decl.cantidad", 'nombre'=>_TABCANTIDAD, 'alias'=>'cantidad', 'key'=>'', 'tipo'=>'n', 'sort'=>1, 'groupby'=>0, 'fulltext'=>0, 'enacumulado'=>1);
$camposSisduanExp[] = array('campo'=>"unidad_expo.unidad_expo", 'nombre'=>_TABUNIDADCOMERCIAL, 'alias'=>'unidad_expo', 'key'=>'decl.id_unidad_expo', 'tipo'=>'s', 'sort'=>1, 'groupby'=>1, 'fulltext'=>0, 'enacumulado'=>1);
$camposSisduanExp[] = array('campo'=>"decl.valorfob", 'nombre'=>_TABVALORFOB, 'alias'=>'valorfob', 'key'=>'', 'tipo'=>'n', 'sort'=>1, 'groupby'=>0, 'fulltext'=>0, 'enacumulado'=>1);
$camposSisduanExp[] = array('campo'=>"decl.valorfob_unitario", 'nombre'=>_TABVALORUNEXP, 'alias'=>'valorfob_unitario', 'key'=>'', 'tipo'=>'n', 'sort'=>1, 'groupby'=>0, 'fulltext'=>0, 'enacumulado'=>0);
$camposSisduanExp[] = array('campo'=>"decl.valorcif", 'nombre'=>_TABVALORCIF, 'alias'=>'valorcif', 'key'=>'', 'tipo'=>'n', 'sort'=>1, 'groupby'=>0, 'fulltext'=>0, 'enacumulado'=>1);
$camposSisduanExp[] = array('campo'=>"decl.peso_neto", 'nombre'=>_TABPESONETO, 'alias'=>'peso_neto', 'key'=>'', 'tipo'=>'n', 'sort'=>1, 'groupby'=>0, 'fulltext'=>0, 'enacumulado'=>1);
$camposSisduanExp[] = array('campo'=>"decl.peso_bruto", 'nombre'=>_TABPESOBRUTO, 'alias'=>'peso_bruto', 'key'=>'', 'tipo'=>'n', 'sort'=>1, 'groupby'=>0, 'fulltext'=>0, 'enacumulado'=>1);
$camposSisduanExp[] = array('campo'=>"via.via", 'nombre'=>_TABVIA, 'alias'=>'via', 'key'=>'decl.id_via', 'tipo'=>'s', 'sort'=>1, 'groupby'=>1, 'fulltext'=>0, 'enacumulado'=>1);
$camposSisduanExp[] = array('campo'=>"aduanas.aduanas", 'nombre'=>_TABADUANA, 'alias'=>'aduana', 'key'=>'decl.id_aduanas', 'tipo'=>'s', 'sort'=>1, 'groupby'=>1, 'fulltext'=>0, 'enacumulado'=>1);
$camposSisduanExp[] = array('campo'=>"departamentos.departamentos", 'nombre'=>_TABDEPTORIGEN, 'alias'=>'departamentos', 'key'=>'decl.id_deptorigen', 'tipo'=>'s', 'sort'=>1, 'groupby'=>1, 'fulltext'=>0, 'enacumulado'=>1);
$camposSisduanExp[] = array('campo'=>"departamentosproce.departamentos", 'nombre'=>_TABDEPTOPROCE, 'alias'=>'departamentosproce', 'key'=>'decl.id_deptoproce', 'tipo'=>'s', 'sort'=>1, 'groupby'=>1, 'fulltext'=>0, 'enacumulado'=>0);
$camposSisduanExp[] = array('campo'=>"aduanas_sal.aduanas", 'nombre'=>_TABCIUDADSAL, 'alias'=>'aduanas_sal', 'key'=>'decl.id_aduanas_sal', 'tipo'=>'s', 'sort'=>1, 'groupby'=>1, 'fulltext'=>0, 'enacumulado'=>1);
$camposSisduanExp[] = array('campo'=>"decl.fecha_embarque", 'nombre'=>_TABFECHAEMBARQUE, 'alias'=>'fecha_embarque', 'key'=>'', 'tipo'=>'s', 'sort'=>1, 'groupby'=>0, 'fulltext'=>0, 'enacumulado'=>0);
$camposSisduanExp[] = array('campo'=>"aduanas_emb.aduanas", 'nombre'=>_TABADUANAEMBARQUE, 'alias'=>'aduanas_emb', 'key'=>'decl.id_aduana_emb', 'tipo'=>'s', 'sort'=>1, 'groupby'=>1, 'fulltext'=>0, 'enacumulado'=>0);
$camposSisduanExp[] = array('campo'=>"decl.autorizacion_embarque", 'nombre'=>_TABAUTOEMBARQUE, 'alias'=>'autorizacion_embarque', 'key'=>'', 'tipo'=>'s', 'sort'=>0, 'groupby'=>0, 'fulltext'=>0, 'enacumulado'=>0);
$camposSisduanExp[] = array('campo'=>"decl.flete", 'nombre'=>_TABFLETE, 'alias'=>'flete', 'key'=>'', 'tipo'=>'n', 'sort'=>1, 'groupby'=>0, 'fulltext'=>0, 'enacumulado'=>0);
$camposSisduanExp[] = array('campo'=>"decl.seguro", 'nombre'=>_TABSEGUROPAN, 'alias'=>'seguro', 'key'=>'', 'tipo'=>'n', 'sort'=>1, 'groupby'=>0, 'fulltext'=>0, 'enacumulado'=>0);
$camposSisduanExp[] = array('campo'=>"decl.valor_gastos", 'nombre'=>_TABVALORGASTOS, 'alias'=>'valor_gastos', 'key'=>'', 'tipo'=>'n', 'sort'=>1, 'groupby'=>0, 'fulltext'=>0, 'enacumulado'=>0);
$camposSisduanExp[] = array('campo'=>"decl.valor_agregado", 'nombre'=>_VALAGREGADO, 'alias'=>'valor_agregado', 'key'=>'', 'tipo'=>'n', 'sort'=>1, 'groupby'=>0, 'fulltext'=>0, 'enacumulado'=>0);
$camposSisduanExp[] = array('campo'=>"decl.valor_pesos", 'nombre'=>_TABVALORPESOS, 'alias'=>'valor_pesos', 'key'=>'', 'tipo'=>'n', 'sort'=>1, 'groupby'=>0, 'fulltext'=>0, 'enacumulado'=>0);

//$camposSisduanExp[] = array('campo'=>"decl.flete_seguro_otros", 'nombre'=>_TABFLETEYSEGUROTROS, 'alias'=>'flete_seguro_otros', 'key'=>'', 'tipo'=>'n', 'sort'=>1, 'groupby'=>0, 'fulltext'=>0, 'enacumulado'=>0);
$camposSisduanExp[] = array('campo'=>"embarque.embarque", 'nombre'=>_TABEMBARQUE, 'alias'=>'embarque', 'key'=>'decl.id_embarque', 'tipo'=>'s', 'sort'=>1, 'groupby'=>1, 'fulltext'=>0, 'enacumulado'=>0);
$camposSisduanExp[] = array('campo'=>"decl.id_sia", 'nombre'=>_TABNITDECLARA, 'alias'=>'id_sia', 'key'=>'', 'tipo'=>'s', 'sort'=>1, 'groupby'=>1, 'fulltext'=>0, 'enacumulado'=>1);
$camposSisduanExp[] = array('campo'=>"sia.sia", 'nombre'=>_TABRAZONDECLARA, 'alias'=>'sia', 'key'=>'decl.id_sia', 'tipo'=>'s', 'sort'=>1, 'groupby'=>1, 'fulltext'=>0, 'enacumulado'=>1);
$camposSisduanExp[] = array('campo'=>"forma_pago_exp.forma_pago_exp", 'nombre'=>_TABFORMAPAGO, 'alias'=>'forma_pago_exp', 'key'=>'decl.id_forma_pago_exp', 'tipo'=>'s', 'sort'=>1, 'groupby'=>1, 'fulltext'=>0, 'enacumulado'=>0);
$camposSisduanExp[] = array('campo'=>"datos.datos", 'nombre'=>_TABDATOS, 'alias'=>'datos', 'key'=>'decl.id_datos', 'tipo'=>'s', 'sort'=>1, 'groupby'=>1, 'fulltext'=>0, 'enacumulado'=>0);
$camposSisduanExp[] = array('campo'=>"decl.expo_transito", 'nombre'=>_TABEXPOTRANSITO, 'alias'=>'expo_transito', 'key'=>'', 'tipo'=>'s', 'sort'=>0, 'groupby'=>0, 'fulltext'=>0, 'enacumulado'=>0);
$camposSisduanExp[] = array('campo'=>"regimen_expo.regimen_expo", 'nombre'=>_TABREGIMENAR, 'alias'=>'regimen_expo', 'key'=>'decl.id_regimen_expo', 'tipo'=>'s', 'sort'=>0, 'groupby'=>0, 'fulltext'=>0, 'enacumulado'=>0);
$camposSisduanExp[] = array('campo'=>"cert_origen.cert_origen", 'nombre'=>_TABCERTORIGEN, 'alias'=>'cert_origen', 'key'=>'decl.id_cert_origen', 'tipo'=>'s', 'sort'=>1, 'groupby'=>1, 'fulltext'=>0, 'enacumulado'=>0);
$camposSisduanExp[] = array('campo'=>"decl.id_ciiu", 'nombre'=>_TABCIIU, 'alias'=>'id_ciiu', 'key'=>'', 'tipo'=>'s', 'sort'=>1, 'groupby'=>1, 'fulltext'=>0, 'enacumulado'=>1);
$camposSisduanExp[] = array('campo'=>"ciiu.ciiu", 'nombre'=>_TABDESCCIIU, 'alias'=>'ciiu', 'key'=>'decl.id_ciiu', 'tipo'=>'s', 'sort'=>1, 'groupby'=>1, 'fulltext'=>0, 'enacumulado'=>1);
$camposSisduanExp[] = array('campo'=>"decl.id_cuode", 'nombre'=>_TABCUODE, 'alias'=>'id_cuode', 'key'=>'', 'tipo'=>'s', 'sort'=>1, 'groupby'=>1, 'fulltext'=>0, 'enacumulado'=>0);
$camposSisduanExp[] = array('campo'=>"cuode.cuode", 'nombre'=>_TABDESCCUODE, 'alias'=>'cuode', 'key'=>'decl.id_cuode', 'tipo'=>'s', 'sort'=>1, 'groupby'=>1, 'fulltext'=>0, 'enacumulado'=>0);
//$camposSisduanExp[] = array('campo'=>"decl.lineas", 'nombre'=>_TABLINEAS, 'alias'=>'lineas', 'key'=>'', 'tipo'=>'n', 'sort'=>1, 'groupby'=>1, 'fulltext'=>0, 'enacumulado'=>1);



$camposIntercambioSisduan[0] = $camposSisduanImp;
$camposIntercambioSisduan[1] = $camposSisduanExp;


$agruparSisduanImp[_TOTALES] = array("decl.valorfob_unitario","decl.base_ara","decl.porcen_ara","decl.subtot_ara","decl.ara_pagado","decl.valor_t_ar","decl.base_iva","decl.porcen_iva","decl.sustot_iva","decl.valor_t_iva","decl.iva_pagado","decl.valor_paga","decl.tasa","decl.flete_seguro_otros","decl.flete","decl.seguro","decl.cantidad","decl.peso_neto","decl.peso_bruto","decl.valorfob","decl.valorcif","decl.val_aduana","decl.tot_pagado","decl.num_bultos");
$agruparSisduanExp[_TOTALES] = array("decl.valorfob_unitario","decl.flete","decl.cantidad","decl.peso_neto","decl.peso_bruto","decl.valorfob","decl.valorcif","decl.seguro","decl.valor_gastos","decl.valor_agregado","decl.valor_pesos");


$agruparIntercambioSisduan[0] = $agruparSisduanImp;
$agruparIntercambioSisduan[1] = $agruparSisduanExp;


$filtrosSisduanImp[] = array('campo'=>'decl.id_anio', 'alias'=>'id_anio', 'filtro'=>FILTRO_ANIO, 'tabla'=>'anio', 'nombre'=>_ANIO, 'opcional'=>0, 'enacumulado'=>0, 'sphinx_attr'=>'sql_attr_uint');
$filtrosSisduanImp[] = array('campo'=>'decl.periodo', 'alias'=>'periodo', 'filtro'=>FILTRO_PERIODODESDE, 'tabla'=>'', 'nombre'=>_DESDE, 'opcional'=>0, 'enacumulado'=>0, 'sphinx_attr'=>'sql_attr_uint');
$filtrosSisduanImp[] = array('campo'=>'decl.periodo', 'alias'=>'periodo', 'filtro'=>FILTRO_PERIODOHASTA, 'tabla'=>'', 'nombre'=>_HASTA, 'opcional'=>0, 'enacumulado'=>0, 'sphinx_attr'=>'sql_attr_uint');
$filtrosSisduanImp[] = array('campo'=>'decl.id_aduanas', 'alias'=>'id_aduanas', 'filtro'=>FILTRO_ADUANAS, 'tabla'=>'aduanas', 'nombre'=>_ADUANA, 'opcional'=>1, 'enacumulado'=>1, 'sphinx_attr'=>'sql_attr_uint');
$filtrosSisduanImp[] = array('campo'=>'decl.id_posicion', 'alias'=>'id_posicion', 'filtro'=>FILTRO_POSICION, 'tabla'=>'posicion', 'nombre'=>_POSICION, 'opcional'=>1, 'enacumulado'=>1, 'sphinx_attr'=>'sql_attr_bigint');
$filtrosSisduanImp[] = array('campo'=>'decl.descripcion_prod', 'alias'=>'descripcion_prod', 'filtro'=>FILTRO_DESCRIPCIONPROD, 'tabla'=>'fulltext', 'nombre'=>_DESCRIPCIONPROD, 'opcional'=>1, 'enacumulado'=>0, 'sphinx_attr'=>'sql_attr_uint');
$filtrosSisduanImp[] = array('campo'=>'decl.id_bancos', 'alias'=>'id_bancos', 'filtro'=>FILTRO_BANCO, 'tabla'=>'bancos', 'nombre'=>_TABBANCOS, 'opcional'=>1, 'enacumulado'=>0, 'sphinx_attr'=>'sql_attr_uint');
$filtrosSisduanImp[] = array('campo'=>'decl.id_deposito', 'alias'=>'id_deposito', 'filtro'=>FILTRO_DEPOSITOADUANERO, 'tabla'=>'deposito', 'nombre'=>_DEPOSITOADUANERO, 'opcional'=>1, 'enacumulado'=>1, 'sphinx_attr'=>'sql_attr_uint');
$filtrosSisduanImp[] = array('campo'=>'decl.id_sia', 'alias'=>'id_sia', 'filtro'=>FILTRO_SIA, 'tabla'=>'sia', 'nombre'=>_SIA, 'opcional'=>1, 'enacumulado'=>1, 'sphinx_attr'=>'sql_attr_uint');
$filtrosSisduanImp[] = array('campo'=>'decl.id_via', 'alias'=>'id_via', 'filtro'=>FILTRO_VIA, 'tabla'=>'via', 'nombre'=>_VIA, 'opcional'=>1, 'enacumulado'=>1, 'sphinx_attr'=>'sql_attr_uint');
$filtrosSisduanImp[] = array('campo'=>'decl.id_transportador', 'alias'=>'id_transportador', 'filtro'=>FILTRO_TRANSPORTADOR, 'tabla'=>'transportador', 'nombre'=>_TRANSPORTADOR, 'opcional'=>1, 'enacumulado'=>1, 'sphinx_attr'=>'sql_attr_uint');
$filtrosSisduanImp[] = array('campo'=>'decl.id_empresa', 'alias'=>'id_empresa', 'filtro'=>FILTRO_NIT, 'tabla'=>'empresa', 'nombre'=>_NIT, 'opcional'=>1, 'enacumulado'=>1, 'sphinx_attr'=>'sql_attr_uint');
$filtrosSisduanImp[] = array('campo'=>'decl.id_proveedor', 'alias'=>'id_proveedor', 'filtro'=>FILTRO_PROVEEDOR, 'tabla'=>'proveedor', 'nombre'=>_PROVEEDOR, 'opcional'=>1, 'enacumulado'=>1, 'sphinx_attr'=>'sql_attr_uint');

$filtrosSisduanImp[] = array('campo'=>'proveedor.id_paises', 'alias'=>'prove_id_pais', 'filtro'=>FILTRO_PAISPROVE, 'tabla'=>'paises', 'nombre'=>_TABPAISPROVEEDOR, 'opcional'=>1, 'enacumulado'=>1, 'sphinx_attr'=>'sql_attr_uint');
$filtrosSisduanImp[] = array('campo'=>'decl.id_ciudad_prove', 'alias'=>'id_ciudad_prove', 'filtro'=>FILTRO_CIUDADPROVE, 'tabla'=>'ciudad_prove', 'nombre'=>_TABCIUDADPROVEEDOR, 'opcional'=>1, 'enacumulado'=>1, 'sphinx_attr'=>'sql_attr_uint');


$filtrosSisduanImp[] = array('campo'=>'decl.id_paisprocedencia', 'alias'=>'id_paisprocedencia', 'filtro'=>FILTRO_PAISPROCEDENCIA, 'tabla'=>'paises', 'nombre'=>_PAISPROCEDENCIA, 'opcional'=>1, 'enacumulado'=>1, 'sphinx_attr'=>'sql_attr_uint');
$filtrosSisduanImp[] = array('campo'=>'decl.id_paisorigen', 'alias'=>'id_paisorigen', 'filtro'=>FILTRO_PAISORIGEN, 'tabla'=>'paises', 'nombre'=>_PAISORIGEN, 'opcional'=>1, 'enacumulado'=>1, 'sphinx_attr'=>'sql_attr_uint');
$filtrosSisduanImp[] = array('campo'=>'decl.id_paiscompra', 'alias'=>'id_paiscompra', 'filtro'=>FILTRO_PAISCOMPRA, 'tabla'=>'paises', 'nombre'=>_PAISCOMPRA, 'opcional'=>1, 'enacumulado'=>1, 'sphinx_attr'=>'sql_attr_uint');
$filtrosSisduanImp[] = array('campo'=>'decl.id_aduanas_ing', 'alias'=>'id_aduanas_ing', 'filtro'=>FILTRO_CIUDADINGRESO, 'tabla'=>'aduanas', 'nombre'=>_CIUDADINGRESO, 'opcional'=>1, 'enacumulado'=>1, 'sphinx_attr'=>'sql_attr_uint');
$filtrosSisduanImp[] = array('campo'=>'empresa.id_ciudad', 'alias'=>'emp_id_ciudad', 'filtro'=>FILTRO_CIUDADDOM, 'tabla'=>'ciudad', 'nombre'=>_CIUDADDOM, 'opcional'=>1, 'enacumulado'=>1, 'sphinx_attr'=>'sql_attr_uint');
$filtrosSisduanImp[] = array('campo'=>'decl.id_deptorigen', 'alias'=>'id_deptorigen', 'filtro'=>FILTRO_DEPTODESTINO, 'tabla'=>'departamentos', 'nombre'=>_DEPTODESTINO, 'opcional'=>1, 'enacumulado'=>0, 'sphinx_attr'=>'sql_attr_uint');
$filtrosSisduanImp[] = array('campo'=>'decl.id_ciiu', 'alias'=>'id_ciiu', 'filtro'=>FILTRO_CIIU, 'tabla'=>'ciiu', 'nombre'=>_CIIU, 'opcional'=>1, 'enacumulado'=>1, 'sphinx_attr'=>'sql_attr_uint');
$filtrosSisduanImp[] = array('campo'=>'decl.id_cuode', 'alias'=>'id_cuode', 'filtro'=>FILTRO_CUODE, 'tabla'=>'cuode', 'nombre'=>_CUODE, 'opcional'=>1, 'enacumulado'=>0, 'sphinx_attr'=>'sql_attr_uint');
$filtrosSisduanImp[] = array('campo'=>'empresa.id_departamentos', 'alias'=>'emp_id_departamentos', 'filtro'=>FILTRO_DEPTODOM, 'tabla'=>'departamentos', 'nombre'=>_DEPTODOM, 'opcional'=>1, 'enacumulado'=>1, 'sphinx_attr'=>'sql_attr_uint');
$filtrosSisduanImp[] = array('campo'=>'decl.id_forma_pago_imp', 'alias'=>'id_forma_pago_imp', 'filtro'=>FILTRO_FORMAPAGOIMP, 'tabla'=>'forma_pago_imp', 'nombre'=>_FORMAPAGO, 'opcional'=>1, 'enacumulado'=>0, 'sphinx_attr'=>'sql_attr_uint');


$filtrosSisduanExp[] = array('campo'=>'decl.id_anio', 'alias'=>'id_anio', 'filtro'=>FILTRO_ANIO, 'tabla'=>'anio', 'nombre'=>_ANIO, 'opcional'=>0, 'enacumulado'=>0, 'sphinx_attr'=>'sql_attr_uint');
$filtrosSisduanExp[] = array('campo'=>'decl.periodo', 'alias'=>'periodo', 'filtro'=>FILTRO_PERIODODESDE, 'tabla'=>'', 'nombre'=>_DESDE, 'opcional'=>0, 'enacumulado'=>0, 'sphinx_attr'=>'sql_attr_uint');
$filtrosSisduanExp[] = array('campo'=>'decl.periodo', 'alias'=>'periodo', 'filtro'=>FILTRO_PERIODOHASTA, 'tabla'=>'', 'nombre'=>_HASTA, 'opcional'=>0, 'enacumulado'=>0, 'sphinx_attr'=>'sql_attr_uint');
$filtrosSisduanExp[] = array('campo'=>'decl.id_aduanas', 'alias'=>'id_aduanas', 'filtro'=>FILTRO_ADUANAS, 'tabla'=>'aduanas', 'nombre'=>_ADUANA, 'opcional'=>1, 'enacumulado'=>1, 'sphinx_attr'=>'sql_attr_uint');
$filtrosSisduanExp[] = array('campo'=>'decl.id_posicion', 'alias'=>'id_posicion', 'filtro'=>FILTRO_POSICION, 'tabla'=>'posicion', 'nombre'=>_POSICION, 'opcional'=>1, 'enacumulado'=>1, 'sphinx_attr'=>'sql_attr_bigint');
$filtrosSisduanExp[] = array('campo'=>'decl.id_sia', 'alias'=>'id_sia', 'filtro'=>FILTRO_SIA, 'tabla'=>'sia', 'nombre'=>_SIA, 'opcional'=>1, 'enacumulado'=>1, 'sphinx_attr'=>'sql_attr_uint');
$filtrosSisduanExp[] = array('campo'=>'decl.id_via', 'alias'=>'id_via', 'filtro'=>FILTRO_VIA, 'tabla'=>'via', 'nombre'=>_VIA, 'opcional'=>1, 'enacumulado'=>1, 'sphinx_attr'=>'sql_attr_uint');
$filtrosSisduanExp[] = array('campo'=>'decl.id_empresa', 'alias'=>'id_empresa', 'filtro'=>FILTRO_NIT, 'tabla'=>'empresa', 'nombre'=>_NIT, 'opcional'=>1, 'enacumulado'=>1, 'sphinx_attr'=>'sql_attr_uint');
$filtrosSisduanExp[] = array('campo'=>'empresa.id_ciudad', 'alias'=>'emp_id_ciudad', 'filtro'=>FILTRO_CIUDADDOM, 'tabla'=>'ciudad', 'nombre'=>_CIUDADDOM, 'opcional'=>1, 'enacumulado'=>1, 'sphinx_attr'=>'sql_attr_uint');
$filtrosSisduanExp[] = array('campo'=>'empresa.id_departamentos', 'alias'=>'emp_id_departamentos', 'filtro'=>FILTRO_DEPTODOM, 'tabla'=>'departamentos', 'nombre'=>_DEPTODOM, 'opcional'=>1, 'enacumulado'=>1, 'sphinx_attr'=>'sql_attr_uint');
$filtrosSisduanExp[] = array('campo'=>'decl.id_paisdestino', 'alias'=>'id_paisdestino', 'filtro'=>FILTRO_PAISDESTINO, 'tabla'=>'paises', 'nombre'=>_PAISDESTINO, 'opcional'=>1, 'enacumulado'=>1, 'sphinx_attr'=>'sql_attr_uint');
$filtrosSisduanExp[] = array('campo'=>'decl.id_ciiu', 'alias'=>'id_ciiu', 'filtro'=>FILTRO_CIIU, 'tabla'=>'ciiu', 'nombre'=>_CIIU, 'opcional'=>1, 'enacumulado'=>1, 'sphinx_attr'=>'sql_attr_uint');
$filtrosSisduanExp[] = array('campo'=>'decl.id_cuode', 'alias'=>'id_cuode', 'filtro'=>FILTRO_CUODE, 'tabla'=>'cuode', 'nombre'=>_CUODE, 'opcional'=>1, 'enacumulado'=>0, 'sphinx_attr'=>'sql_attr_uint');
$filtrosSisduanExp[] = array('campo'=>'decl.id_aduanas_sal', 'alias'=>'id_aduanas_sal', 'filtro'=>FILTRO_CIUDADSALIDA, 'tabla'=>'aduanas', 'nombre'=>_CIUDADSALIDA, 'opcional'=>1, 'enacumulado'=>1, 'sphinx_attr'=>'sql_attr_uint');
$filtrosSisduanExp[] = array('campo'=>'decl.id_deptorigen', 'alias'=>'id_deptorigen', 'filtro'=>FILTRO_DEPTORIGEN, 'tabla'=>'departamentos', 'nombre'=>_DEPTORIGEN, 'opcional'=>1, 'enacumulado'=>1, 'sphinx_attr'=>'sql_attr_uint');
$filtrosSisduanExp[] = array('campo'=>'decl.id_forma_pago_exp', 'alias'=>'id_forma_pago_exp', 'filtro'=>FILTRO_FORMAPAGOEXP, 'tabla'=>'forma_pago_exp', 'nombre'=>_FORMAPAGO, 'opcional'=>1, 'enacumulado'=>0, 'sphinx_attr'=>'sql_attr_uint');
$filtrosSisduanExp[] = array('campo'=>'decl.id_importador', 'alias'=>'id_importador', 'filtro'=>FILTRO_RAZONIMPORTADOR, 'tabla'=>'importador', 'nombre'=>_RAZONIMPORTADOR, 'opcional'=>1, 'enacumulado'=>1, 'sphinx_attr'=>'sql_attr_uint');


$filtrosIntercambioSisduan[0] = $filtrosSisduanImp;
$filtrosIntercambioSisduan[1] = $filtrosSisduanExp;

$tablasAuxAcum[0][] = array("tabla"=>"empresa",									"join"=>"decl.id_empresa		= empresa.id_empresa");
$tablasAuxAcum[0][] = array("tabla"=>"arancel_aduana.arancel_ingles AS aran", 	"join"=>"decl.id_subpartida     = aran.cod_armonizado");
$tablasAuxAcum[0][] = array("tabla"=>"posicion",								"join"=>"decl.id_posicion		= posicion.id_posicion");
$tablasAuxAcum[0][] = array("tabla"=>"aduanas",									"join"=>"decl.id_aduanas		= aduanas.id_aduanas");
$tablasAuxAcum[0][] = array("tabla"=>"sia",										"join"=>"decl.id_sia			= sia.id_sia");
$tablasAuxAcum[0][] = array("tabla"=>"via",										"join"=>"decl.id_via			= via.id_via");
$tablasAuxAcum[0][] = array("tabla"=>"paises AS paisprocedencia",				"join"=>"decl.id_paisprocedencia= paisprocedencia.id_paises");
$tablasAuxAcum[0][] = array("tabla"=>"paises AS paisorigen",					"join"=>"decl.id_paisorigen		= paisorigen.id_paises");
$tablasAuxAcum[0][] = array("tabla"=>"paises AS paiscompra",					"join"=>"decl.id_paiscompra		= paiscompra.id_paises");
$tablasAuxAcum[0][] = array("tabla"=>"proveedor",	 							"join"=>"decl.id_proveedor		= proveedor.id_proveedor");
$tablasAuxAcum[0][] = array("tabla"=>"bancos",									"join"=>"decl.id_bancos			= bancos.id_bancos");
$tablasAuxAcum[0][] = array("tabla"=>"ciiu",									"join"=>"decl.id_ciiu			= ciiu.id_ciiu");
$tablasAuxAcum[0][] = array("tabla"=>"transportador",							"join"=>"decl.id_transportador	= transportador.id_transportador");
$tablasAuxAcum[0][] = array("tabla"=>"ciudad_prove",							"join"=>"decl.id_ciudad_prove	= ciudad_prove.id_ciudad_prove");
$tablasAuxAcum[0][] = array("tabla"=>"deposito",								"join"=>"decl.id_deposito		= deposito.id_deposito");
$tablasAuxAcum[0][] = array("tabla"=>"aduanas AS aduanasing",					"join"=>"decl.id_aduanas_ing	= aduanasing.id_aduanas");
$tablasAuxAcum[0][] = array("tabla"=>"unidad_impo",								"join"=>"decl.id_unidad_impo	= unidad_impo.id_unidad_impo");

$tablasAuxAcum[1][] = array("tabla"=>"empresa",									"join"=>"decl.id_empresa		= empresa.id_empresa");
$tablasAuxAcum[1][] = array("tabla"=>"arancel_aduana.arancel_ingles AS aran", 	"join"=>"decl.id_subpartida     = aran.cod_armonizado");
$tablasAuxAcum[1][] = array("tabla"=>"posicion",								"join"=>"decl.id_posicion		= posicion.id_posicion");
$tablasAuxAcum[1][] = array("tabla"=>"aduanas",									"join"=>"decl.id_aduanas		= aduanas.id_aduanas");
$tablasAuxAcum[1][] = array("tabla"=>"via",										"join"=>"decl.id_via			= via.id_via");
$tablasAuxAcum[1][] = array("tabla"=>"paises AS paisdestino",					"join"=>"decl.id_paisdestino	= paisdestino.id_paises");
$tablasAuxAcum[1][] = array("tabla"=>"departamentos", 							"join"=>"decl.id_deptorigen		= departamentos.id_departamentos");
$tablasAuxAcum[1][] = array("tabla"=>"sia",										"join"=>"decl.id_sia			= sia.id_sia");
$tablasAuxAcum[1][] = array("tabla"=>"importador",	 							"join"=>"decl.id_importador		= importador.id_importador");
$tablasAuxAcum[1][] = array("tabla"=>"ciiu",									"join"=>"decl.id_ciiu			= ciiu.id_ciiu");
$tablasAuxAcum[1][] = array("tabla"=>"ciudad_prove",							"join"=>"decl.id_ciudad_prove	= ciudad_prove.id_ciudad_prove");
$tablasAuxAcum[1][] = array("tabla"=>"aduanas AS aduanas_sal",					"join"=>"decl.id_aduanas_sal    = aduanas_sal.id_aduanas");
$tablasAuxAcum[1][] = array("tabla"=>"unidad_expo",	 							"join"=>"decl.id_unidad_expo	= unidad_expo.id_unidad_expo");

$tablasAux[0][] = array("tabla"=>"aduanas",									"join"=>"decl.id_aduanas		= aduanas.id_aduanas");
$tablasAux[0][] = array("tabla"=>"modalidad",								"join"=>"decl.id_modalidad		= modalidad.id_modalidad");
$tablasAux[0][] = array("tabla"=>"bancos",									"join"=>"decl.id_bancos			= bancos.id_bancos");
$tablasAux[0][] = array("tabla"=>"ciiu",									"join"=>"decl.id_ciiu			= ciiu.id_ciiu");
$tablasAux[0][] = array("tabla"=>"cuode",									"join"=>"decl.id_cuode			= cuode.id_cuode");
$tablasAux[0][] = array("tabla"=>"empresa",									"join"=>"decl.id_empresa		= empresa.id_empresa");
$tablasAux[0][] = array("tabla"=>"ciudad",									"join"=>"decl.id_ciudad_dom		= ciudad.id_ciudad");
$tablasAux[0][] = array("tabla"=>"posicion",								"join"=>"decl.id_posicion		= posicion.id_posicion");
$tablasAux[0][] = array("tabla"=>"arancel_aduana.arancel_ingles AS aran", 	"join"=>"decl.id_subpartida     = aran.cod_armonizado");
$tablasAux[0][] = array("tabla"=>"deposito",								"join"=>"decl.id_deposito		= deposito.id_deposito");
$tablasAux[0][] = array("tabla"=>"sia",										"join"=>"decl.id_sia			= sia.id_sia");
$tablasAux[0][] = array("tabla"=>"via",										"join"=>"decl.id_via			= via.id_via");
$tablasAux[0][] = array("tabla"=>"transportador",							"join"=>"decl.id_transportador	= transportador.id_transportador");
$tablasAux[0][] = array("tabla"=>"paises AS paisprocedencia",				"join"=>"decl.id_paisprocedencia= paisprocedencia.id_paises");
$tablasAux[0][] = array("tabla"=>"paises AS paisorigen",					"join"=>"decl.id_paisorigen		= paisorigen.id_paises");
$tablasAux[0][] = array("tabla"=>"paises AS paiscompra",					"join"=>"decl.id_paiscompra		= paiscompra.id_paises");
$tablasAux[0][] = array("tabla"=>"paises AS paisbandera",					"join"=>"decl.id_bandera		= paisbandera.id_paises");
$tablasAux[0][] = array("tabla"=>"aduanas AS aduanasing",					"join"=>"decl.id_aduanas_ing	= aduanasing.id_aduanas");
$tablasAux[0][] = array("tabla"=>"departamentos", 							"join"=>"decl.id_deptorigen		= departamentos.id_departamentos");
$tablasAux[0][] = array("tabla"=>"proveedor",	 							"join"=>"decl.id_proveedor		= proveedor.id_proveedor");
$tablasAux[0][] = array("tabla"=>"regimen_impo",	 						"join"=>"decl.id_regimen_impo	= regimen_impo.id_regimen_impo");
$tablasAux[0][] = array("tabla"=>"forma_pago_imp",	 						"join"=>"decl.id_forma_pago_imp	= forma_pago_imp.id_forma_pago_imp");
$tablasAux[0][] = array("tabla"=>"sucursal",	 							"join"=>"(decl.id_sucursal		= sucursal.id_sucursal AND decl.id_bancos = sucursal.id_bancos)");
$tablasAux[0][] = array("tabla"=>"unidad_impo",								"join"=>"decl.id_unidad_impo	= unidad_impo.id_unidad_impo");
$tablasAux[0][] = array("tabla"=>"ciudad_prove",							"join"=>"decl.id_ciudad_prove	= ciudad_prove.id_ciudad_prove");

$tablasAux[1][] = array("tabla"=>"empresa",									"join"=>"decl.id_empresa		= empresa.id_empresa");
$tablasAux[1][] = array("tabla"=>"arancel_aduana.arancel_ingles AS aran", 	"join"=>"decl.id_subpartida     = aran.cod_armonizado");
$tablasAux[1][] = array("tabla"=>"posicion",								"join"=>"decl.id_posicion		= posicion.id_posicion");
$tablasAux[1][] = array("tabla"=>"aduanas",									"join"=>"decl.id_aduanas		= aduanas.id_aduanas");
$tablasAux[1][] = array("tabla"=>"via",										"join"=>"decl.id_via			= via.id_via");
$tablasAux[1][] = array("tabla"=>"paises AS paisdestino",					"join"=>"decl.id_paisdestino	= paisdestino.id_paises");
$tablasAux[1][] = array("tabla"=>"departamentos", 							"join"=>"decl.id_deptorigen		= departamentos.id_departamentos");
$tablasAux[1][] = array("tabla"=>"ciiu",									"join"=>"decl.id_ciiu			= ciiu.id_ciiu");
$tablasAux[1][] = array("tabla"=>"cuode",									"join"=>"decl.id_cuode			= cuode.id_cuode");
$tablasAux[1][] = array("tabla"=>"regimen_expo",	 						"join"=>"decl.id_regimen_expo	= regimen_expo.id_regimen_expo");
$tablasAux[1][] = array("tabla"=>"clas_expo",	 							"join"=>"decl.id_clas_expo		= clas_expo.id_clas_expo");
$tablasAux[1][] = array("tabla"=>"aduanas AS aduanas_sal",					"join"=>"decl.id_aduanas_sal    = aduanas_sal.id_aduanas");
$tablasAux[1][] = array("tabla"=>"departamentos AS departamentosproce",		"join"=>"decl.id_deptoproce		= departamentosproce.id_departamentos");
$tablasAux[1][] = array("tabla"=>"forma_pago_exp",	 						"join"=>"decl.id_forma_pago_exp	= forma_pago_exp.id_forma_pago_exp");
$tablasAux[1][] = array("tabla"=>"cert_origen",	 							"join"=>"decl.id_cert_origen	= cert_origen.id_cert_origen");
$tablasAux[1][] = array("tabla"=>"aduanas AS aduanas_emb",					"join"=>"decl.id_aduanas_emb    = aduanas_emb.id_aduanas");
$tablasAux[1][] = array("tabla"=>"embarque",								"join"=>"decl.id_embarque    	= embarque.id_embarque");
$tablasAux[1][] = array("tabla"=>"datos",									"join"=>"decl.id_datos    		= datos.id_datos");
$tablasAux[1][] = array("tabla"=>"sia",										"join"=>"decl.id_sia			= sia.id_sia");
$tablasAux[1][] = array("tabla"=>"importador",	 							"join"=>"decl.id_importador		= importador.id_importador");
$tablasAux[1][] = array("tabla"=>"tradicional",	 							"join"=>"decl.id_tradicional	= tradicional.id_tradicional");
$tablasAux[1][] = array("tabla"=>"unidad_expo",	 							"join"=>"decl.id_unidad_expo	= unidad_expo.id_unidad_expo");
$tablasAux[1][] = array("tabla"=>"ciudad_prove",							"join"=>"decl.id_ciudad_prove	= ciudad_prove.id_ciudad_prove");
/******************************************************************* configuracion del directorio de empresas ***********************************************************************/

$camposDirectorio = array();

$camposDirectorio[] = array('campo'=>"empresa.id_empresa", 'nombre'=>_TABNIT, 'alias'=>'id_empresa', 'key'=>'', 'tipo'=>'s', 'sort'=>1, 'groupby'=>1, 'fulltext'=>0);
$camposDirectorio[] = array('campo'=>"empresa.digito_cheq", 'nombre'=>_TABDIGITOCHEQUEO, 'alias'=>'digito_cheq', 'key'=>'', 'tipo'=>'s', 'sort'=>0, 'groupby'=>0, 'fulltext'=>0);
$camposDirectorio[] = array('campo'=>"empresa.empresa", 'nombre'=>_RAZON, 'alias'=>'empresa', 'key'=>'decl.id_empresa', 'tipo'=>'s', 'sort'=>1, 'groupby'=>1, 'fulltext'=>1);
$camposDirectorio[] = array('campo'=>"empresa.representante", 'nombre'=>_TABREPRESENTANTE, 'alias'=>'representante', 'key'=>'', 'tipo'=>'s', 'sort'=>0, 'groupby'=>0, 'fulltext'=>1);
$camposDirectorio[] = array('campo'=>"empresa.ciudad", 'nombre'=>_TABCIUDADDOMICILIO, 'alias'=>'ciudad', 'key'=>'empresa.id_ciudad', 'tipo'=>'s', 'sort'=>0, 'groupby'=>0, 'fulltext'=>0);
$camposDirectorio[] = array('campo'=>"empresa.departamentos", 'nombre'=>_TABDEPTODOMICILIO, 'alias'=>'departamentos', 'key'=>'', 'tipo'=>'s', 'sort'=>0, 'groupby'=>0, 'fulltext'=>0);
$camposDirectorio[] = array('campo'=>"empresa.telefono", 'nombre'=>_TABTELEFONO, 'alias'=>'telefono', 'key'=>'', 'tipo'=>'s', 'sort'=>0, 'groupby'=>0, 'fulltext'=>0);
$camposDirectorio[] = array('campo'=>"empresa.fax", 'nombre'=>_TABFAX, 'alias'=>'fax', 'key'=>'', 'tipo'=>'s', 'sort'=>0, 'groupby'=>0, 'fulltext'=>0);
$camposDirectorio[] = array('campo'=>"empresa.direccion", 'nombre'=>_TABDIRECCION, 'alias'=>'direccion', 'key'=>'', 'tipo'=>'s', 'sort'=>0, 'groupby'=>0, 'fulltext'=>1);
$camposDirectorio[] = array('campo'=>"empresa.email", 'nombre'=>_TABEMAIL, 'alias'=>'email', 'key'=>'', 'tipo'=>'s', 'sort'=>0, 'groupby'=>0, 'fulltext'=>1);
$camposDirectorio[] = array('campo'=>"empresa.clase", 'nombre'=>_TABCLASE, 'alias'=>'clase', 'key'=>'', 'tipo'=>'s', 'sort'=>0, 'groupby'=>0, 'fulltext'=>0);
$camposDirectorio[] = array('campo'=>"empresa.uap", 'nombre'=>_TABUAP, 'alias'=>'uap', 'key'=>'', 'tipo'=>'s', 'sort'=>0, 'groupby'=>0, 'fulltext'=>0);
$camposDirectorio[] = array('campo'=>"empresa.altex", 'nombre'=>_TABALTEX, 'alias'=>'altex', 'key'=>'', 'tipo'=>'s', 'sort'=>0, 'groupby'=>0, 'fulltext'=>0);
$camposDirectorio[] = array('campo'=>"GROUP_CONCAT( DISTINCT empresa_posicion.id_posicion ORDER BY empresa_posicion.id_posicion ASC SEPARATOR ' - ')", 'nombre'=>_TABPOSICION, 'alias'=>'id_posicion', 'key'=>'', 'tipo'=>'s', 'sort'=>1, 'groupby'=>1, 'fulltext'=>1);
//$camposDirectorio[] = array('campo'=>"GROUP_CONCAT(posicion.posicion)", 'nombre'=>_TABDESCRIPCIONARANCEL, 'alias'=>'posicion', 'key'=>'decl.id_posicion', 'tipo'=>'s', 'sort'=>1, 'groupby'=>1, 'fulltext'=>1);

$tablasDirectorioAux[] = array("tabla"=>"empresa",	"join"=>"id_empresa");
//$tablasDirectorioAux[] = array("tabla"=>"posicion",	"join"=>"id_posicion");

$filtrosDirectorio = array();

$filtrosDirectorio[] = array('campo'=>'empresa.id_empresa', 'alias'=>'id_empresa', 'filtro'=>FILTRO_NIT, 'tabla'=>'empresa', 'nombre'=>_NIT, 'opcional'=>1);
$filtrosDirectorio[] = array('campo'=>'empresa_posicion.id_posicion', 'alias'=>'id_posicion', 'filtro'=>FILTRO_POSICION, 'tabla'=>'posicion', 'nombre'=>_POSICION, 'opcional'=>1);
$filtrosDirectorio[] = array('campo'=>'empresa.id_ciudad', 'alias'=>'emp_id_ciudad', 'filtro'=>FILTRO_CIUDADDOM, 'tabla'=>'ciudad', 'nombre'=>_CIUDADDOM, 'opcional'=>1);
$filtrosDirectorio[] = array('campo'=>'empresa.id_departamentos', 'alias'=>'emp_id_departamentos', 'filtro'=>FILTRO_DEPTODOM, 'tabla'=>'departamentos', 'nombre'=>_DEPTODOM, 'opcional'=>1);

