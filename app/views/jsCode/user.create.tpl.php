/*<script>*/
(function(){
	Ext.form.Field.prototype.msgTarget = 'side';
	var module = '<?= $module; ?>';
	
	var storeProfile = new Ext.data.JsonStore({
		url:'profile/list'
		,root:'data'
		,sortInfo:{field:'profile_id',direction:'ASC'}
		,totalProperty:'total'
		,fields:[
			{name:'profile_id', type:'float'},
			{name:'profile_name', type:'string'}
		]
	});
	
	var comboProfile = new Ext.form.ComboBox({
		hiddenName:'profile'
		,id:module+'comboProfile'
		,fieldLabel:'<?= Lang::get('user.columns_title.profile_name'); ?>'
		,store:storeProfile
		,valueField:'profile_id'
		,displayField:'profile_name'
		,typeAhead:true
		,forceSelection:true
		,triggerAction:'all'
		,selectOnFocus:true
		,allowBlank:false
		,listeners:{
			select: {
				fn: function(combo,reg){
					Ext.getCmp(module + 'user_profile_id').setValue(reg.data.profile_id);
				}
			}
		}
	});

	var formUser = new Ext.FormPanel({
		baseCls:'x-plain'
		,id:module + 'formUser'
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
				{name:'user_id', mapping:'user_id', type:'float'},
				{name:'user_full_name', mapping:'user_full_name', type:'string'},
				{name:'user_email', mapping:'user_email', type:'string'},
				{name:'user_active', mapping:'user_active', type:'string'},
				{name:'user_profile_id', mapping:'user_profile_id', type:'float'},
				{name:'profile_name', mapping:'profile_name', type:'string'}
			]
		})
		,layout: {
            type: 'vbox'
            ,align: 'stretch'
        }
		,items:[{
			xtype:'fieldset'
			,title:''
			,layout:'column'
			,flex: 1
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
					xtype:'textfield'
					,name:'user_full_name'
					,fieldLabel:'<?= Lang::get('user.columns_title.user_full_name'); ?>'
					,id:module+'user_full_name'
					,allowBlank:false
				}]
			},{
				defaults:{anchor:'100%'}
				,items:[{
					xtype:'textfield'
					,name:'user_email'
					,fieldLabel:'<?= Lang::get('user.columns_title.user_email'); ?>'
					,vtype:'email'
					,id:module+'user_email'
					,allowBlank:false
				}]
			},{
				defaults:{anchor:'100%'}
				,items:[{
					xtype:'radiogroup'
					,fieldLabel:'<?= Lang::get('user.columns_title.user_active'); ?>'
					,id:module+'user_active'
					,allowBlank:false
					,items: [{
						boxLabel: '<?= Lang::get('user.user_active.1'); ?>'
						,checked:true
						,inputValue:1
						,name:'user_active'
					},{
						boxLabel: '<?= Lang::get('user.user_active.0'); ?>'
						, inputValue:0
						, name:'user_active'
					}]
				}]
			},{
				defaults:{anchor:'100%'}
				,items:[comboProfile]
			},{
				defaults:{anchor:'100%'}
				,columnWidth:.5
				,items:[{
					xtype:'textfield'
					,name:'user_password'
					,fieldLabel:'<?= Lang::get('user.columns_title.user_password'); ?>'
					,id:module+'user_password'
					,inputType:'password'
					,allowBlank:false
					,vtype:'password'
					,submitValue:false
				}]
			},{
				defaults:{anchor:'100%'}
				,columnWidth:.5
				,items:[{
					xtype:'textfield'
					,name:'user_password_confirm'
					,fieldLabel:'<?= Lang::get('user.columns_title.user_password_confirm'); ?>'
					,id:module+'user_password_confirm'
					,inputType:'password'
					,allowBlank:false
					,vtype:'confirmVal'
					,initialField:module+'user_password'
					,submitValue:false
				}]
			},{
				xtype:'hidden'
				,name:'user_id'
				,id:module+'user_id'
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

	return formUser;

	
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