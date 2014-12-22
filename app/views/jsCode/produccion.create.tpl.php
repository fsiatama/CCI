/*<script>*/
(function(){
	Ext.form.Field.prototype.msgTarget = 'side';
	var module = '<?= $module; ?>';

	var arrYears   = <?= json_encode($yearsAvailable); ?>;

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

	var simpleCombo = Ext.extend(Ext.form.ComboBox, {
		typeAhead:false
		,forceSelection:true
		,selectOnFocus:true
		,allowBlank:false
		,triggerAction:'all'
		,flex:true
	});

	var comboAnio = new simpleCombo({
		hiddenName:'produccion_anio'
		,id:module+'comboAnio'
		,store:arrYears
		,fieldLabel:'<?= Lang::get('produccion.columns_title.produccion_anio'); ?>'
	});

	var storeSector = new Ext.data.JsonStore({
		url:'sector/list'
		,id:module+'storeSector'
		,root:'data'
		,sortInfo:{field:'sector_id',direction:'ASC'}
		,totalProperty:'total'
		,baseParams:{id:'<?= $id; ?>'}
		,fields:[
			{name:'sector_id', type:'float'},
			{name:'sector_nombre', type:'string'}
		]
	});
	var resultTplSector = new Ext.XTemplate(
		'<tpl for=".">' +
			'<div class="search-item x-combo-list-item">' +
				'<span>{sector_nombre}</span>' +
			'</div>' +
		'</tpl>'
	);
	var comboSector = new Combo({
		id:module+'comboSector'
		,singleMode:true
		,fieldLabel:'<?= Lang::get('sector.columns_title.sector_nombre'); ?>'
		,name:'produccion_sector_id[]'
		,store:storeSector
		,displayField:'sector_nombre'
		,valueField:'sector_id'
		,tpl: resultTplSector
		,displayFieldTpl:'{sector_nombre}'
	});
	
	var formProduccion = new Ext.FormPanel({
		baseCls:'x-plain'
		,id:module + 'formProduccion'
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
				{name:'produccion_id', mapping:'produccion_id', type:'float'},
				{name:'produccion_sector_id', mapping:'produccion_sector_id', type:'float'},
				{name:'produccion_anio', mapping:'produccion_anio', type:'float'},
				{name:'produccion_peso_neto', mapping:'produccion_peso_neto', type:'float'},
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
				,items:[comboAnio]
			},{
				defaults:{anchor:'100%'}
				,items:[comboSector]
			},{
				defaults:{anchor:'100%'}
				,items:[{
					xtype:'numberfield'
					,name:'produccion_peso_neto'
					,fieldLabel:'<?= Lang::get('produccion.columns_title.produccion_peso_neto'); ?>'
					,id:module+'produccion_peso_neto'
					,allowBlank:false
				}]
			},{
				xtype:'hidden'
				,name:'produccion_id'
				,id:module+'produccion_id'
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
	formProduccion.on('show', function(){
		formProduccion.form.load({
			 url: 'produccion/listId'
			,params:{
				 produccion_id: '$produccion_id'
				,id: '$id'
			}
			,method: 'POST'
			,waitTitle:'Loading......'
			,waitMsg: 'Loading......'
			,success: function(formulario, response) {
				Ext.getCmp(module+'comboSector').setValue(response.result.data.produccion_sector_id);
			}
		});
	});";
	}
	?>

	return formProduccion;


	/*********************************************** Start functions***********************************************/

	function fnCloseTab(){
		var tabs = Ext.getCmp('tabpanel');
		tabs.remove(tabs.activeTab, true);
	}

	function fnSave () {
		if(formProduccion.form.isValid()){
			params = {
				id: '<?= $id; ?>'
			};
			formProduccion.getForm().submit({
				waitMsg: 'Saving....'
				,waitTitle:'Wait please...'
				,url:'produccion/<?= $action; ?>'
				,params: params
				,success: function(form, action){
					if(Ext.getCmp('<?= $parent; ?>gridProduccion')){
						Ext.getCmp('<?= $parent; ?>gridProduccion').getStore().reload();
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