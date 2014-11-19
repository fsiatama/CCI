<?php
set_time_limit(0);
ini_set('memory_limit', '128M');
//Trae la sesión que esté asignada
session_start();

//Variables de configuración del sistema
include ("../lib/config.php");
include (PATH_APP."lib/idioma.php");
include (PATH_APP."lib/lib_sesion.php");
include (PATH_APP."lib/lib_sphinx.php");
include (PATH_APP."lib/lib_funciones.php");

/*--------------------------------------------------trae la informacion del reporte --------------------------------------------------------------------*/
include_once(PATH_RAIZ.'sicex_r/lib/reportes/reportesAdo.php');
$reportesAdo = new ReportesAdo('sicex_r');
$reportes    = new Reportes;
$reportes->setReportes_id($reporte);
$reportes->setReportes_uinsert($_SESSION['session_usuario_id']);
$reportes->setReportes_isleaf("1");
$rsReportes = $reportesAdo->lista($reportes); //como busco por ID del reporte, debe devolver solo un registro

unset($_SESSION["reporte_detallado"][$reporte]);
unset($_SESSION["reporte_pivot"][$reporte]);
	
$intercambio = $rsReportes[0]["reportes_intercambio"];
$pais_id	 = $rsReportes[0]["reportes_pais_id"];
$producto	 = $rsReportes[0]["reportes_producto_id"];
$campos_rep  = $rsReportes[0]['reportes_campos'];
$filtros     = $rsReportes[0]['reportes_filtros'];
$acumulado	 = $rsReportes[0]['reportes_acumulado'];
$nombre_rep  = $rsReportes[0]['reportes_nombre'];
//$descripcion = $rsReportes[0]['reportes_detalle'];
$descripcion_arr = explode("->",$rsReportes[0]['reportes_detalle']);

$arr_filas    = ($rsReportes[0]["reportes_filas"] == "")?array():explode("||",$rsReportes[0]["reportes_filas"]);
$arr_columnas = ($rsReportes[0]["reportes_columnas"] == "")?array():explode("||",$rsReportes[0]["reportes_columnas"]);
$arr_totales  = ($rsReportes[0]["reportes_totales"] == "")?array():explode("||",$rsReportes[0]["reportes_totales"]);

//print_r($arr_filas);

$descripcion .= "<ul>";

$periodo_arr = json_decode($periodo, true);
if($periodo_arr["periodoPersonalizado"] != PERIODOPERSONALIZADO && !empty($periodo_arr["periodoPersonalizado"])){
	$descripcion .= "<li><strong class=\"\">"._PERIODO_MODIFICADO."</strong>:&nbsp;";
	switch($periodo_arr["periodoPersonalizado"]){
		case ULTIMO_ANO:
			$descripcion .= ""._ULTIMO_ANO."";
		break;
		case ULTIMO_SEMESTRE:
			$descripcion .= ""._ULTIMO_SEMESTRE."";
		break;
		case ULTIMO_TRIMESTRE:
			$descripcion .= ""._ULTIMO_TRIMESTRE."";
		break;
		case ULTIMO_BIMESTRE:
			$descripcion .= ""._ULTIMO_BIMESTRE."";
		break;
		case ULTIMO_MES:
			$descripcion .= ""._ULTIMO_MES."";
		break;
		case ULTIMO_QUINCENA:
			$descripcion .= ""._ULTIMO_QUINCENA."";
		break;
		case ULTIMO_SEMANA:
			$descripcion .= ""._ULTIMO_SEMANA."";
		break;
	}
	$descripcion .= "</li>";
}
elseif(!empty($periodo_arr["anio"]) && !empty($periodo_arr["perini"]) && !empty($periodo_arr["perfin"])){
	$descripcion .= "<li><strong class=\"\">"._PERIODO_MODIFICADO."</strong>:&nbsp;";
	$descripcion .= $periodo_arr["anio"]."-".traducir($_periodo[$periodo_arr["perini"]])."-".traducir($_periodo[$periodo_arr["perfin"]])."";
	$descripcion .= "</li>";
}

foreach($descripcion_arr as $key => $data){
	$item_arr = explode(":",$data);
		
	$descripcion .= "<li><strong class=\"\">".$item_arr[0]."</strong>";
	
	if(!empty($item_arr[1])){
		$subdesc   = explode(",",$item_arr[1]);
		if(count($subdesc) == 1){
			$descripcion .= ":&nbsp;".$subdesc[0];
		}
		else{
			$descripcion .= ":&nbsp;<ul>";
			foreach($subdesc as $subkey => $subdata){
				$descripcion .= "<li><small>".sanear_string($subdata)."</small></li>";
			}
			$descripcion .= "</ul>";
		}
	}
	$descripcion .= "</li>";
}
$descripcion .= "</ul>";
if($rsReportes[0]["reportes_pendiente"] == 1){
	$file = buscar_archivo(PATH_REPORTES, $reporte."*");
	if($file){
		$descripcion .= "<p><a target=\"new\" href=\"download-excel/".$file."/\" class=\"btn btn-block btn-primary \" >"._DOWNLOAD."</a></p>";
	}
}

//print_r($files);

//print $descripcion;

//$descripcion = str_replace("->","<br>",$descripcion);

//print_r($campos);
/*--------------------------------------------------fin la informacion del reporte --------------------------------------------------------------------*/

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
			"errors"=>array("reason"=>"No existe configurtación para el pais")
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

/*-------------------------------------------------- extrae la informacion de los campos para la consulta --------------------------------------------------------------------*/

$_camposIntercambio = $camposIntercambioSisduan[$intercambio];
$tmp_campos = array();
foreach($_camposIntercambio as $key => $data){
	if($acumulado == "1"){
		if($data['enacumulado'] == 1){
			$tmp_campos[]=$data["campo"].":".$data["nombre"];
		}
	}
	else{
		$tmp_campos[]=$data["campo"].":".$data["nombre"];
	}
}
$campos = implode("||",$tmp_campos);

/*-------------------------------------------------- fin trae la informacion del pais --------------------------------------------------------------------*/

/*-------------------------------------------------- busca el reporte del usuario_tpl, si existe --------------------------------------------------------------------*/

if(isset($_SESSION['usuario_tpl']) && $_SESSION['usuario_tpl'] != ""){ 
	$arr_reporte_tpl = buscar_reporte_usuario_tpl($_SESSION['usuario_tpl'],$producto,$pais_id,$intercambio);
	if($arr_reporte_tpl != false){
		//elimina los campos no permitidos del reporte
		$_arr = explode("||",$campos);
		$_arr_tpl = explode("||",$arr_reporte_tpl['reportes_campos']);
		$_arr_tmp = array();
		$_arr_tmp2 = array();
		foreach($_arr as $key => $data){
			$arr_tmp_campo = explode(":",$data);
			$_arr_tmp[] = $arr_tmp_campo[0]; //solo el nombre de la base de datos
			$_arr_tmp2[$arr_tmp_campo[0]] = $arr_tmp_campo[1];
		}
		$_arr = $_arr_tmp;
		
		$_arr_tmp = array();
		foreach($_arr_tpl as $key => $data){
			$arr_tmp_campo = explode(":",$data);
			$_arr_tmp[] = $arr_tmp_campo[0]; //solo el nombre de la base de datos
		}
		$_arr_tpl = $_arr_tmp;
		
		$_arr_tmp = array_intersect($_arr,$_arr_tpl);
		$_arr2 = array();
		foreach($_arr_tmp as $key => $data){
			$_arr2[] = $data.":".$_arr_tmp2[$data];
		}
		$campos = implode("||",$_arr2);
	}
}

/*-------------------------------------------------- fin busca el reporte del usuario_tpl, si existe --------------------------------------------------------------------*/

/*-------------------------------------------------- extrae la informacion de los campos para la consulta --------------------------------------------------------------------*/

$_arrCampos = convierteArreglo($campos);
$_arrCamposRep = convierteArreglo($campos_rep);
//print_r($_arrCamposRep);
$origCampo  = array();
$arrCampos  = array();
$arrTitulos = array();
$arrTipos   = array();


$campos_store    = array();
$campos_cm       = array();
$campos_tree     = array();
$campos_fulltext = array();
$campos_nofulltext = array();

$campos_totales = $agruparIntercambioSisduan[$intercambio][_TOTALES];
$i = 0;
$tot_arrCamposRep = count($_arrCamposRep);
foreach($_arrCampos as $campo => $titulo) {
	$i++;
	$origCampo = campoDatos($_camposIntercambio, $campo);
	$arrTitulos[] = $titulo;
	
	$hidden   = 'function()true';
	$original = 'function()false';
	
	if($origCampo['tipo'] == 'n'){
		$tipo = 'float';
		$align = 'right';
		$renderer = "function()numberFormat";
	}
	else{
		$tipo = 'string';
		$align = 'left';
		$renderer = "";
	}
	//verifica si el campo es sortable
	if($origCampo['sort'] == '1'){
		$sortable = "function()true";
	}
	else{
		$sortable = "function()false";
	}
	//verifica si el campo es agrupable
	if($origCampo['groupby'] == '1'){
		$groupable = "function()true";
	}
	else{
		$groupable = "function()false";
	}
	//establece el alias de los campos
	if($origCampo['alias'] == ''){
		//$arrCampos[] = $campo;
		$alias = str_replace("decl.","",$campo);
	}
	else{
		$alias = $origCampo['alias'];
	}
	//verifica si el campo permite busquedas textuales
	if($origCampo['fulltext'] == '1'){
		$campos_fulltext[] = $alias;
	}
	else{
		$campos_nofulltext[] = $alias;
	}
	
	//si el campo esta dentro de los almacenados en el reporte, lo hace visible
	if(array_key_exists($campo, $_arrCamposRep)){
		$hidden   = 'function()false';
		$original = 'function()true';
		//asigna la pocicion en la que se muestra dentro de la grilla
		//print($campo."\n");
		$indice = array_search($campo,array_keys($_arrCamposRep));
		//print array_search($campo,array_keys($_arrCamposRep))."\n";
		//print($indice."\n");
	}
	else{
		//asigna la pocicion en la que se muestra dentro de la grilla
		$indice = $tot_arrCamposRep + $i;
	}
	
	
	$arrCampos[] = $campo . " AS " . $alias;
	
	$campos_store[] = array("name"=>($alias), "type"=>$tipo);
	$titulo = utf8_encode($titulo);
	if(in_array($campo, $campos_totales)){
		$campos_cm[$indice] = array("dataIndex"=>$alias, "header"=>($titulo), "align"=>$align, "renderer"=>$renderer, "sortable"=>$sortable, "groupable"=>$groupable, "original"=>$original, "hidden"=>$hidden, "summaryType"=>"sum");
	}
	else{
		$campos_cm[$indice] = array("dataIndex"=>$alias, "header"=>($titulo), "align"=>$align, "renderer"=>$renderer, "sortable"=>$sortable, "groupable"=>$groupable, "original"=>$original, "hidden"=>$hidden);
	}
	/*$campos_tree[]= array(
		'id'=>array($campo=>$alias)
		,'text'=>sanear_string($titulo)
		,'leaf'=>true
		,'order'=>$i
	);*/
	$campos_tree[]= array(
		'id'=>$campo
		,"alias"=>$alias
		,'text'=>($titulo)
		,'leaf'=>true
		,'order'=>$i
		,'key'=>$origCampo['key']
		,'checked'=>false
		,"type"=>$tipo
	);
}
ksort($campos_cm);
$tmp_arr = $campos_cm;
$campos_cm = array();
foreach($tmp_arr as $key => $data){
	$campos_cm[] = $data;
}
//print_r($campos_cm);
//print_r($campos_cm);

$return = "
	(function(){
		var altura = Math.floor(Ext.getCmp('tabpanel').getInnerHeight() - 150);
		var cantidad_reg = Math.floor((Ext.getCmp('tabpanel').getInnerHeight() - 245)/22);
		
		var store_". $reporte." = new Ext.data.GroupingStore({
			proxy:new Ext.data.HttpProxy({
				url:'proceso/declaraciones/'
				,timeout: 1000000000
				,method: 'POST'
			})
			,autoLoad:false
			,reader: new Ext.data.JsonReader({
				root: 'datos'
				,totalProperty: 'total'
			}
			,".json_encode($campos_store)."
			)
			//,restful:true
			//,groupOnSort:true
        	,remoteGroup:true
			,remoteSort:true
			,sortInfo:{field:'id', direction: 'DESC'}
			,baseParams:{accion:'lista', reporte:".$reporte."}
		});
		
		var colModel_". $reporte." = new Ext.grid.ColumnModel({
			columns:".json_encode_jsfunc($campos_cm)."
			,defaults: {
				sortable: false,
				width: 150
			}
			,listeners: {
				hiddenchange: function(cm, colIndex, hidden) {
					var columnas=new Object();
					Ext.iterate(cm.columns, function(key, value) {
						if((key.hidden == undefined || key.hidden == false) && key.header != ''){
							columnas[key.dataIndex] = key.header;
						}
					}, this);
				}
				,columnmoved: function(cm, oldIndex, newIndex ) {
					//console.log(cm, oldIndex, newIndex);
				}
			}
		});
		
		store_". $reporte.".on('beforeload', function(){
			var filtros_adicionales = Ext.getCmp('comboFiltrosAdi_". $reporte."').getValue();
			store_". $reporte.".setBaseParam('limit', cantidad_reg);
			store_". $reporte.".setBaseParam('filtros_adicionales', filtros_adicionales);
			store_". $reporte.".setBaseParam('periodo', '".$periodo."');
		});
		var grid_".$reporte." = new Ext.grid.GridPanel({
			border: false
			,monitorResize:true
			,store: store_".$reporte."
			,colModel: colModel_".$reporte."
			,stateful:true
			,columnLines:true
			,stripeRows:true
			,viewConfig: {
				scrollOffset:2
				,autoScroll: true
			}
			,id:'grid_".$reporte."'			
			,sm: new Ext.grid.RowSelectionModel({singleSelect:true})
			,bbar:new Ext.PagingToolbar({pageSize:cantidad_reg, store:store_".$reporte.", displayInfo:true})
			,tbar:[]
			,iconCls: 'silk-grid'
			,plugins:[
				 new Ext.ux.grid.Excel()
				,new Ext.ux.gridOrden()
				,new Ext.ux.grid.Search({
					 iconCls:'silk-zoom'
					,id:'searchid'
					,minChars:3
					,autoFocus:false
					,width:300
					,mode:'remote'
					,position:top
					,disableIndexes:".json_encode($campos_nofulltext)."
				})]
			,layout:'fit'
			,height:altura
			,autoWidth:true
            ,margins:'10 15 5 0'
			,view:new Ext.grid.GroupingView()
		});
				
		grid_".$reporte.".on({
			afterlayout:{scope:this, single:true, fn:function(){
				store_". $reporte.".load();
			}}
		});
		
		/*elimiar cualquier estado de la grilla guardado con anterioridad */
		Ext.state.Manager.clear(grid_".$reporte.".getItemId());
		
		/**********************************************************************************************/
		/*****creacion de los arboles para los campos de las pivot************************************/
		
		var Tree = Ext.tree;
		
		var treeDisponibles_root_".$reporte." = new Ext.tree.AsyncTreeNode({
			 text: 'prueba'
			,type: 'root'
			,draggable: false
			,id:'treeDisponibles_root_".$reporte."'
			,expanded: true
			,uiProvider: false
			,iconCls: 'silk-folder'
			,children: ".json_encode($campos_tree)."
		});
		
		var treeDisponiblesHeight = (altura <= 560)?150:altura - 410;
		
		var treeDisponibles_".$reporte." = new Tree.TreePanel({
			id:'treeDisponibles_".$reporte."'
			,animate:true
			,autoScroll:true
			,autoDestoy:true
			,rootVisible: false
			,title:'"._CAMPOS."'
			,root:treeDisponibles_root_".$reporte."
			,enableDD:true
			,containerScroll: true
			,border:false
			,height:treeDisponiblesHeight
			,ddGroup:'tree".$reporte."'
			,dropConfig: {allowContainerDrop:true,ddGroup:'tree".$reporte."'}
			,contextMenu: new Ext.menu.Menu({
				id:'".$reporte."menu'
				,items: [{
					id:'".$reporte."a-fila'
					,text:'"._AFILA."'
					,iconCls:'icon-fila'					
				},{
					id:'".$reporte."a-columna'
					,text:'"._ACOLUMNA."'
					,iconCls:'icon-columna'
				},{
					id:'".$reporte."a-total'
					,text:'"._ATOTAL."'
					,iconCls:'icon-sigma'
				},{
					id:'".$reporte."restaurar'
					,text:'"._QUITARTODO."'
					,iconCls:'icon-clear'
				}],
				listeners: {
					itemclick: function(item) {
						switch (item.id) {
							case '".$reporte."a-fila':
								var n = item.parentMenu.contextNode;
								var padre = Ext.getCmp('treeFilas_".$reporte."').getRootNode();
								padre.appendChild(n);
							break;
							case '".$reporte."a-columna':
								var n = item.parentMenu.contextNode;
								var padre = Ext.getCmp('treeColumnas_".$reporte."').getRootNode();
								padre.appendChild(n);
							break;
							case '".$reporte."a-total':
								var n = item.parentMenu.contextNode;
								var padre = Ext.getCmp('treeTotales_".$reporte."').getRootNode();
								padre.appendChild(n);
							break;
							case '".$reporte."restaurar':
								var t = Ext.getCmp('treeTotales_".$reporte."').getRootNode();
								var f = Ext.getCmp('treeFilas_".$reporte."').getRootNode();
								var c = Ext.getCmp('treeColumnas_".$reporte."').getRootNode();
								var padre = Ext.getCmp('treeDisponibles_".$reporte."').getRootNode();
								
								while (t.childNodes.length > 0){									
									padre.appendChild(t.childNodes[0]);
								}
								while (f.childNodes.length > 0){									
									padre.appendChild(f.childNodes[0]);
								}
								while (c.childNodes.length > 0){									
									padre.appendChild(c.childNodes[0]);
								}
								
							break;
						}
					}
				}
			})
			,listeners: {
				contextmenu: function(node, e){
					node.select();
					var c = node.getOwnerTree().contextMenu;
					c.contextNode = node;
					c.showAt(e.getXY());
				}
				,beforedestroy: function(panel){
					Ext.getCmp('".$reporte."menu').destroy();
				}
				,'append':function(tree,parent,node,index){
					node.getUI().toggleCheck(false)
				}
				,'checkchange': function(node, checked){
					if(checked){
						var tipo = node.attributes.type;
						if(tipo == 'string'){
							var padre = Ext.getCmp('treeFilas_".$reporte."').getRootNode();
						}
						else{
							var padre = Ext.getCmp('treeTotales_".$reporte."').getRootNode();
						}
						padre.appendChild(node);
					}
				}
			}
			,tbar:['Filter:', {
				 xtype:'trigger'
				,triggerClass:'x-form-clear-trigger'
				,onTriggerClick:function() {
					this.setValue('');
					filterDisponibles_".$reporte.".clear();
				}
				,id:'filter_".$reporte."'
				,enableKeyEvents:true
				,listeners:{
					keyup:{buffer:150, fn:function(field, e) {						
						
						if(Ext.EventObject.ESC == e.getKey()) {
							field.onTriggerClick();
						}
						else {
							var val = field.getRawValue();
							
							var re = new RegExp('.*' + val + '.*', 'i');
							filterDisponibles_".$reporte.".clear();
							
							filterDisponibles_".$reporte.".filter(re, 'text');
						}
					}}
				}
			}]
		});
		
		filterDisponibles_".$reporte." = new Ext.ux.tree.TreeFilterX(treeDisponibles_".$reporte.");
		
		var treeFilas_".$reporte." = new Tree.TreePanel({
			id:'treeFilas_".$reporte."'
			,animate:true
			,autoScroll:true
			,rootVisible: false
			,title:'"._FILA."'
			,root: new Ext.tree.TreeNode({
				type: 'root'
				,draggable: false
				,expanded: true
				,iconCls: 'silk-folder'
			})
			,containerScroll:true
			,height:130
			,enableDD:true
			,ddGroup:'tree".$reporte."'
			,dropConfig: {allowContainerDrop:true,ddGroup:'tree".$reporte."'}
			,listeners: {
				'dblclick':function(node, e){
					var padre = Ext.getCmp('treeDisponibles_".$reporte."').getRootNode();
					padre.appendChild(node);
				}
				,'append':function(tree,parent,node,index){
					node.getUI().toggleCheck(false)
				}
				,'checkchange': function(node, checked){
					if(checked){
						var padre = Ext.getCmp('treeDisponibles_".$reporte."').getRootNode();
						padre.appendChild(node);
					}
				}
				,'render': {
					fn: function(tree){
					";
					if(!empty($arr_filas)){
						foreach($arr_filas as $data){
							$return .= "	var node = Ext.getCmp('treeDisponibles_".$reporte."').getNodeById(\"".$data."\");
											if(node){
												tree.getRootNode().appendChild(node);
											}
									";
						}
					}
					
					$return .= "
					}
				}
			}
		});
		
		var treeColumnas_".$reporte." = new Tree.TreePanel({
			id:'treeColumnas_".$reporte."'
			,animate:true
			,autoScroll:true
			,rootVisible:false
			,title:'"._COLUMNA."'
			,root: new Ext.tree.TreeNode({
				 type: 'root'
				,draggable: false
				,expanded: true
				,iconCls: 'silk-folder'
			})
			,containerScroll:true
			,height:70
			,enableDD:true
			,ddGroup:'tree".$reporte."'
			,dropConfig: {allowContainerDrop:true,ddGroup:'tree".$reporte."'}
			,listeners: {
				'dblclick':function(node, e){
					var padre = Ext.getCmp('treeDisponibles_".$reporte."').getRootNode();
					padre.appendChild(node);
				}
				,'append':function(tree,parent,node,index){
					node.getUI().toggleCheck(false)
				}
				,'checkchange': function(node, checked){
					if(checked){
						var padre = Ext.getCmp('treeDisponibles_".$reporte."').getRootNode();
						padre.appendChild(node);
					}
				}
				,'render': {
					fn: function(tree){
					";
					if(!empty($arr_columnas)){
						foreach($arr_columnas as $data){
							$return .= "	var node = Ext.getCmp('treeDisponibles_".$reporte."').getNodeById(\"".$data."\");
											if(node){
												tree.getRootNode().appendChild(node);
											}
									";
						}
					}
					
					$return .= "
					}
				}
				,beforeappend:function(tree,parent,node){
					var padre = Ext.getCmp('treeDisponibles_".$reporte."').getRootNode();
					this.getRootNode().eachChild(function(node){
						padre.appendChild(node);
					});
				}
			}
		});
		var treeTotales_".$reporte." = new Tree.TreePanel({
			id:'treeTotales_".$reporte."'
			,animate:true
			,autoScroll:true
			,rootVisible: false
			,title:'"._TOTALES."'
			,root: new Ext.tree.TreeNode({
				 type: 'root'
				,draggable: false
				,expanded: true
				,iconCls: 'silk-folder'
			})
			,containerScroll: true
			,height:130
			,enableDD:true
			,ddGroup:'tree".$reporte."'
			,dropConfig: {allowContainerDrop:true,ddGroup:'tree".$reporte."'}
			,listeners: {
				'dblclick':function(node, e){
					var padre = Ext.getCmp('treeDisponibles_".$reporte."').getRootNode();
					padre.appendChild(node);
				}
				,'append':function(tree,parent,node,index){
					node.getUI().toggleCheck(false)
				}
				,'checkchange': function(node, checked){
					if(checked){
						var padre = Ext.getCmp('treeDisponibles_".$reporte."').getRootNode();
						padre.appendChild(node);
					}
				}
				,'render': {
					fn: function(tree){
					";
					if(!empty($arr_totales)){
						foreach($arr_totales as $data){
							$return .= "	var node = Ext.getCmp('treeDisponibles_".$reporte."').getNodeById(\"".$data."\");
											if(node){
												tree.getRootNode().appendChild(node);
											}
									";
						}
					}
					
					$return .= "
					}
				}
			}
		});
		
		var mostrar = [
			[1	,'1'],
			[2	,'2'],
			[3	,'3'],
			[4	,'4'],
			[5	,'5'],
			[6	,'6'],
			[7	,'7'],
			[8	,'8'],
			[9	,'9'],
			[10	,'10'],
			[11	,'11'],
			[12	,'12'],
			[13	,'13'],
			[14	,'14'],
			[15	,'15'],
			[16	,'16'],
			[17	,'17'],
			[18	,'18'],
			[19	,'19'],
			[20	,'20'],
			[40	,'40'],
			[50	,'50'],
			[".MAXREGEXCEL."	,'"._TODOS."']
		];
				
		var storeMostrar_". $reporte." = new Ext.data.ArrayStore({
			fields:['mostrar','numero']
			,data:mostrar
		});
		
		var tabs_".$reporte." = new Ext.TabPanel({
			activeTab:0
			,id:'tabs_".$reporte."'
			,autoDestroy:true
			,listeners:{
				'beforedestroy':{
					fn:function(tabpanel){
						Ext.state.Manager.clear(grid_".$reporte.".getItemId());
					}
				}
			}
			,items:[{
				title:'"._ACUMULADOHERRAMIENTA."'
				,layout:'border'
				,height:altura
				,items:[{
					id:'pivotTable_".$reporte."'
					,layout:'fit'
					,autoScroll:true
					,anchor:'100%'
					,region:'center'
					,items:[{
						autoWidth:true
						,autoHeight:true
						,autoScroll:true
						,layout:'fit'
						,anchor:'90%'
						,html:'<div class=\"row-fluid padtop10\">																																				' +
							'	<div class=\"span6\">																																							' +
							'		<div class=\"timeline-wrapper\">																																			' +
							'			<div class=\"timeline-item timeline-start\">                                                                                                                            ' +
							'				<div class=\"panel bg-header\">                                                                                                                                     ' +
							'					<div class=\"panel-body text-center\">                                                                                                                          ' +
							'						<strong class=\"font-14\">                                                                                                                                  ' +
							'							".$nombre_rep."                                                                                                                                         ' +
							'						</strong>                                                                                                                                                   ' +
							'					</div>                                                                                                                                                          ' +
							'				</div><!-- /panel -->                                                                                                                                               ' +
							'			</div><!-- /timeline-item -->                                                                                                                                           ' +
							'			<div class=\"timeline-item\">                                                                                                                                           ' +
							'				<div class=\"timeline-info\">                                                                                                                                       ' +
							'					<div class=\"timeline-icon bg-warning\">                                                                                                                           ' +
							'						<i class=\"fa fa-folder\"></i>                                                                                                                              ' +
							'					</div>                                                                                                                                                          ' +
							'				</div>                                                                                                                                                              ' +
							'				<div class=\"panel panel-default timeline-panel\">                                                                                                                  ' +
							'					<div class=\"panel-body\">                                                                                                                                      ' +
							'						<p>                                                                                                                                                         ' +
							'						".$descripcion."																																			' +
							'						</p>                                                                                                                                                        ' +
							'					</div>                                                                                                                                                          ' +
							'				</div><!-- /panel -->                                                                                                                                               ' +
							'			</div><!-- /timeline-item -->                                                                                                                                           ' +
							
							'			<div class=\"timeline-item\">                                                                                                                                           ' +
							'				<div class=\"timeline-info\">                                                                                                                                       ' +
							'					<div class=\"timeline-icon bg-header\">                                                                                                                        ' +
							'						<i class=\"fa fa-question\"></i>                                                                                                                         ' +
							'					</div>                                                                                                                                                          ' +
							'				</div>                                                                                                                                                              ' +
							'				<div class=\"panel panel-default timeline-panel\">                                                                                                                  ' +
							'					<div class=\"panel-body\">                                                                                                                                      ' +
							'						<p>                                                                                                                                                         ' +
							'							"._AYUDATABLADINAMICA6."																																' +
							'						</p>                                                                                                                                                        ' +
							'					</div>                                                                                                                                                          ' +
							'				</div><!-- /panel -->                                                                                                                                               ' +
							'			</div><!-- /timeline-item -->                                                                                                                                           ' +
							
							
							'			<div class=\"timeline-item clearfix\"><div class=\"timeline-info\"><div class=\"timeline-icon bg-grey\"><i class=\"fa fa-angle-down\"></i></div></div></div>			' +
							'		</div>                                                                                                                                                                      ' +
							'	</div>																																											' +
							'	<div class=\"span5\">																																							' +
							'		<div class=\"timeline-wrapper\">																																			' +
							'			<div class=\"timeline-item timeline-start\">                                                                                                                            ' +
							'				<div class=\"panel bg-header\">                                                                                                                                     ' +
							'					<div class=\"panel-body text-center\">                                                                                                                          ' +
							'						<strong class=\"font-14\">                                                                                                                                  ' +
							'							"._ACUMULADOHERRAMIENTA."                                                                                                                               ' +
							'						</strong>                                                                                                                                                   ' +
							'					</div>                                                                                                                                                          ' +
							'				</div><!-- /panel -->                                                                                                                                               ' +
							'			</div><!-- /timeline-item -->                                                                                                                                           ' +
							'			<div class=\"timeline-item\">                                                                                                                                           ' +
							'				<div class=\"timeline-info\">                                                                                                                                       ' +
							'					<div class=\"timeline-icon bg-warning\">                                                                                                                           ' +
							'						<i class=\"fa fa-folder\"></i>                                                                                                                              ' +
							'					</div>                                                                                                                                                          ' +
							'				</div>                                                                                                                                                              ' +
							'				<div class=\"panel panel-default timeline-panel\">                                                                                                                  ' +
							'					<div class=\"panel-body\">                                                                                                                                      ' +
							'						<p>                                                                                                                                                         ' +
							'							"._AYUDATABLADINAMICA1."																																' +
							'						</p>                                                                                                                                                        ' +
							'					</div>                                                                                                                                                          ' +
							'				</div><!-- /panel -->                                                                                                                                               ' +
							'			</div><!-- /timeline-item -->                                                                                                                                           ' +
							'			<div class=\"timeline-item\">                                                                                                                                           ' +
							'				<div class=\"timeline-info\">                                                                                                                                       ' +
							'					<div class=\"timeline-icon bg-warning\">                                                                                                                           ' +
							'						<i class=\"fa fa-eye\"></i>                                                                                                                        ' +
							'					</div>                                                                                                                                                          ' +
							'				</div>                                                                                                                                                              ' +
							'				<div class=\"panel panel-default timeline-panel\">                                                                                                                  ' +
							'					<div class=\"panel-body\">                                                                                                                                      ' +
							'						<p>"._AYUDATABLADINAMICA3."</p>                                                       																		' +
							'					</div>                                                                                                                                                          ' +
							'				</div><!-- /panel -->                                                                                                                                               ' +
							'			</div><!-- /timeline-item -->                                                                                                                                           ' +
							'			<div class=\"timeline-item\">                                                                                                                                  			' +
							'				<div class=\"timeline-info\">                                                                                                                                       ' +
							'					<div class=\"timeline-icon bg-warning\">                                                                                                                        	' +
							'						<i class=\"fa fa-floppy-o\"></i>                                                                                                                            ' +
							'					</div>                                                                                                                                                          ' +
							'				</div>                                                                                                                                                              ' +
							'				<div class=\"panel panel-default timeline-panel\">                                                                                                                  ' +
							'					<div class=\"panel-body\">                                                                                                                                      ' +
							'						<p>"._AYUDATABLADINAMICA5."</p>                                                                                                              				' +
							'					</div>                                                                                                                                                          ' +
							'				</div><!-- /panel -->                                                                                                                                               ' +
							'			</div><!-- /timeline-item -->                                                                                                                                           ' +
							'			<div class=\"timeline-item\">                                                                                                                                  			' +
							'				<div class=\"timeline-info\">                                                                                                                                       ' +
							'					<div class=\"timeline-icon bg-success\">                                                                                                                        ' +
							'						<i class=\"fa fa-search\"></i>                                                                                                                              ' +
							'					</div>                                                                                                                                                          ' +
							'				</div>                                                                                                                                                              ' +
							'				<div class=\"panel panel-default timeline-panel\">                                                                                                                  ' +
							'					<div class=\"panel-body\">                                                                                                                                      ' +
							'						<p>"._AYUDATABLADINAMICA4."</p>                                                                                                              				' +
							'					</div>                                                                                                                                                          ' +
							'				</div><!-- /panel -->                                                                                                                                               ' +
							'			</div><!-- /timeline-item -->                                                                                                                                           ' +
							'			<div class=\"timeline-item\">                                                                                                                                           ' +
							'				<div class=\"timeline-info\">                                                                                                                                       ' +
							'					<div class=\"timeline-icon bg-danger\">                                                                                                                        ' +
							'						<i class=\"fa fa-exclamation\"></i>                                                                                                                         ' +
							'					</div>                                                                                                                                                          ' +
							'				</div>                                                                                                                                                              ' +
							'				<div class=\"panel panel-default timeline-panel\">                                                                                                                  ' +
							'					<div class=\"panel-heading\">                                                                                                                                   ' +
							'						<span class=\"label label-danger m-left-xs\">"._IMPORTANTE."</span>                                                                                         ' +
							'					</div>                                                                                                                                                          ' +
							'                	<div class=\"panel-body\">                                                                                                                                      ' +
							'						<p>                                                                                                                                                         ' +
							'							"._AYUDATABLADINAMICA2."																											                    ' +
							'						</p>                                                                                                                                                        ' +
							'					</div>                                                                                                                                                          ' +
							'				</div><!-- /panel -->                                                                                                                                               ' +
							'			</div><!-- /timeline-item -->                                                                                                                                           ' +
							'			<div class=\"timeline-item clearfix\"><div class=\"timeline-info\"><div class=\"timeline-icon bg-grey\"><i class=\"fa fa-angle-down\"></i></div></div></div>			' +
							'		</div>                                                                                                                                                                      ' +
							'	</div>																																											' +
							'</div>'
					}]
				},{
					region:'east'
					,collapsible:true
					,split:true
					,width:300
					,layout:'accordion'
					,items:[{
						layout:'column'
						,title:'"._ACUMULADOHERRAMIENTA."'
						,autoWidth:true
						,autoScroll:true
						,defaults:{columnWidth:.5,bodyStyle:'padding:2px'}
						,frame:true
						,items:[{
							columnWidth:1
							,items:[treeDisponibles_".$reporte."]
						},{
							items:[treeFilas_".$reporte."]
						},{
							items:[treeTotales_".$reporte."]
						},{
							columnWidth:1
							,items:[treeColumnas_".$reporte."]
						},{
							columnWidth:1
							,layout:'form'
							,labelAlign:'top'
							,bodyStyle:'padding:5px'
							,defaults:{anchor: '-20'}
							,items:[{								
								xtype:'combo'
								,fieldLabel:'". _MOSTRAR."'
								,store:storeMostrar_". $reporte."
								,displayField:'numero'
								,valueField:'mostrar'
								,mode:'local'
								,flex:1
								,forceSelection:true
								,triggerAction:'all'
								,id:'mostrar_".$reporte."'
								,hiddenName:'mostrar'
								,value:10
								,lastQuery:''
							}]
						";
				if($acumulado == "1"){
					$return .= "
						},{
							columnWidth:1
							,layout:'form'
							,bodyStyle:'padding:5px'
							,defaults:{anchor: '-20'}
							,items:[{
								xtype:'radiogroup'
								,fieldLabel:'"._MULTIANIO."?'
								,id:'multiano_".$reporte."'
								,allowBlank:false
								,items:[
									{boxLabel: '"._SI."', inputValue:1, name:'multiano'},
									{boxLabel: '"._NO."', inputValue:0, checked:true, name:'multiano'}
								]
							}]
					
					";
				}
$return .= "
						}]
						,bbar:[{
							text:'"._SAVE."'
							,iconCls:'silk-page-save'
							,handler:function(){
								guardarCfgReporte();
							}
						},'->',{
							text:'"._BUSCAR."'
							,iconCls:'silk-zoom'
							,handler:function(){
								pivotTable();
							}
						}]
					}]
				}]
			";
			if($acumulado == "0"){
				$return .= "
			},{
				title:'"._DETALLADO."'
				,items:[grid_".$reporte."]
				,autoScroll:true
				,listeners:{
					activate:function(panel){
						var parametros = store_". $reporte.".baseParams;
						if(parametros['filtros_adicionales'] != Ext.getCmp('comboFiltrosAdi_". $reporte."').getValue()){
							store_". $reporte.".load();
						}
					}
				}
				,layout:'fit'
				,height:altura
				";
			}
			$return .= "
			}]
		});
		
		var quitarFiltros = true;
		var filtrosAdiStore = new Ext.data.SimpleStore({
			fields:['valor','texto']
			,data:[]
			,sortInfo: {field:'valor', direction: 'ASC'}
		});
		var comboFiltrosAdi = new Ext.ux.form.SuperBoxSelect({
			allowAddNewData:true
			,id:'comboFiltrosAdi_". $reporte."'
			,fieldLabel:''
			,readOnly:true
			,resizable:true
			,store:filtrosAdiStore
			,stackItems:false
			,mode:'local'
			,valueField:'valor'
			,displayField:'texto'
			,allowQueryAll:false
			,listeners:{
				removeitem: function(a,b,c,d){
					tabs_".$reporte.".setActiveTab(0);
					if(quitarFiltros){
						pivotTable();
					}
				}
				,clear:function(a,b,c,d){
					quitarFiltros = false;
					pivotTable();
				}
			}
		});
		
		
		var layout_".$reporte." = new Ext.Panel({
			 xtype:'panel'
			,layout:'border'
			,border:false
			,autoDestroy:true
			,items:[{
				xtype:'panel'
				,region:'north'
				,autoHeight:true
				,region:'north'
				,layout:'column'
				,defaults:{
					columnWidth:1
				}
				,items:[comboFiltrosAdi]
			},{
				region:'center'
				,layout:'fit'
				,items:[tabs_".$reporte."]
			}]
		});
		
		
		
		/****************************************************************funciones*****************************************************************************/
		function pivotTable(){
			var ajaxReader = new Ext.data.JsonReader();
			
			var store = new Ext.data.GroupingStore({
				proxy:new Ext.data.HttpProxy({
					url:'proceso/declaraciones/'
					,timeout: 1000000000
					,method: 'POST'
				})
				,reader: new Ext.data.JsonReader({
					root: 'datos'
					,totalProperty: 'total'
				}
				,".json_encode($campos_store)."
				)
				,remoteSort:true
				,multiSort:true
				,sortInfo:{field:'id', direction: 'DESC'}
				,baseParams:{accion:'pivot', reporte:".$reporte.", periodo:'".$periodo."'}
			});
			var parametros = store.baseParams;
			delete(parametros['filas']);
			delete(parametros['columnas']);
			delete(parametros['totales']);
			delete(parametros['mostrar']);
			delete(parametros['multiano']);
			delete(parametros['filtros_adicionales']);
			
			var filasTree = Ext.getCmp('treeFilas_".$reporte."').getRootNode();
			var columnasTree = Ext.getCmp('treeColumnas_".$reporte."').getRootNode();
			var totalesTree = Ext.getCmp('treeTotales_".$reporte."').getRootNode();
			var mostrar = (Ext.getCmp('mostrar_".$reporte."').getValue() == '')?10:Ext.getCmp('mostrar_".$reporte."').getValue();
			var filtros_adicionales = Ext.getCmp('comboFiltrosAdi_". $reporte."').getValue();
			//console.log(filtros_adicionales);
";
	if($acumulado == "1"){
		$return .= "var multiano = Ext.getCmp('multiano_".$reporte."').getValue().getGroupValue()";
		
	}
$return .= "		
			var filas = [];
			var columnas = [];
			var totales = [];
			filasTree.eachChild(function(node){
				var newObj = { id: node.attributes.id, alias: node.attributes.alias, key: node.attributes.key };
				filas.push(newObj);
			});
			columnasTree.eachChild(function(node){
				var newObj = { id: node.attributes.id, alias: node.attributes.alias };
				columnas.push(newObj);
			});
			totalesTree.eachChild(function(node){
				var newObj = { id: node.attributes.id, alias: node.attributes.alias };
				totales.push(newObj);
			});
			if(filas.length == 0){
				return;
			}
			
			store.baseParams['filas'] 	 = Ext.encode(filas);
			store.baseParams['columnas'] = Ext.encode(columnas);
			store.baseParams['totales']  = Ext.encode(totales);
			store.baseParams['limit']    = cantidad_reg;
";
	if($acumulado == "1"){
		$return .= "store.baseParams['multiano'] = multiano;";
	}
$return .= "
			
			store.baseParams['mostrar']  = mostrar;
			store.baseParams['filtros_adicionales']  = filtros_adicionales;
			
			var nuevo = true;
			store.load();
			var sorters = [];
			store.on('beforeload', function(st, options){
				var cm = Ext.getCmp('pivotGrid_".$reporte."').getColumnModel();
				if(options.params.sort){
					var columna = 1;					
					var columns = cm.getColumnsBy(function(c){						
						if(c.dataIndex == options.params.sort){
							columna = c.index;
						}
					});
					sorters[0] = {index:columna,dir:options.params.dir};
					st.baseParams['sorters'] = Ext.encode(sorters);
				}
			});
			store.on('load', function(st,records, options,d){
				if(nuevo){
					var cm = [];
					var series = st.reader.meta.series;
					sorters = [];
					Ext.each(st.fields.items,function(data){
						//console.log(data);
						var hidden = false;
						if(data.hidden == 'true'){
							hidden = true;
						}
						if(data.name !== 'id' && data.name !== 'porc_id' && data.name.substring(0,9) != 'filtroid_'){
							if(data.renderer == 'numberFormat'){
								cm.push({header:data.header,dataIndex:data.name,sortable:true,align:data.align,renderer:numberFormat, hidden:hidden, index:data.index});
							}
							else if(data.renderer == 'link'){
								cm.push({header:data.header,dataIndex:data.name,sortable:true,align:data.align,renderer:link, hidden:hidden, index:data.index, filtro:data.filtro});
							}
							else{
								cm.push({header:data.header,dataIndex:data.name,sortable:true,align:data.align, index:data.index, hidden:hidden});
							}
						}
					});
					var pivotColModel_". $reporte." = new Ext.grid.ColumnModel({
						columns:cm
						,defaults: {
							sortable: false,
							width: 150
						}
					});
					
					var pivotGrid_".$reporte." = new Ext.grid.GridPanel({
						store:st
						,id:'pivotGrid_".$reporte."'
						,stateful:true
						,cm:pivotColModel_". $reporte."
						,border:false
						,stripeRows:true
						,view: new Ext.ux.grid.BufferView({
							scrollOffset:2
							,autoScroll:true							
							,getRowClass:function(record, index){
								var c = record.get('id');
								if (!c){
									return 'x-grid3-summary-row';
								}
							}
						})
						,sm:new Ext.grid.RowSelectionModel({singleSelect:false})
						,height:(altura - 30)
						,autoWidth:true
						,enableColumnMove:false
						,margins:'10 15 5 0'
						,layout:'fit'
						,tbar:[{
							xtype:'buttongroup'
							,title: '"._TIP0GRAFICA."'
							,items: [{
								text: '"._BARRAS."',
								iconCls: 'sicex_bar',
								scale: 'medium',
								handler: function(){
									graficar(st,'".BARRAS."');
								}
							},{
								text: '"._COLUMNAS."',
								iconCls: 'sicex_column',
								scale: 'medium',
								handler: function(){
									graficar(st,'".COLUMNAS."');
								}
							},{
								text: '"._LINEAL."',
								iconCls: 'sicex_line',
								scale: 'medium',
								handler: function(){
									graficar(st,'".LINEAL."');
								}
							},{
								text: '"._AREA."',
								iconCls: 'sicex_area',
								scale: 'medium',
								handler: function(){
									graficar(st,'".AREA."');
								}
							},{
								text: '"._PIE."',
								iconCls: 'sicex_pie',
								scale: 'medium',
								handler: function(){
									graficar(st,'".PIE."');
								}
							}]
						}]
						,monitorResize:true
						,plugins:[new Ext.ux.grid.Excel({position:'top', title:'"._SAVEAS."'})]
						,listeners: {
							cellclick: function(grid, rowIndex, columnIndex, e) {
								if (e.getTarget('a.x-link', this.body)) {
									e.preventDefault();
									var record = grid.getStore().getAt(rowIndex);
									var columnModel = grid.getColumnModel();
									var idColumn = columnModel.getColumnId(columnIndex);
									var column = columnModel.getColumnById(idColumn);
									var valor = record.get( 'filtroid_' + column.dataIndex);
									var texto = column.header + ': ' + record.get(column.dataIndex);
									var filtro =  column.filtro;
									quitarFiltros = true;
									Ext.getCmp('comboFiltrosAdi_". $reporte."').addItem({'valor':filtro + ':' + valor, 'texto':texto});
									
									pivotTable();
								}
							}
						}
					});
					
					Ext.state.Manager.clear(pivotGrid_".$reporte.".getItemId());
					
					var lp = Ext.getCmp('pivotTable_".$reporte."');
					lp.removeAll(true);
					lp.add(pivotGrid_".$reporte.");
					lp.doLayout();
					nuevo = false;
				}
			});
		}
		
		function graficar(store, tipo){
			FusionCharts.setCurrentRenderer('javascript');
			if(FusionCharts('myChartId_".$reporte."')){
				FusionCharts('myChartId_".$reporte."').dispose();
			}
			var myChart = new FusionCharts(tipo, 'myChartId_".$reporte."', '100%', '100%', '0', '1');
			myChart.setTransparent(true);
			var url = store.proxy.url;
			var parametros = store.baseParams;
			parametros['grafico'] = true;
			parametros['tipo_grafico'] = tipo;
			
			var cm = Ext.getCmp('pivotGrid_".$reporte."').colModel;
			var columnas=new Object();
			Ext.iterate(cm.columns, function(key, value) {
				if((key.hidden == undefined || key.hidden == false) && key.header != ''){
					columnas[key.dataIndex] = key.header;
				}
			}, this);
			
			parametros['fields'] = Ext.encode(columnas);
			Ext.Ajax.request({
				url:url
				,method:'POST'
				,scope:this
				,timeout: 100000000
				,params: parametros
				,success: function(response){
					delete(parametros['grafico']);
					delete(parametros['tipo_grafico']);
					results = Ext.decode(response.responseText);
					myChart.setJSONData(results);
					var graphWin_".$reporte.";
					if(!graphWin_".$reporte."){
						graphWin_".$reporte." = new Ext.Window({
							title:'Resizeable Graph Window'
							,id:'graphWin_".$reporte."'
							,width:800
							,height:450
							,maximizable:true
							,modal:true
							,layout:'fit'
							,closeAction:'close'
							,plain:true
							,html:'<div id=\"chart_panel_".$reporte."\"></div>'
							,items:[{
								xtype:'panel'
								,id:'chart_panel_".$reporte."'
								,plain:true
								,listeners: {
									'render':{
										fn: function(panel){
											myChart.render('chart_panel_".$reporte."');
										}
									}
								}
							}]
							,buttons:[{
								iconCls:'icon-download'
								,text:'"._SAVE."'
								,menu:[{
									text:'Save as JPEG Image'
									,handler:function(){
										ExportMyChart(myChart, 'JPG');
									}
								},{
									text:'Save as PNG Image'
									,handler:function(){
										ExportMyChart(myChart, 'PNG');
									}
								},{
									text:'Save as PDF'
									,handler:function(){
										ExportMyChart(myChart, 'PDF');
									}
								}]
							},{
								text:'Close',
								handler:function(){
									graphWin_".$reporte.".close();
								}
							}]
						});
						graphWin_".$reporte.".addListener('resize', function (anda, w, h) {
							myChart.resizeTo(w -16, h - 71);
						});
					}
					graphWin_".$reporte.".show();
				}
				,failure: function(response){
					results = Ext.decode(response.responseText);
					if (results.msg) {
						Ext.Msg.alert('Infomation',results.msg);
					}
				}
			});
		}
		function numberFormat(value, p, record){
			if(!isNaN(parseFloat(value)) && isFinite(value)){
				return Ext.util.Format.number(value,'0,0.00');
			}
			else{
				return value;
			}
		}
		function link(value, p, record){
			if(value && !isNaN(record.data.id)){
				return '<a href=\"#\" class=\"x-link\">' + value + '</a>';
			}
			else{
				return value;
			}
		}
		function ExportMyChart(chartObject, format){
			if(chartObject.hasRendered()){
				chartObject.exportChart({exportAtClient:'1', exportFormat:format});
			}
		}
		function guardarCfgReporte(){
			var filas = [];
			var columnas = [];
			var totales = [];
			var filasTree = Ext.getCmp('treeFilas_".$reporte."').getRootNode();
			var columnasTree = Ext.getCmp('treeColumnas_".$reporte."').getRootNode();
			var totalesTree = Ext.getCmp('treeTotales_".$reporte."').getRootNode();
			
			filasTree.eachChild(function(node){
				var newObj = { id: node.attributes.id };
				filas.push(newObj);
			});
			columnasTree.eachChild(function(node){
				var newObj = { id: node.attributes.id };
				columnas.push(newObj);
			});
			totalesTree.eachChild(function(node){
				var newObj = { id: node.attributes.id };
				totales.push(newObj);
			});
			if(filas.length == 0 && columnas.length == 0 && totales.length == 0){
				return;
			}
			
			var parametros = new Object();
			parametros['reporte']   = '".$reporte."';
			parametros['filas']     = Ext.encode(filas);
			parametros['columnas']	= Ext.encode(columnas);
			parametros['totales']	= Ext.encode(totales);
			parametros['accion']	= 'actCfgAcumulado';
			
			
			Ext.Ajax.request({
				url:'proceso/reportes/'
				,method:'POST'
				,scope:this
				,timeout: 100000
				,params: parametros
				,success: function(response){
";
	if($acumulado == "1"){
		$return .= "Ext.getCmp('".$tree."').cargar('".$reporte."');";
	}
$return .= "
					
				}
				,failure: function(response){
					results = Ext.decode(response.responseText);
					if (results.msg) {
						Ext.Msg.alert('Infomation',results.msg);
					}
				}
			});
			
			
		}
		
		
		return layout_".$reporte.";		
		
	})()
";

print ($return);