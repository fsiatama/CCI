Ext.ux.MonthYearPicker = Ext.extend(Ext.form.DateField, {
    initComponent:function() {
        Ext.apply(this, {
            monthPickerHasTheListeners: false            
        });
        Ext.ux.MonthYearPicker.superclass.initComponent.apply(this, arguments);        
    },
    
    onTriggerClick: function() {
        Ext.ux.MonthYearPicker.superclass.onTriggerClick.apply(this, arguments);
        this.showMonthYear();
    },
    
    setNewValue: function(date) {
        var picker = this.menu.picker;
        picker.update(date);
        picker.hideMonthPicker();
        picker.setValue(date);
        picker.fireEvent('select', picker, picker.value);
    },
    
    showMonthYear: function() {
        var picker = this.menu.picker,
            firstOfMonth = 1;
        picker.showMonthPicker();
        if (!this.monthPickerHasTheListeners) {
            picker.mon(picker.monthPicker, 'click', function(e, t) {
                e.stopEvent();
                var el = new Ext.Element(t), pn;
                if (el.is('button.x-date-mp-cancel')) {
                    picker.hideMonthPicker();
                    this.menu.hide();
                } else if (el.is('button.x-date-mp-ok')) {
                    var d = new Date(picker.mpSelYear, picker.mpSelMonth, firstOfMonth);                    
                    this.setNewValue(d);
                }
            }, this);
            
            picker.mon(picker.monthPicker, 'dblclick', function(e, t) {
                e.stopEvent();
                var el = new Ext.Element(t), pn;
                if ((pn = el.up('td.x-date-mp-month', 2))) {
                    var d = new Date(picker.mpSelYear, pn.dom.xmonth, firstOfMonth);
                    this.setNewValue(d);
                } else if ((pn = el.up('td.x-date-mp-year', 2))) {
                    var d = new Date(pn.dom.xyear, picker.mpSelMonth, firstOfMonth);
                    this.setNewValue(d);
                }
            }, this);
            
            this.monthPickerHasTheListeners = true;
        }
    }    
});