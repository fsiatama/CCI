/*<script>*/
(function(){
	Ext.form.Field.prototype.msgTarget = 'side';
	var module = '<?= $module; ?>';
	
	var configStorePosicion = {
		 url:'posicion/list'
		,root:'data'
		,sortInfo:{field:'posicion_id',direction:'ASC'}
		,totalProperty:'total'
		,fields:[
			{name:'posicion_id', type:'float'}
			,{name:'posicion', type:'string'}
		]
	};

	var storePosicionOrigen  = new Ext.data.JsonStore(configStorePosicion);
	var storePosicionDestino = new Ext.data.JsonStore(configStorePosicion);

	var resultTpl = new Ext.XTemplate(
		'<tpl for=\".\"><div class=\"search-item\"><span><b>{posicion_id}</b>&nbsp;-&nbsp;{posicion}</span></div></tpl>'
	);

	var comboPosicionOrigen = new Ext.ux.form.SuperBoxSelect({
		id:module+'comboPosicionOrigen'
		,xtype:'superboxselect'
		,fieldLabel:'<?= Lang::get('correlativa.columns_title.correlativa_origen'); ?>'
		,resizable:false
		,name:'correlativa_origen'
		,anchor:'88%'
		,store:storePosicionOrigen
		,minChars:2
		,displayField:'posicion_id'
		,valueField:'posicion'
		,forceSelection:true
		,allowNewData:true
		,extraItemCls:'x-tag'
		,allowBlank:false
		,extraItemStyle:'border-width:2px'
		,stackItems:true
		,tpl:resultTpl
		,mode:'remote'
		,queryDelay:0
		,triggerAction:'all'
		,itemSelector:'.search-item'
		,pageSize:10
	});
	var comboPosicionDestino = new Ext.ux.form.SuperBoxSelect({
		id:module+'comboPosicionDestino'
		,xtype:'superboxselect'
		,fieldLabel:'<?= Lang::get('correlativa.columns_title.correlativa_destino'); ?>'
		,resizable:false
		,name:'correlativa_destino'
		,anchor:'88%'
		,store:storePosicionDestino
		,minChars:2
		,displayField:'posicion_id'
		,valueField:'posicion'
		,forceSelection:true
		,allowNewData:true
		,extraItemCls:'x-tag'
		,allowBlank:false
		,extraItemStyle:'border-width:2px'
		,stackItems:true
		,tpl:resultTpl
		,mode:'remote'
		,queryDelay:0
		,triggerAction:'all'
		,itemSelector:'.search-item'
		,pageSize:10
	});

	var formCorrelativa = new Ext.FormPanel({
		baseCls:'x-panel-mc'
		,id:module + 'formCorrelativa'
		,method:'POST'
		,autoWidth:true
		,autoScroll:true
		,buttonAlign:'center'
		,trackResetOnLoad:true
		,monitorValid:true
		,bodyStyle:'padding:15px;'
		,reader: new Ext.data.JsonReader({
			root:'datos'
			,totalProperty:'total'
			,url:'proceso/trabajo/'
			,fields:[
				{name:'correlativa_id', mapping:'correlativa_id', type:'float'},
				{name:'correlativa_fvigente', mapping:'correlativa_fvigente', type:'string'},
				{name:'correlativa_decreto', mapping:'correlativa_decreto', type:'string'},
				{name:'correlativa_observacion', mapping:'correlativa_observacion', type:'string'},
				{name:'correlativa_origen', mapping:'correlativa_origen', type:'string'},
				{name:'correlativa_destino', mapping:'correlativa_destino', type:'string'},
			]
		})
		,items:[{
			xtype:'fieldset'
			,title:''
			,layout:'column'
			,defaults:{
				columnWidth:.5
				,layout:'form'
				,labelAlign:'top'
				,border:false
				,xtype:'panel'
				,bodyStyle:'padding:0 18px 0 0'
			}
			,items:[{
				defaults:{anchor:'100%'}
				,items:[{
					xtype:'textfield'
					,name:'correlativa_decreto'
					,fieldLabel:'<?= Lang::get('correlativa.columns_title.correlativa_decreto'); ?>'
					,id:module+'correlativa_decreto'
					,allowBlank:false
				}]
			},{
				defaults:{anchor:'100%'}
				,items:[{
					xtype:'datefield'
					,name:'correlativa_fvigente'
					,fieldLabel:'<?= Lang::get('correlativa.columns_title.correlativa_fvigente'); ?>'
					,id:module+'correlativa_fvigente'
					,allowBlank:false
				}]
			},{
				defaults:{anchor:'100%'}
				,columnWidth:1
				,items:[{
					xtype:'textarea'
					,name:'correlativa_observacion'
					,fieldLabel:'<?= Lang::get('correlativa.columns_title.correlativa_observacion'); ?>'
					,id:module+'correlativa_observacion'
					,allowBlank:false
				}]
			},{
				defaults:{anchor:'100%'}
				,items:[comboPosicionOrigen]
			},{
				defaults:{anchor:'100%'}
				,items:[comboPosicionDestino]
			},{
				xtype:'hidden'
				,name:'correlativa_id'
				,id:module+'correlativa_id'
			},{
				xtype:'hidden'
				,name:'user_profile_id'
				,id:module+'user_profile_id'
			}]
		}]
		,buttons: [{
			text:Ext.ux.lang.buttons.cancel
			,iconCls:'silk-cancel'
			,formBind:false
			,handler:function(){
				fnCloseTab();
			}
		},{
			 text:Ext.ux.lang.buttons.save
			,iconCls: 'icon-save'
			,formBind: false
			,handler:function(){
				fnSave();
			}
		}]
	});	

	return formCorrelativa;

	
	/*********************************************** Start functions***********************************************/
	
	function fnCloseTab(){
		var tabs = Ext.getCmp('tabpanel');
		tabs.remove(tabs.activeTab, true);
	}

	function fnSave () {
		params = {
			user_password:Ext.ux.util.MD5(Ext.getCmp(module+'user_password').getValue())
			,id: '<?= $id; ?>'
		};
		formUser.getForm().submit({
			waitMsg: 'Saving....'
			,waitTitle:'Wait please...'
			,url:'user/create'
			,params: params
			,success: function(form, action){
				if(Ext.getCmp('<?= $parent; ?>gridUser')){
					Ext.getCmp('<?= $parent; ?>gridUser').getStore().reload();
				}
				fnCloseTab();
			}
			,failure:function(form, action){
				Ext.Msg.show({
				   title:'Error',
				   buttons: Ext.Msg.OK,
				   msg:Ext.decode(action.response.responseText).error,
				   animEl: 'elId',
				   icon: Ext.MessageBox.ERROR
				});
			}
		});
	}

	/*********************************************** End functions***********************************************/
})()