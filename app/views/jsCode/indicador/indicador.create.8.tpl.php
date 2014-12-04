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
		,allowBlank:false
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

	var arrTrade = <?= json_encode($trade); ?>;

	var simpleCombo = Ext.extend(Ext.form.ComboBox, {
		typeAhead:false
		,forceSelection:true
		,selectOnFocus:true
		,allowBlank:false
		,triggerAction:'all'
		,flex:true
	});

	var comboIntercambio = new simpleCombo({
		hiddenName:'intercambio'
		,id:module+'comboIntercambio'
		,store:arrTrade
		,fieldLabel:Ext.ux.lang.reports.trade
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
				{name:'intercambio', mapping:'intercambio', type:'string'},
				{name:'anio_ini', mapping:'anio_ini', type:'float'},
				{name:'desde_ini', mapping:'desde_ini', type:'float'},
				{name:'hasta_ini', mapping:'hasta_ini', type:'float'},
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
				,items:[comboDesde_ini]
			},{
				defaults:{anchor:'100%'}
				,items:[comboHasta_ini]
			},{
				defaults:{anchor:'100%'}
				,items:[comboIntercambio]
			},{
				defaults:{anchor:'100%'}
				,columnWidth:1
				,items:[comboPais]
			}]
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

		var trade     = Ext.getCmp(module+'comboIntercambio').getRawValue();
		arrValues     = [];
		arrValues.push(trade);
		arrDescription.push({
			label: Ext.ux.lang.reports.trade
			,values: arrValues
		});

		var year      = Ext.getCmp(module+'comboAnio_ini').getValue();
		var perIni    = Ext.getCmp(module+'comboDesde_ini').getRawValue();
		var perFin    = Ext.getCmp(module+'comboHasta_ini').getRawValue();
		arrValues     = [];

		arrValues.push(year + ' ' + perIni + ' - ' + perFin);
		
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