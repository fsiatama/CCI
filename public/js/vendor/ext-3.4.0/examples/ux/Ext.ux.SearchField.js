Ext.ux.SearchField = Ext.extend(Ext.form.TwinTriggerField, {
    initComponent : function(){
        Ext.ux.SearchField.superclass.initComponent.call(this);
        this.on('specialkey', function(f, e){
            if(e.getKey() == e.ENTER){
                this.onTrigger2Click();
            }
        }, this);
    },

    validationEvent:false,
    validateOnBlur:false,
    trigger1Class:'x-form-clear-trigger',
    trigger2Class:'x-form-search-trigger',
    hideTrigger1:true,
    width:180,
    hasSearch : false,
    paramName : 'gQuery',

    onTrigger1Click : function(){
        if(this.hasSearch){
            this.el.dom.value = '';
            this.store.baseParams = (this.store.baseParams || {});
            this.store.baseParams[this.paramName] = '';
						this.store.reload({ params:{ start:0, gQuery:'' }, method:'POST' });
            this.triggers[0].hide();
            this.hasSearch = false;
        }
    },

    onTrigger2Click : function(){
        var v = this.getRawValue();
        if(v.length < 1){
            this.onTrigger1Click();
            return;
        }
        this.store.baseParams = (this.store.baseParams || {});
        this.store.baseParams[this.paramName] = v;
				this.store.reload({ params:{ start:0, gQuery:v }, method:'POST' });
        this.hasSearch = true;
        this.triggers[0].show();
    }
});