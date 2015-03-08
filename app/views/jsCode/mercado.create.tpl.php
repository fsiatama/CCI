/*<script>*/
(function(){
	Ext.form.Field.prototype.msgTarget = 'side';
	var module = '<?= $module; ?>';

	var Combo = Ext.extend(Ext.ux.form.SuperBoxSelect, {
		xtype:'superboxselect'
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
		,fieldLabel:'<?= Lang::get('mercado.columns_title.mercado_paises'); ?>'
		,name:'mercado_paises[]'
		,store:storePais
		,displayField:'pais'
		,valueField:'id_pais'
		,tpl: resultTplPais
		,displayFieldTpl:'{pais}'
	});

	var formMercado = new Ext.FormPanel({
		autoScroll:true
		,autoWidth:true
		,baseCls:'x-plain'
		,bodyStyle:	'padding:15px;position:relative;'
		,buttonAlign:'center'
		,fileUpload:true
		,id:module + 'formMercado'
		,method:'POST'
		,monitorValid:true
		,trackResetOnLoad:true
		,reader: new Ext.data.JsonReader({
			root:'data'
			,totalProperty:'total'
			,fields:[
				{name:'mercado_id', mapping:'mercado_id', type:'float'},
				{name:'mercado_nombre', mapping:'mercado_nombre', type:'string'},
				{name:'mercado_paises', mapping:'mercado_paises', type:'string'},
				{name:'mercado_bandera', mapping:'mercado_bandera', type:'string'},
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
				,items:[{
					xtype:'textfield'
					,name:'mercado_nombre'
					,fieldLabel:'<?= Lang::get('mercado.columns_title.mercado_nombre'); ?>'
					,id:module+'mercado_nombre'
					,allowBlank:false
				}]
			},{
				defaults:{anchor:'100%'}
				,items:[comboPais]
			/*},{
				items:[{
					xtype:'panel'
					,frame:true
					,title:'<?= Lang::get('mercado.columns_title.mercado_bandera'); ?>'
					,items:[{
						bodyStyle: 'padding: 10px 10px 0 10px;'
						,html:'<div id="firma-button-msg1" style="display:none;"></div>'+
							 '<p style="text-align:center;">'+
							 '<img class="img-polaroid" id="firma" src="" '+
							 'width="300" height="100" />'+
							 '</p>'
					},{
						xtype:'panel'
						,layout:'fit'
						,bodyStyle:'padding: 5px 20px 0 10px;'
						,plugins:[new Ext.ux.FieldHelp('<?= '.......'; ?>')]
						,items:[{
							xtype:'fileuploadfield'
							,emptyText:'<?= '........'; ?>'
							,hideLabel:true
							,name:'usuario_firma'
							,id:module+'usuario_firma'
							,allowBlank:false
							,listeners: {
								'fileselected':function(fb, v){
									var el = Ext.fly('firma-button-msg1');
									el.update('<b><?= '.....'; ?>:</b> '+v);
									if(!el.isVisible()){
										el.slideIn('t', {
											duration: .2,
											easing: 'easeIn',
											callback: function(){
												var el = Ext.fly('firma-button-msg1');
												el.highlight();
											}
										});
									}else{
										el.highlight();
									}
								}
							}
					   }]
					}]
				}]*/
			},{
				xtype:'hidden'
				,name:'mercado_id'
				,id:module+'mercado_id'
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
	formMercado.on('show', function(){
		formMercado.form.load({
			 url: 'mercado/listId'
			,params:{
				 mercado_id: '$mercado_id'
				,id: '$id'
			}
			,method: 'POST'
			,waitTitle:'Loading......'
			,waitMsg: 'Loading......'
			,success: function(formulario, response) {
				Ext.getCmp(module+'comboPais').setValue(response.result.data.mercado_paises);
			}
		});
	});";
	}
	?>

	return formMercado;


	/*********************************************** Start functions***********************************************/

	function fnCloseTab(){
		var tabs = Ext.getCmp('tabpanel');
		tabs.remove(tabs.activeTab, true);
	}

	function fnSave () {
		if(formMercado.form.isValid()){
			params = {
				id: '<?= $id; ?>'
			};
			formMercado.getForm().submit({
				waitMsg: 'Saving....'
				,waitTitle:'Wait please...'
				,url:'mercado/<?= $action; ?>'
				,params: params
				,success: function(form, action){
					if(Ext.getCmp('<?= $parent; ?>gridMercado')){
						Ext.getCmp('<?= $parent; ?>gridMercado').getStore().reload();
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