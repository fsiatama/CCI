Ext.onReady(function(){
	
	new Ext.ux.inactivityMonitor({
		 inactivityTimeout: 1200000 // 1200000 = 20min / 1 min = 60000
	});

	Ext.ns('Ext.ux', 'Ext.ux.bodyMask');
	Ext.ux.bodyMask = new Ext.LoadMask(Ext.getBody(), {msg: Ext.ux.lang.messages.please_wait});
	
	document.oncontextmenu = function(){return false;}
	
	Ext.apply(Ext.form.VTypes, {
		togetherField:function(val, field) {
			var cnt1 = field.getValue().length;
			var cnt2 = 0;
			var field2 = 0;
			if (field.startField) {
				field2 = Ext.getCmp(field.startField);
				cnt2   = field2.getValue().length
			} else if (field.endField) {
				field2 = Ext.getCmp(field.endField);
				cnt2   = field2.getValue().length
			}
			//console.log(cnt1, cnt2, field)
			if (cnt1 == 0 && cnt2 == 0) {
				return false;
			};
			return true;
		}
		,daterange:function(val, field) {
			var date = field.parseDate(val);
			if(!date){
				return true;
			}
			if (field.startDateField && (!this.dateRangeMax || (date.getTime() != this.dateRangeMax.getTime()))) {
				var start = Ext.getCmp(field.startDateField);
				start.setMaxValue(date);
				this.dateRangeMax = date;
				start.validate();
			} 
			else if (field.endDateField && (!this.dateRangeMin || (date.getTime() != this.dateRangeMin.getTime()))) {
				var end = Ext.getCmp(field.endDateField);
				end.setMinValue(date);
				this.dateRangeMin = date;
				end.validate();
			}
			return true;
		}
		,confirmVal:function(val,field) {
			if (field.initialField) {
				var pwd = Ext.getCmp(field.initialField); 
				return (val == pwd.getValue());
			}
			return true;
		}
		,confirmValText:'The value should be the same'
		,confirmValPass: function(val, field) {
			if (field.initialField) {
				var pwd = Ext.getCmp(field.initialField); 
				return (val == pwd.getValue());
			}
			return true;
		}
		,confirmValPassText: 'Passwords do not match'
		,confirmValEmail: function(val, field) {
			if (field.initialField) {
				var pwd = Ext.getCmp(field.initialField); 
				return (val == pwd.getValue());
			}
			return true;
		}
		,confirmValEmailText: 'Your email addresses do not match'
		,password: function(val, field) {
			if (!/[0-9]/.test(val)) {
				return false;
			}
			else if (!/[a-z]/.test(val)) {
				return false;
			}
			else if (!/[A-Z]/.test(val)) {
				return false;
			}
			else{
				return true
			}
			
		}
		,passwordText: 'Not a valid Password. Must contain at least one uppercase letter, one lowercase letter and at least one number.'
	});
	Ext.override(Ext.grid.ColumnModel, {
		destroy : function(){
			for(var i = 0, len = this.config.length; i < len; i++){
				Ext.destroy(this.config[i]);
			}
			this.purgeListeners();
		}
	});
	
	
	//resuelve el problema de los itemselector con jsonstore el cual no dejaba cargar los datos en el segundo dataview y tampoco permitia ordenar los items
	Ext.override( Ext.ux.ItemSelector, {
		getValue: function() {
			var retVal = this.hiddenField.dom.value;
			return (retVal != '' ) ? retVal.split(','):[];
		}
		,validate: function() {
			var cnt = this.getValue().length;
			if ('undefined' != this.toMultiselect.minSelections) {
				if (cnt < this.toMultiselect.minSelections) {
					this.toMultiselect.markInvalid(this.toMultiselect.minSelections + ' or more items must be selected');
					return false;
				}
			}
			if ('undefined' != this.toMultiselect.allowBlank && this.toMultiselect.allowBlank == false) {
				if (cnt == 0) {
					this.toMultiselect.markInvalid('At least 1 item must be selected');
					return false;
				}
			}
			this.toMultiselect.clearInvalid();
			return true;
		},
		setValue: function(val){
			this.reset();
			if (!val) return;
			val = val instanceof Array ? val : val.split(this.delimiter);
			var rec, i, id;
			for (i = 0; i < val.length; i++) {
				var vf = this.fromMultiselect.valueField;
				var df = this.fromMultiselect.displayField;
				id = val[i];
				idx = this.toMultiselect.view.store.findBy(function(record){                
					return record.data[vf] == id;
				});
				if (idx != -1) continue;            
				idx = this.fromMultiselect.view.store.findBy(function(record){
					return record.data[vf] == id;
				});
				rec = this.fromMultiselect.view.store.getAt(idx);
				if (rec) {
					this.toMultiselect.view.store.add(rec);
					this.fromMultiselect.view.store.remove(rec);                                
				}
			}
		},		
		onRender: function(ct, position){
			Ext.ux.form.ItemSelector.superclass.onRender.call(this, ct, position);
	
			// Internal default configuration for both multiselects
			var msConfig = [{
				legend: 'Available',
				draggable: true,
				droppable: true,
				width: 100,
				height: 100
			},{
				legend: 'Selected',
				droppable: true,
				draggable: true,
				width: 100,
				height: 100
			}];
	
			this.fromMultiselect = new Ext.ux.form.MultiSelect(Ext.applyIf(this.multiselects[0], msConfig[0]));
			this.fromMultiselect.on('dblclick', this.onRowDblClick, this);
	
			this.toMultiselect = new Ext.ux.form.MultiSelect(Ext.applyIf(this.multiselects[1], msConfig[1]));
			this.toMultiselect.on('dblclick', this.onRowDblClick, this);
			
			this.toMultiselect.displayField = this.fromMultiselect.displayField; // FIX
			this.toMultiselect.valueField = this.fromMultiselect.valueField;     // FIX
	
			var p = new Ext.Panel({
				bodyStyle:this.bodyStyle,
				border:this.border,
				layout:"table",
				layoutConfig:{columns:3}
			});
	
			p.add(this.fromMultiselect);
			var icons = new Ext.Panel({header:false});
			p.add(icons);
			p.add(this.toMultiselect);
			p.render(this.el);
			icons.el.down('.'+icons.bwrapCls).remove();
	
			// ICON HELL!!!
			if (this.imagePath!="" && this.imagePath.charAt(this.imagePath.length-1)!="/")
				this.imagePath+="/";
			this.iconUp = this.imagePath + (this.iconUp || 'up2.gif');
			this.iconDown = this.imagePath + (this.iconDown || 'down2.gif');
			this.iconLeft = this.imagePath + (this.iconLeft || 'left2.gif');
			this.iconRight = this.imagePath + (this.iconRight || 'right2.gif');
			this.iconTop = this.imagePath + (this.iconTop || 'top2.gif');
			this.iconBottom = this.imagePath + (this.iconBottom || 'bottom2.gif');
			var el=icons.getEl();
			this.toTopIcon = el.createChild({tag:'img', src:this.iconTop, style:{cursor:'pointer', margin:'2px'}});
			el.createChild({tag: 'br'});
			this.upIcon = el.createChild({tag:'img', src:this.iconUp, style:{cursor:'pointer', margin:'2px'}});
			el.createChild({tag: 'br'});
			this.addIcon = el.createChild({tag:'img', src:this.iconRight, style:{cursor:'pointer', margin:'2px'}});
			el.createChild({tag: 'br'});
			this.removeIcon = el.createChild({tag:'img', src:this.iconLeft, style:{cursor:'pointer', margin:'2px'}});
			el.createChild({tag: 'br'});
			this.downIcon = el.createChild({tag:'img', src:this.iconDown, style:{cursor:'pointer', margin:'2px'}});
			el.createChild({tag: 'br'});
			this.toBottomIcon = el.createChild({tag:'img', src:this.iconBottom, style:{cursor:'pointer', margin:'2px'}});
			this.toTopIcon.on('click', this.toTop, this);
			this.upIcon.on('click', this.up, this);
			this.downIcon.on('click', this.down, this);
			this.toBottomIcon.on('click', this.toBottom, this);
			this.addIcon.on('click', this.fromTo, this);
			this.removeIcon.on('click', this.toFrom, this);
			if (!this.drawUpIcon || this.hideNavIcons) { this.upIcon.dom.style.display='none'; }
			if (!this.drawDownIcon || this.hideNavIcons) { this.downIcon.dom.style.display='none'; }
			if (!this.drawLeftIcon || this.hideNavIcons) { this.addIcon.dom.style.display='none'; }
			if (!this.drawRightIcon || this.hideNavIcons) { this.removeIcon.dom.style.display='none'; }
			if (!this.drawTopIcon || this.hideNavIcons) { this.toTopIcon.dom.style.display='none'; }
			if (!this.drawBotIcon || this.hideNavIcons) { this.toBottomIcon.dom.style.display='none'; }
	
			var tb = p.body.first();
			this.el.setWidth(p.body.first().getWidth());
			p.body.removeClass();
	
			this.hiddenName = this.name;
			var hiddenTag = {tag: "input", type: "hidden", value: "", name: this.name};
			this.hiddenField = this.el.createChild(hiddenTag);
		} 
	});
	
	Ext.override(Ext.data.Store, {
		listeners:{
			'loadexception': function(proxy, options, response){
				Ext.ux.bodyMask.hide();
				//console.log(proxy, options, response);		
				Ext.MessageBox.show({
					title: '',
					msg: Ext.decode(response.responseText).error,
					buttons: Ext.Msg.OK,
					closable:false,
					icon: Ext.MessageBox.ERROR
				});
			}
			,'exception': function(a,b,c,d,e,f,g){ 	
				//console.log(a,b,c,d,e,f,g);
			}
		}
	});
	
	Ext.override(Ext.data.Connection,{
		listeners:{
			'requestexception': function(conn, response, options){
				//console.log(conn, response, options);
			}
		}
	});
	
	Ext.override(Ext.ux.Plugin.RemoteComponent, {
		listeners:{
			'success': function(response, options){
				if(response.success == false){
					Ext.MessageBox.show({
						title: 'Error',
						msg:response.error,
						buttons: Ext.Msg.OK,
						closable:false,
						icon: Ext.MessageBox.ERROR
					});
					if(response.closeTab && response.tab){
						var tabPanel = Ext.getCmp('tabpanel');
						tabPanel.remove(response.tab, true);
					}					
				}
				/*;*/
			}
		}
	});

});

Ext.ns('Ext.ux', 'Ext.ux.routes');
Ext.ux.routes = {
	 url_index: 'http://cci.local'
}
function unsignedFormat(value){
	if(value > 0){
		value = Ext.util.Format.number(value,'0,0.00');
		return '<span style="color:green;">' + value + '</span>';
	} else {
		value = Ext.util.Format.number(value,'0,0.00');
		return '<span style="color:red;">' + value + '</span>';
	}
	return value;
}
function unsigned4Format(value){
	if(value > 0){
		value = Ext.util.Format.number(value,'0,0.0000');
		return '<span style="color:green;">' + value + '</span>';
	} else {
		value = Ext.util.Format.number(value,'0,0.0000');
		return '<span style="color:red;">' + value + '</span>';
	}
	return value;
}
function unsignedIntegerFormat (value) {
	if(!isNaN(parseInt(value)) && isFinite(value)){
		if(value > 0){
			value = Ext.util.Format.number(value,'0,0');
			return '<span style="color:green;">' + value + '</span>';
		} else {
			value = Ext.util.Format.number(value,'0,0');
			return '<span style="color:red;">' + value + '</span>';
		}
	} else {
		return value;
	}
}
function rateFormat(value){
	if(value > 0){
		value = Ext.util.Format.number(value,'0,0.00');
		return '<span style="color:green;">' + value + '%</span>';
	} else {
		value = Ext.util.Format.number(value,'0,0.00');
		return '<span style="color:red;">' + value + '%</span>';
	}
	return value;
}
function numberFormat (value, decimals) {
	if(!isNaN(parseFloat(value)) && isFinite(value)){
		if(decimals){
			return Ext.util.Format.number(value,'0,0.00');
		} else{
			return Ext.util.Format.number(value,'0,0');
		}
	}
	else{
		return value;
	}
}
function integerFormat (value) {
	if(!isNaN(parseInt(value)) && isFinite(value)){
		return Ext.util.Format.number(value,'0,0');
	} else {
		return value;
	}
}
//valida en conjunto que exista un pais o un mercado seleccionado
function isValidCountry (fieldId1, fieldId2) {
	var field1 = Ext.getCmp(fieldId1);
	var field2 = Ext.getCmp(fieldId2);
	if (!field1 || !field2) {
		return false;
	}
	var cnt1 = field1.getValue().length;
	var cnt2 = field2.getValue().length;
	if (cnt1 == 0 && cnt2 == 0) {
		return false;
	}
	return true;
}

