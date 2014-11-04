<?php
//Trae la sesión que esté asignada
session_start();
include($_SESSION['session_diccionario']);
//Variables de configuración del sistema
include ("../../../../lib/config.php");
include ("../../../../lib/lib_sesion.php");
?>
/*<script>*/
Ext.ns('Ext.ux.grid');

Ext.ux.grid.Excel = function(config) {
	Ext.apply(this, config);
	Ext.ux.grid.Excel.superclass.constructor.call(this);
}; // eo constructor

Ext.extend(Ext.ux.grid.Excel, Ext.util.Observable, {
	
	selectAllText:'Select All'

	/**
	 * @cfg {String} position Where to display the search controls. Valid values are top and bottom (defaults to bottom)
	 * Corresponding toolbar has to exist at least with mimimum configuration tbar:[] for position:top or bbar:[]
	 * for position bottom. Plugin does NOT create any toolbar.
	 */
	,position:'bottom'

	
	,width:100

	/**
	 * @cfg {String} xtype xtype is usually not used to instantiate this plugin but you have a chance to identify it
	 */
	,xtype:'gridexcel'

	/**
	 * @cfg {Object} paramNames Params name map (defaults to {fields:'fields', query:'query'}
	 */
	,paramNames: {
		 fields:'fields'
		,formato:'formato'
		,limit:'limit'
		,start:'start'
	}

	/**
	 * @cfg {String} shortcutKey Key to fucus the input field (defaults to r = Sea_r_ch). Empty string disables shortcut
	 */
	,shortcutKey:'e'

	/**
	 * @cfg {String} shortcutModifier Modifier for shortcutKey. Valid values: alt, ctrl, shift (defaults to alt)
	 */
	,shortcutModifier:'alt'

	/**
	 * @cfg {String} align 'left' or 'right' (defaults to 'left')
	 */

	/**
	 * @cfg {Number} minLength force user to type this many character before he can make a search
	 */

	/**
	 * @cfg {Ext.Panel/String} toolbarContainer Panel (or id of the panel) which contains toolbar we want to render
	 * search controls to (defaults to this.grid, the grid this plugin is plugged-in into)
	 */
	
	// {{{
	/**
	 * private
	 * @param {Ext.grid.GridPanel/Ext.grid.EditorGrid} grid reference to grid this plugin is used for
	 */
	,init:function(grid) {
		this.grid = grid;

		// setup toolbar container if id was given
		if('string' === typeof this.toolbarContainer) {
			this.toolbarContainer = Ext.getCmp(this.toolbarContainer);
		}

		// do our processing after grid render and reconfigure
		grid.onRender = grid.onRender.createSequence(this.onRender, this);
		grid.reconfigure = grid.reconfigure.createSequence(this.reconfigure, this);
	} // eo function init
	// }}}
	// {{{
	/**
	 * private add plugin controls to <b>existing</b> toolbar and calls reconfigure
	 */
	,onRender:function() {
		var panel = this.toolbarContainer || this.grid;
		var tb = 'bottom' === this.position ? panel.bottomToolbar : panel.topToolbar;

		this.combo = new Ext.form.ComboBox({
			forceSelection: true
			,triggerAction: 'all'
			,selectOnFocus:true
			,mode: 'local'
			,store:new Ext.data.ArrayStore({
				id: 0
				,fields:['extensionId','extension']
				,data: [
					[1, '<?php print _EXCEL2010; ?>']
					,[2, '<?php print _EXCEL97; ?>']
				]
			})
			,valueField: 'extensionId'
			,displayField: 'extension'
			,value:2
		});
		
		tb.addSeparator();
		tb.add(this.combo);
				
		this.button = new Ext.Button({
		  iconCls: 'icon-excel'
		  ,handler:this.onTriggerExcel.createDelegate(this)
		});

		// handle position
		if('right' === this.align) {
			tb.addFill();
		}
		else {
			if(0 < tb.items.getCount()) {
				tb.addSeparator();
			}
		}

		// add menu button
		tb.add(this.button);		

	} // eo function onRender
	
	/**
	 * private Search Trigger click handler (executes the search, local or remote)
	 */
	,onTriggerExcel:function(){
		if(!this.combo.isValid()) {
			return;
		}
		this.button.disable();
		
		var cm = this.grid.colModel;
		var val = this.combo.getValue();
		var store = this.grid.store;
		
		var parametros = new Object();
		var columnas=new Object();
		Ext.iterate(cm.columns, function(key, value) {
			if((key.hidden == undefined || key.hidden == false) && key.header != ''){
				columnas[key.dataIndex] = key.header;
			}
		}, this);
		
		parametros = store.baseParams;
		
		// add fields and query to baseParams of store
		delete(parametros[this.paramNames.fields]);
		delete(parametros[this.paramNames.formato]);
		delete(parametros[this.paramNames.limit]);
		delete(parametros[this.paramNames.start]);
		
		if(columnas){
			parametros[this.paramNames.fields] = Ext.encode(columnas);
			parametros[this.paramNames.formato] = val;
			parametros[this.paramNames.limit] = <?php print MAXREGEXCEL; ?>;
		}
		
		Ext.Ajax.request({
			url: store.proxy.url
			,method:'POST'
			,scope:this
			,timeout: 100000
			,params: parametros
			,success: function(response){
				results = Ext.decode(response.responseText);
				try {
					Ext.destroy(Ext.get('downloadIframe'));
				}
				catch(e) {}
				Ext.DomHelper.append(document.body, {
					tag: 'iframe'
					,id:'downloadIframe'
					,frameBorder: 0
					,width: 0
					,height: 0
					,css: 'display:none;visibility:hidden;height:0px;'
					,src: '<?php print URL_RAIZ; ?>download-excel.php?archivo='+results.msg
				});
				this.button.enable();
				delete(store.baseParams[this.paramNames.fields]);
				delete(store.baseParams[this.paramNames.formato]);
				delete(store.baseParams[this.paramNames.limit]);
			 }
			 ,failure: function(response){
				results = Ext.decode(response.responseText);
				if (results.msg) {
					Ext.Msg.alert('Infomation',results.msg);
				}
				this.button.enable();
			 }
		 });
		
		
	} // eo function onTriggerSearch
	
}); // eo extend

// eof
