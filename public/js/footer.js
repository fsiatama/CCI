Footer = function(){
	var toolbar = new Ext.Toolbar({
		items: [
			'->'
			,'Derechos reservados. Queda expresamente prohibida la reproducción total o parcial de la información contenida en este sitio, incluyendo el método de presentación y generación de datos.'
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