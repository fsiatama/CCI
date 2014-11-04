(function(){
	
	var ds = new Ext.data.Store({
        proxy: new Ext.data.HttpProxy({
            url: 'prueba.php'
        }),
		autoLoad: true,
        reader: new Ext.data.JsonReader({
            root: 'listado',
            id: 'nombre'
        }, [
            {name: 'nombre', mapping: 'nombre'},
            {name: 'apellido', mapping: 'apellido'}
        ])
    });
	
	var formulario = new Ext.form.FormPanel({
		 xtype: 'form'
		,baseCls: 'x-panel-mc'
		,items: [{
			 xtype: 'textfield'
			,anchor: '80%'
			,fieldLabel: 'Nombre'
		},{
			 xtype: 'combo'
			,anchor: '80%'
			,fieldLabel: 'Listado'
			,store: ds
			,triggerAction: 'all'
			,mode: 'local'
			,displayField: 'nombre'
			,valueField: 'apellido'
		}]
	});
	
	return formulario;
	
})()