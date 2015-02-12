/*<script>*/
(function(){
	Ext.form.Field.prototype.msgTarget = 'side';
	var module = '<?= $module.'_'.$indicador_id; ?>';

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
	var storePais = new Ext.data.JsonStore({
		url:'pais/list'
		,id:module+'storePais'
		,root:'data'
		,sortInfo:{field:'id_pais',direction:'ASC'}
		,totalProperty:'total'
		,baseParams:{id:'<?= $id; ?>'}
		,fields:[
			{name:'id_pais', type:'float'},
			{name:'pais', type:'string'},
			{name:'pais_iata', type:'string'},
		]
	});
	var resultTplPais = new Ext.XTemplate(
		'<tpl for=".">' +
			'<div class="search-item x-combo-list-item" ext:qtip="{pais_iata}">' +
				'<span><b>{pais_iata}</b>&nbsp;-&nbsp;{pais}</span>' +
			'</div>' +
		'</tpl>'
	);
	var comboPais = new Combo({
		id:module+'comboPais'
		//,singleMode:true
		,fieldLabel:'<?= Lang::get('indicador.columns_title.pais_destino'); ?>'
		,name:'id_pais[]'
		,store:storePais
		,displayField:'pais'
		,valueField:'id_pais'
		,tpl: resultTplPais
		,displayFieldTpl:'({pais_iata}) - {pais}'
	});

	var storeMercado = new Ext.data.JsonStore({
		url:'mercado/list'
		,id:module+'storeMercado'
		,root:'data'
		,sortInfo:{field:'mercado_id',direction:'ASC'}
		,totalProperty:'total'
		,baseParams:{id:'<?= $id; ?>'}
		,fields:[
			{name:'mercado_id', type:'float'},
			{name:'mercado_nombre', type:'string'}
		]
	});
	var resultTplMercado = new Ext.XTemplate(
		'<tpl for=".">' +
			'<div class="search-item x-combo-list-item">' +
				'<span>{mercado_nombre}</span>' +
			'</div>' +
		'</tpl>'
	);
	var comboMercado = new Combo({
		id:module+'comboMercado'
		,singleMode:true
		,fieldLabel:'<?= Lang::get('mercado.columns_title.mercado_nombre'); ?>'
		,name:'mercado_id[]'
		,store:storeMercado
		,displayField:'mercado_nombre'
		,valueField:'mercado_id'
		,tpl: resultTplMercado
		,displayFieldTpl:'{mercado_nombre}'
	});

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
	var comboPosicion = new Combo({
		id:module+'comboPosicion'
		,fieldLabel:'<?= Lang::get('indicador.columns_title.posicion'); ?>'
		,name:'id_posicion[]'
		,store:storePosicion
		,displayField:'posicion'
		,valueField:'id_posicion'
		,tpl: resultTplPosicion
		,displayFieldTpl:'({id_posicion}) - {posicion}'
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

	var arrYears = <?= json_encode($yearsAvailable); ?>;

	var arrMonths = [
		[1, Date.monthNames[0]],
		[2, Date.monthNames[1]],
		[3, Date.monthNames[2]],
		[4, Date.monthNames[3]],
		[5, Date.monthNames[4]],
		[6, Date.monthNames[5]],
		[7, Date.monthNames[6]],
		[8, Date.monthNames[7]],
		[9, Date.monthNames[8]],
		[10, Date.monthNames[9]],
		[11, Date.monthNames[10]],
		[12, Date.monthNames[11]]
	];

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
	var comboDesde_ini = new simpleCombo({
		hiddenName:'desde_ini'
		,id:module+'comboDesde_ini'
		,store:arrMonths
		,fieldLabel:Ext.ux.lang.reports.selectMonthFrom
	});
	var comboHasta_ini = new simpleCombo({
		hiddenName:'hasta_ini'
		,id:module+'comboHasta_ini'
		,store:arrMonths
		,fieldLabel:Ext.ux.lang.reports.selectMonthTo
	});

	var comboAnio_fin = new simpleCombo({
		hiddenName:'anio_fin'
		,id:module+'comboAnio_fin'
		,store:arrYears
		,fieldLabel:Ext.ux.lang.reports.selectYearFrom
	});
	var comboDesde_fin = new simpleCombo({
		hiddenName:'desde_fin'
		,id:module+'comboDesde_fin'
		,store:arrMonths
		,fieldLabel:Ext.ux.lang.reports.selectMonthFrom
	});
	var comboHasta_fin = new simpleCombo({
		hiddenName:'hasta_fin'
		,id:module+'comboHasta_fin'
		,store:arrMonths
		,fieldLabel:Ext.ux.lang.reports.selectMonthTo
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
				{name:'id_pais', mapping:'id_pais', type:'string'},
				{name:'mercado_id', mapping:'mercado_id', type:'string'},
				{name:'id_posicion', mapping:'id_posicion', type:'string'},
				{name:'anio_ini', mapping:'anio_ini', type:'float'},
				{name:'anio_fin', mapping:'anio_fin', type:'float'},
				{name:'desde_ini', mapping:'desde_ini', type:'float'},
				{name:'hasta_ini', mapping:'hasta_ini', type:'float'},
				{name:'desde_fin', mapping:'desde_fin', type:'float'},
				{name:'hasta_fin', mapping:'hasta_fin', type:'float'}
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
			,title:Ext.ux.lang.reports.initialRange
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
				,items:[comboDesde_ini]
			},{
				defaults:{anchor:'100%'}
				,items:[comboHasta_ini]
			}]
		},{
			xtype:'fieldset'
			,title:Ext.ux.lang.reports.finalRange
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
				,items:[comboAnio_fin]
			},{
				defaults:{anchor:'100%'}
				,items:[comboDesde_fin]
			},{
				defaults:{anchor:'100%'}
				,items:[comboHasta_fin]
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
				,items:[comboMercado]
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
				Ext.getCmp(module+'comboMercado').setValue(response.result.data.mercado_id);
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
		if (arrValues.length > 0) {
			arrDescription.push({
				label: label
				,values: arrValues
			});
		};

		arrValues      = [];
		selection      = Ext.getCmp(module+'comboMercado').getSelectedRecords();
		label          = Ext.getCmp(module+'comboMercado').fieldLabel;
		
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
		selection      = Ext.getCmp(module+'comboPosicion').getSelectedRecords();
		label          = Ext.getCmp(module+'comboPosicion').fieldLabel;
		Ext.each(selection,function(row){
			arrValues.push('['+row.get('id_posicion')+'] ' + row.get('posicion'));
		});
		arrDescription.push({
			label: label
			,values: arrValues
		});

		arrValues     = [];
		var year      = Ext.getCmp(module+'comboAnio_ini').getValue();
		var perIni    = Ext.getCmp(module+'comboDesde_ini').getRawValue();
		var perFin    = Ext.getCmp(module+'comboHasta_ini').getRawValue();

		arrValues.push(year + ' ' + perIni + ' - ' + perFin);
		
		arrDescription.push({
			label: Ext.ux.lang.reports.initialRange
			,values: arrValues
		});

		year      = Ext.getCmp(module+'comboAnio_fin').getValue();
		perIni    = Ext.getCmp(module+'comboDesde_fin').getRawValue();
		perFin    = Ext.getCmp(module+'comboHasta_fin').getRawValue();
		arrValues     = [];

		arrValues.push(year + ' ' + perIni + ' - ' + perFin);
		
		arrDescription.push({
			label: Ext.ux.lang.reports.finalRange
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
		if (!isValidCountry(module+'comboPais', module+'comboMercado')) {
			Ext.Msg.show({
				title: Ext.ux.lang.messages.warning
				,msg: Ext.ux.lang.error.empty_country
				,buttons: Ext.Msg.OK
				,icon: Ext.Msg.WARNING
			});
			return false;
		}

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