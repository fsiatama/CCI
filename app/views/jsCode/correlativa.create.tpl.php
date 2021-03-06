/*<script>*/
(function(){
	Ext.form.Field.prototype.msgTarget = 'side';
	var module = '<?= $module; ?>';

	var configStorePosicion = {
		 url:'posicion/list'
		,root:'data'
		,sortInfo:{field:'id_posicion',direction:'ASC'}
		,totalProperty:'total'
		,fields:[
			{name:'id_posicion', type:'string'}
			,{name:'posicion', type:'string'}
		]
	};

	var storePosicionOrigen  = new Ext.data.JsonStore(configStorePosicion);
	var storePosicionDestino = new Ext.data.JsonStore(configStorePosicion);

	var resultTpl = new Ext.XTemplate(
		'<tpl for=".">' +
			'<div class="search-item x-combo-list-item" ext:qtip="{id_posicion}">' +
				'<span><b>{id_posicion}</b>&nbsp;-&nbsp;{posicion}</span>' +
			'</div>' +
		'</tpl>'
	);

	var Combo = Ext.extend(Ext.ux.form.SuperBoxSelect, {
		xtype:'superboxselect'
		,resizable:false
		,anchor:'88%'
		,minChars:2
		,forceSelection:true
		,allowNewData:true
		,extraItemCls:'x-tag'
		,allowBlank:false
		,extraItemStyle:'border-width:2px'
		,stackItems:true
		,mode:'remote'
		,queryDelay:0
		,triggerAction:'all'
		,itemSelector:'.search-item'
		,pageSize:10
	});

	var comboPosicionOrigen = new Combo({
		id:module+'comboPosicionOrigen'
		,fieldLabel:'<?= Lang::get('correlativa.columns_title.correlativa_origen'); ?>'
		,name:'correlativa_origen[]'
		,store:storePosicionOrigen
		,displayField:'posicion'
		,valueField:'id_posicion'
		,tpl: resultTpl
		,displayFieldTpl:'({id_posicion}) - {posicion}'
		,listeners:{
			'beforequery':{
				fn: function(queryEvent) {
					var store = this.getStore();
					store.setBaseParam('selected', this.getValue());
				}
			}
		}
	});

	var comboPosicionDestino = new Combo({
		id:module+'comboPosicionDestino'
		,fieldLabel:'<?= Lang::get('correlativa.columns_title.correlativa_origen'); ?>'
		,name:'correlativa_destino[]'
		,store:storePosicionDestino
		,displayField:'posicion'
		,valueField:'id_posicion'
		,tpl: resultTpl
		,displayFieldTpl:'({id_posicion}) - {posicion}'
		,listeners:{
			'beforequery':{
				fn: function(queryEvent) {
					var store = this.getStore();
					store.setBaseParam('selected', this.getValue());
				}
			}
		}
	});

	var formCorrelativa = new Ext.FormPanel({
		baseCls:'x-plain'
		,id:module + 'formCorrelativa'
		,method:'POST'
		,autoWidth:true
		,autoScroll:true
		,buttonAlign:'center'
		,trackResetOnLoad:true
		,monitorValid:true
		,bodyStyle:	'padding:15px;position:relative;'
		,reader: new Ext.data.JsonReader({
			root:'data'
			,totalProperty:'total'
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
			,flex: 1
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
					,format:'Y-m-d'
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

	<?php
	if ($action == 'modify') {

		echo "
	formCorrelativa.on('show', function(){
		formCorrelativa.form.load({
			 url: 'correlativa/listId'
			,params:{
				 correlativa_id: '$correlativa_id'
				,id: '$id'
			}
			,method: 'POST'
			,waitTitle:'Loading......'
			,waitMsg: 'Loading......'
			,success: function(formulario, response) {
				Ext.getCmp(module+'comboPosicionOrigen').setValue(response.result.data.correlativa_origen);
				Ext.getCmp(module+'comboPosicionDestino').setValue(response.result.data.correlativa_destino);
			}
		});
	});";
	}
	?>

	return formCorrelativa;


	/*********************************************** Start functions***********************************************/

	function fnCloseTab(){
		var tabs = Ext.getCmp('tabpanel');
		tabs.remove(tabs.activeTab, true);
	}

	function fnSave () {
		if(formCorrelativa.form.isValid()){
			params = {
				id: '<?= $id; ?>'
			};
			formCorrelativa.getForm().submit({
				waitMsg: 'Saving....'
				,waitTitle:'Wait please...'
				,url:'correlativa/<?= $action; ?>'
				,params: params
				,success: function(form, action){
					if(Ext.getCmp('<?= $parent; ?>gridCorrelativa')){
						Ext.getCmp('<?= $parent; ?>gridCorrelativa').getStore().reload();
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
		else{
			Ext.Msg.show({
				title: Ext.ux.lang.messages.warning
				,msg: Ext.ux.lang.error.empty_fields
				,buttons: Ext.Msg.OK
				,icon: Ext.Msg.WARNING
			});
		}
	}

	/*********************************************** End functions***********************************************/
})()