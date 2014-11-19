<?php
//ini_set("display_errors",true);
set_time_limit(0);
ini_set('memory_limit', '128M');
//Trae la sesión que esté asignada
session_start();
//Variables de configuración del sistema
include ("../lib/config.php");
//Incluye el diccionario
include (PATH_APP."lib/idioma.php");
include (PATH_APP."lib/lib_sesion.php");
include (PATH_APP."lib/lib_sphinx.php");
include (PATH_APP."lib/lib_funciones.php");

$intercambio = '0';
$acumulado   = '0';

$crear_nuevo = ($accion == EDIT)?false:true;
$descripcion = utf8_decode($descripcion);

include_once(PATH_RAIZ.'sicex_r/lib/reportes/reportesAdo.php');

/*--------------------------------------------------trae la informacion del pais --------------------------------------------------------------------*/
include_once(PATH_RAIZ.'sicex_r/lib/pais/paisAdo.php');
$paisAdo = new PaisAdo('sicex_r');
$pais    = new Pais;
$pais->setPais_id($pais_id);
$paisDs  = $paisAdo->lista($pais);
$db = strtolower($paisDs[0]["pais_bd"]);

if($db != ""){	
	if(file_exists(PATH_RAIZ."lib/".$db.".php")){
		include (PATH_RAIZ."lib/".$db.".php");
	}
	else{
		$respuesta = array(
			"success"=>false,
			"errors"=>array("reason"=>"No existe configuracion para el pais")
		);
		echo json_encode($respuesta);
		exit();
	}
}
else{
	$respuesta = array(
		"success"=>false,
		"errors"=>array("reason"=>"No tiene asignado país de consulta")
	);
	echo json_encode($respuesta);
	exit();
}

$js_filtros1 = "";
$js_filtros2 = "";
/*--------------------------------------------------trae la informacion del reporte --------------------------------------------------------------------*/
if($accion == COPY || $accion == EDIT){
	if($reporte != ''){
		$reportesAdo = new ReportesAdo('sicex_r');
		$reportes    = new Reportes;
		$reportes->setReportes_id($reporte);
		$reportes->setReportes_uinsert($_SESSION['session_usuario_id']);
		$reportes->setReportes_isleaf("1");
		$rsReportes = $reportesAdo->lista($reportes); //como busco por ID del reporte, debe devolver solo un registro
		
		$intercambio = $rsReportes[0]["reportes_intercambio"];
		$campos_rep  = $rsReportes[0]['reportes_campos'];
		$filtros     = $rsReportes[0]['reportes_filtros'];
		$acumulado   = $rsReportes[0]['reportes_acumulado'];
		$nombre      = $rsReportes[0]['reportes_nombre'];
		$filas		 = $rsReportes[0]['reportes_filas'];
		$columnas	 = $rsReportes[0]['reportes_columnas'];
		$totales	 = $rsReportes[0]['reportes_totales'];
		$producto_id = $rsReportes[0]['reportes_producto_id'];
		
		
		$_camposIntercambio = $camposIntercambioSisduan[$intercambio];
		if(!empty($_SESSION['usuario_tpl'])){	
			$_camposIntercambio = orig_campos_reporte_usuario_tpl($_SESSION['usuario_tpl'],$producto_id,$pais_id,$intercambio,$_camposIntercambio);
		}
		
		$js_card1 = "
			Ext.getCmp(modulo+'ctNombre_reporte').setValue('".$nombre."');
			Ext.getCmp(modulo+'intercambio').setValue('".$intercambio."');
			Ext.getCmp(modulo+'acumulado').setValue('".$acumulado."');
		";
		
		/*extrae la informacion de filtros para cargarlos en los selectedbox*/
		$_filtrosIntercambio = $filtrosIntercambioSisduan[$intercambio];
		$_filtros = convierteArreglo($filtros);
		foreach($_filtros as $key => $data){
			if($key == FILTRO_ANIO){
				$anio = $data;
			}
			elseif($key == FILTRO_PERIODODESDE){
				$perini = $data;
			}
			elseif($key == FILTRO_PERIODOHASTA){
				$perfin = $data;
			}
			else{
				$origFiltro = filtroDatos($_filtrosIntercambio, $key);
				if($origFiltro){
					$js_filtros1 .= "
						crearFiltro('".$key."', '".traducir($origFiltro["nombre"])."', ".$intercambio.", true);
					";
					$js_filtros2 .= "
						if(Ext.getCmp(modulo+'combo_".$key."')){
							Ext.getCmp(modulo+'combo_".$key."').setValue('".$data."');
						}
					";
				}
			}
		}
		if($perini >= ULTIMO_ANO || $perfin >= ULTIMO_ANO){
			$js_card2 .= "
				Ext.getCmp(modulo+'comboPeriodoPersonalizado').setValue('".$perini."');
				Ext.getCmp(modulo+'anio').setValue('').clearInvalid();
				Ext.getCmp(modulo+'comboPeriodoIni').setValue('').clearInvalid();
				Ext.getCmp(modulo+'comboPeriodoFin').setValue('').clearInvalid();
				Ext.getCmp(modulo+'anio').setDisabled(true);
				Ext.getCmp(modulo+'comboPeriodoIni').setDisabled(true);
				Ext.getCmp(modulo+'comboPeriodoFin').setDisabled(true);
			";
		}
		else{
			$js_card2 .= "
				Ext.getCmp(modulo+'comboPeriodoPersonalizado').setValue('".PERIODOPERSONALIZADO."');
				Ext.getCmp(modulo+'anio').setValue('".$anio."');
				Ext.getCmp(modulo+'comboPeriodoIni').setValue('".$perini."');
				Ext.getCmp(modulo+'comboPeriodoFin').setValue('".$perfin."');
			";
		}
		$js_card3 = $js_filtros1;
		$_arrCamposRep = convierteArreglo($campos_rep);
		$_arrcamposSel = array();
		foreach($_arrCamposRep as $key => $data){
			foreach($_camposIntercambio as $subkey => $campo){
				if($campo['campo'] == $key){
					$_arrcamposSel[] = $subkey;
				}
			}
		}
		$js_card4 = "
			Ext.getCmp(modulo+'itemselector').setValue('".implode(",",$_arrcamposSel)."');
		";
	}
}
//print_r($campos);
/*--------------------------------------------------fin la informacion del reporte --------------------------------------------------------------------*/

/*-------------------------------------------------- fin trae la informacion del pais --------------------------------------------------------------------*/
$arr_inter = array();
$logica_impo = ($_SESSION['session_producto'][$producto]['servimp'] == 0)?'true':'false';
$logica_expo = ($_SESSION['session_producto'][$producto]['servexp'] == 0)?'true':'false';
foreach($_intercambio as $key => $label){
	if($key == 0){
		$arr_inter[] = "{boxLabel:'". traducir($label) ."', disabled:".$logica_impo.", inputValue: ".$key.", name:'intercambio'}";
	}
	else{
		$arr_inter[] = "{boxLabel:'". traducir($label) ."', disabled:".$logica_expo.", inputValue: ".$key.", name:'intercambio'}";
	}
}

$arr_fini  = explode("-", $_SESSION['session_producto'][$producto]['fechainicio']);
$fecha_min = mktime(0,0,0, $arr_fini[1], $arr_fini[2], $arr_fini[0]);
$fecha_max = mktime(0,0,0, $arr_fini[1] + $_SESSION['session_producto'][$producto]['duracion'], 0, $arr_fini[0]);

$return = "(function(){
		var modulo = 'cfg_sisduan_".$modulo."';
		
		var fechaMin = new Date('".date("m/d/Y",$fecha_min)."');
		var fechaMax = new Date('".date("m/d/Y",$fecha_max)."');
		
		var meses = [
			[1,'"._ENERO."'],
			[2,'"._FEBRERO."'],
			[3,'"._MARZO."'],
			[4,'"._ABRIL."'],
			[5,'"._MAYO."'],
			[6,'"._JUNIO."'],
			[7,'"._JULIO."'],
			[8,'"._AGOSTO."'],
			[9,'"._SEPTIEMBRE."'],
			[10,'"._OCTUBRE."'],
			[11,'"._NOVIEMBRE."'],
			[12,'"._DICIEMBRE."']
		];
		var periodos = [
			 [".PERIODOPERSONALIZADO.",'"._PERIODOPERSONALIZADO."']
			,[".ULTIMO_ANO.",'"._ULTIMO_ANO."']
			,[".ULTIMO_SEMESTRE.",'"._ULTIMO_SEMESTRE."']
			,[".ULTIMO_TRIMESTRE.",'"._ULTIMO_TRIMESTRE."']
			,[".ULTIMO_BIMESTRE.",'"._ULTIMO_BIMESTRE."']
			,[".ULTIMO_MES.",'"._ULTIMO_MES."']
		];

		var storePeriodoIni = new Ext.data.ArrayStore({
			fields:['perini','mes']
			,data:meses
		});
		var storePeriodoFin = new Ext.data.ArrayStore({
			fields:['perfin','mes']
			,data:meses
		});
		var storePeriodoPersonalizado = new Ext.data.ArrayStore({
			fields:['periodo','periodoDes']
			,data:periodos
		});
		var storeFiltros = new Ext.data.JsonStore({
			url:'proceso/filtros/'
			,root:'datos'
			,sortInfo:{field:'filtros_order',direction:'ASC'}
			,totalProperty:'total'
			,fields:[
				 {name:'filtros_id',type:'float'}
				,{name:'filtros_order',type:'float'}
				,{name:'filtros_nombre',type:'string'}
			]
			,baseParams:{accion:'lista'}
		});
		
		var storeCamposDisponibles = new Ext.data.JsonStore({
			 url:'proceso/campos/'
			,root:'datos'
			,sortInfo:{field:'campos_order',direction:'ASC'}
			,totalProperty:'total'
			,idProperty:'campos_order'
			,fields:[
				 'campos_id'
				,'campos_order'
				,'campos_nombre'
				,{name:'campos_id',type:'string'}
				,{name:'campos_order',type:'float'}
				,{name:'campos_nombre',type:'string'}
			]
			,baseParams:{accion:'listaDisponibles'}
		});
		
		var storeCamposSeleccionados = new Ext.data.ArrayStore({
			fields:[
				 {name:'campos_id',type:'string'}
				,{name:'campos_order',type:'float'}
				,{name:'campos_nombre',type:'string'}
			]
		});
		
		var comboPeriodoIni = new Ext.form.ComboBox({
			hiddenName:'periodoIni'
			,id:modulo+'comboPeriodoIni'
			,store:storePeriodoIni
			,valueField:'perini'
			,displayField:'mes'
			,typeAhead:false
			,forceSelection:true
			,selectOnFocus:true
			,allowBlank:false
			,triggerAction:'all'
			,flex:true
			,mode:'local'
		});
		
		var comboPeriodoFin = new Ext.form.ComboBox({
			hiddenName:'periodoFin'
			,id:modulo+'comboPeriodoFin'
			,store:storePeriodoFin
			,valueField:'perfin'
			,displayField:'mes'
			,typeAhead:false
			,forceSelection:true
			,selectOnFocus:true
			,allowBlank:false
			,triggerAction:'all'
			,flex:true
			,mode:'local'
		});
		
		var comboPeriodoPersonalizado = new Ext.form.ComboBox({
			hiddenName:'periodoPersonalizado'
			,id:modulo+'comboPeriodoPersonalizado'
			,fieldLabel:'"._PERIODOPREDEFINIDO."'
			,store:storePeriodoPersonalizado
			,displayField:'periodoDes'
			,valueField:'periodo'
			,typeAhead:false
			,forceSelection:true
			,selectOnFocus:true
			,allowBlank:false
			,triggerAction:'all'
			,mode:'local'
			,listeners:{
				select:{
					fn:function(combo,reg){
						var logica = false;
						if(reg.data.periodo != ".PERIODOPERSONALIZADO."){
							logica = true;
							Ext.getCmp(modulo+'comboPeriodoIni').setValue('').clearInvalid();
							Ext.getCmp(modulo+'comboPeriodoFin').setValue('').clearInvalid();
							Ext.getCmp(modulo+'anio').setValue('').clearInvalid();
						}
						Ext.getCmp(modulo+'comboPeriodoIni').setDisabled(logica);
						Ext.getCmp(modulo+'comboPeriodoFin').setDisabled(logica);
						Ext.getCmp(modulo+'anio').setDisabled(logica);
					}
				}
			}
		});
		
		var comboFiltros = new Ext.form.ComboBox({
			id:modulo+'comboFiltros'
			,fieldLabel:'"._FILTROS."'
			,store:storeFiltros
			,valueField:'filtros_id'
			,displayField:'filtros_nombre'
			,typeAhead:false
			,forceSelection:true
			,selectOnFocus:true
			,triggerAction:'all'
			,mode:'local'
			,submitValue:false
			,plugins: [ new Ext.ux.FieldHelp('"._VALIDASELECCIONCAMPO."') ]
			,listeners:{
				select: {
					fn: function(combo, reg){
						var inter = Ext.getCmp(modulo+'intercambio').getValue().getGroupValue();
						crearFiltro(reg.data.filtros_id, reg.data.filtros_nombre, inter, false);
					}
				}
			}
		});
		
		var infoPanel = new Ext.Panel({
			title:'<b>". _RESUMEN."</b>'
			,border:true
			,autoHeight:true
			,layout:'fit'
			,region:'north'
			,items:[{
				html:'". $descripcion."'
				,bodyStyle:'padding: 10px;'
				,id:modulo+'infoPanel'
				,autoScroll:true
				,height: 50
				,border:false
			}]
		});
		
		storeCamposDisponibles.load({
			params:{
				pais_id:". $pais_id."
				,intercambio:".$intercambio."
				,acumulado:".$acumulado."
			}
		});
		storeFiltros.load({
			params:{
				pais_id:". $pais_id."
				,intercambio:".$intercambio."
				,acumulado:".$acumulado."
			}
		});
		var descripcion = new Object();
		var altura = Math.floor(Ext.getCmp('tabpanel').getInnerHeight() - 120);
		
		var formCard0 = new Ext.FormPanel({
			id:modulo+'formCard0'
			,autoWidth:true
			,autoScroll:true
			,monitorValid:true
			,bodyStyle:'padding:15px;'
			,items:[{
				xtype:'fieldset'
				,title:'<b>"._NOMBRE."</b>'
				,layout:'column'
				,defaults:{
					columnWidth:1
					,layout:'form'
					,labelAlign:'top'
					,border:false
					,xtype:'panel'
				}
				,items:[{
					defaults:{anchor:'100%'}
					,items:[{
						xtype:'textfield'
						,id:modulo+'ctNombre_reporte'
						,allowBlank:false
						,plugins:[new Ext.ux.FieldHelp('"._AYUDANOMBREREPORTE."') ]
					}]
				}]
			},{
				xtype:'fieldset'
				,title:''
				,layout:'column'
				,defaults:{
					columnWidth:1
					,layout:'form'
					,labelAlign:'top'
					,border:false
					,xtype:'panel'
					,bodyStyle:'padding:0 18px 0 0'
				}
				,items:[{
					defaults:{anchor:'100%'}
					,items:[{
						xtype:'radiogroup'
						,fieldLabel:'". _INTERCAMBIO."'
						,id:modulo+'intercambio'
						,allowBlank:false
						,plugins:[ new Ext.ux.FieldHelp('"._AYUDAINTERCAMBIO."') ]
						,items:[
							".implode(",",$arr_inter)."
						]
						,listeners:{
							'change':function(radio, checked){
								var acum = (Ext.getCmp(modulo+'acumulado').getValue().getGroupValue() == '1')?true:false;
								if(!acum){										
									storeCamposDisponibles.load({
										params:{
											pais_id:". $pais_id."
											,intercambio:radio.getValue().getGroupValue()
											,acumulado:Ext.getCmp(modulo+'acumulado').getValue().getGroupValue()
										}
									});
								}
								storeFiltros.load({
									params:{
										pais_id:". $pais_id."
										,intercambio:radio.getValue().getGroupValue()
										,acumulado:Ext.getCmp(modulo+'acumulado').getValue().getGroupValue()
									}
								});
								Ext.getCmp(modulo+'comboFiltros').clearValue();
								Ext.getCmp(modulo+'filtros').removeAll(true);
								if(Ext.getCmp(modulo+'itemselector')){
									if(Ext.getCmp(modulo+'itemselector').isVisible()){
										Ext.getCmp(modulo+'itemselector').reset();
									}
								}
							}
						}
					}]
				}]
			},{
				xtype:'fieldset'
				,title:''
				,layout:'column'
				,defaults:{
					columnWidth:1
					,layout:'form'
					,labelAlign:'top'
					,border:false
					,xtype:'panel'
					,bodyStyle:'padding:0 18px 0 0'
				}
				,items:[{
					defaults:{anchor:'100%'}
					,items:[{
						xtype:'radiogroup'
						,fieldLabel:'"._ACUMULADO."'
						,id:modulo+'acumulado'
						,allowBlank:false
						,plugins: [ new Ext.ux.FieldHelp('"._AYUDAACUMULADO."') ]
						,items: [
							{boxLabel:'". _TRDETALLADO ."', checked:true, inputValue: 0, name:'acumulado'},
							{boxLabel:'". _TRACUMULADO ."', inputValue: 1, name:'acumulado'}
						]
						,listeners:{
							'change':function(radio, checked){
								var acum = (checked.inputValue == '1')?true:false;
								if(!acum){
									storeCamposDisponibles.load({
										params:{
											pais_id:". $pais_id."
											,intercambio:Ext.getCmp(modulo+'intercambio').getValue().getGroupValue()
											,acumulado:radio.getValue().getGroupValue()
										}
									});
								}
								
								storeFiltros.load({
									params:{
										pais_id:". $pais_id."
										,intercambio:Ext.getCmp(modulo+'intercambio').getValue().getGroupValue()
										,acumulado:radio.getValue().getGroupValue()
									}
								});
								Ext.getCmp(modulo+'comboFiltros').clearValue();
								Ext.getCmp(modulo+'filtros').removeAll(true);
								if(Ext.getCmp(modulo+'itemselector')){
									if(Ext.getCmp(modulo+'itemselector').isVisible()){
										Ext.getCmp(modulo+'itemselector').reset();
									}
								}
							}
						}
					}]
				}]
			}]
		});
		var formCard1 = new Ext.FormPanel({
			id:modulo+'formCard1'
			,autoWidth:true
			,autoScroll:true
			,monitorValid:true
			,bodyStyle:'padding:15px;'
			,items:[{
				xtype:'fieldset'
				,title:'<b>"._FECHADISPONIBLE."</b>'
				,layout:'column'
				,defaults:{
					columnWidth:1
					,layout:'form'
					,labelAlign:'top'
					,border:false
					,xtype:'panel'
					,bodyStyle:'padding:0 18px 0 0'
				}
				,items:[{
					defaults:{anchor:'100%'}
					,html:'<h3 style=\'float:left\'>". _FECHADISPONIBLE."&nbsp;&nbsp;&nbsp;<span id=\'fdisponible\' style=\'border:0; color:#f6931f; font-weight:bold;\'></span></h3>'
					,height:40
				}]
			},{
				xtype:'fieldset'
				,title:'<b>"._PERIODO."</b>'
				,layout:'column'
				,defaults:{
					columnWidth:.4
					,layout:'form'
					,labelAlign:'top'
					,border:false
					,xtype:'panel'
					,bodyStyle:'padding:0 18px 0 0'
				}
				,items:[{
					defaults:{anchor:'100%'}
					,columnWidth:1
					,html:'"._INDEXPERIODO."'
					,height:30
				},{
					defaults:{anchor:'100%'}
					,columnWidth:.2
					,items:[comboPeriodoPersonalizado]
				},{
					defaults:{anchor:'100%'}
					,columnWidth:1
					,border:false
					,html:'&nbsp;'
				},{
					defaults:{anchor:'100%'}
					,columnWidth:.2
					,items:[{
						xtype:'spinnerfield'
						,id:modulo+'anio'
						,name:'anio'
						,fieldLabel:'"._ANIO."'
						,accelerate:true
						,allowBlank:false
					}]
				},{
					defaults:{anchor:'100%'}
					,items:[comboPeriodoIni]
				},{
					defaults:{anchor:'100%'}
					,items:[comboPeriodoFin]
				}]
			}]
		});
		
		var formCard2 = new Ext.FormPanel({
			id:modulo+'formCard2'
			,autoWidth:true
			,autoScroll:true
			,monitorValid:true
			,bodyStyle:'padding:15px;'
			,items:[{
				xtype:'fieldset'
				,title:'<b>"._ADICIONAFILTRO."</b>'
				,layout:'column'
				,defaults:{
					columnWidth:1
					,layout:'form'
					,labelAlign:'top'
					,border:false
					,xtype:'panel'
					,bodyStyle:'padding:0 18px 0 0'
				}
				,items:[{
					defaults:{anchor:'88%'}
					,items:[comboFiltros]
				}]
			},{
				xtype:'fieldset'
				,title:'<b>"._FILTROS."</b>'
				,layout:'column'
				,defaults:{
					columnWidth:1
					,layout:'form'
					,labelAlign:'top'
					,border:false
					,xtype:'panel'
					,bodyStyle:'padding:0 18px 0 0'
				}
				,items:[{
					defaults:{
						border:false
						,closable:true
						,layout:'form'
						,anchor:'100%'
					}
					,id:modulo+'filtros'
					,items:[]
				}]
			}]
		});
		var formCard3 = new Ext.FormPanel({
			id:modulo+'formCard3'
			,autoWidth:true
			,autoScroll:true
			,monitorValid:true
			,bodyStyle:'padding:15px;'
			,items:[{
				xtype:'fieldset'
				,title:'<b>"._CAMPOS."</b>'
				,layout:'column'
				,defaults:{
					columnWidth:1
					,layout:'form'
					,labelAlign:'top'
					,border:false
					,xtype:'panel'
					,bodyStyle:'padding:0 18px 0 0'
				}
				,items:[{
					defaults:{anchor:'100%'}
					,items:[{
						xtype:'itemselector'
						,id:modulo+'itemselector'
						,bodyStyle:'padding: 15px 10px;'
						,name:'itemselector'
						,fieldLabel:''
						,imagePath:'". URL_RAIZ."images/'
						,multiselects:[{
							width:320
							,height:altura - 100
							,store:storeCamposDisponibles
							,displayField:'campos_nombre'
							,valueField:'campos_order'
						},{
							width:320
							,height:altura - 100
							,allowBlank:false
							,store:storeCamposSeleccionados
						}]
						,listeners: {
							'afterrender':{fn: function(ms){
								".(($accion == ADD)?"":"cargar_card4()")."
							}}
						}
					}]
				}]
			}]
		});
		
		var cardWizard = new Ext.Panel({
			id:modulo+'card-wizard'
			,region:'center'
			,layout:'card'
			,activeItem:0
			,defaults:{
				layout:'fit'
				,xtype:'panel'
			}
			,bbar:[{
				id:modulo+'cancel'
				,text:'"._BTNCANCEL."'
				,handler:function(){
					Ext.getCmp('".$tree."').cargar('".$reporte."');
				}
				,iconCls:'icon-close'
			},'->',{
				id:modulo+'card-prev'
				,text:'&laquo; ". _ANTERIOR."'
				,handler:cardNav.createDelegate(this, [-1])
				,disabled:true
			},{
				id:modulo+'card-next'
				,text:'"._SIGUIENTE." &raquo;'
				,handler:cardNav.createDelegate(this, [1])
			},{
				id:modulo+'card-finish'
				,text:'"._SAVE."'
				,iconCls:'icon-save'
				,hidden:true
				,handler:cardNav.createDelegate(this, [0])
			}],
			items:[{
				id:modulo+'card-0'
				,items:[formCard0]
			},{
				id:modulo+'card-1'
				,items:[formCard1]
			},{
				id:modulo+'card-2'
				,items:[formCard2]
			},{
				id:modulo+'card-3'
				,items:[formCard3]
			}]
		});
		
		var layout_cfg_sisduan = new Ext.Panel({
			id:'layout_cfg_sisduan'
			,frame:false
			,layout:'border'
			,border:false
			,items:[
				infoPanel,cardWizard
			]
		});
		
		
		".(($accion == ADD)?"":"cargar_card1()")."
		".(($accion == ADD)?"":"cargar_card2()")."
		".(($accion == ADD)?"":"cargar_card3()")."
		
		
		return layout_cfg_sisduan;
		
		/*----------------------------------------------- INICIO FUNCIONES ----------------------------------------------*/
		function crearFiltro(filtro, nombre, intercambio, primervez){
			var contenedor = Ext.getCmp(modulo+'filtros');
			var id = modulo+'filtro_' + filtro;
			var filtroPanel = contenedor.findById(id);
			
			var parametros = new Object();
			parametros['id'] = filtro;
			parametros['paisId'] = ".$pais_id.";
			parametros['intercambio'] = intercambio;
			parametros['producto'] = ".$producto.";
			parametros['modulo'] = modulo;
			
			if(!filtroPanel){
				filtroPanel = new Ext.Panel({
					id:id
					,title:nombre
					,anchor:'95%'
					,autoShow:true
					,border:true
					,frame:true
					,closeAction:'hide'
					,tools:[{
						id:'close'
						,qtip:'"._QUITARTODO."'
						,handler:function(event, toolEl, panel){
							panel.removeAll(true);
							contenedor.remove(panel, true)
							contenedor.doLayout();
						}
					},{
						id:'help'
						,qtip:'Get Help'
						,handler:function(event, toolEl, panel){
							//whatever
						}
					}]
					,plugins:new Ext.ux.Plugin.RemoteComponent({
						 url:'jscode/filtros/'
						,params:parametros
						,loadOn:'show'
						,method:'POST'
						,listeners:{
							'success':{fn:function(a,b,c){
								if(primervez){
									".$js_filtros2."
								}
							}}
						}
					})
				}).show();
				contenedor.add(filtroPanel);
				contenedor.doLayout();
			}
		}
		
		function descripcion_filt(){
			var descripcion_filtros = '';
			var filtros_sel = Ext.getCmp(modulo+'filtros').items.items;
			Ext.each(filtros_sel,function(filtro_panel){
				if(filtro_panel.items.items[0].xtype == 'textfield'){
					descripcion_filtros += '->' + filtro_panel.title + ': <i>' + filtro_panel.items.items[0].getValue() + '</i> ';
				}
				else{
					var valores = new Array();;
					var seleccion = filtro_panel.items.items[0].getSelectedRecords();
					Ext.each(seleccion,function(row){
						valores.push('['+row.get('valor_id')+'] ' + row.get('valor_desc_ori'));
					});
					descripcion_filtros += '->' + filtro_panel.title + ': ( ' + valores + ' )';
				}
				
			});
			return descripcion_filtros.substring(2);
		}
		function guardarReporte(){
			var arr_campos = new Object();
			if(Ext.getCmp(modulo+'acumulado').getValue().getGroupValue() == '0'){
				var campos = Ext.getCmp(modulo+'itemselector').getValue();
				var reg = '';			
				Ext.each(campos,function(campo){
					reg = storeCamposSeleccionados.getById(campo);
					arr_campos[reg.data.campos_id] = reg.data.campos_nombre;
				});				
			}
			var parametros = new Object();
			parametros['reporte']     = '".$reporte."';
			parametros['producto']    = '".$producto."';
			parametros['pais_id'] 	  = '".$pais_id."';
			parametros['intercambio'] = Ext.getCmp(modulo+'intercambio').getValue().getGroupValue();
			parametros['padre']       = '".$padre."';
			parametros['accion'] 	  = '".(($crear_nuevo)?"creaHoja":"actHoja")."';
			parametros['acumulado']   = Ext.getCmp(modulo+'acumulado').getValue().getGroupValue();
			parametros['nombre']   	  = Ext.getCmp(modulo+'ctNombre_reporte').getValue();
			parametros['anio']   	  = Ext.getCmp(modulo+'anio').getValue();
			parametros['periodoIni']  = Ext.getCmp(modulo+'comboPeriodoIni').getValue();
			parametros['periodoFin']  = Ext.getCmp(modulo+'comboPeriodoFin').getValue();
			parametros['periodoPersonalizado'] = Ext.getCmp(modulo+'comboPeriodoPersonalizado').getValue();
			parametros['campos']      = Ext.encode(arr_campos);
			parametros['filtros']     = Ext.encode(Ext.getCmp(modulo+'formCard2').getForm().getFieldValues());
			parametros['detalle']     = '". $descripcion." ->' + descripcion['inter'] + '->' + descripcion['tipo'] + '->"._PERIODO.":' + descripcion['anio'] + '-' + descripcion['periodo'] + '->' + descripcion['descripcion_filtros'];
			parametros['filas']    	  = '".htmlspecialchars($filas, ENT_QUOTES)."'
			parametros['columnas']    = '".htmlspecialchars($columnas, ENT_QUOTES)."'
			parametros['totales']     = '".htmlspecialchars($totales, ENT_QUOTES)."'
			Ext.Ajax.request({
				url:'proceso/reportes/'
				,method:'POST'
				,scope:this
				,timeout: 100000
				,params: parametros
				,success: function(response){
					Ext.getCmp('".$tree."').cargar('".$reporte."');
				}
				,failure: function(response){
					results = Ext.decode(response.responseText);
					if(results.msg) {
						Ext.Msg.alert('Infomation',results.msg);
					}
				}
			});
		}
		
		function cargar_card1(){
			".(($accion == ADD)?"":$js_card1)."
		}
		function cargar_card2(){
			".(($accion == ADD)?"":$js_card2)."
						
		}
		function cargar_card3(){
			".(($accion == ADD)?"":$js_card3)."
		}
		function cargar_card4(){
			".(($accion == ADD)?"":$js_card4)."
		}
		function cardNav(incr){		
			var l = Ext.getCmp(modulo+'card-wizard').getLayout();
			var i = l.activeItem.id.split(modulo+'card-')[1];		
			
			Ext.getCmp('".$modulo."sbPanel').setStatus({
				text:''
			});
			var valid = Ext.getCmp(modulo+'formCard'+i).getForm().isValid();
			if(!valid && incr > 0){ /*valida solo hacia adelante*/
				Ext.getCmp('".$modulo."sbPanel').setStatus({
					text:'"._CAMPOREQUERIDO."',
					iconCls:'x-status-error'
				});
				return;
			}
			if(incr == 0){//cuando guarda el wizard
				if(Ext.getCmp(modulo+'acumulado').getValue().getGroupValue() == '0'){
					var campos = Ext.getCmp(modulo+'itemselector').getValue();
					if(campos == ''){
						Ext.getCmp('".$modulo."sbPanel').setStatus({
							text:'"._CAMPOREQUERIDO."',
							iconCls:'x-status-error'
						});
						return;
					}
				}
				else{
					if(Ext.getCmp(modulo+'filtros').items.items.length == 0){
						Ext.getCmp('".$modulo."sbPanel').setStatus({
							text:'"._VALIDASELECCIONCAMPO."',
							iconCls:'x-status-error'
						});
						return;
					}
					descripcion['descripcion_filtros'] = descripcion_filt();
				}
				guardarReporte();
			}
			Ext.getCmp(modulo+'card-finish').hide();
			Ext.getCmp(modulo+'card-next').show();
			if(incr > 0){
				switch (i){
					case '0':
						var oc = {
							 url:'proceso/periodo_consulta/'			
							,method:'POST'
							,scope:this
							,timeout: 100000
							,params:{
								producto:'".$producto."'
								,intercambio:Ext.getCmp(modulo+'intercambio').getValue().getGroupValue()
								,pais:". $pais_id."
								,accion:'listaPeriodo'
							}
							,callback: function (options, success, response){
								var jsonPeriodo = Ext.util.JSON.decode(response.responseText);
								f = jsonPeriodo.datos[0].fechaMin.split('-');
								var fdisponibleMin = new Date(f[1]+'/'+f[2]+'/'+f[0]);
								
								f = jsonPeriodo.datos[0].fechaMax.split('-');
								var fdisponibleMax = new Date(f[1]+'/'+f[2]+'/'+f[0]);
								
								var fdisponibleMinAnio = fdisponibleMin;
								var fdisponibleMaxAnio = fdisponibleMax;
								if(fdisponibleMin < fechaMin){
									fdisponibleMinAnio = fechaMin;
								}
								if(fdisponibleMax > fechaMax){
									fdisponibleMaxAnio = fechaMax;
								}
								
								var inter = (Ext.getCmp(modulo+'intercambio').getValue().getGroupValue() == '0') ?'". _IMPORTACIONES."':'". _EXPORTACIONES."';
								var tipo  = (Ext.getCmp(modulo+'acumulado').getValue().getGroupValue() == '0') ?'"._TRDETALLADO."':'". _TRACUMULADO."';
								
								descripcion['inter'] = inter;
								descripcion['tipo']  = tipo;
								
								Ext.getCmp(modulo+'infoPanel').update('". $descripcion." ->' + inter  + '->' + tipo);
								
								Ext.getCmp(modulo+'anio').setMaxValue(parseInt(fdisponibleMaxAnio.format('Y')));
								Ext.getCmp(modulo+'anio').setMinValue(parseInt(fdisponibleMinAnio.format('Y')));
								
								".(($accion == ADD)
								?"
								Ext.getCmp(modulo+'anio').setValue(parseInt(fdisponibleMaxAnio.format('Y')));
								Ext.getCmp(modulo+'comboPeriodoIni').setValue(1);
								Ext.getCmp(modulo+'comboPeriodoFin').setValue((fdisponibleMax.format('m')));
								Ext.getCmp(modulo+'comboPeriodoPersonalizado').setValue('".PERIODOPERSONALIZADO."');
								"
								:""
								)."								
								Ext.get('fdisponible').update(fdisponibleMin.format('F') + ' ". _DE." ' + fdisponibleMin.format('Y') + ' ". _HASTA." ' + fdisponibleMax.format('F') + ' ". _DE." ' + fdisponibleMax.format('Y'));
							}
						};				
						Ext.Ajax.request(oc);				
					break;
					case '1':
						var perini  = Ext.getCmp(modulo+'comboPeriodoIni').getValue();
						var perfin  = Ext.getCmp(modulo+'comboPeriodoFin').getValue();
						var anio    = Ext.getCmp(modulo+'anio').getValue();
						
						var f = perini + '/01/' + anio;
						var fseleccionadaMin = new Date(f);
						var f = perfin + '/01/' + anio;
						var fseleccionadaMax = new Date(f);
						if(((fseleccionadaMin < fechaMin) || (fseleccionadaMax > fechaMax)) && perini != '' && perfin != ''){
							Ext.getCmp(modulo+'comboPeriodoFin').markInvalid('"._ELVALORNOESVALIDO."');
							Ext.getCmp('".$modulo."sbPanel').setStatus({
								text:'"._PERIODONOPERMITIDO."' + fechaMin.format('F, Y') + ' "._HASTA." ' + fechaMax.format('F, Y'),
								iconCls:'x-status-error'
							});
							return;
						}
						Ext.getCmp(modulo+'comboPeriodoFin').clearInvalid();
						var periodo = '';
						if(perini == '' || perfin == ''){
							periodo = Ext.getCmp(modulo+'comboPeriodoPersonalizado').getRawValue();
						}
						else if(perini == perfin){
							periodo = fseleccionadaMin.format('F');
						}
						else{
							periodo = fseleccionadaMin.format('F') + '-' + fseleccionadaMax.format('F');
						}
						
						descripcion['anio']    = anio;
						descripcion['periodo'] = periodo;
						
						Ext.getCmp(modulo+'infoPanel').update('". $descripcion." ->' + descripcion['inter'] + '->' + descripcion['tipo'] + '->' + anio + '->' + periodo);
						
						if(Ext.getCmp(modulo+'acumulado').getValue().getGroupValue() == '1'){
							Ext.getCmp(modulo+'card-next').hide();
							Ext.getCmp(modulo+'card-finish').show();
						}
					break;
					case '2':
						if(Ext.getCmp(modulo+'filtros').items.items.length == 0){
							Ext.getCmp('".$modulo."sbPanel').setStatus({
								text:'"._VALIDASELECCIONCAMPO."',
								iconCls:'x-status-error'
							});
							return;
						}
						var descripcion_filtros = descripcion_filt();
						descripcion['descripcion_filtros'] = descripcion_filtros;
						Ext.getCmp(modulo+'infoPanel').update('". $descripcion." ->' + descripcion['inter'] + '->' + descripcion['tipo'] + '->' + descripcion['anio'] + '->' + descripcion['periodo'] + '->' + descripcion_filtros);
						Ext.getCmp(modulo+'card-next').hide();
						Ext.getCmp(modulo+'card-finish').show();
												
					break;
				}
			}
			
			var next = parseInt(i, 10) + incr;
			l.setActiveItem(next);
			Ext.getCmp(modulo+'card-prev').setDisabled(next==0);
			Ext.getCmp(modulo+'card-next').setDisabled(next==3);
		};
		/*----------------------------------------------- FIN FUNCIONES ----------------------------------------------*/
		
		
	})()
";

print ($return);