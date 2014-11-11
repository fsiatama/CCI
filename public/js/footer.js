Footer = function(){
	var toolbar = new Ext.Toolbar({
		items: [
			'Colombia Tel�fono: (57)(1) 369 3390 / Estados Unidos y Canad� Tel�fono: (1)(786) 441 0492'
			,'->'
			,'Derechos reservados. Queda expresamente prohibida la reproducci�n total o parcial de la informaci�n contenida en este sitio, incluyendo el m�todo de presentaci�n y generaci�n de datos.'
		]
	});
    Footer.superclass.constructor.call(this, {
         id: 'sur'
        ,region: 'south'
		,height: 21
		,alwaysShowTabs: false
		,items: [toolbar]
		,listeners: {
			 'afterlayout': function(){
				this.body.dom.style.border = '0';
			}
			,scope: this
		}
    });
};

Ext.extend(Footer, Ext.Panel);