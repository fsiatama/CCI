<?php
$acuerdo_descripcion = Inflector::compress($acuerdo_descripcion);
?>
/*<script>*/
(function(){
	Ext.form.Field.prototype.msgTarget = 'side';
	var module = '<?= $module."_".$acuerdo_id; ?>';
	var numberRecords = Math.floor((Ext.getCmp('tabpanel').getInnerHeight() - 120)/22);
	
	var arrCountries = <?= json_encode($country_data); ?>;
	var arrMarket    = [{id_pais: 99999, pais: '<?= $mercado_nombre ?>'}];

	var storeContingente = new Ext.data.JsonStore({
		url:'contingente/list'
		,root:'data'
		,sortInfo:{field:'contingente_id',direction:'ASC'}
		,totalProperty:'total'
		,baseParams:{
			id:'<?= $id; ?>'
			,contingente_acuerdo_det_id:'<?= $acuerdo_id; ?>'
			,contingente_acuerdo_det_acuerdo_id:'<?= $acuerdo_det_acuerdo_id; ?>'
		}
		,fields:[
			{name:'contingente_id', type:'float'},
			{name:'contingente_id_pais', type:'float'},
			{name:'contingente_mcontingente', type:'string'},
			{name:'contingente_desc', type:'string'},
			{name:'contingente_acuerdo_det_id', type:'float'},
			{name:'contingente_acuerdo_det_acuerdo_id', type:'float'}
		]
	});

	var cmContingente = new Ext.grid.ColumnModel({
		columns:[
			{header:'<?= Lang::get('contingente.columns_title.contingente_id_pais'); ?>', hidden:false, dataIndex:'pais'},
			{header:'<?= Lang::get('contingente.columns_title.contingente_mcontingente'); ?>', align:'left', hidden:false, dataIndex:'contingente_mcontingente'},
			{header:'<?= Lang::get('contingente.columns_title.contingente_desc'); ?>', align:'left', hidden:false, dataIndex:'contingente_desc'},
		]
		,defaults:{
			sortable:true
			,width:100
		}
	});

	var tbContingente = new Ext.Toolbar({
		items:[{
			text: Ext.ux.lang.buttons.add
			,iconCls: 'silk-add'
			,handler: function(){
				if(Ext.getCmp('tab-edit_'+module)){
					Ext.Msg.show({
						 title:Ext.ux.lang.messages.warning
						,msg:Ext.ux.lang.error.close_tab
						,buttons: Ext.Msg.OK
						,icon: Ext.Msg.WARNING
					});
				}
				else{
					var data = {
						id:'add_' + module
						,iconCls:'silk-add'
						,titleTab:'<?= $title; ?> - ' + Ext.ux.lang.buttons.add
						,url:'contingente/jscode/create'
						,params:{
							id:'<?= $id; ?>'
							,title: '<?= $title; ?> - ' + Ext.ux.lang.buttons.add
							,module: 'add_' + module
							,parent: module
							,contingente_acuerdo_det_id:'<?= $acuerdo_det_id; ?>'
						}
					};
					Ext.getCmp('oeste').addTab(this,this,data);
				}
			}
		}]
	});

	var gridContingente = new Ext.grid.GridPanel({
		store:storeContingente
		,id:module + 'gridContingente'
		,colModel:cmContingente
		,viewConfig: {
			forceFit: true
			,scrollOffset:2
		}
		,sm: new Ext.grid.RowSelectionModel({
			singleSelect: true
		})
		//,bbar:new Ext.PagingToolbar({pageSize:numberRecords, store:storeContingente, displayInfo:true})
		,enableColumnMove:false
		,enableColumnResize:false
		,tbar:tbContingente
		,loadMask:true
		,border:false
		,frame: false
		,baseCls: 'x-panel-mc'
		,buttonAlign:'center'
		,title:''
		,iconCls:'icon-grid'
		,autoHeight:true
		,autoWidth:true
		,plugins:[
			new Ext.ux.grid.Search({
				iconCls:'silk-zoom'
				,searchText: Ext.ux.lang.grid.search
				,selectAllText: Ext.ux.lang.grid.selectAllText
				,id:module+'SearchBox'
				,minChars:2
				,width:200
				,mode:'remote'
				,align:'right'
				,position:top
				,disableIndexes:[]
			}) 
			//,gridContingenteAction
			//,gridContingenteExpander
		]
	});

	/*elimiar cualquier estado de la grilla guardado con anterioridad */
	Ext.state.Manager.clear(gridContingente.getItemId());

	var panelContingente = new Ext.Panel({
		xtype:'panel'
		,id:module+'panelContingente'
		,layout:'border'
		,border:false
		,bodyCssClass:'x-plain'
		,bodyStyle:	'padding:15px;position:relative;'
		,autoWidth:true
		,autoScroll:true
		,items:[{
			region:'north'
			,border:false
			,bodyStyle:'padding:15px;'
			,html: '<div class="bootstrap-styles">' +
				'<div class="page-head">' +
					'<h4 class="nopadding"><i class="styleColor fa fa-area-chart"></i> <?= $acuerdo_nombre; ?></h4>' +
					'<div class="clearfix"></div><p><?= $acuerdo_descripcion; ?></p>' +
				'</div>' +
			'</div>'
		},{
			layout:'column'
			,region:'center'
			,border:false
			,defaults:{columnWidth:1}
			,bodyStyle:'padding:10px;'
			,items:[
				gridContingente
			]
		}]
	});
	
	return panelContingente;
	/*********************************************** Start functions***********************************************/
	
	function fnEditItm(record){
		var key = record.get('contingente_id');
		if(Ext.getCmp('tab-add_'+module)){
			Ext.Msg.show({
				 title:Ext.ux.lang.messages.warning
				,msg:Ext.ux.lang.error.close_tab
				,buttons: Ext.Msg.OK
				,icon: Ext.Msg.WARNING
			});
		}
		else{
			var data = {
				id:'edit_' + module
				,iconCls:'silk-page-edit'
				,titleTab:'<?= $title; ?> - ' + Ext.ux.lang.buttons.modify
				,url:'contingente/jscode/modify'
				,params:{
					id:'<?= $id; ?>'
					,title: '<?= $title; ?> - ' + Ext.ux.lang.buttons.modify
					,module: 'edit_' + module
					,parent: module
					,contingente_id: key
				}
			};
			Ext.getCmp('oeste').addTab(this,this,data);
		}
	}
	function fnDeleteItem(record){
		Ext.Msg.confirm(
			Ext.ux.lang.messages.confirmation
			,Ext.ux.lang.messages.question_delete
			,function(btn){
			if(btn == 'yes'){
				var gridMask = new Ext.LoadMask(gridContingente.getEl(), { msg: 'Erasing .....' });
				gridMask.show();
				var key = record.get('contingente_id');

				Ext.Ajax.request({
					 url:'contingente/delete'
					,params: {
						id: '<?= $id; ?>'
						,contingente_id: key
					}
					,callback: function(options, success, response){
						gridMask.hide();
						var json = Ext.util.JSON.decode(response.responseText);
						if (json.success){
							gridContingente.store.reload();
						}
						else{
							Ext.Msg.show({
							   title:Ext.ux.lang.messages.error,
							   buttons: Ext.Msg.OK,
							   msg:json.error,
							   animEl: 'elId',
							   icon: Ext.MessageBox.ERROR
							});
						}
					}
				});
			};
		});
	}
	

	/*********************************************** End functions***********************************************/
})()