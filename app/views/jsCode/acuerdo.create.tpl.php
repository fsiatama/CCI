<?php
$acuerdo_id = ($action == 'modify') ? $acuerdo_id : '' ;
?>
/*<script>*/
(function(){
	Ext.form.Field.prototype.msgTarget = 'side';
	var module = '<?= $module; ?>';

	/*********************************************** acuerdo Form ***********************************************/
	var Combo = Ext.extend(Ext.ux.form.SuperBoxSelect, {
		xtype:'superboxselect'
		,singleMode:true
		,resizable:false
		,anchor:'88%'
		,minChars:2
		,forceSelection:true
		,allowNewData:true
		,extraItemCls:'x-tag'
		//,allowBlank:false
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
			'<div class="search-item x-combo-list-item">' +
				'<span>{pais}</span>' +
			'</div>' +
		'</tpl>'
	);
	var comboPais = new Combo({
		id:module+'comboPais'
		,fieldLabel:'<?= Lang::get('acuerdo.columns_title.acuerdo_id_pais'); ?>'
		,name:'acuerdo_id_pais[]'
		,store:storePais
		,displayField:'pais'
		,valueField:'id_pais'
		,tpl: resultTplPais
		,displayFieldTpl:'{pais}'
		,plugins:[new Ext.ux.FieldHelp('<?= Lang::get('indicador.reports.pais_help'); ?>')]
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
		,fieldLabel:'<?= Lang::get('mercado.columns_title.mercado_nombre'); ?>'
		,name:'acuerdo_mercado_id[]'
		,store:storeMercado
		,displayField:'mercado_nombre'
		,valueField:'mercado_id'
		,tpl: resultTplMercado
		,displayFieldTpl:'{mercado_nombre}'
		,plugins:[new Ext.ux.FieldHelp('<?= Lang::get('indicador.reports.mercado_nombre_help'); ?>')]
	});

	var arrTrade = <?= json_encode($trade); ?>;

	var comboIntercambio = new Ext.form.ComboBox({
		hiddenName:'acuerdo_intercambio'
		,id:module+'comboIntercambio'
		,store:arrTrade
		,fieldLabel:Ext.ux.lang.reports.trade
		,typeAhead:false
		,forceSelection:true
		,selectOnFocus:true
		,allowBlank:false
		,triggerAction:'all'
		,flex:true
	});

	var formAcuerdo = new Ext.FormPanel({
		baseCls:'x-plain'
		,id:module + 'formAcuerdo'
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
				{name:'acuerdo_id', mapping:'acuerdo_id', type:'float'},
				{name:'acuerdo_nombre', mapping:'acuerdo_nombre', type:'string'},
				{name:'acuerdo_descripcion', mapping:'acuerdo_descripcion', type:'string'},
				{name:'acuerdo_intercambio', mapping:'acuerdo_intercambio', type:'string'},
				{name:'acuerdo_fvigente', mapping:'acuerdo_fvigente', type:'string'},
				{name:'acuerdo_ffirma', mapping:'acuerdo_ffirma', type:'string'},
				{name:'acuerdo_ley', mapping:'acuerdo_ley', type:'string'},
				{name:'acuerdo_decreto', mapping:'acuerdo_decreto', type:'string'},
				{name:'acuerdo_url', mapping:'acuerdo_url', type:'string'},
				{name:'acuerdo_tipo_acuerdo', mapping:'acuerdo_tipo_acuerdo', type:'string'},
				{name:'acuerdo_mercado_id', mapping:'acuerdo_mercado_id', type:'float'},
				{name:'acuerdo_id_pais', mapping:'acuerdo_id_pais', type:'float'}
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
				defaults:{anchor:'98%'}
				,items:[{
					xtype:'textfield'
					,name:'acuerdo_nombre'
					,fieldLabel:'<?= Lang::get('acuerdo.columns_title.acuerdo_nombre'); ?>'
					,id:module+'acuerdo_nombre'
					,allowBlank:false
				}]
			},{
				defaults:{anchor:'96%'}
				,columnWidth:.5
				,items:[{
					xtype: 'textfield'
					,name: 'acuerdo_ley'
					,fieldLabel: '<?= Lang::get('acuerdo.columns_title.acuerdo_ley'); ?>'
					,id: module+'acuerdo_ley'
					,allowBlank: true
				}]
			},{
				defaults:{anchor:'96%'}
				,columnWidth:.5
				,items:[{
					xtype: 'textfield'
					,name: 'acuerdo_decreto'
					,fieldLabel: '<?= Lang::get('acuerdo.columns_title.acuerdo_decreto'); ?>'
					,id: module+'acuerdo_decreto'
					,allowBlank: true
				}]
			},{
				defaults:{anchor:'97%'}
				,columnWidth:.33
				,items:[comboIntercambio]
			},{
				defaults:{anchor:'97%'}
				,columnWidth:.33
				,items:[{
					xtype:'datefield'
					,name:'acuerdo_fvigente'
					,fieldLabel:'<?= Lang::get('acuerdo.columns_title.acuerdo_fvigente'); ?>'
					,id:module+'acuerdo_fvigente'
					,allowBlank:false
					,format:'Y-m-d'
					,plugins:[new Ext.ux.FieldHelp(Ext.ux.lang.form.dateFieldHelp)]
				}]
			},{
				defaults:{anchor:'97%'}
				,columnWidth:.33
				,items:[{
					xtype:'datefield'
					,name:'acuerdo_ffirma'
					,fieldLabel:'<?= Lang::get('acuerdo.columns_title.acuerdo_ffirma'); ?>'
					,id:module+'acuerdo_ffirma'
					,allowBlank:true
					,format:'Y-m-d'
					,plugins:[new Ext.ux.FieldHelp(Ext.ux.lang.form.dateFieldHelp)]
				}]
			},{
				defaults:{anchor:'98%'}
				,items:[{
					xtype: 'textfield'
					,name: 'acuerdo_url'
					,fieldLabel: '<?= Lang::get('acuerdo.columns_title.acuerdo_url'); ?>'
					,id: module+'acuerdo_url'
					,allowBlank: true
					,vtype:'url'
				}]
			},{
				defaults:{anchor:'98%'}
				,items:[{
					xtype: 'textarea'
					,id: module+'acuerdo_descripcion'
					,name: 'acuerdo_descripcion'
					,fieldLabel: '<?= Lang::get('acuerdo.columns_title.acuerdo_descripcion'); ?>'
					,allowBlank: true
					,enableKeyEvents: true
					,grow: true
					,growMin: 60
					,growMax: 100
				}]
			}]
		},{
			xtype:'fieldset'
			,title:'<?= Lang::get('acuerdo.partner_title'); ?>'
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
	<?php
	if ($action == 'modify') {

		echo "
				html:'<div class=\"bootstrap-styles\"><p class=\"text-danger\">".Lang::get('acuerdo.alerts.change_partner')."</p></div>'
			},{
		";
	}
	?>
				defaults:{anchor:'98%'}
				,items:[comboPais]
			},{
				defaults:{anchor:'98%'}
				,items:[comboMercado]
			},{
				xtype:'hidden'
				,name:'acuerdo_id'
				,id:module+'acuerdo_id'
			},{
				xtype:'hidden'
				,name:'acuerdo_tipo_acuerdo'
				,id:module+'acuerdo_tipo_acuerdo'
			}]
		/*},{
			xtype:'fieldset'
			,title:Ext.ux.lang.reports.detail
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
				defaults:{anchor:'98%'}
				,items:[gridAcuerdo_det]
			}]*/
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
	formAcuerdo.on('show', function(){
		formAcuerdo.form.load({
			 url: 'acuerdo/listId'
			,params:{
				 acuerdo_id: '$acuerdo_id'
				,id: '$id'
			}
			,method: 'POST'
			,waitTitle:'Loading......'
			,waitMsg: 'Loading......'
			,success: function(formulario, response) {
				Ext.getCmp(module+'comboPais').setValue(response.result.data.acuerdo_id_pais);
				Ext.getCmp(module+'comboMercado').setValue(response.result.data.acuerdo_mercado_id);
			}
		});
	});";
	}
	?>

	return formAcuerdo;


	/*********************************************** Start functions***********************************************/

	function fnCloseTab(){
		var tabs = Ext.getCmp('tabpanel');
		tabs.remove(tabs.activeTab, true);
	}

	function fnSave () {
		if (!isValidComplement(module+'comboPais', module+'comboMercado')) {
			Ext.Msg.show({
				title: Ext.ux.lang.messages.warning
				,msg: '<?= Lang::get('error.acuerdo_invalid_country'); ?>'
				,buttons: Ext.Msg.OK
				,icon: Ext.Msg.WARNING
			});
			return false;
		}
		if(formAcuerdo.form.isValid()){
			params = {
				id: '<?= $id; ?>'
			};
			formAcuerdo.getForm().submit({
				waitMsg: 'Saving....'
				,waitTitle:'Wait please...'
				,url:'acuerdo/<?= $action; ?>'
				,params: params
				,success: function(form, action){
					if(Ext.getCmp('<?= $parent; ?>gridAcuerdo')){
						Ext.getCmp('<?= $parent; ?>gridAcuerdo').getStore().reload();
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