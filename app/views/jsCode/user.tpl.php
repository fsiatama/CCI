/*<script>*/
(function(){
	Ext.form.Field.prototype.msgTarget = 'side';
	var module = '<?= $module; ?>';
	var numberRecords = Math.floor((Ext.getCmp('tabpanel').getInnerHeight() - 120)/22);
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
		url:'user/list'
		,root:'data'
		,sortInfo:{field:'user_id',direction:'ASC'}
		,totalProperty:'total'
		,fields:[
			{name:'user_id', type:'float'},
			{name:'user_full_name', type:'string'},
			{name:'user_email', type:'string'},
			{name:'user_password', type:'string'},
			{name:'user_active', type:'string'},
			{name:'user_profile_id', type:'float'}
		]
	});
	
	storeUser.load({params:{start:0, limit:numberRecords}});
	
	gridUserAction = new Ext.ux.grid.RowActions({
		 header: Ext.ux.lang.columns.options
		,keepSelection:true
		,autoWidth:false
		,actions:[{
			iconCls:'silk-delete'
			,tooltip: Ext.ux.lang.buttons.delete_tt
		},{
			 iconCls: 'silk-page-edit'
			,tooltip: Ext.ux.lang.buttons.modify_tt
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
	
	var cmUser = new Ext.grid.ColumnModel({
		columns:[
			{xtype:'numbercolumn', header:'', align:'right', hidden:false, dataIndex:'user_id'},
			{header:'', align:'left', hidden:false, dataIndex:'user_full_name'},
			{header:'', align:'left', hidden:false, dataIndex:'user_email'},
			{header:'', align:'left', hidden:false, dataIndex:'user_active'},
			{xtype:'numbercolumn', header:'', align:'right', hidden:false, dataIndex:'user_profile_id'},
		]
		,defaults:{
			sortable:true
			,width:100
		}
	});
	
	
	var tbUser = new Ext.Toolbar({
		items:[{
			text: Ext.ux.lang.buttons.add
			,iconCls: 'silk-add'
			,handler: function(){				
				var data = {
					id:'add-' + module
					,iconCls:'silk-add'
					,titleTab:'<?= $title; ?> - ' + Ext.ux.lang.buttons.add
					,url:''
					,params:{
						code:'jobs'
						,id:'agregar-'+module
						,url:'user/jscode/add'
					}
				};
				Ext.getCmp('oeste').addTab(this,this,data);
			}
		}]
	});
	var gridUser = new Ext.grid.GridPanel({
		store:storeUser
		,id:module + 'gridUser'
		,colModel:cmUser
		,viewConfig: {
			forceFit: true
			,scrollOffset:2
		}
		,sm: new Ext.grid.RowSelectionModel({
			singleSelect: true
		})
		,bbar:new Ext.PagingToolbar({pageSize:numberRecords, store:storeUser, displayInfo:true})
		,enableColumnMove:false
		,enableColumnResize:false
		,tbar:tbUser
		,loadMask:true
		,border:false
		,frame: false
		,baseCls: 'x-panel-mc'
		,buttonAlign:'center'
		,title:''
		,iconCls:'icon-grid'
		,plugins:[new Ext.ux.grid.Search({
			iconCls:'silk-zoom'
			,id:module+'searchid'
			,minChars:2
			,autoFocus:true
			,width:300
			,mode:'remote'
			,position:top
		}), new Ext.ux.grid.Excel(),gridUserAction]
	});
	
	/***********************************************INICIO FUNCIONES ***********************************************/
	
	function fnEditItm(record){
		var key = record.get('cargos_id');
		if(Ext.getCmp('tab-agregar-'+module) || Ext.getCmp('tab-modificar-'+module)){
			Ext.Msg.show({
				 title:'Warning'
				,msg:'Please for create or modify a job, you need to close the tab Add Job and / or Modify Job'
				,buttons: Ext.Msg.OK
				,icon: Ext.Msg.WARNING
			});
		}
		else{
			var data = {
				id:'modificar-'+module
				,iconCls:'silk-page-edit'
				,titleTab:'Edit - Job'
				,url:'jscode/cargos_form/'
				,params:{
					code:'jobs'
					,id:'modificar-'+module
					,url:'jscode/cargos_form/'
					,accion:'act'
					,key:key
				}
			};
			Ext.getCmp('oeste').addTab(this,this,data);
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
	return gridUser;	
})()