/*<script>*/
(function(){
	Ext.form.Field.prototype.msgTarget = 'side';
	var module = '<?= $module; ?>';

	var arrYears   = <?= json_encode($yearsAvailable); ?>;
	var arrPeriods = ['1', '2', '3', '4'];

	var simpleCombo = Ext.extend(Ext.form.ComboBox, {
		typeAhead:false
		,forceSelection:true
		,selectOnFocus:true
		,allowBlank:false
		,triggerAction:'all'
		,flex:true
	});

	var comboAnio = new simpleCombo({
		hiddenName:'pib_anio'
		,id:module+'comboAnio'
		,store:arrYears
		,fieldLabel:'<?= Lang::get('pib.columns_title.pib_anio'); ?>'
	});
	var comboPeriodo = new simpleCombo({
		hiddenName:'pib_periodo'
		,id:module+'comboPeriodo'
		,store:arrPeriods
		,fieldLabel:'<?= Lang::get('pib.columns_title.pib_periodo'); ?>'
	});

	var formPib = new Ext.FormPanel({
		baseCls:'x-plain'
		,id:module + 'formPib'
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
				{name:'pib_id', mapping:'pib_id', type:'float'},
				{name:'pib_anio', mapping:'pib_anio', type:'float'},
				{name:'pib_periodo', mapping:'pib_periodo', type:'float'},
				{name:'pib_valor', mapping:'pib_valor', type:'string'}
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
				,items:[comboPeriodo]
			},{
				defaults:{anchor:'100%'}
				,items:[{
					xtype:'numberfield'
					,name:'pib_valor'
					,fieldLabel:'<?= Lang::get('pib.columns_title.pib_valor'); ?>'
					,id:module+'pib_valor'
					,allowBlank:false
				}]
			},{
				xtype:'hidden'
				,name:'pib_id'
				,id:module+'pib_id'
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
	formPib.on('show', function(){
		formPib.form.load({
			 url: 'pib/listId'
			,params:{
				 pib_id: '$pib_id'
				,id: '$id'
			}
			,method: 'POST'
			,waitTitle:'Loading......'
			,waitMsg: 'Loading......'
			,success: function(formulario, response) {
			}
		});
	});";
	}
	?>

	return formPib;


	/*********************************************** Start functions***********************************************/

	function fnCloseTab(){
		var tabs = Ext.getCmp('tabpanel');
		tabs.remove(tabs.activeTab, true);
	}

	function fnSave () {
		if(formPib.form.isValid()){
			params = {
				id: '<?= $id; ?>'
			};
			formPib.getForm().submit({
				waitMsg: 'Saving....'
				,waitTitle:'Wait please...'
				,url:'pib/<?= $action; ?>'
				,params: params
				,success: function(form, action){
					if(Ext.getCmp('<?= $parent; ?>gridPib')){
						Ext.getCmp('<?= $parent; ?>gridPib').getStore().reload();
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