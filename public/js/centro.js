Centro = function(){
	
	var storeBoletinSisduan = new Ext.data.JsonStore({
		url:'proceso/boletines/'
		,root:'datos'
		,sortInfo:{field:'id_boletin',direction:'ASC'}
		,totalProperty:'total'
		,baseParams:{producto:'sisduan',accion:'lista'}
		,autoLoad: true
		,disableCaching: false
		,fields:[
			{name:'id_boletin', type:'float'}
			,{name:'ano', type:'float'}
			,{name:'mes', type:'string'}
			,{name:'edicion', type:'string'}
			,{name:'fecha_publicacion', type:'string'}
		]
		,listeners: {
			 'load': function(){
				filterDataSisduan(sisduanSlider);
			}
		}
	});
	
	var storeBoletinSismar = new Ext.data.JsonStore({
		url:'proceso/boletines/'
		,root:'datos'
		,sortInfo:{field:'id_boletin',direction:'ASC'}
		,totalProperty:'total'
		,baseParams:{producto:'sismar',accion:'lista'}
		,autoLoad: true
		,disableCaching: false
		,fields:[
			{name:'id_boletin', type:'float'}
			,{name:'ano', type:'float'}
			,{name:'mes', type:'string'}
			,{name:'edicion', type:'string'}
			,{name:'fecha_publicacion', type:'string'}
		]
		,listeners: {
			 'load': function(){
				filterDataSismar(sismarSlider);
			}
		}
	});
	
	function verBoletin(id, producto) {
		var src = 'http://www.sicex.com/publicaciones_'+producto+'/'+id+'/'+id+'.html'
		var dialogoBoletin = new Ext.Window({
			id:'iframewin'
			,title: ''
			,maximizable:false
			,modal:true
			,monitorResize:true
			,width:850
			,height:600
			,draggable:false
			,bodyCfg: {tag: 'iframe',src: src}
		});
		dialogoBoletin.show();
	};
	
	function filterDataSisduan(slider) {
        var values  = slider.getValues();
        storeBoletinSisduan.filter([{
            fn: function(record) {
                return record.get('ano') == values[0];
            }
        }]);        
        storeBoletinSisduan.sort('edicion', 'ASC');
    };
	function filterDataSismar(slider) {
        var values  = slider.getValues();
        storeBoletinSismar.filter([{
            fn: function(record) {
                return record.get('ano') == values[0];
            }
        }]);        
        storeBoletinSismar.sort('edicion', 'ASC');
    };
	
	this.dataViewBoletinSisduan = new Ext.DataView({
         store: storeBoletinSisduan
		,title: 'Sisduan'
        ,tpl:new Ext.XTemplate(
            '<ul>',
                '<tpl for=".">',
                    '<li id="{edicion}" class="phone">',
                        '<img width="100" height="119" src="img/boletines/sisduan/{[values.edicion]}/" />',
                        '<strong>Edición {edicion}</strong>',
                        '<span>{fecha_publicacion}</span>',
                    '</li>',
                '</tpl>',
            '</ul>'
        )        
        ,id: 'dvSisduan'
		,cls: 'dvPublicacion'
        ,itemSelector: 'li.phone'
        ,overClass   : 'phone-hover'
        ,singleSelect: true
        ,multiSelect : true
        ,autoScroll  : true
		,listeners: {
			click: {
				fn:function(a,b,c,d){
					verBoletin(c.getAttribute('id'), 'sisduan');
				}
			}
		}
    });
	
	this.dataViewBoletinSismar = new Ext.DataView({
    	 store: storeBoletinSismar
        ,tpl  : new Ext.XTemplate(
            '<ul>',
                '<tpl for=".">',
                    '<li id="{edicion}" class="phone">',
                        '<img width="100" height="119" src="img/boletines/sismar/{[values.edicion]}/" />',
                        '<strong>{edicion}</strong>',
                        '<span>{fecha_publicacion}</span>',
                    '</li>',
                '</tpl>',
            '</ul>'
        )
        ,id: 'dvSismar'
		,cls: 'dvPublicacion'
        ,itemSelector: 'li.phone'
        ,overClass   : 'phone-hover'
        ,singleSelect: true
        ,multiSelect : true
        ,autoScroll  : true
		,listeners: {
			click: {
				fn:function(a,b,c,d){					
					verBoletin(c.getAttribute('id'), 'sismar');
				}
			}
		}
    });
	var sisduanSlider = new Ext.Slider({
         width   : 300
        ,minValue: 2010
        ,maxValue: 2014        ,values  : [2014]
        ,plugins : [
            new Ext.slider.Tip({
                getText: function(thumb) {                    
                    return String.format('<b>{0}</b>', thumb.value);
                }
            })
        ]       
        ,listeners: {
            change: {
                 buffer: 70
                ,fn    : filterDataSisduan
            }
        }
    });
	var sismarSlider = new Ext.Slider({
        width   : 300
        ,minValue: 2010
        ,maxValue: 2014        ,values  : [2014]
        ,plugins : [
            new Ext.slider.Tip({
                getText: function(thumb) {                    
                    return String.format('<b>{0}</b>', thumb.value);
                }
            })
        ]        
		,listeners: {
            change: {
                 buffer: 70
                ,fn    : filterDataSismar
            }
        }
    });
	
	this.sisduanPanel = new Ext.Panel({
		 title: 'Sisduan'
		,height: 400
		,frame: true
		,border: true
		,draggable:true
		,layout: 'fit'
		,items: [this.dataViewBoletinSisduan]
		,tbar  : [
            'Año publicación:', ' '
            ,sisduanSlider
        ]
	});
	this.sismarPanel = new Ext.Panel({
		 title: 'Sismar'
		,height: 400
		,frame: true
		,border: true
		,draggable:true
		,layout: 'fit'
		,items: [this.dataViewBoletinSismar]
		,tbar  : [
            'Año publicación:', ' '
            ,sismarSlider
        ]
	});
	
	Centro.superclass.constructor.call(this,{
		 id: 'tabpanel'
		,region: 'center'
		,margins: '5 5 5 0'
		,bodyStyle: 'background-color:#333333;'
		,split: true
		,frame: false
		,autoScroll: false
		,animScroll: true
		,deferredRender: true
		,enableTabScroll: true
		,bodyBorder: false
		,hideBorders: true
		,layoutOnTabChange: true
		,buttonAlign: 'center'
		,activeTab: 0
		,listeners: {
			 'afterrender': function(container){
				filterDataSisduan(sisduanSlider);
			}
		}
		,items: [{
			 id: 'tab-bienvenidos'
			,title: 'Inicio'
			,iconCls: 'home'
			,closable: false
			,autoScroll: true
			,resizeTabs: true
			,enableTabScroll: false
			,slideDuration: .15
			,items: [{
				xtype:'portal'
				,region:'center'
				,items:[{
					columnWidth:1
					,style:'padding:10px'
					,items:[
						this.sisduanPanel
					]
				},{
					columnWidth:1
					,style:'padding:10px'
					,items:[
						this.sismarPanel
					]
				}]
			}]
		}]
	});
};
Ext.extend(Centro, Ext.ux.SlidingTabPanel);