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

	var storePosicion  = new Ext.data.JsonStore(configStorePosicion);

	var resultTplPosicion = new Ext.XTemplate(
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

	var comboPosicion = new Combo({
		id:module+'comboPosicion'
		,fieldLabel:'<?= Lang::get('indicador.columns_title.posicion'); ?>'
		,name:'id_posicion[]'
		,store:storePosicion
		,displayField:'posicion'
		,valueField:'id_posicion'
		,tpl: resultTplPosicion
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

	var storePais = new Ext.data.JsonStore({
		url:'pais/list'
		,id:module+'storePais'
		,root:'data'
		,sortInfo:{field:'id_pais',direction:'ASC'}
		,totalProperty:'total'
		,baseParams:{id:'<?= $id; ?>'}
		,fields:[
			{name:'id_pais', type:'float'},
			{name:'pais', type:'string'}
		]
	});
	var resultTplPais = new Ext.XTemplate(
		'<tpl for=".">' +
			'<div class="search-item x-combo-list-item" ext:qtip="{id_pais}">' +
				'<span><b>{id_pais}</b>&nbsp;-&nbsp;{pais}</span>' +
			'</div>' +
		'</tpl>'
	);
	var comboPais = new Combo({
		id:module+'comboPais'
		,singleMode:true
		,fieldLabel:'<?= Lang::get('indicador.columns_title.pais_origen'); ?>'
		,name:'id_pais[]'
		,store:storePais
		,displayField:'pais'
		,valueField:'id_pais'
		,tpl: resultTplPais
		,displayFieldTpl:'({id_pais}) - {pais}'
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
				{name:'indicador_tipo_indicador_id', mapping:'indicador_tipo_indicador_id', type:'float'},
				{name:'indicador_nombre', mapping:'indicador_nombre', type:'string'},
				{name:'id_posicion', mapping:'id_posicion', type:'string'},
				{name:'id_pais', mapping:'id_pais', type:'string'}
			]
		})
		,defaults: {anchor:'97%'}
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
			}]
		},{
			xtype:'fieldset'
			,title: Ext.ux.lang.reports.filters
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
				,items:[comboPais]
			},{
				defaults:{anchor:'100%'}
				,columnWidth:1
				,items:[comboPosicion]
			},{
				xtype:'hidden'
				,name:'indicador_tipo_indicador_id'
				,id:module+'indicador_tipo_indicador_id'
				,value: '<?= $tipo_indicador_id; ?>'
			},{
				xtype:'hidden'
				,name:'indicador_id'
				,id:module+'indicador_id'

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
				Ext.getCmp(module+'comboPosicion').setValue(response.result.data.id_posicion);
				Ext.getCmp(module+'comboPais').setValue(response.result.data.id_pais);
			}
		});
	});";
	}
	?>

	return formIndicador;


	/*********************************************** Start functions***********************************************/

	function getDescription () {
		var arrDescription = [];
		
		var arrValues      = [];
		var selection      = Ext.getCmp(module+'comboPais').getSelectedRecords();
		var label          = Ext.getCmp(module+'comboPais').fieldLabel;
		
		Ext.each(selection,function(row){
			arrValues.push(row.get('pais'));
		});
		arrDescription.push({
			label: label
			,values: arrValues
		});

		arrValues      = [];
		selection      = Ext.getCmp(module+'comboPosicion').getSelectedRecords();
		label          = Ext.getCmp(module+'comboPosicion').fieldLabel;
		
		Ext.each(selection,function(row){
			arrValues.push('['+row.get('id_posicion')+'] ' + row.get('posicion'));
		});
		arrDescription.push({
			label: label
			,values: arrValues
		});
		return arrDescription;
	}
	function fnCloseTab(){
		var tabs = Ext.getCmp('tabpanel');
		tabs.remove(tabs.activeTab, true);
	}

	function fnSave () {
		if(formIndicador.form.isValid()){
			var description = getDescription();
			params = {
				id: '<?= $id; ?>'
				,description: Ext.encode(description)
			};
			formIndicador.getForm().submit({
				waitMsg: 'Saving....'
				,waitTitle:'Wait please...'
				,url:'indicador/<?= $action; ?>'
				,params: params
				,success: function(form, action){
					if(Ext.getCmp('<?= $tree; ?>')){
						Ext.getCmp('<?= $tree; ?>').cargar('<?= $indicador_id; ?>');
					}
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