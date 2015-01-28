<?php
$disable_acumulado_pais = (empty($acuerdo_mercado_id)) ? 'true' : 'false' ;
$acuerdo_descripcion = Inflector::compress($acuerdo_descripcion);
?>
/*<script>*/
(function(){
	Ext.form.Field.prototype.msgTarget = 'side';
	var module = '<?= $module; ?>';

	/*********************************************** contingente Form ***********************************************/

	var formContingente = new Ext.FormPanel({
		baseCls:'x-plain'
		,id:module + 'formContingente'
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
				{name:'contingente_id', mapping:'contingente_id', type:'float'},
				{name:'contingente_id_pais', mapping:'contingente_id_pais', type:'float'},
				{name:'contingente_mcontingente', mapping:'contingente_mcontingente', type:'string'},
				{name:'contingente_desc', mapping:'contingente_desc', type:'string'},
				{name:'contingente_msalvaguardia', mapping:'contingente_msalvaguardia', type:'string'},
				{name:'contingente_salvaguardia_sobretasa', mapping:'contingente_salvaguardia_sobretasa', type:'float'},
				{name:'contingente_acuerdo_det_id', mapping:'contingente_acuerdo_det_id', type:'float'},
				{name:'contingente_acuerdo_det_acuerdo_id', mapping:'contingente_acuerdo_det_acuerdo_id', type:'float'},
				{name:'alerta_id', mapping:'alerta_id', type:'float'},
				{name:'alerta_contingente_verde', mapping:'alerta_contingente_verde', type:'float'},
				{name:'alerta_contingente_amarilla', mapping:'alerta_contingente_amarilla', type:'float'},
				{name:'alerta_contingente_roja', mapping:'alerta_contingente_roja', type:'float'},
				{name:'alerta_salvaguardia_verde', mapping:'alerta_salvaguardia_verde', type:'float'},
				{name:'alerta_salvaguardia_amarilla', mapping:'alerta_salvaguardia_amarilla', type:'float'},
				{name:'alerta_salvaguardia_roja', mapping:'alerta_salvaguardia_roja', type:'float'},
				{name:'alerta_emails', mapping:'alerta_emails', type:'string'},
			]
		})
		,items:[{
			xtype:'fieldset'
			,title:'<?= Lang::get('contingente.table_name'); ?>'
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
				defaults:{anchor:'88%',border:false}
				,items:[{
					xtype:'radiogroup'
					,fieldLabel:'<?= Lang::get('contingente.columns_title.contingente_mcontingente'); ?>'
					,id:module+'contingente_mcontingente'
					,allowBlank:false
					,items: [{
						boxLabel:Ext.ux.lang.form.radioBtnYes
						,inputValue:1
						,name:'contingente_mcontingente'
					},{
						boxLabel:Ext.ux.lang.form.radioBtnNo
						,checked:true
						,inputValue:0
						,name:'contingente_mcontingente'
					}]
					,listeners:{
						'change': {
							fn: function(radio, checked){
								if(checked){
									var disable = (checked.inputValue == '1')?false:true;

									Ext.getCmp(module+'alerta_contingente_verde').setDisabled(disable);
									Ext.getCmp(module+'alerta_contingente_amarilla').setDisabled(disable);
									Ext.getCmp(module+'alerta_contingente_roja').setDisabled(disable);
									var radio = Ext.getCmp(module+'contingente_msalvaguardia');
									radio.setDisabled(disable);
									if(disable){
										radio.setValue([0]).fireEvent('change', radio, radio.getValue() );

										Ext.getCmp(module+'alerta_contingente_verde').setValue(0);
										Ext.getCmp(module+'alerta_contingente_amarilla').setValue(0);
										Ext.getCmp(module+'alerta_contingente_roja').setValue(0);
									}
								}
							}
						}
					}
				},{
					html:'<div class="bootstrap-styles"><p class="text-danger"><?= Lang::get('contingente.alerts.contingente_mcontingente'); ?></p></div>'
				}]
			},{
				defaults:{anchor:'98%'}
				,items:[{
					xtype: 'textarea'
					,id: module+'contingente_desc'
					,name: 'contingente_desc'
					,fieldLabel: '<?= Lang::get('contingente.columns_title.contingente_desc'); ?>'
					,allowBlank: true
					,enableKeyEvents: true
					,grow: true
					,growMin: 60
					,growMax: 100
				}]
			}]
		},{
			xtype:'fieldset'
			,title:'<?= Lang::get('alerta.alerta_contingente'); ?>'
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
				,columnWidth:.33
				,items:[{
					xtype:'numberfield'
					,name:'alerta_contingente_verde'
					,fieldLabel:'<?= Lang::get('alerta.columns_title.alerta_contingente_verde'); ?>'
					,id:module+'alerta_contingente_verde'
					,allowBlank:false
					,maxValue: 100
					,minValue: 1
				}]
			},{
				defaults:{anchor:'100%'}
				,columnWidth:.33
				,items:[{
					xtype:'numberfield'
					,name:'alerta_contingente_amarilla'
					,fieldLabel:'<?= Lang::get('alerta.columns_title.alerta_contingente_amarilla'); ?>'
					,id:module+'alerta_contingente_amarilla'
					,allowBlank:false
					,maxValue: 100
					,minValue: 1
				}]
			},{
				defaults:{anchor:'100%'}
				,columnWidth:.33
				,items:[{
					xtype:'numberfield'
					,name:'alerta_contingente_roja'
					,fieldLabel:'<?= Lang::get('alerta.columns_title.alerta_contingente_roja'); ?>'
					,id:module+'alerta_contingente_roja'
					,allowBlank:false
					,maxValue: 100
					,minValue: 1
				}]
			}]
		},{
			xtype:'fieldset'
			,title:'<?= Lang::get('contingente.salvaguardia'); ?>'
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
				defaults:{anchor:'88%',border:false}
				,items:[{
					xtype:'radiogroup'
					,fieldLabel:'<?= Lang::get('contingente.columns_title.contingente_msalvaguardia'); ?>'
					,id:module+'contingente_msalvaguardia'
					,allowBlank:false
					,items: [{
						boxLabel:Ext.ux.lang.form.radioBtnYes
						,inputValue:1
						,name:'contingente_msalvaguardia'
					},{
						boxLabel:Ext.ux.lang.form.radioBtnNo
						,checked:true
						,inputValue:0
						,name:'contingente_msalvaguardia'
					}]
					,listeners:{
						'change': {
							fn: function(radio, checked){
								if(checked){
									var disable = (checked.inputValue == '1')?false:true;

									Ext.getCmp(module+'alerta_salvaguardia_verde').setDisabled(disable);
									Ext.getCmp(module+'alerta_salvaguardia_amarilla').setDisabled(disable);
									Ext.getCmp(module+'alerta_salvaguardia_roja').setDisabled(disable);
									Ext.getCmp(module+'contingente_salvaguardia_sobretasa').setDisabled(disable);

									if(disable){
										Ext.getCmp(module+'contingente_salvaguardia_sobretasa').setValue('');
										Ext.getCmp(module+'contingente_salvaguardia_sobretasa').clearInvalid();
										Ext.getCmp(module+'alerta_salvaguardia_verde').setValue(0);
										Ext.getCmp(module+'alerta_salvaguardia_verde').clearInvalid();
										Ext.getCmp(module+'alerta_salvaguardia_amarilla').setValue(0);
										Ext.getCmp(module+'alerta_salvaguardia_amarilla').clearInvalid();
										Ext.getCmp(module+'alerta_salvaguardia_roja').setValue(0);
										Ext.getCmp(module+'alerta_salvaguardia_roja').clearInvalid();
									}
								}
							}
						}
					}
				}]
			},{
				defaults:{anchor:'98%'}
				,items:[{
					xtype: 'numberfield'
					,id: module+'contingente_salvaguardia_sobretasa'
					,name: 'contingente_salvaguardia_sobretasa'
					,fieldLabel: '<?= Lang::get('contingente.columns_title.contingente_salvaguardia_sobretasa'); ?>'
					,allowBlank: false
					,maxValue: 100
					,minValue: 1
				}]
			},{
				xtype:'hidden'
				,name:'contingente_id'
			},{
				xtype:'hidden'
				,name:'alerta_id'
			},{
				xtype:'hidden'
				,name:'contingente_id_pais'
			},{
				xtype:'hidden'
				,name:'contingente_acuerdo_det_id'
			},{
				xtype:'hidden'
				,name:'contingente_acuerdo_det_acuerdo_id'
			}]
		},{
			xtype:'fieldset'
			,title:'<?= Lang::get('alerta.alerta_salvaguardia'); ?>'
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
				,columnWidth:.33
				,items:[{
					xtype:'numberfield'
					,name:'alerta_salvaguardia_verde'
					,fieldLabel:'<?= Lang::get('alerta.columns_title.alerta_salvaguardia_verde'); ?>'
					,id:module+'alerta_salvaguardia_verde'
					,allowBlank:false
					,maxValue: 100
					,minValue: 1
				}]
			},{
				defaults:{anchor:'100%'}
				,columnWidth:.33
				,items:[{
					xtype:'numberfield'
					,name:'alerta_salvaguardia_amarilla'
					,fieldLabel:'<?= Lang::get('alerta.columns_title.alerta_salvaguardia_amarilla'); ?>'
					,id:module+'alerta_salvaguardia_amarilla'
					,allowBlank:false
					,maxValue: 100
					,minValue: 1
				}]
			},{
				defaults:{anchor:'100%'}
				,columnWidth:.33
				,items:[{
					xtype:'numberfield'
					,name:'alerta_salvaguardia_roja'
					,fieldLabel:'<?= Lang::get('alerta.columns_title.alerta_salvaguardia_roja'); ?>'
					,id:module+'alerta_salvaguardia_roja'
					,allowBlank:false
					,maxValue: 100
					,minValue: 1
				}]
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
	formContingente.on('show', function(){
		formContingente.form.load({
			 url: 'contingente/listId'
			,params:{
				 contingente_id: '$contingente_id'
				,id: '$id'
			}
			,method: 'POST'
			,waitTitle:'Loading......'
			,waitMsg: 'Loading......'
			,success: function(formulario, response) {

				var radio = Ext.getCmp(module+'contingente_mcontingente');
				var value = response.result.data.contingente_mcontingente;
				radio.setValue([value]).fireEvent('change', radio, radio.getValue() );

				radio = Ext.getCmp(module+'contingente_msalvaguardia');
				value = response.result.data.contingente_msalvaguardia;
				radio.setValue([value]).fireEvent('change', radio, radio.getValue() );
			}
		});
	});
	";
	}
	?>

	return formContingente;


	/*********************************************** Start functions***********************************************/

	function fnCloseTab(){
		var tabs = Ext.getCmp('tabpanel');
		tabs.remove(tabs.activeTab, true);
	}

	function fnSave () {
		if(formContingente.form.isValid()){
			params = {
				id: '<?= $id; ?>'
			};
			formContingente.getForm().submit({
				waitMsg: 'Saving....'
				,waitTitle:'Wait please...'
				,url:'contingente/<?= $action; ?>'
				,params: params
				,success: function(form, action){
					if(Ext.getCmp('<?= $parent; ?>gridContingente')){
						Ext.getCmp('<?= $parent; ?>gridContingente').getStore().reload();
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