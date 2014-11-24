/*<script>*/
(function(){
	Ext.form.Field.prototype.msgTarget = 'side';
	var module = '<?= $module; ?>';
	var numberRecords = Math.floor((Ext.getCmp('tabpanel').getInnerHeight() - 120)/22);
	
	var storeUser = new Ext.data.JsonStore({
		url:'user/list'
		,root:'data'
		,sortInfo:{field:'user_id',direction:'ASC'}
		,totalProperty:'total'
		,baseParams:{id:'<?= $id; ?>'}
		,fields:[
			{name:'user_id', type:'float'},
			{name:'user_full_name', type:'string'},
			{name:'user_email', type:'string'},
			{name:'user_active', type:'string'},
			{name:'user_active_title', type:'string'},
			{name:'user_profile_id', type:'float'},
			{name:'profile_name', type:'string'}
		]
	});
	
	storeUser.load({params:{start:0, limit:numberRecords}});
	
	gridUserAction = new Ext.ux.grid.RowActions({
		 header: Ext.ux.lang.grid.options
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
			new Ext.grid.RowNumberer(),
			{header:'<?= Lang::get('user.columns_title.user_full_name'); ?>', hidden:false, dataIndex:'user_full_name'},
			{header:'<?= Lang::get('user.columns_title.user_email'); ?>', hidden:false, dataIndex:'user_email'},
			{header:'<?= Lang::get('user.columns_title.user_active'); ?>', hidden:false, dataIndex:'user_active_title'},
			{header:'<?= Lang::get('user.columns_title.profile_name'); ?>', hidden:false, dataIndex:'profile_name'},
			gridUserAction
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
					id:'add_' + module
					,iconCls:'silk-add'
					,titleTab:'<?= $title; ?> - ' + Ext.ux.lang.buttons.add
					,url:'user/jscode/create'
					,params:{
						id:'<?= $id; ?>'
						,title: '<?= $title; ?> - ' + Ext.ux.lang.buttons.add
						,module: 'add_' + module
						,parent: module
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
				,disableIndexes:['user_active_title', 'profile_name']
			}) 
			,new Ext.ux.grid.Excel()
			,gridUserAction
		]
	});
	
	return gridUser;	
	/*********************************************** Start functions***********************************************/
	
	function fnEditItm(record){
		var key = record.get('user_id');
		if(Ext.getCmp('tab-add-'+module)){
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
				,url:'user/jscode/modify'
				,params:{
					id:'<?= $id; ?>'
					,title: '<?= $title; ?> - ' + Ext.ux.lang.buttons.modify
					,module: 'edit_' + module
					,parent: module
					,user_id: key
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
				var gridMask = new Ext.LoadMask(gridUser.getEl(), { msg: 'Erasing .....' });
				gridMask.show();
				var key = record.get('user_id');

				Ext.Ajax.request({
					 url:'user/delete'
					,params: {
						id: '<?= $id; ?>'
						,user_id: key
					}
					,callback: function(options, success, response){
						gridMask.hide();
						var json = Ext.util.JSON.decode(response.responseText);
						if (json.success){
							gridUser.store.reload();
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