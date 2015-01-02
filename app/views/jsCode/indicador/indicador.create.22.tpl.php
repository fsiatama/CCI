/*<script>*/
(function(){
	Ext.form.Field.prototype.msgTarget = 'side';
	var module = '<?= $module.'_'.$indicador_id; ?>';

	var configStoreSubpartida = {
		url:'subpartida/list'
		,root:'data'
		,sortInfo:{field:'id_subpartida',direction:'ASC'}
		,totalProperty:'total'
		,fields:[
			{name:'id_subpartida', type:'string'}
			,{name:'subpartida', type:'string'}
		]
	};

	var storeSubpartida  = new Ext.data.JsonStore(configStoreSubpartida);

	var resultTplSubpartida = new Ext.XTemplate(
		'<tpl for=".">' +
			'<div class="search-item x-combo-list-item" ext:qtip="{id_subpartida}">' +
				'<span><b>{id_subpartida}</b>&nbsp;-&nbsp;{subpartida}</span>' +
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
		,allowBlank:true
		,extraItemStyle:'border-width:2px'
		,stackItems:true
		,mode:'remote'
		,queryDelay:0
		,triggerAction:'all'
		,itemSelector:'.search-item'
		,pageSize:10
	});

	var comboSubpartida = new Combo({
		id:module+'comboSubpartida'
		,fieldLabel:'<?= Lang::get('indicador.columns_title.subpartida'); ?>'
		,name:'id_subpartida[]'
		,store:storeSubpartida
		,displayField:'subpartida'
		,valueField:'id_subpartida'
		,tpl: resultTplSubpartida
		,displayFieldTpl:'({id_subpartida}) - {subpartida}'
		,allowBlank:false
		,listeners:{
			'beforequery':{
				fn: function(queryEvent) {
					var store = this.getStore();
					store.setBaseParam('selected', this.getValue());
				}
			}
		}
	});

	var configStorePais = {
		url:'pais/list'
		,id:module+'storePais'
		,root:'data'
		,sortInfo:{field:'id_pais',direction:'ASC'}
		,totalProperty:'total'
		,fields:[
			{name:'id_pais', type:'float'},
			{name:'pais', type:'string'}
		]
	}
	var storePaisOrigen  = new Ext.data.JsonStore(configStorePais);
	var storePaisDestino = new Ext.data.JsonStore(configStorePais);

	var resultTplPais = new Ext.XTemplate(
		'<tpl for=".">' +
			'<div class="search-item x-combo-list-item" ext:qtip="{id_pais}">' +
				'<span><b>{id_pais}</b>&nbsp;-&nbsp;{pais}</span>' +
			'</div>' +
		'</tpl>'
	);
	var comboPaisOrigen = new Combo({
		id:module+'comboPaisOrigen'
		,singleMode:true
		,fieldLabel:'<?= Lang::get('indicador.columns_title.pais_origen'); ?>'
		,name:'id_pais[]'
		,store:storePaisOrigen
		,displayField:'pais'
		,valueField:'id_pais'
		,tpl: resultTplPais
		,allowBlank:false
		,displayFieldTpl:'({id_pais}) - {pais}'
	});

	var comboPaisDestino = new Combo({
		id:module+'comboPaisDestino'
		,singleMode:true
		,fieldLabel:'<?= Lang::get('indicador.columns_title.pais_destino'); ?>'
		,name:'id_pais[]'
		,store:storePaisDestino
		,displayField:'pais'
		,valueField:'id_pais'
		,tpl: resultTplPais
		,displayFieldTpl:'({id_pais}) - {pais}'
	});

	var arrYears = <?= json_encode($yearsAvailable); ?>;

	var simpleCombo = Ext.extend(Ext.form.ComboBox, {
		typeAhead:false
		,forceSelection:true
		,selectOnFocus:true
		,allowBlank:false
		,triggerAction:'all'
		,flex:true
	});

	var comboAnio_ini = new simpleCombo({
		hiddenName:'anio_ini'
		,id:module+'comboAnio_ini'
		,store:arrYears
		,fieldLabel:Ext.ux.lang.reports.selectYearFrom
	});

	var comboAnio_fin = new simpleCombo({
		hiddenName:'anio_fin'
		,id:module+'comboAnio_fin'
		,store:arrYears
		,fieldLabel:Ext.ux.lang.reports.selectYearTo
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
				{name:'id_subpartida', mapping:'id_subpartida', type:'string'},
				{name:'id_pais_origen', mapping:'id_pais_origen', type:'string'},
				{name:'id_pais_destino', mapping:'id_pais_destino', type:'string'},
				{name:'anio_ini', mapping:'anio_ini', type:'float'},
				{name:'anio_fin', mapping:'anio_fin', type:'float'}
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
					,id:module+'indicador_nombre'
					,allowBlank:false
				}]
			}]
		},{
			xtype:'fieldset'
			,title:Ext.ux.lang.reports.range
			,layout:'column'
			,defaults:{
				columnWidth:.4
				,layout:'form'
				,labelAlign:'top'
				,border:false
				,xtype:'panel'
				,bodyStyle:'padding:0 18px 0 0'
			}
			,items:[{
				defaults:{anchor:'100%'}
				,columnWidth:.2
				,items:[comboAnio_ini]
			},{
				defaults:{anchor:'100%'}
				,columnWidth:.2
				,items:[comboAnio_fin]
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
				,items:[comboPaisOrigen]
				,plugins:[new Ext.ux.FieldHelp(Ext.ux.lang.reports.countryOriginHelp)]
			},{
				defaults:{anchor:'100%'}
				,items:[comboPaisDestino]
				,plugins:[new Ext.ux.FieldHelp(Ext.ux.lang.reports.countryPartnerHelp)]
			},{
				defaults:{anchor:'100%'}
				,columnWidth:1
				,items:[comboSubpartida]
				,plugins:[new Ext.ux.FieldHelp(Ext.ux.lang.reports.hsCode)]
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
				Ext.getCmp(module+'comboSubpartida').setValue(response.result.data.id_subpartida);
				Ext.getCmp(module+'comboPaisOrigen').setValue(response.result.data.id_pais);
				Ext.getCmp(module+'comboPaisDestino').setValue(response.result.data.mercado_id);
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
		var selection      = Ext.getCmp(module+'comboPaisOrigen').getSelectedRecords();
		var label          = Ext.getCmp(module+'comboPaisOrigen').fieldLabel;
		
		Ext.each(selection,function(row){
			arrValues.push(row.get('pais'));
		});
		if (arrValues.length > 0) {
			arrDescription.push({
				label: label
				,values: arrValues
			});
		};

		arrValues      = [];
		selection      = Ext.getCmp(module+'comboPaisDestino').getSelectedRecords();
		label          = Ext.getCmp(module+'comboPaisDestino').fieldLabel;
		
		Ext.each(selection,function(row){
			arrValues.push(row.get('mercado_nombre'));
		});
		if (arrValues.length > 0) {
			arrDescription.push({
				label: label
				,values: arrValues
			});
		};

		arrValues      = [];
		selection      = Ext.getCmp(module+'comboSubpartida').getSelectedRecords();
		label          = Ext.getCmp(module+'comboSubpartida').fieldLabel;
		
		Ext.each(selection,function(row){
			arrValues.push('['+row.get('id_subpartida')+'] ' + row.get('subpartida'));
		});
		if (arrValues.length > 0) {
			arrDescription.push({
				label: label
				,values: arrValues
			});
		};

		var yearIni      = Ext.getCmp(module+'comboAnio_ini').getValue();
		var yearFin      = Ext.getCmp(module+'comboAnio_fin').getValue();
		arrValues     = [];

		arrValues.push(yearIni + ' - ' + yearFin);
		
		arrDescription.push({
			label: Ext.ux.lang.reports.period
			,values: arrValues
		});
		return arrDescription;
	}
	function fnCloseTab(){
		if(Ext.getCmp('<?= $tree; ?>')){
			Ext.getCmp('<?= $tree; ?>').cargar('<?= $indicador_id; ?>');
		}
	}

	function fnSave () {
		if(formIndicador.form.isValid()){
			var description = getDescription();
			params = {
				id: '<?= $id; ?>'
				,parentId: '<?= $parent; ?>'
				,module: '<?= $module; ?>'
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