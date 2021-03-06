<?php
$disable_acumulado_pais = (empty($acuerdo_mercado_id)) ? 'true' : 'false' ;
$acuerdo_descripcion = Inflector::compress($acuerdo_descripcion);
?>
/*<script>*/
(function(){
	Ext.form.Field.prototype.msgTarget = 'side';
	var module = '<?= $module; ?>';

	/*********************************************** acuerdo_det Form ***********************************************/
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
		,fieldLabel:'<?= Lang::get('acuerdo_det.columns_title.acuerdo_det_productos'); ?>'
		,name:'acuerdo_det_productos[]'
		,store:storePosicion
		,displayField:'posicion'
		,valueField:'id_posicion'
		,tpl: resultTplPosicion
		,displayFieldTpl:'({id_posicion}) - {posicion}'
		//,allowBlank:true
		,plugins:[new Ext.ux.FieldHelp('<?= Lang::get('indicador.reports.posicion_help'); ?>')]
		,listeners:{
			'beforequery':{
				fn: function(queryEvent) {
					var store = this.getStore();
					store.setBaseParam('selected', this.getValue());
				}
			}
		}
	});

	var formAcuerdo_det = new Ext.FormPanel({
		baseCls:'x-plain'
		,id:module + 'formAcuerdo_det'
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
				{name:'acuerdo_det_id', mapping:'acuerdo_det_id', type:'float'},
				{name:'acuerdo_det_arancel_base', mapping:'acuerdo_det_arancel_base', type:'float'},
				{name:'acuerdo_det_productos', mapping:'acuerdo_det_productos', type:'string'},
				{name:'acuerdo_det_productos_desc', mapping:'acuerdo_det_productos_desc', type:'string'},
				{name:'acuerdo_det_administracion', mapping:'acuerdo_det_administracion', type:'string'},
				{name:'acuerdo_det_administrador', mapping:'acuerdo_det_administrador', type:'string'},
				{name:'acuerdo_det_nperiodos', mapping:'acuerdo_det_nperiodos', type:'float'},
				{name:'acuerdo_det_acuerdo_id', mapping:'acuerdo_det_acuerdo_id', type:'float'},
				{name:'acuerdo_det_contingente_acumulado_pais', mapping:'acuerdo_det_contingente_acumulado_pais', type:'float'},
				{name:'acuerdo_det_desgravacion_igual_pais', mapping:'acuerdo_det_desgravacion_igual_pais', type:'float'}
			]
		})
		,items:[{
			border:false
			,bodyStyle:'padding:15px;'
			,html: '<div class="bootstrap-styles">' +
				'<div class="page-head">' +
					'<h4 class="nopadding"><i class="styleColor fa fa-tasks"></i> <?= $acuerdo_nombre; ?></h4>' +
					'<div class="clearfix"></div><p><?= $acuerdo_descripcion; ?></p>' +
				'</div>' +
			'</div>'
		},{
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
				,columnWidth:.5
				,items:[{
					xtype:'numberfield'
					,name:'acuerdo_det_arancel_base'
					,fieldLabel:'<?= Lang::get('acuerdo_det.columns_title.acuerdo_det_arancel_base'); ?>'
					,id:module+'acuerdo_det_arancel_base'
					,allowBlank:true
				}]
			},{
				defaults:{anchor:'98%'}
				,columnWidth:.5
				,items:[{
					xtype:'numberfield'
					,name:'acuerdo_det_nperiodos'
					,fieldLabel:'<?= Lang::get('acuerdo_det.columns_title.acuerdo_det_nperiodos'); ?>'
					,id:module+'acuerdo_det_nperiodos'
					,allowBlank:false
		<?php
		if ($action == 'modify') {

			echo "
				},{
					html:'<div class=\"bootstrap-styles\"><p class=\"text-danger\"><small>".Lang::get('acuerdo_det.alerts.change_nperiodos')."</small></p></div>'
					,border:false
			";
		}
		?>
				}]
			},{
				defaults:{anchor:'98%'}
				,items:[{
					xtype: 'textarea'
					,id: module+'acuerdo_det_administracion'
					,name: 'acuerdo_det_administracion'
					,fieldLabel: '<?= Lang::get('acuerdo_det.columns_title.acuerdo_det_administracion'); ?>'
					,allowBlank: true
					,enableKeyEvents: true
					,grow: true
					,growMin: 60
					,growMax: 100
				}]
			},{
				defaults:{anchor:'98%'}
				,items:[{
					xtype: 'textarea'
					,id: module+'acuerdo_det_administrador'
					,name: 'acuerdo_det_administrador'
					,fieldLabel: '<?= Lang::get('acuerdo_det.columns_title.acuerdo_det_administrador'); ?>'
					,allowBlank: true
					,enableKeyEvents: true
					,grow: true
					,growMin: 60
					,growMax: 100
				}]
			},{
				defaults:{anchor:'98%'}
				,items:[{
					xtype: 'textfield'
					,name: 'acuerdo_det_productos_desc'
					,fieldLabel: '<?= Lang::get('acuerdo_det.columns_title.acuerdo_det_productos_desc'); ?>'
					,id: module+'acuerdo_det_productos_desc'
					,allowBlank: false
				}]
			},{
				defaults:{anchor:'98%'}
				,items:[comboPosicion]
			},{
				xtype:'hidden'
				,name:'acuerdo_det_id'
				,id:module+'acuerdo_det_id'
			},{
				xtype:'hidden'
				,name:'acuerdo_det_acuerdo_id'
				,id:module+'acuerdo_det_acuerdo_id'
				,value:'<?= $acuerdo_det_acuerdo_id; ?>'
			}]
		},{
			xtype:'fieldset'
			,title:'<?= Lang::get('contingente.table_name'); ?>'
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
				defaults:{anchor:'88%'}
				,items:[{
					xtype:'radiogroup'
					,fieldLabel:'<?= Lang::get('acuerdo_det.columns_title.acuerdo_det_contingente_acumulado_pais'); ?>'
					,id:module+'acuerdo_det_contingente_acumulado_pais'
					,disabled:<?= $disable_acumulado_pais; ?>
					,allowBlank:false
					,items: [{
						boxLabel:Ext.ux.lang.form.radioBtnYes
						,inputValue:1
						,name:'acuerdo_det_contingente_acumulado_pais'
					},{
						boxLabel:Ext.ux.lang.form.radioBtnNo
						,checked:true
						,inputValue:0
						,name:'acuerdo_det_contingente_acumulado_pais'
					}]
				}]
		<?php
		if ($action == 'modify') {

			echo "
				},{
					html:'<div class=\"bootstrap-styles\"><p class=\"text-danger\"><small>".Lang::get('acuerdo_det.alerts.change_contingente_acumulado_pais')."</small></p></div>'
					,border:false
					,columnWidth:1
			";
		}
		?>
			}]
		},{
			xtype:'fieldset'
			,title:'<?= Lang::get('desgravacion.table_name'); ?>'
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
				defaults:{anchor:'88%'}
				,items:[{
					xtype:'radiogroup'
					,fieldLabel:'<?= Lang::get('acuerdo_det.columns_title.acuerdo_det_desgravacion_igual_pais'); ?>'
					,id:module+'acuerdo_det_desgravacion_igual_pais'
					,disabled:<?= $disable_acumulado_pais; ?>
					,allowBlank:false
					,items: [{
						boxLabel:Ext.ux.lang.form.radioBtnYes
						,inputValue:1
						,name:'acuerdo_det_desgravacion_igual_pais'
					},{
						boxLabel:Ext.ux.lang.form.radioBtnNo
						,checked:true
						,inputValue:0
						,name:'acuerdo_det_desgravacion_igual_pais'
					}]
				}]
		<?php
		if ($action == 'modify') {

			echo "
				},{
					html:'<div class=\"bootstrap-styles\"><p class=\"text-danger\"><small>".Lang::get('acuerdo_det.alerts.change_desgravacion_igual_pais')."</small></p></div>'
					,border:false
					,columnWidth:1
			";
		}
		?>
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
	formAcuerdo_det.on('show', function(){
		formAcuerdo_det.form.load({
			 url: 'acuerdo_det/listId'
			,params:{
				 acuerdo_det_id: '$acuerdo_det_id'
				,id: '$id'
			}
			,method: 'POST'
			,waitTitle:'Loading......'
			,waitMsg: 'Loading......'
			,success: function(formulario, response) {
				Ext.getCmp(module+'comboPosicion').setValue(response.result.data.acuerdo_det_productos);
			}
		});
	});";
	}
	?>

	return formAcuerdo_det;


	/*********************************************** Start functions***********************************************/

	function fnCloseTab(){
		var tabs = Ext.getCmp('tabpanel');
		tabs.remove(tabs.activeTab, true);
	}

	function fnSave () {
		if(formAcuerdo_det.form.isValid()){
			params = {
				id: '<?= $id; ?>'
			};
			formAcuerdo_det.getForm().submit({
				waitMsg: 'Saving....'
				,waitTitle:'Wait please...'
				,url:'acuerdo_det/<?= $action; ?>'
				,params: params
				,success: function(form, action){
					if(Ext.getCmp('<?= $parent; ?>gridAcuerdo_det')){
						Ext.getCmp('<?= $parent; ?>gridAcuerdo_det').getStore().reload();
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