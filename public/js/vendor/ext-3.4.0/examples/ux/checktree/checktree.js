// vim: sw=4:ts=4:nu:nospell:fdc=4
/**
 * Ext.ux.tree.CheckTreePanel Extension Example Application
 *
 * @author    Ing. Jozef Sakáloš
 * @copyright (c) 2008, by Ing. Jozef Sakáloš
 * @date      2. January 2009
 * @version   $Id: checktree.js 135 2009-02-26 09:47:10Z jozo $
 *
 * @license checktree.js is licensed under the terms of the Open Source
 * LGPL 3.0 license. Commercial use is permitted to the extent that the 
 * code/component(s) do NOT become part of another Open Source or Commercially
 * licensed development library or toolkit without explicit permission.
 * 
 * License details: http://www.gnu.org/licenses/lgpl.html
 */
 
/*global Ext, Example, WebPage */
 
Ext.ns('Example', 'WebPage');
Ext.BLANK_IMAGE_URL = 'ext/resources/images/default/s.gif';
Ext.state.Manager.setProvider(new Ext.state.CookieProvider());
Example.version = '1.0';
 
// application main entry point
Ext.onReady(function() {
    Ext.QuickTips.init();

	var adsenseHost = 
		   'checktree.localhost' === window.location.host
		|| 'checktree.extjs.eu' === window.location.host
	;
	var page = new WebPage({
		 version:Example.version
		,westContent:'west-content'
		,centerContent:'center-content'
		,adRowContent:adsenseHost ? 'adrow-content' : undefined
	});

	var ads = Ext.getBody().select('div.adsense');
	if(adsenseHost) {
		ads.removeClass('x-hidden');
	}
	else {
		ads.remove();
	}
	// {{{
	// RemoteTreePanel - to edit the tree
	var t1 = new Ext.ux.tree.RemoteTreePanel({
		 renderTo:'ct1'
		,title:'RemoteTreePanel - to edit the tree'
		,id:'t1'
		,width:300
		,height:250
		,autoScroll:true
		,rootVisible:false
		,border:true
		,bodyStyle:'background-color:#eff2f6;'
		,root:{
			 nodeType:'async'
			,id:'root'
			,text:'root'
			,expanded:true
			,uiProvider:false
		}
		,loader: {
			 url:'process-request.php'
			,baseParams:{
				 cmd:'getTree'
				,treeTable:'tree'
				,treeID:1
			}
		}
	});
	// }}}
	
	// {{{
	var t2 = new Ext.ux.tree.CheckTreePanel({
		 renderTo:'ct2'
		,title:'CheckTreePanel - default behavior'
		,id:'t2'
		,width:300
		,height:250
		,autoScroll:true
		,rootVisible:false
		,root:{
			 nodeType:'async'
			,id:'root'
			,text:'root'
			,expanded:true
			,uiProvider:false
		}
		,loader:{
			 url:'process-request.php'
			,baseParams:{
				 cmd:'getTree'
				,treeTable:'tree'
				,treeID:1
			}
		}
		,tools:[{
			 id:'refresh'
			,qtip:'Reload Tree'
			,handler:function() {
				t2.getRootNode().reload();
			}
		}]
	});

	var value2 = new Ext.form.TextField({
		 renderTo:'ct2'
		,id:'value2'
		,style:'margin-top:2px'
		,name:'value2'
		,width:300
		,listeners:{
			 focus:function() {
				this.setValue(t2.getValue());
			}
			,change:function() {
				t2.setValue(this.getValue());
			}
			,render:function() {
				Ext.QuickTips.register({
					 target:this.el
					,text:'<div><b>Focus</b> to call <b>tree.getValue()</b></div><div><b>Blur</b> to call <b>tree.setValue()</b></div>'
				})
			}
		}
	});
	// }}}
	// {{{
	var t3 = new Ext.ux.tree.CheckTreePanel({
		 renderTo:'ct3'
		,title:'CheckTreePanel - deepestOnly:true'
		,id:'t3'
		,width:300
		,height:250
		,autoScroll:true
		,rootVisible:false
		,deepestOnly:true
		,root:{
			 nodeType:'async'
			,id:'root'
			,text:'root'
			,expanded:true
			,uiProvider:false
		}
		,loader:{
			 url:'process-request.php'
			,baseParams:{
				 cmd:'getTree'
				,treeTable:'tree'
				,treeID:1
			}
		}
		,tools:[{
			 id:'refresh'
			,qtip:'Reload Tree'
			,handler:function() {
				t3.getRootNode().reload();
			}
		}]
	});

	var value3 = new Ext.form.TextField({
		 renderTo:'ct3'
		,id:'value3'
		,name:'value3'
		,style:'margin-top:2px'
		,width:300
		,listeners:{
			 focus:function() {
				this.setValue(t3.getValue());
			}
			,change:function() {
				t3.setValue(this.getValue());
			}
			,render:function() {
				Ext.QuickTips.register({
					 target:this.el
					,text:'<div><b>Focus</b> to call <b>tree.getValue()</b></div><div><b>Blur</b> to call <b>tree.setValue()</b></div>'
				})
			}
		}
	});
	// }}}
	// {{{
	var t4 = new Ext.ux.tree.CheckTreePanel({
		 renderTo:'ct4'
		,title:'CheckTreePanel - neither bubble nor cascade'
		,id:'t4'
		,width:300
		,height:250
		,autoScroll:true
		,rootVisible:false
		,bubbleCheck:'none'
		,cascadeCheck:'none'
		,root:{
			 nodeType:'async'
			,id:'root'
			,text:'root'
			,expanded:true
			,uiProvider:false
		}
		,loader:{
			 url:'process-request.php'
			,baseParams:{
				 cmd:'getTree'
				,treeTable:'tree'
				,treeID:1
			}
		}
		,tools:[{
			 id:'refresh'
			,qtip:'Reload Tree'
			,handler:function() {
				t4.getRootNode().reload();
			}
		}]
	});

	var value4 = new Ext.form.TextField({
		 renderTo:'ct4'
		,id:'value4'
		,name:'value4'
		,style:'margin-top:2px'
		,width:300
		,listeners:{
			 focus:function() {
				this.setValue(t4.getValue());
			}
			,change:function() {
				t4.setValue(this.getValue());
			}
			,render:function() {
				Ext.QuickTips.register({
					 target:this.el
					,text:'<div><b>Focus</b> to call <b>tree.getValue()</b></div><div><b>Blur</b> to call <b>tree.setValue()</b></div>'
				})
			}
		}
	});
	// }}}

}); // eo function onReady
 
// eof
