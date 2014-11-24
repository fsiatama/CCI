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
			{name:'posicion_id', type:'string'}
			,{name:'posicion', type:'string'}
		]
	};

	var storePosicion  = new Ext.data.JsonStore(configStorePosicion);

	var resultTpl = new Ext.XTemplate(
		'<tpl for=".">' +
			'<div class="search-item x-combo-list-item" ext:qtip="{posicion_id}">' +
				'<span><b>{posicion_id}</b>&nbsp;-&nbsp;{posicion}</span>' +
			'</div>' +
		'</tpl>'
	);

	var PosicionCombo = Ext.extend(Ext.ux.form.SuperBoxSelect, {
		xtype:'superboxselect'
		,resizable:false
		,anchor:'88%'
		,minChars:2
		,displayField:'posicion'
		,valueField:'posicion_id'
		,forceSelection:true
		,allowNewData:true
		,extraItemCls:'x-tag'
		,allowBlank:false
		,extraItemStyle:'border-width:2px'
		,stackItems:true
		,tpl: resultTpl
		,mode:'remote'
		,queryDelay:0
		,triggerAction:'all'
		,itemSelector:'.search-item'
		,pageSize:10
		,displayFieldTpl:'({posicion_id}) - {posicion}'
	});

	var comboPosicion = new PosicionCombo({
		id:module+'comboPosicion'
		,fieldLabel:'<?= Lang::get('indicador.columns_title.posicion'); ?>'
		,name:'posicion[]'
		,store:storePosicion
	});

	var storePais = new Ext.data.JsonStore({
		url:'pais/list'
		,id:module+'storePais'
		,root:'data'
		,sortInfo:{field:'pais_id',direction:'ASC'}
		,totalProperty:'total'
		,baseParams:{id:'<?= $id; ?>'}
		,fields:[
			{name:'pais_id', type:'float'},
			{name:'pais', type:'string'}
		]
	});
	var comboPais = new Ext.form.ComboBox({
		hiddenName:'pais'
		,id:module+'comboPais'
		,fieldLabel:'<?= Lang::get('indicador.columns_title.pais_origen'); ?>'
		,store:storePais
		,valueField:'pais_id'
		,displayField:'pais'
		,typeAhead:true
		,forceSelection:true
		,triggerAction:'all'
		,selectOnFocus:true
		,allowBlank:false
		,listeners:{
			select: {
				fn: function(combo,reg){
					Ext.getCmp(module + 'pais_id').setValue(reg.data.pais_id);
				}
			}
		}
	});

	
	var formIndicador = new Ext.FormPanel({
		baseCls:'x-plain'
		,id:module + 'formIndicador'
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
				{name:'indicador_id', mapping:'indicador_id', type:'float'},
				{name:'indicador_nombre', mapping:'indicador_nombre', type:'string'},
				{name:'indicador_campos', mapping:'indicador_campos', type:'string'},
				{name:'indicador_filtros', mapping:'indicador_filtros', type:'string'},
				{name:'indicador_leaf', mapping:'indicador_leaf', type:'string'},
			]
		})
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
					,name:'indicador_nombre'
					,fieldLabel:'<?= Lang::get('indicador.columns_title.indicador_nombre'); ?>'
					,id:module+'correlativa_decreto'
					,allowBlank:false
				}]
			},{
				defaults:{anchor:'100%'}
				,items:[comboPais]
			},{
				defaults:{anchor:'100%'}
				,columnWidth:1
				,items:[comboPosicion]
			},{
				xtype:'hidden'
				,name:'pais_id'
				,id:module+'pais_id'
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
	formIndicador.on('show', function(){
		formIndicador.form.load({
			 url: 'indicador/listId'
			,params:{
				indicador_id: '$indicador_id'
				,id: '$id'
			}
			,method: 'POST'
			,waitTitle:'Loading......'
			,waitMsg: 'Loading......'
			,success: function(formulario, response) {
				Ext.getCmp(module+'comboPosicion').setValue(response.result.data.correlativa_origen);
			}
		});
	});";
	}
	?>

	return formIndicador;


	/*********************************************** Start functions***********************************************/

	function fnCloseTab(){
		var tabs = Ext.getCmp('tabpanel');
		tabs.remove(tabs.activeTab, true);
	}

	function fnSave () {
		if(formIndicador.form.isValid()){
			params = {
				id: '<?= $id; ?>'
			};
			formIndicador.getForm().submit({
				waitMsg: 'Saving....'
				,waitTitle:'Wait please...'
				,url:'indicador/<?= $action; ?>'
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