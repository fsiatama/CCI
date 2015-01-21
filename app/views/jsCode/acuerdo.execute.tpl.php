<?php
$partner        = (empty($mercado_nombre)) ? $pais : $mercado_nombre ;
$htmlCountryies = '';
foreach ($countryData as $key => $row) {
	$htmlCountryies .= '<li class="list-group-item"><span class="badge">'.($key + 1).'</span>'.$row['pais'].'</li>';
}

$htmlAgreement = '
<div class="container">
	<div class="row">
		<div class="col-lg-10 col-md-9 col-xs-8">
			<h1 class="page-header">'.$acuerdo_nombre.'</h1>
			<div class="jumbotron">
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
	var module       = '<?= $module; ?>';
	
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
			console.log(node);
			if(node){
				var dataViewer = new Ext.Panel({
					autoScroll: false
					,layout: 'fit'
					,autoShow: true
					,frame:false
					,border: false
					,autoDestroy:true
					,plugins: new Ext.ux.Plugin.RemoteComponent({
						url: 'acuerdo_det/jscodeExecute'
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
				,items:[{
					border:false
					,xtype:'panel'
					,autoWidth:true
					,autoScroll:true
					,border: false
					,baseCls:'bootstrap-styles'
					,layout:'column'
					,items: [{
						style:{padding:'0px'}
						,columnWidth:1
						,border: false
						//,layout:'fit'
						,html: '<?= $htmlAgreement; ?>'
					}]
				}]
			}
			Ext.getCmp(module+'lpAcuerdo').add(initialPanel);
			Ext.getCmp(module+'lpAcuerdo').doLayout();
		}
	}

	/*********************************************** End functions***********************************************/
})()