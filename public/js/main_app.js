Ext.BLANK_IMAGE_URL = 'js/vendor/ext-3.4.0/resources/images/default/s.gif';

Ext.onReady(function(){

	//Ext.namespace('sicex');

	Ext.state.Manager.setProvider(new Ext.state.CookieProvider);

	Ext.QuickTips.init();

	Ext.form.Field.prototype.msgTarget = 'under';

	var norte = new Header();
	var sur = new Footer();
	var oeste = new Left();
	var centro = new Centro();

    var viewport = new Ext.Viewport({
		layout: 'border',
		items: [
			norte
		,
			oeste
		,
			sur
		,
			centro
		]
	});
	using('funciones.js');

	var loadingMask = Ext.get('loading-mask');
    var loading = Ext.get('loading');
    //  Hide loading message
    loading.fadeOut({ duration: 5.2 });
    //  Hide loading mask
    loadingMask.setOpacity(0.6);
    loadingMask.shift({
		xy: loading.getXY(),
		width: loading.getWidth(),
		height: loading.getHeight(),
		remove: true,
		duration: 5,
		opacity: 0.1,
		easing: 'easeOut'
	});


});