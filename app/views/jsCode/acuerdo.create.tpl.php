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
				{name:'acuerdo_fvigente', mapping:'acuerdo_fvigente', type:'string'},
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
				,columnWidth:.7
				,items:[{
					xtype:'textfield'
					,name:'acuerdo_nombre'
					,fieldLabel:'<?= Lang::get('acuerdo.columns_title.acuerdo_nombre'); ?>'
					,id:module+'acuerdo_nombre'
					,allowBlank:false
				}]
			},{
				defaults:{anchor:'93%'}
				,columnWidth:.3
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
			},{
				defaults:{anchor:'98%'}
				,items:[comboPais]
			},{
				defaults:{anchor:'98%'}
				,items:[comboMercado]
			},{
				xtype:'hidden'
				,name:'acuerdo_id'
				,id:module+'acuerdo_id'
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
		if (!isValidCountry(module+'comboPais', module+'comboMercado')) {
			Ext.Msg.show({
				title: Ext.ux.lang.messages.warning
				,msg: Ext.ux.lang.error.empty_country
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