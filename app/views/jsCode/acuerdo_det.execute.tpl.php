<?php
$partner        = (empty($mercado_nombre)) ? $pais : $mercado_nombre ;
$htmlCountryies = '';
foreach ($countryData as $key => $row) {
	$htmlCountryies .= '<li class="list-group-item"><span class="badge">'.($key + 1).'</span>'.$row['pais'].'</li>';
}

$htmlAgreement = '
<div class="container">
	<div class="row">
		<div class="col-lg-9 col-md-7 col-sm-6">
			<h1 class="page-header">'.$acuerdo_nombre.'</h1>
			<div class="dashboard-block">
				<p>'.nl2br($acuerdo_descripcion).'</p>
			</div>
			<hr>
			<div class="col-md-4 col-xs-12">
				<div class="dashboard-block">
					<div class="rotate">
						<i class="fa fa-calendar"></i>
					</div>
					<h5 class="bold">'.Lang::get('acuerdo.columns_title.acuerdo_fvigente').'</h5>
					<p>'.$acuerdo_fvigente_title.'</p>
				</div>
			</div>
			<div class="col-md-4 col-xs-12">
				<div class="dashboard-block">
					<div class="rotate">
						<i class="fa fa-money"></i>
					</div>
					<h5 class="bold">'.Lang::get('acuerdo.columns_title.acuerdo_intercambio').'</h5>
					<p>'.$acuerdo_intercambio_title.'</p>
				</div>
			</div>
			<div class="col-md-4 col-xs-12">
				<div class="dashboard-block">
					<div class="rotate">
						<i class="fa fa-globe"></i>
					</div>
					<h5 class="bold">'.Lang::get('acuerdo.partner_title').'</h5>
					<p>'.$partner.'</p>
				</div>
			</div>

			<div class="clearfix"></div>
			<hr>
		  	<p class="lead">'.Lang::get('acuerdo.countries_agreement').'</p>
		  	<ul class="list-group ">
				'.$htmlCountryies.'
			</ul>
		</div>
	</div>
</div>
';
$htmlAgreement = Inflector::compress($htmlAgreement);
?>


/*<script>*/
(function(){
	Ext.form.Field.prototype.msgTarget = 'side';
	Ext.ns('Acuerdo');
	var module = '<?= $module; ?>';

	var storeAcuerdo_det = new Ext.data.JsonStore({
		url:'acuerdo_det/execute'
		,root:'data'
		,id:module+'storeContingente'
		,sortInfo:{field:'acuerdo_det_id',direction:'ASC'}
		,totalProperty:'total'
		,baseParams: {
			id: '<?= $id; ?>'
			,module: module
			,acuerdo_id: '<?= $acuerdo_id; ?>'
		}
		,id:module+'storeAcuerdo_det'
		,fields:[
			{name:'acuerdo_det_id', type:'float'},
			{name:'acuerdo_det_productos', type:'string'},
			{name:'acuerdo_det_productos_desc', type:'string'},
			{name:'pais', type:'string'},
			{name:'contingente_det_peso_neto', type:'float'},
			{name:'salvaguardia_peso_neto', type:'float'}
		]
	});

	var colModelAcuerdo_det = new Ext.grid.ColumnModel({
		columns:[
			{header:'<?= Lang::get('acuerdo_det.columns_title.acuerdo_det_productos_desc'); ?>', dataIndex:'acuerdo_det_productos_desc', align:'left'},
			{header:'<?= Lang::get('acuerdo.partner_title'); ?>', dataIndex:'pais', align:'left'},
			{header:'<?= Lang::get('contingente_det.peso_contingente'); ?>', dataIndex:'contingente_det_peso_neto' ,'renderer':numberFormat , align:'right'},
			{header:'<?= Lang::get('contingente_det.peso_contingente'); ?>', dataIndex:'salvaguardia_peso_neto' ,'renderer':numberFormat , align:'right'},


			/*{header:'<?= Lang::get('contingente_det.peso_ejecutado'); ?>', dataIndex:'executedWeight' ,'renderer':numberFormat , align:'right'},
			{header:'% <?= Lang::get('contingente_det.valor_ejecutado'); ?>', dataIndex:'rate' ,'renderer':rateFormat , align:'right'}*/
		]
	});

	var gridAcuerdo_det = new Ext.grid.GridPanel({
		border:true
		,monitorResize:true
		,store:storeAcuerdo_det
		,colModel:colModelAcuerdo_det
		,stateful:true
		,columnLines:true
		,stripeRows:true
		,viewConfig: {
			forceFit:true
		}
		,enableColumnMove:false
		,id:module+'gridAcuerdo_det'
		,title: '<?= Lang::get('contingente.table_name'); ?> - ' + Ext.ux.lang.reports.detail
		,sm:new Ext.grid.RowSelectionModel({singleSelect:true})
		,bbar: ['->']
		,iconCls:'silk-grid'
		,plugins:[new Ext.ux.grid.Excel()]
		,layout:'fit'
		,autoHeight:true
		,autoWidth:true
		,margins:'10 15 5 0'
		,listeners:{
			render: {
				fn: function(grid){
					storeAcuerdo_det.load();
				}
			}
		}
	});

	
	var root = new Ext.tree.AsyncTreeNode({
		text: '<?= Lang::get('acuerdo_det.table_name'); ?>'
		,type: 'root'
		,draggable: false
		,id: module + 'root'
		,expanded: true
		,uiProvider: false
		,iconCls: 'silk-folder'
		,leaf: false
	});

	Acuerdo.tree = function() {
		Acuerdo.tree.superclass.constructor.call(this, {
			id: module + 'TreeAcuerdo'
			,header: false
			,contextMenu:false
			,minSize: 230
			,maxSize: 500
			,region: 'west'
			,autoScroll: true
			,animate: true
			,containerScroll: true
			,border: false
			,enableDD: false
			,rootVisible: true
			,maskDisabled: false
			,useArrows: true
			,collapsible: true
			,collapseMode:'mini'
			,lines: true
			,split: true
			,width:	200
			,editable:false
	        ,root: root
            ,loader: {
            	url:'acuerdo_det/tree'
            	,baseParams:{
            		id: '<?= $id; ?>'
            		,module: module
            		,acuerdo_id: '<?= $acuerdo_id; ?>'
            	}
            }
			,tbar:[
				Ext.ux.lang.folder.filter
			,{
				xtype:'trigger'
				,triggerClass:'x-form-clear-trigger'
				,onTriggerClick:function() {
					this.setValue('');
					AcuerdoTree.filter.clear();
				}
				,id:module + 'filter'
				,enableKeyEvents:true
				,listeners:{
					keyup:{ buffer:150, fn:function(field, e) {
						if(Ext.EventObject.ESC == e.getKey()) {
							field.onTriggerClick();
						}
						else {
							var val = this.getRawValue();
							var re = new RegExp('.*' + val + '.*', 'i');
							AcuerdoTree.filter.clear();
							AcuerdoTree.filter.filter(re, 'text');
						}
					}}
				}
			}]
	       ,listeners: {
				'beforecollapsenode': function(node, deep, anim){
					initialPanel();
				}
				,'click': function(node, e){
					if (node.leaf) {
						AcuerdoTree.consultar(node.id)
					} else {
						initialPanel();
					}
				}
				,'contextmenu': function(node, e){
					return false;
				}
			}
		});
	}
	Ext.extend(Acuerdo.tree, Ext.ux.tree.RemoteTreePanel, {
		consultar:function(node_id){
			var node = this.getNodeById(node_id);
			Ext.getCmp('tab-' + module).purgeListeners();
			//console.log(node);
			if(node){
				var dataViewer = new Ext.Panel({
					autoScroll: false
					,layout: 'fit'
					,autoShow: true
					,frame:false
					,border: false
					,autoDestroy:true
					,plugins: new Ext.ux.Plugin.RemoteComponent({
						url: 'contingente/jscodeExecute'
						,params:{
							id: '<?= $id; ?>'
							,acuerdo_id: '<?= $acuerdo_id; ?>'
							,acuerdo_det_id: node_id
							,tree: module + 'TreeAcuerdo'
							,module: 'execute_' + module
							,panel: 'tab-' + module
						}
						,disableCaching:false
						,method:'POST'
					})
					,items: []
				});
				var lp     = Ext.getCmp(module + 'lpAcuerdo');
				var remove = lp.removeAll(true);
				lp.add(dataViewer);
				lp.doLayout();
			}
		}
	});

	var AcuerdoTree = new Acuerdo.tree();
	AcuerdoTree.filter = new Ext.ux.tree.TreeFilterX(AcuerdoTree);

	AcuerdoTree.getLoader().on('load', function(loader, node, response){
		/*var parent = AcuerdoTree.getRootNode();
		if (folder_id != '0') {
			parent = AcuerdoTree.getNodeById(folder_id);
			if(parent){
				node = parent;
			}
			else{
				AcuerdoTree.getRootNode().expand(true, false);
			}
		};
		if (parent) {
			AcuerdoTree.fireEvent('click', parent);
		};*/
	});

	AcuerdoTree.getLoader().on('beforeload', function(loader, node, callback){
		if(Ext.getCmp(module+'lpAcuerdo').items.items.length == 0){
			AcuerdoTree.getRootNode().select();
			initialPanel();
		}
	});

	var acuerdoLayout = new Ext.Panel({
		xtype: 'panel'
		,layout: 'border'
		,id:module + 'acuerdoLayout'
		,border: false
		,items: [
			AcuerdoTree
		,{
			region:'center'
			,layout: 'fit'
			,id: module + 'lpAcuerdo'
			,items:[]
		}]
	});

	/*elimiar cualquier estado del Tree guardado con anterioridad */
	Ext.state.Manager.clear(AcuerdoTree.getItemId());

	return acuerdoLayout;
	/*********************************************** Start functions***********************************************/

	function initialPanel(){
		Ext.getCmp('tab-' + module).purgeListeners();
		if(!Ext.getCmp(module+'initialPanel')){
			var lp = Ext.getCmp(module + 'lpAcuerdo');

			var remove = lp.removeAll(true);

			var initialPanel = {
				xtype:'panel'
				,id:module+'initialPanel'
				,border:false
				,autoScroll: false
				,layout: 'fit'
				,monitorResize:true
				,items:[{
					border:false
					,xtype:'panel'
					,autoWidth:true
					,autoScroll:true
					,border: false
					//,baseCls:'bootstrap-styles'
					,layout:'column'
					,items: [gridAcuerdo_det]
				}]
			}
			Ext.getCmp(module+'lpAcuerdo').add(initialPanel);
			Ext.getCmp(module+'lpAcuerdo').doLayout();
		}
	}

	/*********************************************** End functions***********************************************/
})()