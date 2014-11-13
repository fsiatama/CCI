/*<script>*/
(function(){
	var App = new Ext.App({});
	Ext.form.Field.prototype.msgTarget = 'side';
	var modulo = 'cargos';
	var prefijoId = 'cargos-';
	var cantidad_reg = Math.floor((Ext.getCmp('tabpanel').getInnerHeight() - 120)/22);
	var today=new Date()
	var msg = function(title, msg, tipo){
		Ext.Msg.show({
			title: title
			,msg: msg,minWidth: 200
			,modal: true
			,icon: tipo
			,buttons: Ext.Msg.OK
		});
	};	
	var F = Ext.util.Format;
	
	var storeUser = new Ext.data.JsonStore({
		url:'proceso/user/'
		,root:'datos'
		,sortInfo:{field:'user_id',direction:'ASC'}
		,totalProperty:'total'
		,baseParams:{accion:'lista'}
		,fields:[
			{name:'user_id', type:'float'},
			{name:'user_full_name', type:'string'},
			{name:'user_email', type:'string'},
			{name:'user_password', type:'string'},
			{name:'user_active', type:'string'},
			{name:'user_profile_id', type:'float'},
			{name:'user_uinsert', type:'float'},
			{name:'user_finsert', type:'date', dateFormat:'Y-m-d H:i:s'},
			{name:'user_fupdate', type:'date', dateFormat:'Y-m-d H:i:s'}
		]
	});
	
	storeCargos.load({params:{start:0, limit:cantidad_reg}});
	
	gridCargosAction = new Ext.ux.grid.RowActions({
		 header:'Actions'
		,keepSelection:true
		,autoWidth:false
		,actions:[{
			iconCls:'silk-delete'
			,tooltip:'Delete this item'
		},{
			 iconCls:'silk-page-edit'
			,tooltip:'Modify this item'
		}]
		,callbacks:{
			'silk-delete':function(grid, record, action, row, col) {
				fnDeleteItem(record);
			}
			,'silk-page-edit':function(grid, record, action, row, col) {
				fnEditItm(record);
			}
		}
	});
	
	var cmCargos = new Ext.grid.ColumnModel({
		columns:[
			{header:'Position', align:'left', hidden:false, dataIndex:'categoria_cargos_nombre'},
			{header:'Job', align:'left', dataIndex:'cargos_nombre'},
			gridCargosAction
		]
		,defaults:{
			sortable:false
			,menuDisabled:true
			,width:100
		}
	});	
	
	
	var tbCargos = new Ext.Toolbar({
		items:[{
			text: 'Add item',
			iconCls: 'silk-add',
			handler: function(){				
				var datos = {
					id:'agregar-'+modulo
					,iconCls:'silk-add'
					,titleTab:'Job - Add item'
					,url:'jscode/cargos_form/'
					,params:{
						code:'jobs'
						,id:'agregar-'+modulo
						,url:'jscode/cargos_form/'
						,accion:'crea'
					}
				};
				Ext.getCmp('oeste').addTab(this,this,datos);
			}
		}]
	});
	var gridCargos = new Ext.grid.GridPanel({
		store:storeCargos
		,id:prefijoId+'gridCargos'
		//,height:200
		,colModel:cmCargos
		,viewConfig: {
			forceFit: true
			,scrollOffset:2
		}
		,sm: new Ext.grid.RowSelectionModel({
			singleSelect: true
		})
		,bbar:new Ext.PagingToolbar({pageSize:cantidad_reg, store:storeCargos, displayInfo:true})
		,enableColumnMove:false
		,enableColumnResize:false
		,tbar:tbCargos
		,loadMask:true
		,border:false
		,frame: false
		,baseCls: 'x-panel-mc'
		,buttonAlign:'center'
		,title:''
		,iconCls:'icon-grid'
		,plugins:[new Ext.ux.grid.Search({
			iconCls:'silk-zoom'
			,id:modulo+'searchid'
			,minChars:2
			,autoFocus:true
			,width:300
			,mode:'remote'
			,position:top
		}), new Ext.ux.grid.Excel(),gridCargosAction]
	});
	
	/***********************************************INICIO FUNCIONES ***********************************************/
	
	function fnEditItm(record){
		var key = record.get('cargos_id');
		if(Ext.getCmp('tab-agregar-'+modulo) || Ext.getCmp('tab-modificar-'+modulo)){
			Ext.Msg.show({
				 title:'Warning'
				,msg:'Please for create or modify a job, you need to close the tab Add Job and / or Modify Job'
				,buttons: Ext.Msg.OK
				,icon: Ext.Msg.WARNING
			});
		}
		else{
			var datos = {
				id:'modificar-'+modulo
				,iconCls:'silk-page-edit'
				,titleTab:'Edit - Job'
				,url:'jscode/cargos_form/'
				,params:{
					code:'jobs'
					,id:'modificar-'+modulo
					,url:'jscode/cargos_form/'
					,accion:'act'
					,key:key
				}
			};
			Ext.getCmp('oeste').addTab(this,this,datos);
		}
	}
	function fnDeleteItem(record){
		Ext.Msg.confirm('Confirmation', 'Are you sure you want to delete the selected item?', function(btn){
			if(btn == 'yes'){
				var gridMask = new Ext.LoadMask(gridCargos.getEl(), { msg: 'Erasing .....' });
				gridMask.show();
				var selectedKeys =  record.get('cargos_id');

				Ext.Ajax.request({
					 url:'proceso/cargos/'
					,params: {
						 accion: 'del'
						,id: selectedKeys
					}
					,callback: function(options, success, response){
						gridMask.hide();
						var json = Ext.util.JSON.decode(response.responseText);
						if (json.success){
							gridCargos.store.reload();
						}
						else{
							Ext.Msg.show({
							   title:'Error',
							   buttons: Ext.Msg.OK,
							   msg:json.reason,
							   animEl: 'elId',
							   icon: Ext.MessageBox.ERROR
							});
						}
					}
				});
			};
		});
	}

	/*----------------------------------------------- FIN FUNCIONES -----------------------------------------------*/
	return gridCargos;	
})()