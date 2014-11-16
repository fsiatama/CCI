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

	,title:''
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
		,format:'format'
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
		
		this.btgroup = new Ext.ButtonGroup({
			title:this.title
			,items: [{
				iconCls:'icon-excel24'
                ,scale:'medium'
				,menu:[{
					text: Ext.ux.lang.reports.excel2010
					,handler:this.onTriggerExcel.createDelegate(this,['1'])
				},{
					text: Ext.ux.lang.reports.excel97
					,handler:this.onTriggerExcel.createDelegate(this,['2'])
				}]
			/*},{
				iconCls:'icon-pdf24'
                ,scale:'medium'
				,handler:this.onTriggerExcel.createDelegate(this,['3'])
			},{
				iconCls:'icon-txt24'
                ,scale:'medium'
				,handler:this.onTriggerExcel.createDelegate(this,['4'])*/
			}]
		});
		
		tb.addSeparator();
		tb.add(this.btgroup);
		
		// handle position
		if('right' === this.align) {
			tb.addFill();
		}
		else {
			if(0 < tb.items.getCount()) {
				//tb.addSeparator();
			}
		}

	} // eo function onRender
	
	/**
	 * private Search Trigger click handler (executes the search, local or remote)
	 */
	,onTriggerExcel:function(val){
				
		var cm = this.grid.colModel;
		var store = this.grid.store;
		
		var parametros = new Object();
		var columnas=new Object();
		Ext.iterate(cm.columns, function(key, value) {
			if((key.hidden == undefined || key.hidden == false) && key.header != ''){
				columnas[key.dataIndex] = key.header;
			}
		}, this);
		
		parametros = store.baseParams;
		
		var sortInfo = store.getSortState();
		if(sortInfo){
			parametros['sort'] = sortInfo.field;
			parametros['dir']  = sortInfo.direction;
		}
		//console.log(sortInfo);
		// add fields and query to baseParams of store
		delete(parametros[this.paramNames.fields]);
		delete(parametros[this.paramNames.format]);
		delete(parametros[this.paramNames.limit]);
		delete(parametros[this.paramNames.start]);
		
		if(columnas){
			parametros[this.paramNames.fields] = Ext.encode(columnas);
			parametros[this.paramNames.format] = val;
			parametros[this.paramNames.limit] = Ext.util.lang.reports.maxRows;
		}
		
		Ext.Ajax.request({
			url: store.proxy.url
			,method:'POST'
			,scope:this
			,timeout: 100000000
			,params: parametros
			,success: function(response){
				results = Ext.decode(response.responseText);
				if(results.success){
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
						,src: 'download-excel/'+results.msg+'/'
					});
				}
				else{
					if (results.msg) {
						Ext.MessageBox.show({
							title: ''
							,msg:results.msg
							,buttons: Ext.Msg.OK
							,closable:false
							,icon: Ext.MessageBox.ERROR
						});
					}
				}
				delete(store.baseParams[this.paramNames.fields]);
				delete(store.baseParams[this.paramNames.format]);
				delete(store.baseParams[this.paramNames.limit]);
			 }
			 ,failure: function(response){
				results = Ext.decode(response.responseText);
				if (results.msg) {
					Ext.Msg.alert('Infomation',results.msg);
				}
			 }
		 });
		
		
	} // eo function onTriggerSearch
	
}); // eo extend

// eof
